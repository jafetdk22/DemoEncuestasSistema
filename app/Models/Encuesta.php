<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $connection = 'sqlsrv';
    protected $fillable = [
        'Nombre',
        'Departamento',
        'Concesion',
        'status'        
    ];

    public function preguntas(){
        return $this -> belongsToMany('App\Models\Pregunta');
    }

  
}
