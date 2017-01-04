<!-- marca branch master2 --><!-- modal add relacion persona-grupo || persona-recurso :) -->
<div class="modal fade" id="m_addRelacion" tabindex="-10" role="dialog" aria-labelledby="modalAddPersonaLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addRelacion'))}}  
  <div class="modal-dialog modal-md">
    
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info">
          <i class="fa fa-users fa-fw"></i> Añadir relación en:<b> <span id ="m_addRelacion_title_nombre"></span></b> 
        </h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="modal_msgError alert alert-danger text-center" role="alert" style="display:none;margin:10px 0" id="fm_addRelacion_textError">Formulario con errores</div>
        
        <!-- errores sin campo de formulario visible -->
        <div class="form-group" id="m_addRelacion_id">
          <span id="m_addRelacion_textError_id" class="text-danger modal_spantexterror text-center"></span>
        </div>
        <div class="form-group" id="m_addRelacion_tipo">
          <span id="m_addRelacion_textError_tipo" class="text-danger modal_spantexterror text-center"></span>
        </div>
        <div class="form-group" id="m_addRelacion_gestor">
          <span id="m_addRelacion_textError_gestor" class="text-danger modal_spantexterror text-center"></span>
        </div>
        <div class="form-group" id="m_addRelacion_administrador">
          <span id="m_addRelacion_textError_administrador" class="text-danger modal_spantexterror text-center"></span>
        </div>
        <div class="form-group" id="m_addRelacion_validador">
          <span id="m_addRelacion_textError_validador" class="text-danger modal_spantexterror text-center"></span>
        </div>                          
        <!-- ./errores -->

        <div class="form-group" id="m_addRelacion_inputusername">
          {{Form::label('username', 'UVUS')}}
          <span id="m_addRelacion_textError_username" class="text-danger modal_spantexterror"></span>
          {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
        </div>
        
        <div class="form-group" id="m_addRelacion_rol">  
          {{Form::label('rol', 'Roles')}}
          <span id="m_addRelacion_textError_rol" class="text-danger modal_spantexterror"></span>
          {{Form::select('rol', Config::get('string.relaciones'),Config::get('options.relacionPorDefecto'),array('class' => 'form-control'))}}
        </div>

        
        <div class="form-group hidden">
          {{Form::text('id','',array('class' => 'form-control'))}}
        </div>  
        <div class="form-group hidden">
          {{Form::text('tipo','',array('class' => 'form-control'))}}
        </div>
        <div class="form-group hidden">
          {{Form::text('grupoid','',array('class' => 'form-control'))}}
        </div>    

       
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_addRelacion_save">
          <i class="fa fa-save fa-fw"></i> Salvar
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  {{Form::close()}}
</div><!-- /.modal -->



