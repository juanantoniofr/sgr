@if ($recursosSinGrupo->count() > 0)
  @foreach($recursosSinGrupo as $recurso)
    <div class="checkbox" id="divcheckboxid_{{$recurso->id}}">
      <label>
        <input type="checkbox" id="checkboxid_{{$recurso->id}}" name="idrecursos[]" value="{{$recurso->id}}">{{$recurso->nombre}} <b>({{Config::get('string.'.$recurso->tipo)}})</b>
      </label>
    </div>    
  @endforeach
@else
  <div class="alert alert-danger text-center" id="" rol="alert">
    <span>No hay recursos sin grupo</span>
  </div>  
@endif