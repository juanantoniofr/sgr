<?php $e = unserialize($evento); ?>
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
<h3>Notificación automática Sistema de reservas fcom</h3>

<h3>Solicitud {{$e->estado}} en {{htmlentities($e->recursoOwn->nombre)}} por {{$validador}}</h3>

<div style = "border:1px solid #666;padding:10px;line-height:1.6em"> 
<h4><strong>Datos del evento:</strong></h4>

	<ul style ="list-style:none;padding:5px">
		<li class = 'subtitle'><strong>Código:</strong> <span>{{$e->evento_id}}</span></li>
		<li id = 'title'><strong>Título:</strong> <span>{{htmlentities($e->titulo)}}</span></li>
		<li class = 'subtitle'><strong>Solicitada por:</strong> <span>{{$e->userOwn->nombre}} {{$e->userOwn->apellidos}} </span></li>
		<li id = 'title'><strong>Espacio:</strong> <span>{{htmlentities($e->recursoOwn->nombre)}}</span></li>
		<li class = 'first'><strong>Estado de la reserva:</strong> {{$e->estado}}</li>		
		<li class = 'subtitle'><strong>Fecha de solicitud:</strong> <span>{{$e->created_at}}</span></li>
	</ul>
	<h4><strong>Programación del evento:</strong></h4>

	<ul style ="list-style:none;padding:5px">	
	@if($e->repeticion == 0)
		<li class = 'first'><strong>Tipo de Evento:</strong> Puntual</li>
		<li class = 'first'><strong>Fecha del evento:</strong> {{date('d-m-Y',strtotime($e->fechaEvento))}}</li>
		<li class = 'first'><strong>Horario:</strong> {{'Desde las ' .date('G:i',strtotime($e->horaInicio)). ' hasta las '. date('G:i',strtotime($e->horaFin))}}</li>
		<li class = 'first'><strong>Actividad:</strong> {{$e->actividad}}</li>
	@else
		<li class="label"><strong>Tipo de Evento:</strong> Periódico</li>
		<li class="label"><strong>Fecha de inicio:</strong> {{date('d-m-Y',strtotime($e->fechaInicio))}}</li>
		<li class="label"><strong>Fecha de finalización:</strong> {{date('d-m-Y',strtotime($e->fechaFin))}}</li>
		<li class="label"><strong>Horario:</strong> {{'Desde las ' .date('G:i',strtotime($e->horaInicio)). ' hasta las '. date('G:i',strtotime($e->horaFin)) }}</li>
		<li class="label"><strong>Todos los:</strong> {{Date::DaysWeekToStr(json_decode($e->diasRepeticion))}}</li>		
	@endif
	</ul>	

</div>