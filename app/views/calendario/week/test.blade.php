<tr>
  <td class="primeraColumna">

    @foreach ( Config::get('options.horaIntervalo') as $hora )

      <div class= "day week" style="text-align:center;font-weight: bold;height: 41px !important;border:1px solid #aaa" >
        {{$hora}}
      </div>

    @endforeach

  </td>
  @foreach($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
    <td class="columna" style="" >
      <div style="position:relative;" >

      @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
          {{ (string) View::make('calendario.week.td')->with('sgrDia',$sgrDia)->with('hora',sgrDate::parsedatetime(Config::get('options.horarioApertura')[$j],'H:i','H')) }}
        
      @endfor

      @foreach ($sgrDia->events() as $event)
          
          {{ (string) View::make('calendario.week.divEvents')->with('event',$event)->with('sgrDia',$sgrDia) }}

      @endforeach 
    </div>
    </td>
  @endforeach
</tr>