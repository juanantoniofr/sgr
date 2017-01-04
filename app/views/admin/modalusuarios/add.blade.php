<!-- marca branch master2 --><!-- :) -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserLabel">

  <form id="nuevoUsuario">
  
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-user-plus fa-fw"></i> Nuevo usuario</h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="alert alert-danger text-center modal_msgError" role="alert" id="m_addusuario_msgError" style="display:none;margin:10px 0">Formulario con errores</div>
          
        <div class="form-group modal_divinput" id="m_addusuario_inputusername">  
          {{Form::label('username', 'UVUS (Usuario Virtual Universidad de Sevilla)')}}<span id="m_addusuario_textError_username" class="text-danger modal_spantexterror"></span>
          {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
        </div>
               
        <div class="form-group modal_divinput" id="m_addusuario_inputcapacidad">      
          {{Form::label('capacidad', 'Rol')}}<span id="m_addusuario_textError_capacidad" class="text-danger modal_spantexterror"></span>
          {{Form::select('capacidad', Config::get('string.capacidades') , Config::get('options.capacidadPorDefecto') ,array('class' => 'form-control'));}}
        </div>

        <div class="form-group modal_divinput" id="m_addusuario_inputcolectivo">  
          {{Form::label('colectivo', 'Colectivo')}}<span id="m_addusuario_textError_colectivo" class="text-danger modal_spantexterror"></span>
          {{Form::select('colectivo', Config::get('string.colectivos'),Config::get('options.colectivoPorDefecto'),array('class' => 'form-control'))}}
        </div>
      
        <div class="form-group modal_divinput" id="m_addusuario_inputcaducidad">   
          {{Form::label('caducidad', 'Caducidad de la cuenta para sistema de reservas')}}<span id="m_addusuario_textError_caducidad" class="text-danger modal_spantexterror"></span>
          {{Form::text('caducidad',date('d-m-Y',strtotime('+1 year')),array('class' => 'form-control','id' => 'addUserDatePicker'))}}                
        </div>

        <div class="form-group modal_divinput" id="m_addusuario_inputnombre">  
          {{Form::label('nombre', 'Nombre')}}<span id="m_addusuario_textError_nombre" class="text-danger modal_spantexterror"></span>
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
                
        <div class="form-group modal_divinput" id="m_addusuario_inputapellidos">  
          {{Form::label('apellidos', 'Apellidos')}}<span id="m_addusuario_textError_apellidos" class="text-danger modal_spantexterror"></span>
          {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
        </div>
                
        <div class="form-group modal_divinput" id="m_addusuario_inputemail">  
          {{Form::label('email', 'eMail')}}<span id="m_addusuario_textError_email" class="text-danger modal_spantexterror"></span>
          {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
        </div>        
                        
    </div><!-- ./modal-body --> 
      
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" id ="btnSalvarUser"><i class="fa fa-save fa-fw"></i> Salvar</button>
    </div><!-- ./modal-footer -->

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form> 
</div><!-- /.modal -->
