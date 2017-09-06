<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv=Content-Language content=pt-br>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/simple-line-icons/css/simple-line-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap-material-design/css/bootstrap-material-design.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-material-design/css/material-fullpalette.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-material-design/css/ripples.min.css') }}">
 
    <!-- Plots CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/chartist/chartist.min.css') }}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('stylesheets/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('stylesheets/theme-custom.css') }}">
    <!-- Head Libs -->
    <script src="{{ asset('vendor/modernizr/modernizr.js') }}"></script>
    @yield('js_head')
    
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-fixed-top navbar-primary" role="navigation">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo2.png') }}" alt="" width="140px">            
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav top-nav">
                       <li><a href="#">&nbsp;</a></li>
                       <li><a href="#"><i class="fa fa-home"></i>&nbsp;Agence</a></li>
                       <li><a href="#"><i class="fa fa-check-square-o"></i>&nbsp;Projetos</a></li> 
                       <li><a href="#"><i class="fa fa-pencil-square-o"></i>&nbsp;Administrativo</a></li>
                       <li><a href="{{ route('comercial.index') }}"><i class="fa fa-users"></i>&nbsp;Comercial</a></li>
                       <li><a href="#"><i class="fa fa-home"></i>&nbsp;Financeiro</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}"><i class="fa fa-sign-in"></i>&nbsp;Entrar</a></li>
                            <li><a href="{{ route('register') }}"><i class="fa fa-user-plus"></i>&nbsp;Registro</a></li>
                        @else
                            <li><a href="{{ route('register') }}">Usuário</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    Sair
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('vendor/nanoscroller/nanoscroller.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/jquery-maskedinput/jquery.maskedinput.js') }}"></script>
    <script src="{{ asset('vendor/chartist/chartist.js') }}"></script>  
    <script src="{{ asset('js/ui-elements/examples.charts.js') }}"></script>
    <script src="{{ asset('bootstrap-material-design/js/material.min.js') }}"></script>
    <script src="{{ asset('bootstrap-material-design/js/ripples.min.js') }}"></script>
    <script>
        $.material.init();
    </script>

    <!-- Theme Initialization Files -->
    <script src="{{ asset('js/theme.js') }}"></script>
    
    <!-- Scripts del original -->
    @yield('js')
    
    <script src="{{ asset('js/theme.init.js') }}"></script>
    
</body>
</html>
