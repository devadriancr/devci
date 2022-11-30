<?php

namespace Database\Seeders;

use App\Models\ItemClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemClass::create(['iid' => 'IC', 'code' => '01', 'name' => 'Phantom']);
        ItemClass::create(['iid' => 'IC', 'code' => 'F1', 'name' => 'Completion']);
        ItemClass::create(['iid' => 'IC', 'code' => 'G0', 'name' => 'Supply Yes payments(delivery)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'G1', 'name' => 'Supply Yes payments']);
        ItemClass::create(['iid' => 'IZ', 'code' => 'M0', 'name' => 'Press(Manufactured delivery)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'M1', 'name' => 'Press(Manufactured)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'M2', 'name' => 'Sub Assy(Manufactured)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'M3', 'name' => 'Assy(Manufactured)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'M4', 'name' => 'Paint(Manufactured)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'P0', 'name' => 'Supply No payments(delivery)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'P1', 'name' => 'Supply No payments']);
        ItemClass::create(['iid' => 'IC', 'code' => 'P2', 'name' => 'Supply No payments(JS PARTS)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'S1', 'name' => 'Supply Companies']);
        ItemClass::create(['iid' => 'IC', 'code' => 'T1', 'name' => 'Material(COIL)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'T2', 'name' => 'Material(FOOP)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'T3', 'name' => 'Material(SHEET)']);
        ItemClass::create(['iid' => 'IC', 'code' => 'X1', 'name' => 'Non used (wrong item code)']);
    }
}
