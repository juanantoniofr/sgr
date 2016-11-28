@extends('layout')

@section('title')
    SGR: mesanje del sistema
@stop



@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <h3><i class="fa fa-comment fa-fw"></i> Mensaje </h4></h3>
    </div> 
  </div>

  <div class="row">
    <div class="panel panel-danger">
     
      <div class="panel-heading">
        <h4><i class="fa fa-check-square-o fa-fw"></i> {{$title}} </h4>
      </div>
        
      <div class="panel-body" >
        <div class="alert alert-warning" role="alert">
          <div>{{ $msg }}</div>
          
        </div>
        <div class="text-center"><a class="btn btn-primary" href="{{route('wellcome')}}"><i class="fa fa-home fa-fw"></i> Volver a Inicio</a></div>
      
      </div>
      

          
      
      
        
    </div> <!-- /.panel-danger -->
  </div><!-- /.row -->

</div> <!-- /.container -->
@stop