<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        Role::truncate();
        Permission::truncate();


        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789')
        ]);

        $role = Role::create(['name' => 'administrador']);

        $user->assignRole($role);

        Role::create(['name' => 'usuario']);

        $permissions = [
            'Ver cursos',
            'Crear curso',
            'Editar curso',
            'Eliminar curso'
        ];

        $permission = Permission::create($permissions);

        $role->givePermissionTo($permission);


    }
}
