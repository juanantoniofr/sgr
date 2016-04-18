<tr>
	<th width="4%"></th>
	@foreach ($sgrCalendario->sgrWeek()->sgrDays() as $sgrDia)
		<th style = "white-space:nowrap;text-align:center" @if ($sgrDia->festivo()) width="3%" @else width="19%" @endif> <b>{{ $sgrDia->abrDiaSemana() }} @if (!$sgrDia->festivo()) , {{strftime('%d/%b',$sgrDia->timestamp())}} @endif</b> </th>
	@endforeach 
</tr>