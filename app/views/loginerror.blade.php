@extends('layout')

@section('title')
    SGR: mensaje inicio de sesión
@stop


@section('content')

   
  <div class="panel panel-danger col-md-6 col-md-offset-3 well well-md" style="padding:0px;margin-top:40px;min-height:300px">
    <div class="panel-heading">
      <h4><i class="fa fa-check-square-o fa-fw"></i> Error de Inicio de sesión </h4>
    </div>
      
    <div class="panel-body" >

    <div class="alert alert-warning" role="alert">
      <p>{{ $msg }}</p>
      <br />
      @if (Auth::check() || Cas::isAuthenticated())<p class="text-center"><a class="btn btn-primary" href="{{route('logout')}}">Cerrar sesión</a></p>@endif
    </div>
  
   </div><!-- /.panel-body -->
      
  </div> <!-- /.panel-danger -->

@stop