<!-- marca branch master2 --><!-- / Modal edit grupo  :) -->
<div class="modal fade myModal-lg " id="m_editgrupo" tabindex="-2" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_editgrupo'))}}          
    <div class="modal-dialog modal-md">
       
      <div class="modal-content">
          
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-pencil fa-fw"></i> Editar grupo</h2>
        </div><!-- ./modal-header -->
        
        <div class="modal-body">

          <div class="modal_msgError alert alert-danger text-center" role="alert" style="display:none;margin:10px 0" id="fm_editgrupo_textError">Formulario con errores</div>
          
          <div class="form-group" id="m_editgrupo_grupo_id">
            <span id="m_addPersonaGrupo_textError_grupo_id" class="text-danger modal_spantexterror text-center"></span>
          </div>
          <!-- editar nombre del grupo -->
          <div class="form-group" id="fm_editgrupo_inputnombre">  
            {{Form::label('nombre', 'Nombre',array('class' => 'control-label'))}}
            <span id="m_editgrupo_textError_nombre" class="text-danger modal_spantexterror text-center"></span>
            {{Form::text('nombre','',array('class' => 'form-control', 'id' => 'nombre'))}}
          </div>

          <div class="form-group" id="fm_editgrupo_inputtipo">
            <label for="tipo">Tipo</label>
            <span id="m_editgrupo_textError_tipo" class="text-danger modal_spantexterror text-center"></span>
            <select name="tipo" class="form-control" id="fm_editgrupo_optionsTipo">
              <option id="fm_editgrupo_optionstipo" value ="" selected="selected"></option>
            </select>
          </div>
          
          <!-- edita descripción del grupo --> 
          <div class="form-group">  
            {{Form::label('descripcion', 'Descripción')}}
            {{Form::textarea('descripcion','',array('class' => 'form-control', 'id' => 'fm_editgrupo_inputdescripcion'))}}
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