<tr>
  <td class="primeraColumna" >
    @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
      <div style="text-align:center;font-weight: bold;border:1px solid #fff;height: 82px !important;" >
        
          <p>{{Config::get('options.horarioApertura')[$j]}}-{{Config::get('options.horarioApertura')[$j+1]}}</p>
        
      </div>
    @endfor
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