<tr>
	<th width="4%" height="18px"></th>
	@foreach ($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
		<th style = "white-space:nowrap;text-align:center;height:14px" @if ($sgrDia->festivo()) width="3%" @else width="18%" @endif > <b>{{ $sgrDia->abrDiaSemana() }} @if (!$sgrDia->festivo()) , {{strftime('%d/%b',$sgrDia->timestamp())}} @endif</b> </th>
	@endforeach 
</tr>