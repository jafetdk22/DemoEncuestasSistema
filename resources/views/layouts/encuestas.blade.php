<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
  
  p {
    text-align: center;
  }
  
  .estrellas {
    font-size: 30px;
  }
  
.pregunta{
    font-size:1.1em;
    font-weight: bold;
}
  .estrellasEmoji{
    filter:grayscale(100%);
    width: 15%;
  }
  .estrellasEmoji img{
    width: 50%;
  }
  .estrellas {
    color: grey;
  }
  
  .clasificacion {
    direction: rtl;
    unicode-bidi: bidi-override;
  }
  .estrellasEmoji:hover,
  .estrellasEmoji:hover ~ .estrellasEmoji {
    filter:grayscale(30%);
  }
  .estrellas:hover,
  .estrellas:hover ~ .estrellas {
    color: orange;
  }
  input[type="radio"]:checked ~ .estrellas {
    color: orange;
  }
  input[type="radio"]:checked ~ .estrellasEmoji {
    filter:grayscale(0%);
  }
  .encuesta_header{
      background-color: #022E49;
      color:#fff;
      display:flex;
      justify-content:space-between;
      
  }
  .nombres{
    font-weight: bold;
  }

    </style>
    @yield('css')
</head>
<body>
    @yield('contenido')

    @yield('js')
</body>
</html>