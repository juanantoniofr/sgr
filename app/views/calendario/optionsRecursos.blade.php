@foreach ($recursos as $recurso)
  <option value="{{$recurso->id}}" data-disabled="{{$recurso->disabled}}" data-atendido = @if ($recurso->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> {{$recurso->nombre}} @if ($recurso->disabled) (Deshabilitado) @endif 
  </option>                  
@endforeach

@if ($addOptionAll)
  <option value="0" data-disabled="{{$disabledAll}}">Todos los {{$tipoRecurso}}/s</option>
@endif