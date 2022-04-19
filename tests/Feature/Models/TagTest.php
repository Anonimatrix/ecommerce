<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_has_many_products()
    {
        $tag = new Tag();

        $this->assertInstanceOf(Collection::class, $tag->products);
    }
}
