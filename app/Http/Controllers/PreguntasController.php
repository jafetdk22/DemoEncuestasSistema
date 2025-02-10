<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;
use App\Models\Respuesta;
use DB;


class PreguntasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $pregunta = Pregunta::all();
        $alerta = 0;
        return response()->view('preguntas.index', [
            'pregunta' => $pregunta,
            'alerta' => $alerta
        ]);
    }
    public function create(){
        return view('preguntas.create');
    }
    public function store(Request $request){
        $existe=DB::connection('sqlsrv')->select('SELECT * FROM preguntas where pregunta = '."'".$request->get('pregunta')."'".'AND concesion ='."'".$request->get('concesion')."'".'AND tipo ='."'".$request->get('tipo')."'".'AND emoji ='."'".$request->get('emoji')."'");
        //dd($existe);
        if(!$existe){
            $pregunta = new Pregunta();

            $pregunta->pregunta = $request->get('pregunta');
            $pregunta->concesion = $request->get('concesion');
            $pregunta->tipo = $request->get('tipo');
            $pregunta->emoji = $request->get('emoji');
            $pregunta->status = $request->get('status');
    
            $pregunta->save();
            return redirect('preguntas/')->with('crear', 'ok');;
        }

        return redirect('preguntas/')->with('Nocrear', 'ok');
    }
    public function show($id){
        //
    }
    public function edit($id){
        $pregunta = Pregunta::find($id);
        return view('preguntas.editar',compact('pregunta'));
    }
    public function update(Request $request, $id){

        $detalleQ =DB::connection('sqlsrv')->select('SELECT pregunta_id FROM pregunta_respuesta WHERE pregunta_id LIKE'."'".$id."'");
        //d($detalleQ);
        if(!$detalleQ){
            $pregunta = Pregunta::find($id);
            $pregunta->pregunta = $request->get('pregunta');
            $pregunta->concesion = $request->get('concesion');
            $pregunta->tipo = $request->get('tipo');
            $pregunta->emoji = $request->get('emoji');
            $pregunta->save();

            $pregunta =Pregunta::all();
            $alerta= 2;
            return view('preguntas.index')
            ->with('pregunta', $pregunta)
            ->with('alerta', $alerta)->with('actualizar', 'ok')
            ;
        }else{
            $pregunta =Pregunta::all();
            $alerta= 3;
            return view('preguntas.index')
            ->with('pregunta', $pregunta)
            ->with('alerta', $alerta)->with('Noactualizar', 'ok')
            ;
        }
    }
    public function destroy($id){
        $detalleQ =DB::connection('sqlsrv')->select('SELECT pregunta_id FROM pregunta_respuesta WHERE pregunta_id LIKE'."'".$id."'");
        $detalleQ1 =DB::connection('sqlsrv')->select('SELECT pregunta_id FROM encuesta_pregunta WHERE pregunta_id LIKE'."'".$id."'");
        //dd($detalleQ);
        if(!$detalleQ1){
            if(!$detalleQ){
                $pregunta=Pregunta::find($id);
                $pregunta->delete();

                return redirect('preguntas/')->with('eliminar', 'ok');
            }else{
                return redirect('preguntas/')->with('Noeliminar', 'ok');
            }
        }else{
            return redirect('preguntas/')->with('Noeliminar1', 'ok');
        }
    }
    public function respuestas($id){
        $pregunta= Pregunta::find($id);
        $disp =Respuesta::all();
        //dd($pregunta);
        $respues= $pregunta->respuestas;
        $alerta=0;
        return view('preguntas.asignar')
        ->with('pregunta', $pregunta)
        ->with('respues', $respues)
        ->with('alerta', $alerta)
        ->with('disp', $disp);
    }
    public function respuestasCreate(Request $request,$id){
       // dd($id);
        $res = new Respuesta();
        $res->respuesta=$request->get('res');
        $res->valor = $request->get('valor');
        $res->emoji = $request->get('emoji');
        $res->save();

        $pregunta=Pregunta::find($id);
        $pregunta->respuestas()->attach($res);
        $respues= $pregunta->respuestas;
        $disp =Respuesta::all();
        
        return redirect('respuestas/'.$request->get('pre'))->with('creado', 'ok');
    }
    public function agregarR(Request $request ,$id){
        $asignada= DB::connection('sqlsrv')->select('SELECT pregunta_id FROM pregunta_respuesta WHERE pregunta_id='.$id.'AND respuesta_id='. $request->get('respuesta'));
        //dd($asignada);
        if(!$asignada){
            $pregunta = Pregunta::find($id);
            //dd($pregunta);
            $respuesta = $request->get('respuesta');
            $pregunta -> respuestas()->attach($respuesta);
            $respues= $pregunta->respuestas;
            $disp =Respuesta::all();
            
            return redirect('respuestas/'.$id)->with('agregado', 'ok');
        }else{
            
            return redirect('respuestas/'.$id)->with('noAgregado', 'ok');
        }
    }
    public function respuestasEdit($id){
        $respuesta=Respuesta::find($id);
        return view('preguntas.editar_pregunta')->with('respuesta', $respuesta);
    }
    public function respuestasupdate(Request $request, $id){
        $respuesta=Respuesta::find($id);
        $respuesta->respuesta=$request->get('res');
        $respuesta->valor = $request->get('valor');
        $respuesta->emoji = $request->get('emoji'); 
        $respuesta->save();
        
        return redirect('respuestas/'.$request->get('pregunta'));
    }
    public function removerR(Request $request, $id){
        $res = $request->get('respuesta');
        $respuesta=Respuesta::find($res);

        $pregunta= Pregunta::find($id);
        $pregunta-> respuestas()->detach($respuesta);
       
        $respues= $pregunta->respuestas;
        $disp =Respuesta::all();
        $alerta=3;
        return redirect('respuestas/'.$id)->with('Removida', 'ok');
    }
    public function destroyR(Request $request ,$id){

        $detalleQ =DB::connection('sqlsrv')->select('SELECT respuesta_id FROM pregunta_respuesta WHERE respuesta_id LIKE'."'".$id."'");
        // dd($detalleQ);
        if(!$detalleQ){
            $respuesta=Respuesta::find($id);
            $respuesta->delete();
            return redirect('respuestas/'.$request->get('pregunta'))->with('eliminar', 'ok');
        }else{
            return redirect('respuestas/'.$request->get('pregunta'))->with('Noeliminar', 'ok');
        }

    }
    public function search(Request $request){
        $term =$request -> get('term');
        $pregunta=Pregunta::find($term);
        $respuestas = $pregunta -> respuestas;
        return with('respuestas',$respuestas);
    }
    public function statusP(Request $request, $id){
        //dd($request);
        $pregunta = Pregunta::find($id);
        $pregunta->status = $request->get('status');
        $pregunta->save();
        return redirect('/preguntas');
    }
    
}
