@extends('layout')

@section('title')
    SGR: Añadir recurso
@stop
 
@section('content')
<div class="container">
  
  <div class="row">
    {{$menuRecursos or ''}}
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        
        <div class="panel-heading">
         <h4><i class="fa fa-plus fa-fw"></i> Añadir Recurso</h4>
        </div>
        
        <div class="panel-body">
          
          <div class="row">

            
            @if (Session::has('message'))
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ Session::get('message') }}
                </div>
            @endif

            
            <div class="col-lg-12">
            {{Form::open(array('method' => 'POST','route' => 'postAddRecurso','role' => 'form'))}}
                         
              <div class="form-group">  
                {{Form::label('id_lugar', 'Identificador de Lugar')}}
                {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group">
                {{Form::label('nombre', 'Nombre')}}
                {{ $errors->first('nombre', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group">
                {{Form::label('idgrupo', 'Grupo')}}
                {{ $errors->first('nuevogrupo', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                <select name="idgrupo" class="form-control">
                  <option placeholder="" selected value="0">seleccione grupo....</option>
                  @foreach($recursos as $recurso)
                    <option value = "{{$recurso->grupo_id}}">{{$recurso->grupo}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <a type="button" id="grupoNuevo" class="btn btn-info btn-sm ">Añadir nuevo grupo <i class="fa fa-plus fa-fw"></i></a>
              </div>
              
              <div style="display:none" id="nuevoGrupo" class="form-group">
                  {{Form::label('nuevogrupo', 'Nuevo grupo')}}
                  {{Form::text('nuevogrupo','',array('class' => 'form-control','placeholder' => 'Escriba el nombre del nuevo grupo...'))}}
              </div>
              

              <div class="form-group">  
                {{Form::label('tipo', 'Tipo de recurso')}}
                {{Form::select('tipo', array('espacio' => 'Espacio', 'equipo' => 'Equipo', 'puesto' => 'Puesto'),'espacio',array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
                {{Form::select('modo', array('1' => 'Con Validación', '0' => 'Sin Validación'),'0',array('class' => 'form-control'))}}
              </div>

              <div class="form-group">  
                {{Form::label('descripcion', 'Descripcion')}}
                {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group"> 
                <label>Disponible para los colectivos:</label><br />
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="1" checked="true"> Alumnos
                </label>
                 <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="2" checked="true"> PAS
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]"  value="3" checked="true"> PDI
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="5" checked="true"> Validadores
                </label>
              </div>
            
              <div class="form-group"> 
                <button type="submit" class="btn btn-primary">Añadir</button>
              </div>    
            {{Form::close()}}
            </div>
          </div>
        
        </div>
        <!-- /.panel-body -->
      </div>
      <!-- /.panel-default -->
    </div>
    <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
</div><!-- /.container -->
@stop

@section('js')
  {{HTML::script('assets/js/admin.js')}}
@stop