<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@clinica.com', 'password' => Hash::make('admin123'), 'id_rol' => 1]);
        User::create(['name' => 'Doctor', 'email' => 'doctor@clinica.com', 'password' => bcrypt('doctor123'), 'id_rol' => 2]);
        User::create(['name' => 'Secretaria', 'email' => 'secretaria@clinica.com', 'password' => Hash::make('secretaria123'), 'id_rol' => 3]);
    }
}
