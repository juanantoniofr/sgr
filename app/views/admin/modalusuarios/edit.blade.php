<!-- modal edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-2" role="dialog" aria-labelledby="modalEditUserLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditUser">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title"><i class="fa fa-edit fa-fw"></i> Edición de usuario</h3>
        </div><!-- ./modal-header -->

        <div class="modal-body">
          <div class="alert alert-danger text-center" role="alert" style="display:none" id="dangerEdit">Revise el formulario para corregir errores.... </div>

          <div class="form-group" id="fg_username"> 
            <span class="text-danger dataError" style="display:none">
              <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id="error_username"></span>
            </span>
            <p class="form-control-static"><b>Usuario Virtual:</b> <b><span class="text-success" id="username"></span></b></p>
          </div>

          <div class="form-group" id="editmodal_estado">     
            {{Form::label('estado', 'Estado de la cuenta:')}}
            <span id="editmodal_estado_error" style="display:none" class="text-danger dataError"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>
            {{Form::select('estado', array('1' => 'Activa', '0' => 'Desactiva'),'1',array('class' => 'form-control','id' => 'select_estado'));}}
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputcapacidad">      
            {{Form::label('capacidad', 'Rol')}}<span id="m_editusuario_textError_capacidad" class="text-danger modal_spantexterror"></span>
            {{Form::select('capacidad', Config::get('string.capacidades') , '' ,array('class' => 'form-control'));}}
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputcolectivo">  
            {{Form::label('colectivo', 'Colectivo')}}<span id="m_editusuario_textError_colectivo" class="text-danger modal_spantexterror"></span>
            {{Form::select('colectivo', Config::get('string.colectivos'),'',array('class' => 'form-control'))}}
        </div>
          
          <div class="form-group" id="editmodal_caducidad">   
            {{Form::label('caducidad', 'Caducidad para SGR')}}
            <span id="editmodal_caducidad_error" style="display:none" class="text-danger dataError"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('caducidad',date('d-m-Y',strtotime('+1 year')),array('class' => 'form-control datepicker','id' => 'datepickerUserEdit'))}}                
          </div>

          <div class="form-group" id="editmodal_nombre">  
            {{Form::label('nombre', 'Nombre')}}
            <span id="editmodal_nombre_error" style="display:none" class="text-danger dataError"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
          </div>
                  
          <div class="form-group" id="editmodal_apellidos">  
            {{Form::label('apellidos', 'Apellidos')}}
              <span id="editmodal_apellidos_error" style="display:none" class="text-danger dataError"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
          </div>
                  
          <div class="form-group" id="editmodal_email">  
            {{Form::label('email', 'eMail')}}
            <span id="editmodal_email_error" style="display:none" class="text-danger dataError"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
          </div>

          <div class="form-group" id="editmodal_observaciones">
              <label for="observaciones">Observaciones</label> 
              <textarea class="form-control" name="observaciones" rows="5"  value=""></textarea>
              
            </div>
        </div><!-- ./modal-body -->

        <input type="hidden" name="id" value="" />
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id ="modaleditUser"><i class="fa fa-save fa-fw"></i> Salvar</button>
        </div><!-- ./modal-footer -->
      </form>
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
</div><!-- #/modalEditUser -->