<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $chat = Chat::factory()->create();

        $messages = Message::factory(15)->create(['chat_id' => $chat->id]);

        $this->actingAs($user)->get(route('chats.show', $chat->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Chats/Show')
                    ->has('chat')
                    ->has('chat.messages', 15)
            );
    }

    public function test_show_with_deleted_messages()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $chat = Chat::factory()->create();

        $message = Message::factory()->create(['chat_id' => $chat->id]);

        $message->delete();

        $this->actingAs($user)->get(route('chats.show', $chat->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Chats/Show')
                    ->has('chat')
                    ->has('chat.messages', 1)
            );
    }
}
