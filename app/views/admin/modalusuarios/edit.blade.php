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
          <div class="alert alert-danger text-center modal_msgError" role="alert" style="display:none;margin:10px 0" id="m_editusuario_msgError">Formulario con errores</div>

          <div class="form-group" id="fg_username"> 
            <p class="form-control-static"><b>UVUS:</b> <span id="fm_editUser_username"></span></p>
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputestado">     
            {{Form::label('estado', 'Estado de la cuenta:')}}
            <span id="m_editusuario_textError_estado" class="text-danger modal_spantexterror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>
            {{Form::select('estado', Config::get('string.estados'),'',array('class' => 'form-control'));}}
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputcapacidad">      
            {{Form::label('capacidad', 'Rol')}}<span id="m_editusuario_textError_capacidad" class="text-danger modal_spantexterror"></span>
            {{Form::select('capacidad', Config::get('string.capacidades') , '' ,array('class' => 'form-control'));}}
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputcolectivo">  
            {{Form::label('colectivo', 'Colectivo')}}<span id="m_editusuario_textError_colectivo" class="text-danger modal_spantexterror"></span>
            {{Form::select('colectivo', Config::get('string.colectivos'),'',array('class' => 'form-control'))}}
          </div>
          
          <div class="form-group modal_divinput" id="m_editusuario_inputcaducidad">   
            {{Form::label('caducidad', 'Caducidad para SGR')}}
            <span id="m_editusuario_textError_caducidad" class="text-danger modal_spantexterror"></span>     
            {{Form::text('caducidad','',array('class' => 'form-control datepicker','id' => 'datepickerUserEdit'))}}                
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputnombre">  
            {{Form::label('nombre', 'Nombre')}}
            <span id="m_editusuario_textError_nombre" class="text-danger modal_spantexterror"></span>     
            {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
          </div>
                  
          <div class="form-group modal_divinput" id="m_editusuario_inputapellidos">  
            {{Form::label('apellidos', 'Apellidos')}}
              <span id="m_editusuario_textError_apellidos" class="text-danger modal_spantexterror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
          </div>
                  
          <div class="form-group modal_divinput" id="m_editusuario_inputemail">  
            {{Form::label('email', 'eMail')}}
            <span id="m_editusuario_textError_email" class="text-danger modal_spantexterror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> </span>     
            {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
          </div>

          <div class="form-group modal_divinput" id="m_editusuario_inputobservaciones">
            <label for="observaciones">Observaciones</label> 
            <textarea class="form-control" name="observaciones" rows="5"  value=""></textarea>
          </div>
        </div><!-- ./modal-body -->

        <div class="modal-footer">
          <input type="hidden" name="id" value="" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id ="btnEditUser"><i class="fa fa-save fa-fw"></i> Salvar</button>
        </div><!-- ./modal-footer -->
      </form>
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
</div><!-- #/modalEditUser -->