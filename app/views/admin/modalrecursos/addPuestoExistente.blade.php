<!-- marca branch master2 --><!-- / Modal añade puesto existente a espacio  -->
<div class="modal fade myModal-lg " id="m_addpuestoExistente" tabindex="-15" role="dialog" aria-hidden="true">
  {{Form::open(array('method' => 'POST','role' => 'form','id'=>'fm_addpuestoExistente'))}}          
  <div class="modal-dialog modal-lg">
     
    <div class="modal-content">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title text-info" id="myModalLabel"><i class="fa fa-plus fa-fw"></i> Añade puestos al espacio: <b><span id="m_addpuesto_title_nombre"></span></b></h2>
      </div><!-- ./modal-header -->
      
      <div class="modal-body">
        <div class="modal_msgError alert alert-danger text-center" role="alert" id="fm_addPuestoExistente_textError"></div>
        <!-- <span id="PuestoSinEspacio"></span>-->
          @if ($itemsParaEspacios->count() > 0)
            @foreach($itemsParaEspacios as $recurso)
              <div class="checkbox" id="divcheckboxid_{{$recurso->id}}">
                <label>
                  <input type="checkbox" id="checkboxid_{{$recurso->id}}" name="idrecursos[]" value="{{$recurso->id}}">{{$recurso->nombre}}  <b>({{$recurso->tipo}})</b>  
                </label>
              </div>    
            @endforeach
          @else
            <div class="alert alert-danger text-center" id="" rol="alert">
              <span>No hay puestos sin espacio asignado</span>
            </div>  
          @endif

        <div class="form-group" id="m_addpuestoExistente_contenedor_id">
          <span id="m_addpuestoExistente_textError_contenedor_id" class="text-danger modal_spantexterror text-center"></span>
        </div>  
        <div class="form-group hidden">
          {{Form::text('contenedor_id','',array('class' => 'form-control'))}}
        </div>
      </div><!-- /#modal-body -->
      
      <div class="modal-footer">
        <div class="col-lg-12" style="margin-top:10px">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="fm_addpuestoExistente_save" data-idform="" data-idmodal="" data-iditemlista="">
            <i class="fa fa-save fa-fw"></i> Salvar         
          </button>
        </div>
      </div><!-- ./modal-footer -->
       
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
  {{Form::close()}}
</div><!-- ./modal -->