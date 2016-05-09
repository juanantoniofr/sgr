<!-- / Modal edit equipo  -->
<div class="modal fade myModal-lg" id="m_editequipo" tabindex="-17" role="dialog" aria-hidden="true" aria-labelledby="modalEditequipoLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_editequipo'))}}
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info"><i class="fa fa-pencil fa-fw"></i> <b>Editar Equipo:</b> <span id ="m_editequipo_title_nombreequipo"></span></h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_editequipo_textError"></div>

        <div class="form-group" id="fm_editequipo_inputid_lugar">  
          {{Form::label('id_lugar', 'Identificador de Lugar')}}
          {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="fm_editequipo_inputnombre">
          {{Form::label('nombre', 'Nombre')}}
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="fm_editequipo_inputtipo">  
          {{Form::label('tipo', 'Tipo de recurso')}}
          {{Form::select('tipo', array('equipo' => 'Equipo'),'equipo',array('class' => 'form-control'))}}
        </div>

        <div class="form-group" id="fm_editequipo_inputtipoequipo_id">
          {{Form::label('tipoequipo_id', 'Modelo de equipo')}}
          <select name="tipoequipo_id" class="form-control" id="fm_editequipo_optionsTipoequipo">
           
          </select>
        </div>
            
        <div class="form-group" id="fm_editequipo_inputmodo">  
          {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
          {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
        </div>

        <div class="form-group" id="fm_editequipo_inputdescripcion">  
          {{Form::label('descripcion', 'Descripcion')}}
          {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control','id' => 'fm_editequipo_inputdescripcion'))}}
        </div>
            
        <div class="form-group" id="fm_editequipo_inputrol"> 
          <label>Disponible para el Rol:</label><br />
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="1" id="fm_editequipo_roles1" checked="true"> Alumno
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="2" id="fm_editequipo_roles2" checked="true"> PDI & PAS-Administración
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]"  value="3" id="fm_editequipo_roles3" checked="true"> PAS-Técnico (MAV)
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="5" id="fm_editequipo_roles5" checked="true"> Validador 
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="roles[]" value="6" id="fm_editequipo_roles6" checked="true"> Supervisor (EE MAV)
          </label>
        </div>

        <div class="form-group hidden">
            {{Form::text('id','',array('class' => 'form-control'))}}
        </div> 
        <div class="form-group hidden">
            {{Form::text('tipo','equipo',array('class' => 'form-control'))}}
        </div> 
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_editequipo_save">
          <i class="fa fa-save fa-fw"></i> Salvar
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->