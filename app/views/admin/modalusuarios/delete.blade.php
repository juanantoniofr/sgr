<!-- modal eliminar Usuario -->
<div class="modal fade" id="modalEliminaUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminaUsuario" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_eliminausuario'))}}  
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar usuario</h4>
        </div>

        <div class="modal-body">
          <div class="alert alert-danger text-center modal_MsgError" role="alert" id="m_eliminausuario_msgError" style="display:none;margin:10px 0">Formulario con errores: <span id="m_eliminausuario_textError_id" class="text-danger modal_spantexterror"></span></div>
          
          <div class="alert alert-warning text-center" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el usuario: "<b><span id="infoUsuario"></span>"</b> ?</div>
                
          <div class="alert alert-danger text-center" id="modal_deleteUser_tienereservas" > El usuario tiene <span id="modal_deleteUser_numreservas"></span> reservas pendientes de realizar. Si continua, eliminará el usuario y sus reservas pendientes de forma permanente</div>
                
          <div class="form-group hidden">
            {{Form::text('id','',array('class' => 'form-control'))}}
          </div>
        </div><!-- ./.modal-body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="fm_eliminausuario_save" ><i class="fa fa-trash-o fa-fw"></i> Eliminar</button>
        </div>
      </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
  {{Form::close()}}
</div><!-- ./#modalborrarRecurso -->