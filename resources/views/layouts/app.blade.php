<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('css')

</head>
<body>
    <div class="containers">
        <div class="navs">
            <div class="logos">
                <a href="">{{ config('app.name', 'Laravel') }}</a>
            </div>
            <div class="descripcions mt-3">Sistema de administración de encuestas</div>
            <div id="sliders">
                <figure>
                </figure>
            </div>
            <div class="botones m-3">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="">
                                <a class=" btn btn-primary botons" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                           
                        @endif
                        @if (Route::has('register'))
                            <!--<li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>-->
                        @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class=" btn btn-primary botons dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item btn btn-primary botons" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                    @endguest
                </ul>
            </div>


        </div>
        <div class="contenidos">
            @yield('content')
        </div>

    </div>

</body>
</html>
