@if ($addOptionReservarTodo)
  <option value="0" data-disabled="{{$disabledAll}}">Todos los puestos/equipos</option>
@endif
@foreach ($items as $item)
	@if ($item->tipo == Config::get('options.puesto'))
  <option value="{{$item->id}}" data-disabled="{{$item->disabled}}" 
  				data-atendido = @if ($item->espacio->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$item->nombre}} @if ($item->disabled) (Deshabilitado) @endif
  </option>                  
  @elseif ($item->tipo == Config::get('options.equipo'))
  <option value="{{$item->id}}" data-disabled="{{$item->disabled}}" 
  				data-atendido = @if ($item->tipoequipo->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$item->nombre}} @if ($item->disabled) (Deshabilitado) @endif
  </option>                  
  @endif
@endforeach
