@foreach ($recursos as $recurso)
  <option value="{{$recurso->id}}" 
  				data-numeroitems="{{$recurso->items->count()}}"
  				data-disabled="{{$recurso->disabled}}" 
  				data-atendido=@if (Auth::user()->atiende)) "1" @else "0" @endif
  				@if ($recurso->disabled) class="text-danger" @endif
  				>
  				{{$recurso->nombre}} @if ($recurso->disabled) (Deshabilitado) @endif 
  </option>                  
@endforeach