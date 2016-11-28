<div class="modal fade" id="modalAddRecurso" tabindex="-1" role="dialog" aria-labelledby="modalAddRecursoLabel">
  {{Form::open(array('method' => 'POST','route' => 'postAddRecurso','role' => 'form','id'=>'nuevoRecurso'))}}
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-institution fa-fw"></i> Añadir nuevo recurso (espacio/puesto/equipo)</h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
              <div class="alert alert-danger" role="alert" style="display:none" id="aviso">Revise el formulario para corregir errores.... </div>
                         
              <div class="form-group">  
                {{Form::label('id_lugar', 'Identificador de Lugar')}}
                {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group" id="fgnombre">
                {{Form::label('nombre', 'Nombre')}}
                <span id="nombre_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
                {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
              </div>
              
              <div class="form-group" id="fgnuevogrupo">
                {{Form::label('idgrupo', 'Seleccione Agrupación o..')}}
                <a type="button" id="grupoNuevo" class="" href=""><i class="fa fa-plus fa-fw"></i> Cree una nueva</a>
                 <div style="display:none" id="nuevoGrupo" class="form-group">
                  {{Form::label('nuevogrupo', 'Nuevo grupo')}}
                  {{Form::text('nuevogrupo','',array('class' => 'form-control','placeholder' => 'Escriba el nombre del nuevo grupo...'))}}
                </div>
                
                <span id="nuevogrupo_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
                <select name="idgrupo" class="form-control">
                  <option placeholder="" selected value="0">seleccione agrupación....</option>
                  @foreach($grupos as $grupo)
                    <option value = "{{$grupo->grupo_id}}">{{$grupo->grupo}}</option>
                  @endforeach
                </select>
              </div>
              <!--
              <div class="form-group">
                <a type="button" id="grupoNuevo" class="btn btn-info btn-sm ">Añadir nuevo grupo <i class="fa fa-plus fa-fw"></i></a>
              </div>
              -->
             
              

              <div class="form-group">  
                {{Form::label('tipo', 'Tipo de recurso')}}
                {{Form::select('tipo', array('espacio' => 'Espacio', 'equipo' => 'Equipo', 'puesto' => 'Puesto'),'espacio',array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
                {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
              </div>

              <div class="form-group">  
                {{Form::label('descripcion', 'Descripcion')}}
                {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group"> 
                <label>Disponible para el Rol:</label><br />
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="1" checked="true"> Alumno
                </label>
                 <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="2" checked="true"> PDI & PAS-Administración
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]"  value="3" checked="true"> PAS-Técnico (MAV)
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="5" checked="true"> Validador 
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="6" checked="true"> Supervisor (EE MAV)
                </label>
              </div>
            
                      
      </div><!-- ./modal-body --> 
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="btnSalvarRecurso"><i class="fa fa-save fa-fw"></i> Salvar</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->



