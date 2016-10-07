<!-- / Modal edit grupo  -->
<div class="modal fade myModal-lg " id="m_editgrupo" tabindex="-2" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_editgrupo'))}}          
    <div class="modal-dialog modal-md">
       
      <div class="modal-content">
          
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-pencil fa-fw"></i> Editar grupo</h2>
        </div><!-- ./modal-header -->
        
        <div class="modal-body">
          <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_editgrupo_textError"></div>    
          <!-- editar nombre del grupo -->
          <div class="form-group" id="fm_editgrupo_inputnombre">  
            {{Form::label('nombre', 'Nombre',array('class' => 'control-label'))}}
            {{Form::text('nombre','',array('class' => 'form-control', 'id' => 'nombre'))}}
          </div>

          <div class="form-group" id="fm_editgrupo_inputtipo">  
            {{Form::label('tipo', 'Tipo de recurso')}}
            {{Form::select('tipo', Config::get('options.tipoRecursos'),'',array('class' => 'form-control'))}}
          </div>

          <!-- edita descripción del grupo --> 
          <div class="form-group">  
            {{Form::label('descripcion', 'Descripción')}}
            {{Form::textarea('descripcion','',array('class' => 'form-control', 'id' => 'fm_editgrupo_inputdescripcion'))}}
          </div>

          <div class="form-group" id="m_edsitgrupo_grupo_id">
            <span id="m_addPersonaGrupo_textError_grupo_id" class="text-danger modal_spantexterror text-center"></span>
          </div>
          <div class="form-group hidden">
            {{Form::text('grupo_id','',array('class' => 'form-control'))}}
          </div>
        </div><!-- /#modal-body -->
          

        <div class="modal-footer">
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id ="fm_editgrupo_save">
              <i class="fa fa-save fa-fw"></i> Salvar cambios
            </button>
          </div>
        </div><!-- ./modal-footer -->
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
  {{Form::close()}}
</div><!-- ./modal -->