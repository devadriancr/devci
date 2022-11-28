<?php

namespace Database\Seeders;

use App\Models\StandardPack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StandardPack::factory(1)->create(['name' => 'MDA']);
    }
}
