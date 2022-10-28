<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Statuses\OrderStatus;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    //TODO Validate policies and access
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_get_paginated_users()
    {
        /**
         * @var \App\Models\User $authenticatedUser
         */
        $authenticatedUser = User::factory()->create();

        User::factory(25)->create();

        $this->actingAs($authenticatedUser)->get(route('users.index'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Users/Index')
                    ->has('pagination.data', 20)
            );
    }

    public function test_get_paginated_users_with_search()
    {
        /**
         * @var \App\Models\User $authenticatedUser
         */
        $authenticatedUser = User::factory(['name' => 'tom'])->create();

        User::factory(25)->create(['name' => 'wat', 'last_name' => 'moon']);

        $this->actingAs($authenticatedUser)->get(route('users.index', ['q' => 'tom']))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Users/Index')
                    ->has('pagination.data', 1)
            );
    }

    public function test_assign_roles()
    {
        /**
         * @var \App\Models\User $admin
         */
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $user = User::factory()->create();

        $user->assignRole('user');

        $data = [
            'roles' => [
                [
                    'name' => 'role-manager', 'assigned' => true
                ]
            ]
        ];

        $this->actingAs($admin)->patch(route('users.assign-roles', $user->id), $data)
            ->assertSuccessful();

        $afterAssignRolesUser = User::find($user->id);

        $this->assertTrue($afterAssignRolesUser->hasRole('role-manager'));
    }

    public function test_assign_roles_page()
    {
        /**
         * @var \App\Models\User $admin
         */
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $user = User::factory()->create();

        $user->assignRole('user');

        $this->actingAs($admin)->get(route('users.assign-roles-page', $user->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Users/AssignRoles')
                    ->has('user')
                    ->has('roles')
            );
    }

    public function test_get_money_sell()
    {
        /**
         * @var \App\Models\User $user
         */

        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        //Creating real order payed
        $order = Order::factory()->create(['status' => OrderStatus::COMPLETED, 'product_id' => $product->id]);

        $payment = Payment::factory()->create(['order_id' => $order->id]);

        //Creating refunded order
        $order = Order::factory()->create(['status' => OrderStatus::COMPLETED, 'product_id' => $product->id]);

        Payment::factory()->create(['order_id' => $order->id, 'status' => 'refunded']);

        $this->actingAs($user)->get(route('user.money'))
            ->assertSuccessful()
            ->assertJson(['money' => $payment->amount]);
    }

    public function test_get_money_refunded()
    {
        /**
         * @var \App\Models\User $buyer
         */

        $buyer = User::factory()->create();

        //Creating real order payed
        $order = Order::factory()->create(['status' => OrderStatus::CANCELED, 'buyer_id' => $buyer->id]);

        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => 'refunded']);

        $this->actingAs($buyer)->get(route('user.money'))
            ->assertSuccessful()
            ->assertJson(['money' => $payment->amount]);

        $this->actingAs($order->product->user)->get(route('user.money'))
            ->assertSuccessful()
            ->assertJson(['money' => 0]);
    }
}
