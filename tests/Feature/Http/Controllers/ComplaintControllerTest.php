<?php

namespace Tests\Feature\Http\Controllers;

use App\Statuses\ComplaintStatus;
use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Statuses\OrderStatus;
use App\Statuses\PaymentStatus;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ComplaintControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_create()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)->get(route('complaints.create'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Complaints/Create')
            );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_with_images()
    {
        Storage::fake('photos');
        $countOfFilesToUpload = 3;
        $files = [];

        for ($i = 0; $i < $countOfFilesToUpload; $i++) {
            $filename =  "photo-$i.jpg";
            array_push($files, UploadedFile::fake()->image($filename));
        }

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('user');

        $order = Order::factory()->create();

        $data = [
            'order_id' => $order->id,
            'reason' => 'failed product',
            'photos' => $files
        ];

        $this->actingAs($user)->post(route('complaints.store'), $data)
            ->assertStatus(302);

        foreach ($files as $file) {
            $filename = $file->hashName();
            Storage::disk('photos')->exists("complaints/$filename");
        }

        $complaint = Complaint::find(1);

        $this->assertCount($countOfFilesToUpload, $complaint->photos);

        $this->assertDatabaseHas('complaints', ['order_id' => $data['order_id'], 'status' => ComplaintStatus::STARTED]);

        $this->assertDatabaseHas('chats', ['chateable_id' => $complaint->id]);
    }

    public function test_index()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        Complaint::factory(16)->create();

        $this->actingAs($user)->get(route('complaints.index'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Complaints/Index')
                    ->has('pagination.data', 15)
            );
    }

    public function test_take()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $complaint = Complaint::factory()->create();

        $this->actingAs($user)->patch(route('complaints.take', $complaint->id))
            ->assertSuccessful();

        $this->assertDatabaseHas('complaints', ['status' => ComplaintStatus::TAKEN]);
    }

    public function test_show()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $complaint = Complaint::factory()->create();

        $this->actingAs($user)->get(route('complaints.show', $complaint->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Complaints/Show')
                    ->has('complaint')
                    ->has('complaint.order.chat')
                    ->has('complaint.chat')
            );
    }

    public function test_show_chat()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $complaint = Complaint::factory()->create();

        $chat = Chat::where('chateable_id', $complaint->id)->first();

        $this->actingAs($user)->get(route('chats.show', $chat->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Chats/Show')
                    ->has('chat')
            );
    }

    public function test_cancel()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $complaint = Complaint::factory()->create();

        $this->actingAs($user)->patch(route('complaints.cancel', $complaint->id))
            ->assertJson(['status' => 'canceled']);

        $this->assertDatabaseHas('orders', ['status' => $complaint->order->status]);
        $this->assertDatabaseHas('complaints', ['status' => ComplaintStatus::CANCELED]);
    }

    public function test_refund()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();
        $user->assignRole('admin');

        $product = Product::factory()->create(['price' => 500]);

        $order = Order::factory()->create(['buyer_id' => $user->id, 'product_id' => $product->id]);

        $payment = Payment::factory()->create(['status' => PaymentStatus::COMPLETED, 'order_id' => $order->id, 'amount' => $product->price]);

        $complaint = Complaint::factory()->create(['order_id' => $order->id]);

        $this->assertEquals(0, $user->money);

        $this->actingAs($user)->patch(route('complaints.refund', $complaint->id))
            ->assertJson(['status' => 'refunded']);

        $this->assertDatabaseHas('orders', ['status' => OrderStatus::CANCELED]);
        $this->assertDatabaseHas('complaints', ['status' => ComplaintStatus::SOLVED]);
        $this->assertDatabaseHas('payments', ['status' => PaymentStatus::REFUNDED]);

        $this->assertEquals($product->price, $user->money);
    }
}
