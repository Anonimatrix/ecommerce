<?php

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Shipp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShippTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_order()
    {
        $shipp = Shipp::factory()->create();

        $this->assertInstanceOf(Order::class, $shipp->order);
    }
}
