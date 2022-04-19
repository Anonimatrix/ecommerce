<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categorie;
use App\Models\Subcategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategorieControllerTest extends TestCase
{
    use RefreshDatabase;

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
}
