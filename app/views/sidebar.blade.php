<div class="col-sm-6 col-md-3 sidebar"  style="margin-top:20px !important;">
  
  <form class="form" role="form" id="selectRecurse" >
    <div class="form-group">
    <label for="groupName">Seleccione recurso</label> 
      <select class="form-control" id="selectGroupRecurse" name="groupID" >
          <option value="0" disabled selected>Espacio o equipo:</option>
          @foreach ($grupos as $grupo)
            <option value="{{$grupo->grupo_id}}">{{$grupo->grupo}}</option>
          @endforeach
        </select>
    
        <div  id="selectRecurseInGroup" style="display:none;margin-top:5px;">
          <select class="form-control" id="recurse" name="recurseName" > 
            <option value="0" disabled selected> --- </option>
              @foreach ($recursos as $recurso)
                <option value="{{$recurso->id}}" >{{$recurso->nombre}}</option>
              @endforeach         
          </select>
        </div>
      </div>
  </form>

  <div><label>Fecha:</label></div>
  

  <div id="datepicker" value="{{date('d-m-Y',$tsPrimerLunes)}}" style="width:190px" ></div>
  

  uvus: <span id = "dni" ></span>
  
 
  <div style="width:216px">               
    <div id='divApplet'>
      <applet id="lector"  
              code="fcom.maviuno.LectorCarnetUniversitario/InfoUI.class" 
              codebase="https://juanantonio.us.es/sgr1/public/assets/applet"
              archive="LectorCarnetUniversitario.jar, json-simple-1.1.1.jar" 
              width=100%
              height=100% >
      </applet><!-- ./applet -->
    </div>
  </div>
</div>
