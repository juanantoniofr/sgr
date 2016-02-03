@if (count($event) > 0)
    <p href="#" id="evento" data-observaciones="{{$event->atencion->observaciones or 'aún por atender...'}}" data-idserie="{{$event->evento_id}}" data-idevento="{{$event->id}}" data-fechaevento="{{$event->fechaEvento}}" data-uvus="{{$event->userOwn->username}} ({{$event->userOwn->nombre}} {{$event->userOwn->apellidos}})" data-recurso="{{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}})">
            <span class="@if($event->atendida) text-success @else text-warning @endif">
            <i class="fa @if($event->atendida) fa-check @else fa-info @endif fa-fw"></i>
            {{$event->recursoOwn->nombre}} ({{$event->recursoOwn->grupo}}) - ({{strftime('%d/%m/%Y',strtotime($event->fechaEvento))}}) - {{$event->horaInicio}} // {{$event->horaFin}} // {{$event->titulo}} // {{$event->estado}}</span>

            @if (!empty($event->atencion->observaciones) ) <span class="text-danger text-center"> ({{$event->atencion->observaciones}} )</span>@endif
        </p>
    

@else
    <div class="alert alert-danger text-center" id="nohayreservas" rol="alert">
        <span>No hay reservas para el usuario con uvus: {{$username or ''}} </span>
    </div>  
@endif 
