@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')
@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
    <div class="row">
        <div class="col-3">
            <a href="usuarios/create" class="btn btn-primary" type="button" data-toggle="modal" data-target="#CreateUser">Nuevo Usuario</a>
        </div>
    </div>
    <div class="row-9" style="padding-top:5vh;">
            <table id="example3" class="table table-striped" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Tipo de usuario</th>
                        <th>Concesión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $users as $row)
                    @if( Auth::user()->id== 1)
                    <tr>
                        <td>{{$row->NameUser}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->NameRole}}</td>
                        <td>{{$row->concesion}}</td>
                        <td>
                            <div class="dropdown show">
                                @if( $row->NameRole != 'Super Administrador')
                                    <form action="{{route ('usuarios.destroy',$row->UserId)}}" method="POST"  class="eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @elseif(Auth::user()->id!= 1)
                        @if(Auth::user()->concesion == $row->concesion)
                        <tr>
                            <td>{{$row->NameUser}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->NameRole}}</td>
                            <td>{{$row->concesion}}</td>
                            <td>
                                @if($row->NameRole == 'Administrador')
                                @else
                                <div class="dropdown show">
                                    <form action="{{route ('usuarios.destroy',$row->UserId)}}" method="POST"  class="eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Eliminar</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endif
                    @endforeach
                </tbody>
            </table>
            <div class="cards">
                @foreach( $users as $row)
                    @if( Auth::user()->id== 1)
                        <div class="card">
                            <div class="card-header bg-navy">
                                <label for="">{{$row->NameUser}}</label>
                            </div>
                            <div class="card-body">
                                <label>Correo: <span style="font-weight:normal">{{$row->email}}</span></td><br>
                                <label>Tipo de Usuario: <span style="font-weight:normal">{{$row->NameRole}}</span></td><br>
                                <label>Concesión: <span style="font-weight:normal">{{$row->concesion}}</span></td><br>
                            </div>
                            <div class="card-footer">
                                <div class="dropdown show">
                                    @if( $row->NameRole != 'Super Administrador')
                                        <form action="{{route ('usuarios.destroy',$row->UserId)}}" method="POST"  class="eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->id!= 1)
                        @if(Auth::user()->concesion == $row->concesion)
                            <div class="card">
                                <div class="card-header bg-navy">
                                    <label for="">{{$row->NameUser}}</label>
                                </div>
                                <div class="card-body">
                                    <label>Correo: <span style="font-weight:normal">{{$row->email}}</span></td><br>
                                    <label>Tipo de Usuario: <span style="font-weight:normal">{{$row->NameRole}}</span></td><br>
                                    <label>Concesión: <span style="font-weight:normal">{{$row->concesion}}</span></td><br>
                                </div>
                                <div class="card-footer">
                                    <div class="dropdown show">
                                        @if($row->NameRole == 'Administrador')
                                        @else
                                        <div class="dropdown show">
                                            <form action="{{route ('usuarios.destroy',$row->UserId)}}" method="POST"  class="eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash3-fill"></i> Eliminar</button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!-- Modal crear nuevo usuario -->
    <div class="modal fade" id="CreateUser" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title"> {{ __('Nuevo Usuario') }}</h4>
                    <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/usuarios">
                        @csrf
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre completo') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nombre Completo">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3" >
                            <label for="email" class="col-md-3 col-form-label text-md-end">{{ __('Correo ') }}</label>
                            <div class="col-md-4">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="Coreo Empresarial">
                            </div>
                            
                            <div class="col-md-2">
                                <select name="dominio" id="dominio" class="form-select form-select-lg " style="height:38px" required>
                                @if(Auth::user()->concesion == 'Grupo Huerta' || Auth::user()->concesion == 'Super Admin')
                                    <option value="@automotriz1.mx">automotriz1.mx</option>
                                    <option value="@automotriz2.mx">automotriz2.mx</option>
                                    <option value="@automotriz3.mx">automotriz3.mx</option>
                                    <option value="@automotriz4.mx">automotriz4.mx</option>
                                    <option value="@automotriz5.mx">automotriz5.mx</option>
                                    <option value="@automotriz6.mx">automotriz6.mx</option>
                                    <option value="@automotriz7.mx">automotriz7.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz1')
                                    <option  value="@automotriz1.mx">automotriz1.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz2')
                                   <option  value="@automotriz2.mx">automotriz2.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz3')
                                    <option  value="@automotriz3.mx">automotriz3.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz4')
                                    <option  value="@automotriz4.mx">automotriz4.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz5')
                                    <option  value="@automotriz5.mx">automotriz5.mx</option>
                                @elseif(Auth::user()->concesion == 'Automotriz6')
                                    <option  value="@automotriz6.mx">automotriz6.mx</option>
                                @endif     
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end"  >{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="n0ew-password" placeholder="Contraseña">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Verificar Contraseña">
                            </div>
                        </div>
                        <div class="row">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de Usuario') }}</label>
                            <div class="col-md-6">
                                <select name="role" id="role" class="form-select form-select-lg ml-1 mb-3 p-2 swal2-select swal2-input bg-light" required>
                                    <option value="">--Selecciona una Opción--</option>
                                    @foreach($role as $row)
                                        @if($row->id == 1)

                                        @elseif($row->id !=1)
                                            <option value="{{$row->id}}"> {{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">    
                                @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                                    <label for="concesion" class="col-md-4 col-form-label text-md-end"> Concesión <span class=""></span></label><br>
                                    <div class="col-md-6">
                                    <select name="Concesion" id="Concesion" class="form-select form-select-lg ml-2 mb-2 swal2-select swal2-input bg-light" required>
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
                                       <input type="text" name="Concesion"style="display:none" value="Automotriz1">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                        <input style="display:none" name="Concesion" type="text" value="Automotriz2">
                                    @elseif(Auth::user()->concesion == 'Automotriz3')
                                        <input style="display:none" name="Concesion" type="text" value="Automotriz3">
                                    @elseif(Auth::user()->concesion == 'Automotriz4')
                                        <input style="display:none" name="Concesion" type="text" value="Automotriz5">
                                    @elseif(Auth::user()->concesion == 'Automotriz6')
                                        <input style="display:none" name="Concesion" type="text" value="Automotriz6">
                                    @elseif(Auth::user()->concesion == 'Automotriz7')
                                        <input style="display:none" name="Concesion" type="text" value="Automotriz7">
                                    @endif
                        </div>
                        <div class="row mb-3">
                            
                                @if(Auth::user()->concesion == 'Automotriz7' || Auth::user()->concesion == 'Super Admin')
                                    <label for="ConexionDB" class="col-md-4 col-form-label text-md-end"> Conexcion de base de datos <span class=""></span></label><br>
                                    <div class="col-md-6">
                                    <select name="ConexionDB" id="ConexionDB" class="form-select form-select-lg ml-2 mb-2 swal2-select swal2-input bg-light" required>
                                        <option value="">--Selecciona una Conexión--</option>
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
                                       <input style="display:none" type="text" name="ConexionDB" value="Automotriz1">
                                    @elseif(Auth::user()->concesion == 'Automotriz2')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz2">
                                    @elseif(Auth::user()->concesion == 'Automotriz3')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz3">
                                    @elseif(Auth::user()->concesion == 'Automotriz4')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz4">
                                    @elseif(Auth::user()->concesion == 'Automotriz5')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz5">
                                    @elseif(Auth::user()->concesion == 'Automotriz6')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz6">
                                    @elseif(Auth::user()->concesion == 'Automotriz7')
                                        <input style="display:none" type="text" name="ConexionDB" value="Automotriz7">
                                    @endif
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Aceptar') }}</button>
                                <a href="/usuarios" class="btn btn-secondary">cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
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
    <script>
    $('.eliminar').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Estás Seguro?',
            text: "¡Este usuario se va eliminar definitivamente!",
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
    @if(session('eliminar')=='ok')
    <script>
        Swal.fire(
            '¡Eliminado!',
            'El usuario se eliminó con éxito.',
            'success'
        )
    </script>
    @elseif(session('Noeliminar')=='ok')
    <script>
        Swal.fire(
            '¡No eliminado!',
            'No se puede eliminar este usuario.',
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
            $('#example').DataTable({
                "lengthMenu":[[5,8],[5,8]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert1").fadeOut(1500);
            },3000);
            setTimeout(function() {
                $(".alert2").fadeOut(1500);
            },3000);
        });
    </script>
@stop
<!--agregamos Java Script--