<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ProductSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(ProductTagSeeder::class);
        $this->call(SearchSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ViewSeeder::class);
    }
}
