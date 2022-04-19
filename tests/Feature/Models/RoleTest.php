<?php

namespace Tests\Feature\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_has_many_users()
    {
        $role = new Role();

        $this->assertInstanceOf(Collection::class, $role->users);
    }
}
