
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
            <div id="misEnc" class="col text-center bg-navy ml-1 mr-1" >
                <a href="/home" class="btn">Mis Encuestas</a> 
            </div>
            @can('Administrador de Encuestas')
            <div class="col text-center text-navy ">
                <a href="/preguntas" class="btn"> Mis Preguntas</a>
            </div>
            @endcan

            @can('AdministradorN1')
            <div class="col text-center bg-navy ">
                <a href="/usuarios" class="btn">Administración</a>
            </div>
            @endcan
        </div>
        <div class="row">
            @can('Administrador de Encuestas')
            <div class="col-3">
                <a  class="btn btn-primary mt-3" type="button" data-toggle="modal" data-target="#CreatePre">Agregar Pregunta</a>
            </div>
            @endcan
        </div>
        <br>
        <div class="row-9">
            <table id="example2" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Pregunta</th>
                        <th>¿Quién puede verme?</th>
                        <th>Tipo</th>
                        <th>Emoji</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $pregunta as $row)
                        @if(Auth::user()->id == 1)
                            @if($row->status === 'ACTIVA')
                            <tr>
                                <td>{{$row->pregunta}}</td>
                                <td>{{$row->concesion}}</td>
                                <td>{{$row->tipo}}</td>
                                <td>{{$row->emoji}}</td>
                                <td>{{$row->status}}</td>
                                <td>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                            @else
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                            @endif
                                            <hr>
                                            <form action="/statusP/{{$row->id}}" method="post">
                                                @csrf
                                                <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                @if($row->status === 'ACTIVA')
                                                    <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                @elseif($row->status ==='DESACTIVADA')
                                                    <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                @endif
                                            </form>
                                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                            <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @elseif($row->status ==='DESACTIVADA')
                            <tr>
                                <td class="danger">{{$row->pregunta}}</td>
                                <td class="danger">{{$row->concesion}}</td>
                                <td class="danger">{{$row->tipo}}</td>
                                <td class="danger">{{$row->emoji}}</td>
                                <td class="danger">{{$row->status}}</td>
                                <td class="danger">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                            @else
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                            @endif
                                            <hr>
                                            <form action="/statusP/{{$row->id}}" method="post">
                                                @csrf
                                                <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                @if($row->status === 'ACTIVA')
                                                    <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                @elseif($row->status ==='DESACTIVADA')
                                                    <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                @endif
                                            </form>
                                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                            <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @elseif(Auth::user()->id != 1 && Auth::user()->concesion==$row->concesion ||$row->concesion == 'Grupo Huerta')
                            @if($row->status === 'ACTIVA')
                            <tr>
                                <td>{{$row->pregunta}}</td>
                                <td>{{$row->concesion}}</td>
                                <td>{{$row->tipo}}</td>
                                <td>{{$row->emoji}}</td>
                                <td>{{$row->status}}</td>
                                <td>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                         </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                            @else
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                            @endif
                                            <hr>
                                            <form action="statusP/{{$row->id}}" method="post">
                                                @csrf
                                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                @if($row->status === 'ACTIVA')
                                                    <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                @elseif($row->status ==='DESACTIVADA')
                                                    <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                @endif
                                            </form>
                                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                            <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @elseif($row->status ==='DESACTIVADA')
                                <tr>
                                    <td class="danger">{{$row->pregunta}}</td>
                                    <td class="danger">{{$row->concesion}}</td>
                                    <td class="danger">{{$row->tipo}}</td>
                                    <td class="danger">{{$row->emoji}}</td>
                                    <td class="danger">{{$row->status}}</td>
                                    <td class="danger">
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Acciones
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                                <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                                @else
                                                <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                                @endif
                                                <hr>
                                                <form action="statusP/{{$row->id}}" method="post">
                                                    @csrf
                                                        <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                    @if($row->status === 'ACTIVA')
                                                        <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                        <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                    @elseif($row->status ==='DESACTIVADA')
                                                        <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                        <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                    @endif
                                                </form>
                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                                <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endif
                            <!-- Modal editar pregunta --> 
                            <div class="modal fade" id="Editar{{$row->id}}" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header bg-navy">
                                            <h4 class="modal-title"> {{__('Editar Encuesta') }}</h4>
                                            <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                        <form method="POST" action="/preguntas/{{$row->id}}" class="formulario-Actualizar">
                                            @csrf
                                            @method('PUT') 
                                            <div class="row mb-3">
                                                <label for="emoji" class="col-md-2 col-form-label text-md-end">Emoji</label><br>
                                                <div class="col-md-6">
                                                    <select name="emoji" id="emoji" class="form-select form-select-lg  mb-1 swal2-select swal2-input bg-light">
                                                        <option value="{{$row->emoji}}">--{{$row->emoji}}--</option>
                                                        <option value="Si">Si</option>
                                                        <option value="No">No</option>    
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="tipo" class="col-md-2 col-form-label text-md-end">Tipo de pregunta</label><br>
                                                <div class="col-md-6">
                                                    <select name="tipo" id="tipo" class="form-select form-select-lg  mb-1 swal2-select swal2-input bg-light">
                                                        <option value="{{$row->tipo}}">--{{$row->tipo}}--</option>
                                                        <option value="Radio Button">Radio Button</option>
                                                        <option value="CheckBox">CheckBox</option>    
                                                        <option value="Seleccion">Selección</option>
                                                        <option value="Texto">Texto</option>
                                                        <option value="Estrellas">Estrellas</option>
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                                                    <label for="concesion" class="col-md-2 col-form-label text-md-end"> Concesión <span class="m-2"></span></label><br>
                                                    <div class="col-md-6">
                                                        <select name="concesion" id="concesion" class="form-select form-select-lg mb-1 swal2-select swal2-input bg-light" required>
                                                            <option value="">--Selecciona una Concesión--</option>
                                                            <option value="Automotriz1">Automotriz1</option>
                                                            <option value="Automotriz2">Automotriz2</option>
                                                            <option value="Automotriz3">Automotriz3</option>
                                                            <option value="Automotriz4">Automotriz4</option>
                                                            <option value="Automotriz5">Automotriz5</option>
                                                            <option value="Automotriz6">Automotriz6</option>
                                                            <option value="Automotriz7">Automotriz7</option>
                                                        </select>

                                                    </div>
                                                    @elseif(Auth::user()->concesion == 'Automotriz1')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz3')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz4')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz5')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz6')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @elseif(Auth::user()->concesion == 'Automotriz7')
                                                        <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="pregunta" class="col-md-2 ml-3  mr-2 col-form-label text-md-end">Pregunta</label>
                                                <div class="col-md-6">
                                                    <input id="pregunta" type="text" class="form-control @error('pregunta') is-invalid @enderror ml-4" name="pregunta" value="{{ $row->pregunta }}" required autocomplete="pregunta" autofocus>
                                                    @error('pregunta')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 offset-md-4">
                                                    <button type="submit" class="btn btn-primary" >Aceptar</button>
                                                    <a href="/preguntas" class="btn btn-secondary">cancelar</a>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </tbody>
            </table>
            <div class="cards">
                @foreach( $pregunta as $row)
                    @if(Auth::user()->id == 1)
                        @if($row->status === 'ACTIVA')
                            <div class="card">
                                <div class="card-header bg-navy">
                                    <label for="">{{$row->pregunta}}</label>
                                </div>
                                <div class="card-body">
                                    <label>Concesión: <span style="font-weight:normal; border-radius:2px;">Concesion: {{$row->concesion}}</span></td><br>
                                    <label>Tipo: <span style="font-weight:normal; border-radius:2px;"> {{$row->tipo}}</span></td><br>
                                    <label>Emoji: <span style="font-weight:normal; border-radius:2px;">{{$row->emoji}}</span></td><br>
                                    <label>Estatus: <span class="bg-success text-white" style="padding:3px; font-weight:normal; border-radius:2px;">{{$row->status}}</span></td><br>
                                </div>
                                <div class="card-footer">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                            @else
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                            @endif
                                            <hr>
                                            <form action="/statusP/{{$row->id}}" method="post">
                                                @csrf
                                                <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                @if($row->status === 'ACTIVA')
                                                    <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                @elseif($row->status ==='DESACTIVADA')
                                                    <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                @endif
                                            </form>
                                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                            <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($row->status === 'DESACTIVADA')
                        <div class="card">
                                <div class="card-header bg-navy">
                                    <label for="">{{$row->pregunta}}</label>
                                </div>
                                <div class="card-body">
                                    <label>Concesión: <span style="font-weight:normal; border-radius:2px;">Concesion: {{$row->concesion}}</span></td><br>
                                    <label>Tipo: <span style="font-weight:normal; border-radius:2px;"> {{$row->tipo}}</span></td><br>
                                    <label>Emoji: <span style="font-weight:normal; border-radius:2px;">{{$row->emoji}}</span></td><br>
                                    <label>Estatus: <span class="bg-danger text-white" style="padding:3px; font-weight:normal; border-radius:2px;">{{$row->status}}</span></td><br>
                                </div>
                                <div class="card-footer">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if($row->tipo==='Texto'||$row->tipo==='Estrellas')
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}" style="display:none"><i class="bi bi-plus-lg " ></i>Agregar Respuesta</a>
                                            @else
                                            <a class="dropdown-item" href="/respuestas/{{$row->id}}"><i class="bi bi-plus-lg text-primary" ></i>Agregar Respuesta</a>
                                            @endif
                                            <hr>
                                            <form action="/statusP/{{$row->id}}" method="post">
                                                @csrf
                                                <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                @if($row->status === 'ACTIVA')
                                                    <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                @elseif($row->status ==='DESACTIVADA')
                                                    <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                    <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                @endif
                                            </form>
                                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                            <form action="{{route ('preguntas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>

<!-- Modal crear pregunta -->
<div class="modal fade" id="CreatePre" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-navy">
          <h4 class="modal-title"> {{__('Nueva Pregunta') }}</h4>
          <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="/preguntas" class="formulario-crear">
                  @csrf
                  <input id="pregunta" type="text"  name="status" value="ACTIVA" required  style="display:none">

                  <div class="row mb-3">
                        <label for="emoji" class="col-md-2 col-form-label text-md-end">Emoji</label><br>
                        <div class="col-md-6">
                            <select name="emoji" id="emoji" class="form-select form-select-lg  mb-1 swal2-select swal2-input bg-light" required>
                                <option value="">--Selecciona una Opcion--</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>    
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tipo" class="col-md-2 col-form-label text-md-end">Tipo de pregunta</label><br>
                        <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-select form-select-lg  mb-1 swal2-select swal2-input bg-light" required>
                                    <option value="">--Selecciona un Tipo--</option>
                                    <option value="Radio Button">Radio Button</option>
                                    <option value="CheckBox">CheckBox</option>    
                                    <option value="Seleccion">Selección</option>
                                    <option value="Texto">Texto</option>
                                    <option value="Estrellas">Estrellas</option>
                                </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                            
                                @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                                    <label for="concesion" class="col-md-2 col-form-label text-md-end"> Concesión <span class="m-2"></span></label><br>
                                    <div class="col-md-6">
                                        <select name="concesion" id="concesion" class="form-select form-select-lg mb-1 swal2-select swal2-input bg-light" required>
                                            <option value="">--Selecciona una Concesión--</option>
                                            <option value="Automotriz1">Automotriz1</option>
                                            <option value="Automotriz2">Automotriz2</option>
                                            <option value="Automotriz3">Automotriz3</option>
                                            <option value="Automotriz4">Automotriz4</option>
                                            <option value="Automotriz5">Automotriz5</option>
                                            <option value="Automotriz6">Automotriz6</option>
                                            <option value="Automotriz7">Automotriz7</option>

                                        </select>
                                    </div>
                                @elseif(Auth::user()->concesion == 'Automotriz1')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz2')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz3')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz4')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz5')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz6')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @elseif(Auth::user()->concesion == 'Automotriz7')
                                    <input type="hidden" name="concesion" value="{{Auth::user()->concesion}}">
                                @endif


                    </div>
                    <div class="row mb-3">
                      <label for="pregunta" class="col-md-2  mr-1 col-form-label text-md-end">Pregunta</label>
                      <div class="col-md-6">
                                <input id="pregunta" type="text" class="form-control @error('pregunta') is-invalid @enderror ml-4" name="pregunta" value="{{ old('pregunta') }}" required autocomplete="pregunta" autofocus>

                                @error('pregunta')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                            <a href="/preguntas" class="btn btn-secondary">cancelar</a>
                        </div>
                    </div>
                </form> 
        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        ul{
            list-style: none;
        }
        .danger{
            background-color:#FFD2D2;
            font-weight:bold;
        }
        .success{
            background-color:#BCFEE3;
            font-weight:bold;
        }
        th{
            text-align: center;
        }
        td{
            text-align: center;
            padding:5px 10px !important;
        }

    </style>
@stop
<!--agregamos css-->

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.formulario-eliminar').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: '¿Estas seguro?',
                text: "¡Esta pregunta se va eliminar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'cancelar',
                confirmButtonText: 'Si, ¡eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    /**/
                    this.submit();
                }
            })
        })
    </script>
    <script>
        $('.formulario-Actualizar').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: '¿Estas seguro?',
                text: "¡Esta pregunta se va modificar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'cancelar',
                confirmButtonText: 'Si, ¡modificar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    /**/
                    this.submit();
                }
            })
        })
    </script>
    @if(session('eliminar')=='ok')
    <script>
        Swal.fire(
            '¡Eliminado!',
            'La pregunta ha sido eliminada.',
            'success'
        )
    </script>
    @elseif(session('Noeliminar')=='ok')
    <script>
        Swal.fire(
            '¡No eliminada!',
            'La pregunta tiene respuestas asignadas.',
            'error'
        )
    </script>
    @elseif(session('Noeliminar1')=='ok')
    <script>
        Swal.fire(
            '¡No eliminada!',
            'La pregunta esta asignada a una encuesta.',
            'error'
        )
    </script>
    @elseif(session('crear')=='ok')
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '¡creada correctamente!',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    @elseif(session('Nocrear')=='ok')
    <script>
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: '¡la pregunta ya existe!',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#example2').DataTable({
                "lengthMenu":[[5,8],[5,8]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            });
        });
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert1").fadeOut(1500);
            },3000);
        });
    </script>
@stop

<!--agregamos Java Script-->