<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'Administrador']);
        $office = Role::create(['name' => 'Oficina']);
        $warehouse = Role::create(['name' => 'Almacén Envío/Recibo']);
        $line = Role::create(['name' => 'Almacén Línea']);

        Permission::create(['name' => 'admin'])->syncRoles([$admin]);
        Permission::create(['name' => 'office'])->syncRoles([$admin, $office]);
        Permission::create(['name' => 'warehouse'])->syncRoles([$admin, $warehouse]);
        Permission::create(['name' => 'line'])->syncRoles([$admin, $line]);
        Permission::create(['name' => 'all'])->syncRoles([$admin, $office, $warehouse, $line]);


        // Permission::create(['name' => 'dashboard'])->syncRoles([$admin, $office, $warehouse, $line]);
        // Permission::create(['name' => 'user.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'role.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'measurement-type.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'item-type.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'item-class.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'warehouse.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'location.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'transaction-type.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'item.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'inventory.index'])->syncRoles([$admin, $office]);
        // Permission::create(['name' => 'input.index'])->syncRoles([$admin, $office]);
        // Permission::create(['name' => 'output.index'])->syncRoles([$admin, $office]);
        // Permission::create(['name' => 'shipping-instruction.index'])->syncRoles([$admin, $office]);
        // Permission::create(['name' => 'container.index'])->syncRoles([$admin]);
        // Permission::create(['name' => 'consignment-instruction.container'])->syncRoles([$admin, $warehouse]);
        // Permission::create(['name' => 'output.search'])->syncRoles([$admin, $office, $warehouse]);
        // Permission::create(['name' => 'Delivery.index'])->syncRoles([$admin, $warehouse]);
        // Permission::create(['name' => 'Requestlist.index'])->syncRoles([$admin, $office]);
        // Permission::create(['name' => 'RequestList.order_detail'])->syncRoles([$admin, $office, $warehouse]);
        // Permission::create(['name' => 'RequestList.list_order'])->syncRoles([$admin, $office, $warehouse]);
        // Permission::create(['name' => 'consigment-instruction.data-upload-index'])->syncRoles([$admin]);
    }
}
