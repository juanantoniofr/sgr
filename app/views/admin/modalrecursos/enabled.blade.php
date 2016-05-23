<!-- modal habilitar recurso -->
<div class="modal fade" id="m_enabledrecurso" tabindex="-8" role="dialog" aria-labelledby="enabledRecurso" aria-hidden="true">
    {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_enabledrecurso'))}}  
    <div class="modal-dialog modal-md">
       
        <div class="modal-content">
           
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title text-info"><i class="fa fa-toggle-on fa-fw"></i> Habilitar recurso</h2>
            </div>

            <div class="modal-body">
                <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_enabledrecurso_textError"></div>
        
                <div class="alert alert-danger text-center" role = "alert">¿Estás seguro que deseas <b>Habilitar</b> el recurso: "<b><span id="m_enabled_nombre"></span>"</b> ?</div>
                <div class="alert alert-warning"> Al habilitar el recurso:
                    <ul>
                        <li> Se habilitaran todos los elementos asociados a este recurso. </li>
                        <li> Se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <li> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso, inidcando que vuelve a estar habilitado. </li>
                    </ul>
                </div>
            <div class="form-group hidden">
                    {{Form::text('idrecurso','',array('class' => 'form-control'))}}
                </div> 
            </div><!-- ./.modal-body -->

             <div class="modal-footer">
                <div class="col-lg-12" style="margin-top:10px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-idrecursopadre="" id ="fm_enabledrecurso_save">
                        <i class="fa fa-toggle-on fa-fw"></i> Habilitar
                    </button>
                </div>
            </div><!-- ./footer -->
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
    {{Form::close()}}
</div><!-- ./#modaldisabledRecurso -->   