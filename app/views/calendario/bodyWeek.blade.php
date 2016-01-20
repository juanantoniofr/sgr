@for ( $j=0; $j < count($horas)-1 ; $j++ )
	<tr>
      	<td style="width:10px;text-align:center;font-weight: bold;" class="week">{{$horas[$j]}}-{{$horas[$j+1]}}</td>
      	@for ( $i=0 ; $i<7 ; $i++ )
      		<td class="celda">
      			@if ($sgrWeek->dia($i)->festivo()) 
      				{{(string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',$sgrWeek->dia($i)->timestamp()))->with('fecha',$sgrWeek->dia($i)->fecha())->with('view','week')->with('festivo','festivo')}}
      			@else
      				
                              {{ (string) View::make('calendario.testtd')->with('day',$sgrWeek->dia($i)->numerodia())->with('mon',$sgrWeek->dia($i)->mes())->with('year',$sgrWeek->dia($i)->year())->with('hour',$horas[$j])->with('min','30')->with('view','week')->with('isDayAviable',true) }}

                              
      			@endif
      		</td>		
	    @endfor
      	</td>
	</tr>
@endfor