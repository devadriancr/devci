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
        Container::factory(1)->create();
        Container::factory(1)->create([
            'arrival_date' => Carbon::now()->format('Ymd'),
            'arrival_time' => Carbon::now()->addHour()->format('His')
        ]);
        Container::factory(1)->create([
            'arrival_date' => Carbon::now()->addDay()->format('Ymd'),
            'arrival_time' => Carbon::now()->addHour()->format('His')
        ]);
    }
}
