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
    <!--menu superior encuestas,preguntas administracion-->
    <div id="menus" class="row border rounded-8">
        <div id="misEnc" class="col text-center bg-navy ml-1 mr-1">
            <a href="/home" class="btn">Mis Encuestas</a>
        </div>
        @can('Administrador de Encuestas')
        <div id="misPreg" class="col text-center bg-navy ml-1 mr-1">
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
        <div class="col-6">
            <a class="btn btn-primary mt-3" type="button" data-toggle="modal" data-target="#create">Agregar Encuesta</a>
        </div>
        @endcan
    </div>
    <div class="row-9">
        <table id="example" class="table table-striped">
            <thead>
                <th>Nombre</th>
                <th>Departamento</th>
                <th>Concesión</th>
                <th>Estado</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                @foreach($encuesta as $row)
                <!--SI EL USUARIO AUTENTICADO ES DE LA MISMA CONCESION A LA ENCUESTA , O SI EL USUARIO AUTENTICADO ES DE Automotriz7 O EL USUARIO AUTENTICADO ES EL PRIMER REGISTRADO SE MUESTRA LA ENCUESTA  SI LA ENCUESTA ES DE Automotriz7 SE DEBE MOSTRAR-->
                @if(Auth::user()->concesion == $row->concesion || Auth::user()->concesion == 'Automotriz7'|| Auth::user()->id == 1 || $row->concesion == 'Automotriz7')
                <tr>
                    @if($row->status === 'DESACTIVADA')
                    <td class="danger">{{$row->Nombre}}</td>
                    <td class="danger">{{$row->Departamento}}</td>
                    <td class="danger">{{$row->concesion}}</td>
                    <td class="danger">{{$row->status}}</td>
                    <td>
                        <div class="dropdown show">
                            <!--boton de lista de acciones-->
                            <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                                Acciones
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <!--lista de acciones-->
                                @can('Administrador de Encuestas')
                                <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                <hr>
                                @endcan
                                @can('Administrador de Encuestas')
                                <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                                <form action="{{ route('encuestas.status', $row->id) }}" method="post">
                                    @csrf
                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                    @if($row->status === 'ACTIVA')
                                        <input type="hidden" name="status" value="DESACTIVADA">
                                        <button class="dropdown-item" type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                    @elseif($row->status === 'DESACTIVADA')
                                        <input type="hidden" name="status" value="ACTIVA">
                                        <button class="dropdown-item" type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                    @endif
                                </form>
                                <!--Botón para los Editar las encuestas -->
                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                </form>
                                <hr>
                                @endcan
                                @can('Gerencial')
                                <!--solo se muestra a Administrador de encuestas, administradores, super usuarios -->
                                <form action="resumen/{{$row->id}}" method="post">
                                    @csrf
                                    @if($row->concesion == 'Automotriz1')
                                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz2')
                                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz4')
                                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz5')
                                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz6')
                                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz7')
                                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                                    @endif

                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                </form>
                                @endcan
                                @can('GerencialN1')
                                <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                @if($row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Super Admin' ||$row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Automotriz7')
                                <!--si la encuestas es de Automotriz7 y el ususario autenticado es Super Administrador o la enuestas es de Automotriz7 y el usuario esde Automotriz7 -->
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#contestarAqui{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                                @else
                                <!--si la encuesta es de otra concesionaria -->
                                <form action="verificado/{{$row->id}}" method="post">
                                    @csrf
                                    @if(Auth::user()->concesion == 'Automotriz1')
                                    <!--si la encuesta es de Automotriz1 se envia esta concexion -->
                                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                    <!--si la encuesta es de Automotriz2 se envia esta concexion -->
                                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                    <!--si la encuesta es de  Lindavista se envia esta concexion -->
                                    <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz3')
                                    <!--si la encuesta es de Perinorte se envia esta concexion -->
                                    <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz4')
                                    <!--si la encuesta es de Norte se envia esta concexion -->
                                    <input type="text" value="Norte" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz5')
                                    <!--si la encuesta es de Motors se envia esta concexion -->
                                    <input type="text" value="Motors" name="conexionDB" style="display:none">
                                    @endif
                                    <button type="submit" class="dropdown-item"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                                </form>
                                @endif

                                @if($row->concesion == 'Automotriz1'||Auth::user()->concesion == 'Automotriz1')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz1('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz2'||Auth::user()->concesion == 'Automotriz2')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz2('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz3'||Auth::user()->concesion == 'Automotriz3')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz3('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz4'||Auth::user()->concesion == 'Automotriz4')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz4('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz5'||Auth::user()->concesion == 'Automotriz5')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz5('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz6'||Auth::user()->concesion == 'Automotriz6')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz6('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz7'||Auth::user()->concesion == 'Automotriz7')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz7('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @endif
                                <hr>
                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                @endcan
                            </div>
                        </div>
                    </td>
                    @elseif($row->status === 'ACTIVA')
                    <td>{{$row->Nombre}}</td>
                    <td>{{$row->Departamento}}</td>
                    <td>{{$row->concesion}}</td>
                    <td>{{$row->status}}</td>
                    <td>
                        <div class="dropdown show">
                            <!--boton de lista de acciones-->
                            <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                                Acciones
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <!--lista de acciones-->
                                @can('Administrador de Encuestas')
                                <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                <hr>
                                @endcan
                                @can('Administrador de Encuestas')
                                <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                                <form action="{{ route('encuestas.status', $row->id) }}" method="post">
                                    @csrf
                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                    @if($row->status === 'ACTIVA')
                                        <input type="hidden" name="status" value="DESACTIVADA">
                                        <button class="dropdown-item" type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                    @elseif($row->status === 'DESACTIVADA')
                                        <input type="hidden" name="status" value="ACTIVA">
                                        <button class="dropdown-item" type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                    @endif
                                </form>
                                <!--Botón para los Editar las encuestas -->
                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                </form>
                                <hr>
                                @endcan
                                @can('Gerencial')
                                <!--solo se muestra a Administrador de encuestas, administradores, super usuarios -->
                                <form action="resumen/{{$row->id}}" method="post">
                                    @csrf
                                    @if($row->concesion == 'Automotriz1')
                                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz2')
                                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz4')
                                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz5')
                                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz6')
                                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                                    @elseif($row->concesion== 'Automotriz7')
                                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                                    @endif

                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                </form>
                                @endcan
                                @can('GerencialN1')
                                <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                @if($row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Super Admin' ||$row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Automotriz7')
                                <!--si la encuestas es de Automotriz7 y el ususario autenticado es Super Administrador o la enuestas es de Automotriz7 y el usuario esde Automotriz7 -->
                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#contestarAqui{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                                @else
                                <!--si la encuesta es de otra concesionaria -->
                                <form action="verificado/{{$row->id}}" method="post">
                                    @csrf
                                    @if(Auth::user()->concesion == 'Automotriz1')
                                    <!--si la encuesta es de Automotriz1 se envia esta concexion -->
                                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                    <!--si la encuesta es de Automotriz2 se envia esta concexion -->
                                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                    <!--si la encuesta es de  Lindavista se envia esta concexion -->
                                    <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz3')
                                    <!--si la encuesta es de Perinorte se envia esta concexion -->
                                    <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz4')
                                    <!--si la encuesta es de Norte se envia esta concexion -->
                                    <input type="text" value="Norte" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion == 'Automotriz5')
                                    <!--si la encuesta es de Motors se envia esta concexion -->
                                    <input type="text" value="Motors" name="conexionDB" style="display:none">
                                    @endif
                                    <button type="submit" class="dropdown-item"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                                </form>
                                @endif

                                @if($row->concesion == 'Automotriz1'||Auth::user()->concesion == 'Automotriz1')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz1('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz2'||Auth::user()->concesion == 'Automotriz2')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz2('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz3'||Auth::user()->concesion == 'Automotriz3')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz3('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz4'||Auth::user()->concesion == 'Automotriz4')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz4('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz5'||Auth::user()->concesion == 'Automotriz5')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz5('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz6'||Auth::user()->concesion == 'Automotriz6')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz6('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @elseif($row->concesion == 'Automotriz7'||Auth::user()->concesion == 'Automotriz7')
                                <a class="dropdown-item" onclick="javascript:getlinkAutomotriz7('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                @endif
                                <hr>
                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                @endcan
                            </div>
                        </div>
                    </td>
                    @endif
                </tr>
                <!-- Modal envio de email -->
                <div class="modal fade" id="enviar{{$row->id}}" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-navy">
                                <h4 class="modal-title"> {{$row->Nombre}}</h4>
                                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info" role="alert">
                                    ¡Enviar encuesta por correo electronico!
                                </div>
                                <form action="/enviar" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="orden" class="form-label">No.Orden</label>
                                        <input type="text" class="form-control" id="orden" name="orden" placeholder="Ejemplo: OE0000000002" required>
                                        <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                    </div>
                                    @if(Auth::user()->concesion == 'Automotriz1'||$row->concesion == 'Automotriz1'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion == 'Automotriz1')
                                    <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz2'||$row->concesion =='Automotriz2'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz2')
                                    <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz3'||$row->concesion == 'Automotriz3'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz3')
                                    <input type="text" value="Automotriz3" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz4'||$row->concesion == 'Automotriz4'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz4')
                                    <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz5'||$row->concesion == 'Automotriz5'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz5')
                                    <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz6'||$row->concesion == 'Automotriz6'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz6')
                                    <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                                    @elseif(Auth::user()->concesion== 'Automotriz7'||$row->concesion == 'Automotriz7'|| $row->concesion=='Automotriz7'&& Auth::user()->concesion== 'Automotriz7')
                                    <input type="text" value="Automotriz7" name="conexionDB" style="display:none">

                                    @elseif($row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Super Admin' ||$row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Automotriz7')
                                        <div class="col">
                                            <label for="">Seleccionar una concesion</label>

                                            <p style="font-size:.8rem;">selecciona la concesión con la cual se hará la consulta de número de orden</p>
                                            <select name="conexionDB" id="concexionDB" class="form-control">
                                                <option value="" selected disabled hidden>--seleccione una opción--</option>
                                                <option value="Automotriz1">Automotriz1</option>
                                                <option value="Automotriz2">Automotriz2</option>
                                                <option value="Automotriz3">Automotriz3</option>
                                                <option value="Automotriz4">Automotriz4</option>
                                                <option value="Automotriz5">Automotriz5</option>
                                                <option value="Automotriz6">Automotriz6</option>
                                                <option value="Automotriz7">Automotriz7</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal editar encuesta -->
                <div class="modal fade" id="Editar{{$row->id}}" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-navy">
                                <h4 class="modal-title"> {{__('Editar Encuesta') }}</h4>
                                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/encuestas/{{$row->id}}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label for="Nombre" class="form-label">Nombre de la encuesta</label>
                                        <div class="col-md-6">
                                            <input id="Nombre" type="text" class="form-control @error('Nombre') is-invalid @enderror" name="Nombre" onkeyup="javascript:this.value=this.value.toUpperCase();" value="{{$row->Nombre}}" required autocomplete="Nombre" autofocus>
                                            @error('Nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="Departamento" class="form-label mr-4">Departamento</label><br>
                                        <div class="col-md-6">
                                            <select name="Departamento" id="Departamento" class="swal2-select swal2-input bg-light">
                                                <option value="Venta">Venta</option>
                                                <option value="Call Center">Call Center</option>
                                                <option value="Post Venta">Post Venta</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="Concesion" class="form-label mr-4"> Concesión <span class="m-2"></span></label><br>
                                        <div class="col-md-6">
                                            <select name="Concesion" id="Concesion" class="form-select form-select-lg ml-5 mb-3 swal2-select swal2-input bg-light" required>
                                                @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                                                <option value="{{$row->concesion}}">--{{$row->concesion}}--</option>
                                                <option value="Automotriz1">Automotriz1</option>
                                                <option value="Automotriz2">Automotriz2</option>
                                                <option value="Automotriz3">Automotriz3</option>
                                                <option value="Automotriz4">Automotriz4</option>
                                                <option value="Automotriz5">Automotriz5</option>
                                                <option value="Automotriz6">Automotriz6</option>
                                                <option value="Automotriz7">Automotriz7</option>
                                                @elseif(Auth::user()->concesion == 'Automotriz7')
                                                <input type="text" name="Concesion" value="Automotriz7" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz1')
                                                <input type="text" name="Concesion" value="Automotriz1" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz2')
                                                <input type="text" name="Concesion" value="Automotriz2" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz3')
                                                <input type="text" name="Concesion" value="Automotriz3" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz4')
                                                <input type="text" name="Concesion" value="Automotriz4" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz5')
                                                <input type="text" name="Concesion" value="Automotriz5" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz6')
                                                <input type="text" name="Concesion" value="Automotriz6" style="display:none">
                                                @elseif(Auth::user()->concesion == 'Automotriz7')
                                                <input type="text" name="Concesion" value="Automotriz7" style="display:none">
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-6 offset-md-4 d-flex">
                                            <button type="submit" class="btn btn-primary mr-4">Aceptar</button>
                                            <a href="/encuestas" class="btn btn-secondary">cancelar</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal CONTESTAR AQUI -->
                <div class="modal fade" id="contestarAqui{{$row->id}}" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-navy">
                                <h4 class="modal-title"> {{$row->Nombre}}</h4>
                                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form action="verificado/{{$row->id}}" method="post">
                                    @csrf
                                    <div class="col">
                                        <label for="">Seleccionar una concesion</label>
                                        <p>selecciona la concesión con la cual se hará la consulta de número de orden</p>
                                        <select name="Conexion" id="Conexion" class="form-select form-select-lg  mb-3 swal2-select swal2-input bg-light" required>
                                            <option value="" selected disabled hidden>--seleccione una opción--</option>
                                            <option value="Automotriz1">Automotriz1</option>
                                            <option value="Automotriz2">Automotriz2</option>
                                            <option value="Automotriz3">Automotriz3</option>
                                            <option value="Automotriz4">Automotriz4</option>
                                            <option value="Automotriz5">Automotriz5</option>
                                            <option value="Automotriz6">Automotriz6</option>
                                            <option value="Automotriz7">Automotriz7</option>

                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Contestar</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endif
                @endforeach
            </tbody>
        </table>
        <!--agregamos las tarjetas para diseño responsive-->
        <div class="cards">
            @foreach( $encuesta as $row)
            @if(Auth::user()->concesion == $row->concesion || Auth::user()->concesion == 'Automotriz7'|| Auth::user()->id ==1|| $row->concesion == 'Automotriz7')
            <div class="card">
                <div class="card-header bg-navy">
                    <label for="">{{$row->Nombre}}</label>
                </div>
                <div class="card-body">
                    <label>Departamento: <span style="font-weight: normal; border-radius:2px;">{{$row->Departamento}}</span></label><br>
                    <label>Concesión: <span style="font-weight: normal; border-radius:2px;">{{$row->concesion}}</span></label><br>
                    @if($row->status === 'ACTIVA')
                    <label>Estatus: <span class="bg-success text-white" style="padding:3px;font-weight: normal; border-radius:2px;">{{$row->status}}</span></label><br>
                    @elseif($row->status ==='DESACTIVADA')
                    <label>Estatus: <span class="bg-danger text-white" style="padding:3px;font-weight: normal; border-radius:2px;">{{$row->status}}</span></label><br>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="dropdown show">
                        <!--boton de lista de acciones-->
                        <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                            Acciones
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <!--lista de acciones-->
                            @can('Administrador de Encuestas')
                            <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                            <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                            <hr>
                            @endcan
                            @can('Administrador de Encuestas')
                            <!--solo se muestra a Administrador de encuestas, administradores y super usuarios -->
                            <form action="{{ route('encuestas.status', $row->id) }}" method="post">
                                @csrf
                                <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                @if($row->status === 'ACTIVA')
                                    <input type="hidden" name="status" value="DESACTIVADA">
                                    <button class="dropdown-item" type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                @elseif($row->status === 'DESACTIVADA')
                                    <input type="hidden" name="status" value="ACTIVA">
                                    <button class="dropdown-item" type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                @endif
                            </form>
                            <!--Botón para los Editar las encuestas -->
                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                            <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST" class="formulario-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                            </form>
                            <hr>
                            @endcan
                            @can('Gerencial')
                            <!--solo se muestra a Administrador de encuestas, administradores, super usuarios -->
                            <form action="resumen/{{$row->id}}" method="post">
                                @csrf
                                @if($row->concesion == 'Automotriz1')
                                <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz2')
                                <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz3')
                                <input type="text" value="Automotriz3" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz4')
                                <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz5')
                                <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz6')
                                <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                                @elseif($row->concesion== 'Automotriz7')
                                <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                                @endif
                                <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                            </form>
                            @endcan
                            @can('GerencialN1')
                            <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                            @if($row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Super Admin' ||$row->concesion == 'Automotriz7' && Auth::user()->concesion == 'Automotriz7')

                            <button class="dropdown-item" type="button" data-toggle="modal" data-target="#contestarAqui{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                            @else
                            <!--si la encuesta es de otra concesionaria -->
                            <form action="verificado/{{$row->id}}" method="post">
                                @csrf
                                @if(Auth::user()->concesion == 'Automotriz1')
                                <input type="text" value="Automotriz1" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz2')
                                <input type="text" value="Automotriz2" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz3')
                                <input type="text" value="Automotriz3" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz4')
                                <input type="text" value="Automotriz4" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz5')
                                <input type="text" value="Automotriz5" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz6')
                                <input type="text" value="Automotriz6" name="conexionDB" style="display:none">
                                @elseif(Auth::user()->concesion == 'Automotriz7')
                                <input type="text" value="Automotriz7" name="conexionDB" style="display:none">
                                @endif

                                <button type="submit" class="dropdown-item"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</button>
                            </form>
                            @endif

                            @if($row->concesion == 'Automotriz1')
                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz1('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                            @elseif($row->concesion == 'Automotriz2')

                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz2('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                            @elseif($row->concesion == 'Automotriz3')
                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz3('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>


                            @elseif($row->concesion == 'Automotriz4')

                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz4('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                            @elseif($row->concesion == 'Automotriz5')

                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz5('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>


                            @elseif($row->concesion == 'Automotriz6')

                            <a class="dropdown-item" onclick="javascript:getlinkAutomotriz6('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                           
                            @endif
                            <hr>

                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
<!-- Modal Crear encuesta -->
<div class="modal fade" id="Create" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h4 class="modal-title"> {{__('Nueva Encuesta') }}</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/encuestas" class="formulario-crear">
                    @csrf
                    <input id="status" type="text" name="status" value="ACTIVA" required style="display:none">
                    <div class="row mb-3">
                        <label for="Nombre" class="form-label">Nombre de la encuesta</label>
                        <div class="col-md-6">
                            <input id="Nombre" type="text" class="form-control @error('Nombre') is-invalid @enderror" name="Nombre" value="{{ old('Nombre') }}" onkeyup="javascript:this.value=this.value.toUpperCase();" required autocomplete="Nombre" autofocus>
                            @error('Nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>Z
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="Departamento" class="form-label mr-3">Departamento</label><br>
                        <div class="col-md-6">
                            <select name="Departamento" id="Departamento" class="swal2-select swal2-input bg-light" required>
                                <option value="">--Selecciona un Departamento--</option>
                                <option value="Venta">Venta</option>
                                <option value="Call Center">Call Center</option>
                                <option value="Post Venta">Post Venta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                        <label for="Concesion" class="form-label mr-4"> Concesión <span class="m-2"></span></label>
                        <div class="col-md-6">
                            <select name="Concesion" id="Concesion" class="form-select form-select-lg ml-5 mb-3 swal2-select swal2-input bg-light" required>
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
                        <input type="text" style="display:none" name="Concesion" value="Automotriz1">
                        @elseif(Auth::user()->concesion == 'Automotriz2')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz2">
                        @elseif(Auth::user()->concesion == 'Automotriz3')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz3">
                        @elseif(Auth::user()->concesion == 'Automotriz4')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz4">
                        @elseif(Auth::user()->concesion == 'Automotriz5')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz5">
                        @elseif(Auth::user()->concesion == 'Automotriz6')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz6">
                        @elseif(Auth::user()->concesion == 'Automotriz7')
                        <input type="text" style="display:none" name="Concesion" value="Automotriz7">
                        @endif
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                            <a href="/home" class="btn btn-secondary">cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css">
<style>
</style>
@stop
<!--agregamos css-->
@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>

@if(session('eliminar')=='ok')
<script>
    Swal.fire(
        '¡Eliminado!',
        'Su encuesta ha sido eliminada.',
        'success'
    )
</script>
@elseif(session('nohayEncuestas')=='ok')
<script>
    Swal.fire(
        '¡No existen encuestas contestadas!',
        'se podran visualizar las estadisticas cuando existan encuestas contestadas.',
        'info'
    )
</script>
@elseif(session('Enviada')=='ok')
<script>
    Swal.fire(
        '¡Exito!',
        'La encuesta se envio exitosamente.',
        'success'
    )
</script>
@elseif(session('NoPreguntas')=='ok')
<script>
    Swal.fire(
        '¡No existen preguntas asignadas!',
        'La encuesta no se puede contestar por que no contiene preguntas asignadas.',
        'info'
    )
</script>
@endif
<script>
    $('.formulario-eliminar').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta encuesta se va a eliminar!",
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
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
        });
    });
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert1").fadeOut(1500);
        }, 3000);
    });
</script>
<script>
    function getlinkAutomotriz1($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz1/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoAutomotriz1/' + $id);
    }

    function getlinkAutomotriz2($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz2/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoAutomotriz2/' + $id);
    }


    function getlinkAutomotriz3($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz3/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoAutomotriz3/' + $id);
    }


    function getlinkAutomotriz4($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz4/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoPe   rinorte/' + $id);
    }

    function getlinkAutomotriz5($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz5/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoAutomotriz5/' + $id);

    }

    function getlinkAutomotriz6($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://127.0.0.1:8000/verificadoAutomotriz6/' + $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        alert("URL copiada al portapapeles\n\n" + 'http://127.0.0.1:8000/verificadoAutomotriz6/' + $id);
    }

</script>
@stop