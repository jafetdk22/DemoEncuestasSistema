<?php

namespace Database\Seeders;
use App\Models\Pregunta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreguntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pregunta::create([
            'pregunta'=>'Pregunta 1',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 1',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 2',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 3',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 4',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 5',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 6',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
        Pregunta::create([
            'pregunta'=>'Pregunta 7',
            'concesion'=>'Automotriz1',
            'tipo'=>'Estrellas',
            'emoji'=> 0,
            'status'=>'ACTIVA'
        ]);
    }
}
