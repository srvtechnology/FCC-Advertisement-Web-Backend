<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // List all bookings for authenticated user
    // public function index()
    // {
    //     return response()->json(Auth::user()->bookings()->with('space')->get());
    // }
        public function index(Request $request)
    {
        $query = Booking::with('user','createdByUser', 'space.category','payment','payments')->orderBy('id', 'desc')->withSum('payments', 'payment_amount_1');
        // Filter by User Name
        // if ($request->has('user_name')) {
        //     $query->whereHas('user', function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->user_name . '%');
        //     });
        // }

        // // Filter by Period
        // if ($request->has('period')) {
        //     $query->where('period', $request->period);
        // }

      if ($request->filled('agent_name')) {
            $query->whereHas('space', function ($q) use ($request) {
                $q->where('name_of_advertise_agent_company_or_person', 'LIKE', '%' . $request->agent_name . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) { 
            $fromDate = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s'); // Start of the day
            $toDate = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s'); // End of the day

            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }
        // Clone query to log SQL before pagination
            $clonedQuery = clone $query;
            // Log::info('SQL Query: ' . $clonedQuery->toSql(), $clonedQuery->getBindings());

            return response()->json($query->paginate(50)); // Paginated response
        }






    public function show(Request $request, $id){
        $query = Booking::with('user','space.category','createdByUser')->find($id);

        if (!$query) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($query);;
    }

    // Create a new booking
   public function store(Request $request)
{
    // Find the user by name
    $user = User::where('name', $request->user_name)->first();

    // Debugging: Check if user is found
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Validate request
    $request->validate([
        'space_id' => 'required|exists:spaces,id',
        'start_date' => 'required|date|after_or_equal:today',
        'period' => 'required',
    ]);

    // Create the booking
    $booking = Booking::create([
        'user_id' => $user->id, // Use the correct user ID
        'space_id' => $request->space_id,
        'start_date' => $request->start_date,
        'period' => $request->period,
        'end_date' => $request->end_date,
        'customer_name' => $request->customer_name,
        'customer_email' => $request->customer_email,
        'mobile' => $request->mobile,
        'address' => $request->address,
        'status' => 'pending',
        'description_of_ad' => $request->description_of_ad
    ]);

     audit_log('add', 'booking', $booking->id, request()->all());

    return response()->json(['message' => 'Booking successful', 'booking' => $booking], 201);
}


    // Update a booking (Admin only)
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

         audit_log('edit', 'booking', $booking->id, request()->all());

        return response()->json(['message' => 'Booking status updated successfully', 'booking' => $booking]);
    }

    // Cancel a booking
    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         audit_log('delete', 'booking', $booking->id,  [
            'deleted_booking_id' => $booking->id,
        ]);

        $booking->delete();
        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
