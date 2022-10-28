<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        if (!Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $this->get('/register')
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Auth/Register')
                    ->has('dni_types')
            );
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled()
    {
        if (Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_can_register()
    {
        if (!Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'dni_type' => 'DNI',
            'dni_number' => 45872821,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
