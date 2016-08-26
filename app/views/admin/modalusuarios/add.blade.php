<!-- :) -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserLabel">

  {{Form::open(array('method' => 'POST','route' => 'post_addUser','role' => 'form','id'=>'nuevoUsuario'))}}

  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-user-plus fa-fw"></i> Nuevo usuario</h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="alert alert-danger text-center" role="alert" style="display:none" id="aviso">Revise el formulario para corregir errores.... </div>
          
        <div class="form-group" id="fg_username">  
          {{Form::label('username', 'UVUS (Usuario Virtual Universidad de Sevilla)')}}
          <span id="username_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
          {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
        </div>
               
        <div class="form-group" id="fg_capacidad">      
          {{Form::label('capacidad', 'Rol')}}
          <span id="capacidad_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
          {{Form::select('capacidad', Config::get('string.capacidades') , Config::get('options.capacidadPorDefecto') ,array('class' => 'form-control'));}}
        </div>

        <div class="form-group" id="fg_colectivo">  
          {{Form::label('colectivo', 'Colectivo')}}
          {{Form::select('colectivo', array('Alumno' => 'alumno','PAS' => 'PAS','PDI' => 'PDI'),'Alumno',array('class' => 'form-control'))}}
        </div>
      
        <div class="form-group" id="fg_caducidad">   
          {{Form::label('caducidad', 'Caducidad de la cuenta para sistema de reservas')}}
          <span id="caducidad_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
          {{Form::text('caducidad',date('d-m-Y',strtotime('+1 year')),array('class' => 'form-control','id' => 'addUserDatePicker'))}}                
        </div>

        <div class="form-group" id="fg_nombre">  
          {{Form::label('nombre', 'Nombre')}}
          <span id="nombre_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
                
        <div class="form-group" id="fg_apellidos">  
          {{Form::label('apellidos', 'Apellidos')}}
            <span id="apellidos_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
          {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
        </div>
                
        <div class="form-group" id="fg_email">  
          {{Form::label('email', 'eMail')}}
          <span id="email_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
          {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
        </div>        
                        
    </div><!-- ./modal-body --> 
      
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" id ="btnSalvarUser"><i class="fa fa-save fa-fw"></i> Salvar</button>
    </div><!-- ./modal-footer -->

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->
