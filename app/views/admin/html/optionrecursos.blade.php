 @foreach($grupos as $grupo)
    <option value = "{{$grupo->id}}">{{$grupo->nombre}}</option>
@endforeach