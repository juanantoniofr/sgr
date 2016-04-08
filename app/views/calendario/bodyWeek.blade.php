@for ( $j=0; $j < count($horarioApertura)-1 ; $j++ )
      <tr>
            <td style="width:10px;text-align:center;font-weight: bold;" class="week">
                  {{$horarioApertura[$j]}}-{{$horarioApertura[$j+1]}}
            </td>
            @foreach($sgrWeek->sgrDays() as $sgrDia)
            
                  <td>
                        {{ (string) View::make('calendario.td')->with('sgrDia',$sgrDia)->with('view','week')->with('hora',sgrDate::parsedatetime($horarioApertura[$j],'H:i','H'))->with('minuto',30)->with('id_recurso',$sgrCalendario->sgrRecurso()->recurso()->id)->with('id_grupo',$sgrCalendario->sgrRecurso()->recurso()->grupo_id) }}
                  </td> 
            
            @endforeach
      </tr>
@endfor