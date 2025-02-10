<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $fillable = [
        'respuesta',
        'valor',
        'emoji',
    ];  
    public function preguntas(){
        return $this -> belongsToMany('App\Models\Pregunta');
    }
}
