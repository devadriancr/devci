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
        $this->call([
            RoleSeeder::class,
            UsersSeeder::class,
            StandardTypeSeeder::class,
            StandardPackSeeder::class,
            MeasurementTypeSeeder::class,
            ItemTypeSeeder::class,
            ItemClassSeeder::class,
            ItemSeeder::class,
            ContainerSeeder::class,
            WarehouseSeeder::class,
            LocationSeeder::class,
            TransactionTypeSeeder::class,
        ]);
    }
}
