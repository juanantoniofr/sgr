<!-- modal eliminar recurso -->
<div class="modal fade" id="m_delrecurso" tabindex="-7" role="dialog" aria-labelledby="borrarRecurso" aria-hidden="true">
    {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_delrecurso'))}}  
    <div class="modal-dialog modal-md">
        
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title text-info"><i class="fa fa-trash-o fa-fw"></i> Eliminar recurso</h2>
            </div>

            <div class="modal-body">
                <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_delrecurso_textError"></div>
                <!--Div alert --> 
                <div class="alert alert-danger text-center" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el recurso: "<b><span id="mdrecurso_nombre"></span>"</b> ?</div>
                <!--Div warning --> 
                <div class="alert alert-warning text-center"> El recurso se eliminará de forma permanente</div>
                <div class="form-group hidden">
                    {{Form::text('idrecurso','',array('class' => 'form-control'))}}
                </div>

                
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <div class="col-lg-12" style="margin-top:10px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-idrecursopadre="" id ="fm_delrecurso_save">
                        <i class="fa fa-trash-o fa-fw"></i> Eliminar
                    </button>
                </div>
            </div><!-- ./footer -->
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
    {{Form::close()}}
</div><!-- ./#modalborrarRecurso -->