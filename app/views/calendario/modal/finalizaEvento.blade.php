<!-- marca branch master2 --><!-- / Modal finaliza reserva  -->
<div class="modal fade myModal-lg " id="modalFinalizareserva" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> Finalizar reserva</h3>
      </div><!-- ./modal-header -->
        

      <div class="modal-body">
          
        <p class="alert alert-danger text-center" style="display:none" rol="alert" id="msgerrorfinaliza"></p>

        <p class="alert alert-warning text-center" rol="alert">¿Seguro que desea finalizar la reserva <b><i><span id="titulofinaliza"></span></i></b> del usuario <b><i><span id="usuariofinaliza"></span></i></b>?</p>
          
        <form class="form-horizontal" role="form">
          <!-- observaciones -->  
          <div class="form-group">
            <label for="observaciones"  class="control-label col-md-2" >Observaciones <small>(Opcional)</small></label> 
            <div class="col-md-10">
              <textarea class="form-control" rows="5" name="observaciones" id="finalizaObservaciones" placeholder="Campo opcional. Indique las observaciones que considere...."></textarea>
            </div>
          </div>
        </form>
            
      </div><!-- /#modal-body -->
        

      <div class="modal-footer">
          <button type="button" class="btn btn-primary" id ="buttonModalFinaliza" data-idevento=""><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> Finalizar reserva</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div><!-- ./modal-footer -->
       
    </div><!-- ./modal-content -->
  </div><!-- ./modal-dialog -->
</div><!-- ./modal -->