<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Space;
// use App\Models\User;
use App\Models\SpaceCategory;
// use App\Models\Booking;
use App\Models\Payment;
// use App\Models\SpaceCategory;
use App\Http\Requests\SpaceRequest;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SpaceController extends Controller
{
    // Get all spaces
    // public function index(Request $request)
    // {
    //     $query = Space::query();


    //     if ($request->has('search')) {
    //         $search = $request->input('search');
    //         $query->where('name_of_person_collection_data', 'LIKE', "%{$search}%")
    //             ->orWhere('name_of_advertise_agent_company_or_person', 'LIKE', "%{$search}%");
    //     }
    //     return response()->json(
    //         $query->orderBy('id', 'desc')->paginate(10),
    //         Response::HTTP_OK
    //     );
    // }

    // public function index(Request $request)
    // {
    //     $query = Space::with('bookings:space_id,status'); // Eager load booking status

    //     if ($request->has('search')) {
    //         $search = $request->input('search');
    //         $query->where('name_of_person_collection_data', 'LIKE', "%{$search}%")
    //             ->orWhere('name_of_advertise_agent_company_or_person', 'LIKE', "%{$search}%");
    //     }

    //     return response()->json(
    //         $query->orderBy('id', 'desc')->paginate(10),
    //         Response::HTTP_OK
    //     );
    // }

public function index(Request $request)
{

    $createdUserId = auth()->id();

    $adminRole = auth()->user()->role_id == 2;


    if ($adminRole) {
        $query = Space::with('bookings:space_id,status', 'createdByUser');
    }else {
        $query = Space::with('bookings:space_id,status', 'createdByUser')
        ->where('created_user_id', $createdUserId);
    }
   
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name_of_person_collection_data', 'LIKE', "%{$search}%");
        });
    }

    // Ensure date filtering is working
    if ($request->filled('from_date') && $request->has('to_date')) {
        $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate = Carbon::parse($request->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $query->whereBetween('data_collection_date', [$fromDate, $toDate]);
    }

    if ($request->filled('agent')) {
       $agent = $request->input('agent');
        $query->where(function ($q) use ($agent) {
            $q->where('name_of_advertise_agent_company_or_person', 'LIKE', "%{$agent}%");
        });
    }

    // Clone query to log SQL before pagination
    // $clonedQuery = clone $query;
    // Log::info('SQL Query: ' . $clonedQuery->toSql(), $clonedQuery->getBindings());

    // Execute the paginated query
    $data = $query->orderBy('id', 'desc')->paginate(50);

    return response()->json($data, Response::HTTP_OK);
}

    public function getAgentList(){
        $agentList = User::where('user_type','agent')->get();
        return response()->json($agentList, 200);
    }


    // Create a new space
    // Create a new space
    public function store(SpaceRequest $request)
    {
        $data = $request->validated();

        // Fetch rate from space_categories table
        $category = SpaceCategory::find($data['space_cat_id']);
        if (!$category) {
            return response()->json(['message' => 'Invalid Space Category'], Response::HTTP_BAD_REQUEST);
        }

        $data['rate'] = $category->rate; // Assign rate

        $space = Space::create($data);
        // Get the inserted ID
         $insertedId = $space->id;

        audit_log('add', 'space', $space->id, request()->all());


        return response()->json([ 'id' => $insertedId,'message' => 'Space created successfully', 'data' => $space], Response::HTTP_CREATED);
    }

    // Show a single space
    public function show(Space $space)
    {
        return response()->json(
            $space->load('category','createdByUser','bookings'), // Load the category relationship
            Response::HTTP_OK
        );
    }


    // Update an existing space
    public function update(SpaceRequest $request, Space $space)
    {
        $data = $request->validated();

        // Fetch rate from space_categories table if space_cat_id is updated
        if (isset($data['space_cat_id'])) {
            $category = SpaceCategory::find($data['space_cat_id']);
            if ($category) {
                $data['rate'] = $category->rate;
            }
        }

        $space->update($data);
        audit_log('edit', 'space', $space->id, request()->all());

        return response()->json(['message' => 'Space updated successfully', 'data' => $space], Response::HTTP_OK);
    }

    // Delete a space
    // public function destroy(Space $space)
    // {
    //     $space->delete();
    //     return response()->json(['message' => 'Space deleted successfully'], Response::HTTP_NO_CONTENT);
    // }

    public function destroy(Space $space)
    {
        $spaceId = $space->id;
        $spaceData = $space->toArray(); // Optional: capture full space details

        $space->delete();

        audit_log('delete', 'space', $spaceId, [
            'deleted_space_id' => $spaceId,
            'deleted_space_data' => $spaceData,
        ]);

        return response()->json(['message' => 'Space deleted successfully'], Response::HTTP_NO_CONTENT);
    }




    public function spacesUpadte(Request $request){
         // Handle image uploads
        $upd=[];
    if ($request->hasFile('image_1')) {
        $imagePath = $request->file('image_1')->store('uploads', 'public');
        $upd['image_1'] = $imagePath;
    }
    if ($request->hasFile('image_2')) {
        $imagePath = $request->file('image_2')->store('uploads', 'public');
        $upd['image_2'] = $imagePath;
    }
    if ($request->hasFile('image_3')) {
        $imagePath = $request->file('image_3')->store('uploads', 'public');
        $upd['image_3'] = $imagePath;
    }
    $u=Space::where('id',$request->id)->update($upd);

      audit_log('edit', 'space', $request->id, [
            'edited_space_id' => $request->id,
            'form_data' => json_encode($request->all()),
        ]);


         return response()->json(['message' => 'Space updated successfully', 'data' => $u], Response::HTTP_OK);
    }




    public function counts()
    {
        return response()->json([
            'spaces_count' => Space::count(),
            'users_count' => User::count(),
            'space_categories_count' => SpaceCategory::count(),
            'bookings_count' => Booking::count(),
            'payments_count' => Payment::count(),
        ]);
    }


    
}
