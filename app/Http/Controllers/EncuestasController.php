<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\detalle_encuestas;
use App\Models\User;
use DB;
use Carbon\Carbon;
class EncuestasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function index(){
        $encuesta =Encuesta::all();
        $count = Encuesta::all()->count(); 
        $alerta=0;
        //dd($encuesta);
        return view('encuestas.index')
        ->with('encuesta',$encuesta)
        ->with('count',$count)
        ->with('alerta',$alerta);
    }
    public function create(){
        return view('encuestas.create');
    }
    public function store(Request $request){
        $existe=DB::connection('sqlsrv')->select('SELECT Nombre , concesion FROM encuestas where Nombre = '."'".$request->get('Nombre')."'".'AND concesion ='."'".$request->get('Concesion')."'".'AND departamento ='."'".$request->get('Departamento')."'");
        if(!$existe){
            $encuesta = new Encuesta();

            $encuesta->Nombre = $request->get('Nombre');
            $encuesta->Departamento = $request->get('Departamento');
            $encuesta->concesion = $request->get('Concesion');
            $encuesta->status = $request->get('status');
            $encuesta->save();
    
            $encuesta =Encuesta::all();
            $count = Encuesta::all()->count(); 
            $alerta=1;
            //dd($encuesta);
            return redirect('/home')
            ->with('encuesta',$encuesta)
            ->with('count',$count)
            ->with('alerta',$alerta);
        }
        $encuesta =Encuesta::all();
        $count = Encuesta::all()->count(); 
        $alerta=5;
        //dd($encuesta);
        return redirect('/home')
        ->with('encuesta',$encuesta)
        ->with('count',$count)
        ->with('alerta',$alerta);
    }
    public function show($id){
        $orden = 123456789;
        $status ='CE';
        $ver = 123456789;
        $encuesta  = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;

        $respuestas = DB::connection('sqlsrv')->select('SELECT encuestas.id, encuestas.Nombre, encuesta_pregunta.pregunta_id, preguntas.pregunta, preguntas.tipo, pregunta_respuesta.respuesta_id, respuestas.respuesta, respuestas.emoji,respuestas.valor
        FROM encuestas INNER JOIN
        encuesta_pregunta ON encuestas.id = encuesta_pregunta.encuesta_id INNER JOIN
        preguntas ON encuesta_pregunta.pregunta_id = preguntas.id INNER JOIN
        pregunta_respuesta ON preguntas.id = pregunta_respuesta.pregunta_id INNER JOIN
        respuestas ON pregunta_respuesta.respuesta_id = respuestas.id WHERE encuesta_pregunta.encuesta_id='.$id);
        //dd($respuestas);
        return view('encuestas.vistaPrev')
        ->with('encuesta', $encuesta)
        ->with('preguntas', $preguntas)
        ->with('respuestas',$respuestas)
        ->with('orden',$orden);
    }
    public function edit($id){
        $encuesta= Encuesta::find($id);
        //dd($encuesta);
        return view('encuestas.edit')->with('encuesta',$encuesta);
    }
    public function update(Request $request, $id){
        $detalleQ =DB::connection('sqlsrv')->select('SELECT encuesta_id FROM detalle_encuestas WHERE encuesta_id LIKE'."'".$id."'");
        //d($detalleQ);
        if(!$detalleQ){
            $encuesta = Encuesta::find($id);

        $encuesta->Nombre = $request->get('Nombre');
        $encuesta->Departamento = $request->get('Departamento');
        $encuesta->concesion = $request->get('Concesion');
        $encuesta->save();

        $encuesta =Encuesta::all();
        $count = Encuesta::all()->count(); 
        $alerta=2;
        //dd($encuesta);
        return view('encuestas.index')
        ->with('encuesta',$encuesta)
        ->with('count',$count)
        ->with('alerta',$alerta)
        ;

        return redirect('/encuestas');

        }else{

            $encuesta =Encuesta::all();
            $count = Encuesta::all()->count(); 
            $alerta=3;
            //dd($encuesta);
            return view('encuestas.index')
            ->with('encuesta',$encuesta)
            ->with('count',$count)
            ->with('alerta',$alerta)
            ;
        }
    }
    public function destroy($id){
        $detalleQ =DB::connection('sqlsrv')->select('SELECT encuesta_id FROM detalle_encuestas WHERE encuesta_id LIKE'."'".$id."'");
        //d($detalleQ);
        if(!$detalleQ){
            $encuesta = Encuesta::find($id);
            $encuesta->delete();
            return redirect('/encuestas')->with('eliminar', 'ok');
        }else{
            $encuesta =Encuesta::all();
            $count = Encuesta::all()->count(); 
            $alerta=4;
            //dd($encuesta);
            return view('encuestas.index')
            ->with('encuesta',$encuesta)
            ->with('count',$count)
            ->with('alerta',$alerta)
            ;
        }
    }
    public function PreguntasDisp($id){
        $encuesta  = Encuesta::find($id);
        $preguntas= $encuesta->preguntas;
       /* $disp = DB::connection('sqlsrv')->select('SELECT encuesta_pregunta.id, 
        encuesta_pregunta.encuesta_id, encuesta_pregunta.pregunta_id, preguntas.pregunta, 
        preguntas.concesion, preguntas.tipo, preguntas.status, preguntas.created_at, 
        preguntas.updated_at, 
        preguntas.emoji, preguntas.id AS Expr1
        FROM encuesta_pregunta INNER JOIN
        preguntas ON encuesta_pregunta.pregunta_id = preguntas.id
        WHERE encuesta_id !='.$id."and status = 'ACTIVA'");*/
        //dd($disp);
        $disp = DB::connection('sqlsrv')->select("SELECT * FROM preguntas WHERE status='ACTIVA'");
     
        return view('encuestas.asignar')->with('encuesta', $encuesta)->with('preguntas', $preguntas)->with('disp', $disp);
    }
    public function agregarP(Request $request ,$id){
        $asignada= DB::connection('sqlsrv')->select('SELECT id FROM encuesta_pregunta WHERE pregunta_id='.$request->get('pregunta').' AND encuesta_id= '.$id);
        $contestada= DB::connection('sqlsrv')->select('SELECT encuesta_id FROM detalle_encuestas WHERE encuesta_id= '.$id);
        $contar= DB::connection('sqlsrv')->select('SELECT COUNT(*) as numero FROM encuesta_pregunta WHERE encuesta_id='.$id);
        foreach ($contar as $row) {
           $totalPE=$row->numero;
        }
        //dd($contestada);
        if($totalPE >= 10){
            return redirect('asignar/'.$id)->with('solo10', 'ok');
        }elseif($totalPE < 10){
            if(!$asignada){
                if(!$contestada){
                    $encuesta = Encuesta::find($id);
                    $pregunta = $request->get('pregunta');
                    $encuesta -> preguntas()->attach($pregunta);
                    
                    return redirect('asignar/'.$id)->with('agregado', 'ok');
                }else{
                    return redirect('asignar/'.$id)->with('noAgregado', 'ok');
                }
            }else{
                return redirect('asignar/'.$id)->with('yaAsignada', 'ok');
            }
        }
    }
    public function removerP(Request $request ,$id){
        $contestada= DB::connection('sqlsrv')->select('SELECT encuesta_id FROM detalle_encuestas WHERE encuesta_id= '.$id);
        if(!$contestada){
            $encuesta = Encuesta::find($id);
            $pregunta = $request->get('pregunta');
            $encuesta -> preguntas()->detach($pregunta);
            return redirect('asignar/'.$id)->with('Removido', 'ok');
        }else{
            return redirect('asignar/'.$id)->with('noRemovido', 'ok');
        }
    }
    public function contestar($id){
        $encuesta  = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        //return view('encuestas.show')->with('encuesta', $encuesta)->with('preguntas', $preguntas);
        foreach($preguntas as $row){
            $pre = $row->id; 
            $pregunta=Pregunta::find($pre);
            $respuestas = $pregunta->respuestas; 
            return view('encuestas.show')->with('encuesta', $encuesta)->with('preguntas', $preguntas)->with('respuestas',$respuestas);
        }
    }
    public function statusE(Request $request, $id){
        //dd($request);
        $encuesta = Encuesta::find($id);

        $encuesta->status = $request->get('status');

        $encuesta->save();

        return redirect('/encuestas');
      
    }
    public function resumen(Request $request,$id){
        //dd($request->get('conexionDB'));
        $contando=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id);
        foreach($contando as $row){
            $resultado = $row->conteo;
        }
 
        
        $promedE =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$id);
        if(!$promedE){
            return redirect('encuestas/')->with('nohayEncuestas', 'ok');
        }
        foreach($promedE as $rows){
            $proRes[]=$rows->Promedio;
            $suma = array_sum($proRes); 
        }
       
        $numeroComleto=$suma/$resultado;
        $proG=number_format($numeroComleto, 1);
        //dd($proG);

        /*RECOLECTANDO LOS PROMEDIOS DE CADA MES */
        
        $fechaActual= Carbon::now();
        $fechaBu=$fechaActual->format('Y-m-d');
        $ano=$fechaActual->formatLocalized("%Y");
        $mesActual=$fechaActual->formatLocalized("%B");
        $year[0] =  $fechaActual->formatLocalized("%B");
        //$proMesX= detalle_encuestas::select('Promedio')->whereDate('created_at',$fechaBu)->get();
        // $wordCount = $proMesX->count(); 
        $ultimos=DB::connection('sqlsrv')->select('SELECT TOP 10 * FROM detalle_encuestas where encuesta_id='.$id.' AND month='."'".$mesActual."'".'AND Year='."'".$ano."'".' ORDER BY id DESC ');
        //dd($ultimos);

        $proMesX=DB::connection('sqlsrv')->select('SELECT  * FROM detalle_encuestas WHERE encuesta_id='.$id.' AND month ='."'".$year[0]."'".'AND Year='."'".$ano."'");
        $wordCounts=DB::connection('sqlsrv')->select('SELECT COUNT(*) as numero FROM detalle_encuestas WHERE encuesta_id='.$id.' AND month ='."'".$year[0]."'".'AND Year='."'".$ano."'");
        if(!$proMesX){
            $promediosXmes=0;

        }else{
            foreach( $wordCounts as $countst ){
                $wordCount= $countst->numero; 
            }
            foreach($proMesX as $rowst){
                $proMes[]=$rowst->Promedio;
                $sumas = array_sum($proMes); 
            }
            $promedioMes=$sumas/$wordCount;
            $promediosXmes=number_format($promedioMes, 1);
        }
        //dd($promediosXmes);
        
     
        for($i=1; $i<=4; $i++){
           $year[] =  $fechaActual->subMonths(1)->formatLocalized("%B");
           //$promedio_mensual= detalle_encuestas::select('Promedio')->where('mount',$year[$i])->get();
           $promedio_mensual[]=DB::connection('sqlsrv')->select('SELECT  * FROM detalle_encuestas WHERE  encuesta_id='.$id.' AND month ='."'".$year[$i]."'");
           $contar[]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as numero FROM detalle_encuestas WHERE encuesta_id='.$id.' AND month ='."'".$year[$i]."'");
        }

        if(!$promedio_mensual[0]){
            $promediosXmesUno=0;
        }else{
            foreach(  $contar[0] as $counts ){
                $divide= $counts->numero; 
            }
            //dd($promedio_mensual[0]);
            foreach($promedio_mensual[0] as $mens){
                $mesUno[]=$mens->Promedio;
                $sumasMesUno= array_sum($mesUno); 
            }
            $promedioMesUno=$sumasMesUno/$divide;
            $promediosXmesUno=number_format($promedioMesUno, 1);
        }

        if(!$promedio_mensual[1]){
            $promediosXmesDos=0;
        }else{
            foreach(  $contar[1] as $counts1 ){
                $divide1= $counts1->numero; 
             }
            foreach($promedio_mensual[1] as $mens){
                $mesDos[]=$mens->Promedio;
                $sumasMesDos= array_sum($mesDos); 
            }
            //dd($promedio_mensual);
            $promedioMesDos=$sumasMesDos/$divide1;
            $promediosXmesDos=number_format($promedioMesDos, 1);
        }
        if(!$promedio_mensual[2 ]){
            $promediosXmesTres=0;

        }else{
            foreach(  $contar[2] as $counts2 ){
                $divide2= $counts2->numero; 
            }
            foreach($promedio_mensual[2] as $mens){
                $mesTres[]=$mens->Promedio;
                $sumasMesTres= array_sum($mesTres); 
            }
            $promedioMesTres=$sumasMesTres/$divide2;
            $promediosXmesTres=number_format($promedioMesTres, 1);

        }
        if(!$promedio_mensual[3]){
            $promediosXmesCuatro=0;

        }else{
            foreach(  $contar[3] as $counts3 ){
                $divide3= $counts3->numero; 
            }
            foreach($promedio_mensual[3] as $mens){
                $mesCuatro[]=$mens->Promedio;
                $sumasMesCuatro= array_sum($mesCuatro); 
            }
            $promedioMesCuatro=$sumasMesCuatro/$divide3;
            $promediosXmesCuatro=number_format($promedioMesCuatro, 1);

        }

        $user = [ number_format($promediosXmes,1),number_format($promediosXmesUno,1),number_format($promediosXmesDos,1),number_format($promediosXmesTres,1),number_format($promediosXmesCuatro,1)];
        foreach ($year as $key => $value) {
            $user[] = User::all();
        }
        $encuesta  = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
       /*foreach($ultimos as $pre){
            $Respuestas=DB::connection('sqlsvr')->select('SELECT * FROM detalle_encuestas WHERE encuesta_id='.$id.' AND Asesor='.$.' AND month ='."'".$year[0]."'");
        }*/
        $respuestas=DB::connection('sqlsrv')->select('SELECT P.pregunta,P.id as pregunta_id, R.respuesta, R.valor,EP.encuesta_id 
        FROM pregunta_respuesta PR 
		INNER JOIN respuestas R ON PR.respuesta_id = R.id 
		INNER JOIN preguntas P ON PR.pregunta_id = P.id 
		INNER JOIN encuesta_pregunta EP ON P.id = EP.pregunta_id 
        WHERE EP.encuesta_id='.$id);
         $aActual= Carbon::now()->format('Y');
         $mAcual= Carbon::now()->format('m');

        $encuestas=DB::connection('sqlsrv')->select('SELECT * from detalle_encuestas where encuesta_id='.$id);
        /*buscar total de ordenes asignadas */
        /*if($encuesta->departamento == 'VENTAS'){

        }*/
        
        $ordenes=DB::connection($request->get('conexionDB'))->select("SELECT OPCveOpe, OROrden, ORTipOrd, ORCliente, ORNombre, ORUser, ORDirec, ORColonia, ORCta, ORStatus, ORFecEnt, ORFecAlta 
        from SEORDSER WHERE DATEPART(MONTH, ORFecEnt) = ".$mAcual." AND  DATEPART(YEAR, ORFecEnt) = ".$aActual."  AND ORStatus='ce' AND ORTipOrd <>'s'AND ORTipOrd <>'i' AND ORTipOrd <>'h' AND ORTipOrd <>'g'");
        $contarTotOrd=count($ordenes);
        if($contarTotOrd == 0 ){
            $noContestadas=0;
            $contarTotOrd=0;
            $Contestadas=0;
            $PorcEncContes=0;
            $ordNoContes[]='No existen registros';
            $ordContes[]='No existen registros';
        }else{
            /*calculando las encuestas contestadas */
            foreach($ordenes as $row){
                $orden[]=$row->OROrden;
            }
            for($i=0; $i<$contarTotOrd; $i++){
                $ordenesContstadas=DB::connection('sqlsrv')->select("SELECT No_Orden FROM detalle_encuestas WHERE  No_Orden="."'".$orden[$i]."'");
                if(!$ordenesContstadas){
                    $ordNoContes[]=$orden[$i];
                }
                if($ordenesContstadas){
                    $ordContes[]=$orden[$i];
                }
            }
            $Contestadas=count($ordContes);
            $noContestadas=count($ordNoContes);
            $PorcEncContes=number_format(($Contestadas*100)/$contarTotOrd,2);
        }
        /*NOMBRE DE LOS ASESORES*/
        $asesores=array();
        foreach($ultimos as $asec){
            $asesor=$asec->Asesor;
           
            if (in_array($asesor, $asesores)) {
                
            }else{
                $asesores[]=$asesor;
            }
        }
        $buscarAses=array();
        $iterar=count($asesores);
        for($i=0; $i<$iterar; $i++){
            $buscarAses[]=DB::connection($request->get('conexionDB'))->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='.$asesores[$i]);
        }
        if(!$buscarAses){
            $buscarAses='No hay asesores';
        }
        

        
        //dd($encuestas,$ordenes);
    	return view('encuestas.resumen')
        ->with('year',json_encode($year,JSON_NUMERIC_CHECK))
        ->with('user',json_encode($user,JSON_NUMERIC_CHECK))
        ->with('resultado',$resultado)
        ->with('contestadas',$Contestadas)
        ->with('ordNoContes',$ordNoContes)
        ->with('ordContes',$ordContes)
        ->with('ultimos',$ultimos)
        ->with('preguntas', $preguntas)
        ->with('buscarAses', $buscarAses)
        ->with('ordenes', $ordenes)
        ->with('respuestas', $respuestas)
        ->with('contarTotOrd', $contarTotOrd)
        ->with('noContestadas', $noContestadas)
        ->with('PorcEncContes',$PorcEncContes)
        ->with('proG',$proG)
        ->with('encuesta',$encuesta)
        ->with('encuestas',$encuestas);
        
    }
    public function encuesta(Request $request,$id){
        /*CALCULANDO EL PROMEDIO GENERAL DE LA ENCUESTA */
        $contando=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id);
        foreach($contando as $row){
            $resultado = $row->conteo;
        }
        $promedE =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$id);
        if(!$promedE){
            return redirect('encuestas/')->with('nohayEncuestas', 'ok');
        }
        foreach($promedE as $rows){
            $proRes[]=$rows->Promedio;
            $suma = array_sum($proRes); 
        }
        $numeroComleto=$suma/$resultado;
        $proG=number_format($numeroComleto, 2);
        $encuestas=DB::connection('sqlsrv')->select('SELECT * FROM detalle_encuestas WHERE encuesta_id='.$id);
        $encuesta  = Encuesta::find($id);
        $preguntas = $encuesta->preguntas;
        $respuestas=DB::connection('sqlsrv')->select('SELECT P.pregunta,P.id as pregunta_id, R.respuesta, R.valor,EP.encuesta_id 
        FROM pregunta_respuesta PR 
		INNER JOIN respuestas R ON PR.respuesta_id = R.id 
		INNER JOIN preguntas P ON PR.pregunta_id = P.id 
		INNER JOIN encuesta_pregunta EP ON P.id = EP.pregunta_id 
        WHERE EP.encuesta_id='.$id);
        $asesores=array();
        foreach($encuestas as $asec){
            $asesor=$asec->Asesor;
           
            if (in_array($asesor, $asesores)) {
                
            }else{
                $asesores[]=$asesor;
            }
        }
       // dd($request->get('conexionDB'));
        $buscarAses=array();
        $iterar=count($asesores);
        for($i=0; $i<$iterar; $i++){
            $buscarAses[]=DB::connection($request->get('conexionDB'))->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='.$asesores[$i]);
        }
        if(!$buscarAses){
            $buscarAses='No hay asesores';
        }
        //dd($encuestas);
        return view('encuestas.encuestas')
        ->with('encuestas',$encuestas)
        ->with('buscarAses',$buscarAses)
        ->with('respuestas', $respuestas)
        ->with('preguntas', $preguntas)
        ->with('proG',$proG)
        ->with('encuesta',$encuesta);
    }
    public function estadisticas($id){
        $encuestas=Encuesta::find($id);
        $encuesta=$id;
        $fecha= Carbon::now();
        $anhos[0]=$fecha->formatLocalized("%Y");
        $mes1[0]=$fecha->formatLocalized("%B");
        $anho[0]=$fecha->formatLocalized("%Y");
        for($i=1; $i<=4; $i++){
            $anhos[$i]=$fecha->subYear(1)->formatLocalized("%Y");
        }  
        //dd($id);

        $meses=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

             /*Buscar promedos generales por mes y año*/
      /*ENERO*/
        if($mes1[0]==$meses[0]){
         /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;
           }else{
               foreach($promedE[0] as $rows){
                   $proRes[]=$rows->Promedio;
                   $suma[0]= array_sum($proRes); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */  
           $anho[1]=$anho[0]-1;
           $mes1[1]='diciembre';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }

           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);
           }
        /*mes actual menos 2 */
           $mes1[2]='noviembre';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }

           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;
           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);
           }
        /*mes actual menos 3 */
           $mes1[3]='octubre';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }

           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;
           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);
           }
        /*mes actual menos 4 */
           $mes1[4]='septiembre';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }

           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;
           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);
           }
       }
     /*FEBRERO*/
       elseif($mes1[0]==$meses[1]){
        /*mes actual*/
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $row){
               $resultadoNe[0] = $row->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='enero';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $anho[1]=$anho[0]-1;
           $mes1[2]='diciembre';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='noviembre';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='octubre';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*MARZO*/
       elseif($mes1[0]==$meses[2]){
        /*mes Actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='Febrero';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='enero';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $anho[1]=$anho[0]-1;
           $mes1[3]='diciembre';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='noviembre';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*ABRIL*/
       elseif($mes1[0]==$meses[3]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='marzo';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='febrero';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='enero';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
           $anho[1]=$anho[0]-1;
        /*mes actual menos 4 */
           $mes1[4]='diciembre';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }
       }
     /*MAYO*/
       elseif($mes1[0]==$meses[4]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
           foreach($positiva[0] as $rowP){
            $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsN){
                   $proRes[]=$rowsN->Promedio;
                   $suma[0]= array_sum($proRes); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='abril';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='marzo';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsEsw){
                   $proResEsw[]=$rowsEsw->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);
           }
        /*mes actual menos 3 */
           $mes1[3]='febrero';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }

           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
            $suma[3]=0;
            $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsEswt){
                   $proResEswt[]=$rowsEswt->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
           $anho[1]=$anho[0]-1;
        /*mes actual menos 4 */
           $mes1[4]='enero';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);
           }
       }
     /*JUNIO*/
       elseif($mes1[0]==$meses[5]){
        /*mes actual */  
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rows){
                   $proRes[]=$rows->Promedio;
                   $suma[0]= array_sum($proRes); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='mayo';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='abril';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='marzo';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResE); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='febrero';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*JULIO*/
       elseif($mes1[0]==$meses[6]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativas[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativas[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rows){
                   $proRes[]=$rows->Promedio;
                   $suma[0]= array_sum($proRes); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='junio';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='mayo';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='abril';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='marzo';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*AGOSTO*/
       elseif($mes1[0]==$meses[7]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='julio';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowNe){
               $resultadoNe[1] = $rowNe->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='junio';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $row){
               $resultadoNe[2] = $row->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='mayo';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='abril';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }
       }
     /*SEPTIEMBRE*/
       elseif($mes1[0]==$meses[8]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $row){
               $resultadoNe[0] = $row->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='agosto';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $row){
               $resultadoNe[1] = $row->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='julio';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $row){
               $resultadoNe[2] = $row->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='junio';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $row){
               $resultadoNe[3] = $row->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='mayo';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $row){
               $resultadoNe[4] = $row->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proReEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proReEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*OCTUBRE*/
       elseif($mes1[0]==$meses[9]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='septiembre';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='agosto';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='julio';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='junio';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*NOVIEMBRE*/
       elseif($mes1[0]==$meses[10]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $rowN){
               $resultadoNe[0] = $rowN->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='octubre';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $rowN){
               $resultadoNe[1] = $rowN->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='septiembre';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $rowN){
               $resultadoNe[2] = $rowN->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='agosto';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $rowN){
               $resultadoNe[3] = $rowN->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rowsN){
                   $proResNe[]=$rowsN->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEsw); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='julio';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $rowN){
               $resultadoNe[4] = $rowN->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rowsNe){
                   $proResNe[]=$rowsNe->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }

       }
     /*DCIEMBRE*/
       elseif($mes1[0]==$meses[11]){
        /*mes actual */
           $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           foreach($positivaPAST as $rowPAST){
               $resultadoPAST = $rowPAST->positiva;
           }
           foreach($perfectas as $rowPer){
              $resultadoPer = $rowPer->perfecta;
          }
           foreach($positiva[0] as $rowP){
               $resultadoPos[0] = $rowP->positiva;
           }
           foreach($negativa[0] as $row){
               $resultadoNe[0] = $row->negativa;
           }
           foreach($contando[0] as $row){
               $resultado[0] = $row->conteo;
           }
           $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[0]){
               $sumaPos[0]=0;
               $promediosPos[0]=0;
           }else{
               foreach($promePos[0] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[0]= array_sum($proResPos); 
               }
               $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
           }
           if(!$promeNe[0]){
               $sumaNe[0]=0;
               $promediosNe[0]=0;
           }else{
               foreach($promeNe[0] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[0]= array_sum($proResNe); 
               }
               $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
           }
           if(!$promedE[0]){
               $suma[0]=0;
               $promedios[0]=0;

           }else{
               foreach($promedE[0] as $rowsE){
                   $proResE[]=$rowsE->Promedio;
                   $suma[0]= array_sum($proResE); 
               }
               $promedios[0]=number_format($suma[0]/$resultado[0],1);
           }
        /*mes actual menos 1 */
           $mes1[1]='noviembre';
           $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[1] as $rowP){
               $resultadoPos[1] = $rowP->positiva;
           }
           foreach($negativa[1] as $row){
               $resultadoNe[1] = $row->negativa;
           }
           foreach($contando[1] as $row){
               $resultado[1] = $row->conteo;
           }
           $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[1]){
               $sumaPos[1]=0;
               $promediosPos[1]=0;
           }else{
               foreach($promePos[1] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[1]= array_sum($proResPos); 
               }
               $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
           }
           if(!$promeNe[1]){
               $sumaNe[1]=0;
               $promediosNe[1]=0;
           }else{
               foreach($promeNe[1] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[1]= array_sum($proResNe); 
               }
               $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
           }
           if(!$promedE[1]){
               $suma[1]=0;
               $promedios[1]=0;
           }else{
               foreach($promedE[1] as $rowsE){
                   $proResEs[]=$rowsE->Promedio;
                   $suma[1]= array_sum($proResEs); 
               }
               $promedios[1]=number_format($suma[1]/$resultado[1],1);

           }
        /*mes actual menos 2 */
           $mes1[2]='octubre';
           $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[2] as $rowP){
               $resultadoPos[2] = $rowP->positiva;
           }
           foreach($negativa[2] as $row){
               $resultadoNe[2] = $row->negativa;
           }
           foreach($contando[2] as $row){
               $resultado[2] = $row->conteo;
           }
           $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[2]){
               $sumaPos[2]=0;
               $promediosPos[2]=0;
           }else{
               foreach($promePos[2] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[2]= array_sum($proResPos); 
               }
               $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
           }
           if(!$promeNe[2]){
               $sumaNe[2]=0;
               $promediosNe[2]=0;
           }else{
               foreach($promeNe[2] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[2]= array_sum($proResNe); 
               }
               $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
           }
           if(!$promedE[2]){
               $suma[2]=0;
               $promedios[2]=0;

           }else{
               foreach($promedE[2] as $rowsE){
                   $proResEsw[]=$rowsE->Promedio;
                   $suma[2]= array_sum($proResEsw); 
               }
               $promedios[2]=number_format($suma[2]/$resultado[2],1);

           }
        /*mes actual menos 3 */
           $mes1[3]='septiembre';
           $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[3] as $rowP){
               $resultadoPos[3] = $rowP->positiva;
           }
           foreach($negativa[3] as $row){
               $resultadoNe[3] = $row->negativa;
           }
           foreach($contando[3] as $row){
               $resultado[3] = $row->conteo;
           }
           $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[3]){
               $sumaPos[3]=0;
               $promediosPos[3]=0;
           }else{
               foreach($promePos[3] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[3]= array_sum($proResPos); 
               }
               $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
           }
           if(!$promeNe[3]){
               $sumaNe[3]=0;
               $promediosNe[3]=0;
           }else{
               foreach($promeNe[3] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[3]= array_sum($proResNe); 
               }
               $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
           }
           if(!$promedE[3]){
               $suma[3]=0;
               $promedios[3]=0;

           }else{
               foreach($promedE[3] as $rowsE){
                   $proResEswt[]=$rowsE->Promedio;
                   $suma[3]= array_sum($proResEswt); 
               }
               $promedios[3]=number_format($suma[3]/$resultado[3],1);

           }
        /*mes actual menos 4 */
           $mes1[4]='agosto';
           $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           foreach($positiva[4] as $rowP){
               $resultadoPos[4] = $rowP->positiva;
           }
           foreach($negativa[4] as $row){
               $resultadoNe[4] = $row->negativa;
           }
           foreach($contando[4] as $row){
               $resultado[4] = $row->conteo;
           }
           $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
           $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
           if(!$promePos[4]){
               $sumaPos[4]=0;
               $promediosPos[4]=0;
           }else{
               foreach($promePos[4] as $rowsP){
                   $proResPos[]=$rowsP->Promedio;
                   $sumaPos[4]= array_sum($proResPos); 
               }
               $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
           }
           if(!$promeNe[4]){
               $sumaNe[4]=0;
               $promediosNe[4]=0;
           }else{
               foreach($promeNe[4] as $rows){
                   $proResNe[]=$rows->Promedio;
                   $sumaNe[4]= array_sum($proResNe); 
               }
               $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
           }
           if(!$promedE[4]){
               $suma[4]=0;
               $promedios[4]=0;

           }else{
               foreach($promedE[4] as $rowsE){
                   $proResEswty[]=$rowsE->Promedio;
                   $suma[4]= array_sum($proResEswty); 
               }
               $promedios[4]=number_format($suma[4]/$resultado[4],1);

           }
       }
     /*arreglos []*/
       for($i=0; $i<5; $i ++){
       $multiNeg[$i]=$resultadoNe[$i]*100;
       $multiPos[$i]=$resultadoPos[$i]*100;
       if($multiNeg[$i]==0){
           $porcentajeN[$i]=0;
       }else if($multiNeg[$i]!=0){
           $porcentajeN[$i]=number_format($multiNeg[$i]/$resultado[$i],0);
       }
       if($multiPos[$i]==0){
           $porcentajeP[$i]=0;
       }else if($multiPos[$i]!=0){
           $porcentajeP[$i]=number_format($multiPos[$i]/$resultado[$i],0);
       }
       }
       if($resultadoPer==0){
       $porcentajePer= 0;
       }else if($resultadoPer!= 0){
       $porcentajePer=number_format(($resultadoPer*100)/$resultado[0],0);
       }
       if($resultadoPAST==0){
       $porcentajePAST= 0;
       }else if($resultadoPAST!= 0){
       $porcentajePAST=number_format(($resultadoPAST*100)/$resultado[0],0);
       }
     /* */

     $pastelData=[$porcentajePer,$porcentajePAST,$porcentajeN[0]];
     $pastelLabels=['Perfectas','Positivas','Negativas'];
       $grafica[0]=[$mes1,$resultado,$promedios];
       $grafica[1]=[$resultadoNe,$porcentajeN];
       $grafica[2]=[$resultadoPos,$porcentajeP];
       $grafica[3]=[$pastelData,$pastelLabels];


        return view('encuestas.estadisticas')
        ->with('mes1',json_encode($mes1))
        ->with('promedios',json_encode($promedios,JSON_NUMERIC_CHECK))

        ->with('resultadoNe',json_encode($resultadoNe,JSON_NUMERIC_CHECK))
        ->with('porcentajeN',json_encode($porcentajeN,JSON_NUMERIC_CHECK))

        ->with('resultadoPos',json_encode($resultadoPos,JSON_NUMERIC_CHECK))
        ->with('porcentajeP',json_encode($porcentajeP,JSON_NUMERIC_CHECK))
        ->with('pastelData',json_encode($pastelData,JSON_NUMERIC_CHECK))

        ->with('pastelLabels',json_encode($pastelLabels))

        ->with('resultado',json_encode($resultado,JSON_NUMERIC_CHECK))
        ->with('anhos',$anhos)
        ->with('encuestas',$encuestas);

    }
    public function estadisticasAjax(Request $request){
        $encuesta = $request->get('encuesta');
        $mes1[0]=$request->get('mes1');
        $anho[0]=$request->get('anho');
        $meses=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
     /*Buscar promedos generales por mes y año*/
      /*ENERO*/
        if($mes1[0]==$meses[0]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;
            }else{
                foreach($promedE[0] as $rows){
                    $proRes[]=$rows->Promedio;
                    $suma[0]= array_sum($proRes); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */  
            $anho[1]=$anho[0]-1;
            $mes1[1]='diciembre';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }

            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);
            }
         /*mes actual menos 2 */
            $mes1[2]='noviembre';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }

            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;
            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);
            }
         /*mes actual menos 3 */
            $mes1[3]='octubre';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }

            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;
            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);
            }
         /*mes actual menos 4 */
            $mes1[4]='septiembre';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }

            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;
            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);
            }
        }
      /*FEBRERO*/
        elseif($mes1[0]==$meses[1]){
         /*mes actual*/
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $row){
                $resultadoNe[0] = $row->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='enero';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $anho[1]=$anho[0]-1;
            $mes1[2]='diciembre';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='noviembre';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='octubre';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*MARZO*/
        elseif($mes1[0]==$meses[2]){
         /*mes Actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='Febrero';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='enero';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $anho[1]=$anho[0]-1;
            $mes1[3]='diciembre';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='noviembre';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*ABRIL*/
        elseif($mes1[0]==$meses[3]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='marzo';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='febrero';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='enero';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
            $anho[1]=$anho[0]-1;
         /*mes actual menos 4 */
            $mes1[4]='diciembre';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[1]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }
        }
      /*MAYO*/
        elseif($mes1[0]==$meses[4]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
                $resultadoPer = $rowPer->perfecta;
            }
            foreach($positiva[0] as $rowP){
             $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsN){
                    $proRes[]=$rowsN->Promedio;
                    $suma[0]= array_sum($proRes); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='abril';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='marzo';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsEsw){
                    $proResEsw[]=$rowsEsw->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);
            }
         /*mes actual menos 3 */
            $mes1[3]='febrero';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }

            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
             $suma[3]=0;
             $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsEswt){
                    $proResEswt[]=$rowsEswt->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
            $anho[1]=$anho[0]-1;
         /*mes actual menos 4 */
            $mes1[4]='enero';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);
            }
        }
      /*JUNIO*/
        elseif($mes1[0]==$meses[5]){
         /*mes actual */  
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rows){
                    $proRes[]=$rows->Promedio;
                    $suma[0]= array_sum($proRes); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='mayo';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='abril';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='marzo';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResE); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='febrero';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*JULIO*/
        elseif($mes1[0]==$meses[6]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativas[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativas[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rows){
                    $proRes[]=$rows->Promedio;
                    $suma[0]= array_sum($proRes); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='junio';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='mayo';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='abril';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='marzo';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*AGOSTO*/
        elseif($mes1[0]==$meses[7]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $row){
                $resultadoNe[0] = $row->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='julio';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowNe){
                $resultadoNe[1] = $rowNe->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='junio';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $row){
                $resultadoNe[2] = $row->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='mayo';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $row){
                $resultadoNe[3] = $row->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='abril';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $row){
                $resultadoNe[4] = $row->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }
        }
      /*SEPTIEMBRE*/
        elseif($mes1[0]==$meses[8]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $row){
                $resultadoNe[0] = $row->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='agosto';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $row){
                $resultadoNe[1] = $row->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='julio';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $row){
                $resultadoNe[2] = $row->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='junio';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $row){
                $resultadoNe[3] = $row->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='mayo';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $row){
                $resultadoNe[4] = $row->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proReEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proReEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*OCTUBRE*/
        elseif($mes1[0]==$meses[9]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='septiembre';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='agosto';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='julio';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='junio';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*NOVIEMBRE*/
        elseif($mes1[0]==$meses[10]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $rowN){
                $resultadoNe[0] = $rowN->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='octubre';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $rowN){
                $resultadoNe[1] = $rowN->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='septiembre';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $rowN){
                $resultadoNe[2] = $rowN->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='agosto';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $rowN){
                $resultadoNe[3] = $rowN->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rowsN){
                    $proResNe[]=$rowsN->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEsw); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='julio';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $rowN){
                $resultadoNe[4] = $rowN->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rowsNe){
                    $proResNe[]=$rowsNe->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }

        }
      /*DCIEMBRE*/
        elseif($mes1[0]==$meses[11]){
         /*mes actual */
            $contando[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $negativa[0]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positiva[0]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $perfectas = DB::connection('sqlsrv')->select('SELECT COUNT(*) as perfecta FROM detalle_encuestas WHERE encuesta_id='.$encuesta.' AND promedio= 10'.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $positivaPAST=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND promedio < 10 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            foreach($positivaPAST as $rowPAST){
                $resultadoPAST = $rowPAST->positiva;
            }
            foreach($perfectas as $rowPer){
               $resultadoPer = $rowPer->perfecta;
           }
            foreach($positiva[0] as $rowP){
                $resultadoPos[0] = $rowP->positiva;
            }
            foreach($negativa[0] as $row){
                $resultadoNe[0] = $row->negativa;
            }
            foreach($contando[0] as $row){
                $resultado[0] = $row->conteo;
            }
            $promedE[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[0] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[0]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[0]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[0]){
                $sumaPos[0]=0;
                $promediosPos[0]=0;
            }else{
                foreach($promePos[0] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[0]= array_sum($proResPos); 
                }
                $promediosPos[0]=number_format($sumaPos[0]/$resultadoPos[0],1);
            }
            if(!$promeNe[0]){
                $sumaNe[0]=0;
                $promediosNe[0]=0;
            }else{
                foreach($promeNe[0] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[0]= array_sum($proResNe); 
                }
                $promediosNe[0]=number_format($sumaNe[0]/$resultadoNe[0],1);
            }
            if(!$promedE[0]){
                $suma[0]=0;
                $promedios[0]=0;

            }else{
                foreach($promedE[0] as $rowsE){
                    $proResE[]=$rowsE->Promedio;
                    $suma[0]= array_sum($proResE); 
                }
                $promedios[0]=number_format($suma[0]/$resultado[0],1);
            }
         /*mes actual menos 1 */
            $mes1[1]='noviembre';
            $contando[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $negativa[1]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $positiva[1]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[1] as $rowP){
                $resultadoPos[1] = $rowP->positiva;
            }
            foreach($negativa[1] as $row){
                $resultadoNe[1] = $row->negativa;
            }
            foreach($contando[1] as $row){
                $resultado[1] = $row->conteo;
            }
            $promedE[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[1] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[1]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[1]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[1]){
                $sumaPos[1]=0;
                $promediosPos[1]=0;
            }else{
                foreach($promePos[1] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[1]= array_sum($proResPos); 
                }
                $promediosPos[1]=number_format($sumaPos[1]/$resultadoPos[1],1);
            }
            if(!$promeNe[1]){
                $sumaNe[1]=0;
                $promediosNe[1]=0;
            }else{
                foreach($promeNe[1] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[1]= array_sum($proResNe); 
                }
                $promediosNe[1]=number_format($sumaNe[1]/$resultadoNe[1],1);
            }
            if(!$promedE[1]){
                $suma[1]=0;
                $promedios[1]=0;
            }else{
                foreach($promedE[1] as $rowsE){
                    $proResEs[]=$rowsE->Promedio;
                    $suma[1]= array_sum($proResEs); 
                }
                $promedios[1]=number_format($suma[1]/$resultado[1],1);

            }
         /*mes actual menos 2 */
            $mes1[2]='octubre';
            $contando[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $negativa[2]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $positiva[2]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[2] as $rowP){
                $resultadoPos[2] = $rowP->positiva;
            }
            foreach($negativa[2] as $row){
                $resultadoNe[2] = $row->negativa;
            }
            foreach($contando[2] as $row){
                $resultado[2] = $row->conteo;
            }
            $promedE[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[2] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[2]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[2]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[2]){
                $sumaPos[2]=0;
                $promediosPos[2]=0;
            }else{
                foreach($promePos[2] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[2]= array_sum($proResPos); 
                }
                $promediosPos[2]=number_format($sumaPos[2]/$resultadoPos[2],1);
            }
            if(!$promeNe[2]){
                $sumaNe[2]=0;
                $promediosNe[2]=0;
            }else{
                foreach($promeNe[2] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[2]= array_sum($proResNe); 
                }
                $promediosNe[2]=number_format($sumaNe[2]/$resultadoNe[2],1);
            }
            if(!$promedE[2]){
                $suma[2]=0;
                $promedios[2]=0;

            }else{
                foreach($promedE[2] as $rowsE){
                    $proResEsw[]=$rowsE->Promedio;
                    $suma[2]= array_sum($proResEsw); 
                }
                $promedios[2]=number_format($suma[2]/$resultado[2],1);

            }
         /*mes actual menos 3 */
            $mes1[3]='septiembre';
            $contando[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $negativa[3]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $positiva[3]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[3] as $rowP){
                $resultadoPos[3] = $rowP->positiva;
            }
            foreach($negativa[3] as $row){
                $resultadoNe[3] = $row->negativa;
            }
            foreach($contando[3] as $row){
                $resultado[3] = $row->conteo;
            }
            $promedE[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[3] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[3]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[3]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[3]){
                $sumaPos[3]=0;
                $promediosPos[3]=0;
            }else{
                foreach($promePos[3] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[3]= array_sum($proResPos); 
                }
                $promediosPos[3]=number_format($sumaPos[3]/$resultadoPos[3],1);
            }
            if(!$promeNe[3]){
                $sumaNe[3]=0;
                $promediosNe[3]=0;
            }else{
                foreach($promeNe[3] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[3]= array_sum($proResNe); 
                }
                $promediosNe[3]=number_format($sumaNe[3]/$resultadoNe[3],1);
            }
            if(!$promedE[3]){
                $suma[3]=0;
                $promedios[3]=0;

            }else{
                foreach($promedE[3] as $rowsE){
                    $proResEswt[]=$rowsE->Promedio;
                    $suma[3]= array_sum($proResEswt); 
                }
                $promedios[3]=number_format($suma[3]/$resultado[3],1);

            }
         /*mes actual menos 4 */
            $mes1[4]='agosto';
            $contando[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $negativa[4]=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as negativa FROM detalle_encuestas where promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $positiva[4]=DB::connection('sqlsrv')->select('SELECT COUNT(*) as positiva FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            foreach($positiva[4] as $rowP){
                $resultadoPos[4] = $rowP->positiva;
            }
            foreach($negativa[4] as $row){
                $resultadoNe[4] = $row->negativa;
            }
            foreach($contando[4] as $row){
                $resultado[4] = $row->conteo;
            }
            $promedE[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$encuesta.'AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            $promeNe[4] =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio < 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");           
            $promePos[4]=DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE promedio >= 7 AND encuesta_id='.$encuesta.' AND month ='."'".$mes1[4]."'".' AND year ='."'".$anho[0]."'");
            if(!$promePos[4]){
                $sumaPos[4]=0;
                $promediosPos[4]=0;
            }else{
                foreach($promePos[4] as $rowsP){
                    $proResPos[]=$rowsP->Promedio;
                    $sumaPos[4]= array_sum($proResPos); 
                }
                $promediosPos[4]=number_format($sumaPos[4]/$resultadoPos[4],1);
            }
            if(!$promeNe[4]){
                $sumaNe[4]=0;
                $promediosNe[4]=0;
            }else{
                foreach($promeNe[4] as $rows){
                    $proResNe[]=$rows->Promedio;
                    $sumaNe[4]= array_sum($proResNe); 
                }
                $promediosNe[4]=number_format($sumaNe[4]/$resultadoNe[4],1);
            }
            if(!$promedE[4]){
                $suma[4]=0;
                $promedios[4]=0;

            }else{
                foreach($promedE[4] as $rowsE){
                    $proResEswty[]=$rowsE->Promedio;
                    $suma[4]= array_sum($proResEswty); 
                }
                $promedios[4]=number_format($suma[4]/$resultado[4],1);

            }
        }
      /*arreglos []*/
        for($i=0; $i<5; $i ++){
        $multiNeg[$i]=$resultadoNe[$i]*100;
        $multiPos[$i]=$resultadoPos[$i]*100;
        if($multiNeg[$i]==0){
            $porcentajeN[$i]=0;
        }else if($multiNeg[$i]!=0){
            $porcentajeN[$i]=number_format($multiNeg[$i]/$resultado[$i],0);
        }
        if($multiPos[$i]==0){
            $porcentajeP[$i]=0;
        }else if($multiPos[$i]!=0){
            $porcentajeP[$i]=number_format($multiPos[$i]/$resultado[$i],0);
        }
        }
        if($resultadoPer==0){
        $porcentajePer= 0;
        }else if($resultadoPer!= 0){
        $porcentajePer=number_format(($resultadoPer*100)/$resultado[0],0);
        }
        if($resultadoPAST==0){
        $porcentajePAST= 0;
        }else if($resultadoPAST!= 0){
        $porcentajePAST=number_format(($resultadoPAST*100)/$resultado[0],0);
        }
      /* */

      $pastelData=[$porcentajePer,$porcentajePAST,$porcentajeN[0]];
      $pastelLabels=['Perfectas','Positivas','Negativas'];
        $grafica[0]=[$mes1,$resultado,$promedios];
        $grafica[1]=[$resultadoNe,$porcentajeN];
        $grafica[2]=[$resultadoPos,$porcentajeP];
        $grafica[3]=[$pastelData,$pastelLabels];
    
        return response(json_encode($grafica),200)->header('Content-type','text/plain');
    }
    public function detalle_diario($id){
        /*CALCULANDO EL PROMEDIO GENERAL DE LA ENCUESTA DIAPOR DIA */
        $fechaActual= Carbon::now();
        $dOdate=$fechaActual->formatLocalized("%B");

        $encuestas=DB::connection('sqlsrv')->select('SELECT AVG(Promedio) as Promedio,Odate FROM detalle_encuestas WHERE encuesta_id='.$id.' AND month='."'".$dOdate."'".'GROUP BY Odate ');
        $encuesta  = Encuesta::find($id);

       //dd($encuestas,$encuesta);
        return view('encuestas.detalle-diario')
        ->with('encuestas',$encuestas)
        ->with('encuesta', $encuesta);
    }
    public function detalleAjax(Request $request){
        $fecha=$request->get('fecha');//fecha elegida
        $id=(int)$request->get('encuesta');//id de la encuesta
        $Odate=stripslashes($fecha);//fecha sin ""
        $conexion=$request->get('conexionDB');//conexion
        /*prmedio del día */
        $encuestas=DB::connection('sqlsrv')->select('SELECT AVG(Promedio) as Promedio FROM detalle_encuestas WHERE encuesta_id='.$id." AND Odate="."'".$Odate."'");//promedio de la encuesta en el dia de la fecha elegida
        foreach($encuestas as $row){
            $promedios=$row->Promedio;
            $Promedio=number_format($promedios,2);
        }
        /*contando cuantas ordenes existen*/
        $Ord=DB::connection('sqlsrv')->select('SELECT COUNT(NO_Orden) as conteo FROM detalle_encuestas WHERE encuesta_id='.$id." AND Odate="."'".$Odate."'");
        foreach($Ord as $rows){
            $Total=$rows->conteo;
            $totalOrd=number_format($Total);
        }
        //cambiando el formato de la fecha con Carbon
        $carbon = new Carbon($fecha);
        $fechaConsulta=$carbon->format('Y-m-d H:i:s');
        //consulta todos los datos de las ordenes contestadas en el dia
        $OrdenData=DB::connection('sqlsrv')->select('SELECT * FROM detalle_encuestas  WHERE encuesta_id='.$id." AND Odate="."'".$Odate."'");
        $arrayNoContes = [];
        $consultaDataNoContes=[];
        if(!$OrdenData){
                $Promedio=0;
                $totalOrd=0;
                //consulta cuantas ordenes han sido ingresadas en el dia
                $ordenes=DB::connection($request->get('conexionDB'))->select("SELECT OPCveOpe, OROrden, ORTipOrd, ORCliente, ORNombre, ORUser, ORDirec, ORColonia, ORCta, ORStatus, ORFecEnt, ORFecAlta
                from SEORDSER WHERE ORFecEnt=(convert(datetime,"."'".$fechaConsulta."'".",102))  AND ORStatus='ce' AND ORTipOrd <>'s'AND ORTipOrd <>'i' AND ORTipOrd <>'h' AND ORTipOrd <>'g'");
                $contarTotOrd=count($ordenes);
                $consultaData[]='Sin Datos'; 
                $OrdenData[]='Sin Datos'; 
                if($ordenes){
                    foreach($ordenes as $ordens){
                        $arrayOrde[]=$ordens->OROrden;
                    }
                    foreach($arrayOrde as $raws){
                        $ONoContes=$raws;
                        $consultaDataNoContes[]=DB::connection($request->get('conexionDB'))->select('SELECT * FROM SEORDSER SEO 
                        INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE'."'".$ONoContes."'");
                    }
                }else if(!$ordenes){
                    $consultaDataNoContes[]=0;
                }
            }else{


                foreach($OrdenData as $orde){
                    $Ordene=$orde->No_Orden;
                    $OrdeneS[]=$orde->No_Orden;
                        $consultaData[]=DB::connection($request->get('conexionDB'))->select('SELECT * FROM SEORDSER SEO 
                        INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE'."'".$Ordene."'");
                }
                //consulta cuantas ordenes han sido ingresadas en el dia
                $ordenes=DB::connection($request->get('conexionDB'))->select("SELECT OPCveOpe, OROrden, ORTipOrd, ORCliente, ORNombre, ORUser, ORDirec, ORColonia, ORCta, ORStatus, ORFecEnt, ORFecAlta 
                from SEORDSER WHERE ORFecEnt=(convert(datetime,"."'".$fechaConsulta."'".",102))  AND ORStatus='ce' AND ORTipOrd <>'s'AND ORTipOrd <>'i' AND ORTipOrd <>'h' AND ORTipOrd <>'g'");
                $contarTotOrd=count($ordenes);
                foreach($ordenes as $ordens){
                    $arrayOrde[]=$ordens->OROrden;
                }
                for($i=0; $i< $contarTotOrd; $i++){
                    if (in_array($arrayOrde[$i], $OrdeneS)) {
                        $arrayContestadas[]=$arrayOrde[$i];
                    }else{
                        $arrayNoContes[]=$arrayOrde[$i];
                    }
                }
                        //buscando datos de las oredenes que aun no an sido contestadas
                foreach($arrayNoContes as $raws){
                    $ONoContes=$raws;
                    $consultaDataNoContes[]=DB::connection($request->get('conexionDB'))->select('SELECT * FROM SEORDSER SEO 
                    INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE'."'".$ONoContes."'");
                }
        //dd($consultaDataNoContes);
            }

        
        $detalle_diario=[$Promedio,$totalOrd,$contarTotOrd,$OrdenData,$fechaConsulta,$consultaData,$consultaDataNoContes];
        return response(json_encode($detalle_diario),200)->header('Content-type','text/plain');
    }
    public function asesores(Request $request,$id){
        $encuesta  = Encuesta::find($id);
        $fechaActual= Carbon::now();
        $mes=$fechaActual->formatLocalized("%B");
        $anho=$fechaActual->formatLocalized("%Y");
        $conexion=$request->get('conexionDB');
        $anhos[0]=$fechaActual->formatLocalized("%Y");
        $ases='todos';
      /*SELECT'S*/
        for($i=1; $i<=4; $i++){
            $anhos[$i]=$fechaActual->subYear(1)->formatLocalized("%Y");
        }  
        $buscar=DB::connection('sqlsrv')->select('SELECT  Asesor FROM detalle_encuestas WHERE encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
        $contando=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
        //dd($contando);
        if(!$contando){
            $encuestasTotalesMes=0;
        }else{
            foreach($contando as $cots){
                $encuestasTotalesMes[]=$cots->conteo;
            }
        }
        if(!$buscar){
            $asesores='No existen datos de esta fecha';
            $asesoresPastel[]='No existen datos de esta fecha';
            $contando=0;
            $PromediosAsesores=0;
            $NUMERO_DE_ASESORES=0;
            $conteosTot=0;
            $porcentajeEncTxAses[]=0;
            $asesoresList='';
        }else{
            foreach($buscar as $row){
                $asesor=$row->Asesor;
                $traerNombreTra=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $asesor);
                foreach($traerNombreTra as $tra){
                    $nombreEspacios=$tra->TraNom;
                    $nombre = trim($nombreEspacios);
                    $apellidoPEspacios=$tra->TraApPat;
                    $apellidoP = trim($apellidoPEspacios);
                    $numerodeTrabajador=$tra->TraCod;
                    $arrayAsesores[]=$nombre.' '.$apellidoP;
                    $asesoresList[]=[1=>$asesor, 2=>$nombre.' '.$apellidoP];
                }
                $NUMERO_DE_ASESORES=count($asesoresList);
            }
        //*variable de los asesores sin repetir */
            $asesores = array_unique($arrayAsesores);
            $asesoresPastel=array_unique($arrayAsesores);
            /*Calculando los promedios por asesor */
            $promedioXases=DB::connection('sqlsrv')->select('SELECT AVG(ALL Promedio) as PromedioAses FROM detalle_encuestas  where encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
            foreach($promedioXases as $rows){
                $promediAsesor=$rows->PromedioAses;
                $PromediosAsesores[]=number_format($promediAsesor,1);
            }
            foreach($contando as $CountTot){
                $conteosTot[]=$CountTot->conteo;
            }
            $TotalEncuestas=array_sum($conteosTot);
            foreach($conteosTot as $conte){
                $contEXases=$conte;
                $multEncTxAses=$contEXases*100;
                $porcentajePastel[]=number_format($multEncTxAses/$TotalEncuestas,1);
            }
            //dd($porcentajePastel);
        }
      /*SELECT'S */
      /*Promedio de asesores mensual y porsentaje de encuestas totales mensual por asesor */
        $promedioXases=DB::connection('sqlsrv')->select('SELECT AVG(ALL Promedio) as PromedioAses FROM detalle_encuestas  where encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
        foreach($promedioXases as $rows){
            $promediAsesor=$rows->PromedioAses;
            $PromediosAsesores[]=number_format($promediAsesor,1);
        }
        if(!$contando){
            $conteosTot=0;
        }else{
            foreach($contando as $CountTot){
                $conteosTot[]=$CountTot->conteo;
            }
            $TotalEncuestas=array_sum($conteosTot);
            foreach($conteosTot as $conte){
                $contEXases=$conte;
                $multEncTxAses=$contEXases*100;
                $porcentajeEncTxAses[]=number_format($multEncTxAses/$TotalEncuestas,1);
            }
        }
      /*Promedio de asesores mensual y porsentaje de encuestas totales mensual por asesor */
      /*Porcentajes Positivos por asesor*/
        $conteoPos=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Promedio > 7 GROUP BY Asesor');
        if(!$conteoPos){
            $conteosPos=0;
            $porcentajePos=0;
            $NumAses=0;
            $asesoresPos='no existen registros';
        }else{
            foreach($conteoPos as $CountPos){
                $buscaAsesPosi=$CountPos->Asesor;
                $buscarPosi=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Asesor='.$buscaAsesPosi);
                foreach($buscarPosi as $sub){
                    $arrayPositive[]=$sub->conteo;
                }
                $conteosPos[]=$CountPos->conteo;
                $NumAses[]=$CountPos->Asesor;
            }
            $conPosi=count($conteoPos);
            for($i=0; $i<$conPosi; $i++){
                $multiPos[]=$conteosPos[$i]*100;
                $porcentajePos[]=number_format($multiPos[$i]/$arrayPositive[$i],1);
            }
            foreach($NumAses as $asesPos){
                $BuscarAses=$asesPos;
                $traerNombreTraPos=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $BuscarAses);
                foreach($traerNombreTraPos as $traPos){
                    $nombreEspaciosPos=$traPos->TraNom;
                    $nombrePos = trim($nombreEspaciosPos);
                    $apellidoPEspaciosPos=$traPos->TraApPat;
                    $apellidoPPos = trim($apellidoPEspaciosPos);
                    $numerodeTrabajadorPos=$traPos->TraCod;
                    $arrayAsesoresPos[]=$nombrePos.' '.$apellidoPPos;
                    $asesoresListPos[]=[1=>$numerodeTrabajadorPos, 2=>$nombrePos.' '.$apellidoPPos];
                }
            }
            $asesoresPos = array_unique($arrayAsesoresPos);
        }
      /*Porcentajes Positivos por asesor*/
      /*Porcentajes Negativos por asesor*/
        $promedioNeg=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Promedio <= 7 GROUP BY Asesor');
        if(!$promedioNeg){
          $conteosNeg=0;
          $porcentajeNeg=0;
          $NumAsesNeg=0;
          $asesoresNeg='no existen registros';
        }else{
          foreach($promedioNeg as $CountNeg){
            $buscaAses=$CountNeg->Asesor;
            $buscarNeg=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Asesor='.$buscaAses);
                foreach($buscarNeg as $sub){
                    $arrayNegative[]=$sub->conteo;
                }
              $conteosNeg[]=$CountNeg->conteo;
              $NumAsesNeg[]=$CountNeg->Asesor;
          }
          $nontNega=count($conteosNeg);
          for($i=0; $i<$nontNega; $i++){
              $multiNeg[]=$conteosNeg[$i]*100;
              $porcentajeNeg[]=number_format($multiNeg[$i]/$arrayNegative[$i],1);
          }
          foreach($NumAsesNeg as $asesNeg){
              $BuscarAsesNeg=$asesNeg;
              $traerNombreTraNeg=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $BuscarAsesNeg);
              foreach($traerNombreTraNeg as $traNeg){
                  $nombreEspaciosNeg=$traNeg->TraNom;
                  $nombreNeg = trim($nombreEspaciosNeg);
                  $apellidoPEspaciosNeg=$traNeg->TraApPat;
                  $apellidoPNeg = trim($apellidoPEspaciosNeg);
                  $numerodeTrabajadorNeg=$traNeg->TraCod;
                  $arrayAsesoresNeg[]=$nombreNeg.' '.$apellidoPNeg;
                  $asesoresListNeg[]=[1=>$numerodeTrabajadorNeg, 2=>$nombreNeg.' '.$apellidoPNeg];
              }
          }
          $asesoresNeg = array_unique($arrayAsesoresNeg);
        }
      /*Porcentajes Negativos por asesor*/
      /*Promedio por pregunta*/
        /* 1*/
        $RP1=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P1, DT.Resp_P1, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P1 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA=count($RP1);
        if(!$RP1){
            $porcentajePRE1=0;
            $PRE1='No existe';
        }else{
            foreach($RP1 as $rows){
                $proPRE1[]=$rows->Resp_P1;
                $PRE1=$rows->pregunta;
                $sumaPRE1 = array_sum($proPRE1); 
            }
            $porcentajePRE1=number_format($sumaPRE1 / $TotalA,1);
        }
        /*2*/
        $RP2=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P2, DT.Resp_P2, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P2 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA2=count($RP2);

        if(!$RP2){
            $porcentajePRE2=0;
            $PRE2='No existe';
        }else{
            foreach($RP2 as $rows2){
                $proPRE2[]=$rows2->Resp_P2;
                $PRE2=$rows2->pregunta;
                $sumaPRE2 = array_sum($proPRE2); 
            }
            $porcentajePRE2=number_format($sumaPRE2 / $TotalA2,1);
        }
        /*3*/
        $RP3=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P3, DT.Resp_P3, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P3 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA3=count($RP3);

        if(!$RP3){
            $porcentajePRE3=0;
            $PRE3='No existe';
        }else{
            foreach($RP3 as $rows3){
                $proPRE3[]=$rows3->Resp_P3;
                $PRE3=$rows3->pregunta;
                $sumaPRE3 = array_sum($proPRE3); 
            }
            $porcentajePRE3=number_format($sumaPRE3 / $TotalA3,1);
        }
        /*4*/
        $RP4=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P4, DT.Resp_P4, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P4 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA4=count($RP4);
        if(!$RP4){
            $porcentajePRE4=0;
            $PRE4='No existe';
        }else{
            foreach($RP4 as $rows4){
                $proPRE4[]=$rows4->Resp_P4;
                $PRE4=$rows4->pregunta;
                $sumaPRE4 = array_sum($proPRE4); 
            }
            $porcentajePRE4=number_format($sumaPRE4 / $TotalA4,1);
        }
        /*5*/
        $RP5=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P5, DT.Resp_P5, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P5 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA5=count($RP5);

        if(!$RP5){
            $porcentajePRE5=0;
            $PRE5='No existe';
        }else{
            foreach($RP5 as $rows5){
                $proPRE5[]=$rows5->Resp_P5;
                $PRE5=$rows5->pregunta;
                $sumaPRE5 = array_sum($proPRE5); 
            }
            $porcentajePRE5=number_format($sumaPRE5 / $TotalA5,1);
        }
        /*6*/
        $RP6=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P6, DT.Resp_P6, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P6 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA6=count($RP6);
        if(!$RP6){
            $porcentajePRE6=0;
            $PRE6='No existe';
        }else{
            foreach($RP6 as $rows6){
                $proPRE6[]=$rows6->Resp_P6;
                $PRE6=$rows6->pregunta;
                $sumaPRE6 = array_sum($proPRE6); 
            }
            $porcentajePRE6=number_format($sumaPRE6 / $TotalA6,1);
        }
        /*7*/
        $RP7=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P7, DT.Resp_P7, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P7 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA7=count($RP7);

        if(!$RP7){
            $porcentajePRE7=0;
            $PRE7='No existe';
        }else{
            foreach($RP7 as $rows7){
                $proPRE7[]=$rows7->Resp_P7;
                $PRE7=$rows7->pregunta;
                $sumaPRE7 = array_sum($proPRE7); 
            }
            $porcentajePRE7=number_format($sumaPRE7 / $TotalA7,1);
        }
        /*8*/
        $RP8=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P8, DT.Resp_P8, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P8 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA8=count($RP8);

        if(!$RP8){
            $porcentajePRE8=0;
            $PRE8='No existe';
        }else{
            foreach($RP8 as $rows8){
                $proPRE8[]=$rows8->Resp_P8;
                $PRE8=$rows8->pregunta;
                $sumaPRE8 = array_sum($proPRE8); 
            }
            $porcentajePRE8=number_format($sumaPRE8 / $TotalA8,1);
        }
        /*9*/ 
        $RP9=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P9, DT.Resp_P9, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P9 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA9=count($RP9);

        if(!$RP9){
            $porcentajePRE9=0;
            $PRE9='No existe';
        }else{
            foreach($RP9 as $rows9){
                $proPRE9[]=$rows9->Resp_P9;
                $PRE9=$rows9->pregunta;
                $sumaPRE9 = array_sum($proPRE9); 
            }
            $porcentajePRE9=number_format($sumaPRE9 / $TotalA9,1);
        }
        /*10*/
        $RP10=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P10, DT.Resp_P10, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P10 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
        $TotalA10=count($RP10);

        if(!$RP10){
            $porcentajePRE10=0;
            $PRE10='No existe';
        }else{
            foreach($RP10 as $rows10){
                $proPRE10[]=$rows10->Resp_P10;
                $PRE10=$rows10->pregunta;
                $sumaPRE10 = array_sum($proPRE10); 
            }
            $porcentajePRE10=number_format($sumaPRE10 / $TotalA10,1);
        }
      /*Promedio por pregunta*/
      /*Porcentajes Negativos por asesor*/
      /*arreglos*/
      
        $pormediosPregAses=[$porcentajePRE1,$porcentajePRE2,$porcentajePRE3,$porcentajePRE4,$porcentajePRE5,$porcentajePRE6,$porcentajePRE7,$porcentajePRE8,$porcentajePRE9,$porcentajePRE10];
        $preguntasAses=[$PRE1,$PRE2,$PRE3,$PRE4,$PRE5,$PRE6,$PRE7,$PRE8,$PRE9,$PRE10];
        $porcentajePastel=$porcentajeEncTxAses;

     /*arreglos*/
        return view('encuestas.asesores')
        ->with('anhos',$anhos)
        ->with('mes',json_encode($mes))
        ->with('asesoresList',$asesoresList)
        ->with('asesores',json_encode($asesores))
        ->with('asesoresPastel',json_encode($asesoresPastel))
        ->with('asesoresPos',json_encode($asesoresPos))
        ->with('asesoresNeg',json_encode($asesoresNeg))
        ->with('preguntasAses',json_encode($preguntasAses))
        ->with('pormediosPregAses',json_encode($pormediosPregAses,JSON_NUMERIC_CHECK))
        ->with('porcentajePastel',json_encode($porcentajePastel,JSON_NUMERIC_CHECK))
        ->with('porcentajePos',json_encode($porcentajePos,JSON_NUMERIC_CHECK))
        ->with('encuestasTotalesMes',json_encode($encuestasTotalesMes,JSON_NUMERIC_CHECK))
        ->with('porcentajeNeg',json_encode($porcentajeNeg,JSON_NUMERIC_CHECK))
        ->with('conteosPos',json_encode($conteosPos,JSON_NUMERIC_CHECK))
        ->with('conteosNeg',json_encode($conteosNeg,JSON_NUMERIC_CHECK))
        ->with('PromediosAsesores',json_encode($PromediosAsesores,JSON_NUMERIC_CHECK))
        ->with('encuesta',$encuesta);
    }
    public function asesoresAjax(Request $request){
        $id = $request->get('encuesta');
        $ases = $request->get('asesor');
        $mes = $request->get('mes1');
        $anho = $request->get('anho');
        $conexion = $request->get('conexion');

     /*CALCULANDO EL PROMEDIO GENERAL DE LOS ASESORES*/
        $buscar=DB::connection('sqlsrv')->select('SELECT  Asesor FROM detalle_encuestas WHERE encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
        $contando=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
        if(!$buscar){
            $asesores='No existen datos de esta fecha';
            $asesoresPastel[]='No existen datos de esta fecha';
            $contando=0;
            $PromediosAsesores=0;
            $NUMERO_DE_ASESORES=0;
            $conteosTot=0;
            $porcentajeEncTxAses[]=0;

        }else{
            foreach($buscar as $row){
                $asesor=$row->Asesor;
                //dd($conexion);
                $traerNombreTra=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $asesor);
                foreach($traerNombreTra as $tra){
                    $nombreEspacios=$tra->TraNom;
                    $nombre = trim($nombreEspacios);
                    $apellidoPEspacios=$tra->TraApPat;
                    $apellidoP = trim($apellidoPEspacios);
                    $numerodeTrabajador=$tra->TraCod;
                    $arrayAsesores[]=$nombre.' '.$apellidoP;
                    $asesoresList[]=[1=>$asesor, 2=>$nombre.' '.$apellidoP];
                }
                $NUMERO_DE_ASESORES=count($asesoresList);
            }
            /*variable de los asesores sin repetir */
            $asesores = array_unique($arrayAsesores);
            $asesoresPastel=array_unique($arrayAsesores);
            /*Calculando los promedios por asesor */
            $promedioXases=DB::connection('sqlsrv')->select('SELECT AVG(ALL Promedio) as PromedioAses FROM detalle_encuestas  where encuesta_id='.$id.'AND month= '."'".$mes."'".' AND Year='.$anho.' GROUP BY Asesor');
            foreach($promedioXases as $rows){
                $promediAsesor=$rows->PromedioAses;
                $PromediosAsesores[]=number_format($promediAsesor,1);
            }
            foreach($contando as $CountTot){
                $conteosTot[]=$CountTot->conteo;
            }
            $TotalEncuestas=array_sum($conteosTot);
            foreach($conteosTot as $conte){
                $contEXases=$conte;
                $multEncTxAses=$contEXases*100;
                $porcentajeEncTxAses[]=number_format($multEncTxAses/$TotalEncuestas,1);
             }
        }
     /*CALCULANDO EL PROMEDIO GENERAL DE LOS ASESORES*/
     /*Porcentajes Positivos por asesor*/
        $conteoPos=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Promedio > 7 GROUP BY Asesor');
        if(!$conteoPos){
            $conteosPos=0;
            $porcentajePos=0;
            $NumAses=0;
            $asesoresPos='no existen registros';
        }else{
            foreach($conteoPos as $CountPos){
                $buscaAsesPosi=$CountPos->Asesor;
                $buscarPosi=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Asesor='.$buscaAsesPosi);
                foreach($buscarPosi as $sub){
                    $arrayPositive[]=$sub->conteo;
                }
                $conteosPos[]=$CountPos->conteo;
                $NumAses[]=$CountPos->Asesor;
            }
            $conteosPosotives=count($conteosPos);
            for($i=0; $i<$conteosPosotives; $i++){
                $multiPos[]=$conteosPos[$i]*100;
                $porcentajePos[]=number_format($multiPos[$i]/$arrayPositive[$i],1);
            }
            foreach($NumAses as $asesPos){
                $BuscarAses=$asesPos;
                $traerNombreTraPos=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $BuscarAses);
                foreach($traerNombreTraPos as $traPos){
                    $nombreEspaciosPos=$traPos->TraNom;
                    $nombrePos = trim($nombreEspaciosPos);
                    $apellidoPEspaciosPos=$traPos->TraApPat;
                    $apellidoPPos = trim($apellidoPEspaciosPos);
                    $numerodeTrabajadorPos=$traPos->TraCod;
                    $arrayAsesoresPos[]=$nombrePos.' '.$apellidoPPos;
                    $asesoresListPos[]=[1=>$numerodeTrabajadorPos, 2=>$nombrePos.' '.$apellidoPPos];
                }
            }
            $asesoresPos = array_unique($arrayAsesoresPos);
        }
     /*Porcentajes Positivos por asesor*/
     /*Porcentajes Negativos por asesor*/
        $promedioNeg=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo, Asesor FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Promedio <= 7 GROUP BY Asesor');
        //dd($promedioNeg);

        if(!$promedioNeg){
            $conteosNeg=0;
            $porcentajeNeg=0;
            $NumAsesNeg=0;
            $asesoresNeg='no existen registros';
            $arrayNegative=[0,0,0,0,0];
        }else{
            foreach($promedioNeg as $CountNeg){
                $buscaAses=$CountNeg->Asesor;
                $buscarNeg=DB::connection('sqlsrv')->select('SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id.' AND month= '."'".$mes."'".' AND Year='.$anho.' AND Asesor='.$buscaAses);
                    foreach($buscarNeg as $sub){
                        $arrayNegative[]=$sub->conteo;
                    }
                $conteosNeg[]=$CountNeg->conteo;
                $NumAsesNeg[]=$CountNeg->Asesor;

            }
            $conarNEG=count($conteosNeg);
            for($i=0; $i<$conarNEG; $i++){
                

                $multiNeg[]=$conteosNeg[$i]*100;
                $porcentajeNeg[]=number_format($multiNeg[$i]/$arrayNegative[$i],1);
            }
            
            foreach($NumAsesNeg as $asesNeg){
                $BuscarAsesNeg=$asesNeg;
                $traerNombreTraNeg=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod='. $BuscarAsesNeg);
                foreach($traerNombreTraNeg as $traNeg){
                    $nombreEspaciosNeg=$traNeg->TraNom;
                    $nombreNeg = trim($nombreEspaciosNeg);
                    $apellidoPEspaciosNeg=$traNeg->TraApPat;
                    $apellidoPNeg = trim($apellidoPEspaciosNeg);
                    $numerodeTrabajadorNeg=$traNeg->TraCod;
                    $arrayAsesoresNeg[]=$nombreNeg.' '.$apellidoPNeg;
                    $asesoresListNeg[]=[1=>$numerodeTrabajadorNeg, 2=>$nombreNeg.' '.$apellidoPNeg];
                }
            }
            $asesoresNeg = array_unique($arrayAsesoresNeg);
        }   
     /*Porcentajes Negativos por asesor*/
     /*Promedio por pregunta*/
        /* 1*/
        if($ases=='todos'){
            $RP1=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P1, DT.Resp_P1, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P1 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA=count($RP1);
        }else{
            $RP1=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P1, DT.Resp_P1, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P1 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA=count($RP1);
        }
        if(!$RP1){
            $porcentajePRE1=0;
            $PRE1='No existe';
        }else{
            foreach($RP1 as $rows){
                $proPRE1[]=$rows->Resp_P1;
                $PRE1=$rows->pregunta;
                $sumaPRE1 = array_sum($proPRE1); 
            }
            $porcentajePRE1=number_format($sumaPRE1 / $TotalA,1);
        }
        /*2*/
            if($ases=='todos'){
            $RP2=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P2, DT.Resp_P2, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P2 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA2=count($RP2);
            }else{
            $RP2=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P2, DT.Resp_P2, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P2 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA2=count($RP2);
            }
            if(!$RP2){
                $porcentajePRE2=0;
                $PRE2='No existe';
            }else{
                foreach($RP2 as $rows2){
                    $proPRE2[]=$rows2->Resp_P2;
                    $PRE2=$rows2->pregunta;
                    $sumaPRE2 = array_sum($proPRE2); 
                }
                $porcentajePRE2=number_format($sumaPRE2 / $TotalA2,1);
            }
        /*3*/
        if($ases=='todos'){
            $RP3=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P3, DT.Resp_P3, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P3 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA3=count($RP3);
        }else{
            $RP3=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P3, DT.Resp_P3, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P3 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA3=count($RP3);
        }
        if(!$RP3){
            $porcentajePRE3=0;
            $PRE3='No existe';
        }else{
            foreach($RP3 as $rows3){
                $proPRE3[]=$rows3->Resp_P3;
                $PRE3=$rows3->pregunta;
                $sumaPRE3 = array_sum($proPRE3); 
            }
            $porcentajePRE3=number_format($sumaPRE3 / $TotalA3,1);
        }
        /*4*/
        if($ases=='todos'){
            $RP4=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P4, DT.Resp_P4, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P4 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA4=count($RP4);
        }else{
            $RP4=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P4, DT.Resp_P4, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P4 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA4=count($RP4);
        }
        if(!$RP4){
            $porcentajePRE4=0;
            $PRE4='No existe';
        }else{
            foreach($RP4 as $rows4){
                $proPRE4[]=$rows4->Resp_P4;
                $PRE4=$rows4->pregunta;
                $sumaPRE4 = array_sum($proPRE4); 
            }
            $porcentajePRE4=number_format($sumaPRE4 / $TotalA4,1);
        }
        /*5*/
        if($ases=='todos'){
            $RP5=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P5, DT.Resp_P5, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P5 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA5=count($RP5);
        }else{
            $RP5=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P5, DT.Resp_P5, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P5 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA5=count($RP5);
        }
        if(!$RP5){
            $porcentajePRE5=0;
            $PRE5='No existe';
        }else{
            foreach($RP5 as $rows5){
                $proPRE5[]=$rows5->Resp_P5;
                $PRE5=$rows5->pregunta;
                $sumaPRE5 = array_sum($proPRE5); 
            }
            $porcentajePRE5=number_format($sumaPRE5 / $TotalA5,1);
        }
        /*6*/
        if($ases=='todos'){
            $RP6=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P6, DT.Resp_P6, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P6 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA6=count($RP6);
        }else{
            $RP6=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P6, DT.Resp_P6, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P6 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA6=count($RP6);
        }
        if(!$RP6){
            $porcentajePRE6=0;
            $PRE6='No existe';
        }else{
            foreach($RP6 as $rows6){
                $proPRE6[]=$rows6->Resp_P6;
                $PRE6=$rows6->pregunta;
                $sumaPRE6 = array_sum($proPRE6); 
            }
            $porcentajePRE6=number_format($sumaPRE6 / $TotalA6,1);
        }
        /*7*/
        if($ases=='todos'){
            $RP7=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P7, DT.Resp_P7, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P7 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA7=count($RP7);
        }else{
            $RP7=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P7, DT.Resp_P7, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P7 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA7=count($RP7);
        }
        if(!$RP7){
            $porcentajePRE7=0;
            $PRE7='No existe';
        }else{
            foreach($RP7 as $rows7){
                $proPRE7[]=$rows7->Resp_P7;
                $PRE7=$rows7->pregunta;
                $sumaPRE7 = array_sum($proPRE7); 
            }
            $porcentajePRE7=number_format($sumaPRE7 / $TotalA7,1);
        }
        /*8*/
        if($ases=='todos'){
            $RP8=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P8, DT.Resp_P8, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P8 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA8=count($RP8);
        }else{
            $RP8=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P8, DT.Resp_P8, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P8= p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA8=count($RP8);
        }
        if(!$RP8){
            $porcentajePRE8=0;
            $PRE8='No existe';
        }else{
            foreach($RP8 as $rows8){
                $proPRE8[]=$rows8->Resp_P8;
                $PRE8=$rows8->pregunta;
                $sumaPRE8 = array_sum($proPRE8); 
            }
            $porcentajePRE8=number_format($sumaPRE8 / $TotalA8,1);
        }
        /*9*/ 
        if($ases=='todos'){
            $RP9=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P9, DT.Resp_P9, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P9 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA9=count($RP9);
        }else{
            $RP9=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P9, DT.Resp_P9, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P9= p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA9=count($RP9);
        }
        if(!$RP9){
            $porcentajePRE9=0;
            $PRE9='No existe';
        }else{
            foreach($RP9 as $rows9){
                $proPRE9[]=$rows9->Resp_P9;
                $PRE9=$rows9->pregunta;
                $sumaPRE9 = array_sum($proPRE9); 
            }
            $porcentajePRE9=number_format($sumaPRE9 / $TotalA9,1);
        }
        /*10*/
        if($ases=='todos'){
            $RP10=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P10, DT.Resp_P10, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P10 = p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'" );
            $TotalA10=count($RP10);
        }else{
            $RP10=DB::connection('sqlsrv')->select('SELECT DT.id, DT.P10, DT.Resp_P10, p.pregunta FROM detalle_encuestas DT INNER JOIN preguntas p ON DT.P10= p.id WHERE encuesta_id='.$id.' AND Year='.$anho.' AND DT.month='."'".$mes."'".' AND Asesor='."'".$ases."'" );
            $TotalA10=count($RP10);
        }
        if(!$RP10){
            $porcentajePRE10=0;
            $PRE10='No existe';
        }else{
            foreach($RP10 as $rows10){
                $proPRE10[]=$rows10->Resp_P10;
                $PRE10=$rows10->pregunta;
                $sumaPRE10 = array_sum($proPRE10); 
            }
            $porcentajePRE10=number_format($sumaPRE10 / $TotalA10,1);
        }
     /*Asesor*/
        if($ases=='todos'){ 
            $NombreAsesor='todas las encuestas';
        }else{
            $NombreAsesorConEspacios=DB::connection($request->get('conexion'))->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat FROM  NRCATTRA WHERE TraCod='. $ases);
            foreach($NombreAsesorConEspacios as $tras){
                $nombreEspacioss=$tras->TraNom;
                $nombres = trim($nombreEspacioss);
                $apellidoPEspacioss=$tras->TraApPat;
                $apellidoPs = trim($apellidoPEspacioss);
                $NombreAsesor=$nombres.' '.$apellidoPs;
            }
        }
     /*arreglos*/
            $pormediosPregAses=[$porcentajePRE1,$porcentajePRE2,$porcentajePRE3,$porcentajePRE4,$porcentajePRE5,$porcentajePRE6,$porcentajePRE7,$porcentajePRE8,$porcentajePRE9,$porcentajePRE10];
            $preguntasAses=[$PRE1,$PRE2,$PRE3,$PRE4,$PRE5,$PRE6,$PRE7,$PRE8,$PRE9,$PRE10];

            $PromTotXasesor=[$asesores,$conteosTot,$PromediosAsesores];
            $graficaEncuesPos=[$asesoresPos,$conteosPos,$porcentajePos];
            $graficaEncuesNeg=[$asesoresNeg,$conteosNeg,$porcentajeNeg];
            $graficaPETXAeses=[$asesoresPastel,$porcentajeEncTxAses];
            $graficaPromPregAses=[$preguntasAses,$conteosTot,$pormediosPregAses];
    
        $graficasAsesores=[$PromTotXasesor,$graficaEncuesNeg,$graficaEncuesPos,$graficaPETXAeses,$graficaPromPregAses,$NombreAsesor,$conteosNeg,$porcentajeNeg,$arrayNegative];

        return response(json_encode($graficasAsesores),200)->header('Content-type','text/plain');
    }
    public function configuracion($id){
        /*CALCULANDO EL PROMEDIO GENERAL DE LA ENCUESTA */
        $contando=DB::connection('sqlsrv')->select(' SELECT COUNT(*) as conteo FROM detalle_encuestas where encuesta_id='.$id);
        foreach($contando as $row){
            $resultado = $row->conteo;
        }
        $promedE =DB::CONNECTION('sqlsrv')->SELECT('SELECT  Promedio FROM detalle_encuestas WHERE encuesta_id='.$id);
        if(!$promedE){
            return redirect('encuestas/')->with('nohayEncuestas', 'ok');
        }
        foreach($promedE as $rows){
            $proRes[]=$rows->Promedio;
            $suma = array_sum($proRes); 
        }
        $numeroComleto=$suma/$resultado;
        $proG=number_format($numeroComleto, 4);

        $encuestas=DB::connection('sqlsrv')->select('SELECT * FROM detalle_encuestas WHERE encuesta_id='.$id);
       
        $encuesta  = Encuesta::find($id);
        return view('encuestas.configuracion')
        ->with('encuestas',$encuestas)
        ->with('proG',$proG)
        ->with('encuesta',$encuesta);
    }
    public function grafica($encuesta,$id){
        $encuesta=Encuesta::find($encuesta);
        return view('encuestas.grafica')->with('id',$id)->with('encuesta',$encuesta);
    }
    public function ajaxGrafica(Request $request){
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');
        $encuesta = $request->get('encuesta');
        $asesor = $request->get('asesor');

        $contarEncuestas=DB::connection('sqlsrv')->select("SELECT count(*) as numero FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P1 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            foreach($contarEncuestas as $r){
                $contar=$r->numero;
            }
        $RP1=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P1, DT.Resp_P1, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P1 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP1){
                foreach($RP1 as $rows){
                    $proPRE1[]=$rows->Resp_P1;
                    $PRE1=$rows->pregunta;
                    $sumaPRE1 = array_sum($proPRE1);
                }
                $contar1=$contar;
                $porcentajePRE1=number_format($sumaPRE1 / $contar,2);
            }else{
                $porcentajePRE1=0;
                $PRE1='No existe';
                $contar1=0;
            }
            $RP2=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P2, DT.Resp_P2, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P2 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP2){
                foreach($RP2 as $rows){
                    $proPRE2[]=$rows->Resp_P2;
                    $PRE2=$rows->pregunta;
                    $sumaPRE2 = array_sum($proPRE2);
                }
                $contar2=$contar;
                $porcentajePRE2=number_format($sumaPRE2 / $contar,2);
            }else{
                $porcentajePRE2=0;
                $PRE2='No existe';
                $contar2=0;


            }
            $RP3=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P3, DT.Resp_P3, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P3 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP3){
                foreach($RP3 as $rows){
                    $proPRE3[]=$rows->Resp_P3;
                    $PRE3=$rows->pregunta;
                    $sumaPRE3 = array_sum($proPRE3);
                }
                $contar3=$contar;
                $porcentajePRE3=number_format($sumaPRE3 / $contar,2);
            }else{
                $porcentajePRE3=0;
                $PRE3='No existe';
                $contar3=0;


            }
            $RP4=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P4, DT.Resp_P4, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P4 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP4){
                foreach($RP4 as $rows){
                    $proPRE4[]=$rows->Resp_P4;
                    $PRE4=$rows->pregunta;
                    $sumaPRE4 = array_sum($proPRE4);
                }
                $contar4=$contar;
                $porcentajePRE4=number_format($sumaPRE4 / $contar,2);
            }else{
                $porcentajePRE4=0;
                $PRE4='No existe';
                $contar4=0;

            }
            $RP5=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P5, DT.Resp_P5, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P5 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP5){
                foreach($RP5 as $rows){
                    $proPRE5[]=$rows->Resp_P5;
                    $PRE5=$rows->pregunta;
                    $sumaPRE5 = array_sum($proPRE5);
                }
                $contar5=$contar;
                $porcentajePRE5=number_format($sumaPRE5 / $contar,2);
            }else{
                $porcentajePRE5=0;
                $PRE5='No existe';
                $contar5=0;
            }
            $RP6=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P6, DT.Resp_P6, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P6 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP6){
                foreach($RP6 as $rows){
                    $proPRE6[]=$rows->Resp_P6;
                    $PRE6=$rows->pregunta;
                    $sumaPRE6 = array_sum($proPRE6);
                }
                $contar6=$contar;
                $porcentajePRE6=number_format($sumaPRE6 / $contar,2);
            }else{
                $porcentajePRE6=0;
                $PRE6='No existe';
                $contar6=0;

            }
            $RP7=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P7, DT.Resp_P7, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P7 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP7){
                foreach($RP7 as $rows){
                    $proPRE7[]=$rows->Resp_P7;
                    $PRE7=$rows->pregunta;
                    $sumaPRE7 = array_sum($proPRE7);
                }
                $contar7=$contar;
                $porcentajePRE7=number_format($sumaPRE7 / $contar,2);
            }else{
                $porcentajePRE7=0;
                $PRE7='No existe';
                $contar7=0;
            }
            $RP8=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P8, DT.Resp_P8, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P8 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP8){
                foreach($RP8 as $rows){
                    $proPRE8[]=$rows->Resp_P8;
                    $PRE8=$rows->pregunta;
                    $sumaPRE8 = array_sum($proPRE8);
                }
                $contar8=$contar;
                $porcentajePRE8=number_format($sumaPRE8 / $contar,2);
            }else{
                $porcentajePRE8=0;
                $PRE8='No existe';
                $contar8=0;

            }
            $RP9=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P9, DT.Resp_P9, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P9 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP9){
                foreach($RP9 as $rows){
                    $proPRE9[]=$rows->Resp_P9;
                    $PRE9=$rows->pregunta;
                    $sumaPRE9 = array_sum($proPRE9);
                }
                $contar9=$contar;
                $porcentajePRE9=number_format($sumaPRE9 / $contar,2);
            }else{
                $porcentajePRE9=0;
                $PRE9='No existe';
                $contar9=0;

            }
            $RP10=DB::connection('sqlsrv')->select("SELECT DT.id, DT.P10, DT.Resp_P10, DT.Year, DT.month, DT.Day, p.pregunta, DT.promedio FROM detalle_encuestas DT 
            INNER JOIN preguntas p ON DT.P10 = p.id 
            WHERE encuesta_id=".$encuesta."AND (Odate BETWEEN "."'".$desde."'"." AND "."'".$hasta."'".") "."AND DT.Asesor=".$asesor);
            if($RP10){
                foreach($RP10 as $rows){
                    $proPRE10[]=$rows->Resp_P10;
                    $PRE10=$rows->pregunta;
                    $sumaPRE10 = array_sum($proPRE10);
                }
                $contar10=$contar;
                $porcentajePRE10=number_format($sumaPRE10 / $contar,2);
            }else{  
                $porcentajePRE10=0;
                $PRE10='No existe';
                $contar10=0;
            }
            $promedios=[$porcentajePRE1,$porcentajePRE2,$porcentajePRE3,$porcentajePRE4,$porcentajePRE5,$porcentajePRE6,$porcentajePRE7,$porcentajePRE8,$porcentajePRE9,$porcentajePRE10];
            $preguntas=[$PRE1,$PRE2,$PRE3,$PRE4,$PRE5,$PRE6,$PRE7,$PRE8,$PRE9,$PRE10];
            $cuantas=[$contar1,$contar2,$contar3,$contar4,$contar5,$contar6,$contar7,$contar8,$contar9,$contar10];
        return response(json_encode([$promedios,$preguntas,$cuantas]),200)->header('Content-type','text/plain');

    }
}
