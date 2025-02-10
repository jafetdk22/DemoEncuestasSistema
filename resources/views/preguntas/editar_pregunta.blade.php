@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')
@stop
@section('content')
    
    <div class="estadisticas">
        <div class=".col-xl">
            <p class="bg-primary  text-center p-1  rounded-3"><b>Respuesta:</b> {{$respuesta->respuesta}}</p>
            <a href="/preguntas" class="btn btn-danger">Regresar</a>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">{{__('Editar Respuesta')}}</div>
                    <div class="card-body">
                    <form action="/respuestasUpdate/{{$respuesta->id}}" method="POST">
                        @csrf
                            <div class="row">
                            <label for="res" class="col-md-8 col-form-label text-md-end">Texto de la respuesta</label><br>
                            <div class="col-8" >
                                <input id="res" type="text" class="form-control @error('respuesta') is-invalid @enderror ml-4" name="res" required  autofocus value ="{{$respuesta->respuesta}}"> 
                                @error('res')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                            <div class="row">
                                <label for="valor" class="col-md-8 col-form-label text-md-end"> valor que tendrá esta respuesta<span class="m-2"></span></label><br>
                                <div class="col-md-6 ">
                                    <select name="valor" id="valor" class="form-select form-select-lg ml-4 swal2-select swal2-input bg-light" value="{{$respuesta->valor}}">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                   </select>
                                </div>
                            </div>
                            <div class="row m-3 justify-content-left">
                            <div class="col">
                                <button type="submit" class="btn btn-primary" >Aceptar</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
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
    $('#example1').DataTable({
        "lengthMenu":[[5],[5]]
    });
} );

</script>
@stop

<!--agregamos Java Script-->