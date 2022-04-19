<?php

namespace Tests\Feature\Models;

use App\Models\Categorie;
use App\Models\Subcategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubcategorieTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_categorie()
    {
        $subcategorie = Subcategorie::factory()->create();

        $this->assertInstanceOf(Categorie::class, $subcategorie->categorie);
    }
}
