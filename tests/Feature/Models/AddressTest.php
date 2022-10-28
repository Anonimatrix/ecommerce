<?php

namespace Tests\Feature\Models;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_user()
    {
        $address = Address::factory()->create();

        $this->assertInstanceOf(User::class, $address->user);
    }

    public function test_has_many_orders()
    {
        $address = new Address();

        $this->assertInstanceOf(Collection::class, $address->orders);
    }
}
