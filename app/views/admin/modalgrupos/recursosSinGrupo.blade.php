@if ($recursos->count() > 0)
    @foreach($recursos as $recurso)
        <div class="checkbox">
            <label>
                <input type="checkbox" name="idrecurso[]" value="{{$recurso->id}}">{{$recurso->nombre}}    
            </label>
        </div>    
    @endforeach
@else
    <div class="alert alert-danger text-center" id="" rol="alert">
        <span>No hay recursos sin grupo</span>
    </div>  
@endif 