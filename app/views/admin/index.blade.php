@extends('layout')

@section('title')
    Acceso para administradores: Inicio
@stop

@section('content')
<div class="container">

  <div class="row">
    <div class="col-lg-12">
      <h2 class="page-header"><i class="fa fa-dashboard fa-fw"></i> Escritorio</h2>
    </div>
  </div>


  <div class="row">
        
    <div class="panel panel-info">

      <div class="panel-heading">
        <h2><i class="fa fa-comment fa-fw"></i> Peticiones de alta </h2>   
      </div><!-- /.panel-heading -->
                
     
      <div id="msg"></div>

      <div class="panel-body" id='listTask'>
        
        @if($notificaciones->count()>0)
          @foreach($notificaciones as $notificacion)
            <div class="list-group" data-defaultcaducidad="{{Config::get('options.fecha_caducidadAlumnos')}}" data-uvus = "{{$notificacion->source}}" data-idnotificacion = "{{$notificacion->id}}">
                <a href="#" class="list-group-item" title="Activar" data-toggle="modal" > <!--data-target="#modalUser"-->
                  {{$notificacion->msg }}
                </a>
            </div>
          @endforeach
        @else
          <div class="alert alert-warning text-center" role="alert">No hay peticiones pendientes</div>
        @endif
      </div><!-- /.panel-body -->
    </div><!-- /.panel -->
  </div><!-- /.row -->



  <div id = "espera" style="display:none"></div>


</div><!-- /.container -->

<!-- modal's -->
{{ $modalvalidaRegistroUser or ''}}

@stop

@section('js')

{{HTML::script('assets/js/notificaciones.js')}}


@stop

