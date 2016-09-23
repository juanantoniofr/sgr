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
        <!-- /.col-lg-12 -->
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
            <div class="list-group" data-username="{{$notificacion->source}}" data-idnotificacion="{{$notificacion->id}}" id="{{$notificacion->id}}" data-iduser = "{{$notificacion->user->id}}">
              <a href="#"   class="list-group-item" title="Activar" >
                {{$notificacion->msg }}
              </a>
            </div><!-- /.list-group -->
          @endforeach
        @else
          <div class="alert alert-warning text-center" role="alert">No hay peticiones pendientes</a>
        @endif
      </div><!-- /.panel-body -->
    </div><!-- /.panel -->
  </div>

</div>
<div id = "espera" style="display:none"></div>

{{ $modalvalidaRegistroUser or ''}}

@stop


@section('js')
<script src="../assets/js/notificaciones.js"></script>
@stop

