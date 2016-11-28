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

	<p id = "title">Título: <span>{{htmlentities($events->titulo)}}</span></p>
	<p class = "subtitle">Código: <span>{{$events->evento_id}}</span></p>
	<p class = "subtitle">Registrada por: <span>{{$events->userOwn->nombre .' '. $events->userOwn->apellidos}}</span></p>
	<p class = "subtitle">Fecha de registro: <span>{{$created_at}}</span></p>


</div>
<p>Datos de la reserva</p>
<table>
	<tr>
		<td class = "first"><p class="label">Equipo o espacio reservado</p></td>
		<td class = "first"><p>
		
		@foreach ($recursos as $recurso)	
		
			{{$recurso->recursoOwn->nombre}} <small>( {{$recurso->recursoOwn->grupo}} )</small>
			<br />
		@endforeach
		
		</p></td>
	</tr>
	<tr>
		<td class = "first"><p class="label">Estado de la reserva</p></td>
		<td class = "first {{$events->estado}}"><p>{{$events->estado}}</p></td>		
	</tr>
@if($events->repeticion == 0)
	<tr>
		<td class = "first"><p class="label">Tipo de Evento:</p></td>
		<td><p>Puntual</p></td>
	</tr>
	
	<tr>
		<td class = "first"><p class="label">Fecha del evento:</p></td>
		<td><p>{{$strDayWeek;}}, {{date('d-m-Y',strtotime($events->fechaEvento))}}</p></td>
	</tr>

	<tr>
		<td class = "first"><p class="label">Horario:</p></td>
		<td><p>{{'Desde las ' .date('G:i',strtotime($events->horaInicio)). ' hasta las '. date('G:i',strtotime($events->horaFin))}}</p></td>
	</tr>

	<tr>
		<td class = "first"><p class="label">Actividad:</p></td>
		<td><p>{{$events->actividad}}</p></td>	
	</tr>					
@else
	<tr>
		<td><p class="label">Tipo de Evento:</p></td>
		<td><p>Periódico</p></td>	
	</tr>		

	<tr>
		<td><p class="label">Fecha de inicio:</p></td>
		<td><p>{{$strDayWeekInicio;}}, {{date('d-m-Y',strtotime($events->fechaInicio))}}</p></td>
	</tr>	

	<tr>
		<td><p class="label">Fecha de finalización:</p></td>
		<td><p>{{$strDayWeekFin;}}, {{date('d-m-Y',strtotime($events->fechaFin))}}</p></td>
	</tr>

	<tr>
		<td><p class="label">Horario:</p></td>
		<td><p>{{'Desde las ' .date('G:i',strtotime($events->horaInicio)). ' hasta las '. date('G:i',strtotime($events->horaFin)) }}</p></td>
	</tr>

	<tr>
		<td><p class="label">Todos los:</p></td>
		<td><p>{{Date::DaysWeekToStr(json_decode($events->diasRepeticion))}}</p></td>		
	</tr>
@endif
	
</table>
