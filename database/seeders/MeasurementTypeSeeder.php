<?php

namespace Database\Seeders;

use App\Models\MeasurementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasurementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeasurementType::factory(1)->create(['name' => 'Pieza', 'code' => 'EA']);
    }
}
