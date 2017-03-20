<div class="modal fade" id="modalUser" ><!-- modalusuarios -->
   <form class="form-horizontal" role="form" id="activeUser"> <!-- data-item=''--> 
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Notificaciones de Acceso</h4>
      </div>
      

      <div class="modal-body">

          <div class="alert alert-danger text-center modal_msgError" role="alert" id="m_activaUsuario_msgError" style="">Formulario con errores</div>  
            
          <span id="m_activaUsuario_textError_username" class="text-danger modal_spantexterror text-center"></span> 
          <div class="form-group" id="m_activaUsuario_inputusername" >
            <label for="uvus"  class="control-label col-md-3" >Uvus: </label>   
              <div class = "col-md-9">  
                <input type="text" name = "username" class="form-control" id="username"  readonly/>
              </div>             
          </div>

          <span id="m_activaUsuario_textError_colectivo" class="text-danger modal_spantexterror text-center"></span>
          <div class="form-group" id="m_activaUsuario_inputcolectivo" >
            <label for="colectivo"  class="control-label col-md-3" >Colectivo: </label>
            <div class = "col-md-9">  
              {{Form::select('colectivo', Config::get('string.colectivos'),Config::get('options.colectivoPorDefecto'),array('class' => 'form-control'))}}
            </div>
          </div>

          <span id="m_activaUsuario_textError_capacidad" class="text-danger modal_spantexterror text-center"></span>
          <div class="form-group" id="m_activaUsuario_inputcapacidad">
            <label for="rol"  class="control-label col-md-3" >Rol: </label>
            <div class = "col-md-9">  
              {{Form::select('capacidad', Config::get('string.capacidades') , Config::get('options.capacidadPorDefecto') ,array('class' => 'form-control'));}}    
            </div>
          </div>  
            
          <span id="m_activaUsuario_textError_caducidad" class="text-danger modal_spantexterror text-center"></span>
          <div class="form-group" id="m_activaUsuario_inputcaducidad">
            <label for="caducidad"  class="control-label col-md-3" >Caduca el: </label> 
            <div class="col-md-9">
              {{Form::text('caducidad',date('d-m-Y',strtotime(Config::get('options.fin_cursoAcademico' ))),array('class' => 'form-control','id' => 'datepickerCaducidad'))}}
            </div>
          </div>
          
          <div class="form-group">
            <label for="observaciones"  class="control-label col-md-3" >Observaciones (opcional): </label> 
            <div class = "col-md-9">
              <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>
          </div>      
         
          <div class="form-group " id="m_activaUsuario_inputidnotificacion">
            <span id="m_activaUsuario_textError_idnotificacion" class="text-danger modal_spantexterror col-md-12  text-center "></span>
            <input type="hidden" name="idnotificacion" value="" />
          </div>

          <div class="form-group " id="m_activaUsuario_inputid">
            <span id="m_activaUsuario_textError_id" class="text-danger modal_spantexterror col-md-12  text-center "></span>
            <!--<input type="hidden" name="activar" value="0" />-->
          </div>
      </div><!-- ./modal-body -->
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="borrar"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
        <button type="button" class="btn btn-warning" id="desactivar"><i class="fa fa-toggle-off" aria-hidden="true"></i> Desactivar</button>
        <button type="button" class="btn btn-primary" id="activar"><i class="fa fa-check" aria-hidden="true"></i> Activar</button>
      </div>
     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form> 
</div><!-- /.modal -->