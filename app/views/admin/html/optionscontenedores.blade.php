<!-- marca branch master2 -->
@foreach($itemsContenedores as $contenedor)
  <option value = "{{$contenedor['id']}}">{{$contenedor['nombre']}}</option>
@endforeach