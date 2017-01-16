<div class="col-sm-6 col-md-3 sidebar"  style="margin-top:20px !important;">
  
  <form class="form" role="form" id="selectRecurse" >
    <div class="form-group">
    <label for="groupName">Seleccione espacio o medio</label> 
      <!-- grupos -->
        <select class="form-control" id="selectGroupRecurse" name="groupID" >
          <option value="0" disabled selected>Ningún grupo seleccionado</option>
          @foreach ($grupos as $grupo)
            <option value="{{$grupo->id}}" placeholder="Seleccione grupo">{{$grupo->nombre}}</option>
          @endforeach
        </select>
      <!-- ./ -->
      <!-- recursos -->
        <div  id="selectRecurseInGroup" style="display:none;margin-top:5px;">
          <select class="form-control" id="recurse" name="recurseName">          
          </select>
        </div>
      <!-- ./ -->
      <!-- items -->
        <div  id="selectItems" style="display:none;margin-top:5px;">
          <select class="form-control" id="items" name="itemName">          
          </select>
        </div>
      <!-- ./ -->
    </div>
  </form>

  <div><label>Fecha:</label></div>
  <div id="datepicker" value="{{date('d-m-Y',ACL::fristMonday())}}" style="width:190px" ></div>
</div>
