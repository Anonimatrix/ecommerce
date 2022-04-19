<?php

namespace Tests\Feature\Models;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_chat()
    {
        $message = Message::factory()->create();

        $this->assertInstanceOf(Chat::class, $message->chat);
    }
}
