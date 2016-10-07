<!-- modal baja supervisor//gestor//validador en grupo-->
<div class="modal fade" id="m_removePersonaGrupo" tabindex="-11" role="dialog" aria-labelledby="removeUserWithRol" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_removePersonaGrupo'))}}  
  <div class="modal-dialog modal-md">

    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info">
          <i class="fa fa-user-times fa-fw"></i> Eliminar relación en:<b> <span id ="m_removePersonaGrupo_title_nombre"></span></b> 
        </h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        
        <div class="modal_msgError alert alert-danger text-center" role="alert" style="display:none;margin:10px 0" id="fm_removePersonaGrupo_textError">Formulario con errores</div>
        
         <!-- errores sin campo de formulario visible -->
        <div class="form-group" id="m_removePersonaGrupo_idgrupo">
          <span id="m_removePersonaGrupo_textError_idgrupo" class="text-danger modal_spantexterror text-center"></span>
        </div>
        <!-- ./errores -->  
        
        <div id = "fm_removePersonas-checkboxes">
        </div> 
        

        <span class="help-block">Marque los usuarios para eliminar su relación en el grupo</span>
          
        
        <div class="form-group hidden">
            {{Form::text('idgrupo','',array('class' => 'form-control'))}}
        </div>  
      </div><!-- ./.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_removePersonaGrupo_save">
          <i class="fa fa-trash fa-fw"></i> Remover relación
        </button>
      </div><!-- /.footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  {{Form::close()}}
</div><!-- /.modal -->
