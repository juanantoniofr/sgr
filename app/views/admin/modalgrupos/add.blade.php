<!-- / Modal add grupo  -->
<div class="modal fade myModal-md " id="m_addgrupo" tabindex="-1" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addgrupo'))}}          
  <div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-plus fa-fw"></i> Añadir nuevo grupo  </h2>
      </div><!-- ./modal-header -->
      
      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addgrupo_textError"></div>
        <!-- nombre del grupo -->
        <div class="form-group" id="fm_addgrupo_inputnombre">  
          {{Form::label('nombre', 'Nombre',array('class' => 'control-label'))}}
          {{Form::text('nombre','',array('class' => 'form-control', 'id' => 'nombre'))}}
        </div>

        <!-- Descripción del grupo --> 
        <div class="form-group">  
          {{Form::label('descripcion', 'Descripción')}}
          {{Form::textarea('descripcion','',array('class' => 'form-control', 'id' => 'fm_addgrupo_inputdescripcion'))}}
          </div>
      </div><!-- /#modal-body -->
      
      <div class="modal-footer">
        <div class="col-lg-12" style="margin-top:10px">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id ="fm_addgrupo_save">
            <i class="fa fa-save fa-fw"></i> Salvar cambios
          </button>
        </div>
      </div><!-- ./modal-footer -->
       
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
  {{Form::close()}}
</div><!-- ./modal -->