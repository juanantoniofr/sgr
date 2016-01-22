@for ( $j=0; $j < count($horas)-1 ; $j++ )
	<tr>
      	<td style="width:10px;text-align:center;font-weight: bold;" class="week"><small>{{$horas[$j]}}-{{$horas[$j+1]}}</small></td>
      	@for ( $i=0 ; $i<7 ; $i++ )
      		<td class="celda">
      			@if ($sgrWeek->dia($i)->festivo()) 
      				{{(string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',$sgrWeek->dia($i)->timestamp()))->with('fecha',$sgrWeek->dia($i)->fecha())->with('view','week')->with('festivo','festivo')}}
      			@else
      				
                              {{ (string) View::make('calendario.td')->with('day',(int) $sgrWeek->dia($i)->numerodia())->with('mon',(int) $sgrWeek->dia($i)->mes())->with('year',(int) $sgrWeek->dia($i)->year())->with('hour',(int) $horas[$j])->with('min',30)->with('view','week')->with('currentday',$sgrWeek->dia($i))->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('isDayAviable',Auth::user()->isDayAviable($sgrWeek->dia($i)->numerodia(),$sgrWeek->dia($i)->mes(),$sgrWeek->dia($i)->year())) }}
      			@endif
      		</td>		
	    @endfor
      	
	</tr>
@endfor