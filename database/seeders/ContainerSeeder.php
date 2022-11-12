<?php

namespace Database\Seeders;

use App\Models\Container;
use Carbon\Carbon;
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
        Container::factory(1)->create(['date' => Carbon::now()->format('Y-m-d'), 'time' => '12:00']);
        Container::factory(1)->create(['date' => Carbon::now()->format('Y-m-d'), 'time' => '00:00']);
    }
}
