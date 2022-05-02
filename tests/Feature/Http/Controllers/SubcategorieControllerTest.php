<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categorie;
use App\Models\Product;
use App\Models\Subcategorie;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class SubcategorieControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_show()
    {
        $subcategorie = Subcategorie::factory()->create();

        Product::factory(10)->create(['subcategorie_id' => $subcategorie->id]);

        $this->get(route('subcategories.show', 1))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Subcategories/Show')
                    ->has('products.data', 10)
            );
    }

    public function test_create()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $this->actingAs($user)->get(route('subcategories.create'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Subcategories/Create')
            );
    }

    public function test_store()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $data = [
            'title' => $this->faker->sentence(),
            'categorie_id' => Categorie::factory()->create()->id
        ];

        $this->actingAs($user)->post(route('subcategories.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('subcategories', $data);
    }

    public function test_store_validation()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $data = [
            // 'title' => $this->faker->sentence(),
            'categorie_id' => Categorie::factory()->create()
        ];

        $this->actingAs($user)->post(route('subcategories.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('title');

        $this->assertDatabaseMissing('subcategories', $data);
    }

    public function test_edit()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $subcategorie = Subcategorie::factory()->create();

        $this->actingAs($user)->get(route('subcategories.edit', $subcategorie->id))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Subcategories/Edit')
                    ->has('subcategorie')
            );
    }

    public function test_update()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $subcategorie = Subcategorie::factory()->create();

        $data = [
            'title' => $this->faker->sentence(),
            'categorie_id' => Categorie::factory()->create()->id
        ];

        $this->actingAs($user)->put(route('subcategories.update', $subcategorie->id), $data)
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('subcategories', $data);
    }

    public function test_update_validation()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $subcategorie = Subcategorie::factory()->create();

        $data = [
            // 'title' => $this->faker->sentence(),
            'categorie_id' => Categorie::factory()->create()
        ];

        $this->actingAs($user)->put(route('subcategories.update', $subcategorie->id), $data)
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('title');

        $this->assertDatabaseHas('subcategories', ['categorie_id' => $subcategorie->getAttribute('categorie_id')]);
        $this->assertDatabaseMissing('subcategories', $data);
    }

    public function test_destroy()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $subcategorie = Subcategorie::factory()->create();

        $this->actingAs($user)->delete(route('subcategories.destroy', $subcategorie->id))
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertSoftDeleted('subcategories', $subcategorie->getAttributes());
    }
}
