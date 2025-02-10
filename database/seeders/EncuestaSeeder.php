<?php

namespace Database\Seeders;
use App\Models\Encuesta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EncuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Encuesta::create([
            'Nombre'=>'ENCUESTA 1',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz1','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 2',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz2','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 3',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz3','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 4',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz4','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 5',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz5','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 6',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz1','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 7',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz1','status'=>'ACTIVA'
        ]);
        Encuesta::create([
            'Nombre'=>'ENCUESTA 8',
            'Departamento'=>'VENTAS',
            'Concesion'=>'Automotriz1','status'=>'ACTIVA'
        ]);
    }
}
