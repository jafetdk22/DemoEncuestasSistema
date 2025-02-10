<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Encuesta_pregunta;
use App\Mail\EnviarEncuestasCorreo;
use App\Mail\MailNotify;
use Mail;
use Carbon\Carbon;
use DB;
use App\Models\detalle_encuestas;

class ContestarController extends Controller
{
    public function verificarOrden($id)
    {
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
            return view('encuestas.verificar')->with('encuesta', $encuesta)->with('alerta', $alerta);
        }
    }
    public function verificadoOrden(Request $request, $id)
    {

        $conexion = $request->get('conexionDB');
        $consulta = DB::connection('sqlsrv')->select('SELECT * FROM encuesta_pregunta WHERE encuesta_id=' . $id);
        if (!$consulta) {
            return view('encuestas.noPreg');
        }
        $orden = $request->get('Orden');
        $encuesta = Encuesta::find($id);
        $alerta = 1;
        if ($encuesta->status == 'DESACTIVADA') {
            return redirect('/home');
        } else {
            return view('encuestas.verificado')
                ->with('encuesta', $encuesta)
                ->with('orden', $orden)
                ->with('conexion', $conexion)
                ->with('alerta', $alerta);
        }
    }
    public function contestar(Request $request, $id)
    {
       //dd($request->all());
        $conexionDB=$request->get('conexionDB');
        $orden = $request->get('Orden');
        $detalleQ = DB::connection('sqlsrv')->select('SELECT No_orden FROM detalle_encuestas WHERE No_orden LIKE' . "'" . $orden . "'");
        //dd($detalleQ);
        if (!$detalleQ) {
            //verificamos que exista el numero de orden 
            //dd($request->get('conexionDB'));
            $prueba = DB::connection($request->get('conexionDB'))->select('SELECT * FROM SEORDSER SEO INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE' . "'" . $orden . "'");
            //dd($prueba);
            if (!$prueba) {
                //si la orden no existe nos redirige a la ventana verificar con la aletrta 2 
                $encuesta  = Encuesta::find($id);
                $alerta = 2;
                return view('encuestas.verificar')
                ->with('encuesta', $encuesta)
                ->with('conexionDB', $conexionDB)->with('orden', $orden)
                ->with('alerta', $alerta);
            } else {
                //
                foreach ($prueba as $row) {
                    $ver = $row->OrOrden;
                    $status = $row->ORStatus;
                    //dd($ver,$status);
                }
                if ($orden != $ver) {
                    return redirect('verificado/' . $id);
                } else if ($orden == $ver && $status == 'CE') {
                    $encuesta  = Encuesta::find($id);
                    $preguntas = DB::connection('sqlsrv')->select('SELECT EP.encuesta_id, EP.pregunta_id, P.id, EP.id , P.pregunta, P.concesion, P.tipo, P.emoji, P.status ,ROW_NUMBER() OVER(ORDER BY EP.id ASC) AS Row
                    FROM encuesta_pregunta EP INNER JOIN preguntas P ON EP.pregunta_id = P.id WHERE EP.encuesta_id=' . $id);
                    //dd($preguntas);
                    $respuestas = DB::connection('sqlsrv')->select('SELECT encuestas.id, encuestas.Nombre, encuesta_pregunta.pregunta_id, preguntas.pregunta, preguntas.tipo, pregunta_respuesta.respuesta_id, respuestas.respuesta, respuestas.emoji,respuestas.valor
                        FROM encuestas INNER JOIN
                        encuesta_pregunta ON encuestas.id = encuesta_pregunta.encuesta_id INNER JOIN
                        preguntas ON encuesta_pregunta.pregunta_id = preguntas.id INNER JOIN
                        pregunta_respuesta ON preguntas.id = pregunta_respuesta.pregunta_id INNER JOIN
                        respuestas ON pregunta_respuesta.respuesta_id = respuestas.id WHERE encuesta_pregunta.encuesta_id=' . $id);
                    return view('encuestas.show')
                        ->with('encuesta', $encuesta)
                        ->with('preguntas', $preguntas)
                        ->with('respuestas', $respuestas)
                        ->with('conexionDB', $conexionDB)
                        ->with('prueba', $prueba)
                        ->with('orden', $orden);
                } else if ($orden == $ver && $status == 'CA') {
                    $encuesta  = Encuesta::find($id);
                    $alerta = 5;
                    return view('encuestas.verificar')
                ->with('conexionDB', $conexionDB)->with('orden', $orden)
                ->with('encuesta', $encuesta)->with('alerta', $alerta);
                }
            }
        } else {
            $encuesta  = Encuesta::find($id);
            $alerta = 3;
            return view('encuestas.verificar')->with('orden', $orden)->with('conexionDB', $conexionDB)->with('encuesta', $encuesta)->with('alerta', $alerta);
        }
    }
    public function detalle(Request $request)
    {
        //dd($request->all());
        $conexionDB= $request->get('conexionDB');
        // dd($request->get('Asesores'));
        $orden = $request->get('orden');
        $detalleQ = DB::connection('sqlsrv')->select('SELECT No_orden FROM detalle_encuestas WHERE No_orden LIKE' . "'" . $orden . "'");

        if (is_numeric($request->get('Resp_P1'))) {
            $resp1 = $request->get('Resp_P1');
            $conteo1 = 1;
        } elseif (is_string($request->get('Resp_P1')) || is_null($request->get('Resp_P1'))) {
            $resp1 = 0;
            $conteo1 = 0;
        }
        if (is_numeric($request->get('Resp_P2'))) {
            $resp2 = $request->get('Resp_P2');
            $conteo2 = 1;
        } elseif (is_string($request->get('Resp_P2')) || is_null($request->get('Resp_P2'))) {
            $resp2 = 0;
            $conteo2 = 0;
        }
        if (is_numeric($request->get('Resp_P3'))) {
            $resp3 = $request->get('Resp_P3');
            $conteo3 = 1;
        } elseif (is_string($request->get('Resp_P3')) || is_null($request->get('Resp_P3'))) {
            $resp3 = 0;
            $conteo3 = 0;
        }
        if (is_numeric($request->get('Resp_P4'))) {
            $resp4 = $request->get('Resp_P4');
            $conteo4 = 1;
        } elseif (is_string($request->get('Resp_P4')) || is_null($request->get('Resp_P4'))) {
            $resp4 = 0;
            $conteo4 = 0;
        }
        if (is_numeric($request->get('Resp_P5'))) {
            $resp5 = $request->get('Resp_P5');
            $conteo5 = 1;
        } elseif (is_string($request->get('Resp_P5')) || is_null($request->get('Resp_P5'))) {
            $resp5 = 0;
            $conteo5 = 0;
        }
        if (is_numeric($request->get('Resp_P6'))) {
            $resp6 = $request->get('Resp_P6');
            $conteo6 = 1;
        } elseif (is_string($request->get('Resp_P6')) || is_null($request->get('Resp_P6'))) {
            $resp6 = 0;
            $conteo6 = 0;
        }
        if (is_numeric($request->get('Resp_P7'))) {
            $resp7 = $request->get('Resp_P7');
            $conteo7 = 1;
        } elseif (is_string($request->get('Resp_P7')) || is_null($request->get('Resp_P7'))) {
            $resp7 = 0;
            $conteo7 = 0;
        }
        if (is_numeric($request->get('Resp_P8'))) {
            $resp8 = $request->get('Resp_P8');
            $conteo8 = 1;
        } elseif (is_string($request->get('Resp_P8')) || is_null($request->get('Resp_P8'))) {
            $resp8 = 0;
            $conteo8 = 0;
        }
        if (is_numeric($request->get('Resp_P9'))) {
            $resp9 = $request->get('Resp_P9');
            $conteo9 = 1;
        } elseif (is_string($request->get('Resp_P9')) || is_null($request->get('Resp_P9'))) {
            $resp9 = 0;
            $conteo9 = 0;
        }
        if (is_numeric($request->get('Resp_P10'))) {
            $resp10 = $request->get('Resp_P10');
            $conteo10 = 1;
        } elseif (is_string($request->get('Resp_P10')) || is_null($request->get('Resp_P10'))) {
            $resp10 = 0;
            $conteo10 = 0;
        }

        $suma1 = $resp1 + $resp2 + $resp3 + $resp4 + $resp5 + $resp6 + $resp7 + $resp8 + $resp9 + $resp10;
        $suma2 = $conteo1 + $conteo2 + $conteo3 + $conteo4 + $conteo5 + $conteo6 + $conteo7 + $conteo8 + $conteo9 + $conteo10;
        //dd($suma2);
        $promedio = $suma1 / $suma2;
        //dd($promedio);

        $detalle = new detalle_encuestas();
        $detalle->No_Orden = $request->get('orden');
        $detalle->encuesta_id = $request->get('encuesta');
        $detalle->P1 = $request->get('P1');
        $detalle->P2 = $request->get('P2');
        $detalle->P3 = $request->get('P3');
        $detalle->P4 = $request->get('P4');
        $detalle->P5 = $request->get('P5');
        $detalle->P6 = $request->get('P6');
        $detalle->P7 = $request->get('P7');
        $detalle->P8 = $request->get('P8');
        $detalle->P9 = $request->get('P9');
        $detalle->P10 = $request->get('P10');

        $detalle->Resp_P1 = $request->get('Resp_P1');
        $detalle->Resp_P2 = $request->get('Resp_P2');
        $detalle->Resp_P3 = $request->get('Resp_P3');
        $detalle->Resp_P4 = $request->get('Resp_P4');
        $detalle->Resp_P5 = $request->get('Resp_P5');
        $detalle->Resp_P6 = $request->get('Resp_P6');
        $detalle->Resp_P7 = $request->get('Resp_P7');
        $detalle->Resp_P8 = $request->get('Resp_P8');
        $detalle->Resp_P9 = $request->get('Resp_P9');
        $detalle->Resp_P10 = $request->get('Resp_P10');
        $detalle->Promedio = $promedio;
        $detalle->concesion = $request->get('concesion');
        $date = Carbon::now();
        //dd($date);
        $detalle->Year = $date->formatLocalized("%Y");
        $detalle->Month = $date->formatLocalized("%B");
        //$detalle->Month='mayo';
        $detalle->Day = $date->formatLocalized("%d");
        $detalle->Odate = $date->format('Y-m-d');
        $ver = $date->format('Y-m-d');
        //dd($ver);
        $detalle->Resp_Texto = $request->get('respuesta_text');
        $detalle->Asesor = $request->get('Asesores');
        //dd($detalle);
        if ($request->get('orden') != 123456789) {
            if (!$detalleQ) {
                $detalle->save();
                $encuesta = Encuesta::find($request->get('encuesta'));
                $preguntas = $encuesta->preguntas;
                $respuestas = DB::connection('sqlsrv')->select('SELECT encuestas.id, encuestas.Nombre, encuesta_pregunta.pregunta_id, preguntas.pregunta, preguntas.tipo, pregunta_respuesta.respuesta_id, respuestas.respuesta, respuestas.emoji,respuestas.valor
                FROM encuestas INNER JOIN
                encuesta_pregunta ON encuestas.id = encuesta_pregunta.encuesta_id INNER JOIN
                preguntas ON encuesta_pregunta.pregunta_id = preguntas.id INNER JOIN
                pregunta_respuesta ON preguntas.id = pregunta_respuesta.pregunta_id INNER JOIN
                respuestas ON pregunta_respuesta.respuesta_id = respuestas.id WHERE encuesta_pregunta.encuesta_id=' . $request->get('encuesta'));
                $orden = $request->get('orden');

                $alerta = 4;

                return view('encuestas.verificar')
                    ->with('encuesta', $encuesta)
                    ->with('preguntas', $preguntas)
                    ->with('respuestas', $respuestas)
                    ->with('conexionDB', $conexionDB)
                    ->with('orden', $orden)
                    ->with('alerta', $alerta);
            } else {
                $encuesta = Encuesta::find($request->get('encuesta'));
                $preguntas = $encuesta->preguntas;
                $respuestas = DB::connection('sqlsrv')->select('SELECT encuestas.id, encuestas.Nombre, encuesta_pregunta.pregunta_id, preguntas.pregunta, preguntas.tipo, pregunta_respuesta.respuesta_id, respuestas.respuesta, respuestas.emoji,respuestas.valor
                FROM encuestas INNER JOIN
                encuesta_pregunta ON encuestas.id = encuesta_pregunta.encuesta_id INNER JOIN
                preguntas ON encuesta_pregunta.pregunta_id = preguntas.id INNER JOIN
                pregunta_respuesta ON preguntas.id = pregunta_respuesta.pregunta_id INNER JOIN
                respuestas ON pregunta_respuesta.respuesta_id = respuestas.id WHERE encuesta_pregunta.encuesta_id=' . $request->get('encuesta'));
                $orden = $request->get('orden');

                $alerta = 3;


                return view('encuestas.verificar')
                    ->with('encuesta', $encuesta)
                    ->with('preguntas', $preguntas)
                    ->with('respuestas', $respuestas)
                    ->with('conexionDB', $conexionDB)
                    ->with('orden', $orden)
                    ->with('alerta', $alerta);
            }
        } else {

            $encuesta = Encuesta::find($request->get('encuesta'));
            $preguntas = $encuesta->preguntas;
            $respuestas = DB::connection('sqlsrv')->select('SELECT encuestas.id, encuestas.Nombre, encuesta_pregunta.pregunta_id, preguntas.pregunta, preguntas.tipo, pregunta_respuesta.respuesta_id, respuestas.respuesta, respuestas.emoji,respuestas.valor
            FROM encuestas INNER JOIN
            encuesta_pregunta ON encuestas.id = encuesta_pregunta.encuesta_id INNER JOIN
            preguntas ON encuesta_pregunta.pregunta_id = preguntas.id INNER JOIN
            pregunta_respuesta ON preguntas.id = pregunta_respuesta.pregunta_id INNER JOIN
            respuestas ON pregunta_respuesta.respuesta_id = respuestas.id WHERE encuesta_pregunta.encuesta_id=' . $request->get('encuesta'));
            $orden = $request->get('orden');

            return view('encuestas.show')
                ->with('encuesta', $encuesta)
                ->with('preguntas', $preguntas)
                    ->with('conexionDB', $conexionDB)
                    ->with('respuestas', $respuestas)
                ->with('orden', $orden);
        }
    }
    public function enviar(Request $request)
    {
        //dd($request->all());
        $orden = $request->get('orden');
        $encuesta = $request->get('encuesta');
        $conexion = $request->get('conexionDB');
        $mails = DB::connection($request->get('conexionDB'))->select('SELECT OrMail from SEORDSER where OrOrden =' . "'" . $orden . "'");
        foreach ($mails as $row) {
            $mail = $row->OrMail;
        }
        //dd($mail);
        $mailable = new EnviarEncuestasCorreo($orden, $encuesta,$conexion);
        Mail::to('jafetvwlerma@gmail.com')->send($mailable);
        return redirect('/encuestas')->with('Enviada', 'ok');
        //return view('correos.enviar_encuesta')->with('orden',$orden)->with('encuesta',$encuesta);
    }
}
