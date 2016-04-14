<div class="divEvents" style="" data-numero-de-eventos="{{$sgrDia->eventsByHora($hora)->count()}}">
    
  @if ( $sgrDia->eventsByHora($hora)->count() > 4 ) <a style="display:none" class="cerrar" href="">Cerrar</a>@endif    
    

    <?php $i = 0?>
    @foreach($sgrDia->eventsByHora($hora) as $event)
      
      <div class="divEvent" 
            style=" position:absolute;

                    top:0px;
                    @if ($i == 0) left:0px;
                    @else left:{{ (100/$sgrDia->events()->count()) * $i}}%;
                    @endif
                    z-index:{{10 + $i}};  
                    border:2px solid white;
                    height:{{100 * $event->numeroHoras()}}%;
                    width:{{100/$sgrDia->eventsByHora($hora)->count()}}%;
                    background-color:#eee;
                    "
            data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($event->horaInicio,0,2)}}">
        <?php $i++ ?>
        <a class = " 
                    @if ($event->solape($sgrDia->timestamp()) && $event->estado != 'aprobada') text-danger
                    @elseif($event->estado == 'aprobada' && !$event->finalizada())   text-success
                    @elseif($event->finalizada())   text-info
                    @elseif ($event->estado == 'pendiente') text-info
                    @elseif ($event->estado == 'denegada')  text-warning
                    @endif
                    linkpopover linkEvento {{$event->evento_id}}  {{$event->id}}"
                    id="{{$event->id}}" 
                    data-id-serie="{{$event->evento_id}}" data-id="{{$event->id}}"  href="" rel="popover" data-html="true" 
                    data-title="{{ $event->titulo }}
                                    {{ htmlentities('<a href="" class="closePopover"> X </a>') }}" 
                    data-content="{{htmlentities( (string) View::make('calendario.allViews.tooltip')->with('time',$sgrDia->timestamp($hora,'30'))->with('sgrRecurso',$sgrDia->sgrRecurso())->with('event',$event) )}}"    
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
                aria-hidden="true"></span>
          @endif
          <!-- Title -->
          {{$sgrDia->events()->count()}}
          {{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}
          {{ $event->titulo }}

        </a>
      </div> <!-- ./divEvent -->  
    @endforeach
  </div> <!-- ./divEvents -->
  @if ($sgrDia->eventsByHora($hora)->count() > 4) <a class="linkMasEvents" href=""> + {{ $sgrDia->eventsByHora($hora)->count() - 4 }}  más </a>@endif
