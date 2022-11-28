<?php

namespace Database\Seeders;

use App\Models\StandardType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StandardType::factory(1)->create(['name' => 'Metálico', 'description' => '']);
    }
}
