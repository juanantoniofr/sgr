@extends('layout')
 
@section('title')
    SGR: edición de usuario 
@stop
 
@section('content')
<div class="container">

  <div class="row">
    {{$menuUsuarios or ''}} 
  </div>
  <div class="row">
      <div class="panel panel-info">
        
        <div class="panel-heading">
          <h3><i class="fa fa-pencil fa-fw"></i> Edición</h3>
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
          {{Form::open(array('method' => 'POST','route' => array('updateUser.html', $user->id),'role'=>'form'))}}
          
          <div class="form-group @if ($errors->has('username')) has-error @endif"> 
            {{ $errors->first('username', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            <p class="form-control-static"><b>Usuario Virtual:</b> {{$user->username}}</p>
          </div>
          <div class="form-group @if ($errors->has('estado')) has-error @endif">
            {{Form::label('estado', 'Estado')}}
            {{ $errors->first('estado', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>:message</span>') }}
            {{Form::select('estado', array('1' => 'Activa','0' => 'Desactiva'),$user->estado,array('class' => 'form-control'))}}
          </div>
          
          <div class="form-group @if ($errors->has('capacidad')) has-error @endif">     
            {{Form::label('capacidad', 'Rol')}}
            {{ $errors->first('capacidad', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>:message</span>') }}
            {{Form::select('capacidad', array('1' => 'Usuario (Alumnos)','2' => 'Usuario Avanzado (PDI/PAS Administración)','3' => 'Técnico (PAS-MAV)','4' => 'Administrador (SGR)','5' => 'Validador (Dirección/Decanato)','6' => 'Supervisor (E.E Unidad)'),$user->capacidad,array('class' => 'form-control'));}}
          </div>

          <div class="form-group @if ($errors->has('colectivo')) has-error @endif">     
            {{Form::label('colectivo', 'Colectivo')}}
            {{ $errors->first('colectivo', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>:message</span>') }}
            {{Form::select('colectivo', array('Alumno' => 'Alumno','PDI' => 'PDI','PAS' => 'PAS'),$user->colectivo,array('class' => 'form-control'));}}
          </div>
          <div class="form-group @if ($errors->has('caducidad')) has-error @endif">   
            {{Form::label('caducidad', 'Caducidad de la cuenta para sistema de reservas')}}
            {{ $errors->first('caducidad', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            {{Form::text('caducidad',date('d-m-Y',strtotime($user->caducidad)),array('class' => 'form-control'))}}
          </div>

          <div class="form-group @if ($errors->has('nombre')) has-error @endif">  
            {{Form::label('nombre', 'Nombre')}}
            {{ $errors->first('nombre', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            {{Form::text('nombre',$user->nombre,array('class' => 'form-control'))}}
          </div>
          <div class="form-group @if ($errors->has('apellidos')) has-error @endif">  
            {{Form::label('apellidos', 'Apellidos')}}
            {{ $errors->first('apellidos', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            {{Form::text('apellidos',$user->apellidos,array('class' => 'form-control'))}}
          </div>
              
            
          <div class="form-group @if ($errors->has('email')) has-error @endif">  
            {{Form::label('email', 'eMail')}}
            {{ $errors->first('email', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
            {{Form::text('email',$user->email,array('class' => 'form-control'))}}
          </div>
          
          <div class="form-group">
            <label for="observaciones"  class="control-label" >Observaciones (opcional): </label> 
            <div class = "">
                <textarea name="observaciones" class="form-control" rows="3">{{$user->observaciones}}</textarea>
            </div>
          </div> 

          <div class="form-group hidden">
            {{Form::text('id',$user->id,array('class' => 'form-control'))}}
          </div>
      
          <button type="submit" class="btn btn-primary">Salvar</button>

        {{Form::close()}}
      </div>
    </div>
        
    </div>
    <!-- /.panel-body -->
   </div>
   <!-- /.panel-default -->
    
  </div>
  <!-- /.row -->
</div>
@stop

@section('js')
  {{HTML::script('assets/js/admin.js')}}
@stop