<tr>
  <td class="primeraColumna" width="4%">
    <div style="position:relative;" >
    @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
      <div style="position:absolute;border-bottom:1px dotted #345;top:{{$j*82;}}px;height:41px;width:100%">&nbsp;</div>
      <div style="text-align:right;border-top:1px solid #ccc;height: 82px !important;" >
        
          <span>{{Config::get('options.horarioApertura')[$j]}}</span>
        
      </div>
    @endfor
    </div>
  </td>
  @foreach($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
    <td class="columna" style="" >

      <div style="position:relative;" >

      @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
          
          {{ (string) View::make('calendario.week.td')->with('sgrDia',$sgrDia)->with('hora',sgrDate::parsedatetime(Config::get('options.horarioApertura')[$j],'H:i','H'))->with('j',$j) }}
        
      @endfor

      @foreach ($sgrDia->events() as $event)
          
          {{ (string) View::make('calendario.week.divEvents')->with('event',$event)->with('sgrDia',$sgrDia) }}

      @endforeach 
    </div>
    </td>
  @endforeach
</tr>