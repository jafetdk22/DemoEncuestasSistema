
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
        <a href="/estadisticas/{{$encuesta->id}}" class="btn btne"><span><i class="fas fa-chart-line"></i></span><br> Estadisticas</a>
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
            <button type="submit" class="btn btne asesores bg-primary"><span><i class="fas fa-people-arrows ases"></i></span> <br> Asesores</button>
        </form>
    </ul>
</div>
@stop


@section('content')
<div class="estadisticas">
    <div class="row mb-4  d-flex justify-content-center containers">
        <div class="col-12 mb-4 d-flex justify-content-center ">
            <div class="col-2">
                <label class="form-label"> Desde:</label>
                <input type="text" class="form-control" id="desde" name="desde" required/>
            </div>
            <div class="col-2">
                <label class="form-label">Hasta:</label>
                <input type="text" class="form-control" id="hasta" name="hasta" required/>
                <input type="hidden" name="encuesta" id="encuesta" value="{{$encuesta->id}}">
                <input type="hidden" name="asesor" id="asesor" value="{{$id}}">
            </div>
            <div class="col-3">
                <button id="button" class="btn btn-primary position-absolute bottom-0 start-0" onclick="datosBTN()">Buscar</button>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-center" id="aviso">
            <label for="" class="text-secondary" >Selecciona un rango de fechas y da clic en en boton <b>"buscar"</b>  para poder visualizar las estadisticas.</label>
        </div>
        <div class="col-9"  height="200">
            <div class="panel panel-default" id="page">
                <div class="panel-heading  titulo-1 text-center"><p><i class="bi bi-bar-chart-fill"></i> Datos del   <label id="fecha1"></label>   al   <label id="fecha2"></label></p></div>
                <div class="panel-body">
                    <div id="contenedorGrafico">
                        <canvas id="EPXAsesor" height="200" width="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/estadisticas.css">
  <style>
    .estadisticas{
        width: 100%;
        height:750px;
        overflow-x:hidden; 
        overflow-y:auto;

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
        max-width: 1400px;
        max-height:600px;
    }
    .pastel{
        display:flex;
        justify-content:center;
    }
   #page-template4{
       width: 70%;

   }
   #page-template{
       width: 70%;
       
   }
   .proMensual{
    display:flex;
        justify-content:center;
   }

  

  </style>
@stop

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    
    <script src="http://kendo.cdn.telerik.com/2017.2.621/js/kendo.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    $(function () {
        $.datepicker.setDefaults($.datepicker.regional["es"]={
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
        });
        $("#desde").datepicker({
        dateFormat: 'yy-mm-dd',
        firstDay: 1
        }).datepicker("setDate", "-30");
        $.datepicker.setDefaults($.datepicker.regional["es"]={
        closeText: 'Cerrar',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
        });
        $("#hasta").datepicker({
        dateFormat: 'yy-mm-dd',
        firstDay: 1
        }).datepicker("setDate", new Date());
    });
</script>
<script>
    function datosBTN(){
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var encuesta = $('#encuesta').val();
        var asesor = $('#asesor').val();
        $('#EPXAsesor').remove();
        $('#aviso').remove();
        $('#pdfs').remove();
        $('#EPXAsesores').remove();
        document.getElementById('fecha1').innerHTML = desde;
        document.getElementById('fecha2').innerHTML = hasta;

        $.ajax({
            url: '/ajax',
            method:'get',
            data:{desde:desde,hasta:hasta,encuesta:encuesta, asesor:asesor}
        }).done(function(res){
            console.log(res);
            var arreglo =JSON.parse(res);
            var  promedios = arreglo[0];
            var  preguntas = arreglo[1];
            var  cuantas = arreglo[2];
            /*console.log(arreglo);*/
            $('#contenedorGrafico').append("<button class='btn btn-danger' id='pdfs'><i class='bi bi-file-earmark-pdf-fill'  onclick='EPEPXA();'></i></button>");
            $('#contenedorGrafico').append("<a id='EPXAsesores' class='btn btn-primary' onclick='PMXA();'><i class='bi bi-image-fill'></i></a>");
            $('#contenedorGrafico').append("<canvas id='EPXAsesor' width='500' height='200'></canvas>");

            var ctx = document.getElementById("EPXAsesor");
            ctx.getContext("2d");

            window.myBar = new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: "bar",
                data: {
                    labels: preguntas,
                    datasets: [
                        {
                            type: "bar",
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgb(75, 192, 192)',
                            color: '#9A9A9B',
                            borderWidth: 1,
                            label: "Promedio por pregunta",
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
                            label: "Encuestas Totales por Asesor",
                            data: cuantas,
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
                    scales: {
                        yAxes: [{
                            ticks: {
                            beginAtZero: true
                            }
                        }]
                    },
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
        })
    }
 
    function PMXA() {
        var canvasEPXAsesor = document.getElementById("EPXAsesor");
        var filename = prompt("Guardar como...", "Porcentaje Mensual de Encuestas Positivas por Asesor");
        if (canvasEPXAsesor.msToBlob) {
            var blob = canvasEPXAsesor.msToBlob();
            window.navigator.msSaveBlob(blob, filename + ".png")
        } else {
            link = document.getElementById("EPXAsesores");
            link.href = canvasEPXAsesor.toDataURL("image/png");
            link.download = filename
        }
        }
        function EPEPXA() {
        
        kendo.drawing.drawDOM("#EPXAsesor", {
        forcePageBreak: ".page-break",
        paperSize: "A4",
        margin: {top: "2cm",bottom: "5cm",left:'2cm'},
        scale: 0.6,
        height: 200,
        template: $("#page-template1").html(),
        keepTogether: ".prevent-split"
        }).then(function(group) {
        kendo.drawing.pdf.saveAs(group, "Porcentaje de Encuestas Positivas por Asesor.pdf")
        })
        }
</script>

@stop