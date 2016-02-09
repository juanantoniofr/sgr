<!-- / Modal addEvent // editEvent  -->
<div class="modal fade myModal-lg" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="formNewEvent" aria-hidden="true">
    
    <div class="modal-dialog modal-lg">
     
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="myModalLabel">Atención de reserva: usuario <b><span id="uvusModal"></span></b></h3>
        </div><!-- ./modal-header -->
        

        <div class="modal-body">
          <form class="form-horizontal" role="form" id = "addEvent" >
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Información básica</h4>
            
             <!-- Errores --> 
            <div id = 'errorsModalAdd' class = "col-md-12 alert alert-danger text-center is_slide" style = "display:none">  
              <p id="titulo_Error" class="col-md-12" style=""></p>
              <p id="reservarParaUvus_Error" class="col-md-12" style=""></p>
              <p id="fInicio_Error" class="col-md-12" style=""></p>
              <p id="hFin_Error" class="col-md-12" style=""></p>
              <p id="fEvento_Error" class="col-md-12" style=""></p>
              <p id="fFin_Error" class="col-md-12" style=""></p>
              <p id="dias_Error" class="col-md-12" style=""></p>
            </div>

            <!-- título -->

            <div class="form-group" id="titulo">
              <label for="titulo"  class="control-label col-sm-2" >Título: </label>   
              <div class = "col-sm-10" >  
                <input type="text" name="titulo" class="form-control" id = "newReservaTitle" placeholder="Introducir título de la reserva" value="" />
              </div>             
            </div>
            
            
            <!-- Actividad -->
            <div class="form-group">
              <label class="control-label col-sm-2">Actividad:</label>
              <div class="col-sm-8">
                <select class="form-control"  name="actividad" id="tipoActividad" >
                  @if (Auth::user()->capacidad > 1)<option value="Docencia Reglada PAP">Docencia Reglada PAP</option>@endif
                  @if (Auth::user()->capacidad > 1)<option value="Títulos propios">Títulos propios</option>@endif
                  @if (Auth::user()->capacidad > 1)<option value="Otra actividad docente/investigadora">Otra actividad docente/investigadora</option>@endif
                 <option value="Autoaprendizaje">Autoaprendizaje</option>
                  @if (Auth::user()->capacidad > 1)<option value="Otra actividad">Otra actividad</option>@endif
                </select>
              </div>
            </div>

           
            <!-- Fecha  evento -->
            <div class="form-group" id="fEvento">
                  <label for="fEvento"  class="control-label col-md-2" >Fecha: </label> 
                  <div class="col-md-4" >  
                    <input type="text"  name="fEvento" class="form-control" id="datepickerFinicio" />
                  </div>
            </div>
            
            <!-- horario -->
            <div class="form-group" id="hFin">
              <label class="control-label col-sm-2">Horario desde:</label>
              <div class="col-sm-4">
                  <select class="form-control"  name="hInicio" id="newReservaHinicio" >
                    <option value="8:30">8:30</option>
                    <option value="9:30">9:30</option>
                    <option value="10:30">10:30</option>
                    <option value="11:30">11:30</option>
                    <option value="12:30">12:30</option>
                    <option value="13:30">13:30</option>
                    <option value="14:30">14:30</option>
                    <option value="15:30">15:30</option>
                    <option value="16:30">16:30</option>
                    <option value="17:30">17:30</option>
                    <option value="18:30">18:30</option>
                    <option value="19:30">19:30</option>
                    <option value="20:30">20:30</option>
                  </select>
              </div>
              <label class="control-label col-sm-2">Hasta:</label>
              <div class="col-sm-4">
                  <select class="form-control"  name="hFin" id="newReservaHfin" >
                    <option value="9:30">9:30</option>
                    <option value="10:30">10:30</option>
                    <option value="11:30">11:30</option>
                    <option value="12:30">12:30</option>
                    <option value="13:30">13:30</option>
                    <option value="14:30">14:30</option>
                    <option value="15:30">15:30</option>
                    <option value="16:30">16:30</option>
                    <option value="17:30">17:30</option>
                    <option value="18:30">18:30</option>
                    <option value="19:30">19:30</option>
                    <option value="20:30">20:30</option>
                    <option value="21:30">21:30</option>
                  </select>
              </div>
            </div>
            
           
          <div @if (!Auth::user()->puedePeriodica()) {{'style="display:none"'}} @endif>
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Periocidad</h4>

            <!-- repetición?? -->
            <div class="form-group">
              <label  class="control-label col-md-2">Repetir.... </label> 
              <div class="col-md-10">  
                  <select class="form-control" name="repetir" id="newReservaRepetir" >
                    <option value="SR">Sin repetición</option>
                    <option value="CS">Cada Semana</option>
                  </select>
              </div>
            </div>

            <!-- fecha inicio, fecha finalización y días -->            
            <div id="inputRepeticion">
              <!-- fecha inicio -->
              <div class="form-group" id="fInicio">
                  <label for="fInicio"  class="control-label col-md-2" >Empieza el: </label> 
                  <div class="col-md-4">  
                    <input type="text" name="fInicio"  class="form-control"   id="datepickerFevento"  />
                  </div>
              </div>    
              <!-- fecha finalización -->
              <div class="form-group" id="fIni">
                  <label for="fFin"  class="control-label col-md-2">Finaliza el: </label> 
                    <div class="col-md-4">  
                      <input type="text" name="fFin" class="form-control" id="datepickerFfin" />
                    </div>
              </div>
              <!-- días -->
              <div class="form-group" id="dias">
                <label  class="control-label col-md-2">Los días: </label>
                <div class="col-md-10">
                  <div class="checkbox-inline" style="display:none">
                    <label><input type="checkbox" value = "0" name="dias[]" > D</label>
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "1" name="dias[]" > L</label>
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "2" name="dias[]" > M</label>
                  </div>  
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "3" name="dias[]" > X</label>  
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "4" name="dias[]" > J</label>  
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "5" name="dias[]" > V</label>  
                  </div>
                  <div class="checkbox-inline" style="display:none">
                    <label><input type="checkbox" value = "6" name="dias[]" > S</label>
                  </div>
                </div> 
              </div>        
              
            </div>
           
          </div>  

          @if(Auth::user()->capacidad == 3 || Auth::user()->capacidad == 4)
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Reserva diaria</h4>
              <div class="form-group" id="reservadoPor">
                    <label for="reservadoPor"  class="control-label col-md-2" >Reservado por</label>   
                    <div class = "col-md-10">  
                        <input type="text" name = "reservadoPor" id = "reservadoPor" class="form-control" value="{{Auth::user()->username}}"  readonly  />
                    </div>             
            </div>

            <div class="form-group" id="reservarParaUvus" >
                    <label for="reservarParaUvus"  class="control-label col-md-2" >Reservar para</label>   
                    <div class = "col-md-10">  
                        <input type="text" name = "reservarParaUvus" class="form-control" value="" placeholder="Escriba UVUS de usuario" id = "reservadoPara"  />
                    </div>             
            </div>
           
            <!-- observaciones -->  
            <div class="form-group">
              <label for="observaciones"  class="control-label col-md-2" >Observaciones</label> 
              <div class="col-md-10">
                <textarea class="form-control" rows="5" name="observaciones_add" placeholder="por ejemplo: entrega de material, alumno responsable, descripción de la actividad....."></textarea>
              </div>
            </div>
          @endif
         
          <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Resumen</h4>
          
            <div class="alert alert-info text-center" role="alert" id="resumen"><p></p></div>
            <!-- fin elementos de edición -->
           
            <input type="hidden" name="id_recurso" id="idRecurso" value="" />
            <input type="hidden" name="action"  id="actionType" value="" />
            <input type="hidden" name="eventoid"  value=""   />
        
        
         
       </div><!-- /#modal-body -->
       
       <div class="modal-footer">
          <div class="col-md-12 " style = "display:none" id = "editOptions">
            <button type="button" class="btn btn-primary  optionedit" id="editOption1" data-id-serie="" data-id-evento="">Modificar evento</button>
          </div>
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="save"><i class="fa fa-save fa-fw"></i> Salvar</button>
          </div>
        </div><!-- ./footer --> 

       </form> 
       
      </div><!-- ./modal-content -->
    </div><!-- ./modal-dialog -->
  </div><!-- ./modal -->