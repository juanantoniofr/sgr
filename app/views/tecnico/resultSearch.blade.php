@if (count($event) > 0)
    <p href="#" id="evento" data-observaciones="{{$event->atencion->observaciones or ''}}" data-idevento="{{$event->id}}" data-fechaevento="{{$event->fechaEvento}}" data-uvus="{{$event->userOwn->username}}" data-nombre="{{$event->userOwn->nombre}} {{$event->userOwn->apellidos}}" data-recurso="{{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}})">
            <span id = "infoEvento" class="@if($event->atendida) text-success @else text-info @endif">
            <i class="fa @if($event->atendida) fa-calendar-check-o @else fa-calendar-o @endif fa-fw"></i>
            {{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}}): {{date('d-m-Y',strtotime($event->fechaEvento))}}, {{date('G:i',strtotime($event->horaInicio))}}-{{date('G:i',strtotime($event->horaFin))}}</span>

            @if (!empty($event->atencion->observaciones) ) <span class="text-danger text-center"> ({{$event->atencion->observaciones}} )</span>@endif
        </p>
    

@else
    <div class="alert alert-danger text-center" id="nohayreservas" rol="alert">
        <span>No hay reservas para el usuario con uvus: {{$username or ''}} </span>
    </div>  
@endif 
