<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $fillable = [
        'pregunta',
        'descripcion',
        'concesion',
        'tipo',
        'emoji',
    ];

    
    public function encuestas(){
        return $this -> belongsToMany('App\Models\Encuesta');
    }
    public function respuestas(){
        return $this -> belongsToMany('App\Models\Respuesta');
    }

}
