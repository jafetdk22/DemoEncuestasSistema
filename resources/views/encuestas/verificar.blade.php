@extends('layouts.encuestas')

@section('contenido')
<div class="container">
    <div class="row mb-5 d-flex justify-content-center">
        <div class="col mt-3">
            <div class="card">
                <div class="card-header encuesta_header"><h6>{{$encuesta->Nombre}}</h6> {{$encuesta->concesion}}</div>
                <div class="card-body ">
                    <form action="/contestar/{{$encuesta->id}}" method="POST">
                 
                        @csrf
                        @if($alerta==1)
                            <div class="alert alert-info" role="alert">
                               "Ingresar número de orden"
                            </div>
                        @elseif($alerta==2)
                             <div class="alert alert-danger" role="alert">
                                "Este número de orden no existe"
                            </div>
                        @elseif($alerta==3)
                             <div class="alert alert-warning" role="alert">
                                "Esta encuesta ya ha sido contestada"
                            </div>
                        @elseif($alerta==4)
                             <div class="alert alert-primary" role="alert">
                                "Gracias por haber contestado esta encuesta"
                            </div>
                        @elseif($alerta==5)
                             <div class="alert alert-danger" role="alert">
                                "Este número de orden fue cancelado"
                            </div>
                            
                        @endif
                        @if($encuesta->concesion == 'Automotriz1')
                            <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz2')
                            <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz3')
                            <input type="text" value="Automotriz3" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz4')
                            <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz5')
                            <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz6')
                            <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                        @elseif($encuesta->concesion== 'Automotriz7')
                            <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                        @endif

                        <label for="Orden " class="form-label">Número de orden</label>
                        <input type="text" name="Orden" id="Orden" class="form-control" value="{{$orden}}">
                        <input type="Number" name="encuesta" id="encuesta" style="display:none" class="form-control" value="{{$encuesta->id}}">
                        
                        <button type="submit" Class="btn btn-success mt-3">Contestar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
    setTimeout(function() {
        $(".alert-danger").fadeOut(1500);
    },3000);
    setTimeout(function() {
        $(".alert-warning").fadeOut(1500);
    },3000);
    setTimeout(function() {
        $(".alert-primary").fadeOut(1500);
    },3000);
    setTimeout(function() {
        $(".alert-info").fadeIOut(1500);
    },3000);

});
</script>
@endsection