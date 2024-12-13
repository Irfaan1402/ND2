<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $viewMembersPermission = Permission::create(['name' => 'view members']);
        $viewMeetingsPermission = Permission::create(['name' => 'view meetings']);
        $viewPostsPermission = Permission::create(['name' => 'view posts']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($viewMembersPermission, $viewMeetingsPermission);

        // Assign role to user
        $admin = User::find(1); // Example user with ID 1
        $admin->assignRole('admin');

        $user = User::find(2);
        $user->assignRole('user');
    }
}
