<div class="divEvent" 
     style="  position:absolute;
              top:{{ ((int) Config::get('options.pxintervalo')/2) * Config::get('options.intervalo')[date('G:i',strtotime($event->horaInicio))]}}px;
              height:{{(int) Config::get('options.pxintervalo') * $event->numeroHoras()}}px;
              left:{{$sgrDia->left($event)}}%;
              width:{{$sgrDia->width($event)}}%;
              background-color:#aaa;
              border:1px solid white;
              "
       data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($event->horaInicio,0,2)}}">
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
      data-content="{{htmlentities( (string) View::make('calendario.allViews.tooltip')->with('time',$sgrDia->timestamp(substr($event->horaInicio,0,2),'30'))->with('sgrRecurso',$sgrDia->sgrRecurso())->with('event',$event) )}}"    
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
    <small>{{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}</small><br />
    <span style="display:block;height:40px !important; overflow: hidden;
  text-overflow: ellipsis;">{{ $event->titulo }}</span>
  </a>
</div> <!-- ./divEvent --> 