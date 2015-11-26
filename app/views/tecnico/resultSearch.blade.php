@if ($events->count() > 0)
    @foreach ($events as $event)
    	<div class="list-group">
            <a href="#" class="list-group-item reserva" data-observaciones="{{$event->atencion->observaciones or ''}}" data-idserie="{{$event->evento_id}}" data-idevento="{{$event->id}}" data-fechaevento="{{$event->fechaEvento}}" data-uvus="{{$event->userOwn->username}} ({{$event->userOwn->nombre}} {{$event->userOwn->apellidos}})" data-recurso="{{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}})">
            	<span class="@if($event->atendida) text-success @else text-warning @endif">
                <i class="fa @if($event->atendida) fa-check @else fa-info @endif fa-fw"></i>
                {{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}}) - ({{strftime('%d/%m/%Y',Date::getTimeStampEN($event->fechaEvento))}}) - {{$event->horaInicio}} // {{$event->horaFin}} // {{$event->titulo}} // {{$event->estado}}</span>

                @if (!empty($event->atencion->observaciones) ) <span class="text-danger text-center"> ({{$event->atencion->observaciones}} )</span>@endif
            </a>

        </div>
    @endforeach
@else
	<div class="alert alert-info text-center" id="nohayreservas" rol="alert">
	    <span>No hay reservas para el usuario con uvus: {{$username or ''}} </span>
	</div>  
@endif  
