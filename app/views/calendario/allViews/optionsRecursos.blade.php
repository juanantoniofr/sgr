@foreach ($recursos as $recurso)
  <option value="{{$recurso->id}}" 
  				@if ( $recurso->tipo == Config::get('options.espacio') ) data-numeroitems="{{$recurso->puestos()->count()}}"
  				@elseif ( $recurso->tipo == Config::get('options.tipoequipos') ) data-numeroitems="{{$recurso->equipos()->count()}}"
  				@endif
  				data-disabled="{{$recurso->disabled}}" 
  				data-atendido=@if ($recurso->esAtendidoPor(Auth::user()->id)) "1" @else "0" @endif
  				@if ($recurso->disabled) class="text-danger" @endif
  				>
  				{{$recurso->nombre}} @if ($recurso->disabled) (Deshabilitado) @endif 
  </option>                  
@endforeach