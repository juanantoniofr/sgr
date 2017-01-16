@foreach ($sgrRecursos as $sgrRecurso)
  <option value="{{$sgrRecurso->id()}}" 
  				data-numeroitems="{{count($sgrRecurso->itemsVisiblesParaCapacidad(Auth::user()->capacidad))}}"
  				data-disabled="{{$sgrRecurso->isDisabled()}}" 
  				data-atendido=@if (Auth::user()->atiende)) "1" @else "0" @endif
  				@if ($sgrRecurso->isDisabled()) class="text-danger" @endif
  				>
  				{{$sgrRecurso->nombre()}} @if ($sgrRecurso->isDisabled()) (Deshabilitado) @endif 
  </option>                  
@endforeach

