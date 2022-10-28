<?php

namespace Tests\Feature\Http\Controllers;

use App\Repositories\Cache\AddressCacheRepository;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AddressControllerTest extends TestCase
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
        $requests[] = $this->post(route('addresses.store'), []);
        $requests[] = $this->get(route('addresses.create'));

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
            'address' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        $this->actingAs($user)->post(route('addresses.store'), $data)
            ->assertStatus(302);

        $user->refresh();

        $this->assertInstanceOf(Address::class, $user->addresses[0]);
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
            'address' => 'ortiz de zarate 6659',
            // 'postal_code' => '7600'
        ];

        $this->actingAs($user)->post(route('addresses.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['postal_code']);
    }

    public function test_create()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('addresses.create'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Address/Create')
            );
    }

    public function test_delete()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user->id])->create();

        $this->actingAs($user)->delete(route('addresses.destroy', $address->id))
            ->assertStatus(302);

        $this->assertDatabaseMissing('addresses', $address->toArray());
    }

    public function test_owner_policy()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory()->create();
        $requests = array();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'address' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        //Destroy authorization
        $requests[] = $this->actingAs($user)->delete(route('addresses.destroy', $address->id));
        //Edit authorization
        $requests[] = $this->actingAs($user)->get(route('addresses.edit', $address->id));
        //Update authorization
        $requests[] = $this->actingAs($user)->put(route('addresses.update', $address->id), $data);

        foreach ($requests as $request) {
            $request->assertStatus(403);
        }

        $this->assertDatabaseHas('addresses', $address->getAttributes(['country', 'city', 'address']));
    }

    public function test_update()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user])->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'address' => 'ortiz de zarate 6659',
            'postal_code' => '7600'
        ];

        $this->actingAs($user)->put(route('addresses.update', $address->id), $data)
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('addresses', $data);
        $this->assertDatabaseMissing('addresses', ['country' => $address->country]);
    }

    public function test_update_validation()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user])->create();

        $data = [
            'country' => 'Argentina',
            'city' => 'mar del plata',
            'address' => 'ortiz de zarate 6659',
            // 'postal_code' => '7600'
        ];

        $this->actingAs($user)->put(route('addresses.update', $address->id), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['postal_code']);

        $this->assertDatabaseHas('addresses', ['country' => $address->country]);
        $this->assertDatabaseMissing('addresses', $data);
    }

    public function test_index()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();

        Address::factory(15)->create(['user_id' => $user]);

        $this->actingAs($user)->get(route('addresses.index'))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Address/Index')
                    ->has('addresses.data', 10)
            );
    }

    public function test_edit()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user])->create();

        $this->actingAs($user)->get(route('addresses.edit', $address->id))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Address/Edit')
            );
    }
}
