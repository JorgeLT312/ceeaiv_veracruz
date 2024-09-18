<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
//use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role1=Role::create(['name' => 'Admin']);
        $role2=Role::create(['name' => 'Invitado']);
        $role3=Role::create(['name' => 'Operativo']);
        $role4=Role::create(['name' => 'Nivel Superior']);
        

        Permission::create(['name' => 'admin.usuario.index','guard_name' => 'web'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.usuario.create','guard_name' => 'web'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.usuario.edit','guard_name' => 'web'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.usuario.active','guard_name' => 'web'])->syncRoles([$role1]);

    }
}
