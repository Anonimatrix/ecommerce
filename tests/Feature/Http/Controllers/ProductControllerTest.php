<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\Subcategorie;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_authorization()
    {
        $product = Product::factory()->create();

        $this->get(route('products.index'))->assertSuccessful();
        $this->get(route('products.show', $product->id))->assertSuccessful();
        $this->get(route('products.search'), ['q' => 'foo'])->assertSuccessful();
        $this->post(route('products.store'), [])->assertRedirect(route('login'));
        $this->put(route('products.update', $product->id), [])->assertRedirect(route('login'));
        $this->patch(route('products.pause', $product->id))->assertRedirect(route('login'));
        $this->delete(route('products.destroy', $product->id))->assertRedirect(route('login'));
        $this->get(route('products.edit', $product->id))->assertRedirect(route('login'));
        $this->get(route('products.create'))->assertRedirect(route('login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        Product::factory(15)->create();

        $this->get(route('products.index'))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Products/Index')
                    ->has('pagination.data', 15)
            );
    }

    public function test_search()
    {
        $product = Product::factory()->create();

        $tag = Tag::factory()->create(['title' => 'minecraft']);

        $product->tags()->sync([$tag->id]);

        $this->get(route('products.search', ['q' => $tag->title]))
            ->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Products/Search')
                    ->has('products', 1)
            );
    }

    public function test_store()
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
        $tag = Tag::factory()->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
            'price' => $this->faker->numberBetween(0, 100),
            'photos' => $files,
            'stock' => $this->faker->numberBetween(1, 20),
            'paused_at' => null,
            'tags_titles' => [$tag->title]
        ];

        $this->actingAs($user)->post(route('products.store'), $data)
            ->assertStatus(302);

        foreach ($files as $file) {
            $filename = $file->hashName();
            Storage::disk('photos')->exists("products/$filename");
        }

        $product = Product::find(1);

        $this->assertCount($countOfFilesToUpload, $product->photos);

        $this->assertDatabaseHas('products', ['title' => $data['title']]);
    }

    public function test_store_validation()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
        ];

        $this->actingAs($user)->post(route('products.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['price']);

        $this->assertDatabaseMissing('products', $data);
    }

    public function test_pause()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory(['user_id' => $user->id])->create();

        $this->actingAs($user)->patch(route('products.pause', $product))
            ->assertStatus(302);

        $productAfterPause = Product::find($product->id);

        $this->assertNotNull($productAfterPause->paused_at);
    }

    public function test_pause_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $this->actingAs($user)->patch(route('products.pause', $product))
            ->assertStatus(403);

        $productAfterPause = Product::find($product->id);

        $this->assertNull($productAfterPause->paused_at);
    }

    public function test_update()
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

        $product = Product::factory(['user_id' => $user->id])->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
            'price' => $this->faker->numberBetween(0, 100),
            'photos' => $files,
            'stock' => $this->faker->numberBetween(1, 20),
            'paused_at' => null
        ];

        $this->actingAs($user)->put(route('products.update', $product), $data)
            ->assertStatus(302);

        foreach ($files as $file) {
            $filename = $file->hashName();
            Storage::disk('photos')->exists("products/$filename");
        }

        $product = Product::find(1);

        $this->assertCount($countOfFilesToUpload, $product->photos);

        $this->assertDatabaseHas('products', ['title' => $data['title']]);

        $this->assertDatabaseMissing('products', $product->toArray());
    }

    public function test_update_without_photos()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory(['user_id' => $user->id])->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
            'price' => $this->faker->numberBetween(0, 100),
            'stock' => $this->faker->numberBetween(1, 20),
            'paused_at' => null
        ];

        $this->actingAs($user)->put(route('products.update', $product), $data)
            ->assertStatus(302);

        $this->assertDatabaseHas('products', ['title' => $data['title']]);

        $this->assertDatabaseMissing('products', $product->toArray());
    }

    public function test_update_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
            'price' => $this->faker->numberBetween(0, 100),
            'stock' => $this->faker->numberBetween(1, 20),
            'paused_at' => null
        ];

        $this->actingAs($user)->put(route('products.update', $product), $data)
            ->assertStatus(403);
    }

    public function test_update_validation()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'subcategorie_id' => Subcategorie::factory()->create(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
        ];

        $this->actingAs($user)->put(route('products.update', $product), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['price']);

        $this->assertDatabaseMissing('products', $data);
    }

    public function test_destroy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory(['user_id' => $user->id])->create();

        $this->actingAs($user)->delete(route('products.destroy', $product))
            ->assertStatus(302);

        $this->assertDatabaseMissing('products', $product->toArray());
    }

    public function test_destroy_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $this->actingAs($user)->delete(route('products.destroy', $product))
            ->assertStatus(403);
    }

    public function test_create()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('products.create'))
            ->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Products/Create')
            );
    }

    public function test_edit()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory(['user_id' => $user->id])->create();

        $this->actingAs($user)->get(route('products.edit', $product))
            ->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Products/Edit')
                    ->has('product')
            );
    }

    public function test_edit_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user 
         */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $this->actingAs($user)->get(route('products.edit', $product))
            ->assertStatus(403);
    }

    public function test_show()
    {
        $product = Product::factory()->create();

        $this->get(route('products.show', $product))
            ->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Products/Show')
                    ->has('product')
            );
    }
}
