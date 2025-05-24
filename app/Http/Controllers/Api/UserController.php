<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\RoleNew;
use App\Models\User;
use Illuminate\Http\Request;
use DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = User::query();
    
        // Check if search query exists
        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }
        $users = $query->with('roles')->orderBy('id', 'desc')->paginate(50);
        // Order by latest ID and paginate
        return UserResource::collection($users);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

         // Assign role if user_type is system and role_id is provided
         if ($request->input('user_type') === 'system' && $request->has('role_id')) {
            $roleId = $request->input('role_id');
            // $role = Role::find($roleId);
             $role = RoleNew::find($roleId);

            if ($role) {
                // DB::table('user_roles')->insert([
                //     'user_id' => $user->id,
                //     'role_id' => $role->id,
                // ]);

                $userUpdate=User::where('id',$user->id)->update(['role_id'=>$request->input('role_id')]);

            }
        }

        audit_log('add', 'user', $user->id, request()->all());


        return response(new UserResource($user), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param \App\Models\User                     $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
{
    $data = $request->validated();

    if (isset($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    }

    $user->update($data);

    // Update roles if user_type is system and role_id is provided
    if ($request->input('user_type') === 'system' && $request->has('role_id')) {
        $roleId = $request->input('role_id');
        // $role = Role::find($roleId);
         $role = RoleNew::find($roleId);

        if ($role) {
            // Detach old roles (if any) and attach the new one
            // DB::table('user_roles')->where('user_id', $user->id)->delete();

            // DB::table('user_roles')->insert([
            //     'user_id' => $user->id,
            //     'role_id' => $role->id,
            // ]);

            $userUpdate=User::where('id',$user->id)->update(['role_id'=>$request->input('role_id')]);

        }
    }

     audit_log('edit', 'user', $user->id, request()->all());

    return new UserResource($user->fresh('roles.permissions')); // ensure roles & permissions are reloaded
}


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
         $userId = $user->id; // Capture before delete

        $user->delete();

        // After deletion, log the audit
        audit_log('delete', 'user', $userId, [
            'deleted_user_id' => $userId,
            'deleted_user_name' => $user->name ?? null, // If you have 'name' column
            'deleted_user_email' => $user->email ?? null, // If you have 'email' column
        ]);

    return response("", 204);
    }
}
