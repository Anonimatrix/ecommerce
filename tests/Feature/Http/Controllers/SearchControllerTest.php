<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\SearchController;
use App\Models\Search;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;

class SearchControllerTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_show_most_searched()
    {
        Search::factory(10)->create();

        $this->get(route('searches.most-searched', ['q' => 'foo']))
            ->assertJsonStructure([
                'searches' => [
                    '*' => [
                        'content',
                        'user_id'
                    ]
                ]
            ])->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'searches',
                    SearchController::LIMIT_SUGGESTS
                )
            );
    }

    public function test_show_user_history()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $user = User::factory()->create();

        Search::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get(route('searches.history', ['q' => 'foo']))
            ->assertJsonStructure([
                'searches' => [
                    '*' => [
                        'content',
                        'user_id'
                    ]
                ]
            ])->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'searches',
                    1,
                    fn ($json) =>
                    $json->where('user_id', $user->id)
                        ->etc()
                )
            );
    }

    public function test_not_show_searched_by_other_user()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();

        Search::factory(4)->create(['user_id' => $user->id, 'content' => 'foo']);

        $this->get(route('searches.history', ['q' => 'err']))
            ->assertJsonStructure([
                'searches' => [
                    '*' => [
                        'content',
                        'user_id'
                    ]
                ]
            ])->assertJsonCount(0, 'searches');
    }

    public function test_autosuggest()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();

        Search::factory()->create(['user_id' => $user->id, 'content' => 'foo']);

        Search::factory(5)->create();

        $this->actingAs($user)->get(route('searches.autosuggest', ['q' => 'foo']))
            ->assertJsonStructure([
                'suggestions'
            ])->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'suggestions',
                    6,
                    fn ($json) =>
                    $json->where('user_id', $user->id)
                        ->etc()
                )
            );
    }

    public function test_autosuggest_validation()
    {
        Search::factory(10)->create(['content' => 'foo']);

        $this->get(route('searches.autosuggest'))
            ->assertJsonStructure([
                'status',
                'meta' => [
                    'message',
                    'errors'
                ]
            ])->assertJsonFragment(['errors' => ["q" => ["The q field is required."]]]);
    }

    public function test_history_page()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $user = User::factory()->create();

        Search::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get(route('searches.history-page', ['q' => 'foo']))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Searches/History')
                ->has('products', 1));
    }
}
