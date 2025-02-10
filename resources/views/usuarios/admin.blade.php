
@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')
@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
    
    <div class="container">
        <div class="row border rounded-8">
            <div class="col text-center bg-navy ml-1 mr-1" >
                <a href="//encuestas" class="btn">Mis Encuestas</a> 
            </div>
            <div class="col text-center bg-navy ">
                <a href="//preguntas" class="btn"> Mis Preguntas</a>
            </div>
            @can('Gerencial')
            <div class="col text-center text-navy ">
                <a href="/administracion/{{ auth()->user()->id }}" class="btn">Administración</a>
            </div>
            @endcan
        </div>
        <div class="row">
            
          
        </div>
    </div>
@stop

<!--Contenido de nuestra pagina-->

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        ul{
            list-style: none;
        }
    </style>
@stop
<!--agregamos css-->

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu":[[5,8],[5,8]]
    });
} );
</script>
@stop

<!--agregamos Java Script-->