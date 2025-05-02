<?php

namespace App\Http\Controllers\Api;

use App\Models\RoleNew;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\AuditModel;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = RoleNew::with('permissions:id,module,action')
                          ->select(['id', 'name', 'created_at'])
                          ->orderBy('id','desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles_new,name'
            ]);

            $role = RoleNew::create($validated);

            audit_log('add', 'role', $role->id, request()->all());

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Role created successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $role = RoleNew::with('permissions:id,module,action')
                         ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'integer|exists:permissions_new,id'
            ]);

            $role = RoleNew::findOrFail($id);

            DB::transaction(function () use ($role, $validated) {
                $role->permissions()->sync($validated['permission_ids']);
            });


            audit_log('add', 'role', $role->id, [
            'permissions' => json_encode($request->all()),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully',
                'data' => $role->load('permissions:id,module,action')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $role = RoleNew::findOrFail($id);

            DB::transaction(function () use ($role) {
                $role->permissions()->detach();
                $role->delete();
            });

             audit_log('add', 'role', $role->id, [
                'deleted_id' => $role->id,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role',
                'error' => $e->getMessage()
            ], 500);
        }
    }







public function getUserPermissions(Request $request)
{
    try {
        $user = auth()->user(); // Assuming you're using auth

        if (!$user || !$user->role_id) {
            return response()->json([
                'success' => false,
                'message' => 'User or role not found.'
            ], 404);
        }

        // Load role and its permissions
        $role = $user->role()->with('permissions:id,module,action')->first();

        return response()->json([
            'success' => true,
            'data' => $role->permissions
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch permissions',
            'error' => $e->getMessage()
        ], 500);
    }
}




public function all_logs(Request $request)
{
    try {
        $user = auth()->user(); // Assuming you're using auth

        $query = AuditModel::query()->orderBy('id', 'desc')->with('userData');

        // Apply filters if they exist in the request
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('entity_id') && $request->entity_id != '') {
            $query->where('entity_id', $request->entity_id);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('ip_address', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('userData', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Get paginated results
        $perPage = $request->per_page ?? 10;
        $allLogs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $allLogs
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch audit logs',
            'error' => $e->getMessage()
        ], 500);
    }
}












}