@extends('layout')
 
@section('title')
    SGR: Calendarios
@stop


@section('content')

<div id="page-wrapper"> 


  <div class="row">
  <div id = "espera" style="display:none"></div>

    <div id="calendario">
      <h2>
        Calendario: <span id ="recurseName"></span> 

      </h2>
      <hr />

      <div class="form-inline pull-left" role="form">
        
        <div class="form-group">
          <button class="btn btn-danger" data-toggle="modal" data-target=".myModal-sm" id="btnNuevaReserva" data-fristday="{{date('d-m-Y',ACL::fristMonday())}}"><i class="fa fa-calendar fa-fw" ></i>
           Nueva reserva
          </button>
          <a class="btn btn-warning" id="infoButton" alt="Muestra descripción del recurso..." style="display:none" > <i class="fa fa-eye fa-fw" ></i>Descripción</a>
        </div>

      </div>  

      
      <div class="form-inline pull-right btn-group">
        <div class="btn-group" style = "margin-right:10px" id="btnNav">
          <button class="btn btn-primary" data-calendar-nav="prev" id="navprev"><< </button>
          <button class="btn btn-default active" data-calendar-nav="today" id="navhoy">Hoy</button>
          <button class="btn btn-primary" data-calendar-nav="next" id="navnext"> >></button>
        </div>
        <div class="btn-group" id = "btnView" style = "margin-right:10px">
          <!--<button class="btn btn-warning" data-calendar-view="year">Year</button>-->
          <button class="btn btn-warning active" data-calendar-view="month">Mes</button>
          <button class="btn btn-warning" data-calendar-view="week">semana</button>
          <!--<button class="btn btn-warning" data-calendar-view="day">Day</button>-->
          <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" data-container='body' title="Agenda" data-calendar-view="agenda">
          <i class="glyphicon glyphicon-list-alt"></i> Mi Agenda
          </button>
        </div>
        <div class = "btn-group"  >
          <a type="button" data-view="{{$viewActive}}" data-day="{{$day}}" data-month="{{$numMonth}}" data-year="{{$year}}"  id="btnprint" class="btn btn-primary disabled">
            <i class="fa fa-print fa-fw" ></i> Imprimir
          </a>
        </div>
     </div>


      

          
      @if(isset($msg) && !empty($msg))
        <div class="alert alert-danger col-md-12 text-center" role="alert" id="alert_msg" data-nh="{{$nh}}"><strong>{{$msg}}</strong></div> 
      @else
        <div class="alert alert-danger col-md-12 text-center" role="alert" id="alert"><strong> Por favor, seleccione espacio o medio a reservar</strong></div> 
       
      @endif
      <div style = "display:none" class="alert alert-info col-md-12 text-center" role="alert" id="msg"></div> 
      <div style = "display:none" class="alert alert-success col-md-12 text-center" role="alert" id="message"></div>
      <div style = "display:none" class="alert alert-warning col-md-12 text-center" role="alert" id="warning"></div>
    


      
      <div id="loadCalendar">  
        <table class="pull-left " style = "table-layout: fixed;width: 100%;" id="tableCalendar" >
          <caption id="tableCaption">{{$tCaption}}</caption>
          <thead id="tableHead">{{$tHead}}</thead>
          <tbody id="tableBody">{{$tBody}}</tbody>
        </table>
      </div>
    </div>   


 </div>
 <!-- /#row -->
</div>

  <!-- /#page-wrapper -->

 <!-- Modal deleteEvent
  **********************
  **********************
   -->

  <div class="modal fade deleteOptionsModal-lg " id="deleteOptionsModal" tabindex="-2" role="dialog" aria-labelledby="optionsDelete" aria-hidden="true">
    
    <div class="modal-dialog modal-lg">
      
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="deleteModalTitle">Eliminar evento</h3>
        </div>
        

        <div class="modal-body" style = "min-height:100px">
          <div  class="col-md-12 alert alert-danger text-center" rol="alert" >¿Seguro que desea eliminar el evento?</div>
        </div>
    
        <div class="modal-footer">
         
          <div class="col-md-12">
            <button type="button" class="btn btn-primary optiondel" id="option1" data-id-serie="" data-id-evento="">Eliminar evento/s</button><br />
          </div>  
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      
      </div>
    </div>
  </div>

<!-- Modal addEvent & editEvent  -->
<div class="modal fade myModal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="formNewEvent" aria-hidden="true">
    
    <div class="modal-dialog modal-lg">
     
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="myModalLabel"></h3>
        </div><!-- ./header --> 
        

        <div class="modal-body">
          <form class="form-horizontal" role="form" id = "addEvent">
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Información básica</h4>
            
            <!-- Errores --> 
            <div id = 'errorsModalAdd' class = "col-md-12 alert alert-danger text-center is_slide" style = "display:none">  
              <p id="titulo_Error" class="col-md-12" style=""></p>
              <p id="fInicio_Error" class="col-md-12" style=""></p>
              <p id="hFin_Error" class="col-md-12" style=""></p>
              <p id="fEvento_Error" class="col-md-12" style=""></p>
              <p id="fFin_Error" class="col-md-12" style=""></p>
              <p id="dias_Error" class="col-md-12" style=""></p>
            </div>


            <!-- título -->
            <div class="form-group" id="titulo">
              <label for="titulo"  class="control-label col-sm-2" >Título: </label>   
              <div class = "col-sm-10">  
                <input type="text" name = "titulo" class="form-control" placeholder="Introducir título de la reserva" id="newReservaTitle" />
              </div>             
            </div>
           
            <!-- Actividad -->
            <div class="form-group">
              <label class="control-label col-sm-2">Actividad:</label>
              <div class="col-sm-8">
                <select class="form-control"  name="actividad" id="tipoActividad">
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
            
            <!-- Fechas -->
            <div class="form-group" id="hFin">
              <label class="control-label col-sm-2">Horario desde:</label>
              <div class="col-sm-4">
                <select class="form-control"  name="hInicio" id="newReservaHinicio">
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
              <select class="form-control"  name="hFin" id="newReservaHfin">
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
      
          <div @if (ACL::withOutRepetition()) {{'style="display:none"'}} @endif>
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
                    <label><input type="checkbox" value = "0" name="dias[]"> D</label>
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "1" name="dias[]"> L</label>
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "2" name="dias[]"> M</label>
                  </div>  
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "3" name="dias[]"> X</label>  
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "4" name="dias[]"> J</label>  
                  </div>
                  <div class="checkbox-inline">
                    <label><input type="checkbox" value = "5" name="dias[]"> V</label>  
                  </div>
                  <div class="checkbox-inline" style="display:none">
                    <label><input type="checkbox" value = "6" name="dias[]"> S</label>
                  </div>
                </div> 
              </div>        
            </div>
          </div>  
          <!-- técnicos y administradores de SGR: Reservas para otros usurios -->
          @if((Auth::user()->capacidad == 3 || Auth::user()->capacidad == 4) && !empty($uvusUser))
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Reserva delegada </h4>
            <!-- observaciones -->  
            <div class="form-group" id="para">
              <label for="reservarParaUvus"  class="control-label col-md-2" >Reservar para </label>   
              <div class = "col-md-10">  
                <input type="text" name = "reservarParaUvus" class="form-control" value="{{$uvusUser or ''}}" readonly />
              </div>             
            </div>

            <div class="form-group" id="por">
              <label for="reservadoPor"  class="control-label col-md-2" >Reservado por</label>   
              <div class = "col-md-10">  
                <input type="text" name = "reservadoPor" class="form-control"  value="{{Auth::user()->nombre}} {{Auth::user()->apellidos}} ({{Auth::user()->username}})" readonly />
              </div>             
            </div>
            
          @endif
            <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Resumen</h4>
            <div class="alert alert-info text-center" role="alert" id="resumen"><p></p></div>
            <!-- fin elementos de edición -->
            
            <input type="hidden" name="id_recurso" id="idRecurso" value="" />
            <input type="hidden" name="action"  id="actionType" value="" />
            
          </form>
        </div><!-- ./body -->
        

        <div class="modal-footer">
          <div class="col-md-12 " style = "display:none" id = "editOptions">
            <button type="button" class="btn btn-primary  optionedit" id="editOption1" data-id-serie="" data-id-evento="">Modificar evento</button>
          </div>
          <div class="col-lg-12" style="margin-top:10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="save">Salvar</button>
          </div>
        </div><!-- ./footer -->

      </div>
    </div>
</div>
<!-- ./modal addEvent & editEvent -->

<!-- Modal print -->
<div class="modal fade printModal-md " id="printModal" tabindex="-3" role="dialog" aria-labelledby="print" aria-hidden="true">

    <div class="modal-dialog modal-md">
      
      <div class="modal-content">        
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title" id="printTitle">Opciones de impresión</h3>
        </div><!-- ./header -->
        
        <div class="modal-body">
          <div class="alert alert-info text-center" role="alert">Por favor, seleccione la información a incluir en la impresión</div>
            
            <div class="row">
              <div class="col-md-6 col-md-offset-4"> 
                  <div class="checkbox"> 
                    <label><input type="checkbox" id ="checktitulo" value = "titulo" name="info[]" checked /> Título</label>
                  </div>
              </div>
              <div class="col-md-6 col-md-offset-4">     
                  <div class="checkbox">
                    <label><input type="checkbox"  id = "checknombre" value = "nombre" name="info[]" /> Nombre y apellidos</label>
                  </div>
              </div>      
              <div class="col-md-6 col-md-offset-4"> 
                  <div class="checkbox">
                    <label><input type="checkbox" id = "checkcolectivo" value = "colectivo" name="info[]" /> Colectivo</label>
                  </div>  
              </div>     
              <div class="col-md-6 col-md-offset-4"> 
                  <div class="checkbox">
                    <label><input type="checkbox" id = "checktotal" value = "total" name="info[]" /> Total (puestos/equipos)</label>
                  </div>  
              </div>       

            </div>
              
        </div> <!-- ./body -->
    
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <a href="" target="_blank"class="btn btn-primary" id="modalImprimir" ><i class="fa fa-print fa-fw" ></i> Imprimir</a>
          
        </div><!-- ./footer -->
      
      </div><!-- ./content -->
    </div><!-- ./modal-dialog -->
</div>
<!-- ./modal print -->



  {{$modaldescripcion or ''}}

 @stop
@section('js')
  {{HTML::script('assets/js/calendar.js')}}
  {{HTML::script('assets/js/imprimir.js')}}
@stop
