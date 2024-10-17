<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view-tasks',
            'create-tasks',
            'update-tasks',
            'reassign-tasks',
            'add-comments',
            'add-attachments',
            'assign-tasks',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $adminRole->permissions()->sync(Permission::all());
        $userRole->permissions()->sync(Permission::whereIn('name', ['view-tasks', 'add-comments', 'add-attachments'])->get());
    }
}