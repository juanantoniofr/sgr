@for ( $j=0; $j < count($horarioApertura)-1 ; $j++ )
      <tr>
            <td style="width:10px;text-align:center;font-weight: bold;" class="week">
                  {{$horarioApertura[$j]}}-{{$horarioApertura[$j+1]}}
            </td>
            @foreach($sgrWeek->sgrDays() as $sgrDia)
            
                  <td>
                        {{ (string) View::make('calendario.td')->with('sgrDia',$sgrDia)->with('view','week')->with('hora',Date::parsedatetime($horarioApertura[$j],'H:i','H'))->with('minuto',30)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo) }}
                  </td> 
            
            @endforeach
      </tr>
@endfor