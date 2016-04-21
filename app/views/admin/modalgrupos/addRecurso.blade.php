<!-- / Modal añade recurso existente a un grupo  -->
<div class="modal fade myModal-lg " id="m_addrecursotogrupo" tabindex="-6" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addrecursotogrupo'))}}          
  <div class="modal-dialog modal-lg">
     
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-object-group fa-fw"></i> Añade recursos al grupo: <b><span id="m_addrecursotogrupo_nombre"></span></b></h2>
        </div><!-- ./modal-header -->
      
        <div class="modal-body">
            <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addrecursotogrupo_textError"></div>
            <span id="recursosSinGrupo"></span>
            <div class="form-group hidden">
            {{Form::text('grupo_id','',array('class' => 'form-control'))}}
            </div>
        </div><!-- /#modal-body -->
        

        <div class="modal-footer">
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id ="fm_addrecursotogrupo_save">
              <i class="fa fa-save fa-fw"></i> Salvar cambios
            </button>
          </div>
        </div><!-- ./modal-footer -->
      
       
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
    {{Form::close()}}
  </div><!-- ./modal -->