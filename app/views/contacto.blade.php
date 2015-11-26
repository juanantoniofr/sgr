@extends('layout')

@section('head')
<style>
  .container{margin-top:10px;}
</style>
@stop
@section('title')
    SGR: formulario de contacto
@stop

@section('content')
<div class="container">


<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      
      <div class="panel-heading">
       <h4><i class="fa fa-envelope fa-fw"></i> Fromulario de contacto</h4>
      </div>

      <div class="panel-body">
        
          @if (Session::has('message'))
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ Session::get('message') }}
              </div>
          @endif


          {{Form::open(array('method' => 'POST','route' => 'enviaformulariocontacto','role' => 'form'))}}


            <div class="form-group">  
              {{Form::label('titulo', 'Título')}}
              {{Form::text('titulo','',array('class' => 'form-control'))}}
              {{ $errors->first('titulo', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            </div>
           
            <div class="form-group">     
              {{Form::label('texto', 'Texto')}}
              {{Form::textarea('texto','',array('class' => 'form-control'));}}
              {{ $errors->first('texto', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>:message</span>') }}
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar</button>
          {{Form::close()}}
           <div class="alert alert-warning" role="alert">
            Puedes usar este formulario para comunicar cualquier incidencia, queja, sugerencia..., o cualquier otra cuestión que consideres oportuna, en relación con el funcionamiento de la aplicación SGR.
          </div>
      
      </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
  </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
</div><!-- /.container -->
@stop