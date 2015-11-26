<!-- modal baja supervisor -->
<div class="modal fade" id="modalConfirmaBajaSupervisor" tabindex="-3" role="dialog" aria-labelledby="bajaSupervisor" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Quitar supervisor</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>quitar</b> el usuario con UVUS <i><span id="usernameSupervisor"></span></i> como supervisor del recurso "<b><i><span id="nombreRecurso"></span>"</b></i>?</div>
                
                <div class="alert text-center" role="alert" style="display:none" id="avisoQuitarSupervisor"></div>


       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" href="" role="button" id="btnquitaSupervisor" data-idrecurso="" data-iduser=""><i class="fa fa-toggle-off fa-fw"></i> Quitar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalQuitarSupervisor -->
