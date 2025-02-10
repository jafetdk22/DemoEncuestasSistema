
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
            <div class="EncuestaName"><h5>Datos de: {{$encuestas->Nombre}}</h5>
            <div class="menuBTN">
                <button onclick="Mostrar();" id="mostrar"><i class="bi bi-caret-down-fill"></i></button>
                <button onclick="Ocultar();" style="display:none;" id="ocultar"><i class="bi bi-caret-up-fill"></i></button>
            </div>
        </div>
            <div class="botones" id="menuBTN">
                <form action="/resumen/{{$encuestas->id}}" method="post" class="for-ases">
                    @csrf
                    @if($encuestas->concesion == 'Automotriz1')
                        <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz2')
                        <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz4')
                        <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz5')
                        <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz6')
                        <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz7')
                        <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                    @endif
                        <button type="submit" class="btn btne asesores "><span><i class="fas fa-newspaper ases"> </i></span><br> Resumen</button>
                </form>
                <form action="/encuesta/{{$encuestas->id}}" method="post" class="for-ases">
                    @csrf
                    @if($encuestas->concesion == 'Automotriz1')
                        <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz2')
                        <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz4')
                        <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz5')
                        <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz6')
                        <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz7')
                        <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                    @endif
                        <button type="submit" class="btn btne asesores "><span><i class="fas fa-newspaper ases"> </i></span><br> Encuestas</button>
                </form>
                <div class="enlaces-btn">
                    <a href="/estadisticas/{{$encuestas->id}}" type="button" class="btn btne bg-primary"><span><i class="fas fa-chart-line"></i></span><br> Estadisticas</a>
                </div>
                <form action="/detalle-diario/{{$encuestas->id}}" method="post" class="for-ases">
                    @csrf
                    @if($encuestas->concesion == 'Automotriz1')
                        <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz2')
                        <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz4')
                        <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz5')
                        <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz6')
                        <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz7')
                        <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                    @endif
                    <button type="submit" class="btn btne asesores"><span><i class="fas fa-calendar-alt"></i></span> <br> Detalle Diario</button>
                </form>
                <form action="/asesores/{{$encuestas->id}}" method="post" class="for-ases">
                    @csrf
                    @if($encuestas->concesion == 'Automotriz1')
                        <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz2')
                        <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz4')
                        <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz5')
                        <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz6')
                        <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                    @elseif($encuestas->concesion== 'Automotriz7')
                        <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                    @endif

                    <button type="submit" class="btn btne asesores "><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
                </form>
            </div>
        </div>
        <div class="col selectsNav">
                <div class="meses">
                    <label class="control-label">Mes: </label>
                    <select ng-model="month" class="form-select form-select-sm" aria-label=".form-select-sm example" ng-options="m for m in months" id="mes1">
                        <option value="nada">--Selecciona una Opcción--</option>
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
                </div>
                <div class="anhos">
                    <label class="control-label">año: </label>
                    <select ng-model="month" class="form-select form-select-sm" aria-label=".form-select-sm example" ng-options="m for m in months"id="anho">
                        @foreach($anhos as $anho)
                            <option value="{{$anho}}">{{$anho}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="encuesta" id="encuesta" value="{{$encuestas->id}}">
        </div>
   </div>
    <div class="estadisticas">
        <div class="row mb-4  d-flex justify-content-center ">
            <div class="col mb-4" id="rangosXmes">
                <div class="panel panel-default" id="page" >
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes2"></label> hasta <label id="mes3"></label></div>
                    <div class="panel-body">
                        <div id="acciones1"></div>
                        <div id="page-template" class="col" style="margin:0 auto ;">
                            <canvas id="proMensual" height="200" width="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="panel panel-default">
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Umbral de <label id="mes6"></label></div>
                        <div class="panel-body">
                            <div id="acciones4"></div>
                            <div id="page-template4">
                                <canvas id="pastel"></canvas>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="col-12 mb-4 d-flex" id="graficasPyN">
                <div class="col">
                    <div class="panel panel-defult">
                        <div class="panel-heading  titulo-1 text-center mt-3"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes4"></label> hasta <label id="mes5"></label></div>
                        <div class="panel-body">
                            <div id="acciones2"></div>
                            <div id="page-template1">
                                <canvas id="Negativascanvas" height="200" width="500"></canvas>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="col mb-4">
                    <div class="panel panel-defult">
                        <div class="panel-heading  titulo-1 text-center mt-3"><i class="bi bi-bar-chart-fill"></i> Datos de <label id="mes7"></label> hasta <label id="mes8"></label></div>
                        <div class="panel-body">
                            <div id="acciones3"></div>
                            <div id="page-template2">
                                <canvas id="Positivascanvas" height="200" width="500"></canvas>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/estadisticas.css">
    <link rel="stylesheet" href="/css/responsive.css">
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
        @media(max-width:404px){
            .estadisticas{
                position: relative;
                top:20% !important;
            }
            .selectsNav{
                display:block;
            }
        }
    </style>
@stop

@section('js')
    <!--agregamos librerias-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script src="http://kendo.cdn.telerik.com/2017.2.621/js/kendo.all.min.js"></script>
    <!--agregamos librerias-->

 <script>
     window.onload = mi_funcion();
    $(function () {
        $('#mes1').change(mi_funcion);
        $('#anho').change(mi_funcion);
    });
    function mi_funcion () {
        var mes1 = $('#mes1').val();
        var anho = $('#anho').val();
        var encuesta = $('#encuesta').val();
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
        $('#proMensual').remove();//canvas
        $('#pdfImage1').remove();
        $('#image1').remove();

        $('#Negativascanvas').remove();//canvas
        $('#negativaspdf').remove();
        $('#negativasimages').remove();

        $('#Positivascanvas').remove();//canvas
        $('#positivaspdf').remove();
        $('#positivasimages').remove();

        $('#pastel').remove();//canvas
        $('#PENPPerfect').remove();
        $('#pastelPDF').remove();

        $.ajax({
            url: '/encuestasAjax',
            method:'get',
            data:{mes1:mes1, anho:anho,encuesta:encuesta}
        }).done(function(res){
            var arreglo =JSON.parse(res);
            var  meses = arreglo[0][0];
            var  cuantas = arreglo[0][1];
            var  promedios = arreglo[0][2];

            var  cuantasNE = arreglo[1][0];
            var  porcentajesNE = arreglo[1][1];
            
            var  cuantasPos = arreglo[2][0];
            var  porcentajesPos = arreglo[2][1];

            var  labelsPastel=arreglo[3][1];
            var  pastel=arreglo[3][0];
            
            $('#acciones1').append("<button  id='pdfImage1' class='btn btn-danger' ><i class='bi bi-file-earmark-pdf-fill' onclick='ExportProMensual();'></i></button>");
            $('#acciones1').append("<a id='image1' class='btn btn-primary' onclick='PMensual();'><i class='bi bi-image-fill'></i></a>");
            $('#page-template').append("<canvas id='proMensual' width='500' height='200'></canvas>");
            document.getElementById('mes2').innerHTML = arreglo[0][0][4];
            document.getElementById('mes3').innerHTML = arreglo[0][0][0];
            var ctx = document.getElementById("proMensual");
            ctx.getContext("2d");
            window.myBar = new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: "bar",
                data: {
                    labels: meses,
                    datasets: [
                        {
                            type: "bar",
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgb(54, 162, 235)',
                            color: '#9A9A9B',
                            borderWidth: 1,
                            label: "Promedio por mes",
                            order: 1,
                            data: promedios,
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
                            label: "Encuestas totales por mes",
                            data: cuantas,
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
            $('#acciones2').append("<button id='negativaspdf' class='btn btn-danger' onclick='EPENXA();'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
            $('#acciones2').append("<a id='negativasimages' class='btn btn-primary' onclick='PENXA();'><i class='bi bi-image-fill'></i></a>");
            $('#page-template1').append("<canvas id='Negativascanvas' height='200' width='500'></canvas>");
            document.getElementById('mes4').innerHTML = arreglo[0][0][4];
            document.getElementById('mes5').innerHTML = arreglo[0][0][0];
            var ctx = document.getElementById("Negativascanvas");
            ctx.getContext("2d");
            window.myBar = new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: "bar",
                data: {
                    labels: meses,
                    datasets: [
                        {
                            type: "bar",
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgb(255, 99, 132)',
                            color: '#9A9A9B',
                            borderWidth: 1,
                            label: "Promedio por mes",
                            order: 1,
                            data: porcentajesNE,
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
                            data: cuantasNE ,
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
            $('#acciones3').append("<button class='btn btn-danger' id='positivaspdf' onclick='EPEPXA();'><i class='bi bi-file-earmark-pdf-fill'></i></button>");
            $('#acciones3').append("<a id='positivasimages' class='btn btn-primary' onclick='PMXA();'><i class='bi bi-image-fill'></i></a>");
            $('#page-template2').append("<canvas id='Positivascanvas' height='200' width='500'></canvas>");
            var ctx = document.getElementById("Positivascanvas");
            ctx.getContext("2d");
            window.myBar = new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: "bar",
                data: {
                    labels: meses,
                    datasets: [
                        {
                            type: "bar",
                            backgroundColor:['rgba(75, 192, 192, 0.2)'],
                            borderColor: 'rgb(75, 192, 192)',
                            color: '#9A9A9B',
                            borderWidth: 1,
                            label: "Promedio por mes",
                            order: 1,
                            data: porcentajesPos,
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
                            data: cuantasPos ,
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
            document.getElementById('mes6').innerHTML = arreglo[0][0][0];
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
                            text: '% Porcentaje de Encuestas Negativas, Positivas y Perfectas'
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

        })
    
    }   
 </script>
 <script>
    //img
    function PMensual() {
        console.log(document.getElementById("proMensual"));
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
        var filename = prompt("Guardar como...", "Porcentaje de Encuestas Por Asesor");
        if (document.getElementById("pastel").msToBlob) {
            var blob = document.getElementById("pastel").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("PENPPerfect");
            link.href = document.getElementById("pastel").toDataURL("image/png");
            link.download = filename
        }
    }
    function PENXA() {
        var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
        if (document.getElementById("Negativascanvas").msToBlob) {
            var blob = document.getElementById("Negativascanvas").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("negativasimages");
            link.href = document.getElementById("Negativascanvas").toDataURL("image/png");
            link.download = filename
        }
    }
    function PMXA() {
        var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
        if (document.getElementById("Positivascanvas").msToBlob) {
            var blob = document.getElementById("Positivascanvas").msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("positivasimages");
            link.href = document.getElementById("Positivascanvas").toDataURL("image/png");
            link.download = filename
        }
    }
    //PDFS
    function ExportProMensual() {

        kendo.drawing.drawDOM("#proMensual", {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: {top: "2cm",bottom: "5cm",left:"1cm"},
            scale: 0.8,
            height: 200,
            template: $("#page-template").html(),
            keepTogether: ".prevent-split"
        }).then(function(group) {
            kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
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
            kendo.drawing.pdf.saveAs(group, "Exported_Itinerary.pdf")
        })
    }
    function EPENXA() {
            kendo.drawing.drawDOM("#Negativascanvas", {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: {top: "2cm",bottom: "5cm",left:"1cm"},
            scale: 0.8,
            height: 200,
            template: $("#page-template1").html(),
            keepTogether: ".prevent-split"
        }).then(function(group) {
            kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
        })
    }
    function EPEPXA() {
        kendo.drawing.drawDOM("#Positivascanvas", {
            forcePageBreak: ".page-break",
            paperSize: "A4",
            margin: {top: "2cm",bottom: "5cm",left:"1cm"},
            scale: 0.8,
            height: 200,
            template: $("#page-template2").html(),
            keepTogether: ".prevent-split"
        }).then(function(group) {
            kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
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
@stop

<!--agregamos Java Script-->