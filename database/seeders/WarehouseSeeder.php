<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create(['lid' => 'WM', 'code' => 'W10', 'name' => 'YKM WareHouse']);
        Warehouse::create(['lid' => 'WM', 'code' => 'W40', 'name' => 'Supplier']);
        Warehouse::create(['lid' => 'WM', 'code' => 'W60', 'name' => 'Consignado']);
    }
}
