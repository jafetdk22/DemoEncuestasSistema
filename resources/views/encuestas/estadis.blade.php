
@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')
<div class="navi">
<div class="EncuestaName">
    <h4 >Datos de: {{$encuesta->Nombre}}</h4>
</div>
    <ul>
    <form action="/resumen/{{$encuesta->id}}" method="post" class="for-ases">
                    @csrf
                        @if($encuesta->concesion == 'Automotriz Lerma')
                            <input type="text" value="Lerma" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol')
                            <input type="text" value="divol" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Lindavista')
                            <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Perinorte')
                            <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Norte')
                            <input type="text" value="Norte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Pirineos Motors')
                            <input type="text" value="Motors" name="conexionDB" style="display:none">
                        @endif
            <button type="submit" class="btn btne asesores g-primary"><span><i class="fas fa-newspaper ases"> </i></span><br> Resumen</button>
        </form>
        <form action="/encuesta/{{$encuesta->id}}" method="post" class="for-ases">
        @csrf
                        @if($encuesta->concesion == 'Automotriz Lerma')
                            <input type="text" value="Lerma" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol')
                            <input type="text" value="divol" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Lindavista')
                            <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Perinorte')
                            <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Norte')
                            <input type="text" value="Norte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Pirineos Motors')
                            <input type="text" value="Motors" name="conexionDB" style="display:none">
                        @endif
            <button type="submit" class="btn btne asesores "><span><i class="fas fa-newspaper ases"> </i></span><br> Encuestas</button>
        </form>
        <a href="/estadisticas/{{$encuesta->id}}" class="btn btne bg-primary"><span><i class="fas fa-chart-line"></i></span><br> Estadisticas</a>
        <a href="/detalle-diario/{{$encuesta->id}}" class="btn btne"><span><i class="fas fa-calendar-alt"></i></span> <br> Detalle Diario</a>
        <form action="/asesores/{{$encuesta->id}}" method="post" class="for-ases">
                    @csrf
                        @if($encuesta->concesion == 'Automotriz Lerma')
                            <input type="text" value="Lerma" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol')
                            <input type="text" value="divol" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Lindavista')
                            <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Perinorte')
                            <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Divol Norte')
                            <input type="text" value="Norte" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Pirineos Motors')
                            <input type="text" value="Motors" name="conexionDB" style="display:none">
                        @endif
            <button type="submit" class="btn btne asesores"><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
        </form>
    </ul>
</div>
@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
    <div class="estadisticas ">
        <div class="row mb-4">
        <div class="col-12 mb-4 d-flex justify-content-center">
            <div class="col-2">
                <label class="form-label"> Desde:</label>
                <input type="text" class="form-control" id="desde" name="desde" required/>
            </div>
            <div class="col-2 ">
                <label class="form-label">Hasta:</label>
                <input type="text" class="form-control" id="hasta" name="hasta" required/>
                <input type="hidden" name="encuesta" id="encuesta" value="">
                <input type="hidden" name="asesor" id="asesor" value="">
            </div>
            <div class="col-2  d-flex" style="max-height:50%; margin-top:2%;">
                <button id="button" class="btn btn-primary position-relative bottom-0 start-0" onclick="datosBTN()">Buscar</button>
            </div>
        </div>
            <div class="col">
                <div class="panel panel-default" id="page" >
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Datos de los Ultimos 5 Meses</div>
                    <div class="panel-body" >
                        <div>
                            <button class="btn btn-danger " ><i class="bi bi-file-earmark-pdf-fill"  onclick='ExportProMensual();'></i></button>
                            <a id="download" class="btn btn-primary" onclick='PMensual();'><i class="bi bi-image-fill"></i></a>
                        </div>
                        <div id="page-template">
                            <canvas id="proMensual" height="200" width="500"></canvas>
                        </div>
                            
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="panel panel-default">

                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Datos de los Ultimos 5 Meses</div>
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                            <button class="btn btn-danger" onclick="ExportPositivas();"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                                <a id="positivas" class="btn btn-primary" onclick='Positivas();'><i class="bi bi-image-fill"></i></a>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                            <button class="btn btn-danger" onclick="ExportNegativas();"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                                <a id="negativas" class="btn btn-primary" onclick='Negativas();'><i class="bi bi-image-fill"></i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" id="page-template1">
                                <canvas id="Positivas" height="180" width="300"></canvas>
                            </div>
                                <div class="col-6 " id="page-template2">
                                <canvas id="Negativas" height="180" width="300"></canvas>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="panel panel-default">
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Promedio por Pregunta</div>
                    <div class="panel-body">
                        <div>
                            <button class="btn btn-danger" onclick="ExportPMensualXPre();"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                            <a id="MensualXPre" class="btn  btn-primary" onclick='PMensualXPre();'><i class="bi bi-image-fill"></i></a>
                        </div>
                        <div id="page-template3">
                            <canvas id="ProPregunta" height="280" width="600"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="panel panel-default">
                    <div class="panel-heading  titulo-1 text-center"><i class="bi bi-bar-chart-fill"></i> Umbral del Ultimo Mes</div>
                        <div class="panel-body pastel" >
                        <div>
                            <button class="btn btn-danger" onclick="ExportPENPPerfectas()"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                            <a id="PENPPerfect" class="btn btn-primary" onclick='PENPPerfectas();'><i class="bi bi-image-fill"></i></a>
                        </div>
                        <div id="page-template4">
                            <canvas id="pastel"></canvas>
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
<style>
    .body-conntent{
        background-color:#f4F6F9;
    }
    .estadisticas{
        width: 100%;
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
    #pastel{
        max-width: 500px;
        max-height:400px;
    }
    .pastel{
        display:flex;
        justify-content:center;
    }
   #page-template4{
       width: 80%;
   }
  

</style>

@stop
<!--agregamos css-->

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="http://kendo.cdn.telerik.com/2017.2.621/js/kendo.all.min.js"></script>
<script>
    var year = <?php echo $year; ?>;
    var user = <?php echo $user; ?>;
    var negativas = <?php echo $negativas; ?>;
    var positivas = <?php echo $positivas; ?>;
    var pastel = <?php echo $pastel; ?>;
    var labelsPastel = <?php echo $labelsPastel; ?>;
    var porcentajePreguntas = <?php echo $porcentajePreguntas; ?>;
    var labelPreguntas = <?php echo $labelPreguntas; ?>;
    var barChartData = {
        labels: year,      
        datasets: [{
            label: 'Promedio Mensual de las Encuestas',
            data: user,
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
             borderColor: [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
                ],
            borderWidth: 1
            }]
    };
    var barChartData1 = {
        labels: year,      
        datasets: [{
            label: '% Encuestas Negativas',
            data: negativas,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
             borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 99, 132)',
                'rgb(255, 99, 132)',
                'rgb(255, 99, 132)',
                'rgb(255, 99, 132)',
                'rgb(255, 99, 132)',
                ],
            borderWidth: 1
            }]
    };
    var barChartData2 = {
        labels: year,      
        datasets: [{
            label: '% Encuestas Positivas',
            data: positivas,
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
            ],
             borderColor: [
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                ],
            borderWidth: 1
            }]
    };
    var barChartData3 = {
        labels: labelPreguntas,      
        datasets: [{
            label: 'Promedio del Último Mes',
            data: porcentajePreguntas,
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(75, 192, 192, 0.2)',
            ],
             borderColor: [
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                'rgb(75, 192, 192)',
                ],
            borderWidth: 1
            }]
    };
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


    window.onload = function() {
        var ctx = document.getElementById("proMensual").getContext("2d");
        window.myBar = new Chart(ctx, {
            plugins: [ChartDataLabels],
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
                plugins: {
                    datalabels: {
                        anchor: "center",
                        formatter: (dato) => dato,
                        color: "#9A9A9B",
                        font: {
                        size: "18",
                        weight: "bold",
                        },
                    }
                },
                
            }
        });
        var ctx1 = document.getElementById("Negativas").getContext("2d");
        window.myBar = new Chart(ctx1, {
            plugins: [ChartDataLabels],
            type: 'bar',
            data: barChartData1,
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
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => context.label + ': ' + context.formattedValue + '%'
                        }
                    },
                    datalabels: {
                        anchor: "center",
                        formatter: (dato) => dato + "%",
                        color: "#9A9A9B",
                        font: {
                        size: "15",
                        weight: "bold",
                        },
                    }
                }
            }
        });
        var ctx2 = document.getElementById("Positivas").getContext("2d");
        window.myBar = new Chart(ctx2, {
            plugins: [ChartDataLabels],
            type: 'bar',
            data: barChartData2,
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
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => context.label + ': ' + context.formattedValue + '%'
                        }
                    },
                    datalabels: {
                        anchor: "center",
                        formatter: (dato) => dato + "%",
                        color: "#9A9A9B",
                        font: {
                        size: "15",
                        weight: "bold",
                        },
                    }
                }
                
            }
        });
        var ctx3 = document.getElementById("ProPregunta").getContext("2d");
        window.myBar = new Chart(ctx3, {
            plugins: [ChartDataLabels],
            type: 'bar',
            data: barChartData3,
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
                plugins: {
                    datalabels: {
                        anchor: "center",
                        formatter: (dato) => dato,
                        color: "#9A9A9B",
                        font: {
                        size: "18",
                        weight: "bold",
                        },
                    }
                },
                
            }
        });
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
                        text: '% Porcentaje de encuestas Negativas Positivas y Perfectas'
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
    };
    
</script>
<script>
    eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 U=9.b("14");4 1a(){3 5=n("o p...","15 1b V 1c W 1d");q(U.c){3 d=U.c();k.r.s(d,5+".e")}t{7=9.b("l");7.u=U.v("w/e");7.l=5}}3 X=9.b("x");4 x(){3 5=n("o p...","12 V W x");q(X.c){3 d=X.c();k.r.s(d,5+".e")}t{7=9.b("1e");7.u=X.v("w/e");7.l=5}}3 Y=9.b("z");4 z(){3 5=n("o p...","12 V W z");q(Y.c){3 d=Y.c();k.r.s(d,5+".e")}t{7=9.b("1f");7.u=Y.v("w/e");7.l=5}}3 Z=9.b("16");4 1g(){3 5=n("o p...","15 1h 1i 1j 1k 1l");q(Z.c){3 d=Z.c();k.r.s(d,5+".e")}t{7=9.b("1m");7.u=Z.v("w/e");7.l=5}}3 10=9.b("17");4 1n(){3 5=n("o p...","12 V W x, z y 1o");q(10.c){3 d=10.c();k.r.s(d,5+".e")}t{7=9.b("1p");7.u=10.v("w/e");7.l=5}}4 1q(){f.g.A("#14",{B:".a-C",D:"E",F:{G:"j",H:"I",J:"j"},K:0.6,L:M,m:$("#a-m").N(),O:".P-Q"}).R(4(h){f.g.i.S(h,"T.i")})}4 1r(){f.g.A("#x",{B:".a-C",D:"E",F:{G:"j",H:"I",J:"j"},K:1,L:M,m:$("#a-1s").N(),O:".P-Q"}).R(4(h){f.g.i.S(h,"T.i")})}4 1t(){f.g.A("#z",{B:".a-C",D:"E",F:{G:"j",H:"I",J:"j"},K:1.2,L:M,m:$("#a-1u").N(),O:".P-Q"}).R(4(h){f.g.i.S(h,"T.i")})}4 1v(){f.g.A("#16",{B:".a-C",D:"E",F:{G:"j",H:"I",J:"j"},K:0.6,L:M,m:$("#a-1w").N(),O:".P-Q"}).R(4(h){f.g.i.S(h,"T.i")})}4 1x(){f.g.A("#17",{B:".a-C",D:"E",F:{G:"j",H:"I",J:"1y"},K:.8,L:M,m:$("#a-1z").N(),O:".P-Q"}).R(4(h){f.g.i.S(h,"T.i")})}4 1A(a){3 18=9.b(a).11;3 19=9.13.11;9.13.11=18;k.1B();9.13.11=19}',62,100,'|||var|function|filename||link||document|page|getElementById|msToBlob|blob|png|kendo|drawing|group|pdf|2cm|window|download|template|prompt|Guardar|como|if|navigator|msSaveBlob|else|href|toDataURL|image|Negativas||Positivas|drawDOM|forcePageBreak|break|paperSize|A4|margin|top|bottom|5cm|left|scale|height|200|html|keepTogether|prevent|split|then|saveAs|Exported_Itinerary|canvasPM|de|Encuestas|canvasN|canvasPos|canvasPMPP|canvasPorcentaje|innerHTML|Porcentaje|body|proMensual|Promedio|ProPregunta|pastel|contenido|contenidoOriginal|PMensual|Mensual|las|Aplicadas|negativas|positivas|PMensualXPre|por|Pregunta|del|Ultimo|Mes|MensualXPre|PENPPerfectas|Perfectas|PENPPerfect|ExportProMensual|ExportNegativas|template1|ExportPositivas|template2|ExportPMensualXPre|template3|ExportPENPPerfectas|3cm|template4|printDiv|print'.split('|'),0,{}))
</script>
@stop

<!--agregamos Java Script-->