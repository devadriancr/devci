<?php

namespace Database\Seeders;

use App\Models\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Container::factory(1)->create(['date' => '2022-11-10', 'time' => '12:00']);
        Container::factory(1)->create(['date' => '2022-11-10', 'time' => '00:00']);
        Container::factory(1)->create(['date' => '2022-11-11', 'time' => '12:00']);
        Container::factory(1)->create(['date' => '2022-11-11', 'time' => '00:00']);
        Container::factory(1)->create(['date' => '2022-11-12', 'time' => '12:00']);
        Container::factory(1)->create(['date' => '2022-11-12', 'time' => '00:00']);
    }
}
