<tr>
	<th style="width:80px;height:20px">{{ $hora or '' }}</th>
@foreach ($text as $t)
	<th style = "white-space:nowrap;font-size-adjust:none">{{ $t or ''}}</th>
@endforeach
</tr>