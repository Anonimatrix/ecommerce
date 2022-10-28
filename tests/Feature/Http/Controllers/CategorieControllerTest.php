<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categorie;
use App\Models\Subcategorie;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategorieControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_index()
    {
        //Create subcategorie with Categories
        Subcategorie::factory(10)->create();

        $this->get(route('categories.index'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Index')
                    ->has('categories', 10)
                    ->has('categories.1.subcategories')
            );
    }

    public function test_search()
    {
        $categorie = Categorie::factory(10)->create();

        $this->get(route('categories.index', ['q' => $categorie[0]->title]))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Index')
                    ->has('categories', 1)
            );
    }

    public function test_create()
    {
        $this->get(route('categories.create'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Create')
            );
    }

    public function test_store()
    {
        $categorie = [
            'title' => $this->faker->sentence
        ];

        $this->post(route('categories.store'), $categorie)
            ->assertRedirect();

        $this->assertDatabaseHas('categories', $categorie);
    }

    public function test_edit()
    {
        $categorie = Categorie::factory()->create();

        $this->get(route('categories.edit', $categorie))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Edit')
                    ->has('categorie')
            );
    }

    public function test_update()
    {
        $categorie = Categorie::factory()->create();

        $data = [
            'title' => $this->faker->sentence
        ];

        $this->put(route('categories.update', $categorie->id), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_destroy()
    {
        $categorie = Categorie::factory()->create();

        $this->assertNotSoftDeleted('categories', $categorie->only('id', 'title'));

        $this->delete(route('categories.destroy', $categorie->id))
            ->assertSuccessful();

        $this->assertSoftDeleted('categories', $categorie->only('id', 'title'));
    }

    public function test_destroy_validation()
    {
        $subcategorie = Subcategorie::factory()->create();

        $this->assertNotSoftDeleted('categories', $subcategorie->categorie->only('id', 'title'));

        $this->delete(route('categories.destroy', $subcategorie->categorie->id))
            ->assertStatus(500);

        $this->assertNotSoftDeleted('categories', $subcategorie->categorie->only('id', 'title'));
    }

    public function test_move_all_to_other_categorie()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $categorie = Categorie::factory()->create();

        $subcategories  = Subcategorie::factory(50)->create(['categorie_id' => $categorie->id]);

        $toCategorie = Categorie::factory()->create();

        $this->assertDatabaseHas('subcategories', ['categorie_id' => $categorie->id]);

        $this->actingAs($user)->patch(route('categories.subcategorie-move', $categorie->id), ['to_categorie_id' => $toCategorie->id])
            ->assertRedirect();

        $this->assertDatabaseHas('subcategories', ['categorie_id' => $toCategorie->id]);
    }

    public function test_remove_subcategories_for_categorie()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $user->assignRole('admin');

        $categorie = Categorie::factory()->create();

        $subcategories  = Subcategorie::factory(50)->create(['categorie_id' => $categorie->id]);

        $this->assertDatabaseHas('subcategories', ['categorie_id' => $categorie->id]);

        $this->actingAs($user)->delete(route('categories.subcategorie-remove', $categorie->id))
            ->assertRedirect();

        $this->assertSoftDeleted('subcategories', ['categorie_id' => $categorie->id]);
    }
}
