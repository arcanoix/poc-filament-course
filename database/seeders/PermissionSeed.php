<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'administrador']);
        Role::create(['name' => 'usuario']);

        Permission::create(['name' => 'ver cursos']);
        Permission::create(['name' => 'crear curso']);
        Permission::create(['name' => 'editar curso']);
        Permission::create(['name' => 'eliminar curso']);
    }
}
