<p style="width=100%;text-align:center" class=" alert @if ($event->estado == 'aprobada') alert-success @endif @if ($event->estado == 'pendiente') alert-danger @endif">
	
	Estado:<strong> {{ucfirst($event->estado)}}</strong>

	 @if ($numRecursos > 0) ({{$numRecursos}} {{$event->recursoOwn->tipo}}/s)
     @else ({{$event->recursoOwn->nombre}})
     @endif

</p>

<p style="width=100%;text-align:center">{{ucfirst(strftime('%a, %d de %B, ',$time))}}{{ Date::parsedatetime($event->horaInicio,'H:i:s','G:i')}} - {{Date::parsedatetime($event->horaFin,'H:i:s','G:i') }}</p><p style="width=100%;text-align:center">{{$event->actividad}}</p>

<p style="width=100%;text-align:center">{{Config::get('options.tiporeserva')[$event->repeticion]}}</p>

<p style="width=100%;text-align:center">{{$event->userOwn->nombre }} {{$event->userOwn->apellidos}}</p>

<hr />
@if($esEditable && $isDayAviable)
	<a class = "comprobante" href="{{ URL::route('justificante',array('idEventos' => $event->evento_id)) }}" data-id-evento="{{ $event->id }}" data-id-serie="{{ $event->evento_id }}" data-periodica="{{ $event->repeticion }}" title="Comprobante" target="_blank"><span class="fa fa-file-pdf-o fa-fw text-success" aria-hidden="true"></span></a>
	|

	<a href="#" id="edit_{{$event->id}}" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-periodica="{{$event->repeticion}}" title="Editar reserva"><span class="fa fa-pencil fa-fw text-success" aria-hidden="true"></span></a>
	|

	<a href="#" id="delete" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-periodica="{{$event->repeticion}}" title="Eliminar reserva"><span class="fa fa-trash fa-fw text-success" aria-hidden="true"></span></a>
	|
@endif
@if($esAnulable)
	<a  href="#"  class="libera" id="libera_{{$event->id}}" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-titulo="{{$event->titulo}}" data-usuario="{{$event->userOwn->nombre}}" data-periodica="{{$event->repeticion}}" title="Anular reserva"><span class="fa fa-eraser fa-fw text-warning" aria-hidden="true"></span></a>
@endif
@if($esFinalizable)
	<a  href="#" class="finaliza" id="finaliza_{{$event->id}}" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-titulo="{{$event->titulo}}" data-usuario="{{$event->userOwn->nombre}}" data-periodica="{{$event->repeticion}}" title="Finalizar reserva"><span class="fa fa-clock-o fa-fw text-warning" aria-hidden="true"></span></a>
@endif