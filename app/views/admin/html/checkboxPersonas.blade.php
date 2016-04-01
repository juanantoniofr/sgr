<!-- supervisores -->
<h3>Supervisores</h3>
	@foreach($grupo->supervisores as $supervisor)
	  <div class="checkbox" id="fm_removePersona_inputsupervisores_id">
	 	  <label>
	 		  <input type="checkbox" name="supervisores_id[]" value="{{$supervisor->id}}"> {{$supervisor->nombre}} {{$supervisor->apellidos}} ({{$supervisor->username}})
	   	</label>
	 	</div>
	@endforeach

<!-- validadores -->
<h3>Validadores</h3>
	@foreach($grupo->validadores as $validador)
    <div class="checkbox" id="fm_removePersona_inputvalidadores_id">
      <label>
        <input type="checkbox" name="validadores_id[]" value="{{$validador->id}}"> {{$validador->nombre}} {{$validador->apellidos}} ({{$validador->username}})
      </label>
    </div>
	@endforeach

<!-- técnicos -->
<h3>Técnicos</h3>
	@foreach($grupo->tecnicos as $tecnico)
    <div class="checkbox" id="fm_removePersona_inputtecnicos_id">
      <label>
 	     <input type="checkbox" name="tecnicos_id[]" value="{{$tecnico->id}}"> {{$tecnico->nombre}} {{$tecnico->apellidos}} ({{$tecnico->username}})
      </label>
    </div>
	@endforeach
