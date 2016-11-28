@extends('layout')

@section('title')
  <title>Comprobante sgr</title>
@stop



@section('content')

   
  <div class="panel panel-default col-md-6 col-md-offset-3 well well-md" style="padding:0px;margin-top:40px;">
    <div class="panel-heading">
      <h4><i class="fa fa-check-square-o fa-fw"></i> Error de generación de comprobante </h4>
    </div>
      
    <div class="panel-body" >
    
      
        
        

  
  <div class="alert alert-danger" role="alert">
    <p>No existe reserva </p>
    
    
    
    
  </div>
  <br /><br />
  <div class='col-md-12'>
    <a class="btn btn-primary col-md-4 col-md-offset-4" href="{{route('calendarios.html')}}">Volver al calendario</a>
  </div>
                  
    <br /><br />

   </div><!-- /.panel-body -->
      
   </div> <!-- /.panel-default -->

@stop