<!-- marca branch master2 -->
 @foreach($grupos as $grupo)
    <option value = "{{$grupo->id}}">{{$grupo->nombre}}
    	<i style="color:#444 !important">({{Config::get('string.'.$grupo->tipo)}})</i>
    </option>
@endforeach