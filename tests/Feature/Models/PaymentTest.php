<?php

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_order()
    {
        $payment = Payment::factory()->create();

        $this->assertInstanceOf(Order::class, $payment->order);
    }
}
