<!-- / Modal atender Reserva  -->
 
  <div class="modal fade " id="modalAtenderReserva" tabindex="-8" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-md">
     
      <div class="modal-content">
       
        <form id="atenderEvento"> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="myModalLabel"><i class="fa fa-calendar fa-fw" ></i> Atención de Reservas </h3>
        </div><!-- ./modal-header -->
        

        <div class="modal-body">
        
          <div id ="reserva">
            <label class="control-label" >Próxima/s reserva/s de <b><span class="text-info" id="nombreUsuario"></span></b>:</label>
            <div class="alert alert-success text-center" id="msgModalAtender" style="display:none">Datos salvados con éxito...</div>
            <p class="" id="resultsearch" ></p>
            <!-- atendido por -->
            <div class="form-group" id="atendidoPor" >
                <label for="atendidoPor"  class="control-label" >Atendido por:</label>   
                <input type="text" name = "atendidoPor" class="form-control" value="{{Auth::user()->username}}"  readonly  id = "atendidoPor"  />
            </div>
             
              <!-- observaciones -->  
              <div class="form-group">
                <label for="observaciones"  class="control-label" >Observaciones</label> 
                  <textarea class="form-control" rows="5" name="observaciones" placeholder="por ejemplo: entrega de material, alumno responsable, descripción de la actividad....."></textarea>
              </div>
            
            <input type="hidden" name="idtecnico" id="idtecnico"  value="{{Auth::user()->id}}"   />
          
          </div><!-- /#reserva -->
         
       </div><!-- /#modal-body -->
        

        <div class="modal-footer">
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id = "atender">Atender</button>
          </div>
        </div><!-- ./modal-footer -->
      
      </form> 
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
  </div><!-- ./modal -->