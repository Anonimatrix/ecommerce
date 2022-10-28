<?php

namespace Tests\Feature\Models;

use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Order;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_has_one_order()
    {
        $order = Order::factory()->create();

        $chat = Chat::factory(['chateable_type' => Order::class, 'chateable_id' => $order->id])->create();

        $this->assertInstanceOf(Order::class, $chat->chateable);
    }

    public function test_has_one_complaint()
    {
        $complaint = Complaint::factory()->create();

        $chat = Chat::factory(['chateable_type' => Complaint::class, 'chateable_id' => $complaint->id])->create();

        $this->assertInstanceOf(Complaint::class, $chat->chateable);
    }

    public function test_has_many_messages()
    {
        $chat = new Chat();

        $this->assertInstanceOf(Collection::class, $chat->messages);
    }
}
