@if ($puestos->count() > 0)
  @foreach($puestos as $puesto)
    <div class="checkbox">
      <label>
        <input type="checkbox" name="idpuestos[]" value="{{$puesto->id}}">{{$puesto->nombre}}    
      </label>
    </div>    
  @endforeach
@else
  <div class="alert alert-danger text-center" id="" rol="alert">
    <span>No hay puestos sin espacio</span>
  </div>  
@endif 