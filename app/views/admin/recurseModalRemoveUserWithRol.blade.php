<!-- modal baja supervisor//tecnico//validador -->
<div class="modal fade" id="modalRemoveUserWithRol" tabindex="-3" role="dialog" aria-labelledby="removeUserWithRol" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 class="modal-title"><i class="fa fa-user-times fa-fw"></i> Quitar usuarios de la relación supervisor, validador y/o técnico</h3>
            </div>

            <div class="modal-body" class="col-md-8 col-offset-md-2">
        
                <div class="alert alert-danger text-center" role = "alert" style="display:none" id="msg_modalRemoveUserWithRol"></div>
                
                
                <form id="removeUserWithRol" role="form">

                    <!-- supervisores -->
                    
                    <label class="control-label" >Supervisores</label>
                    <div class="checkbox" id="supervisores">
                        
                    </div>
                    
              
                    <!-- validadores -->
                    
                    <label class="control-label" >Validadores</label>
                    <div class="checkbox" id="validadores">
                        
                    </div>
                    

                    <!-- técnicos -->
                    <label class="control-label" >Técnicos</label>
                    <div class="checkbox" id="tecnicos">
                        
                    </div>
                    

                    <span class="help-block">Marque los usuarios para eliminar la relación con el recurso.</span>

                    <input type="hidden" name="idrecurso" value="" />
    
                </form>

       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" href="" role="button" id="btnremoveUserWithRol" data-idrecurso="" data-iduser=""><i class="fa fa-user-times fa-fw"></i> Eliminar relación usuario/s marcado/s</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalQuitarSupervisor -->
