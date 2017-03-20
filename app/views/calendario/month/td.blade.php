<div 
  class = "day month @if($sgrDia->sgrRecurso()->userPuedeReservar($sgrDia->timestamp(),Auth::user()) && !$sgrDia->festivo()) formlaunch @else disable @endif @if($sgrDia->festivo()) festivo @endif" data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" >

  <div class="titleEvents"><small>{{ $sgrDia->numerodia() }}</small></div>
  
  <div class="divEvents" data-numero-de-eventos="{{$sgrDia->numeroDeEventos()}}">
        
    @if ($sgrDia->numeroDeEventos() > 4) <a style="display:none" class="cerrar" href="">Cerrar</a>@endif
    
    @foreach($sgrDia->sgrEventos() as $sgrEvento)
      
      <div class="divEvent" data-fecha="{{date('j-n-Y',$sgrDia->timestamp())}}" data-hora="{{substr($sgrEvento->horaInicio(),0,2)}}">
        <a class = "linkpopover linkEvento linkpopover_month
                    @if ($sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin()))) text-danger
                    @else
                      @if($sgrEvento->estado() == 'aprobada' && !$sgrEvento->finalizado()) text-success
                        @elseif($sgrEvento->finalizado())   text-info
                        @elseif ($sgrEvento->estado() == 'pendiente') text-info
                        @elseif ($sgrEvent->estado() == 'denegada')  text-warning
                      @endif
                    @endif
                     {{$sgrEvento->serieId()}}  {{$sgrEvento->id()}}"
            id="{{$sgrEvento->id()}}"
            data-id-serie="{{$sgrEvento->serieId()}}" 
            data-id="{{$sgrEvento->id()}}"
            href=""
            rel="popover"
            data-html="true" 
            data-title="{{ sgrDate::parsedatetime($sgrEvento->horaInicio() ,'H:i:s', 'G:i') }} - {{ sgrDate::parsedatetime($sgrEvento->horaFin(), 'H:i:s', 'G:i') }}
                        {{ $sgrEvento->titulo() }}" 
            data-content="{{htmlentities( (string) View::make('calendario.allViews.tooltip')->with('sgrDia',$sgrDia)->with('sgrRecurso',$sgrDia->sgrRecurso())->with('time',$sgrDia->timestamp())->with('sgrEvento',$sgrEvento) )}}"
            data-toggle="popover"
            data-trigger="focus"    
        >
          @if ($sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) && $sgrEvento->estado() != 'aprobada')
            <span title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>
          @else
            <!-- Icono -->
            <span  title="Solicitud @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) solapada @else {{$sgrEvento->estado()}} @endif" 
                   class=" fa fa-fw
                      @if ( $sgrDia->haySolape(strtotime($sgrEvento->horaInicio()),strtotime($sgrEvento->horaFin())) ) fa-ban text-danger
                      @else
                        @if($sgrEvento->estado() == 'aprobada' && !$sgrEvento->finalizado()) fa-check text-success
                          @elseif($sgrEvento->finalizado())  fa-clock-o text-info
                          @elseif ($sgrEvento->estado() == 'pendiente') fa-question text-primary
                          @elseif ($sgrEvento->estado() == 'denegada')  fa-ban text-warning
                        @endif
                      @endif" 
              aria-hidden="true">
            </span>
          @endif
            <!-- Title -->
          {{ sgrDate::parsedatetime($sgrEvento->horaInicio(),'H:i:s','G:i')}}-{{sgrDate::parsedatetime($sgrEvento->horaFin(),'H:i:s','G:i')}}
          {{ substr($sgrEvento->titulo(),0,45) }}
        </a>
      </div> <!-- ./divEvent -->  
    @endforeach
  </div> <!-- ./divEvents -->
  @if ($sgrDia->numeroDeEventos() > 4) 
    <a class="linkMasEvents" href=""> + {{ ($sgrDia->numeroDeEventos()-4) }}  más </a>
  @endif
    
</div>