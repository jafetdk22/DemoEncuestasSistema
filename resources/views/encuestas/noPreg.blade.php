@extends('layouts.encuestas')

@section('contenido')
<div class="d-flex align-items-center justify-content-center vh-100" style="background-color: #022E49;">
    <div class=" ">
    <h1 class="display-5 fw-bold text-white text-center">Sentimos las Molestias</h1>
        <br>
        <h6 class="display-7 text-white text-center">Esta encuesta no contiene preguntas disponibles.</h6><br>
        <div class="d-flex align-items-center justify-content-center">
        @can('Operativo')
            <a href="/encuestas" class="btn  text-center" style="background-color:#fff; color:#022E49; margin-right:10%; ">Home</a>
         @endcan
            <a href="javascript:closed();" class="btn  text-center" style="background-color:#fff; color:#022E49;  min-width:140px;">Cerrar Pestaña</a>
        </div>

    </div>
    

    </div>
@endsection
@section('css')
<link rel="stylesheet" href="/css/estadisticas.css">
@endsection
@section('js')
<script language="javascript" type="text/javascript"> 
    function closed() { 
       window.open('','_parent',''); 
       window.close(); 
    } 
    </script>


@endsection
