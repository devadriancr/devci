<?php

namespace Database\Seeders;

use App\Models\ItemClass;
use App\Models\StandardType;
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
        // User::factory(10)->create();
        $this->call([
            UsersSeeder::class,
            StandardTypeSeeder::class,
            StandardPackSeeder::class,
            MeasurementTypeSeeder::class,
            ItemTypeSeeder::class,
            ItemClassSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
