<?php

namespace Tests\Feature\Models;

use App\Models\Search;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_belongs_to_user()
    {
        $search = Search::factory()->create();

        $this->assertInstanceOf(User::class, $search->user);
    }
}
