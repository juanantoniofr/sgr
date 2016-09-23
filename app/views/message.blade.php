@extends('layout')

@section('title')
    SGR: {{ $pagetitle or '' }}
@stop


@section('content')

   
  <div class="panel panel-{{$alertLevel or 'info'}} col-md-6 col-md-offset-3 well well-md" style="padding:0px;">
    <div class="panel-heading">
      <h4><i class="fa fa-info fa-fw"></i> {{$paneltitle or ''}} </h4>
    </div>
      
    <div class="panel-body" >

      <p class="text-{{$alertLevel or ''}} text-center">{{ $msg or ''}}</p>
        
      <p class="text-center">
       
          <a class="btn btn-warning" href="{{route('logout')}}">Cerrar sesión <abr title="Single Sign On">SSO</abr></a>
          {{ $btnredirect or '' }}
        </p>
    
    </div><!-- /.panel-body -->
      
  </div> <!-- /.panel-danger -->

@stop