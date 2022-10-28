<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        $chat = Chat::factory()->create();

        $data = [
            'content' => $this->faker->text(400)
        ];

        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('messages.store', $chat->id), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('messages', ['chat_id' => $chat->id]);
    }

    public function test_destroy()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $message = Message::factory()->create();

        $this->actingAs($user)->delete(route('messages.destroy', $message->id))
            ->assertSuccessful();

        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }
}
