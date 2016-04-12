<tr>
	<th width="9%"></th>
	@foreach ($sgrWeek->sgrDays() as $sgrDia)
		<th style = "white-space:nowrap;text-align:center" @if ($sgrDia->festivo()) width="6%" @else width="16%" @endif> <b>{{ $sgrDia->abrDiaSemana()}} @if (!$sgrDia->festivo()) , {{strftime('%d/%b',$sgrDia->timestamp())}} @endif</b> </th>
	@endforeach 
</tr>