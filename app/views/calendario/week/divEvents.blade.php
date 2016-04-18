<div
    class="divEvent
          @if ($event->solape($sgrDia->timestamp())) bg-danger
          @else
            @if($event->estado == 'aprobada' && !$event->finalizada()) bg-success
              @elseif($event->finalizada())  bg-info
              @elseif ($event->estado == 'pendiente') bg-primary
              @elseif ($event->estado == 'denegada')  bg-warning
            @endif
          @endif" 
    style="position:absolute;
           top:{{ ((int) Config::get('options.pxintervalo')/2) * Config::get('options.intervalo')[date('G:i',strtotime($event->horaInicio))]}}px;
           height:{{(int) Config::get('options.pxintervalo') * $event->numeroHoras()}}px;
           left:{{$sgrDia->left($event)}}%;
           width:{{$sgrDia->width($event)}}%;
           border:1px solid white;
           
           "
    data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($event->horaInicio,0,2)}}">
  
  <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
    @if ($event->solape($sgrDia->timestamp()) && $event->estado != 'aprobada')
      <span title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
    @else
      <!-- Icono -->
      <span  title="Solicitud {{$event->estado}}" 
            class=" fa fa-fw
                    @if ($event->solape($sgrDia->timestamp())) fa-ban text-danger
                    @else
                      @if($event->estado == 'aprobada' && !$event->finalizada()) fa-check text-success
                        @elseif($event->finalizada())  fa-clock-o text-info
                        @elseif ($event->estado == 'pendiente') fa-question text-primary
                        @elseif ($event->estado == 'denegada')  fa-ban text-warning
                      @endif
                    @endif" 
            aria-hidden="true"></span>
    @endif
    <!-- Title -->
    <small>{{ sgrDate::parsedatetime($event->horaInicio,'H:i:s','G:i')}}-{{sgrDate::parsedatetime($event->horaFin,'H:i:s','G:i')}}</small>
  </div>
  
  <a  class = "
              linkEvento linkpopover 
              @if ($event->solape($sgrDia->timestamp())) text-danger
              @else
                @if($event->estado == 'aprobada' && !$event->finalizada()) text-success
                  @elseif($event->finalizada())   text-info
                  @elseif ($event->estado == 'pendiente') text-info
                  @elseif ($event->estado == 'denegada')  text-warning
                @endif
              @endif
               {{$event->evento_id}}  {{$event->id}}"
      
      id="{{$event->id}}" 
      data-id-serie="{{$event->evento_id}}"
      data-id="{{$event->id}}"
      href=""
      rel="popover"
      data-html="true" 
      data-title="{{ $event->titulo }}" 
      data-content="{{htmlentities( (string) View::make('calendario.allViews.tooltip')->with('time',$sgrDia->timestamp())->with('sgrRecurso',$sgrDia->sgrRecurso())->with('event',$event) )}}"
      data-toggle="popover"
      data-trigger="focus">
    {{ $event->titulo }}
  </a>
</div> <!-- ./divEvent --> 