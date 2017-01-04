<!-- marca branch master2 -->@for ( $j=0; $j < count($horas)-1 ; $j++ )
	<tr>
      	<td style="font-weight: bold;" width="9%"><small>{{$horas[$j]}}-{{$horas[$j+1]}}</small></td>
      	@for ( $i=0 ; $i<7 ; $i++ )
      		<td @if ($sgrWeek->dia($i)->festivo()) width="4%" @else width="17%" @endif>
      			@if ($sgrWeek->dia($i)->festivo()) 
      				{{(string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',$sgrWeek->dia($i)->timestamp()))->with('fecha',$sgrWeek->dia($i)->fecha())->with('view','week')->with('festivo','festivo')}}
      			@else
      				
                             {{ (string) View::make('calendario.printTd')->with('day',(int) $sgrWeek->dia($i)->numerodia())->with('mon',(int) $sgrWeek->dia($i)->mes())->with('year',(int) $sgrWeek->dia($i)->year())->with('hour',(int) $horas[$j])->with('min',30)->with('view','week')->with('currentday',$sgrWeek->dia($i))->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('isDayAviable',Auth::user()->isDayAviable($sgrWeek->dia($i)->timestamp(),$id_recurso))->with('datatoprint',$datatoprint) }}

      			@endif
      		</td>		
	    @endfor
      	
	</tr>
@endfor