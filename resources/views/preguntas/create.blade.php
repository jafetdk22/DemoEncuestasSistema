
@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Nueva Encuestas</title>

@section('content_header')
@stop
<!--Agregamos un header a nuestra pagina -->

@section('content')
<div class="container">
    <div class="row justify-content-center ">
    <div class="col-md-6">
        <div class="card">
          <div class="card-header">{{__('Nueva Pregunta') }}</div>
          <div class="card-body">
                <form method="POST" action="/preguntas" class="formulario-eliminar">
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
                      <label for="concesion" class="col-md-2 col-form-label text-md-end"> Concesión <span class="m-2"></span></label><br>
                         <div class="col-md-6">
                            <select name="concesion" id="concesion" class="form-select form-select-lg mb-1 swal2-select swal2-input bg-light" required>
                                <option value="">--Selecciona una Concesión--</option>
                                <option value="Grupo Huerta">Grupo Huerta</option>
                                <option value="Automotriz Lerma">Automotriz Lerma</option>
                                <option value="Divol Lindavista">Divol Lindavista</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                      <label for="pregunta" class="col-md-2  mr-2 col-form-label text-md-end">Pregunta</label>
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

<!--Contenido de nuestra pagina-->

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
<!--agregamos css-->

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('.formulario-eliminar').submit(function(){
    this.submit();
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Your work has been saved',
        showConfirmButton: false,
        timer: 9500
    })
})
</script>

@stop

<!--agregamos Java Script-->