<div
    class="divEvent
          @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) bg-danger
          @else
            @if($sgrEvento->estado() == 'aprobada' && !$sgrEvento->finalizada()) bg-success
              @elseif($sgrEvento->finalizada())  bg-info
              @elseif ($sgrEvento->estado() == 'pendiente') bg-primary
              @elseif ($sgrEvento->estado() == 'denegada')  bg-warning
            @endif
          @endif" 
    style="position:absolute;
           top:{{ ((int) Config::get('options.pxintervalo')/2) * Config::get('options.intervalo')[date('G:i',strtotime($sgrEvento->horaInicio() ))]}}px;
           height:{{(int) Config::get('options.pxintervalo') * $sgrEvento->numeroHoras()}}px;
           left:{{$sgrDia->left($sgrEvento)}}%;
           width:{{$sgrDia->width($sgrEvento)}}%;
           border:1px solid white;
           z-index:{{$sgrDia->left($sgrEvento) + floor($sgrEvento->numeroHoras())}};

           "
    data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($sgrEvento->horaInicio(),0,2)}}">
  
    <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
      @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) && $sgrEvento->estado() != 'aprobada')
        <span title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
      @else
        <!-- Icono -->
        <span  title="Solicitud @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) solapada @else {{$sgrEvento->estado()}} @endif" 
              class=" fa fa-fw
                      @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) fa-ban text-danger
                      @else
                        @if($sgrEvento->estado() == 'aprobada' && !$sgrEvento->finalizada()) fa-check text-success
                          @elseif($sgrEvento->finalizada())  fa-clock-o text-info
                          @elseif ($sgrEvento->estado() == 'pendiente') fa-question text-primary
                          @elseif ($sgrEvento->estado() == 'denegada')  fa-ban text-warning
                        @endif
                      @endif" 
              aria-hidden="true"></span>
      @endif
      <!-- Title -->
      <small>{{ sgrDate::parsedatetime($sgrEvento->horaInicio(),'H:i:s','G:i')}}-{{sgrDate::parsedatetime($sgrEvento->horaFin(),'H:i:s','G:i')}}</small>
    </div>
    <div style="overflow:hidden;text-overflow:ellipsis;word-wrap: break-word;max-height:{{(int) (Config::get('options.pxintervalo') * $sgrEvento->numeroHoras()) - 20}}px">
      <a  class = "
                linkEvento linkpopover linkpopover_week
                @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) text-danger
                @else
                  @if($sgrEvento->estado() == 'aprobada' && !$sgrEvento->finalizada()) text-success
                    @elseif($sgrEvento->finalizada())   text-info
                    @elseif ($sgrEvento->estado() == 'pendiente') text-info
                    @elseif ($sgrEvento->estado() == 'denegada')  text-warning
                  @endif
                @endif
                 {{$sgrEvento->serieId()}}  {{$sgrEvento->id()}}"
        
          id="{{$sgrEvento->id()}}" 
          data-id-serie="{{$sgrEvento->serieId()}}"
          data-id="{{$sgrEvento->id()}}"
          href=""
          rel="popover"
          data-html="true" 
          data-title="{{ $sgrEvento->titulo() }}" 

          

          data-content="{{htmlentities( (string) View::make('calendario.allViews.tooltip')->with('sgrDia',$sgrDia)->with('time',$sgrDia->timestamp())->with('sgrRecurso',$sgrDia->sgrRecurso())->with('sgrEvento',$sgrEvento) )}}"
          


          data-toggle="popover"
          data-trigger="focus"
          data-placement="auto left"
          data-container="#anotherDiv_{{$sgrEvento->id()}}">
        {{ $sgrEvento->titulo() }}
      </a>
    </div>
</div> <!-- ./divEvent --> 
<div id="anotherDiv_{{$sgrEvento->id()}}"></div>
