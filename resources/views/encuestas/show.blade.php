@extends('layouts.encuestas')

@section('contenido')
    <div class="containers d-flex responder">
        @foreach($prueba as $ase)
            <div class="cols-3 car">
                <div class="row">
                    <div class="cajaNo">
                        <div class=" col card-header encuesta_header ">
                            <div class="col text-light"><h5 >CLIENTE</h5></div>
                        </div>
                        <div class="col-body">
                            Nombre del cliente: <b>{{$ase->ORNombre}} </b> <br>
                            @if($orden== 123456789)
                            <div class="encuestasS">No.Orden <b>DEMO</b> </div>
                            @else
                            <div class="encuestasS">No.Orden  <b>{{$orden}}</b></div>
                            @endif
                            Fecha de Alta: <b>{{$ase->ORFecAlta}}</b> <br>
                            Fecha de Entrega: <b>{{$ase->ORFecEnt}}</b><br>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="cajaNo">
                        <div class="col card-header encuesta_header">
                            <div class="col"><h5>SERVICIO</h5></div>
                        </div>
                        <div class="col-body">
                            Descripción del Servicio: <b>{{$ase->ORFalla1}}</b><br>
                            Asesor que lo Atendio: <b>{{$ase->TraNom}}{{$ase->TraApPat}}</b><br>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="cajaNo">
                        <div class="col card-header encuesta_header">
                            <div class="col"><h5>UNIDAD</h5></div>
                        </div>
                        <div class="col-body">
                            Descripción de la unudad: <b>{{$ase->MDes}}</b><br>
                            Modelo: <b>{{$ase->Modelo}}</b><br>
                            Año: <b>{{$ase->ORAno}}</b><br>
                            Color: <b>{{$ase->ORColUni}}</b><br>
                            Número de serie <b>{{$ase->ORChasis}}</b><br>
                        </div>
                    </div> 
                </div>  
            </div>
            <div class="cols preguntasRes">
                <div class="card preguntasRes-card">
                    <div class="card-header encuesta_header">
                        @if($orden== 123456789)
                        <div class="encuestasS">DEMO</div>
                        @endif
                        <div class="encuestasS">{{$encuesta->Nombre}}</div>
                    </div>
                    <div class="card-body">
                        <form action="/detalle" method="POST" id="encuestasClientes">
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
                                            @if($row->emoji === 'No')
                                                <select name="Resp_P{{$row->Row}}" id="Resp_P{{$row->Row}}" class="form-select" required>
                                                    <option value=""> Seleccione una Opción </option>
                                                    @foreach($respuestas as $rows)
                                                        @if($rows->pregunta_id == $row->pregunta_id)
                                                            <option value="{{$rows->valor}}">{{$rows->respuesta}}</option>
                                                        @endif
                                                    @endforeach
                                                </select> 
                                            @elseif($row->emoji === 'Si')
                                                <div class="selectbox">
				                                    <div class="select d-flex justify-content-between" id="select">
					                                    <div class="contenido-select">
                                                            <h1 class="titulo">Seleccione una Opción </h1>
					                                    </div>
                                                        <div class="col text-end"><i class="fas fa-angle-down"></i></div>
				                                    </div>
				                                    <div class="opciones" id="opciones">
                                                        @foreach($respuestas as $rows)
                                                            @if($rows->pregunta_id == $row->pregunta_id)
					                                            <a href="#" class="opcion" style="text-decoration:none;">
						                                            <div class="contenido-opcion">
                                                                        @if($rows->emoji=='1')
                                                                        <img src="{{asset('images/emoji/emoji-1.png')}}"  alt="">
                                                                        @elseif($rows->emoji=='2')
                                                                        <img src="{{asset('images/emoji/emoji-2.png')}}"  alt="">
                                                                        @elseif($rows->emoji=='3')
                                                                        <img src="{{asset('images/emoji/emoji-3.png')}}"  alt="">
                                                                        @elseif($rows->emoji=='4')
                                                                        <img src="{{asset('images/emoji/emoji-4.png')}}"  alt="">
                                                                        @elseif($rows->emoji=='5')
                                                                        <img src="{{asset('images/emoji/emoji-5.png')}}"  alt="">
                                                                        @elseif($rows->emoji=='6')
                                                                        <img src="{{asset('images/emoji/emoji-6.png')}}"  alt="">
                                                                        @endif
							                                            <div class="textos">
								                                            <h5 class="titulo">{{$rows->respuesta}}</h5>
                                                                            <h6 class="titulos" style="display:none">{{$rows->valor}}</h6>
							                                            </div>
						                                            </div>
					                                            </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <input type="hidden" name="Resp_P{{$row->Row}}" id="inputSelect" value="">
                                            @endif
                                        </div>
                                    <hr>
                                    @elseif($row->tipo === 'Radio Button')
                                        <br>
                                        <div class="col">
                                            <label for="" class="text-secondary">Selecciona una opción</label>
                                            <div class="d-flex">
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
                            @if($encuesta->concesion == 'Grupo Huerta')
                                @if($conexionDB == 'Lerma')
                                    <input type="text" name="concesion" style="display:none" value="Automotriz Lerma">
                                @elseif($conexionDB == 'divol')
                                    <input type="text" name="concesion" style="display:none" value="Divol ">
                                @elseif($conexionDB == 'Lindavista')
                                    <input type="text" name="concesion" style="display:none" value="Divol Lindavista">
                                @elseif($conexionDB == 'Perinorte')
                                    <input type="text" name="concesion" style="display:none" value="Divol Perinorte">
                                @elseif($conexionDB == 'Norte')
                                    <input type="text" name="concesion" style="display:none" value="Divol Norte">
                                @elseif($conexionDB == 'Motors')
                                    <input type="text" name="concesion" style="display:none" value="Pirineos Motors">
                                @endif
                            @endif
                            <input type="hidden" name="conexionDB" value="{{$conexionDB}}">

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
            <script>
                /* bindamos al evento submit del elemento formulario la función de validación
                document.getElementById("encuestasClientes").addEventListener("submit", function(event){
                    let hasError = false;
                    if(!document.querySelector('input[name="Resp_P{{$row->Row}}"]:checked')) {
                        Swal.fire('Alerta!',
                            'Debe contestar todas las preguntas para poder enviar!',
                            'info'
                        )
                        hasError = true;
                    }
                    // si hay algún error no efectuamos la acción submit del form
                    if(hasError) event.preventDefault();
                });*/
            </script>
        @endforeach
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
        .selectbox {
            width: 100%;
            margin: auto;
            position: relative;

        }
        .select {
	        background: #fff;
	        width: 100%;
            height:20px;
            display: flex;
	        box-shadow: 0px 0px 0px rgba(0, 0, 0, .16);
	        border-radius: 10px;
	        cursor: pointer;
	        display: flex;
	        justify-content: space-between;
	        align-items: center;
	        transition: .2s ease all;
	        margin-bottom: 30px;
            padding:20px;
	        padding-top: 30px;
	        position: relative;
            border:1px solid #979797;
        }
        .select.active,
        .select:hover {
	        box-shadow: 0px 5px 10px rgba(0, 0, 0, .16);
	        border: 2px solid #213f8f;
        }
        .select.active:before {
	        content: "";
	        display: block;
	        height: 0;
	        width: 0;
	        border-top: 15px solid #213f8f;
	        border-right: 15px solid transparent;
	        border-bottom: 15px solid transparent;
	        border-left: 15px solid transparent;
	        position: absolute;
	        bottom: -30px;
	        left: calc(50% - 15px);
        }

        .select i {
	        font-size: 30px;
	        margin-left: 30px;
	        color: #213f8f;
        }

        .titulo {
	        margin-bottom: 10px;
	        color: #000;
	        font-weight: 600;
	        font-size: 1em;
            margin-top:8px;
        }
        .opciones {
	        background: #fff;
	        border-radius: 10px;
	        box-shadow: 0px 5px 10px rgba(0,0,0,.16);
	        height:auto;
	        z-index: 100;
	        width: 100%;
	        display: none;
        }

        .opciones.active {
	        display: block;
	        animation: fadeIn .3s forwards;
	        z-index: 100;
            position: absolute;
        }

        @keyframes fadeIn {
	        from {
		        transform: translateY(-200px) scale(.5);
	        }
	        to {
		        transform: translateY(0) scale(1);
	        }
        }
        .contenido-opcion {
	        width: 100%;
	        display: flex;
	        align-items: center;
	        transition: .2s ease all;        }

        .opciones .contenido-opcion {
	        padding: 5px;
        }

        .contenido-opcion img {
	        width: 30px;
	        height: 30px;
	        margin-right: 5px;
	        margin-top: 0px;

        }

        .opciones .contenido-opcion:hover {
	        background: #213F8F;
        }

        .opciones .contenido-opcion:hover .titulo,
        .opciones .contenido-opcion:hover .descripcion {
	        color: #fff;
        }
        @media screen and (max-width: 800px){
	        .selectbox {
		        width: 100%;
	        }
        }
    </style>
@stop
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <script>
        Swal.fire(
            '¡Bienvenido!',
            'Por tu preferencia, nos mantenemos actualizados y enfocados en atenderte como mereces',
            'success', 
        )
    </script>
    <script>
        const select = document.querySelector('#select');
        const opciones = document.querySelector('#opciones');
        const contenidoSelect = document.querySelector('#select .contenido-select');
        const hiddenInput = document.querySelector('#inputSelect');
        document.querySelectorAll('#opciones > .opcion').forEach((opcion) => {
	        opcion.addEventListener('click', (e) => {
		        e.preventDefault();
		        contenidoSelect.innerHTML = e.currentTarget.innerHTML;
		        select.classList.toggle('active');
		        opciones.classList.toggle('active');
		        hiddenInput.value = e.currentTarget.querySelector('.titulos').innerText;
	        });
        });
        select.addEventListener('click', () => {
	        select.classList.toggle('active');
	        opciones.classList.toggle('active');
        });
    </script>
@endsection 


