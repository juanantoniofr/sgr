@if ($addOptionReservarTodo)
  <option id="allitems" data-numeroitems = "{{$items->count()}}" data-numeroitemsdisabled = "{{$numerodeitemsdisabled}}" value="0" data-disabled="{{$disabledAll}}">Todos los puestos/equipos ,{{$items->count() - $numerodeitemsdisabled}}/{{$items->count()}}</option>
@endif
@foreach ($items as $item)
	@if ($item->tipo == Config::get('options.puesto'))
  <option @if ($item->disabled) class="text-danger" @endif value="{{$item->id}}" data-disabled="{{$item->disabled}}" 
  				data-atendido = @if ($item->espacio->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$item->nombre}} @if ($item->disabled) (Deshabilitado) @endif
  </option>                  
  @elseif ($item->tipo == Config::get('options.equipo'))
  <option @if ($item->disabled) class="text-danger" @endif value="{{$item->id}}" data-disabled="{{$item->disabled}}" 
  				data-atendido = @if ($item->tipoequipo->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$item->nombre}} @if ($item->disabled) (Deshabilitado) @endif
  </option>                  
  @endif
@endforeach
