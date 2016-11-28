@extends('layout')
 
@section('title')
    SGR:: Añadir nuevo usuario
@stop
 
@section('content')
<div class="container">
   <h2 class=""><i class="fa fa-users fa-fw"></i> Gestión de Usuarios</h2>
   <div class="row">
   <form class="navbar-form navbar-left">
            <div class="form-group ">
                <a href="{{route('adduser')}}" class="active btn btn-danger" title="Añadir nuevo usuario"><i class="fa fa-plus fa-fw"></i> Añadir nuevo usuario</a>
            </div>
            <div class="form-group ">
                <a href="{{route('users',array('veractivados' => 1))}}" class="btn btn-primary" title="Listar usuarios"><i class="fa fa-list fa-fw"></i> Listar cuentas activas</a>
            </div>                            
                
        </form>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        
        <div class="panel-heading">
         <h4><i class="fa fa-plus fa-fw"></i> Añadir</h4>
        </div>
        
        <div class="panel-body">
          
          <div class="row">

            
            @if (Session::has('message'))
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ Session::get('message') }}
                </div>
            @endif

            @if ($errors->has())
                <div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <p>Formulario con errores. Revise los campos requeridos.....</p>
                </div>
            @endif
            
            <div class="col-lg-12">
            {{Form::open(array('method' => 'POST','route' => 'post_addUser','role' => 'form'))}}

              <div class="form-group">  
                {{Form::label('username', 'UVUS (Usuario Virtual Universidad de Sevilla)')}}
                {{ $errors->first('username', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
              </div>
             
              <div class="form-group">     
                {{Form::label('capacidad', 'Rol')}}
                {{Form::select('capacidad', array('1' => 'Usuario (Alumnos)', '2' => 'Usuario Avanzado (PDI/PAS Administración)','3' => 'Técnico (PAS-MAV)','4' => 'Administrador (SGR)', '5' => 'Validador (Dirección/Decanato)','6' => 'Supervisor (E.E Unidad)'),'Usuario (Alumnos)',array('class' => 'form-control'));}}
                {{ $errors->first('capacidad', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>:message</span>') }}
              </div>
              <div class="form-group">  
                {{Form::label('colectivo', 'Colectivo')}}
                {{Form::select('colectivo', array('Alumno' => 'alumno','PAS' => 'PAS','PDI' => 'PDI'),'Alumno',array('class' => 'form-control'))}}
              </div>
              <div class="form-group">   
                {{Form::label('caducidad', 'Caducidad de la cuenta para sistema de reservas')}}
                {{ $errors->first('caducidad', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                {{Form::text('caducidad',date('d-m-Y',strtotime('+1 year')),array('class' => 'form-control'))}}                
              </div>
              <div class="form-group">  
                {{Form::label('nombre', 'Nombre')}}
                {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
              </div>
              <div class="form-group">  
                {{Form::label('apellidos', 'Apellidos')}}
                {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
              </div>
              
            
              <div class="form-group">  
                {{Form::label('email', 'eMail')}}
                {{ $errors->first('email', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
              </div>
              
            <button type="submit" class="btn btn-primary">Salvar</button>
            {{Form::close()}}
            </div>
          </div>
        
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
</div><!-- /.container -->
@stop

@section('js')
  {{HTML::script('assets/js/admin.js')}}
@stop