<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserLabel">

  {{Form::open(array('method' => 'POST','route' => 'post_addUser','role' => 'form','id'=>'nuevoUsuario'))}}

  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-user fa-fw"></i> Nuevo usuario</h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
          
        <div class="alert alert-danger" role="alert" style="display:none" id="aviso">Revise el formulario para corregir errores.... </div>
          
      <div class="form-group">  
        {{Form::label('username', 'UVUS (Usuario Virtual Universidad de Sevilla)')}}
        <span id="username_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
        {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
      </div>
             
      <div class="form-group">     
        {{Form::label('capacidad', 'Rol')}}
        <span id="capacidad_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>
        {{Form::select('capacidad', array('1' => 'Usuario (Alumnos)', '2' => 'Usuario Avanzado (PDI/PAS Administración)','3' => 'Técnico (PAS-MAV)','4' => 'Administrador (SGR)', '5' => 'Validador (Dirección/Decanato)','6' => 'Supervisor (E.E Unidad)'),'Usuario (Alumnos)',array('class' => 'form-control'));}}
      </div>

      <div class="form-group">  
        {{Form::label('colectivo', 'Colectivo')}}
        {{Form::select('colectivo', array('Alumno' => 'alumno','PAS' => 'PAS','PDI' => 'PDI'),'Alumno',array('class' => 'form-control'))}}
      </div>
      
      <div class="form-group">   
        {{Form::label('caducidad', 'Caducidad de la cuenta para sistema de reservas')}}
        <span id="caducidad_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
        {{Form::text('caducidad',date('d-m-Y',strtotime('+1 year')),array('class' => 'form-control'))}}                
      </div>

      <div class="form-group">  
        {{Form::label('nombre', 'Nombre')}}
        <span id="nombre_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
        {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
      </div>
              
      <div class="form-group">  
        {{Form::label('apellidos', 'Apellidos')}}
          <span id="apellidos_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
        {{Form::text('apellidos',Input::old('apellidos'),array('class' => 'form-control'))}}
      </div>
              
      <div class="form-group">  
        {{Form::label('email', 'eMail')}}
        <span id="email_error" style="display:none" class="text-danger spanerror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id='text_error'></span></span>     
        {{Form::text('email',Input::old('email'),array('class' => 'form-control'))}}
      </div>        
                      
    </div><!-- ./modal-body --> 
      
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" id ="btnSalvarRecurso"><i class="fa fa-save fa-fw"></i> Salvar</button>
    </div><!-- ./modal-footer -->

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->



