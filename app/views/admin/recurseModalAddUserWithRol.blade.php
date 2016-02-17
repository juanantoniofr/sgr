<!-- modal alta supervisor//tecnico//validador -->
<div class="modal fade" id="modalAddUserWithRol" tabindex="-2" role="dialog" aria-labelledby="modalAddSupervisorLabel">
  
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-user fa-fw"></i> Establecer relación para el recurso:<b> <span id ="ModalUsuerNombreRecurso"></span></b> <i>(<span id ="ModalUserNombreGrupo"></span>)</i> </h3>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="alert text-center" role="alert" style="display:none" id="msg_modalAddUserWithRol"></div>
          <form class="form-horizontal" id="addUserWithRol">
            <div class="form-group">
              <label for="username" class="col-md-2 control-label">UVUS:</label>
              <div class="col-md-10">
                {{Form::text('username',Input::old('username'),array('id' => 'username','class' => 'form-control','placeholder' => 'Escriba usuario virtual'))}}
              </div>
            </div>
             
            <div class="form-group" id="funcion">
              <label for="rol"  class="control-label col-md-2" >Rol: </label>
              <div class = "col-md-10">  
                <select class="form-control"  name="rol" id="selectrol">
                  <option value="1" selected="selected" >Técnico (Atiende reservas)</option>
                  <option value="2" >Supervisor (Gestiona recursos)</option>
                  <option value="3" >Validador (Valida solicitudes de reserva)</option>
                </select> 
              </div>
            </div>

            <input type="hidden" name="idRecurso" id ="ModalUsuerIdRecurso" value="" />
          </form>          
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id ="btnAddUserWithRol"><i class="fa fa-save fa-fw"></i> Salvar</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  
</div><!-- /.modal -->



