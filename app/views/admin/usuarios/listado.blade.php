<!-- marca branch master2 -->@extends('layout')
<!-- :( -->
@section('title')
  SGR: Gestión de Usuarios
@stop

@section('content')
<div class="container">

  <div class="row">
    {{$menuUsuarios or ''}}
  </div>

  <div class="row">
    <div class="panel panel-info">
      <div id = "espera" style="display:none"></div>            
        
      <div class="panel-heading"><h2><i class="fa fa-list fa-fw"></i> Listado de usuarios</h2></div>

      <div class="panel-body">
                        
        <div id="msg"></div>    
            
            {{$tableUsuarios or ''}}
            {{$links         or ''}}   
        </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
  </div><!-- /.row -->    
</div><!-- /.container -->

{{ $modalAddUser     or '' }}
{{ $modalDeleteUser  or '' }}
{{ $modalEditUser    or '' }}



@stop
@section('js')
  {{  HTML::script('assets/js/user.js') }}
  {{  HTML::script('assets/js/comun.js') }}
@stop