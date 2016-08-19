 @foreach($recursosContenedores as $recursoContenedor)
    <option value = "{{$recursoContenedor->id}}">{{$recursoContenedor->nombre}}</option>
@endforeach