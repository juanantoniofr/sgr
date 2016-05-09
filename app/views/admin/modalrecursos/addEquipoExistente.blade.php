<!-- / Modal añade equipo existenten a tipoequipos  -->
<div class="modal fade myModal-lg " id="m_addEquipoExistente" tabindex="-16" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addEquipoExistente'))}}          
  <div class="modal-dialog modal-lg">
     
    <div class="modal-content">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-plus fa-fw"></i> Añade equipo/s del modelo: <b><span id="m_addEquipoExistente_nombre"></span></b></h2>
      </div><!-- ./modal-header -->
      
      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addEquipoExistente_textError"></div>
        <span id="EquipoSinModelo"></span>
        <div class="form-group hidden">
          {{Form::text('tipoequipo_id','',array('class' => 'form-control'))}}
        </div>
      </div><!-- /#modal-body -->
      
      <div class="modal-footer">
        <div class="col-lg-12" style="margin-top:10px">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="fm_addEquipoExistente_save">
            <i class="fa fa-save fa-fw"></i> Salvar
          </button>
        </div>
      </div><!-- ./modal-footer -->
       
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
  {{Form::close()}}
</div><!-- ./modal -->