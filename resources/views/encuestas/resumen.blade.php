@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')

@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
    <div class="estatico">
        <div class="navi">
            <div class="encuestaName"><h5>Datos de: {{$encuesta->Nombre}}</h5>
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
                        <button type="submit" class="btn btne asesores bg-primary"><span><i class="fas fa-newspaper ases"> </i></span><br> Resumen</button>
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
                        <button type="submit" class="btn btne asesores "><span><i class="fas fa-newspaper ases"> </i></span><br> Encuestas</button>
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
        <div class="row" id="tegets">
            <div class="col small-box bg-info">
                <div class="inner">
                    <h6><b>{{$proG}}</b></h6>
                    <p>Promedio</p>
                </div>
                <div class="icon">
                <i class="fas fa-calculator"></i>
                </div>
            </div>
            <div class="col small-box bg-secondary">
                <div class="inner">
                    <h6><b>{{$contestadas}}</b></h6>
                    <p>Encuestas Contestadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-scroll"></i>
                </div>
                <a href="/encuesta/{{$encuesta->id}}"  class="small-box-footer" data-toggle="modal" data-target="#ordenesCont">Información <i class="fas fa-arrow-circle-right"></i></a>    
            </div>
            <div class="col small-box bg-primary">
                <div class="inner">
                    <h6><b>{{$contarTotOrd}}</b></h6>
                    <p>Órdenes</p>
                </div>
                <div class="icon">
                <i class="fas fa-asterisk"></i>
                </div>
            </div>
            <div class="col small-box bg-danger">
                <div class="inner">
                    <h6><b>{{$noContestadas}}</b></h6>
                    <p>Ordenes No Contestadas</p>
                </div>
                <div class="icon">
                <i class="bi bi-x"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#ordenes">Información <i class="fas fa-arrow-circle-right"></i></a>    
            </div>
            <div class="col small-box bg-success">
                <div class="inner">
                    <h6><b>{{$PorcEncContes}}%</b></h6>
                    <p>Porcentaje</p>
                </div>
                <div class="icon">
                <i class="fas fa-bolt"></i>
                </div>
            </div>
        </div>
        <div class="row" id="resumen">
            <div class="col tablaUltimos">
                <p class="titulo-1 text-center">Últimas 10 Encuestas del Mes</p>
                <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                       <th>Fecha</th>
                       <th>No.oreden</th>
                       <th>Promedio</th>
                       <th>detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimos as $row)
                        @if($row->encuesta_id != $encuesta->id)
                        @elseif($row->encuesta_id == $encuesta->id)
                            @if($row->Promedio < 7)
                                <tr >
                                    <td style="Background-color:#FFD2D2;">{{$row->Odate}} </td>
                                    <td style="Background-color:#FFD2D2;">{{$row->No_Orden}}</td>
                                    <td style="Background-color:#FFD2D2;">{{$row->Promedio}}</td>
                                    <td style="Background-color:#FFD2D2;">
                                        <a type="button" data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-success">Detalle</a>
                                    </td>
                                </tr>
                            @elseif($row->Promedio >=7)
                                <tr>
                                    <td class="danger">{{$row->Odate}} </td>
                                    <td>{{$row->No_Orden}}</td>
                                    <td>{{$row->Promedio}}</td>
                                    <td>
                                        <a type="button" data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-success">Detalle</a>
                                    </td>
                                </tr>
                            @endif
                        @endif
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
                                <div class="row respuestas">
                                    @foreach($preguntas as $pre)
                                        <div class="col-6 mb-2 target-resp">
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
                        </div>
                    @endforeach
                </tbody>
                </table>
                <div class="kards">
                    @foreach($ultimos as $row)
                        @if($row->encuesta_id != $encuesta->id)
                        @elseif($row->encuesta_id == $encuesta->id)
                            @if($row->Promedio < 7)
                                <div class="kard">
                                    <div class="kard-header bg-navy">{{$row->No_Orden}}</div>
                                    <div class="kard-body">
                                        <label for="">Fecha: <br><span style="font-weight: normal; border-radius:2px;">{{$row->Odate}}</span></label><br>
                                        <label for="">Promedio: <br><span style="font-weight: normal; border-radius:2px;">{{$row->Promedio}}</span></label>
                                    </div>
                                    <div class="kard-footer">
                                        <a type="button" data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-danger">Detalle</a>
                                    </div>
                                </div>
                            @elseif($row->Promedio >=7)
                                <div class="kard">
                                    <div class="kard-header bg-navy">{{$row->No_Orden}}</div>
                                    <div class="kard-body">
                                        <label for="">Fecha: <br><span style="font-weight: normal; border-radius:2px;">{{$row->Odate}}</span></label><br>
                                        <label for="">Promedio: <br><span style="font-weight: normal; border-radius:2px;">{{$row->Promedio}}</span></label>
                                    </div>
                                    <div class="kard-footer">
                                        <a type="button" data-toggle="modal" data-target="#enviar{{$row->id}}" class="btn btn-sm btn-success">Detalle</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col">
                <div class="panel panel-default">
                    <div class="panel-heading  titulo-1 text-center">Promedio de los ultimos 5 meses</div>
                    <div class="panel-body ">
                        <canvas id="canvas" height="280" width="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
        <!-- Modal info ordenes No conestadas -->
        <div class="modal fade" id="ordenes" role="dialog">
            <div class="modal-dialog modal-xl ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-navy d-flex justify-content-between">
                        <div><h4 class="modal-title">{{$encuesta->Nombre}}</h4></div>
                        <div><h6 class="modal-title">Ordenes no contestadas</h6></div>
                        
                        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body">
                        <div class="row d-flex justify-content-center">
                        @foreach($ordNoContes as $encs)
                            @foreach($ordenes as $ords)
                                @if($ords->OROrden == $encs) 
                                    <div class="cajaNo">
                                        <div class=" col-header bg-danger d-flex justify-content-between">
                                        <div class="col ">Orden: {{$ords->OROrden}}</div>
                                            <div class="col  d-flex justify-content-end "><b>{{$loop->iteration}}</b></div>
                                        </div>
                                        <div class="col-body">
                                            <b>Nombre del cliente: <br> </b> {{$ords->ORNombre}} <br>
                                            <b>Dirección: <br> </b> {{$ords->ORDirec}} <br>
                                            <b>Colonia: <br> </b> {{$ords->ORColonia}} <br>
                                            <b>Fecha de Alta: <br> </b> {{$ords->ORFecAlta}} <br>
                                            <b>Fecha de Entrega: <br> </b> {{$ords->ORFecEnt}} <br>
                                            <b class="text-danger">¡No Contestada!</b>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal info ordenes contestadas -->
        <div class="modal fade" id="ordenesCont" role="dialog">
            <div class="modal-dialog modal-xl ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h4 class="modal-title">{{$encuesta->Nombre}}</h4>
                        <h6 class="modal-title">Total de Ordenes</h6>
                        
                        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex justify-content-center">
                        @foreach($ordContes as $enc)
                            @foreach($ordenes as $ord)
                                @if($ord->OROrden == $enc) 
                                    <div class="cajaNo">
                                        <div class=" col-header bg-navy d-flex justify-content-between">
                                        <div class="col ">Orden: {{$ord->OROrden}}</div>
                                            <div class="col  d-flex justify-content-end "><b>{{$loop->iteration}}</b></div>
                                        </div>
                                        <div class="col-body">
                                            <b>Nombre del cliente: <br> </b> {{$ord->ORNombre}} <br>
                                            <b>Dirección: <br> </b> {{$ord->ORDirec}} <br>
                                            <b>Colonia: <br> </b> {{$ord->ORColonia}} <br>
                                            <b>Fecha de Alta: <br> </b> {{$ord->ORFecAlta}} <br>
                                            <b>Fecha de Entrega: <br> </b> {{$ord->ORFecEnt}} <br>
                                            <b class="text-success">¡Contestada!</b>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
<!--Contenido de nuestra pagina-->
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/estadisticas.css">
    <style>
        .estadisticas{
            position: relative;
            top:1vh;
            left:0;
            width: 99%;
            height:500px;
            margin:0;
            padding:0 2vh;
        }
        .titulo-1{
            background-color:#2E4053;
            color:#ffffff;
            border-radius:2px;
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
        .estrellas{
            font-size:30px;
            color:#727272;
        }
        .small-box{
            margin: 0 5px;
            margin-bottom:5px;
        }
        @media(max-width:919px){
            #resumen{
                display: block;
            }
            .estatico{
                position: static;
            }
            .estadisticas{
                top:1vh;
                max-height:auto;
            }
        }
    </style>

@stop
<!--agregamos css-->
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var year = <?php echo $year; ?>;
        var user = <?php echo $user; ?>;
        var barChartData = {
        labels: year,      
        datasets: [{
            label: 'Promedio Mensual',
            data: user,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
             borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
                ],
            borderWidth: 1,

            }]
       
        };

        window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#b6effb',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Promedio Mensual de las Encuestas'
                },
                scales:{
                    y: {
                        beginAtZero: true,
                    }
                },
                
            }
        });
        };

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
@stop

<!--agregamos Java Script-->