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
        TransactionType::create(['tid' => 'TE', 'code' => 'A', 'name' => 'Inventory Adjustment']);
        TransactionType::create(['tid' => 'TE', 'code' => 'B', 'name' => 'Shipment from Inventory']);
        TransactionType::create(['tid' => 'TE', 'code' => 'CI', 'name' => 'JIT Cmponent Issue (Backflush)']);
        TransactionType::create(['tid' => 'TE', 'code' => 'DS', 'name' => 'Drop Ship Confitm-No Inv Updte']);
        TransactionType::create(['tid' => 'TE', 'code' => 'O', 'name' => 'Opening Balance/Physical Invty']);
        TransactionType::create(['tid' => 'TE', 'code' => 'PR', 'name' => 'JIT Production Receipt']);
        TransactionType::create(['tid' => 'TE', 'code' => 'QC', 'name' => 'Quality Control Reject']);
        TransactionType::create(['tid' => 'TE', 'code' => 'RJ', 'name' => 'JIT Production Reject']);
        TransactionType::create(['tid' => 'TE', 'code' => 'T', 'name' => 'Inventory Location Transfer']);
        TransactionType::create(['tid' => 'TE', 'code' => 'U', 'name' => 'PO Receipt Direct to Stock']);
        TransactionType::create(['tid' => 'TE', 'code' => 'U3', 'name' => 'Consignment Parts Receipt']);
        TransactionType::create(['tid' => 'TE', 'code' => 'U4', 'name' => 'Commodity Receipt']);
    }
}
