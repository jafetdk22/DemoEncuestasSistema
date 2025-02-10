<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*SUPER ADMINISTRADOR */
        User::create([
            'name' => 'Super Administrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'concesion' =>'Super Admin',
            'ConexionDB' =>'todas',
            'email_verified_at'=>'2022-04-20 17:50:15.873'
        ])->assignRole('Super Administrador');
    }
}
