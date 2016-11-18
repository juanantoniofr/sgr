<!-- / Modal add recurso (Espacio|tipoequipo|puesto|equipo) :) -->
<div class="modal fade myModal-lg" id="m_addrecurso" tabindex="-4" role="dialog" aria-hidden="true" aria-labelledby="modalAddRecursoLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addrecurso'))}}
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info"><i class="fa fa-institution fa-fw"></i> XXAñadir nuevo <b><span id="m_addrecurso_title_tiporecursoAañadir"></span></b> en: <span class = "text-primary" id="m_addrecurso_title_nombre"></span></h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="modal_msgError alert alert-danger text-center" role="alert" style="display:none;margin:10px 0" id="fm_addrecurso_textError">Formulario con errores</div>

        <div class="form-group" id="m_addrecurso_inputid_lugar">  
          {{Form::label('id_lugar', 'Identificador de Lugar')}}
          <span id="m_addrecurso_textError_id_lugar" class="text-danger modal_spantexterror text-center"></span>
          {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="m_addrecurso_inputnombre">
          {{Form::label('nombre', 'Nombre')}}
          <span id="m_addrecurso_textError_nombre" class="text-danger modal_spantexterror text-center"></span>
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="m_addrecurso_inputcontenedor_id">
          <label id="fm_addrecurso_labelContenedor_id" for="contenedor_id"></label>
          <span id="m_addrecurso_textError_contenedor_id" class="text-danger modal_spantexterror text-center"></span>
          <select name="contenedor_id" class="form-control" id="fm_addrecurso_optionsContenedor">
           <option id="fm_addrecurso_optionsContenedor_id" value ="" selected="selected"></option>
          </select>
        </div>
        
        <div class="form-group" id="m_addrecurso_inputtipo">
          <label for="tipo">Tipo</label>
          <span id="m_addrecurso_textError_tipo" class="text-danger modal_spantexterror text-center"></span>
          <select name="tipo" class="form-control" id="fm_addrecurso_optionsTipo">
           <option id="fm_addrecurso_optionstipo" value ="" selected="selected"></option>
          </select>
        </div>

        <div class="form-group" id="m_addrecurso_inputmodo">  
          {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
          <span id="m_addrecurso_textError_modo" class="text-danger modal_spantexterror text-center"></span>
          {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
        </div>

        <div class="form-group" id="m_addrecurso_inputdescripcion">  
          {{Form::label('descripcion', 'Descripcion')}}
          <span id="m_addrecurso_textError_descripcion" class="text-danger modal_spantexterror text-center"></span>
          {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control','id' => 'fm_addrecurso_inputdescripcion'))}}
        </div>
            
        <div class="form-group" id="fm_addrecurso_inputrol"> 
          <label>Disponible para reserva para los usuarios con Rol:</label><br />
          <span id="m_addrecurso_textError_roles" class="text-danger modal_spantexterror text-center"></span>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="1" checked="true"> Alumno
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="2" checked="true"> PDI & PAS-Administración
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]"  value="3" checked="true"> PAS-Técnico (MAV)
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="5" checked="true"> Validador 
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="6" checked="true"> Supervisor (EE MAV)
          </label>
        </div>

        <div class="form-group hidden">{{Form::text('tipopadre','',array('class' => 'form-control'))}}</div>

      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_addrecurso_save">
          <i class="fa fa-save fa-fw"></i> Salvar cambios
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->