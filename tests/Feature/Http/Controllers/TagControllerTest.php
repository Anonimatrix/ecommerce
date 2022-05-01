<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_suggest()
    {
        for ($i = 0; $i < 3; $i++) {
            Tag::factory()->create(['title' => 'foo' . $this->faker->sentence]);
        }

        $data = [
            'search' => 'foo'
        ];

        $this->get(route('tags.suggest', $data))
            ->assertStatus(200)
            ->assertSessionDoesntHaveErrors()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('suggests', 3)
            );
    }

    public function test_suggest_validation()
    {
        $data = [
            // 'search' => 'foo'
        ];

        $this->get(route('tags.suggest', $data))
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('search');
    }
}
