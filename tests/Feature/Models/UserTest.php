<?php

namespace Tests\Feature\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_role()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Role::class, $user->role);
    }

    public function test_has_many_products()
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class, $user->products);
    }

    public function test_has_many_adresses()
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class, $user->adresses);
    }

    public function test_has_many_searches()
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class, $user->searches);
    }


    public function test_has_many_orders()
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class, $user->orders);
    }
}
