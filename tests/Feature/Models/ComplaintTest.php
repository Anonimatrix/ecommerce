<?php

namespace Tests\Feature\Models;

use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_order()
    {
        $complaint = Complaint::factory()->create();

        $this->assertInstanceOf(Order::class, $complaint->order);
    }

    public function test_has_one_chat()
    {
        $complaint = Complaint::factory()->create();

        Chat::factory(['chateable_id' => $complaint->id, 'chateable_type' => Complaint::class])->create();

        $this->assertInstanceOf(Chat::class, $complaint->chat);
    }

    public function test_belongs_to_intermediary()
    {
        $complaint = Complaint::factory()->create();

        $this->assertInstanceOf(User::class, $complaint->intermediary);
    }
}
