@if(count($events)>0)
	
	<table class="table table-bordered">
	<thead>
		<th width="8%">Fecha</th>
		<th style="width:8%">Horario</th>
		<th width="30%">Espacio//Medio</th>
		<th>Título</th>
	</thead>
	<tbody>
	@foreach ($events as $event)
	<tr  id="{{$event->id}}">

		<td >
			<div>{{date('d-m-Y',strtotime($event->fechaEvento))}}</div>
		</td>
		
		<td >
			{{date('H:i',strtotime($event->horaInicio))}} - {{date('H:i',strtotime($event->horaFin))}}
		</td>
					
		
					
		<td  height="45px">
				{{$event->recurso->nombre}} <small> {{$event->recurso->grupo}}</small>
		</td>
		<td  style="text-align:left">
			<a class = "comprobante" href="{{URL::route('justificante',array('idEventos' => $event->evento_id))}}" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-periodica="{{$event->repeticion}}" title="Comprobante" target="_blank"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
			</a>
		
			@if($event->esEditable())	
				<a href="" class="agendaEdit" id = "edit_agenda_{{$event->id}}" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-periodica="{{$event->repeticion}}"><span class="fa fa-pencil fa-fw"></span></a>

				<a href="#" class="delete_agenda" data-id-evento="{{$event->id}}" data-id-serie="{{$event->evento_id}}" data-periodica="{{$event->repeticion}}" ><span class="fa fa-trash-o fa-fw"></span>
				</a>
							
			@endif
						
			<span >{{$event->titulo}}</span> 

		</td>
	</tr>
	@endforeach
	</tbody>
	</table>
	
	
@else
	<div class="alert alert-danger col-md-12 text-center" role="alert" id="alert_evento"><strong> No hay eventos</strong></div>
@endif