@if ($eventos->count() > 0)
	@foreach($eventos as $event)

	<div class="radio">
		<label href="#" id="evento_{{$event->id}}"  data-idevento="{{$event->id}}" data-uvus="{{$usuarioAtendido->username}}" data-nombre="{{$usuarioAtendido->nombre}} {{$usuarioAtendido->apellidos}}">
            <input type="radio" name="idevento" value="{{$event->id}}" data-observaciones="{{$event->atencion->observaciones or ''}}">
            <span id = "infoEvento_{{$event->id}}" class="@if($event->atendido()) text-success @else text-info @endif">
            <i class="fa @if($event->atendido()) fa-calendar-check-o @else fa-calendar-o @endif fa-fw"></i>
            {{$event->recurso->nombre}} ({{$event->recurso->grupo}}): {{date('d-m-Y',strtotime($event->fechaEvento))}}, {{date('G:i',strtotime($event->horaInicio))}}-{{date('G:i',strtotime($event->horaFin))}}</span>

            
        </label>
    	</div>    
    @endforeach
@else
    <div class="alert alert-danger text-center" id="nohayreservas" rol="alert">
        <span id="evento" data-uvus="{{$usuarioAtendido->username}}" data-nombre="{{$usuarioAtendido->nombre}} {{$usuarioAtendido->apellidos}}">No hay reservas para el usuario con uvus: {{$username or ''}} </span>
    </div>  
@endif 
