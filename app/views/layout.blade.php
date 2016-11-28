<!doctype html>
<html lang="es-ES">
<head>
   <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Aplicación de reserva y gestión de espacios de la Facultad de Comunicación, Universidad de Sevilla">
    <meta name="author" content="Juan Antonio Fernández, E.E UNITIC Fcom">
    <link rel="icon" href="">
   
    <title>@yield('title')</title>
    
    {{HTML::style('assets/css/bootstrap.css')}}
    {{HTML::style('assets/css/sb-admin-2.css')}}
    {{HTML::style('assets/css/datepicker.css')}}
    {{HTML::style('assets/css/normalize.css')}}
    {{HTML::style('assets/css/stilo.css')}}
    <!--{{HTML::style('assets/font-awesome-4.1.0/css/font-awesome.min.css')}}-->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
    @yield('head')
    
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-default" role="navigation">
   <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Desplegar navegación</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{route('loginsso')}}" title="Inicio"><i class="fa fa-home fa-fw"></i> SGR: UNITIC fcom</a>
    </div><!-- /.navbar-header -->
    
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav navbar-right" >

          {{$dropdown or ''}}
          <li><a href="{{asset('assets/R_mav.pdf')}}" target="_blank" title="Reglamento Servicio Medios Audiovisuales"><i class="fa fa-book fa-fw"></i> Normativa</a></li>

          <li><a href="{{route('ayuda')}}" title="Ayuda"><i class="fa fa-support fa-fw"></i> Ayuda</a></li>

          @if (Cas::isAuthenticated() && Auth::check())
          <li><a href="{{route('contactar')}}" title="Contacto"><i class="fa fa-envelope fa-fw"></i> Contacto</a></li>
          <li><a href="{{URL::route('logout')}}" title="Salir"><i class="fa fa-sign-out fa-fw"></i> Logout ({{Auth::user()->nombre}} {{Auth::user()->apellidos}})</a></li>

          @endif
        </ul>
    </div> 
    </div>
  </nav>

 
  {{$sidebar or ''}}
 
  @yield('content')
  

<div class ="row col-md-12  col-xs-12 text-right" id ="credits">
  <div class="">
    <a href="http://fcom.us.es" alt="Facultad de Comunicación"><img src = "{{ asset('assets/img/logofcom.png') }}"></a>&nbsp;&nbsp;&nbsp;<a href="http://www.us.es" alt="Universidad de Sevilla"><img src = "{{ asset('assets/img/logo_us.jpg') }}"></a>
  </div>
           
  <div class="">
    Developed by UNITIC Facultad de Comunicación. Universidad de Sevilla.
  </div>
</div>

@yield('modal')
<!-- scripts -->

{{HTML::script('assets/js/jquery-1.11.0.js')}}
{{HTML::script('assets/js/jquery-ui.js')}}
{{HTML::script('assets/js/bootstrap.min.js')}}

@yield('js')
<!-- scripts -->  
</body>
</html>