<?php

namespace Tests\Feature\Models;

use App\Models\Adress;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_user()
    {
        $adress = Adress::factory()->create();

        $this->assertInstanceOf(User::class, $adress->user);
    }

    public function test_has_many_orders()
    {
        $adress = new Adress();

        $this->assertInstanceOf(Collection::class, $adress->orders);
    }
}
