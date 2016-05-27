<div class="col-sm-6 col-md-3 sidebar"  style="margin-top:20px !important;">
  
  <form class="form" role="form" id="selectRecurse" >
    <div class="form-group">
    
      <label for="groupName">Seleccione recurso:</label> 
      <select class="form-control" id="selectGroupRecurse" name="groupID" >
        <option value="0" disabled selected>Espacio o equipo:</option>
        @foreach ($grupos as $grupo)
          <option value="{{$grupo->id}}">{{$grupo->nombre}}</option>
        @endforeach
      </select>
    
      <div  id="selectRecurseInGroup" style="display:none;margin-top:5px;">
        <select class="form-control" id="recurse" name="recurseName" > 
        </select>
      </div>
      
      <div  id="selectItems" style="display:none;margin-top:5px;">
        <select class="form-control" id="items" name="items" > 
        </select>
      </div>

      </div>
  </form>

  <div><label>Fecha:</label></div>
  

  <div id="datepicker" value="{{date('d-m-Y',$tsPrimerLunes)}}" style="width:190px" ></div>
  

  <span id = "dni" style="display:none"></span>
  
  @if (Auth::user()->isTecnico())
    <div style="width:216px;margin-top:10px">               
      <div id='divApplet'>
        <applet id="lector"  
                code="fcom.maviuno.LectorCarnetUniversitario/InfoUI.class" 
                codebase="https://juanantonio.us.es/sgr1/public/assets/applet"
                archive="LectorCarnetUniversitario.jar, json-simple-1.1.1.jar" 
                width=100%
                height=300px>
        </applet><!-- ./applet -->
      </div>
    </div>
  @endif
</div>
