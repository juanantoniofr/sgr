<!-- / Modal añade puesto existente a espacio  -->
<div class="modal fade myModal-lg " id="addPuestoExistente" tabindex="-15" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addPuestoExistente'))}}          
  <div class="modal-dialog modal-lg">
     
    <div class="modal-content">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-plus fa-fw"></i> Añade puestos al espacio: <b><span id="m_addPuestoExistente_nombre"></span></b></h2>
      </div><!-- ./modal-header -->
      
      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addPuestoExistente_textError"></div>
        <span id="PuestoSinEspacio"></span>
        <div class="form-group hidden">
          {{Form::text('espacio_id','',array('class' => 'form-control'))}}
        </div>
      </div><!-- /#modal-body -->
      
      <div class="modal-footer">
        <div class="col-lg-12" style="margin-top:10px">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id ="fm_addPuestoExistente_save">
            <i class="fa fa-save fa-fw"></i> Salvar cambios
          </button>
        </div>
      </div><!-- ./modal-footer -->
       
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
  {{Form::close()}}
</div><!-- ./modal -->