
@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Crear Usuario</title>

@section('content_header')
@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Nuevo Usuario') }}</div>

                <div class="card-body">
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

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo empresarial') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="Coreo Empresarial">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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


                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de Usuario') }}</label>

                            <div class="col-md-6">
                                <select name="role" id="role" class="form-select form-select-lg ml-1 mb-3 p-2 swal2-select swal2-input bg-light"required>
                                    <option value="">--Selecciona una Opción--</option>
                                    @foreach($role as $row)
                                    <option value="{{$row->id}}"> {{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="concesion" class="col-md-4 col-form-label text-md-end"> Concesión <span class="m-2"></span></label><br>
                         <div class="col-md-6">
                            <select name="concesion" id="concesion" class="form-select form-select-lg ml-1 mb-3 swal2-select swal2-input bg-light" required>
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
</div>
@stop

<!--Contenido de nuestra pagina-->

@section('css')

@stop
<!--agregamos css-->

@section('js')

@stop

<!--agregamos Java Script-->