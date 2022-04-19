<?php

namespace Tests\Feature\Models;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategorieTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_has_many_subcategories()
    {
        $categorie = new Categorie();

        $this->assertInstanceOf(Collection::class, $categorie->subcategories);
    }
}
