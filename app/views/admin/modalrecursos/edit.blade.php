<!-- / Modal edit recurso  :) -->
<div class="modal fade myModal-lg" id="m_editrecurso" tabindex="-5" role="dialog" aria-hidden="true" aria-labelledby="modalEditRecursoLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_editrecurso'))}}          
   <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info"><i class="fa fa-pencil fa-fw"></i> Editar recurso <span class = "text-primary" id="m_editrecurso_title_nombre"></span></h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="modal_msgError alert alert-danger text-center" role="alert" style="display:none;margin:10px 0" id="fm_editrecurso_textError">Formulario con errores</div>
          
        <div class="form-group" id="m_editrecurso_id">
          <span id="m_editrecurso_textError_id" class="text-danger modal_spantexterror text-center"></span>
        </div>
        
        <div class="form-group" id="fm_editrecurso_inputid_lugar">  
          {{Form::label('id_lugar', 'Identificador de Lugar')}}
          <span id="m_editrecurso_textError_id_lugar" class="text-danger modal_spantexterror text-center"></span>
          {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="fm_editrecurso_inputnombre">
          {{Form::label('nombre', 'Nombre')}}
          <span id="m_editrecurso_textError_nombre" class="text-danger modal_spantexterror text-center"></span>
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
        
        
        <div class="form-group" id="fm_editrecurso_inputPadre_id">
          {{Form::label('padre_id', 'Incluido en:')}}
          <span id="m_editrecurso_textError_padre_id" class="text-danger modal_spantexterror text-center"></span>
          <select name="padre_id" class="form-control" id="fm_editrecurso_optionsPadre">
           
          </select>
        </div>

        <div class="form-group " id="fm_editrecurso_inputtipo">  
            {{Form::label('tiposelect', 'Tipo')}}
            <span id="m_editrecurso_textError_tipo" class="text-danger modal_spantexterror text-center"></span>
            {{Form::select('tiposelect', Config::get('options.tipoRecursos'),'',array('class' => 'form-control', 'disabled' => 'disabled'))}}            
        </div>
        <div class="form-group hidden">
          {{Form::text('tipo','',array('class' => 'form-control'))}}
        </div>
        
        
        <div class="form-group" id="fm_editrecurso_inputmodo">  
          {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
          <span id="m_editrecurso_textError_modo" class="text-danger modal_spantexterror text-center"></span>
          {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
        </div>

        <div class="form-group">  
          {{Form::label('descripcion', 'Descripcion')}}
          {{Form::textarea('descripcion',Input::old('descripcion'),array('class' => 'form-control','id' => 'fm_editrecurso_inputdescripcion'))}}
        </div>
            
        <div class="form-group" id="fm_editrecurso_inputrol"> 
          <label>Disponible para el Rol:</label>
          <span id="m_editrecurso_textError_roles" class="text-danger modal_spantexterror text-center"></span>
          <br />
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="1" id="fm_editrecurso_roles1"> Alumno
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="2" id="fm_editrecurso_roles2"> PDI & PAS-Administración
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]"  value="3" id="fm_editrecurso_roles3"> PAS-Técnico (MAV)
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="5" id="fm_editrecurso_roles5"> Validador 
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="6" id="fm_editrecurso_roles6"> Supervisor (EE MAV)
          </label>
        </div>


        
        <div class="form-group hidden">
          {{Form::text('id','',array('class' => 'form-control'))}}
        </div> 
      </div><!-- ./modal-body -->      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_editrecurso_save">
          <i class="fa fa-save fa-fw"></i> Salvar cambios
        </button>
      </div> <!-- ./modal-footer -->
  
  </div><!-- ./modal-content --> 
</div><!-- /.modal-dialog -->
     
  {{Form::close()}}
</div><!-- #/modalEditRecurso -->