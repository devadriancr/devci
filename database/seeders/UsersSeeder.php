<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Usuario Administrador', 'email' => 'admin@admin.com', 'email_verified_at' => now(), 'password' => bcrypt('admin'), 'remember_token' => Str::random(10)])->assignRole('Administrador');
        User::create(['name' => 'Usuario Oficina', 'email' => 'user@office.com', 'email_verified_at' => now(), 'password' => bcrypt('user'), 'remember_token' => Str::random(10)])->assignRole('Oficina');
        User::create(['name' => 'Usuario Almacén Envío/Recibo', 'email' => 'user@warehouse.com', 'email_verified_at' => now(), 'password' => bcrypt('user'), 'remember_token' => Str::random(10)])->assignRole('Almacén Envío/Recibo');
        User::create(['name' => 'Usuario Almacén Línea', 'email' => 'user@line.com', 'email_verified_at' => now(), 'password' => bcrypt('user'), 'remember_token' => Str::random(10)])->assignRole('Almacén Línea');
    }
}
