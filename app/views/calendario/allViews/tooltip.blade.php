<p style="width=100%;text-align:center" class=" alert @if ($sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin()))) alert-danger @elseif ($sgrEvento->estado() == 'aprobada') alert-success @elseif ($sgrEvento->estado() == 'pendiente') alert-danger @endif">
		
		Estado:<strong> Solicitud @if ($sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin()))) Solapada @else {{ucfirst($sgrEvento->estado())}} @endif</strong>

		 @if ($sgrEvento->numeroItems() > 1) ({{$sgrEvento->numeroItems()}} {{ ucwords($sgrEvento->tiporecurso()) }}/s)
	   @else ({{$sgrEvento->recurso()->nombre}})
	   @endif

</p>

<p style="width=100%;text-align:center">
	{{ucfirst(strftime('%a, %d de %B, ',$time))}}{{ sgrDate::parsedatetime($sgrEvento->horaInicio(),'H:i:s','G:i')}} - {{sgrDate::parsedatetime($sgrEvento->horaFin(),'H:i:s','G:i') }}
</p>
<p style="width=100%;text-align:center">
	{{$sgrEvento->actividad()}}
</p>

<p style="width=100%;text-align:center">{{Config::get('options.tiporeserva')[$sgrEvento->repeticion()]}}</p>

<p style="width=100%;text-align:center">{{ $sgrEvento->nombrePropietario() }} {{ $sgrEvento->apellidosPropietario()}}</p>

<hr />

@if(
		(
			$sgrEvento->userId() == Auth::user()->id 
				&& 
			$sgrRecurso->userPuedeReservar(strtotime($sgrEvento->fechaEvento()),Auth::user())
			
		)  
		|| 
		( $sgrEvento->reservadoporId() == Auth::user()->id 
				&&
			$sgrRecurso->userPuedeReservar(strtotime($sgrEvento->fechaEvento()),$sgrEvento->evento()->reservadoPor)
		)
		)	
	<a class = "comprobante" href="{{ URL::route('justificante',array('idEventos' => $sgrEvento->serieId())) }}" data-id-evento="{{ $sgrEvento->id() }}" data-id-serie="{{ $sgrEvento->serieId() }}" data-periodica="{{ $sgrEvento->repeticion() }}" title="Comprobante" target="_blank"><span class="fa fa-file-pdf-o fa-fw text-success" aria-hidden="true"></span></a>

	|
		
	<a href="#" id="edit_{{$sgrEvento->id()}}" data-id-evento="{{$sgrEvento->serieId()}}" data-id-serie="{{$sgrEvento->serieId()}}" data-periodica="{{$sgrEvento->repeticion()}}" title="Editar reserva"><span class="fa fa-pencil fa-fw text-success" aria-hidden="true"></span></a>
	|

	<a href="#" id="delete" data-id-evento="{{$sgrEvento->id()}}" data-id-serie="{{$sgrEvento->serieId()}}" data-periodica="{{$sgrEvento->repeticion()}}" title="Eliminar reserva"><span class="fa fa-trash fa-fw text-success" aria-hidden="true"></span></a>
	|
@endif
@if($sgrEvento->esAnulable(Auth::user()->id))
	<!-- anular -->
	<a  href="#"  class="anula" id="anula_{{$sgrEvento->id()}}" data-idevento="{{$sgrEvento->id()}}" data-idserie="{{$sgrEvento->serieId()}}" data-titulo="{{$sgrEvento->titulo()}}" data-usuario="{{$sgrEvento->nombrePropietario()}}" data-periodica="{{$sgrEvento->repeticion()}}" title="Anular reserva"><span class="fa fa-eraser fa-fw text-warning" aria-hidden="true"></span></a>
@endif
@if($sgrEvento->esFinalizable() && $sgrRecurso->atendidoPor(Auth::user()->id) )
	<a  href="#" class="finaliza" id="finaliza_{{$sgrEvento->id()}}" data-id-evento="{{$sgrEvento->id()}}" data-id-serie="{{$sgrEvento->serieId()}}" data-titulo="{{$sgrEvento->titulo()}}" data-usuario="{{$sgrEvento->nombrePropietario()}}" data-periodica="{{$sgrEvento->repeticion()}}" title="Finalizar reserva"><span class="fa fa-clock-o fa-fw text-warning" aria-hidden="true"></span></a>
@endif