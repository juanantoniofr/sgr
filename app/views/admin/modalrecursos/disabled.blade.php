<!-- modal deshabilitar recurso -->
<div class="modal fade" id="m_disabledrecurso" tabindex="-9" role="dialog" aria-labelledby="disabledRecurso" aria-hidden="true">
    {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_disabledrecurso'))}}  
    <div class="modal-dialog modal-md">
        
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title text-info"><i class="fa fa-toggle-off fa-fw"></i> Deshabilitar recurso</h2>
            </div>

            <div class="modal-body">
                <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_disabledrecurso_textError"></div>
                
                <div class="alert alert-danger" role = "alert">
                    ¿Estás seguro que deseas <b>deshabilitar</b> el recurso: "<b><span id="m_disabled_nombre"></span>"</b> ?
                </div>

                <div class="alert alert-warning text-center"> Al deshabilitar el recurso:
                    <ul class="text-left">
                        <li> Se deshabilitarán todos los elementos asociados a este recurso. </li>
                        <li> No se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <lI> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso. </lI>
                    </ul>
                </div>
                
                <!-- mensaje -->  
                <div class="form-group">
                    <label for="motivo"  class="control-label" >Motivo <small>(opcional)</small></label> 
                    <textarea class="form-control" rows="5" name="motivo" id="fm_disabledrecurso_motivo"></textarea>
                </div>
                
                <div class="form-group hidden">
                    {{Form::text('idrecurso','',array('class' => 'form-control'))}}
                </div> 

            </div><!-- ./.modal-body -->

            <div class="modal-footer">
               <div class="col-lg-12" style="margin-top:10px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning" id ="fm_disabledrecurso_save">
                        <i class="fa fa-toggle-off fa-fw"></i> Deshabilitar
                    </button>
                </div>
            </div><!-- ./footer -->
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
    {{Form::close()}}
</div> <!-- ./#modaldisabledRecurso --> 