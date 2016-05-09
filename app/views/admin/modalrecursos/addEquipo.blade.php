<!-- / Modal add equipo  -->
<div class="modal fade myModal-lg" id="m_addequipo" tabindex="-14" role="dialog" aria-hidden="true" aria-labelledby="modalAddequipoLabel">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addequipo'))}}
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h2 class="modal-title text-info"><i class="fa fa-plus-square fa-fw"></i> <b><span id ="m_addequipo_title_nombrerecurso"></span>: </b>Añadir nuevo Equipo</h2>
        </div><!-- ./modal-header -->

        <div class="modal-body">
          <div class="divmodal_msgError alert alert-danger text-center" role="alert" id="fm_addequipo_textError"></div>

          <div class="form-group" id="fm_addequipo_inputid_lugar">  
            {{Form::label('id_lugar', 'Identificador de Lugar')}}
            {{Form::text('id_lugar',Input::old('id_lugar'),array('class' => 'form-control'))}}
          </div>
              
          <div class="form-group" id="fm_addequipo_inputnombre">
            {{Form::label('nombre', 'Nombre')}}
            {{Form::text('nombre',Input::old('nombre'),array('class' => 'form-control'))}}
          </div>
              
          <div class="form-group" id="fm_addequipo_inputtipo">  
            {{Form::label('tipo', 'Tipo de recurso')}}
            {{Form::select('tipo', array('equipo' => 'Equipo'),'equipo',array('class' => 'form-control'))}}
          </div>
            
          <div class="form-group" id="fm_addequipo_inputmodo">  
            {{Form::label('modo', 'Gestión de solicitudes de reserva')}}
            {{Form::select('modo', array('0' => 'Con Validación', '1' => 'Sin Validación'),'1',array('class' => 'form-control'))}}
          </div>

          <div class="form-group" id="fm_addequipo_inputdescripcion">  
            {{Form::label('descripcion', 'Descripcion')}}
            {{Form::text('descripcion',Input::old('descripcion'),array('class' => 'form-control','id' => 'fm_addequipo_inputdescripcion'))}}
          </div>
            
          <div class="form-group" id="fm_addequipo_inputrol"> 
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

          <div class="form-group hidden">{{Form::text('tipoequipo_id','',array('class' => 'form-control'))}}</div> 
          
          <div class="form-group hidden">{{Form::text('id','',array('class' => 'form-control'))}}</div> 
        </div><!-- ./modal-body --> 
      
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id ="fm_addequipo_save"><i class="fa fa-save fa-fw"></i> Salvar</button>
        </div><!-- /.modal-footer -->

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  {{Form::close()}}
</div><!-- /.modal -->