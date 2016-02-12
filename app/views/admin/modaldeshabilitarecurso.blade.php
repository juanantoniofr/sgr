<!-- modal deshabilitar recurso -->
<div class="modal fade" id="modaldisabledRecurso" tabindex="-2" role="dialog" aria-labelledby="disabledRecurso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deshabilitarecurso">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 class="modal-title"><i class="fa fa-toggle-off fa-fw"></i> Deshabilitar recurso</h3>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">
                    ¿Estás seguro que deseas <b>deshabilitar</b> el recurso: "<b><span id="nombrerecurso_switchenabled"></span>"</b> ?
                </div>
                <!-- mensaje -->  
                <div class="form-group">
                    <label for="motivo"  class="control-label" >Descripción del motivo<small>(opcional)</small></label> 
                    <textarea class="form-control" rows="5" name="motivo" id="motivo"></textarea>
                </div>
                <input id="modaldisable_idrecurso" type="hidden" name="idDisableRecurso" value=""   />

            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <button class="btn btn-primary" id="btnDeshabilitar"><i class="fa fa-toggle-off fa-fw"></i> Deshabilitar</button>                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                 <div class="alert alert-warning text-center"> Al deshabilitar el recurso:
                    <ul class="text-left">
                        <li> No se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <lI> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso. </lI>
                    </ul>
                </div>
            </div>
            </form>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modaldisabledRecurso --> 