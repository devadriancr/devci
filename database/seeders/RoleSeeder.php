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
        $warehouse = Role::create(['name' => 'Almacén']);

        /**
         *
         */
        Permission::create(['name' => 'dashboard'])->syncRoles([$admin, $office, $warehouse]);

        /**
         *
         */
        Permission::create(['name' => 'measurement-type.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'item-type.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'item-class.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'warehouse.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'location.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'transaction-type.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'item.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'container.index'])->syncRoles([$admin]);

        /**
         *
         */
        Permission::create(['name' => 'consignment-instruction.container'])->syncRoles([$admin, $warehouse]);

        /**
         *
         */
        Permission::create(['name' => 'shipping-instruction.index'])->syncRoles([$admin, $office]);
    }
}
