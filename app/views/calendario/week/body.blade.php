@for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
  <tr>
    <td style="width:10px;text-align:center;font-weight: bold;" class="week">
      {{Config::get('options.horarioApertura')[$j]}}-{{Config::get('options.horarioApertura')[$j+1]}}
    </td>
    @foreach($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
      <td>
        {{ (string) View::make('calendario.week.td')->with('sgrDia',$sgrDia)->with('hora',sgrDate::parsedatetime(Config::get('options.horarioApertura')[$j],'H:i','H')) }}
      </td> 
    @endforeach
  </tr>
@endfor