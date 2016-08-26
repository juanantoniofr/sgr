<!-- Administradores -->
<h3>Administradores</h3>
	@foreach($grupo->administradores as $administrador)
	  <div class="checkbox" id="fm_removePersona_inputadministradores_id">
	 	  <label>
	 		  <input type="checkbox" name="administradores_id[]" value="{{$administrador->id}}"> {{$administrador->nombre}} {{$administrador->apellidos}} ({{$administrador->username}})
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
