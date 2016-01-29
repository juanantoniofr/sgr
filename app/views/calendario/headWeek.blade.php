<tr>
	<th >{{ $hora or ' ' }}</th>
@foreach ($text as $t)
	<th style = "white-space:nowrap;">@if($view == 'week') @endif <b>{{ $t or ''}} </b> @if($view == 'week')  @endif</th>
@endforeach
</tr>