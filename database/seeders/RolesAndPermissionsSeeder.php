<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $agentRole = Role::create(['name' => 'agent']);
        $userRole = Role::create(['name' => 'user']);

        // Create Permissions
        $manageBookingsPermission = Permission::create(['name' => 'manage bookings']);
        $manageSpacesPermission = Permission::create(['name' => 'manage spaces']);
        $managePaymentsPermission = Permission::create(['name' => 'manage payments']);
        $manageRolesPermission = Permission::create(['name' => 'manage roles']);

        // Assign Permissions to Roles (using pivot table)
        DB::table('role_permissions')->insert([
            ['role_id' => $adminRole->id, 'permission_id' => $manageBookingsPermission->id],
            ['role_id' => $adminRole->id, 'permission_id' => $manageSpacesPermission->id],
            ['role_id' => $adminRole->id, 'permission_id' => $managePaymentsPermission->id],
            ['role_id' => $adminRole->id, 'permission_id' => $manageRolesPermission->id],
            ['role_id' => $agentRole->id, 'permission_id' => $manageBookingsPermission->id],
            ['role_id' => $agentRole->id, 'permission_id' => $manageSpacesPermission->id],
            ['role_id' => $agentRole->id, 'permission_id' => $managePaymentsPermission->id],
        ]);

        // Create Users and Assign Roles (using pivot table)
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('user_roles')->insert(['user_id' => $adminUser->id, 'role_id' => $adminRole->id]);

        $agentUser = User::create([
            'name' => 'Agent User2',
            'email' => 'agent2@example.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('user_roles')->insert(['user_id' => $agentUser->id, 'role_id' => $agentRole->id]);

        $normalUser = User::create([
            'name' => 'Normal User2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('user_roles')->insert(['user_id' => $normalUser->id, 'role_id' => $userRole->id]);
    }
}