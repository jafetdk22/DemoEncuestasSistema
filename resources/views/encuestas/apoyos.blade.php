
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
        <div id="menus" class="row border rounded-8">
            <div class="col text-center text-navy  bg-navy  ">
                <a href="/home" class="btn">Mis Encuestas</a> 
            </div>
            @can('Administrador de Encuestas')
            <div id="misPreg" class="col text-center bg-navy ml-1 mr-1" >
                <a href="/preguntas" class="btn"> Mis Preguntas</a>
            </div>
            @endcan
            @can('AdministradorN1')
            <div class="col text-center bg-navy ">
                <a href="/usuarios" class="btn">Administración</a>
            </div>
            @endcan
        </div>
        <div class="row ">
            @can('Administrador de Encuestas')
            <div class="col-6">
                <a  class="btn btn-primary mt-3"  type="button" data-toggle="modal" data-target="#create">Agregar Encuesta</a>
            </div>
            @endcan
            <div class="col-6">
            @if($alerta == 0)
            @elseif($alerta == 1)
                <div class="alert alert1 " style="background-color: #cff4fc; border-color: #b6effb; color: #055160;" role="alert">
                    ¡La encuesta se creó correctamente!
                </div>
            @elseif($alerta == 2)
                <div class="alert alert1 " style="background-color:#cfe2ff; border-color: #b6d4fe; color: #084298;" role="alert">
                    ¡La encuesta se editó correctamente!
                </div>
            @elseif($alerta == 3)
                <div class="alert alert1 " style="background-color:#cfe2ff; border-color: #b6d4fe; color: #084298;" role="alert">
                    ¡La encuesta no se puede editar ya que existen ordenes ya contestadas!
                </div>
            @elseif($alerta == 4)
                <div class="alert alert1 " style="background-color:#f8d7da; border-color: #f5c2c7; color: #842029;" role="alert">
                    ¡La pregunta no se puede eliminar ya que existen ordenes ya contestadas!
                </div>
            @elseif($alerta == 5)
                <div class="alert alert1 " style="background-color:#f8d7da; border-color: #f5c2c7; color: #842029;" role="alert">
                    ¡La encuesta ya existe!
                </div>
            @endif
            </div>
        </div>
        <br>
        <div class="row-9">
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Concesión</th>
                        <th>estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $encuesta as $row)
                        @if(Auth::user()->id == 1)
                            @if($row->status === 'DESACTIVADA')
                                <tr>
                                <td class="danger">{{$row->Nombre}}</td>
                                <td class="danger">{{$row->Departamento}}</td>
                                <td class="danger">{{$row->concesion}}</td>
                                <td class="danger">{{$row->status}}</td>
                                <td class="danger">
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @can('Administrador de Encuestas')
                                                <form action="status/{{$row->id}}" method="post">
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
                                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                                <hr>
                                            @endcan
                                            @can('Gerencial')
                                            <form action="resumen/{{$row->id}}" method="post">
                                                @csrf
                                                @if($row->concesion == 'Automotriz Lerma')
                                                    <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol')
                                                    <input type="text" value="divol" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Lindavista')
                                                    <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Perinorte')
                                                    <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Norte')
                                                    <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Pirineos Motors')
                                                    <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                @endif
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                            </form>
                                            @endcan
                                            @can('GerencialN1')
                                            <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                            <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"> <i class="bi bi-link-45deg"></i>Obtener Link</a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @elseif($row->status === 'ACTIVA')
                            <tr>
                                <td>{{$row->Nombre}}</td>
                                <td>{{$row->Departamento}}</td>
                                <td>{{$row->concesion}}</td>
                                <td>{{$row->status}}</td>
                                <td> 
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @can('Administrador de Encuestas')
                                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                                <hr>
                                            @endcan
                                            @can('Administrador de Encuestas')
                                                <form action="status/{{$row->id}}" method="post">
                                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                    @if($row->status === 'ACTIVA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                        </form>
                                                    @elseif($row->status ==='DESACTIVADA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                        </form>
                                                    @endif
                                                </form>

                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                                <hr>
                                            @endcan
                                            @can('Gerencial')
                                                <form action="resumen/{{$row->id}}" method="post">
                                                    @csrf
                                                    @if($row->concesion == 'Automotriz Lerma')
                                                        <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol')
                                                        <input type="text" value="divol" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Lindavista')
                                                        <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Perinorte')
                                                        <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Norte')
                                                        <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Pirineos Motors')
                                                        <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                    @endif
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                                </form>
                                                @endcan
                                                @can('GerencialN1')
                                   
                                            <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                            <a class="dropdown-item" href="verificado/{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</a>
                                            <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                            <hr>
                                            <a class="dropdown-item"  type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
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
                                            <input type="number" name="connection" id="connection" value="{{$row->id}}" style="display:none">
                                        </div>
                                        @if($row->concesion == 'Automotriz Lerma')
                                            <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                        @elseif($row->concesion== 'Divol')
                                            <input type="text" value="divol" name="conexionDB" style="display:none">
                                        @elseif($row->concesion== 'Divol Lindavista')
                                            <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                        @elseif($row->concesion== 'Divol Perinorte')
                                            <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                        @elseif($row->concesion== 'Divol Norte')
                                            <input type="text" value="Norte" name="conexionDB" style="display:none">
                                        @elseif($row->concesion== 'Pirineos Motors')
                                            <input type="text" value="Motors" name="conexionDB" style="display:none">
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
                                    <form method="POST" action="/encuestas/{{$row->id}}" >
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
                                                    @if(Auth::user()->concesion == 'Grupo Huerta' || Auth::user()->concesion == 'Super Admin')
                                                        <option value="{{$row->concesion}}">--{{$row->concesion}}--</option>
                                                        <option value="Grupo Huerta">Grupo Huerta</option>
                                                        <option value="Automotriz Lerma">Automotriz Lerma</option>
                                                        <option value="Divol ">Divol </option>
                                                        <option value="Divol Lindavista">Divol Lindavista</option>
                                                        <option value="Divol Perinorte">Divol Perinorte</option>
                                                        <option value="Divol Norte">Divol Norte</option>
                                                        <option value="Pirineos Motors">Pirineos Motors</option>
                                                    @elseif(Auth::user()->concesion == 'Grupo Huerta')
                                                        <input type="text" name="Concesion" value="Grupo Huerta" style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Automotriz Lerma')
                                                        <input type="text" name="Concesion" value="Automotriz Lerma" style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Divol')
                                                        <input type="text" name="Concesion" value="Divol " style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Divol Lindavista')
                                                        <input type="text" name="Concesion" value="Divol Lindavista" style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Divol Perinorte')
                                                        <input type="text" name="Concesion" value="Divol Perinorte" style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Divol Norte')
                                                        <input type="text" name="Concesion" value="Divol Norte" style="display:none">
                                                    @elseif(Auth::user()->concesion == 'Pirineos Motors')
                                                        <input type="text" name="Concesion" value="Pirineos Motors" style="display:none">
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-0">
                                            <div class="col-md-6 offset-md-4 d-flex">
                                                <button type="submit" class="btn btn-primary mr-4" >Aceptar</button>
                                                <a href="/encuestas" class="btn btn-secondary">cancelar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif(Auth::user()->id != 1)
                        @if(Auth::user()->concesion == $row->concesion)
                            @if($row->status === 'DESACTIVADA')
                                    <tr>
                                        <td class="danger">{{$row->Nombre}}</td>
                                        <td class="danger">{{$row->Departamento}}</td>
                                        <td class="danger">{{$row->concesion}}</td>
                                        <td class="danger">{{$row->status}}</td>
                                        <td class="danger">
                                            <div class="dropdown show">
                                                <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Acciones
                                                </a>
                                                
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                @can('Administrador de Encuestas')
                                                    <form action="status/{{$row->id}}" method="post">
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
                                                    <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                    </form>
                                                    <hr>
                                                @endcan
                                                @can('Gerencial')
                                                    <form action="resumen/{{$row->id}}" method="post">
                                                        @csrf
                                                        @if($row->concesion == 'Automotriz Lerma')
                                                            <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                        @elseif($row->concesion== 'Divol')
                                                            <input type="text" value="divol" name="conexionDB" style="display:none">
                                                        @elseif($row->concesion== 'Divol Lindavista')
                                                            <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                        @elseif($row->concesion== 'Divol Perinorte')
                                                            <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                        @elseif($row->concesion== 'Divol Norte')
                                                            <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                        @elseif($row->concesion== 'Pirineos Motors')
                                                            <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                        @endif
                                                            button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                                    </form>
                                                @endcan
                                                 @can('GerencialN1')

                                                    <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                                    <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"> <i class="bi bi-link-45deg"></i>Obtener Link</a>
                                                @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                               
                            @elseif($row->status === 'ACTIVA')
                                <tr>
                                    <td>{{$row->Nombre}}</td>
                                    <td>{{$row->Departamento}}</td>
                                    <td>{{$row->concesion}}</td>
                                    <td>{{$row->status}}</td>
                                    <td> 
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Acciones
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                @can('Administrador de Encuestas')
                                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                                <hr>

                                                <form action="status/{{$row->id}}" method="post">
                                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                    @if($row->status === 'ACTIVA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                        </form>

                                                    @elseif($row->status ==='DESACTIVADA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                        </form>
                                                    @endif
                                                </form>

                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                                <hr>
                                                @endcan
                                                @can('Gerencial')
                                                <form action="resumen/{{$row->id}}" method="post">
                                                    @csrf
                                                    @if($row->concesion == 'Automotriz Lerma')
                                                        <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol')
                                                        <input type="text" value="divol" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Lindavista')
                                                        <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Perinorte')
                                                        <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Norte')
                                                        <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Pirineos Motors')
                                                        <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                    @endif
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                                </form>
                                                @endcan
                                                @can('GerencialN1')
                                                
                                                <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                                <a class="dropdown-item" href="verificado/{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</a>
                                                <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                                <hr>
                                                <a class="dropdown-item"  type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
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
                                                    <input type="number" name="connection" id="connection" value="{{$row->id}}" style="display:none">
                                                </div>
                                                @if($row->concesion == 'Automotriz Lerma')
                                                    <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol')
                                                    <input type="text" value="divol" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Lindavista')
                                                    <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Perinorte')
                                                    <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Divol Norte')
                                                    <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                @elseif($row->concesion== 'Pirineos Motors')
                                                    <input type="text" value="Motors" name="conexionDB" style="display:none">
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
                                            <form method="POST" action="/encuestas/{{$row->id}}" >
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
                                                <div class="row mb-3">
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
                                                    @if(Auth::user()->concesion == 'Grupo Huerta' || Auth::user()->concesion == 'Super Admin')
                                                        <label for="Concesion" class="form-label mr-4"> Concesión <span class="m-2"></span></label>
                                                        <div class="col">
                                                            <select name="Concesion" id="Concesion" class="form-select form-select-lg  mb-3 swal2-select swal2-input bg-light" required>
                                                                <option value="{{$row->concesion}}">--{{$row->concesion}}--</option>
                                                                <option value="Grupo Huerta">Grupo Huerta</option>
                                                                <option value="Automotriz Lerma">Automotriz Lerma</option>
                                                                <option value="Divol ">Divol </option>
                                                                <option value="Divol Lindavista">Divol Lindavista</option>
                                                                <option value="Divol Perinorte">Divol Perinorte</option>
                                                                <option value="Divol Norte">Divol Norte</option>
                                                                <option value="Pirineos Motors">Pirineos Motors</option>
                                                            </select>
                                                        </div>
                                                    @elseif(Auth::user()->concesion == 'Grupo Huerta')
                                                        <input type="text" name="Concesion" style="display:none" value="Grupo Huerta">
                                                    @elseif(Auth::user()->concesion == 'Automotriz Lerma')
                                                        <input type="text" name="Concesion" style="display:none" value="Automotriz Lerma">
                                                    @elseif(Auth::user()->concesion == 'Divol')
                                                        <input type="text" name="Concesion" style="display:none" value="Divol ">
                                                    @elseif(Auth::user()->concesion == 'Divol Lindavista')
                                                        <input type="text" name="Concesion" style="display:none" value="Divol Lindavista">
                                                    @elseif(Auth::user()->concesion == 'Divol Perinorte')
                                                        <input type="text" name="Concesion" style="display:none" value="Divol Perinorte">
                                                    @elseif(Auth::user()->concesion == 'Divol Norte')
                                                        <input type="text" name="Concesion" style="display:none" value="Divol Norte">
                                                    @elseif(Auth::user()->concesion == 'Pirineos Motors')
                                                        <input type="text" name="Concesion" style="display:none" value="Pirineos Motors">
                                                    @endif
                                                    <div class="row " style="width:100%">
                                                        <div class="col-md-6 offset-md-4 d-flex">
                                                            <button type="submit" class="btn btn-primary mr-3" >Aceptar</button>
                                                            <a href="/encuestas" class="btn btn-secondary">cancelar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        @endif
                    @endif
                    @endforeach
                </tbody>
            </table>
            <div class="cards">
                @foreach( $encuesta as $row)
                    @if(Auth::user()->id == 1)
                        @if($row->status === 'DESACTIVADA')
                            <div class="card ">
                                <div class="card-header bg-navy">
                                    <label for="">{{$row->Nombre}}</label>
                                </div>
                                <div class="card-body">
                                    <label>Departamento: <span style="font-weight: normal; border-radius:2px;">{{$row->Departamento}}</span></label><br>
                                    <label>Concesión: <span style="font-weight: normal; border-radius:2px;">{{$row->concesion}}</span></label><br>
                                    <label>Estatus: <span class="bg-danger text-white" style="padding:3px;font-weight: normal; border-radius:2px;">{{$row->status}}</span></label><br>
                                </div>
                                <div class="card-footer">
                                <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @can('Administrador de Encuestas')
                                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                                <hr>
                                            @endcan
                                            @can('Administrador de Encuestas')
                                                <form action="status/{{$row->id}}" method="post">
                                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                    @if($row->status === 'ACTIVA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                        </form>
                                                    @elseif($row->status ==='DESACTIVADA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                        </form>
                                                    @endif
                                                </form>

                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                                <hr>
                                            @endcan
                                            @can('Gerencial')
                                                <form action="resumen/{{$row->id}}" method="post">
                                                    @csrf
                                                    @if($row->concesion == 'Automotriz Lerma')
                                                        <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol')
                                                        <input type="text" value="divol" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Lindavista')
                                                        <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Perinorte')
                                                        <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Norte')
                                                        <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Pirineos Motors')
                                                        <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                    @endif
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                                </form>
                                                @endcan
                                                @can('GerencialN1')
                                   
                                            <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                            <a class="dropdown-item" href="verificado/{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</a>
                                            <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                            <hr>
                                            <a class="dropdown-item"  type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($row->status === 'ACTIVA')
                        <div class="card">
                                <div class="card-header bg-navy">
                                    <label for="">{{$row->Nombre}}</label>
                                </div>
                                <div class="card-body">
                                    <label>Departamento: <span style="font-weight: normal; border-radius:2px;">{{$row->Departamento}}</span></label><br>
                                    <label>Concesión: <span style="font-weight: normal; border-radius:2px;">{{$row->concesion}}</span></label><br>
                                    <label>Estatus: <span class="bg-success text-white" style="padding:3px;font-weight: normal; border-radius:2px;">{{$row->status}}</span></label><br>
                                </div>
                                <div class="card-footer">
                                <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle btn-sm " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @can('Administrador de Encuestas')
                                                <a class="dropdown-item" href="asignar/{{$row->id}}"><i class="bi bi-plus-lg  text-primary"></i> Asignar Preguntas</a>
                                                <hr>
                                            @endcan
                                            @can('Administrador de Encuestas')
                                                <form action="status/{{$row->id}}" method="post">
                                                    <input type="number" name="encuesta" id="encuesta" value="{{$row->id}}" style="display:none">
                                                    @if($row->status === 'ACTIVA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="DESACTIVADA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-x-circle-fill text-danger"></i> Desactivar</button>
                                                        </form>
                                                    @elseif($row->status ==='DESACTIVADA')
                                                        <form action="status/{{$row->id}}" method="post">
                                                            @csrf
                                                            <input type="text" name="status" id="status" value="ACTIVA" style="display:none">
                                                            <button class="dropdown-item"  type="submit"><i class="bi bi-check-circle-fill text-success"></i> Activar</button>
                                                        </form>
                                                    @endif
                                                </form>

                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square text-info"></i> Editar</a>
                                                <form action="{{route ('encuestas.destroy',$row->id)}}" method="POST"  class="formulario-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-trash3-fill text-danger"></i> Eliminar</button>
                                                </form>
                                                <hr>
                                            @endcan
                                            @can('Gerencial')
                                                <form action="resumen/{{$row->id}}" method="post">
                                                    @csrf
                                                    @if($row->concesion == 'Automotriz Lerma')
                                                        <input type="text" value="Lerma" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol')
                                                        <input type="text" value="divol" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Lindavista')
                                                        <input type="text" value="Lindavista" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Perinorte')
                                                        <input type="text" value="Perinorte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Divol Norte')
                                                        <input type="text" value="Norte" name="conexionDB" style="display:none">
                                                    @elseif($row->concesion== 'Pirineos Motors')
                                                        <input type="text" value="Motors" name="conexionDB" style="display:none">
                                                    @endif
                                                    <button type="submit" class="dropdown-item"><i class="bi bi-bar-chart-fill text-warning"></i> Estadisticas</button>
                                                </form>
                                                @endcan
                                                @can('GerencialN1')
                                   
                                            <a class="dropdown-item" href="encuestas/{{$row->id}}"><i class="bi bi-eye-fill text-primary"></i> Vista Previa</a>
                                            <a class="dropdown-item" href="verificado/{{$row->id}}"><i class="bi bi-arrow-up-right-square-fill"></i> Contestar Aquí</a>
                                            <a class="dropdown-item"  onclick="javascript:getlink('{{$row->id}}');"><i class="bi bi-link-45deg"></i>Obtener Link</a>
                                            <hr>
                                            <a class="dropdown-item"  type="button" data-toggle="modal" data-target="#enviar{{$row->id}}"><i class="bi bi-envelope text-info"></i> Enviar por Correo</a>
                                            @endcan
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
                        <input id="status" type="text"  name="status" value="ACTIVA" required style="display:none">
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
                            @if(Auth::user()->concesion == 'Grupo Huerta' || Auth::user()->concesion == 'Super Admin')
                                <label for="Concesion" class="form-label mr-4"> Concesión <span class="m-2"></span></label>
                                <div class="col-md-6">
                                    <select name="Concesion" id="Concesion" class="form-select form-select-lg ml-5 mb-3 swal2-select swal2-input bg-light" required>
                                        <option value="">--Selecciona una Concesión--</option>
                                        <option value="Grupo Huerta">Grupo Huerta</option>
                                        <option value="Automotriz Lerma">Automotriz Lerma</option>
                                        <option value="Divol ">Divol </option>
                                        <option value="Divol Lindavista">Divol Lindavista</option>
                                        <option value="Divol Perinorte">Divol Perinorte</option>
                                        <option value="Divol Norte">Divol Norte</option>
                                        <option value="Pirineos Motors">Pirineos Motors</option>
                                    </select>  
                                </div>
                            @elseif(Auth::user()->concesion == 'Grupo Huerta')
                                <input type="text" style="display:none" name="Concesion" value="Grupo Huerta">
                            @elseif(Auth::user()->concesion == 'Automotriz Lerma')
                                <input type="text" style="display:none" name="Concesion" value="Automotriz Lerma">
                            @elseif(Auth::user()->concesion == 'Divol')
                                <input type="text" style="display:none" name="Concesion" value="Divol ">
                            @elseif(Auth::user()->concesion == 'Divol Lindavista')
                                <input type="text" style="display:none" name="Concesion" value="Divol Lindavista">
                            @elseif(Auth::user()->concesion == 'Divol Perinorte')
                                <input type="text" style="display:none" name="Concesion" value="Divol Perinorte">
                            @elseif(Auth::user()->concesion == 'Divol Norte')
                                <input type="text" style="display:none" name="Concesion" value="Divol Norte">
                            @elseif(Auth::user()->concesion == 'Pirineos Motors')
                                <input type="text" style="display:none" name="Concesion" value="Pirineos Motors">
                            @endif
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" >Aceptar</button>
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
    $('.formulario-eliminar').submit(function(e){
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
        "lengthMenu":[[10,20,50,100],[10,20,50,100]],
        "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
    });
} );

$(document).ready(function() {
    setTimeout(function() {
        $(".alert1").fadeOut(1500);
    },3000);


});
</script>


<script>
    function getlink($id) {
        var aux = document.createElement("input");
        aux.setAttribute("value", 'http://192.168.2.65:5000/verificado/'+ $id);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        alert("URL copiada al portapapeles\n\n" + 'http://192.168.2.65:5000/verificado/'+ $id);
    }
</script>
@stop

