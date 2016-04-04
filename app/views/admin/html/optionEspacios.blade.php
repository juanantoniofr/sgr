 @foreach($espacios as $espacio)
    <option value = "{{$espacio->id}}">{{$espacio->nombre}}</option>
@endforeach