
@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')

@stop
@section('content')
<div class="estatico">
   <div class="navi">
        <div class="encuestaName">
            <h5>Datos de: {{$encuesta->Nombre}}</h5>
            <div class="menuBTN">
                <button onclick="Mostrar();" id="mostrar"><i class="bi bi-caret-down-fill"></i></button>
                <button onclick="Ocultar();" style="display:none;" id="ocultar"><i class="bi bi-caret-up-fill"></i></button>
            </div>
        </div>
        <div class="botones" id="menuBTN">
            <form action="/resumen/{{$encuesta->id}}" method="post" class="for-ases">
                @csrf
                @if($encuesta->concesion == 'Automotriz1')
                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz2')
                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz4')
                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz5')
                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz6')
                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz7')
                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                @endif
                    <button type="submit" class="btn btne asesores "><span><i class="fas fa-newspaper ases"> </i></span><br> Resumen</button>
            </form>
            <form action="/encuesta/{{$encuesta->id}}" method="post" class="for-ases">
                @csrf
                @if($encuesta->concesion == 'Automotriz1')
                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz2')
                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz4')
                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz5')
                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz6')
                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz7')
                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                @endif
                    <button type="submit" class="btn btne asesores bg-primary"><span><i class="fas fa-newspaper ases"> </i></span><br> Encuestas</button>
            </form>
            <div class="enlaces-btn">
                <a href="/estadisticas/{{$encuesta->id}}" type="button" class="btn btne"><span><i class="fas fa-chart-line"></i></span><br> Estadisticas</a>
            </div>
            <form action="/detalle-diario/{{$encuesta->id}}" method="post" class="for-ases">
                    @csrf
                    @if($encuesta->concesion == 'Automotriz1')
                        <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                    @elseif($encuesta->concesion== 'Automotriz2')
                        <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                    @elseif($encuesta->concesion== 'Automotriz4')
                        <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                    @elseif($encuesta->concesion== 'Automotriz5')
                        <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                    @elseif($encuesta->concesion== 'Automotriz6')
                        <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                    @elseif($encuesta->concesion== 'Automotriz7')
                        <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                    @endif
                    <button type="submit" class="btn btne asesores"><span><i class="fas fa-calendar-alt"></i></span> <br> Detalle Diario</button>
                </form>
            <form action="/asesores/{{$encuesta->id}}" method="post" class="for-ases">
                @csrf
                @if($encuesta->concesion == 'Automotriz1')
                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz2')
                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz4')
                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz5')
                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz6')
                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                @elseif($encuesta->concesion== 'Automotriz7')
                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                @endif
                <button type="submit" class="btn btne asesores "><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
            </form>
        </div>
    </div>
 </div>
<div class="estadisticas">
    <div class="row">
        <div class="col d-flex justify-content-center" id="rango">
            <div class="col">
                <label for="">Desde:</label>
                <input type="text" id="min" name="min" style="border-radius:5px; border:1px solid #909090;">
            </div>
            <div class="col ml-3">
                <label for="">Hasta:</label>
                <input type="text" id="max" name="max" style="border-radius:5px; border:1px solid #909090;">
            </div>
        </div>
    </div>
        <div class="col tablaUltimos">
            <p class="titulo-1 text-center" style="width:80%; margin:5px auto;">Todas las encuestas contestadas</p>
            <table id="example1" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>No.oreden</th>
                        <th>Promedio</th>
                        <th>detalles</th>
                    </tr>    
                </thead>
                <tbody>
                    @foreach($encuestas as $row)
                    <tr>    
                        <td>{{$row->Odate}}</td>
                        <td>{{$row->No_Orden}}</td>
                        <td>{{$row->Promedio}}</td>
                        <td>
                            <a type="button"  data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-success">Detalles</a>
                        </td>
                    </tr>
                    <!-- Modal detalle -->
                    <div class="modal fade" id="enviar{{$row->id}}" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-navy">
                                    <h4 class="modal-title">Resultados de la Encuesta</h4>
                                    <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="info-box bg-info">
                                                <span class="info-box-icon"><i class="fas fa-calculator"></i> </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Promedio</span>
                                                    <span class="info-box-number">{{$row->Promedio}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="info-box bg-secondary">
                                                <span class="info-box-icon"><i class="fas fa-scroll"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">FEcha de Aplicación</span>
                                                    <span class="info-box-number">{{$row->Odate}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="info-box bg-success">
                                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Número de Orden</span>
                                                    <span class="info-box-number">{{$row->No_Orden}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        @foreach($buscarAses as $rosc)
                                            @foreach($rosc as $rows)
                                                @if($rows->TraCod == $row->Asesor)
                                                    <h4 class="bg-navy" style="padding:5px 10px; border-radius:5px">Asesor: <b>{{$rows->TraNom}} {{$rows->TraApPat}}</b></h4>
                                                @else
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        @foreach($preguntas as $pre)
                                            <div class=" col-6 mb-2">
                                                <div class="card-header bg-primary d-flex jusify-content-between"> <div class="col ">{{$pre->pregunta}}</div> <div class="col" style="text-align:right; font-size:.8em;">{{$pre->tipo}}</div> </div>
                                                <div class="card-body border border-info d-flex">
                                                    @if($pre->tipo== 'Estrellas')
                                                        @foreach($encuestas as $enc)
                                                            @if($pre->id == $enc->P1 && $enc->No_Orden==$row->No_Orden)
                                                                <div class="col"><label for="" class="estrellas">{{$enc->Resp_P1/2}} <span class="text-warning">★</span></label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">Valor</div>
                                                                <div class="row  d-flex justify-content-center">
                                                                    {{$enc->Resp_P1}}
                                                                </div>
                                                            </div>
                                                        @elseif($pre->id == $enc->P2 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P2/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P2}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P3 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P3/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P3}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P4 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P4/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P4}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P5 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P5/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P5}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P6 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P6/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P6}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P7 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P7/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P7}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P8 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P8/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P8}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P9 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P9/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                {{$enc->Resp_P9}}
                                                            </div>
                                                        </div>
                                                        @elseif($pre->id == $enc->P10 && $enc->No_Orden==$row->No_Orden)
                                                        <div class="col"><label for="" class="estrellas">{{$enc->Resp_P10/2}} <span class="text-warning">★</span></label></div>
                                                        <div class="col-2 bg-info" style="border-radius:5px;">
                                                            <div class="row d-flex justify-content-center">Valor</div>
                                                            <div class="row  d-flex justify-content-center">
                                                                <{$enc->Resp_P10}}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @elseif($pre->tipo == 'Radio Button')
                                                @foreach( $respuestas as $res)
                                                    @foreach($encuestas as $enc)
                                                        @if( $row->No_Orden==$enc->No_Orden && $pre->id== $enc->P1 && $res->pregunta_id == $enc->P1 && $enc->Resp_P1 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P2 && $res->pregunta_id == $enc->P2 && $enc->Resp_P2 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P3  &&$res->pregunta_id == $enc->P3 && $enc->Resp_P3 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P4 && $res->pregunta_id == $enc->P4 && $enc->Resp_P4 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P5 && $res->pregunta_id == $enc->P5 && $enc->Resp_P5 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P6 && $res->pregunta_id == $enc->P6 && $enc->Resp_P6 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P7 && $res->pregunta_id == $enc->P7 && $enc->Resp_P7 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P8  &&$res->pregunta_id == $enc->P8 && $enc->Resp_P8 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P9 && $res->pregunta_id == $enc->P9 && $enc->Resp_P9 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}<</div>
                                                            </div>
                                                        @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P10 && $res->pregunta_id == $enc->P10 && $enc->Resp_P10 == $res->valor)
                                                            <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                            <div class="col-2 bg-info" style="border-radius:5px;">
                                                                <div class="row d-flex justify-content-center">valor</div>
                                                                <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                @elseif($pre->tipo == 'Texto')
                                                    @foreach($encuestas as $enc)
                                                        @if($pre->id == $enc->P1 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P1}}</label>
                                                        @elseif($pre->id == $enc->P2 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P2}}</label>
                                                        @elseif($pre->id == $enc->P3 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P3}}</label>
                                                        @elseif($pre->id == $enc->P4 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P4}}</label>
                                                        @elseif($pre->id == $enc->P5 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P5}}</label>
                                                        @elseif($pre->id == $enc->P6 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P6}}</label>
                                                        @elseif($pre->id == $enc->P7 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P7}}</label>
                                                        @elseif($pre->id == $enc->P8 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P8}}</label>
                                                        @elseif($pre->id == $enc->P9 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P9}}</label>
                                                        @elseif($pre->id == $enc->P10 && $enc->No_Orden==$row->No_Orden)
                                                            <label style="color:#727272;">{{$enc->Resp_P10}}</label>
                                                        @endif
                                                    @endforeach
                                                @elseif($pre->tipo == 'Seleccion')
                                                    @foreach( $respuestas as $res)
                                                        @foreach($encuestas as $enc)
                                                            @if($row->No_Orden==$enc->No_Orden &&  $pre->id== $enc->P1 && $res->pregunta_id == $enc->P1 && $enc->Resp_P1 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P2 && $res->pregunta_id == $enc->P2 && $enc->Resp_P2 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P3  &&$res->pregunta_id == $enc->P3 && $enc->Resp_P3 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P4 && $res->pregunta_id == $enc->P4 && $enc->Resp_P4 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P5 && $res->pregunta_id == $enc->P5 && $enc->Resp_P5 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P6 && $res->pregunta_id == $enc->P6 && $enc->Resp_P6 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P7 && $res->pregunta_id == $enc->P7 && $enc->Resp_P7 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P8  &&$res->pregunta_id == $enc->P8 && $enc->Resp_P8 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P9 && $res->pregunta_id == $enc->P9 && $enc->Resp_P9 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @elseif($row->No_Orden==$enc->No_Orden && $pre->id== $enc->P10 && $res->pregunta_id == $enc->P10 && $enc->Resp_P10 == $res->valor)
                                                                <div class="col"><label for=""class="estrellas">{{$res->respuesta}}</label></div>
                                                                <div class="col-2 bg-info" style="border-radius:5px;">
                                                                    <div class="row d-flex justify-content-center">valor</div>
                                                                    <div class="row d-flex justify-content-center">{{$res->valor}}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @elseif($pre->tipo == 'CheckBox')
                                                    @foreach( $respuestas as $res)
                                                        @foreach($encuestas as $enc)
                                                
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>   
                        </div>    
                        </div> 
                  @endforeach
                </tbody>
            </table>
            <div class="kards">
             @foreach($encuestas as $row)
                <div class="kard">
                    <div class="kard-header bg-navy">
                        <label for="">{{$row->No_Orden}}</label>
                    </div>
                    <div class="kard-body">
                        <label for="">Promedio: <span style="font-weight: normal; border-radius:2px;"><br> {{$row->Promedio}}</span></label>
                        <label for="">Fecha: <span style="font-weight: normal; border-radius:2px;"><br> {{$row->Odate}}</span></label>
                    </div>
                    <div class="kard-footer">
                        <a type="button"  data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-success">Detalles</a>
                    </div>
                </div>
             @endforeach
            </div>
        </div>
</div>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/estadisticas.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
    <style>
        .estadisticas{
            position: relative;
            top:0vh;
            width: 100%;
        }
        .titulo-1{
            background-color:#2E4053;
            color:#ffffff;
            border-radius:2px;
            font-weight:bold;
        }
        .contenido{
            width: 100%;
            height:auto;
            display:flex;
            flex-wrap:wrap;
            justify-content:space-between;
        }
        .card{
            width:48%;
            height:8vh;
            margin:10px auto;
        /*text-align:center;*/
        }
        .pregunta-header{
            width:100%;
            height:50%;
            text-align:center;
            background-color:#6D97C3;
        }
        @media(max-width:919px){
            #resumen{
                display: block;
                padding: 20px;
            }
            .estatico{
                position: static;
            }
            .estadisticas{
                top:1vh;
            }
        }
        @media(max-width:628px){
            #example1,#example1_wrapper{
                display:none;
            }
            .kards{
                display: flex;
                flex-wrap: wrap;
            }
            .kard{
                width: 40%;
                margin: 5px auto;
                border-radius: 5px;
                border:1px solid #a0a0a0;
            }
            .kard-header{
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                display: flex;
                justify-content: center;
            }
            .kard-body{
                padding: 10px;
                font-weight: normal;
            }
            .kard-footer{
                width: 80%;
                margin: 5px auto;
                display: flex;
                justify-content: end;
                padding-top: 5px;
                border-top: 1px solid #a0a0a0;
            }
            #rango{
                display:none !important;                
            }
        }
        @media(max-width:360px){
            .kards{
                display:block;
            }
            .kard{
                width: 80%;
            }
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
    <script>
        var minDate, maxDate;
        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min = minDate.val();
                var max = maxDate.val();
                var date = new Date( data[0] );
                if (
                    ( min === null && max === null ) ||
                    ( min === null && date <= max ) ||
                    ( min <= date   && max === null ) ||
                    ( min <= date   && date <= max )
                ){
                    return true;
                }
                return false;
            }
        );
        $(document).ready(function() {
            // Create date inputs
            minDate = new DateTime($('#min'), {
                format: 'YYYY-MM-DD'
            });
            maxDate = new DateTime($('#max'), {
                format: 'YYYY-MM-DD'
            });
            // DataTables initialisation
            var table = $('#example1').DataTable();
            // Refilter the table
            $('#min, #max').on('change', function () {
                table.draw();
            });
        });
    </script>
        <script>
        function Mostrar(){
            $('#mostrar').css('display','none');  
            $('#menuBTN').css('display','block');  
            $('#ocultar').css('display','block');  
        }
        function Ocultar(){
            $('#mostrar').css('display','block');  
            $('#menuBTN').css('display','none');  
            $('#ocultar').css('display','none');  
        }
    </script>
@endsection