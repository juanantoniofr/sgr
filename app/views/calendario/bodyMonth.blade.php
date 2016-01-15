@while ( mktime(0,0,0,(int) $mon,(int) $sgrCalendario->ultimoDia(),(int) $year) >= mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year) )
	<!-- una fila por cada semana del mes -->
	<tr class="fila">
	@for($i=1;$i<=7;$i++)
		<!-- Una celda por cada día de la semama -->
		<td class="celda">
		<!-- días de la primera semana y de la última que no son del mes -->
			@if ( ((int) $diaSemanaPimerDiaMes > $i && $j == 1) || ((int) $diaActual > (int) $sgrCalendario->ultimoDia()) )
				{{ (string) View::make('calendario.tdFestivo') }}
			@else
				<!-- días del mes en curso -->
				@if($days[$diaActual]->festivo())
					{{ (string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year)))->with('fecha',date('j-n-Y',mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year)))->with('view','month')->with('day',(int) $diaActual)->with('festivo','festivo') }}
				@else
					<!-- (count($events) > 4) ? $muchosEventos=true : $muchosEventos=false; -->
					{{ (string) View::make('calendario.td')->with('view','month')->with('isDayAviable',Auth::user()->isDayAviable($diaActual,$mon,$year))->with('hour',0)->with('min',0)->with('mon',$mon)->with('day',$diaActual)->with('year',$year)->with('numeroEventos',$days[$diaActual]->numeroEventos())->with('muchosEventos',$muchosEventos)->with('events',$days[$diaActual]->events())->with('time',mktime(0,0,0,$mon,$diaActual,$year)) }}
        				
				@endif
				<?php $diaActual++; ?>
			@endif
			
		</td>
	@endfor			
	</tr>
	<?php $j++; ?>
@endwhile