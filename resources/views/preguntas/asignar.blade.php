@extends('adminlte::page')
<!--implementa la vista de adminlte-->

@section('title', 'Dashboard')
<!--agregamos un titulo-->
<title>Panel Encuestas</title>

@section('content_header')
@stop
@section('content')
    <div class="estadisticas">
        <div class="col-xl">
            <p class="bg-primary  text-center p-1  rounded-3"><b>PREGUNTA:</b> {{$pregunta->pregunta}}</p>
            <div class="row" >
                <div class="col-3"><a href="/preguntas" class="btn btn-danger">Regresar</a></div>
                <div class="col-6">
                @if($alerta == 0)
                @elseif($alerta == 1)
                    <div class="alert alert1 " style="background-color: #cff4fc; border-color: #b6effb; color: #055160;" role="alert">
                        ¡Respuesta creada!
                    </div>
                @elseif($alerta == 2)
                    <div class="alert alert1 " style="background-color:#cfe2ff; border-color: #b6d4fe; color: #084298;" role="alert">
                        ¡La pregunta se editó correctamente!
                    </div>
                @elseif($alerta == 3)
                    <div class="alert alert1 " style="background-color:#fff3cd; border-color: #ffecb5; color: #664d03;" role="alert">
                        ¡Respuesta Removida Correctamente!
                    </div>
                @elseif($alerta == 4)
                    <div class="alert alert1 " style="background-color:#cfe2ff; border-color: #b6d4fe; color: #084298;" role="alert">
                        ¡Respuesta Asignada Correctamente!
                    </div>
                @elseif($alerta == 5)
                    <div class="alert alert1 " style="background-color:#f8d7da; border-color: #f5c2c7; color: #842029;" role="alert">
                        ¡La pregunta se eliminó correctamente!
                    </div>
                @endif
            </div>
            </div>
        </div>
        <br>
        <div class="row" id="contenidos">
            <div class="col" id="nuevaResPuesta">
                <div class="card">
                    <div class="card-header">{{__('Nueva Respuesta')}}</div>
                    <div class="card-body">
                        <form action="/preguntas/respuestasCreate/{{$pregunta->id}}" method="POST">
                        @csrf
                        <input type="number" value="{{$pregunta->id}}" style="display:none" name="pre">
                            <div class="row">
                            <label for="res" class="col-md-8 col-form-label text-md-end">Texto de la respuesta</label><br>
                            <div class="col-8" >
                                <input id="res" type="text" class="form-control @error('respuesta') is-invalid @enderror ml-4" name="res" required  autofocus>
                                @error('res')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                            <div class="row ">
                                <label for="valor" class="col-md-8 col-form-label text-md-end"> valor que tendrá esta respuesta<span class="m-2"></span></label><br>
                                <div class="col-md-6 ">
                                    <select name="valor" id="valor" class="form-select form-select-lg ml-4 "required>
                                        <option value="">--Valor que tendrá esta respuesta-</option>
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
                            @if($pregunta->emoji == "Si")
                            <div class="row ml-3">
                                <label for="" class="col-md-8 col-form-label text-md-end">Selecciona una imagen de la colección</label>
                                <div class="col-md-9  d-flex">
                                    <label for="emoji-1" class="emoji">
                                        <input id="emoji-1" type="radio" name="emoji" value="1" >
                                        <img src="{{asset('images/emoji/emoji-1.png')}}"  alt="">
                                    </label>
                                    <label for="emoji-2" class="emoji">
                                        <input id="emoji-2" type="radio" name="emoji" value="2" >
                                        <img src="{{asset('images/emoji/emoji-2.png')}}"  alt="">
                                    </label>
                                    <label for="emoji-3" class="emoji">
                                        <input id="emoji-3" type="radio" name="emoji" value="3" >
                                        <img src="{{asset('images/emoji/emoji-3.png')}}"  alt="">
                                    </label>
                                    <label for="emoji-4" class="emoji">
                                        <input id="emoji-4" type="radio" name="emoji" value="4" >
                                        <img src="{{asset('images/emoji/emoji-4.png')}}"  alt="">
                                    </label>
                                    <label for="emoji-5" class="emoji">
                                        <input id="emoji-5" type="radio" name="emoji" value="5" >
                                        <img src="{{asset('images/emoji/emoji-5.png')}}"  alt="">
                                    </label>
                                    <label for="emoji-6" class="emoji">
                                        <input id="emoji-6" type="radio" name="emoji" value="6" >
                                        <img src="{{asset('images/emoji/emoji-6.png')}}"  alt="">
                                    </label>   
                                </div>
                            </div>
                            @else
                                <label for="emoji-0" class="emoji " style="display:none">
                                    <input id="emoji-0" type="radio" name="emoji" value="0" checked>
                                    <img src="{{asset('images/emoji/emoji-0.png')}}"  alt="">
                                </label> 
                            @endif
                            <div class="row m-3 justify-content-left">
                            <div class="col">
                                <button type="submit" class="btn btn-primary" >Crear Respuesta</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
            <table id="example1" class="table table-striped" style="width:100%">
                 <p class="bg-dark text-center">Respuestas Disponibles</p>
                <thead>
                <tr>
                    <th>Respuesta</th>
                    <th>Valor</th>
                    <th>emoji</th>
                    <th>Acciones</th>
                </tr>
               </thead>
               <tbody>
               @foreach( $disp as $row)
               @if($pregunta->emoji != "Si" && $row->emoji== 0)
                <tr>
                    <td>{{$row->respuesta}}</td>
                    <td>{{$row->valor}}</td>
                    <td>
                        @if($row->emoji==='1')
                        <label for="" > 
                            <img src="{{asset('images/emoji/emoji-1.png')}}" style=" width:30px" alt="">
                        </label>
                        @elseif($row->emoji==='2')
                        <img src="{{asset('images/emoji/emoji-2.png')}}" style=" width:30px"  alt="">
                        
                        @elseif($row->emoji==='3')
                        <img src="{{asset('images/emoji/emoji-3.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='4')
                        <img src="{{asset('images/emoji/emoji-4.png')}}" style=" width:30px"  alt="">

                        @elseif($row->emoji==='5')
                        <img src="{{asset('images/emoji/emoji-5.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='6')
                        <img src="{{asset('images/emoji/emoji-6.png')}}" style=" width:30px"  alt="">
                        @elseif($row->emoji==='0')
                        <img src="{{asset('images/emoji/emoji-0.png')}}" style=" width:30px"  alt="">
                        @endif
                    </td>
                    <td>
                    <div class="dropdown show"> 
                        <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                        <form action=" /agregarR/{{$pregunta->id}}" method="POST">
                        @csrf 
                            <input id="respuesta" type="text"name="respuesta" value="{{ $row->id }}" required style=" display:none">
                            <button type="submit" class="dropdown-item  ">Agregar</button>
                        </form>

                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square"></i> Editar</a>
                            <form action="/destroyR/{{$row->id}}" method="POST"  class="formulario-eliminar">
                               @csrf
                               <input id="pregunta" type="text"name="pregunta" value="{{ $pregunta->id}}" required style=" display:none">
                               <button type="submit" class="dropdown-item "><i class="bi bi-trash3-fill"></i> Eliminar</button>
                            </form>
                        </div>
                    </div>
                </tr>
               @elseif($pregunta->emoji != "No"  && $row->emoji != 0)
               <tr>
                    <td>{{$row->respuesta}}</td>
                    <td>{{$row->valor}}</td>
                    <td>
                        @if($row->emoji==='1')
                        <label for="" > 
                            <img src="{{asset('images/emoji/emoji-1.png')}}" style=" width:30px" alt="">
                        </label>
                        @elseif($row->emoji==='2')
                        <img src="{{asset('images/emoji/emoji-2.png')}}" style=" width:30px"  alt="">
                        
                        @elseif($row->emoji==='3')
                        <img src="{{asset('images/emoji/emoji-3.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='4')
                        <img src="{{asset('images/emoji/emoji-4.png')}}" style=" width:30px"  alt="">

                        @elseif($row->emoji==='5')
                        <img src="{{asset('images/emoji/emoji-5.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='6')
                        <img src="{{asset('images/emoji/emoji-6.png')}}" style=" width:30px"  alt="">
                        @elseif($row->emoji==='0')
                        <img src="{{asset('images/emoji/emoji-0.png')}}" style=" width:30px"  alt="">
                        @endif
                    </td>
                    <td>
                    <div class="dropdown show"> 
                        <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                        <form action=" /agregarR/{{$pregunta->id}}" method="POST">
                        @csrf 
                            <input id="respuesta" type="text"name="respuesta" value="{{ $row->id }}" required style=" display:none">
                            <button type="submit" class="dropdown-item  ">Agregar</button>
                        </form>

                            <a class="dropdown-item" type="button" data-toggle="modal" data-target="#Editar{{$row->id}}"><i class="bi bi-pencil-square"></i> Editar</a>
                            <form action="/destroyR/{{$row->id}}" method="POST"  class="formulario-eliminar">
                               @csrf
                               <input id="pregunta" type="text"name="pregunta" value="{{ $pregunta->id}}" required style=" display:none">
                               <button type="submit" class="dropdown-item "><i class="bi bi-trash3-fill"></i> Eliminar</button>
                            </form>
                        </div>
                    </div>
                </tr>
               @endif

                <!-- Modal editar pregunta --> 
                <div class="modal fade" id="Editar{{$row->id}}" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-navy">
                                <h4 class="modal-title"> {{__('Nueva Encuesta') }}</h4>
                                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form action="/respuestasUpdate/{{$row->id}}" method="POST">
                                @csrf
                                <input type="number" name="pregunta" id="pregunta" value="{{$pregunta->id}}" style="display:none ">
                                    <div class="row">
                                        <label for="res" class="col-md-8 col-form-label text-md-end">Texto de la respuesta</label><br>
                                        <div class="col-8" >
                                            <input id="res" type="text" class="form-control @error('respuesta') is-invalid @enderror ml-4" name="res" required  autofocus value ="{{$row->respuesta}}"> 
                                            @error('res')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                    @if($pregunta->emoji == "Si")
                            <div class="row mt-3">
                                <label for="" class="text-secondary">Selecciona una imagen de la colección</label>
                                <div class="col-md-6  d-flex">
                                   
                                
                                <label for="{{$row->id}}-1" class="emoji">
                                    <input id="{{$row->id}}-1" type="radio" name="emoji" value="1" >
                                    <img src="{{asset('images/emoji/emoji-1.png')}}"  alt="">
                                </label>

                                
                                <label for="{{$row->id}}-2" class="emoji">
                                    <input id="{{$row->id}}-2" type="radio" name="emoji" value="2" >
                                    <img src="{{asset('images/emoji/emoji-2.png')}}"  alt="">
                                </label>

                                <label for="{{$row->id}}-3" class="emoji">
                                    <input id="{{$row->id}}-3" type="radio" name="emoji" value="3" >
                                    <img src="{{asset('images/emoji/emoji-3.png')}}"  alt="">
                                </label>

                                <label for="{{$row->id}}-4" class="emoji">
                                    <input id="{{$row->id}}-4" type="radio" name="emoji" value="4" >
                                    <img src="{{asset('images/emoji/emoji-4.png')}}"  alt="">
                                </label>

                                <label for="{{$row->id}}-5" class="emoji">
                                    <input id="{{$row->id}}-5" type="radio" name="emoji" value="5" >
                                    <img src="{{asset('images/emoji/emoji-5.png')}}"  alt="">
                                </label>

                                <label for="{{$row->id}}-6" class="emoji">
                                    <input id="{{$row->id}}-6" type="radio" name="emoji" value="6" >
                                    <img src="{{asset('images/emoji/emoji-6.png')}}"  alt="">
                                </label>   
                                </div>
                            </div>
                            @else
                                <label for="emoji-0" class="emoji" style="display:none">
                                    <input id="emoji-0" type="radio" name="emoji" value="{{$row->emoji}}" checked>
                                    <img src="{{asset('images/emoji/emoji-0.png')}}"  alt="">
                                </label> 
                            @endif

                        </div>
                  
                                    <div class="row">
                                        <label for="valor" class="col-md-8 col-form-label text-md-end"> valor que tendrá esta respuesta<span class="m-2"></span></label><br>
                                        <div class="col-md-6 ">
                                            <select name="valor" id="valor" class="form-select form-select-lg ml-4 swal2-select swal2-input bg-light" value="{{$row->valor}}">
                                                <option value="{{$row->valor}}">--{{$row->valor}}--</option>
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
                @endforeach
          
              </tbody>
            </table>
            </div>

        </div>
        <div class="row-5">
        <table id="example2" class="table table-striped" style="width:80%; margin:0 auto;">
                 <p class="bg-dark text-center">Respuestas Asignadas</p>
                <thead>
                <tr>
                    <th>Respuesta</th>
                    <th>Valor</th>
                    <th>emoji</th>
                    <th>Acciones</th>
                </tr>
               </thead>
               <tbody>
               @foreach( $respues as $row)
                <tr>
                    <td class="tds">{{$row->respuesta}}</td>
                    <td class="tds">{{$row->valor}}</td>
                    <td class="tds">
                        @if($row->emoji==='1')
                        <label for="" > 
                            <img src="{{asset('images/emoji/emoji-1.png')}}" style=" width:30px" alt="">
                        </label>
                        @elseif($row->emoji==='2')
                        <img src="{{asset('images/emoji/emoji-2.png')}}" style=" width:30px"  alt="">
                        
                        @elseif($row->emoji==='3')
                        <img src="{{asset('images/emoji/emoji-3.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='4')
                        <img src="{{asset('images/emoji/emoji-4.png')}}" style=" width:30px"  alt="">

                        @elseif($row->emoji==='5')
                        <img src="{{asset('images/emoji/emoji-5.png')}}"  style=" width:30px" alt="">

                        @elseif($row->emoji==='6')
                        <img src="{{asset('images/emoji/emoji-6.png')}}" style=" width:30px"  alt="">
                        @elseif($row->emoji==='0')
                        <img src="{{asset('images/emoji/emoji-0.png')}}" style=" width:30px"  alt="">
                        @endif
                    </td>
                    <td class="tds">
                        <form action=" /removerR/{{$pregunta->id}}" method="POST" class="remover-respuesta">
                        @csrf 
                            <input id="respuesta" type="text"name="respuesta" value="{{ $row->id }}" required style=" display:none">
                            <button type="submit" class=" btn btn-danger btn-sm" style="margin:0;">Remover</button>
                        </form>
                </tr>
                @endforeach
          
              </tbody>
            </table>
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
        .emoji{
            min-width: 30px;
            margin-left:10px;
        }
        .emoji input{
            display:none;
        }
        .emoji:checked{
            font-weight: bold;
        }
        [type=radio] + img {
            cursor: pointer;
        }
        [type=radio]:checked + img {
            filter:blur(2px);
            
        }
        th{
            text-align: center;
        }
        td{
            text-align: center;
            padding:5px 10px !important;
        }
        .remover-respuesta{
            margin:0 !important;
        }


    </style>
@stop
<!--agregamos css-->

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('creado')=='ok')
<script>
Swal.fire({
  position: 'center',
  icon: 'success',
  title: 'Respuesta Creada Exitosamente',
  showConfirmButton: false,
  timer: 1500
})
</script>

@elseif(session('agregado')=='ok')
<script>
Swal.fire({
  position: 'center',
  icon: 'success',
  title: 'Respuesta Asignada Correctamente',
  showConfirmButton: false,
  timer: 1500
})
</script>

@elseif(session('noAgregado')=='ok')
<script>
    Swal.fire(
      'No Asignada!',
      'La respueta ya esta asignada a esta pregunta.',
      'error'
    )
</script>

@elseif(session('Removida')=='ok')
<script>
    Swal.fire(
      'Removida!',
      'La respueta ha sido removida con éxito.',
      'error'
    )
</script>
@elseif(session('eliminar')=='ok')
<script>
    Swal.fire(
      'Eliminada!',
      'La respueta ha sido eliminada con éxito.',
      'success'
    )
</script>
@elseif(session('Noeliminar')=='ok')
<script>
    Swal.fire(
      'No eliminada!',
      'La respueta esta asignada a una pregunta.',
      'error'
    )
</script>
@endif
<script>
    $('.remover-respuesta').submit(function(e){
        e.preventDefault();
        Swal.fire({
        title: '¿Estas seguro?',
        text: "¡La respuesta ya no pertenecera a esta pregunta!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'cancelar',
        confirmButtonText: 'Si, ¡remover!'
        }).then((result) => {
            if (result.isConfirmed) {
                /**/
                this.submit();
                }
        })
    })

    $('.formulario-eliminar').submit(function(e){
        e.preventDefault();
        Swal.fire({
        title: '¿Estas seguro?',
        text: "¡La respuesta se eliminará!",
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
    $('#example1').DataTable({
        "lengthMenu":[[3],[3]],
        "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
    });
} );
$(document).ready(function() {
    $('#example2').DataTable({
        "lengthMenu":[[3],[3]],        
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
@stop

<!--agregamos Java Script-->