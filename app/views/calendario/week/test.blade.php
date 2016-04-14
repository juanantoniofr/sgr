<tr>
  <td class="primeraColumna">
    @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
      <div style="position:relative;top:0px;width:100%;text-align:center;font-weight: bold;border:1px solid black" class="week">
        <div style="" >
          <p>{{Config::get('options.horarioApertura')[$j]}}-{{Config::get('options.horarioApertura')[$j+1]}}</p>
        </div>
      </div>
    @endfor
  </td>
  @foreach($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
  <td class="columna">
    <div style="position:relative;top:0px;width:100%;">
    @for ( $j=0; $j < count(Config::get('options.horarioApertura'))-1 ; $j++ )
      <div style="position:relative;top:0px;width:100%;" class="week">
        {{ (string) View::make('calendario.week.td')->with('sgrDia',$sgrDia)->with('hora',sgrDate::parsedatetime(Config::get('options.horarioApertura')[$j],'H:i','H')) }}
      
      <div style="position:relative;top:0px;width:100%;z-index:10" class="week">
        eventos
      </div>
      </div>
    @endfor
    </div>
  </td>
  @endforeach
</tr>