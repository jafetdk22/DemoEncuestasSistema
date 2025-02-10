
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
        $NombreAsesorConEspacios=DB::connection($conexion)->select('SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat FROM  NRCATTRA WHERE TraCod='. $ases);
        foreach($NombreAsesorConEspacios as $tras){
            $nombreEspacioss=$tras->TraNom;
            $nombres = trim($nombreEspacioss);
            $apellidoPEspacioss=$tras->TraApPat;
            $apellidoPs = trim($apellidoPEspacioss);
            $NombreAsesor=$nombres.' '.$apellidoPs;
        }
     /*arreglos*/
        $pormediosPregAses=[$porcentajePRE1,$porcentajePRE2,$porcentajePRE3,$porcentajePRE4,$porcentajePRE5,$porcentajePRE6,$porcentajePRE7,$porcentajePRE8,$porcentajePRE9,$porcentajePRE10];
        $preguntasAses=[$PRE1,$PRE2,$PRE3,$PRE4,$PRE5,$PRE6,$PRE7,$PRE8,$PRE9,$PRE10];

        $PromTotXasesor=[$asesores,$conteosTot,$PromediosAsesores];
        $graficaEncuesPos=[$asesoresPos,$conteosPos,$porcentajePos];
        $graficaEncuesNeg=[$asesoresNeg,$conteosNeg,$porcentajeNeg];
        $graficaPETXAeses=[$asesoresPastel,$porcentajeEncTxAses];
        $graficaPromPregAses=[$preguntasAses,$conteosTot,$pormediosPregAses];
        
        //$graficasAsesores=[$PromTotXasesor,$graficaEncuesNeg,$graficaEncuesPos,$graficaPETXAeses,$graficaPromPregAses,$NombreAsesor];