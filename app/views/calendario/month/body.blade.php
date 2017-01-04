<!-- marca branch master2 -->@foreach($sgrCalendario->sgrWeeks() as $sgrWeek)
	<tr>
	@foreach($sgrWeek->sgrDays() as $sgrDia)
		<td>
			{{ (string) View::make('calendario.month.td')->with('sgrDia',$sgrDia) }}
		</td>	
	@endforeach
	</tr>
@endforeach
