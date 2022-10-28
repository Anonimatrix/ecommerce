<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\User;
use App\Models\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_product()
    {
        $view = View::factory()->create();

        $this->assertInstanceOf(Product::class, $view->product);
    }

    public function test_belongs_to_user()
    {
        $view = View::factory()->create();

        $this->assertInstanceOf(User::class, $view->user);
    }
}
