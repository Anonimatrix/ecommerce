<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categorie;
use App\Models\Subcategorie;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategorieControllerTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Categories/Index')
                ->has('categories', 10));
    }

    public function test_create()
    {
    }

    public function test_store()
    {
    }

    public function test_edit()
    {
    }

    public function test_update()
    {
    }

    public function test_destroy()
    {
    }
}
