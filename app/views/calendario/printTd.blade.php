@if($view == 'month') <span style="float:right;font-size:0.5em;color:#aaa">{{ $day }}</span><br />@endif
@foreach($currentday->events($id_recurso,$id_grupo) as $event)

    <span class= "evento" style = "
                @if ($event->solape(mktime(0,0,0,(int) $mon,(int) $day,(int) $year)))   color:#C12E2A
                @else
                    
                    @if($event->estado == 'aprobada')   color:#419641 @endif
                    @if ($event->estado == 'pendiente') color:#2AABD2 @endif 
                    @if ($event->estado == 'denegada')  color:#EB9316 @endif
                 @endif
               ">@if($view != 'week') 
           {{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}, @endif
        @if ($datatoprint['titulo'] == 'true') {{$event->titulo}}, @endif
        @if ($datatoprint['nombre'] == 'true') {{$event->user->nombre}} {{$event->user->apellidos}}, @endif
        @if ($datatoprint['colectivo'] == 'true') {{$event->user->colectivo}}, @endif
        @if ($datatoprint['total'] == 'true' && $event->total() > 0) {{$event->total()}} {{$event->recurso->tipo}}  /s @endif
        @if ($datatoprint['titulo'] == 'false' && $datatoprint['nombre'] == 'false' && $datatoprint['colectivo'] == 'false' && $datatoprint['total'] == 'false') No se ha seleccionado información a mostrar @endif
    </span>
@endforeach
