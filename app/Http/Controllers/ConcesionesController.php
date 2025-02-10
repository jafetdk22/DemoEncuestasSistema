<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;

class ConcesionesController extends Controller
{
    public function automotriz1(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz1');
        $alerta=0;
        return view('encuestas.index')->with('encuesta',$encuesta)->with('alerta',$alerta);
    }
    public function automotriz2(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz2');
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')->with('encuesta', $encuesta)->with('alerta',$alerta);
    }
    public function automotriz3(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz3');
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')->with('encuesta', $encuesta)->with('alerta',$alerta);
    }
    public function automotriz4(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz4');
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')->with('encuesta', $encuesta)->with('alerta',$alerta);
    }   
    public function automotriz5(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz5');
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')->with('encuesta', $encuesta)->with('alerta',$alerta);
    }
    public function automotriz6(){
        $encuesta =Encuesta::all()->where('concesion','=','Automotriz6');
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')->with('encuesta', $encuesta)->with('alerta',$alerta);
    }
}
