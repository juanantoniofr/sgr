@if ($addOptionReservarTodo)
  <option value="0" data-disabled="{{$disabledAll}}">Todos los puestos</option>
@endif
@foreach ($items as $item)
  <option value="{{$item->id}}" data-disabled="{{$item->disabled}}" data-atendido = @if ($item->espacio->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$item->nombre}} @if ($item->disabled) (Deshabilitado) @endif
  </option>                  
@endforeach
