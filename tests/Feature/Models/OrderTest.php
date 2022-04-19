<?php

namespace Tests\Feature\Models;

use App\Models\Adress;
use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_product()
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Product::class, $order->product);
    }

    public function test_belongs_to_buyer()
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(User::class, $order->buyer);
    }

    public function test_belongs_to_adress()
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Adress::class, $order->adress);
    }

    public function test_has_one_chat()
    {
        $order = Order::factory()->create();

        Chat::factory(['chateable_id' => $order->id, 'chateable_type' => Order::class])->create();

        $this->assertInstanceOf(Chat::class, $order->chat);
    }

    public function test_has_one_shipp()
    {
        $order = Order::factory()->create();

        Shipp::factory(['order_id' => $order->id])->create();

        $this->assertInstanceOf(Shipp::class, $order->shipp);
    }

    public function test_has_one_payment()
    {
        $order = Order::factory()->create();

        Payment::factory(['order_id' => $order->id])->create();

        $this->assertInstanceOf(Payment::class, $order->payment);
    }

    public function test_has_one_complaint()
    {
        $order = Order::factory()->create();

        Complaint::factory(['order_id' => $order->id])->create();

        $this->assertInstanceOf(Complaint::class, $order->complaint);
    }
}
