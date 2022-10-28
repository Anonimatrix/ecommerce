<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Search;
use App\Models\Subcategorie;
use App\Models\Tag;
use App\Models\User;
use App\Models\View;
use App\Statuses\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * @var \App\Models\User $user 
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_products_latest_search_in_home_page()
    {
        $subcategorie = Subcategorie::factory()->create();

        Search::factory()->create(['user_id' => $this->user->id]);

        $latestSearch = Search::factory()->create(['user_id' => $this->user->id]);

        Product::factory(5)->create(['title' => $latestSearch->content, 'subcategorie_id' =>  $subcategorie->id]);

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has('products_latest_search', 5)
            );
    }

    public function test_similar_products_latest_view_in_home_page()
    {
        $subcategorie = Subcategorie::factory()->create();

        $product = Product::factory()->create(['subcategorie_id' =>  $subcategorie->id]);

        $tag = Tag::factory()->create();

        $product->tags()->sync($tag);

        $similarProducts = Product::factory(5)->create(['subcategorie_id' =>  $subcategorie->id])
            ->each(function ($product) use ($tag) {
                $product->tags()->sync($tag);
            });

        //First view
        View::factory()->create();

        //LATEST view (!important)
        View::factory()->create(['user_id' => $this->user->id, 'product_id' => $product->id]);

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has('similar_products_latest_view', 6)
            );
    }

    public function test_products_most_searched_in_home_page()
    {
        $search_content = 'search';
        Search::factory(5)->create(['content' => $search_content]);

        Product::factory(10)->create(['title' => $search_content]);

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has('products_of_most_searched', 10)
            );
    }

    public function test_products_of_most_sold_subcategories()
    {
        $subcategories = Subcategorie::factory(20)->create();

        $slicedSubcategories = $subcategories->slice(0, 6);

        $slicedSubcategories->each(function ($subcategorie) {
            $product = Product::factory()->create(['subcategorie_id' => $subcategorie->id]);
            Order::factory()->create(['status' => OrderStatus::COMPLETED, 'product_id' => $product->id]);
        });


        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has(
                        'products_subcategorie_with_most_solds',
                        6,
                        fn (AssertableInertia $page) => $page
                            ->where('title', $slicedSubcategories[0]->title)
                            ->where(
                                'products',
                                fn ($products) =>
                                count($products) === 1
                            )
                            ->etc()
                    )
            );
    }

    public function test_products_most_sold_in_home_page()
    {
        $product_least_sold = Product::factory()->create();

        Order::factory(1)->create(['status' => OrderStatus::COMPLETED, 'product_id' => $product_least_sold->id]);

        $product_most_sold = Product::factory()->create();

        Order::factory(5)->create(['status' => OrderStatus::COMPLETED, 'product_id' => $product_most_sold->id]);

        $product_not_sold = Product::factory()->create();

        Order::factory(1)->create(['status' => OrderStatus::PENDING, 'product_id' => $product_not_sold->id]);

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has(
                        'products_most_sold',
                        2,
                        fn (AssertableInertia $page) => $page
                            ->where('id', $product_most_sold->id)
                            ->etc()
                    )
            );
    }

    public function test_products_similar_to_user_views()
    {
        $similarUser = User::factory()->create();
        $viewSimilarUser = View::factory()->create(['user_id' => $similarUser->id]);

        $otherViewsSimilarUser = View::factory(15)->create(['user_id' => $similarUser->id]);

        View::factory()->create(['user_id' => $this->user->id, 'product_id' => $viewSimilarUser->product->id]);

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has('products_similar_to_user', 10)
            );
    }

    public function test_products_most_viewed_in_home_page()
    {
        $productsMostViewed = Product::factory(3)->create();

        $productsLeastViewed = Product::factory(3)->create();

        $productsNotViewed = Product::factory(3)->create();

        $productsMostViewed->each(function ($product) {
            $views = View::factory(50)->create();
            $product->views()->saveMany($views);
        });

        $productsLeastViewed->each(function ($product) {
            $views = View::factory(5)->create();
            $product->views()->saveMany($views);
        });

        $this->actingAs($this->user)->get(route('home'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Home')
                    ->has('products_most_viewed', 6)
            );
    }
}
