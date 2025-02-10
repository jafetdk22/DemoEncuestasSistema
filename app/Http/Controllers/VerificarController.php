<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use Mail;
use Carbon\Carbon;
use DB;
class VerificarController extends Controller
{
    public function verificadoAutomotriz1(Request $request, $id){
        $orden=$request->get('orden');
        $conexionDB = 'Lerma';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('orden', $orden)->with('conexionDB', $conexionDB)->with('alerta', $alerta);
        }

    }
    public function verificadoAutomotriz2($id){
        $conexion = 'Lindavista';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('conexion', $conexion)->with('alerta', $alerta);
        }
    }
    public function verificadoAutomotriz3($id){
                $conexion = 'divol';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('conexion', $conexion)->with('alerta', $alerta);
        }
    }
    public function verificadoAutomotriz4 ($id){
                $conexion = 'Perinorte';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('conexion', $conexion)->with('alerta', $alerta);
        }
    }
    public function verificadoAutomotriz5 ($id){
               $conexion = 'Perinorte';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('conexion', $conexion)->with('alerta', $alerta);
        } 
    }
    public function verificarAutomotriz6 ($id){
               $conexion = 'Motors';
        $encuesta = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        if (!$preguntas) {
            return redirect('/encuestas')->with('NoPreguntas', 'ok');
        }
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);

        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('conexion', $conexion)->with('alerta', $alerta);
        } 
    }
}
