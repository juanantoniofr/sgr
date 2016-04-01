<!-- modal baja supervisor//tecnico//validador -->
<div class="modal fade" id="m_removePersona" tabindex="-11" role="dialog" aria-labelledby="removeUserWithRol" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_removePersona'))}}  
  <div class="modal-dialog modal-md">

    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info">
          <i class="fa fa-user-times fa-fw"></i> Eliminar persona para el recurso:<b> <span id ="m_removePersona_title_nombrerecurso"></span></b> 
        </h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addRemove_textError"></div>
          
        <div id = "fm_removePersonas-checkboxes">
          
        </div> 
        
        <span class="help-block">Marque los usuarios para eliminar la relación con el recurso.</span>
          
        <div class="form-group hidden">
            {{Form::text('idgrupo','',array('class' => 'form-control'))}}
        </div>  
      </div><!-- ./.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_removePersona_save">
          <i class="fa fa-save fa-fw"></i> Salvar
        </button>
      </div><!-- /.footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  {{Form::close()}}
</div><!-- /.modal -->
