<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Mail\OTPMail;
use Mail;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provided email or password is incorrect'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
         
        // return response(compact('user', 'token'));
        // ALTER TABLE `users` ADD `otp` VARCHAR(255) NULL AFTER `updated_at`;

         // Generate OTP
         $otp = rand(100000, 999999);

        // Send OTP via email (directly from the controller)
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to("jeetbasak54@gmail.com")
                    ->subject('Your OTP Code');
        });
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your OTP Code');
        });

        //update otp on user table
        $updt=User::where('id',$user->id)->update(['otp'=>$otp]);

        return response([
            'u'=>$updt,
            'user'=>$user,
            'message' => 'OTP has been sent to your email. Please verify.'
        ]);
    }




public function verifyOtp(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6'
    ]);

    // Find user with the given email and OTP
    $user = User::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

    // Check if user exists
    if (!$user) {
        return response(['message' => 'Invalid or expired OTP'], 422);
    }

    // Clear OTP after successful verification
    $updt=User::where('id',$user->id)->update(['otp'=>null]);

    // Log in the user manually
    Auth::login($user);

    // Generate API token
    $token = $user->createToken('main')->plainTextToken;

    return response(compact('user', 'token'));
}





    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response('', 204);
    }

    public function createRole(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create($validatedData);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function allRolesWithPermissions()
    {
        $roles = Role::with('permissions')->get();

        return response()->json(['roles' => $roles]);
    }

    public function assignRole(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::find($validatedData['user_id']);
        $role = Role::find($validatedData['role_id']);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function assignPermissionsToRole(Request $request)
    {
        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::find($validatedData['role_id']);

        foreach ($validatedData['permission_ids'] as $permissionId) {
            // Check if the record already exists
            $existingRecord = DB::table('role_permissions')
                ->where('role_id', $role->id)
                ->where('permission_id', $permissionId)
                ->first();

            if (!$existingRecord) {
                // Insert only if the record doesn't exist
                DB::table('role_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        return response()->json(['message' => 'Permissions assigned to role successfully']);
    }

    public function allPermissions()
    {
        $permissions = Permission::all();

        return response()->json(['permissions' => $permissions]);
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        // Detach related permissions
        $role->permissions()->detach();

        // Remove role assignments from users
        DB::table('user_roles')->where('role_id', $id)->delete();

        // Delete the role
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
