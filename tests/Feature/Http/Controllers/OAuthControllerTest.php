<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Str;

class OAuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function mockSocialiteUser()
    {
        /**
         * @var object $abstractUser
         */

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

        $abstractUser
            ->shouldReceive('getId')
            ->andReturn($this->faker->numerify('##########'))
            ->shouldReceive('getNickname')
            ->andReturn($this->faker->name)
            ->shouldReceive('getName')
            ->andReturn($this->faker->name)
            ->shouldReceive('getEmail')
            ->andReturn($this->faker->email)
            ->shouldReceive('getAvatar')
            ->andReturn($this->faker->imageUrl);

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);
    }

    public function test_google_redirect()
    {
        $this->get(route('oauth.redirect', 'google'))
            ->assertRedirectContains('https://accounts.google.com/o/oauth2/auth');
    }

    public function test_google_auth()
    {
        $this->mockSocialiteUser();

        $this->get(route('oauth.callback', 'google'))
            ->assertStatus(302);

        $this->assertAuthenticated();
    }

    public function test_facebook_auth()
    {
        $this->mockSocialiteUser();

        $this->get(route('oauth.callback', 'facebook'))
            ->assertStatus(302);

        $this->assertAuthenticated();
    }

    public function test_facebook_redirect()
    {
        $this->get(route('oauth.redirect', 'facebook'))
            ->assertRedirectContains('https://www.facebook.com')
            ->assertRedirectContains('oauth');
    }

    public function test_redirect_to_set_password_if_user_not_have_one()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create(['password' => null]);

        $this->actingAs($user)->get(route('home'))
            ->assertRedirect(route('oauth.set-password'));
    }

    public function test_redirect_to_home_if_user_have_password()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('oauth.set-password'))
            ->assertRedirect(route('home'));
    }
}
