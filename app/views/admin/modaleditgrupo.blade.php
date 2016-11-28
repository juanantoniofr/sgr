<!-- / Modal update descripcion recurso  -->
 
  <div class="modal fade myModal-lg " id="modalEditarGrupo" tabindex="-3" role="dialog" aria-hidden="true">
    {{Form::open(array('method' => 'POST','role' => 'form','id'=>'formeditargrupo'))}}          

    <div class="modal-dialog modal-lg">
     
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Edición del grupo  <b><span id="titlenombregrupo"></span></b></h4>
        </div><!-- ./modal-header -->
        

        <div class="modal-body">
          
          <!-- editar nombre del grupo -->
          <div class="form-group">  
            {{Form::label('grupo', 'Grupo')}}
            <div id="error_grupo" class="text-danger hidden" ><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b id="text_error_grupo"></b></div>
              {{Form::text('grupo','',array('class' => 'form-control', 'id' => 'grupo'))}}
          </div>

          <!-- edita descripción del grupo --> 

          <div class="form-group">  
                {{Form::label('descripcion', 'Descripción')}}
                {{Form::textarea('descripcion','',array('class' => 'form-control', 'id' => 'updatedescripciongrupo'))}}
          </div>


          <div class="form-group hidden">
            {{Form::text('idRecurso','',array('class' => 'form-control','id' => 'modaldescripcionid'))}}
          </div>
         
       </div><!-- /#modal-body -->
        

        <div class="modal-footer">
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id ="saveChangeDescriptionGroup"><i class="fa fa-save fa-fw"></i> Salvar cambios</button>
          </div>
        </div><!-- ./modal-footer -->
       </form> 
       
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
    {{Form::close()}}
  </div><!-- ./modal -->