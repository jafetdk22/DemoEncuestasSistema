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
                    <button type="submit" class="btn btne asesores bg-primary"><span><i class="fas fa-calendar-alt"></i></span> <br> Detalle Diario</button>
                </form>
                <form action="/asesores/{{$encuesta->id}}" method="post" class="for-ases">
                    @csrf
                    @php
                        $concesiones = ['Automotriz1', 'Automotriz2', 'Automotriz4', 'Automotriz5', 'Automotriz6', 'Automotriz7'];
                    @endphp
                    @if(in_array($encuesta->concesion, $concesiones))
                        <input type="text" value="{{ $encuesta->concesion }}" name="conexionDB" style="display:none">
                    @endif
                    <button type="submit" class="btn btne asesores"><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
                </form>
            </div>
        </div>
        <div class="col selectsNav">
            <p>Fecha: <input type="text" id="datepicker"></p>
            <div class="menuBTN menuBTN1 ">
                <button class=" btn btn-secondary btn-sm "  onclick="MostrarBTN();"id="MostrarBTN">Más<i class="bi bi-caret-down-fill"></i></button>
                <button class=" btn btn-secondary btn-sm "   onclick="OcultarBTN();" style="display:none;" id="OcultarBTN">Menos<i class="bi bi-caret-up-fill"></i></button>
            </div>
        </div>
            <input type="hidden" name="encuesta" id="encuesta" value="{{$encuesta->id}}">
            @php
                $conexiones = [
                    "Automotriz1" => "Automotriz1",
                    "Automotriz2" => "Automotriz2",
                    "Automotriz4" => "Automotriz4",
                    "Automotriz5" => "Automotriz5",
                    "Automotriz6" => "Automotriz6",
                    "Automotriz7" => "Automotriz7",
                ];
            @endphp
            @if(array_key_exists($encuesta->concesion, $conexiones))
                <input type="hidden" name="conexion" id="conexion" value="{{ $conexiones[$encuesta->concesion] }}">
            @endif
    </div>
    <div class="estadisticas">
        <div class="carteles" id="carteles">
            <div class="cartel bg-info">
                <div class="cartel-1">
                    <div class="texto-cartel">
                        <p>Promedio:</p>
                    </div>
                    <div class="texto-cartel">
                        <label for="" id="promedioDia"></label>
                    </div>
                </div>
                <div class="cartel-2">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>
            <div class="cartel bg-secondary">
                <div class="cartel-1">
                    <div class="texto-cartel">
                        <p class="menor">Encuestas Contestadas:</p>
                    </div>
                    <div class="texto-cartel">
                        <label for="" id="encuestasContestadas"></label></label>
                    </div>
                </div>
                <div class="cartel-2">
                    <i class="fas fa-scroll"></i>
                </div>
            </div>
            <div class="cartel bg-primary">
                <div class="cartel-1">
                    <div class="texto-cartel">
                        <p>Órdenes:</p>
                    </div>
                    <div class="texto-cartel">
                        <label for="" id="EncuestasTotales"></label>
                    </div>
                </div>
                <div class="cartel-2">
                    <i class="fas fa-asterisk"></i>
                </div>
            </div>
            <div class="cartel bg-danger">
                <div class="cartel-1">
                    <div class="texto-cartel">
                        <p class="menor">Órdenes no contestadas:</p>
                    </div>
                    <div class="texto-cartel">
                        <label for="" id="EncNoContestadas"></label>
                    </div>
                </div>
                <div class="cartel-2">
                    <i class="bi bi-x" style="font-size:2em;"></i>
                </div>
            </div>
            <div class="cartel bg-success">
                <div class="cartel-1">
                    <div class="texto-cartel">
                        <p>Porcentaje:</p>
                    </div>
                    <div class="texto-cartel">
                        <label for="" id="PorcentajeEncuestas"></label>%
                    </div>
                </div>
                <div class="cartel-2">
                    <i class="fas fa-bolt" ></i>
                </div>
            </div>
        </div>
        <div class="col d-flex datos">
            <div class="col">
            <div class='card-detalle'>
                        <div class='header-detalle'>
                            <div class='panel-heading  titulo-1 text-center'><i class='bi bi-bar-chart-fill'></i> Ordenes con encuesta aplicada <span id="dia"></span> <label></label></div>
                        </div>
                        <div class='body-detalle tarjetas tarjetas1' id="encuestasContestadas">

                        </div>
                    </div>
            </div>
            <div class="col">
                <div class="card-detalle">
                    <div class="header-detalle">
                        <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Ordenes con encuesta no aplicada <span id="dia2"></span> <label></label></div>
                    </div>
                    <div class="body-detalle tarjetas tarjetas2">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modales"></div>
    <div class="modalest">

    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/estadisticas.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .estadisticas{
            position: relative;
            width: 100%;
        }
        .calendario{
            height:100%;
            border-radius:10px;
            box-shadow:5px 8px 60px #4F4F4F;
            border:3px solid #043173;
        }
        .small-box{
            margin: 0 5px;
            margin-bottom:5px;
        }
        .titulo-1{
            background-color:#2E4053;
            color:#ffffff;
            border-radius:2px;
            margin-bottom:5px;
        }
        .tarjeta{
            width: 40%;
        }
        @media(max-width:920px){
            .estadisticas{
                position: relative;
                top:20% !important;
            }
            .selectsNav{
                position: relative;
                top:1%;
            }
        }
        @media(max-width:852px){
            .selectsNav{
                position: relative!important;
                top:-20px!important;
            }
            .tarjetas{
                display:block;
            }
            .tarjeta{
                width: 80%;
                height:150px;
            }
        }        
        @media(max-width:790px){
            .carteles{
                display:flex;
                flex-wrap:wrap;
            }
            .cartel{
                min-width:170px;
                margin-top:2px;
            }
        }
        @media(max-width:575px){
            .selectsNav{
                top:0!important;
            }
            .estadisticas{
                top:150px !important;
            }
            .datos{
                display:block !important;
            }
            .tarjetas{
                display:flex;
                flex-wrap:wrap;
                justify-content:center;
            }
            .tarjeta{
                width: 25%;
                margin: 3%;
                height:150px;
                font-size:.7rem !important;



            }
        }
        @media(max-width:280px){
            .estadisticas{
                top:10px;
            }
            .tarjetas{
                display: block;
                display: flex;
                justify-content:center;
            }
            .tarjeta{
                width: 80%;
            }
        }
    </style>
@stop
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
           $(function(){
            $("#datepicker").datepicker({ dateFormat:'yy-mm-dd'}).datepicker("setDate", new Date());

        });
        $(function(){
            window.onload = mi_funcion();
        });
        $(function(){
            $("#datepicker").change(mi_funcion);
        });

        function mi_funcion(){
            var fecha = $('#datepicker').val();   
            var encuesta = $('#encuesta').val();            
            var conexionDB = $('#conexion').val();  
               
            $.ajax({
                url: '/detalleAjax',
                method:'get',
                data:{fecha:fecha, encuesta:encuesta, conexionDB:conexionDB}
            }).done(function(res){
                var arreglo =JSON.parse(res);
                console.log(arreglo);
                document.getElementById('promedioDia').innerHTML = arreglo[0];
                document.getElementById('encuestasContestadas').innerHTML = arreglo[1];
                document.getElementById('EncuestasTotales').innerHTML = arreglo[2];
                if (arreglo[2] !== 0) {
                    var noContestadas = arreglo[2] - arreglo[1];
                    document.getElementById('EncNoContestadas').innerHTML = noContestadas;
                } else {
                    document.getElementById('EncNoContestadas').innerHTML = 0;
                }
                var multiplicar = arreglo[1] * 100;
                var porcentaje;

                if (arreglo[2] !== 0) {
                    porcentaje = (multiplicar / arreglo[2]).toFixed(1);
                } else {
                    porcentaje = arreglo[0];
                }

                console.log(arreglo[1], arreglo[2]);
                document.getElementById('PorcentajeEncuestas').innerHTML=porcentaje;
                var contenido = '';
                var contenidos = '';
                var modal = '';
                var modals = '';
                for(var i=0; i<arreglo[1]; i++){
                    $.each(arreglo[5][i], function(index, value) {
                        contenido +=
                        "<div class='tarjeta'>"+
                            "<div class='tarjeta-header titulo-2'>"+
                                "<label>"+value.OrOrden+"</label>"+
                            "</div>"+
                            "<div class='tarjeta-body' style='height:50%;'>"+
                                "<label><span class='identificadores'>Cliente:</span>"+"<br>"+value.ORNombre+"</label>"+"<br>"+
                            "</div>"+
                            "<div class='col d-flex justify-content-center align-items-center' style='height:30%; padding: 10px;'>"+
                                "<button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#exampleModal"+value.OrOrden+"' style='width: 100%;'>"+
                                    'Detalles'+
                                "</button>"+
                            "</div>"+
                        "</div>";
                    });    
                }
                for(var i=0; i<arreglo[1]; i++){
                    $.each(arreglo[5][i], function(index, value) {
                        $.each(arreglo[3], function(index, values) {
                            if(value.OrOrden == values.No_Orden && value.OPCveOpe==values.Asesor){

                                modal +=
                                "<div class='modal fade' id='exampleModal"+value.OrOrden+"' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
                                    "<div class='modal-dialog modal-lg'>"+
                                        "<div class='modal-content'>"+
                                            "<div class='modal-header bg-navy d-flex justify-content-between'>"+
                                                "<div>"+
                                                    "<h4 class='modal-title'>"+
                                                        value.ORNombre+
                                                    "</h4>"+
                                                "</div>"+
                                                "<button type='button' class='close text-light' data-dismiss='modal'>"+
                                                    '&times;'+
                                                "</button>"+
                                            "</div>"+
                                            "<div class='modal-body d-flex'>"+
                                                "<div class='col-6'>"+
                                                    "<label><span class='identificadores'>Cliente:</span>"+"<br>"+value.ORNombre+"</label>"+"<br>"+
                                                    "<label><span class='identificadores'>Unidad:</span>"+"<br>"+value.MDes+"</label>"+"<br>"+
                                                    "<label><span class='identificadores'>Modelo:</span>"+"<br>"+value.Modelo+"</label>"+"<br>"+
                                                    "<label><span class='identificadores'>No.Serie:</span>"+"<br>"+value.ORChasis+"</label>"+"<br>"+
                                                    "<label><span class='identificadores'>Servicio:</span>"+"<br>"+value.ORFalla1+"</label>"+"<br>"+
                                                    "<label><span class='identificadores'>Asesor:</span>"+"<br>"+value.TraNom+value.TraApPat+value.TraApMat+"</label>"+"<br>"+
                                                "</div>"+
                                                "<div class='col-6 datosRespuesta'>"+
                                                    "<label><span class='identificadores'>Promedio:</span>"+"<br>"+values.Promedio+"</label>"+"<br>"+
                                                "</div>"+   
                                            "</div>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>";
                            }
                        });
                    });    
                }
                $(".tarjetas1").html(contenido);
                $(".modalest").html(modal);
                for(var i=0; i<noContestadas; i++){
                    $.each(arreglo[6][i], function(index, value) {
                        contenidos +=                         "<div class='tarjeta'>"+
                            "<div class='tarjeta-header titulo-2'>"+
                                "<label>"+value.OrOrden+"</label>"+
                            "</div>"+
                            "<div class='tarjeta-body'style='height:50%;'>"+
                                "<label><span class='identificadores'>Cliente:</span>"+"<br>"+value.ORNombre+"</label>"+"<br>"+
                            "</div>"+
                            "<div class='col d-flex justify-content-center align-items-center' style='height:30%;'>"+
                                "<button type='button' class='btn btn-danger btn-sm  m-3' data-toggle='modal' data-target='#noModal"+value.OrOrden+"'>"+
                                    'Detalles'+
                                "</button>"+
                            "</div>"+
                        "</div>";
                    });    
                }
                for(var i=0; i<noContestadas; i++){
                    $.each(arreglo[6][i], function(index, value) {
                        modals +="<div class='modal fade' id='noModal"+value.OrOrden+"' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
                            "<div class='modal-dialog modal-lg'>"+
                                "<div class='modal-content'>"+
                                    "<div class='modal-header bg-navy d-flex justify-content-between'>"+
                                        "<div>"+
                                            "<h4 class='modal-title'>"+
                                            value.ORNombre+
                                            "</h4>"+
                                        "</div>"+
                                        "<button type='button' class='close text-light' data-dismiss='modal'>"+
                                            '&times;'+
                                        "</button>"+
                                    "</div>"+
                                    "<div class='modal-body'>"+
                                    "<label><span class='identificadores'>Cliente:</span>"+"<br>"+value.ORNombre+"</label>"+"<br>"+
                                        "<label><span class='identificadores'>Unidad:</span>"+"<br>"+value.MDes+"</label>"+"<br>"+
                                        "<label><span class='identificadores'>Modelo:</span>"+"<br>"+value.Modelo+"</label>"+"<br>"+
                                        "<label><span class='identificadores'>No.Serie:</span>"+"<br>"+value.ORChasis+"</label>"+"<br>"+
                                        "<label><span class='identificadores'>Servicio:</span>"+"<br>"+value.ORFalla1+"</label>"+"<br>"+
                                        "<label><span class='identificadores'>Asesor:</span>"+"<br>"+value.TraNom+value.TraApPat+value.TraApMat+"</label>"+"<br>"+
                                    "</div>"+

                                "</div>"+
                            "</div>"+
                        "</div>";
                    });    
                }               
                $(".tarjetas2").html(contenidos);
                $(".modales").html(modals);
            })
        }

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
    <script>
        function MostrarBTN(){
            $('#MostrarBTN').css('display','none');  
            $('#carteles').css('display','block');  
            $('#OcultarBTN').css('display','block');  
        }
        function OcultarBTN(){
            $('#MostrarBTN').css('display','block');  
            $('#carteles').css('display','none');  
            $('#OcultarBTN').css('display','none');  
        }
  </script>

@stop

