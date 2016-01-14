<div class = "@if($isDayAviable) formlaunch @else disable @endif" id = '.date('jnYGi',mktime($hour,$min,0,$mon,$day,$year)).' data-fecha="'.date('j-n-Y',mktime($hour,$min,0,$mon,$day,$year)).'" data-hora="'.date('G:i',mktime($hour,$min,0,$mon,$day,$year)).'">

    <div class="titleEvents"> @if($view == 'month') <small>{{ $day }}</small>@endif </div>
    <div class="divEvents" data-numero-de-eventos="{{$numeroEventos}}"></div>
        
        @if($muchosEventos)<a style="display:none" class="cerrar" href="">Cerrar</a>@endif

        @foreach($events as $event)
            @if ($event->solape($mon,$day,$year))
                <span data-toggle="tooltip" title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
            @else
                <span data-toggle="tooltip" title="Solicitud {{$event->estado}}" class=" fa fa-fw  @if ($event->estado == 'denegada') fa-ban text-warning @endif @if ($event->estado == 'aprobada') fa-check text-success @endif" aria-hidden="true"></span>
            @endif
        @endforeach
</div>