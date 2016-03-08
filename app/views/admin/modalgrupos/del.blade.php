<!-- modal eliminar grupo -->
<div class="modal fade" id="m_delgrupo" tabindex="-3" role="dialog" aria-labelledby="borrarGrupo" aria-hidden="true">
   {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_delgrupo'))}}  
    <div class="modal-dialog modal-md">
        
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title text-info"><i class="fa fa-trash-o fa-fw"></i> Eliminar grupo</h2>
            </div>

            <div class="modal-body">
                <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_delgrupo_textError"></div>    
                <!--Div warning --> 
                <div class="alert alert-danger text-center" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el grupo: "<b><span id="mdgrupo_nombre"></span>"</b> ?</div>
                
                <div class="form-group hidden">
                    {{Form::text('grupo_id','',array('class' => 'form-control'))}}
                </div>       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <div class="col-lg-12" style="margin-top:10px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id ="fm_delgrupo_save">
                        <i class="fa fa-trash-o fa-fw"></i> Eliminar
                    </button>
                </div>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
    {{Form::close()}}
</div><!-- ./#modalborrarGrupo -->