<style>
	* {
		font-family:verdana;
		font-size: 12px; 
	}

	div{
		border-top:none;
		border-bottom: 1px solid #333;
		border-top: 1px solid #333;
		margin-top:20px;
	}

	#title {
		font-size: 14px;
	}

	.subtitle{
		font-style: italic;
	}

	span {
		color:blue;
	}

	p.label{text-align:right;font-size:12px}

	table {
		margin-top:10px;
		padding:20px;
		width: 100%;

	}
	 td {
	 	border:1px solid #aaa;
	 }
	#first{
		background-color: #aaa;
	}
	#estado {
		boder:1px solid green;
	}
</style>

<h2>Comprobante de Reserva</h2>
<div>

	<p id = "title">Título: <span>{{htmlentities($event->titulo)}}</span></p>
	<p class = "subtitle">Código: <span>{{$event->evento_id}}</span></p>
	<p class = "subtitle">Reservado para: <span>{{$event->user->nombre .' '. $event->user->apellidos}} ({{$event->user->username}})</span></p>
	<p class = "subtitle">Reservado por: <span>{{$event->reservadoPor->nombre .' '. $event->reservadoPor->apellidos}} ({{$event->reservadoPor->username}})</span></p>
	<p class = "subtitle">Fecha de registro: <span>{{$created_at}}</span></p>
	<p class = "subtitle">Fecha de impresión: <span>{{$impreso_at}}</span></p>


</div>
<p>Datos de la reserva</p>
<table>
	<tr>
		<td class = "first"><p class="label">Equipo o espacio reservado</p></td>
		<td class = "first">
			<p>
				@foreach ($recursos as $recurso)	
					{{$recurso->recurso->nombre}} 
					@if ($recurso->recurso->tipo == Config::get('options.espacio')) 
						<i>( {{$recurso->recurso->grupo->nombre}} )</i>
					@elseif ($recurso->recurso->tipo == Config::get('options.puesto'))
						<i>( {{$recurso->recurso->espacio->nombre}} )</i>
					@elseif ($recurso->recurso->tipo == Config::get('options.equipo'))
						<i>( {{$recurso->recurso->tipoequipo->nombre}} )</i>
					@endif
				<br />
				@endforeach
			</p>
		</td>
	</tr>
	<tr>
		<td class = "first"><p class="label">Estado de la reserva</p></td>
		<td class = "first {{$event->estado}}"><p>{{$event->estado}}</p></td>		
	</tr>
@if($event->repeticion == 0)
	<tr>
		<td class = "first"><p class="label">Tipo de Evento:</p></td>
		<td><p>Puntual</p></td>
	</tr>
	
	<tr>
		<td class = "first"><p class="label">Fecha del evento:</p></td>
		<td><p>{{$strDayWeek;}}, {{date('d-m-Y',strtotime($event->fechaEvento))}}</p> @if ($event->deleted_at != NULL) <span style="color:red;">(Anulada o Eliminada)</span>@endif</td>
	</tr>

	<tr>
		<td class = "first"><p class="label">Horario:</p></td>
		<td><p>{{'Desde las ' .date('G:i',strtotime($event->horaInicio)). ' hasta las '. date('G:i',strtotime($event->horaFin))}}</p></td>
	</tr>

	<tr>
		<td class = "first"><p class="label">Actividad:</p></td>
		<td><p>{{$event->actividad}}</p></td>	
	</tr>					
@else
	<tr>
		<td><p class="label">Tipo de Evento:</p></td>
		<td><p>Periódico</p></td>	
	</tr>		

	<tr>
		<td><p class="label">Fecha de inicio:</p></td>
		<td><p>{{$strDayWeekInicio;}}, {{date('d-m-Y',strtotime($event->fechaInicio))}}</p></td>
	</tr>	

	<tr>
		<td><p class="label">Fecha de finalización:</p></td>
		<td><p>{{$strDayWeekFin;}}, {{date('d-m-Y',strtotime($event->fechaFin))}}</p></td>
	</tr>

	<tr>
		<td><p class="label">Horario:</p></td>
		<td><p>{{'Desde las ' .date('G:i',strtotime($event->horaInicio)). ' hasta las '. date('G:i',strtotime($event->horaFin)) }}</p></td>
	</tr>

	<tr>
		<td><p class="label">Días de la semana:</p></td>
		<td><p>{{sgrDate::DaysWeekToStr(json_decode($event->diasRepeticion))}}</p></td>		
	</tr>
	<tr>
		<td>
			<p class="label">Serie completa:</p>
		</td>
		<td>
	
		@foreach($recursos as $recurso)
	
			<ul>
			<b>{{$recurso->recurso->nombre}} 
					@if ($recurso->recurso->tipo == Config::get('options.espacio')) 
						<i>( {{$recurso->recurso->grupo->nombre}} )</i>
					@elseif ($recurso->recurso->tipo == Config::get('options.puesto'))
						<i>( {{$recurso->recurso->espacio->nombre}} )</i>
					@elseif ($recurso->recurso->tipo == Config::get('options.equipo'))
						<i>( {{$recurso->recurso->tipoequipo->nombre}} )</i>
					@endif</b>
			
			@foreach($recurso->recurso->events as $event)
				<li>{{sgrDate::getStrDayWeek($event->fechaEvento);}}, {{date('d-m-Y',strtotime($event->fechaEvento))}} @if ($event->deleted_at != NULL) <span style="color:red;">(Anulada o Eliminada)</span>@endif</li>
			@endforeach
			
			</ul>
	
		@endforeach
	
		</td>	
	</tr>
@endif
	
</table>
