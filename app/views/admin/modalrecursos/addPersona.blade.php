<!-- modal alta supervisor//tecnico//validador -->
<div class="modal fade" id="m_addPersona" tabindex="-10" role="dialog" aria-labelledby="modalAddPersonaLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addPersona'))}}  
  <div class="modal-dialog modal-md">
    
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info">
          <i class="fa fa-users fa-fw"></i> Añadir persona para el recurso:<b> <span id ="m_addPersona_title_nombrerecurso"></span></b> 
        </h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addPersona_textError"></div>

        <div class="form-group" id="fm_addPersona_inputusername">
          {{Form::label('username', 'UVUS')}}
          {{Form::text('username',Input::old('username'),array('class' => 'form-control'))}}
        </div>
        
        <div class="form-group" id="fm_addPersona_inputrol">  
          {{Form::label('rol', 'Roles')}}
          {{Form::select('rol', array('1' => 'Técnico (Atiende reservas)', '2' => 'Supervisor (Gestiona recursos)', '3' => 'Validador (Valida solicitudes de reserva)'),'1',array('class' => 'form-control'))}}
        </div>

        <div class="form-group hidden">
          {{Form::text('idrecurso','',array('class' => 'form-control'))}}
        </div>  
        
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_addPersona_save">
          <i class="fa fa-save fa-fw"></i> Salvar
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  {{Form::close()}}
</div><!-- /.modal -->



