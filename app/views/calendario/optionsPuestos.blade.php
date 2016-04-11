@if ($addOptionReservarTodo)
  <option value="0" data-disabled="{{$disabledAll}}">Todos los puestos</option>
@endif
@foreach ($puestos as $puesto)
  <option value="{{$puesto->id}}" data-disabled="{{$puesto->disabled}}" data-atendido = @if ($puesto->espacio->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$puesto->nombre}} @if ($puesto->disabled) (Deshabilitado) @endif
  </option>                  
@endforeach