@extends('layouts.encuestas')

@section('contenido')

<div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Encuestas  servicio
                </div>
                <div class="card-body">
                    <b><h4>¡Hola!</h4></b>
                    <p class="text-inicio">Está recibiendo este correo electrónico por que nos importa,
                    nos gustaria que pudiera contestar una de nuestras encuestas para poder mejorar nuestros sevicios.</p>
                    <hr>
                    <div class="boton-Contestar">
                        <form action="http://127.0.0.1:8000/verificadoAutomotriz1/{{$encuesta}}" method="GET">
                            <input type="text" name="orden" id="orden" class="form-control " style="display:none" value="{{$orden}}">
                            <button type="submit" Class="btn-contestar">Contestar Encuesta</button>
                        </form>
                    </div>
                    <hr>
                    <p class="text-final">
                        1. Si tiene problemas para hacer clic en el botón "Contestar Encuesta", copie y pegue la siguiente URL en su navegador web:
                        <a href="http://127.0.0.1:8000/verificadoAutomotriz1/{{$encuesta}}">http://127.0.0.1:8000/verificadoAutomotriz1/{{$encuesta}}</a><br><br>
                        2. Pegue el siguiente codigo en el campo "No de orden" <b class="text-black ">{{$orden}}</b>
                    </p>
                </div>  
            </div>
        </div>
    </div>
@endsection
@section('css')
<style>
    
    .container{
    font-family: "Nunito", sans-serif;
    width:100vw;
    height: 100vh;
    }
    .col-12{
    width: 100%;
    height: 100%;
    margin: 0 auto;
    border: 1px solid #ced4da;
    border-radius: 5px;
    

    }
    .card{
    width: 100%;
    height: 100%;
    }
    .card-header{
    background-color: #022E49;
    color: #fff;
    font-size: 1.2em;
    height: 40px;
    padding: 12px 16px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    }
    .card-body{
    padding: 20px;
    }
    .card-body h4{
    margin-bottom: 20px;
    }
    .card-body .text-inicio{
    margin-bottom: 20px;
    font-size: .9em;
    color: #6c757d;
    }
    .boton-Contestar{
    display: flex;
    padding: 50px;
    padding-left:150px;
    justify-content:center;
    }
    .text-final{
    margin-top: 20px;
    font-size: .9em;
    color: #6c757d;
    }
    .btn-contestar{
    max-width: 140px;
    min-width: 140px;
    color: #fff;
    height: 35px;
    border: 1px;
    margin: 0 auto;
    border-radius: 4px;
    background-color:#0d6efd;
    }
</style>
@stop

@section('js')

@endsection 


