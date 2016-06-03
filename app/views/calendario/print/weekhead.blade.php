<tr>
	<th style = "text-align:left;height:14px;border:none" width="9%"></th>
	@foreach ($sgrWeek->sgrDays() as $sgrDia)
		<th style = "text-align:left;height:14px;border:none" @if ($sgrDia->festivo()) width="4%" @else width="17%" @endif> <small><b>@if ( $sgrDia->festivo() ) {{ substr($sgrDia->abrDiaSemana(),0,1) }} @else {{ $sgrDia->abrDiaSemana() }}, {{strftime('%d/%b',$sgrDia->timestamp())}} @endif</b></small> </th>
	@endforeach 
</tr>