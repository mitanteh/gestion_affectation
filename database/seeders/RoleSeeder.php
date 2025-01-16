<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['lib_role' => 'Administrateur']);
        Role::create(['lib_role' => 'Manager']);
        Role::create(['lib_role' => 'Chef de Projet']);
        Role::create(['lib_role' => 'Analyste de Test']);
        Role::create(['lib_role' => 'Ingénieur de Test']);
    }
}
