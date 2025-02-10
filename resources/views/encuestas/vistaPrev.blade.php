@extends('layouts.encuestas')

@section('contenido')

<div class="containers d-flex">
        <div class="cols">
            <div class="card">
                <div class="card-header encuesta_header">
                    @if($orden== 123456789)
                    <div class="encuestasS">DEMO</div>
                    @else
                    <div class="encuestasS">Seamos superhéroes al brindar un buen servicio</div>
                    @endif
                    <div class="encuestasS">{{$encuesta->Nombre}}</div>
                </div>
                <div class="card-body">
                
                    <form action="/detalle" method="POST">
                        @csrf
                        <input type="number" name="encuesta" id="encuesta" value="{{$encuesta->id}}" style="display:none">
                        <input type="text" name="orden" id="orden" value="{{$orden}}" style="display:none">
                    @foreach( $preguntas as $row)
                    
                        <div class="m-3">
                            <input type="number" name="P{{$row->Row}}" id="{{$row->pregunta_id}}" value="{{$row->pregunta_id}}" style="display:none" >
                            <label for="exampleInputEmail1" class="form-label fw-bold">{{$row->pregunta}}</label>
                            @if ($row->tipo === 'Texto')
                                    <div class="col">
                                        <input type="text" class="form-control" name="Resp_P{{$row->Row}}" required>
                                    </div>
                                    <hr>
                            @elseif($row->tipo === 'Seleccion')
                            <div class="col">
                                <select name="Resp_P{{$row->Row}}" id="Resp_P{{$row->Row}}" class="form-select" required>
                                    <option value="">-> Seleccione una Opción <-</option>
                                        @foreach($respuestas as $rows)
                                           @if($rows->pregunta_id == $row->pregunta_id)
                                           <option value="{{$rows->valor}}">{{$rows->respuesta}}</option>
                                           @endif
                                        @endforeach
                                </select> 
                            </div>
                           <hr>
                            @elseif($row->tipo === 'Radio Button')
                            <br>
                                <div class="col">
                                <label for="" class="text-secondary">Selecciona una opción</label>
                                    <div class="d-flex justify-content-center" >
                                    @foreach($respuestas as $rows)
                                    @if($row->pregunta_id == $rows->pregunta_id)
                                        @if($row->emoji== 'Si')
                                            @if($rows->emoji=='1')
                                            <label for="{{$row->Row}}-1" class="emoji">
                                            <input id="{{$row->Row}}-1" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-1.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>
                                            @elseif($rows->emoji=='2')
                                            <label for="{{$row->Row}}-2" class="emoji">
                                            <input id="{{$row->Row}}-2" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-2.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>
                                            @elseif($rows->emoji=='3')
                                            <label for="{{$row->Row}}-3" class="emoji">
                                                <input id="{{$row->Row}}-3" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                                <img src="{{asset('images/emoji/emoji-3.png')}}"  alt="">
                                                <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>
                                            @elseif($rows->emoji=='4')
                                            <label for="{{$row->Row}}-4" class="emoji">
                                                <input id="{{$row->Row}}-4" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                                <img src="{{asset('images/emoji/emoji-4.png')}}"  alt="">
                                                <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>
                                            @elseif($rows->emoji==='5')
                                            <label for="{{$row->Row}}-5" class="emoji">
                                                <input id="{{$row->Row}}-5" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                                <img src="{{asset('images/emoji/emoji-5.png')}}"  alt="">
                                                <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>
                                            @elseif($rows->emoji==='6')
                                            <label for="{{$row->Row}}-6" class="emoji">
                                                <input id="{{$row->Row}}-6" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                                <img src="{{asset('images/emoji/emoji-6.png')}}"  alt="">
                                                <p class="texto-r">{{$rows->respuesta}}</p>
                                            </label>  
                                            @endif
                                        @elseif($row->emoji== 'No')
                                            <div class="m-3">
                                                <input id="{{$row->Row}}-7" type="radio" name="Resp_P{{$row->Row}}"  value="{{$rows->valor}}"><br>
                                                <label for="{{$row->Row}}-7">{{$rows->respuesta}}</label>
                                            </div>
                                         @endif
                                    @endif
                                    @endforeach
                                    </div>

                                </div>
                                <hr>

                            @foreach($respuestas as $rows)
                                @if($row->pregunta_id == $rows->pregunta_id)
                                    @if($rows->emoji=='1')
                                        <label for="{{$row->Row}}-1" class="emoji">
                                           <input id="{{$row->Row}}-1" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                           <img src="{{asset('images/emoji/emoji-1.png')}}"  alt="">
                                           <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label>
                                    @elseif($rows->emoji=='2')
                                        <label for="{{$row->Row}}-2" class="emoji">
                                           <input id="{{$row->Row}}-2" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                           <img src="{{asset('images/emoji/emoji-2.png')}}"  alt="">
                                           <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label>
                                    @elseif($rows->emoji=='3')
                                        <label for="{{$row->Row}}-3" class="emoji">
                                            <input id="{{$row->Row}}-3" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-3.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label>
                                    @elseif($rows->emoji=='4')
                                        <label for="{{$row->Row}}-4" class="emoji">
                                            <input id="{{$row->Row}}-4" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-4.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label>
                                    @elseif($rows->emoji==='5')
                                        <label for="{{$row->Row}}-5" class="emoji">
                                            <input id="{{$row->Row}}-5" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-5.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label>
                                    @elseif($rows->emoji==='6')
                                        <label for="{{$row->Row}}-6" class="emoji">
                                            <input id="{{$row->Row}}-6" type="radio" name="Resp_P{{$row->Row}}" value="{{$rows->valor}}" >
                                            <img src="{{asset('images/emoji/emoji-6.png')}}"  alt="">
                                            <p class="texto-r">{{$rows->respuesta}}</p>
                                        </label> 
                                    @else
                                        <label for="{{$row->Row}}-7">{{$rows->respuesta}}</label>
                                            <input id="{{$row->Row}}-7" type="radio" name="Resp_P{{$row->Row}}"  value="{{$rows->valor}}">
                                            
                                        
                                    @endif
                                @endif

                            @endforeach
                            </div>
                            <hr>
                            @elseif($row->tipo === 'CheckBox')
                            <br>
                                <div class="col">

                                    @if($row->emoji== 'No')                                    
                                    <div>
                                        <ul>
                                            @foreach($respuestas as $rows)
                                                @if($row->pregunta_id == $rows->pregunta_id)
                                                    <li style="list-style:none">
                                                        <input type="checkbox" value="{{$rows->valor}}" class="Resp_P{{$row->Row}}">
                                                        <label for="Resp_P1">{{$rows->respuesta}}</label>
                                                    </li>
                                                @endif
                                            @endforeach                                                
                                        </ul>
                                    </div>
                                    <input name="Resp_P{{$row->Row}}" id="Resp_P{{$row->Row}}total" type="text" style="display:none" />
                                    <script>
                                        $(document).ready(function() {
                                            $(document).on('click keyup','.Resp_P{{$row->Row}}',function() {
                                                Resp_P{{$row->Row}}();
                                            });
                                        });
                                        function Resp_P{{$row->Row}}() {
                                            var tot = $('#Resp_P{{$row->Row}}total');
                                            tot.val(0);
                                            $('.Resp_P{{$row->Row}}').each(function() {
                                                if($(this).hasClass('Resp_P{{$row->Row}}')) {
                                                    tot.val(($(this).is(':checked') ? parseFloat($(this).attr('value')) : 0) + parseFloat(tot.val()));  
                                                }
                                                else {
                                                    tot.val(parseFloat(tot.val()) + (isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val())));
                                                }
                                            });
                                            var totalParts = parseFloat(tot.val()).toFixed(2).split('.');
                                            tot.val( totalParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") );  
                                        }
                                    </script>
                                @elseif($row->emoji== 'Si')
                                <div>
                                    <ul>
                                        @foreach($respuestas as $rows)
                                            @if($row->pregunta_id == $rows->pregunta_id)
                                                <li style="list-style:none">
                                                    <input type="checkbox" value="{{$rows->valor}}" class="Resp_P{{$row->Row}}">
                                                    <label for="Resp_P1"><img src="{{asset('images/emoji/emoji-'.$rows->emoji.'.png')}}"  style="width:25px;">{{$rows->respuesta}}</label>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <input name="Resp_P{{$row->Row}}" id="Resp_P{{$row->Row}}totales" type="text"  style="display:none"/>
                                <script>
                                    $(document).ready(function() {
                                        $(document).on('click keyup','.Resp_P{{$row->Row}}',function() {
                                        Resp_P{{$row->Row}}();
                                        });
                                    });
                                    function Resp_P{{$row->Row}}() {
                                        var tot = $('#Resp_P{{$row->Row}}totales');
                                        tot.val(0);
                                        $('.Resp_P{{$row->Row}}').each(function() {
                                            if($(this).hasClass('Resp_P{{$row->Row}}')) {
                                                tot.val(($(this).is(':checked') ? parseFloat($(this).attr('value')) : 0) + parseFloat(tot.val()));  
                                            }
                                            else {
                                                tot.val(parseFloat(tot.val()) + (isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val())));
                                            }
                                        });
                                        var totalParts = parseFloat(tot.val()).toFixed(2).split('.');
                                        tot.val( totalParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") );  
                                    }
                                </script>
                                @endif
                                    
                                </div>
                                <hr> 
                            @elseif($row->tipo === 'Estrellas')
                            @if($row->emoji=='Si')
                                <div class="col">
                                    <p class="clasificacion">
                                        
                                        <input id="radio1{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="10" style="display:none;" >
                                        <label for="radio1{{$row->Row}}" class="estrellasEmoji"><img src="{{asset('images/emoji/emoji-6.png')}}"  alt=""></label>
                                        <input id="radio2{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="8" style="display:none;" >
                                        <label for="radio2{{$row->Row}}" class="estrellasEmoji"><img src="{{asset('images/emoji/emoji-5.png')}}"  alt=""></label>
                                        <input id="radio3{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="6" style="display:none;" >
                                        <label for="radio3{{$row->Row}}" class="estrellasEmoji"><img src="{{asset('images/emoji/emoji-4.png')}}"  alt=""></label>
                                        <input id="radio4{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="4" style="display:none;" >
                                        <label for="radio4{{$row->Row}}" class="estrellasEmoji"><img src="{{asset('images/emoji/emoji-3.png')}}"  alt=""></label>
                                        <input id="radio5{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="2" style="display:none;" >
                                        <label for="radio5{{$row->Row}}" class="estrellasEmoji"><img src="{{asset('images/emoji/emoji-2.png')}}"  alt=""></label>


                                    </p> 
                                </div>

                                @elseif($row->emoji=='No')
                                <div class="col">
                                    <p class="clasificacion">
                                        <input id="radio1{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="10" style="display:none;" >
                                        <label for="radio1{{$row->Row}}" class="estrellas">★</label>
                                        <input id="radio2{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="8" style="display:none;" >
                                        <label for="radio2{{$row->Row}}" class="estrellas">★</label>
                                        <input id="radio3{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="6" style="display:none;" >
                                        <label for="radio3{{$row->Row}}" class="estrellas">★</label>
                                        <input id="radio4{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="4" style="display:none;" >
                                        <label for="radio4{{$row->Row}}" class="estrellas">★</label>
                                        <input id="radio5{{$row->Row}}" type="radio" name="Resp_P{{$row->Row}}" value="2" style="display:none;" >
                                        <label for="radio5{{$row->Row}}" class="estrellas">★</label>
                                    </p> 
                                </div>
                                @endif
                                <hr>
                            @endif
                        </div>
                    @endforeach
                    @if($orden== 123456789)
                    <div class="m-3">
                                    <label for="" class="form-label fw-bold">Asesor que lo atendió</label>
                                    <div class="col">
                                        <label for="" id="NombreAsesor" class="form-label  text-light">Asesor DEMO</label>
                                        <input type="number" name="Asesores" value="Asesor DEMO" style="display:none" >
                                    </div>
                                </div>
                    @else
                                <div class="m-3">
                                    <div class="col">
                                        <input type="number" name="Asesores" value="{{$ase->OPCveOpe}}" style="display:none" >
                                    </div>
                                </div>
                    @endif
                  
                    <div class="row mb-2 mt-3">
                            <div class="col">
                            @if($orden== 123456789)
                                    <a href="/encuestas" class="btn btn-primary enviar" >Enviar</a>
                                    <a href="/encuestas" class="btn btn-secondary ">cancelar</a>
                                @else
                                    <button type="submit" class="btn btn-primary enviar" >Enviar</button>
                                    <a href="/verificado/{{$encuesta->id}}" class="btn btn-secondary ">cancelar</a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>  
            </div>
        </div>
    
</div>
@endsection
@section('css')
<link rel="stylesheet" href="/css/estadisticas.css">
    <style>
        .emoji{
            margin:0 25px;
            justify-content: center;
            position: relative;
            margin-bottom:5px;
        }
        .emoji input{
            display:none;
        }
        .emoji .texto-r{
            position: absolute;
            bottom:-35px;
            left: 25px;
            font-weight:bolder;
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
        #NombreAsesor{
            background-color: #2980B9;
            border-radius:5px;
            padding:10px 30px; 
        }
        .containers{
            width: 100vw;
            margin:0;
            justify-content:center;
        }
        .cols-3{
            width: 25vw;
            margin:0px 35px;
        }
        .cajaNo{
            
            box-shadow:2px 5px 10px #999;

        }
        .cols{
            width: 50vw;
        }
        .col-body{
            font-size:.8em;
        }

    </style>
@stop

@section('js')
<script>
    Swal.fire(
  '¡Bienvenido!',
  'Por tu preferencia, nos mantenemos actualizados y enfocados en atenderte como mereces',
  'success',
 
)

</script>


@endsection 


