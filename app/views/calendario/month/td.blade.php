<div 
  class = "day month @if($sgrDia->reservable(Auth::user()->id) && !$sgrDia->festivo()) formlaunch @else disable @endif @if($sgrDia->festivo()) festivo @endif" data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" >

  <div class="titleEvents"><small>{{ $sgrDia->numerodia() }}</small></div>
  
  <div class="divEvents" data-numero-de-eventos="{{count($sgrDia->events())}}">
        
    @if ($sgrDia->events()->count() > 4) <a style="display:none" class="cerrar" href="">Cerrar</a>@endif
    
    @foreach($sgrDia->events() as $event)
      <div class="divEvent" data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($event->horaInicio,0,2)}}">
        <a class = "@if ($event->solape($sgrDia->timestamp()) && $event->estado != 'aprobada') text-danger
                    @elseif($event->estado == 'aprobada' && !$event->finalizada())   text-success
                    @elseif($event->finalizada())   text-info
                    @elseif ($event->estado == 'pendiente') text-info
                    @elseif ($event->estado == 'denegada')  text-warning
                    @endif
                    linkpopover linkEvento {{$event->evento_id}}  {{$event->id}}"
            id="{{$event->id}}" 
            data-id-serie="{{$event->evento_id}}" data-id="{{$event->id}}"  href="" rel="popover" data-html="true" 
            data-title="
                        {{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}
                        {{ $event->titulo }}
                        {{ htmlentities('<a href="" class="closePopover"> X </a>') }}" 
            data-content="{{htmlentities( (string) View::make('calendario.tooltip')->with('time',$sgrDia->timestamp())->with('event',$event) )}}"    
        >
          @if ($event->solape($sgrDia->timestamp()) && $event->estado != 'aprobada')
            <span data-toggle="tooltip" title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
          @else
            <!-- Icono -->
            <span data-toggle="tooltip" title="Solicitud {{$event->estado}}" 
                  class=" fa fa-fw
                          @if($event->estado == 'aprobada' && !$event->finalizada()) fa-check text-success
                          @elseif($event->finalizada()) fa-clock-o text-info
                          @elseif ($event->estado == 'pendiente') fa-question text-info
                          @elseif ($event->estado == 'denegada') fa-ban text-warning
                          @endif" 
                  aria-hidden="true">
            </span>
          @endif
          <!-- Title -->
          {{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}
          {{ $event->titulo }}
        </a>
      </div> <!-- ./divEvent -->  
    @endforeach
  </div> <!-- ./divEvents -->
  @if ($sgrDia->events()->count() > 4) 
    <a class="linkMasEvents" href=""> + {{ ($sgrDia->events()->count()-4) }}  más </a>
  @endif
    
</div>