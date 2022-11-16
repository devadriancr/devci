<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::factory(1)->create(['code' => 'I', 'name' => 'Entrada a YKM']);
        TransactionType::factory(1)->create(['code' => 'O', 'name' => 'Salida de YKM']);
    }
}
