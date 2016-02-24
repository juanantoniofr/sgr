@foreach($sgrCalendario->sgrWeeks() as $sgrWeek)
	<tr class="fila">
	@foreach($sgrWeek->sgrDays() as $sgrDia)
		<td class="celda" @if ($sgrDia->festivo()) width="5%" @else width="18%" @endif>
			@if ($sgrDia->festivo()) 
      				{{(string) View::make('calendario.tdFestivo')->with('idfecha',date('jnY',$sgrDia->timestamp()))->with('fecha',$sgrDia->fecha())->with('view','month')->with('festivo','festivo')}}
      		@else
      				{{ (string) View::make('calendario.printTd')->with('day',(int) $sgrDia->numerodia())->with('mon',(int) $sgrDia->mes())->with('year',(int) $sgrDia->year())->with('view','month')->with('currentday',$sgrDia)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('isDayAviable',Auth::user()->isDayAviable($sgrDia->timestamp(),$id_recurso))->with('datatoprint',$datatoprint) }}
			@endif
		</td>	
	@endforeach
	</tr>
@endforeach
