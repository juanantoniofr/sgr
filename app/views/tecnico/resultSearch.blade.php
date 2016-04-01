<span style="display:none" id="usuarioAtendido" data-uvus="{{$usuarioAtendido->username}}" data-nombre="{{$usuarioAtendido->nombre}} {{$usuarioAtendido->apellidos}}"></span>

@if ($eventos->count() > 0)
	@foreach($eventos as $event)

	   <div class="radio">
        <label href="#" id="evento_{{$event->id}}" data-idevento="{{$event->id}}">
            <input type="radio" name="idevento" value="{{$event->id}}" data-observaciones="{{$event->atencion->observaciones or ''}}">
            <span id = "infoEvento_{{$event->id}}" class="@if($event->atendido()) text-success @else text-info @endif">
                <i class="fa @if($event->atendido()) fa-calendar-check-o @else fa-calendar-o @endif fa-fw"></i>
                {{$event->recurso->nombre}} ({{$event->recurso->grupo->nombre}}):
                <br /><b class="text-info">Fecha:</b> {{date('d-m-Y',strtotime($event->fechaEvento))}}
                <br /><b class="text-info">Horario:</b> {{date('G:i',strtotime($event->horaInicio))}}-{{date('G:i',strtotime($event->horaFin))}}
            </span>
        </label>
       </div>    
    @endforeach
@else
    <div class="alert alert-danger text-center" id="nohayreservas" rol="alert">
        <span>No hay reservas para el usuario con uvus: {{$usuarioAtendido->username or ''}} {{$usuarioAtendido->nombre or ''}} {{$usuarioAtendido->apellidos or ''}}</span>
    </div>  
@endif 
