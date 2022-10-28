<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;


class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        $user = User::factory()->create();

        $user->assignRole('role-manager');

        $data = [
            'name' => 'role',
            'permissions_ids' => [Permission::create(['name' => 'create products'])]
        ];

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->post(route('roles.store'), $data)
            ->assertStatus(302);

        $this->assertDatabaseHas('roles', ['name' => 'role']);
    }

    public function test_store_policy()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'role',
            'permissions_ids' => [Permission::create(['name' => 'create products'])->id]
        ];

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->post(route('roles.store'), $data)
            ->assertStatus(403);

        $this->assertDatabaseMissing('roles', ['name' => 'role']);
    }

    public function test_create()
    {
        $user = User::factory()->create();

        $user->assignRole('role-manager');

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->get(route('roles.create'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Role/Create')
                    ->has('permissions')
            );
    }

    public function test_destroy()
    {


        $user = User::factory()->create();

        $user->assignRole('role-manager');

        $role = Role::create(['name' => 'role']);

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->delete(route('roles.destroy', $role->id))
            ->assertStatus(302);

        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    public function test_destroy_policy()
    {
        $role = Role::create(['name' => 'role']);

        $this->delete(route('roles.destroy', $role->id))
            ->assertStatus(403);

        $this->assertDatabaseHas('roles', ['name' => 'role']);
    }

    public function test_index()
    {
        $user = User::factory()->create();

        $user->assignRole('role-manager');

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->get(route('roles.index'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Role/Index')
                    ->has('roles.data', Role::all()->count())
            );
    }

    public function test_index_policy()
    {


        $this->get(route('roles.index'))
            ->assertStatus(403);
    }

    public function test_update()
    {


        $user = User::factory()->create();

        $user->assignRole('role-manager');

        $role = Role::create(['name' => 'role']);

        $data = [
            'name' => 'role1',
            'permissions_ids' => [Permission::create(['name' => 'create products'])]
        ];

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->put(route('roles.update', $role->id), $data)
            ->assertStatus(302);

        $this->assertDatabaseHas('roles', ['name' => 'role1']);
    }

    public function test_update_policy()
    {


        $role = Role::create(['name' => 'role']);

        $data = [
            'name' => 'role1',
            'permissions_ids' => [Permission::create(['name' => 'create products'])]
        ];

        $this->put(route('roles.update', $role->id), $data)
            ->assertStatus(403);

        $this->assertDatabaseMissing('roles', ['name' => 'role1']);
    }

    public function test_edit()
    {
        $user = User::factory()->create();

        $user->assignRole('role-manager');

        $role = Role::create(['name' => 'role']);

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $this->actingAs($user)->get(route('roles.edit', $role->id))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Role/Edit')
                    ->has('role')
            );
    }

    public function test_edit_policy()
    {
        $role = Role::create(['name' => 'role']);

        $this->get(route('roles.edit', $role->id))
            ->assertStatus(403);
    }
}
