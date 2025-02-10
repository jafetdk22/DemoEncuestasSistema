<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role  = Role::create(['name'=>'Super Administrador']);
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Administrador de Encuestas']);
        $role3 = Role::create(['name' => 'Gerencial']);
        $role4 = Role::create(['name' => 'Operativo']);
        /*solo super administrador */
        Permission::create(['name' => 'super administrador'])->assignRole($role);
        /*Administrador: administrador y super administrador*/
        Permission::create(['name' => 'Administrador'])->assignRole($role1);
        Permission::create(['name' => 'AdministradorN1'])->assignRole($role,$role1);
        /*Admin. de Encuestas: administrador, super administrador y administrador de Encuestas*/
        Permission::create(['name' => 'Administrador de Encuestas'])->assignRole($role,$role1,$role2);
        /*Gerencial:administrador, super administrador, administrador de Encuestas y gerentes */
        Permission::create(['name' => 'Gerencial'])->assignRole($role,$role1,$role2,$role3);
        Permission::create(['name' => 'GerencialN1'])->assignRole($role,$role1,$role2,$role4);
        /*Operativo:Todos*/
        Permission::create(['name' => 'Operativo'])->assignRole($role,$role1,$role2,$role3,$role4);
        
        Permission::create(['name' => 'Usuarios'])->assignRole($role1,$role2 ,$role3,$role4);
        Permission::create(['name' => 'Admin'])->assignRole($role1 ,$role3,$role4);

    }
}
