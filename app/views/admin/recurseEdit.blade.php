@extends('layout')

@section('title')
    SGR: Editar recurso
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
         <h4><i class="fa fa-pencil fa-fw"></i> Editar Recurso</h4>
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
            {{Form::open(array('method' => 'POST','route' => 'postEditRecurso','role' => 'form'))}}
                         
              <div class="form-group">  
                {{Form::label('id_lugar', 'Identificador de Lugar')}}
                {{Form::text('id_lugar',$recurso->id_lugar,array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group">  
                {{Form::label('nombre', 'Nombre')}}
                {{ $errors->first('nombre', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                {{Form::text('nombre',$recurso->nombre,array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('idgrupo', 'Grupo')}}
                {{ $errors->first('nuevogrupo', '<span class="text-danger"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> :message</span>') }}
                <select name="idgrupo" class="form-control">
                  @foreach($recursos as $item)
                    <option value = "{{$item->grupo_id}}" @if ($item->grupo_id == $recurso->grupo_id)  selected @endif @>{{$item->grupo}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <a type="button" id="grupoNuevo" class="btn btn-info btn-sm ">Cambiar a nuevo grupo <i class="fa fa-plus fa-fw"></i></a>
              </div>
              
              <div style="display:none" id="nuevoGrupo" class="form-group">
                  {{Form::label('nuevogrupo', 'Nuevo grupo')}}
                  {{Form::text('nuevogrupo','',array('class' => 'form-control','placeholder' => 'Escriba el nombre del nuevo grupo...'))}}
              </div>
              

              <div class="form-group">  
                {{Form::label('tipo', 'Tipo de recurso')}}
                {{Form::select('tipo', array('espacio' => 'Espacio', 'equipo' => 'Equipo', 'puesto' => 'Puesto'),$recurso->tipo,array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
                {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),$modo,array('class' => 'form-control'))}}
              </div>

              <div class="form-group">  
                {{Form::label('descripcion', 'Descripcion')}}
                {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group"> 
                <label>Disponible para los colectivos:</label><br />
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="1" @if (strpos($capacidades,'1') !== false)checked="true" @endif> Alumnos
                </label>
                 <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="2" @if (strpos($capacidades,'2') !== false)checked="true" @endif> PAS
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]"  value="3" @if (strpos($capacidades,'3') !== false)checked="true" @endif> PDI
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="5" @if (strpos($capacidades,'5') !== false)checked="true" @endif> Validadores
                </label>
                <!--<label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="4" checked="true"> Admin 
                </label>
                -->
              </div>
              <div class="form-group hidden">
               {{Form::text('id',$recurso->id,array('class' => 'form-control'))}}
              </div>
              <div class="form-group"> 
                <button type="submit" class="btn btn-primary">Salvar</button>
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