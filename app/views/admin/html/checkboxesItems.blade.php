@if ($puestos->count() > 0)
  @foreach($items as $item)
    <div class="checkbox">
      <label>
        <input type="checkbox" name="iditems[]" value="{{$item->id}}">{{$item->nombre}}    
      </label>
    </div>    
  @endforeach
@else
  <div class="alert alert-danger text-center" id="" rol="alert">
    <span>No hay elementos que añadir....</span>
  </div>  
@endif 