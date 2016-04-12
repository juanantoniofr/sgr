@if ($addOptionReservarTodo)
  <option value="0" data-disabled="{{$disabledAll}}">Todos los {{$tipoRecursos}}/s</option>
@endif
@foreach ($recursos as $recurso)
  <option value="{{$recurso->id}}" data-numeropuestos="{{$recurso->puestos()->count()}}" data-disabled="{{$recurso->disabled}}" data-atendido = @if ($recurso->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$recurso->nombre}} @if ($recurso->disabled) (Deshabilitado) @endif 
  </option>                  
@endforeach