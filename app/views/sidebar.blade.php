<div class="col-sm-6 col-md-3 sidebar"  style="margin-top:20px !important;">
  
  <form class="form" role="form" id="selectRecurse" >
    <div class="form-group">
    <label for="groupName">Seleccione recurso</label> 
      <select class="form-control" id="selectGroupRecurse" name="groupID" >
          <option value="0" disabled selected>Espacio o equipo:</option>
          @foreach ($grupos as $grupo)
            <option value="{{$grupo->grupo_id}}" placeholder="Seleccione recurso...">{{$grupo->grupo}}</option>
          @endforeach
        </select>
    
        <div  id="selectRecurseInGroup" style="display:none;margin-top:5px;">
          <select class="form-control" id="recurse" name="recurseName">          
          </select>
        </div>
      </div>
  </form>

  <div><label>Fecha:</label></div>
  <div id="datepicker" value="{{date('d-m-Y',ACL::fristMonday())}}" style="width:190px" ></div>
</div>
