<!-- / Modal atender Reserva  -->
 
  <div class="modal fade " id="modalAtenderReserva" tabindex="-8" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-md">
     
      <div class="modal-content">
        <form> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="myModalLabel"><i class="fa fa-calendar fa-fw" ></i> Atender Reservas </h3>
        </div><!-- ./modal-header -->
        

        <div class="modal-body">
        
          <div id ="reserva">
          
          
            <label class="control-label" >Próxima reserva del usuario: <b><span id="nombreUsuario"></span></b></label>
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
          
          
          </div><!-- /#reserva -->
         
       </div><!-- /#modal-body -->
        

        <div class="modal-footer">
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- ./modal-footer -->
      
      </form> 
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
  </div><!-- ./modal -->