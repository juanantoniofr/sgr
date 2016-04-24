<!-- / Modal add puesto  -->
<div class="modal fade myModal-lg" id="m_editpuesto" tabindex="-13" role="dialog" aria-hidden="true" aria-labelledby="modalEditpuestoLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_editpuesto'))}}
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title text-info"><i class="fa fa-pencil fa-fw"></i> <b>Editar Puesto:</b> <span id ="m_editpuesto_title_nombrepuesto"></span></h2>
      </div><!-- ./modal-header -->

      <div class="modal-body">
        <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_editpuesto_textError"></div>

        <div class="form-group" id="fm_editpuesto_inputid_lugar">  
          {{Form::label('id_lugar', 'Identificador de Lugar')}}
          {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="fm_editpuesto_inputnombre">
          {{Form::label('nombre', 'Nombre')}}
          {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
        </div>
              
        <div class="form-group" id="fm_editpuesto_inputtipo">  
          {{Form::label('tipo', 'Tipo de recurso')}}
          {{Form::select('tipo', array('puesto' => 'Puesto'),'puesto',array('class' => 'form-control'))}}
        </div>

        <div class="form-group" id="fm_editpuesto_inputespacio_id">
          {{Form::label('espacio_id', 'Espacio')}}
          <select name="espacio_id" class="form-control" id="fm_editpuesto_optionsEspacios">
           
          </select>
        </div>
            
        <div class="form-group" id="fm_editpuesto_inputmodo">  
          {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
          {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
        </div>

        <div class="form-group" id="fm_editpuesto_inputdescripcion">  
          {{Form::label('descripcion', 'Descripcion')}}
          {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control','id' => 'fm_editpuesto_inputdescripcion'))}}
        </div>
            
        <div class="form-group" id="fm_editpuesto_inputrol"> 
          <label>Disponible para el Rol:</label><br />
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="1" checked="true"> Alumno
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="2" checked="true"> PDI & PAS-Administración
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]"  value="3" checked="true"> PAS-Técnico (MAV)
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="5" checked="true"> Validador 
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name = "roles[]" value="6" checked="true"> Supervisor (EE MAV)
          </label>
        </div>

        <div class="form-group hidden">
            {{Form::text('id','',array('class' => 'form-control'))}}
        </div> 
        <div class="form-group hidden">
            {{Form::text('tipo','puesto',array('class' => 'form-control'))}}
        </div> 
      </div><!-- ./modal-body --> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id ="fm_editpuesto_save">
          <i class="fa fa-save fa-fw"></i> Salvar
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
      {{Form::close()}}
</div><!-- /.modal -->