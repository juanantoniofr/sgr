<!-- marca branch master2 -->@foreach($sgrCalendario->sgrWeeks() as $sgrWeek)
	<tr>
	@foreach($sgrWeek->sgrDays() as $sgrDia)
		<td>
			{{ (string) View::make('calendario.testtd')->with('sgrDia',$sgrDia)->with('recurso',$recurso) }}
		</td>	
	@endforeach
	</tr>
@endforeach
