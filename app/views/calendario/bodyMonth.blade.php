@while ( mktime(0,0,0,(int) $mon,(int) $sgrCalendario->ultimoDia(),(int) $year) >= mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year) )
	<!-- una fila por cada semana del mes -->
	<tr class="fila">
	@for($i=1;$i<=7;$i++)
		<!-- Una celda por cada día de la semama -->
		<td class="celda">
		
			@if ( ((int) $diaSemanaPimerDiaMes > $i && $j == 1) || ((int) $diaActual > (int) $sgrCalendario->ultimoDia()) )
				{{ (string) View::make('calendario.tdFestivo') }}
				<!-- días de la primera semana y de la última que no son del mes -->
			@else
				
				@if($days[$diaActual]->festivo())
					<!-- festivo mes en curso -->
					{{ (string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year)))->with('fecha',date('j-n-Y',mktime(0,0,0,(int) $mon,(int) $diaActual,(int) $year)))->with('view','month')->with('day',(int) $diaActual)->with('festivo','festivo') }}
				@else
					<!-- disponible mes en curso -->
					{{ (string) View::make('calendario.td')->with('view','month')->with('isDayAviable',Auth::user()->isDayAviable($diaActual,$mon,$year))->with('hour',0)->with('min',0)->with('mon',$mon)->with('day',$diaActual)->with('year',$year)->with('time',mktime(0,0,0,$mon,$diaActual,$year))->with('currentday',$sgrCalendario->dia($diaActual))->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo) }}
        				
				@endif
				<?php $diaActual++; ?>
			@endif
			
		</td>
	@endfor			
	</tr>
	<?php $j++; ?>
@endwhile
