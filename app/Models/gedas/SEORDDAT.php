<?php

namespace App\Models\gedas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEORDDAT extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv2';
}
//$prueba = DB2::select('SELECT ExOrOrden FROM SEORDDAT WHERE ExOrOrden LIKE'."'".$orden."'");