<?php

namespace Tests\Feature\Http\Controllers;

use App\Repositories\Cache\AdressCacheRepository;
use App\Models\Adress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AdressControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_authorization()
    {
        $requests = array();
        $requests[] = $this->post(route('adresses.store'), []);
        $requests[] = $this->get(route('adresses.create'));

        foreach ($requests as $request) {
            $request->assertRedirect(route('login'));
        }
    }

    public function test_store()
    {
        /**
         * @var \App\Models\User $user
         */

        $user = User::factory()->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'adress' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        $this->actingAs($user)->post(route('adresses.store'), $data)
            ->assertStatus(302);

        $user->refresh();

        $this->assertInstanceOf(Adress::class, $user->adresses[0]);
    }

    public function test_store_validation()
    {

        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $user = User::factory()->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'adress' => 'ortiz de zarate 6659',
            // 'postal_code' => '7600'
        ];

        $this->actingAs($user)->post(route('adresses.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['postal_code']);
    }

    public function test_create()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('adresses.create'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Adress/Create')
            );
    }

    public function test_delete()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $adress = Adress::factory(['user_id' => $user->id])->create();

        $this->actingAs($user)->delete(route('adresses.destroy', $adress->id))
            ->assertStatus(302);

        $this->assertDatabaseMissing('adresses', $adress->toArray());
    }

    public function test_owner_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $adress = Adress::factory()->create();
        $requests = array();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'adress' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        //Destroy authorization
        $requests[] = $this->actingAs($user)->delete(route('adresses.destroy', $adress->id));
        //Edit authorization
        $requests[] = $this->actingAs($user)->get(route('adresses.edit', $adress->id));
        //Update authorization
        $requests[] = $this->actingAs($user)->put(route('adresses.update', $adress->id), $data);

        foreach ($requests as $request) {
            $request->assertStatus(403);
        }

        $this->assertDatabaseHas('adresses', $adress->getAttributes(['country', 'city', 'adress']));
    }

    public function test_update()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $adress = Adress::factory(['user_id' => $user])->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'adress' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        $this->actingAs($user)->put(route('adresses.update', $adress->id), $data)
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('adresses', $data);
        $this->assertDatabaseMissing('adresses', ['country' => $adress->country]);
    }

    public function test_update_validation()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $adress = Adress::factory(['user_id' => $user])->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'adress' => 'ortiz de zarate 6659',
            // 'postal_code' => '7600'
        ];

        $this->actingAs($user)->put(route('adresses.update', $adress->id), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['postal_code']);

        $this->assertDatabaseHas('adresses', ['country' => $adress->country]);
        $this->assertDatabaseMissing('adresses', $data);
    }

    public function test_index()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();

        Adress::factory(15)->create(['user_id' => $user]);

        $this->actingAs($user)->get(route('adresses.index'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Adress/Index')
                    ->has('adresses.data', 10)
            );
    }

    public function test_edit()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $adress = Adress::factory(['user_id' => $user])->create();

        $this->actingAs($user)->get(route('adresses.edit', $adress->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Adress/Edit')
            );
    }
}
