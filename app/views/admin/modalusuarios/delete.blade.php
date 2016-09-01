<!-- modal eliminar recurso -->
<div class="modal fade" id="modalEliminaUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminaUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar usuario</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-warning text-center" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el usuario: "<b><span id="infoUsuario"></span>"</b> ?</div>
                
                <div class="alert alert-danger text-center" id="modal_deleteUser_tienereservas" > El usuario tiene <span id="modal_deleteUser_numreservas"></span> reservas pendientes de realizar. Si continua, eliminará el usuario y sus reservas pendientes de forma permanente</div>
                
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnEliminar"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a>

                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalborrarRecurso -->