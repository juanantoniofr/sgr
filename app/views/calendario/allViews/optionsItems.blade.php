@if ($addOptionReservarTodo)
  <option id="allitems" data-numeroitems = "{{count($items)}}" data-numeroitemsdisabled = "{{$numerodeitemsdisabled}}" value="0" data-disabled="{{$disabledAll}}">Todos los puestos/equipos ,{{count($items) - $numerodeitemsdisabled}}/{{count($items)}}</option>
@endif
@foreach ($items as $item)
	<option 
    @if ($item->isDisabled()) class="text-danger" @endif 
    value="{{$item->id()}}"
    data-disabled="{{$item->isDisabled()}}" 
  	data-atendido = @if ($item->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif> 
      {{$item->nombre()}} @if ($item->isDisabled()) (Deshabilitado) @endif
  </option>                  
@endforeach