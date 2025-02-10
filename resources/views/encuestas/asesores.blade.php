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
        <div class="EncuestaName"><h5>Datos de: {{$encuesta->Nombre}}</h5></div>
        <div class="botones">
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
                <button type="submit" class="btn btne asesores bg-primary"><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
            </form>
        </div>
    </div>
    <div class="row pb-6 selectsNav">
        <div class="col d-flex flex-wrap">
            <div class="col-4 d-flex flex-column mb-3">
                <label class="control-label">Asesor:</label>
                <div class="col-9 d-flex justify-content-start" id="select-asesor">
                    <select ng-model="asesor" class="form-select form-select-sm" aria-label=".form-select-sm example" ng-options="m for m in asesores" id="asesor">
                        <option value="todos">Todos</option>
                        @if($asesoresList)
                            @foreach($asesoresList as $w)
                                <option value="{{$w[1]}}">{{$w[2]}}</option>
                            @endforeach
                        @elseif($asesoresList == '')

                        @endif
                    </select>
                </div>
            </div>
            <div class="col-4 d-flex flex-column mb-3">
                <label class="control-label">Mes:</label>
                <div class="col-9 d-flex justify-content-start">
                    <select ng-model="month" class="form-select form-select-sm" aria-label=".form-select-sm example" ng-options="m for m in months" id="mes1">
                        <option value="nada">Mes Actual</option>
                        <option value="enero">Enero</option>
                        <option value="febrero">Febrero</option>
                        <option value="marzo">Marzo</option>
                        <option value="abril">Abril</option>
                        <option value="mayo">Mayo</option>
                        <option value="junio">Junio</option>
                        <option value="julio">Julio</option>
                        <option value="agosto">Agosto</option>
                        <option value="septiembre">Septiembre</option>
                        <option value="octubre">Octubre</option>
                        <option value="noviembre">Noviembre</option>
                        <option value="diciembre">Diciembre</option>
                    </select>
                    <input type="hidden" name="encuesta" id="encuesta" value="{{$encuesta->id}}">
                    @if($encuesta->concesion == "Automotriz1")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz1">
                    @elseif($encuesta->concesion == "Automotriz2")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz2">
                    @elseif($encuesta->concesion == "Automotriz4")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz4">
                    @elseif($encuesta->concesion == "Automotriz5")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz5">
                    @elseif($encuesta->concesion == "Automotriz6")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz6">
                    @elseif($encuesta->concesion == "Automotriz7")
                        <input type="hidden" name="conexion" id="conexion" value="Automotriz7">
                    @endif
                </div>
            </div>
            <div class="col-4 d-flex flex-column mb-3">
                <label class="control-label">Año:</label>
                <div class="col-9 d-flex justify-content-start">
                    <select ng-model="month" class="form-select form-select-sm" aria-label=".form-select-sm example" ng-options="m for m in months"id="anho">
                        @foreach($anhos as $anho)
                            <option value="{{$anho}}">{{$anho}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
   </div>
    <div class="estadisticas mt-5">
        <div class="row mb-4  d-flex justify-content-center containers">
            <div class="col-6 mb-4">
                <div class="panel panel-default" id="page" >
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes2"></div>
                    <div class="panel-body">
                        <div id="acciones1"></div>
                        <div id="page-template" class="col" style="margin:0 auto;">
                            <canvas id="proMensual" height="200" width="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <div class="panel panel-default" id="page" >
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes6"></label></div>
                    <div class="panel-body">
                        <div id="acciones5" id="acciones5"></div>
                        <div id="page-template5">
                            <canvas id="PreporAse" height="200" width="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4 d-flex">
                <div class="col-6 mb-4">
                    <div class="panel panel-default" id="page">                
                        <div class="panel-heading  titulo-1 text-center mt-3"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes4"></label></div>
                        <div class="panel-body">
                            <div id="acciones2"></div>
                            <div id="page-template1">
                                <canvas id="negativaCanvas" height="200" width="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="panel panel-default" id="page">
                        <div class="panel-heading  titulo-1 text-center mt-3"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes3"></label></div>
                            <div class="panel-body">
                            <div id="acciones3"></div>
                            <div id="page-template3">
                                <canvas id="positivaCanvas" height="200" width="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="panel panel-default">
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Umbral de <label id="mes5"></label></div>
                        <div class="panel-body pastel">
                            <div id="acciones4"></div>
                            <div id="page-template4">
                                <canvas id="pastel"></canvas>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
@stop
<!--Contenido de nuestra pagina-->
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/estadisticas.css">
    <style>
        .estadisticas {
            position: relative;
            width: 100%;
            height: 80vh;
            overflow-x: hidden; 
            overflow-y: auto;
        }
        .content_header {
            margin: 0;
        }
        .titulo-1 {
            background-color: #2E4053;
            color: #ffffff;
            border-radius: 2px;
        }
        .contenido {
            width: 100%;
            height: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .card {
            width: 48%;
            height: auto; /* Cambiado de 8vh a auto para evitar encimamiento */
            margin: 10px 0; /* Ajustado el margen para evitar encimamiento */
            text-align: center; /* Comentado para mantener el texto centrado */
        }
        .pregunta-header {
            width: 100%;
            height: 50%;
            text-align: center;
            background-color: #6D97C3;
        }
        #pastel {
            min-width: 300px;
            min-height: 300px;
            margin: 0 auto;
        }
        #page-template4 {
            width: 100%;
        } 
        .proMensual {
            display: flex;
            justify-content: center;
        }
        /* Ajustes adicionales para evitar encimamiento */
        .row.mb-4 {
            margin-bottom: 20px; /* Aumentar el margen inferior */
        }
        .col-6 {
            padding: 10px; /* Añadir padding para evitar que los elementos se encimen */
        }
    </style>
@stop
<!--agregamos css-->
@section('js')
   <!--scripts-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="http://kendo.cdn.telerik.com/2017.2.621/js/kendo.all.min.js"></script>
   <!--scripts-->

    <script>
        window.onload=mi_funcion();
        $(function () {
            $('#mes1').change(mi_funcion);
            $('#anho').change(mi_funcion);
            $('#asesor').change(mi_funcion);
        });
        function mi_funcion () {
           //
            var mes1 = $('#mes1').val();
            var anho = $('#anho').val();
            var asesor = $('#asesor').val();
            var encuesta = $('#encuesta').val();
            var conexion=$('#conexion').val();
           //console.log(asesor);
           //
            if(mes1=="nada"){
                var fecha=new Date();
                var dat=fecha.getMonth();
            }
            if(dat == 0){
                var mes1="enero";
            }else if(dat ==1){
                var mes1="febrero";
            }else if(dat==2){
                var mes1="marzo";
            }else if(dat==3){
                var mes1="abril";
            }else if(dat==4){
                var mes1="mayo";
            }else if(dat==5){
                var mes1="junio";
            }else if(dat==6){
                var mes1="julio";
            }else if(dat==7){
                var mes1="agosto";
            }else if(dat==8){
                var mes1="septiembre";
            }else if(dat==9){
                var mes1="octubre";
            }else if(dat==10){
                var mes1="noviembre";
            }else if(dat==11){
                var mes1="diciembre";
            }
           //
            $('#proMensual').remove();
            $('#pdfImage1').remove();
            $('#image1').remove();
            
            $('#positivaCanvas').remove();
            $('#positivaPNG').remove();
            $('#Positivapdf').remove();
            
            $('#negativaCanvas').remove();
            $('#negativapdf').remove();
            $('#negativaPNG').remove();
            $('#PreporAse').remove();
            $('#prePorAsesPDF').remove();
            $('#prePorAsesPNG').remove();
            $('#pastel').remove();
            $('#pastelPDF').remove();
            $('#PENPPerfect').remove(); 
           //
            $.ajax({
                url: '/asesoresAjax',
                method:'get',
                data:{mes1:mes1, anho:anho,encuesta:encuesta,asesor:asesor, conexion:conexion}
            }).done(function(res){
                var arreglo =JSON.parse(res);
                var asesores=arreglo[0][0];
                var EncTotales=arreglo[0][1];
                var promedTotales=arreglo[0][2];

                var asesoresNeg=arreglo[1][0];
                var EncTotalesNeg=arreglo[1][1];
                var promedTotalesNeg=arreglo[1][2];

                var asesoresPos=arreglo[2][0];
                var EncTotalesPos=arreglo[2][1];
                var promedTotalesPos=arreglo[2][2];

                var  labelsPastel=arreglo[3][0];
                var  pastel=arreglo[3][1];

                var pregunta =arreglo[4][0];
                var cuantasPre =arreglo[4][1];
                var promedio =arreglo[4][2];
                var NombreAsesor=arreglo[5];
                
                
             //graficas
                $('#acciones1').append("<button  id='pdfImage1' class='btn btn-danger' ><i class='bi bi-file-earmark-pdf-fill' onclick='ExportProMensual();'></i></button>");
                $('#acciones1').append("<a id='image1' class='btn btn-primary' onclick='PMensual();'><i class='bi bi-image-fill'></i></a>");
                $('#page-template').append("<canvas id='proMensual' width='500' height='200'></canvas>");
                document.getElementById('mes2').innerHTML = mes1;
                var ctx1 = document.getElementById("proMensual").getContext("2d");
                window.myBar = new Chart(ctx1, {
                    plugins: [ChartDataLabels],
                    type: "bar",
                    data: {
                        labels: asesores,
                        datasets: [
                            {
                                type: "bar",
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgb(54, 162, 235)',
                                color: '#9A9A9B',
                                borderWidth: 1,
                                label: "Promedio por asesor",
                                order: 1,
                                data: promedTotales,
                                datalabels: {
                                    align: 'start',
                                    formatter: (dato) => dato,
                                    anchor: 'end',
                                    font: {
                                        size: "20",
                                        weight: "bold",
                                    },
                                }
                            },
                            {
                                type: "line",
                                label: "Encuestas totales",
                                data: EncTotales,
                                lineTension: 0,
                                backgroundColor: 'rgb(2, 52, 133)',
                                borderColor: "rgb(2, 52, 133 )",
                                order: 0,
                                fill: false,
                                datalabels: {
                                    align: 'end',
                                    anchor: 'end',
                                    color:'#ffff',
                                }
                            }
                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        plugins: {
                            datalabels: {
                                backgroundColor: function(context) {
                                    return context.dataset.backgroundColor;
                                },
                                borderRadius: 4,
                                padding:10,
                                font: {
                                    weight: 'bold'
                                },
                            }
                        }
                    }
                });
                $('#acciones2').append("<button class='btn btn-danger' id='negativapdf' onclick='NegativaPDF();'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
                $('#acciones2').append("<a id='negativaPNG' class='btn btn-primary' onclick='NegativaPNG();'><i class='bi bi-image-fill'></i></a>");
                $('#page-template1').append("<canvas id='negativaCanvas' height='200' width='500'></canvas>");

                document.getElementById('mes3').innerHTML = mes1;
                //document.getElementById('mes5').innerHTML = arreglo[0][0][0];
                var ctx2 = document.getElementById("negativaCanvas");
                ctx2.getContext("2d");
                window.myBar = new Chart(ctx2, {
                    plugins: [ChartDataLabels],
                    type: "bar",
                    data: {
                        labels: asesoresNeg,
                        datasets: [
                            {
                                type: "bar",
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgb(255, 99, 132)',
                                color: '#9A9A9B',
                                borderWidth: 1,
                                label: "Promedio por mes",
                                order: 1,
                                data: promedTotalesNeg,
                                datalabels: {
                                    align: 'start',
                                    formatter: (dato) => dato + "%",
                                    anchor: 'end',
                                    font: {
                                        size: "20",
                                        weight: "bold",
                                    },
                                }
                            },
                            {
                                type: "line",
                                label: "Encuestas totales por mes",
                                data: EncTotalesNeg,
                                lineTension: 0,
                                backgroundColor: 'rgb(133, 2, 2 )',
                                borderColor: "rgb(133, 2, 2  )",
                                order: 0,
                                fill: false,
                                datalabels: {
                                    align: 'end',
                                    anchor: 'end',
                                    color:'#ffff',
                                }
                            }
                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        plugins: {
                            datalabels: {
                                backgroundColor: function(context) {
                                    return context.dataset.backgroundColor;
                                },
                                borderRadius: 4,
                                padding:10,
                                font: {
                                    weight: 'bold'
                                },
                            }
                        }
                    }
                });
                $('#acciones3').append("<button id='Positivapdf' class='btn btn-danger' onclick='PositivaPDF();'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
                $('#acciones3').append("<a id='positivaPNG' class='btn btn-primary' onclick='PositivasPNG();'><i class='bi bi-image-fill'></i></a>");
                $('#page-template3').append("<canvas id='positivaCanvas' height='200' width='500'></canvas>");
                document.getElementById('mes4').innerHTML = mes1;
                var ctx3 = document.getElementById("positivaCanvas");
                ctx3.getContext("2d");
                window.myBar = new Chart(ctx3, {
                    plugins: [ChartDataLabels],
                    type: "bar",
                    data: {
                        labels: asesoresPos,
                        datasets: [
                            {
                                type: "bar",
                                backgroundColor:['rgba(75, 192, 192, 0.2)'],
                                borderColor: 'rgb(75, 192, 192)',
                                color: '#9A9A9B',
                                borderWidth: 1,
                                label: "Promedio por mes",
                                order: 1,
                                data: promedTotalesPos,
                                datalabels: {
                                    align: 'start',
                                    formatter: (dato) => dato + "%",
                                    anchor: 'end',
                                    font: {
                                        size: "20",
                                        weight: "bold",
                                    },
                                }
                            },
                            {
                                type: "line",
                                label: "Encuestas totales por mes",
                                data: EncTotalesPos,
                                lineTension: 0,
                                backgroundColor: 'rgb(2, 133, 26)',
                                borderColor: "rgb(2, 133, 26 )",
                                order: 0,
                                fill: false,
                                datalabels: {
                                    align: 'end',
                                    anchor: 'end',
                                    color:'#ffff',
                                }
                            }
                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        plugins: {
                            datalabels: {
                                backgroundColor: function(context) {
                                    return context.dataset.backgroundColor;
                                },
                                borderRadius: 4,
                                padding:10,
                                font: {
                                    weight: 'bold'
                                },
                            }
                        }
                    }
                });
                $('#acciones4').append("<button id='pastelPDF' class='btn btn-danger' onclick='ExportPENPPerfectas()'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
                $('#acciones4').append("<a id='PENPPerfect' class='btn btn-primary' onclick='PENPPerfectas();'><i class='bi bi-image-fill'></i></a>");
                $('#page-template4').append("<canvas id='pastel'></canvas>");
                document.getElementById('mes5').innerHTML = mes1;
                var barChartData4 = {
                    labels: labelsPastel,      
                    datasets: [{
                        label: 'Grafido del Total de Encuestas del Último Mes',
                        data: pastel,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ],
                        borderWidth: 1
                    }]
                };
                var ctx4 = document.getElementById("pastel").getContext("2d");
                window.myBar = new Chart(ctx4, {
                    plugins: [ChartDataLabels],
                    type: 'pie',
                    data: barChartData4,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: '% Porcentaje de encuestas totales por asesor'
                            },
                            tooltip: {
                                callbacks: {
                                    label: context => context.label + ': ' + context.formattedValue + '%',
                                    text:context =>  context.formattedValue + '%'
                                }
                            },
                            datalabels: {
                                anchor: "center",
                                formatter: (dato) => dato + "%",
                                color: "#9A9A9B",
                                font: {
                                    size: "20",
                                    weight: "bold",
                                },
                            }
                        },
                    },
                });
                $('#acciones5').append("<button id='prePorAsesPDF' class='btn btn-danger' onclick='PreguntaPDF()'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
                $('#acciones5').append("<a id='prePorAsesPNG' class='btn btn-primary' onclick='PreguntaPNG();'><i class='bi bi-image-fill'></i></a>");
                $('#page-template5').append("<canvas id='PreporAse' height='200' width='500'></canvas>");
                document.getElementById('mes6').innerHTML = NombreAsesor;
                var ctx5 = document.getElementById("PreporAse");
                ctx5.getContext("2d");
                window.myBar = new Chart(ctx5, {
                    plugins: [ChartDataLabels],
                    type: "bar",
                    data: {
                        labels: pregunta,
                        datasets: [
                            {
                                type: "bar",
                                backgroundColor:['rgba(75, 192, 192, 0.2)'],
                                borderColor: 'rgb(75, 192, 192)',
                                color: '#9A9A9B',
                                borderWidth: 1,
                                label: "Promedio por mes",
                                order: 1,
                                data: promedio,
                                datalabels: {
                                    align: 'start',
                                    formatter: (dato) => dato + "%",
                                    anchor: 'end',
                                    font: {
                                        size: "20",
                                        weight: "bold",
                                    },
                                }
                            },

                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        plugins: {
                            datalabels: {
                                backgroundColor: function(context) {
                                    return context.dataset.backgroundColor;
                                },
                                borderRadius: 4,
                                padding:0,
                                font: {
                                    weight: 'bold'
                                },
                            }
                        }
                    }
                });
            })
        }
    </script>
    <script>
    function PMensual() {
        var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
        if (document.getElementById("proMensual").msToBlob) {
            var blob = document.getElementById("proMensual").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("image1");
            link.href = document.getElementById("proMensual").toDataURL("image/png");
            link.download = filename
        }
    }
    function PENPPerfectas() {
        var filename = prompt("Guardar como...", "Porcentaje de encuestas totales por asesor");
        if (document.getElementById("pastel").msToBlob) {
            var blob = document.getElementById("pastel").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("PENPPerfect");
            link.href = document.getElementById("pastel").toDataURL("image/png");
            link.download = filename
        }
    }
    function  NegativaPNG() {
        var filename = prompt("Guardar como...", "Porcentaje de encuestas negativas por asesor");
        if (document.getElementById("negativaCanvas").msToBlob) {
            var blob = document.getElementById("negativaCanvas").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("negativaPNG");
            link.href = document.getElementById("negativaCanvas").toDataURL("image/png");
            link.download = filename
        }
    }
    function PositivasPNG(){
        var filename = prompt("Guardar como...", "Porcentaje de encuestas Positivas por asesor");
        if (document.getElementById("positivaCanvas").msToBlob) {
            var blob = document.getElementById("positivaCanvas").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("positivaPNG");
            link.href = document.getElementById("positivaCanvas").toDataURL("image/png");
            link.download = filename
        }
    }
    function PreguntaPNG(){
        console.log(document.getElementById("PreporAse"));
        var filename = prompt("Guardar como...", "Porcentaje de encuestas negativas por asesor");
        if (document.getElementById("PreporAse").msToBlob) {
            var blob = document.getElementById("PreporAse").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("prePorAsesPNG");
            link.href = document.getElementById("PreporAse").toDataURL("image/png");
            link.download = filename
        }
    }
    //PDFS
    function ExportProMensual() {

    kendo.drawing.drawDOM("#proMensual", {
        forcePageBreak: ".page-break",
        paperSize: "A4",
        margin: {top: "2cm",bottom: "5cm",left:"1cm"},
        scale: 0.6,
        height: 200,
        template: $("#page-template").html(),
        keepTogether: ".prevent-split"
    }).then(function(group) {
        kendo.drawing.pdf.saveAs(group, "Promedio mensual por Asesor.pdf")
    })
    }
    function ExportPENPPerfectas() {
    kendo.drawing.drawDOM("#pastel", {
        forcePageBreak: ".page-break",
        paperSize: "A4",
        margin: {
            top: "2cm",
            bottom: "5cm",
            left: "0cm"
        },
        scale: 1,
        height: 200,
        template: $("#page-template4").html(),
        keepTogether: ".prevent-split"
    }).then(function(group) {
        kendo.drawing.pdf.saveAs(group, "Porcentaje de encuestas totales por asesor.pdf")
    })
    }
    function PositivaPDF() {
    kendo.drawing.drawDOM("#positivaCanvas", {
        forcePageBreak: ".page-break",
        paperSize: "A4",
        margin: {top: "2cm",bottom: "5cm",left:"1cm"},
        scale: 0.6,
        height: 200,
        template: $("#page-template3").html(),
        keepTogether: ".prevent-split"
    }).then(function(group) {
        kendo.drawing.pdf.saveAs(group, "Porcentaje de encuestas Positivas por asesor.pdf")
    })
    }
    function NegativaPDF() {
        kendo.drawing.drawDOM("#negativaCanvas", {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: {top: "2cm",bottom: "5cm",left:"1cm"},
            scale: 0.6,
            height: 200,
            template: $("#page-template1").html(),
            keepTogether: ".prevent-split"
        }).then(function(group) {
            kendo.drawing.pdf.saveAs(group, "Porcentaje de encuestas negativas por asesor.pdf")
        })
    }
    function PreguntaPDF() {
        kendo.drawing.drawDOM("#PreporAse", {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: {top: "2cm",bottom: "5cm",left:"1cm"},
            scale: 0.6,
            height: 200,
            template: $("#page-template5").html(),
            keepTogether: ".prevent-split"
        }).then(function(group) {
            kendo.drawing.pdf.saveAs(group, "Porcentaje mensual por pregunta.pdf")
        })
    }


    </script>
@stop

<!--agregamos Java Script-->