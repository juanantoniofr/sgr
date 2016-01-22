<tr>
	<th >{{ $hora or ' ' }}</th>
@foreach ($text as $t)
	<th style = "white-space:nowrap;">@if($view == 'week') <small> @endif <b>{{ $t or ''}} </b> @if($view == 'week') </small> @endif</th>
@endforeach
</tr>