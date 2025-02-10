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
            <p class="bg-primary  text-center p-1  rounded-3"><b>ENCUESTA:</b>  {{$encuesta->Nombre}}</p>
            <a href="/encuestas" class="btn btn-danger">Regresar</a>
        </div>
        <br>
        <div id="Asignar" class="row">
            <div class="col">
            <table id="example0" class="table table-striped" style="width:100%">
                <p class="bg-dark text-center">Preguntas Asignadas</p>
                <thead>
                <tr>
                    <th>Pregunta</th>
                    <th>Tipo</th>
                    <th class="emoji-table">emoji</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $preguntas as $row)

                <tr>
                    <td>{{$row->pregunta}}</td>
                    <td>{{$row->tipo}}</td>
                    <td class="emoji-table">{{$row->emoji}}</td>
                    <td>
                        <form action="/remover/{{$encuesta->id}}" method="POST">
                        @csrf 
                            <input id="pregunta" type="text"name="pregunta" value="{{ $row->id }}" required style=" display:none">
                            <button type="submit" class="btn btn-danger ">Remover</button>
                        </form>
                    </td>
                </tr>
                @endforeach
               </tbody>
            </table>
            </div>

            <div class="col">
            <table id="example1" class="table table-striped" style="width:100%">
                 <p class="bg-dark text-center">Preguntas Disponibles</p>
                <thead>
                <tr>
                    <th>Pregunta</th>
                    <th>Tipo</th>
                    <th class="emoji-table">emoji</th>
                    <th>Acciones</th>
                </tr>
               </thead>
               <tbody>
                   
                   @foreach($disp as $row)
                   @if($row->status=='DESACTIVADA')
                   @elseif($row->status=='ACTIVA' && $row->concesion == Auth::user()->concesion)
                    <tr>
                    <td>{{$row->pregunta}}</td>
                    <td>{{$row->tipo}}</td>
                    <td>{{$row->emoji}}</td>
                    <td>
                        <form action=" /agregar/{{$encuesta->id}}" method="POST">
                        @csrf 
                            <input id="pregunta" type="text"name="pregunta" value="{{ $row->id }}" required style="display:none">
                            <button type="submit" class="btn btn-success ">Agregar </button>
                        </form>
                    </td>
                    </tr>
                    @elseif(Auth::user()->concesion=='Super Admin'&& $row->status=='ACTIVA')
                    <tr>
                    <td>{{$row->pregunta}}</td>
                    <td>{{$row->tipo}}</td>
                    <td class="emoji-table">{{$row->emoji}}</td>
                    <td>
                        <form action=" /agregar/{{$encuesta->id}}" method="POST">
                        @csrf 
                            <input id="pregunta" type="text"name="pregunta" value="{{ $row->id }}" required style="display:none">
                            <button type="submit" class="btn btn-success ">Agregar </button>
                        </form>
                    </td>
                    </tr>
                    @endif
                @endforeach
              </tbody>
            </table>
            </div>

        </div>
        <div class="row-9">

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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('agregado')=='ok')
<script>
Swal.fire({
  position: 'center',
  icon: 'success',
  title: 'Pregunta asignada correctamente',
  showConfirmButton: false,
  timer: 1500
})
</script>
@elseif(session('solo10')=='ok')
<script>
    Swal.fire(
      'No Asignada!',
      'La Encuesta solo puede tener 10 preguntas.',
      'error'
    )
</script>
@elseif(session('yaAsignada')=='ok')
<script>
    Swal.fire(
      'No Asignada!',
      'La pregunta ya se encuenta asignada a esta encuesta.',
      'error'
    )
</script>
@elseif(session('noAgregado')=='ok')
<script>
    Swal.fire(
      'No Asignada!',
      'Ya no se pueden agregar preguntas a esta encuesta por que ya ha sido contestada.',
      'error'
    )
</script>

@elseif(session('Removido')=='ok')
<script>
    Swal.fire(
      'Removida!',
      'La pregunta ha sido removida con éxito.',
      'success'
    )
</script>

@elseif(session('noRemovido')=='ok')
<script>
    Swal.fire(
      'No Removida!',
      'No se pueden remover preguntas ya que existen encuestas contestasdas.',
      'error'
    )
</script>
@endif

<script>
    $(document).ready(function() {
    $('#example0').DataTable({
        "lengthMenu":[[5],[5]],
        "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
    });
} );
$(document).ready(function() {
    $('#example1').DataTable({
        "lengthMenu":[[5],[5]]
        ,        "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
    });
} );
</script>
@stop

<!--agregamos Java Script-->