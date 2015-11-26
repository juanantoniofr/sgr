<div class="modal fade" id="modalEditRecurso" tabindex="-2" role="dialog" aria-labelledby="modalEditRecursoLabel">
{{Form::open(array('method' => 'POST','role' => 'form','id'=>'editRecurso'))}}          
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-institution fa-fw"></i> Editar recurso (espacio/puesto/equipo)</h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
              <div class="alert alert-danger" role="alert" style="display:none" id="aviso">Revise el formulario para corregir errores.... </div>

              <div class="form-group">  
                {{Form::label('id_lugar', 'Identificador de Lugar')}}
                {{Form::text('id_lugar','',array('class' => 'form-control','id' => 'id_lugar'))}}
              </div>
              
              <div class="form-group">  
                {{Form::label('nombre', 'Nombre')}}
                <div id="error_nombre" class="text-danger hidden" ><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b id="text_error_nombre"></b></div>
                {{Form::text('nombre','',array('class' => 'form-control'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('idgrupo', 'Seleccione Agrupación o..')}}
                <a type="button" id="grupoNuevo_edit" class="" href=""><i class="fa fa-plus fa-fw"></i> Cree una nueva</a>
                
                <div id="nuevoGrupo_edit" class="form-group" style="display:none">
                  {{Form::label('nuevogrupo', 'Nuevo grupo')}}
                  {{Form::text('nuevogrupo','',array('class' => 'form-control','placeholder' => 'Escriba el nombre del nuevo grupo...'))}}
                </div>

                <div id="error_nuevogrupo" class="text-danger hidden" ><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b id="text_error_nuevogrupo"></b></div>
                <select name="idgrupo" class="form-control" id="select_grupo_id">
                  @foreach($recursos as $item)
                    <option value = "{{$item->grupo_id}}" >{{$item->grupo}}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="form-group">  
                {{Form::label('tipo', 'Tipo de recurso')}}
                {{Form::select('tipo', array('espacio' => 'Espacio', 'equipo' => 'Equipo', 'puesto' => 'Puesto'),'',array('class' => 'form-control', 'id' => 'select_tipo'))}}
              </div>
            
              <div class="form-group">  
                {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
                {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),0,array('class' => 'form-control','id' => 'select_modo'))}}
              </div>

              <div class="form-group">  
                {{Form::label('descripcion', 'Descripción')}}
                {{Form::textarea('descripcion',Input::old('descripcion'),array('class' => 'form-control', 'id' => 'editdescripcion'))}}
              </div>
              
              <div class="form-group"> 
                
                <label>Disponible para el Rol:</label><br />
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="1" class="check_colectivos"> Alumno
                </label>
                 <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="2" class="check_colectivos"> PDI & PAS-Administración
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]"  value="3" class="check_colectivos"> PAS-Técnico (MAV)
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="5" class="check_colectivos"> Validador 
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" name = "acceso[]" value="6" class="check_colectivos"> Supervisor (EE MAV)
                </label>
              </div>
              
              <div class="form-group hidden">
               {{Form::text('id','',array('class' => 'form-control','id' => 'id'))}}
              </div>
      </div><!-- ./modal-body -->      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="btnEditarRecurso"><i class="fa fa-save fa-fw"></i> Salvar cambios</button>
      </div> <!-- ./modal-footer -->
  
  </div><!-- ./modal-content --> 
</div><!-- /.modal-dialog -->
     
  {{Form::close()}}
</div><!-- #/modalEditRecurso -->