@foreach($sgrCalendario->sgrWeeks() as $sgrWeek)
	<tr>
	@foreach($sgrWeek->sgrDays() as $sgrDia)
		<td>
			{{ (string) View::make('calendario.td')->with('sgrDia',$sgrDia)->with('view','month')->with('hora',0)->with('minuto',0)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo) }}
		</td>	
	@endforeach
	</tr>
@endforeach
