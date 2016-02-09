<div class="modal fade" id="modalAddSupervisor" tabindex="-2" role="dialog" aria-labelledby="modalAddSupervisorLabel">
  
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-user fa-fw"></i> Rol para el recurso:<b> <span id ="ModalUsuerNombreRecurso"></span></b> <i>(<span id ="ModalUserNombreGrupo"></span>)</i></span> </h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
              <form class="form-horizontal">
              <div class="form-group">
                <label class="col-md-2 control-label">UVUS:</label>
                <div class="col-md-10">
                  {{Form::text('username',Input::old('username'),array('id' => 'username','class' => 'form-control','placeholder' => 'Escriba usuario virtual'))}}
                </div>

              </div>
             
              <div class="form-group" id="funcion">
                <label for="rol"  class="control-label col-md-2" >Rol: </label>
                <div class = "col-md-10">  
                    <select class="form-control"  name="rol" id="selectrol">
                        <option value="1" selected="selected" >Técnico (atender reservas)</option>
                        <option value="2" >Supervisor (Gestión de recursos )</option>
                        <option value="3" >Validador (Validar solicitudes de reserva)</option>
                    </select> 
                </div>
            </div>

            <input type="hidden" name="idRecurso" id ="ModalUsuerIdRecurso" value="" />

            <div class="alert text-center" role="alert" style="display:none" id="ModalUsuerAviso"></div>
            </form>          
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id ="btnSalvarSupervisor"><i class="fa fa-save fa-fw"></i> Salvar</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  
</div><!-- /.modal -->



