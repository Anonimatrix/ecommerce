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
        //Permissions roles
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'remove roles']);
        Permission::create(['name' => 'view roles']);

        //Permissions products user
        Permission::create(['name' => 'create own products']);
        Permission::create(['name' => 'pause own products']);
        Permission::create(['name' => 'edit own products']);
        Permission::create(['name' => 'delete own products']);

        //Permissions products admin
        Permission::create(['name' => 'create foreign products']);
        Permission::create(['name' => 'pause foreign products']);
        Permission::create(['name' => 'edit foreign products']);
        Permission::create(['name' => 'delete foreign products']);
        Permission::create(['name' => 'force delete foreign products']);
        Permission::create(['name' => 'restore foreign products']);

        //Permissions categories
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);

        //Permissions subcategories
        Permission::create(['name' => 'create subcategories']);
        Permission::create(['name' => 'edit subcategories']);
        Permission::create(['name' => 'delete subcategories']);

        $roleManager = Role::create(['name' => 'role-manager']);
        $roleUser = Role::create(['name' => 'user']);
        $roleAdmin = Role::create(['name' => 'admin']);

        $permissionsManager = Permission::where('name', 'LIKE', '%roles%')->get();
        $permissionsOwnProducts = Permission::where('name', 'LIKE', '%own products%')->get();
        $permissionsForeignProducts = Permission::where('name', 'LIKE', '%foreign products%')->get();
        $permissionsCategories = Permission::where('name', 'LIKE', '%categories%')->get();
        $permissionsSubcategories = Permission::where('name', 'LIKE', '%subcategories%')->get();

        $roleManager->syncPermissions($permissionsManager);
        $roleAdmin->syncPermissions([$permissionsManager, $permissionsOwnProducts, $permissionsForeignProducts, $permissionsCategories, $permissionsSubcategories]);
        $roleUser->syncPermissions($permissionsOwnProducts);
    }
}
