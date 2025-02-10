<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_encuestas extends Model
{
    protected $connection = 'sqlsrv';
    use HasFactory;
  

    public function preguntas(){
        return $this -> belongsToMany('App\Models\Pregunta');
    }

    public function encuestas(){
        return $this -> belongsToMany('App\Models\Encuesta');
    }

}
