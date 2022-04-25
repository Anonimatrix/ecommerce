<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'remove roles']);
        Permission::create(['name' => 'view roles']);

        $roleManager = Role::create(['name' => 'role-manager']);
        $roleUser = Role::create(['name' => 'user']);
        $roleAdmin = Role::create(['name' => 'admin']);

        $permissionsManager = Permission::where('name', 'LIKE', '%roles%')->get();

        $roleManager->syncPermissions($permissionsManager);
        $roleAdmin->syncPermissions($permissionsManager);
    }
}
