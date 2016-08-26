<!-- :) -->
<div class="col-lg-12">
  <h2 class=""><i class="fa fa-users fa-fw"></i> Gestión de Usuarios</h2>
        
  <div class="row">
    <form class="navbar-form navbar-left">
      <div class="form-group ">
        <a href="" class="active btn btn-danger" id="addUser" title="Añadir nuevo usuario"><i class="fa fa-plus fa-fw"></i> Añadir nuevo usuario</a>
      </div>
      <div class="form-group ">
        <a href="{{route('users')}}" class="btn btn-primary" title="Listar usuarios"><i class="fa fa-list fa-fw"></i> Listar cuentas activas</a>
      </div>                            
    </form>
  </div>
       
  <div class="row">
    <form class="navbar-form navbar-left" role="search" id="filtrarUsuarios">    
      <div class="form-group ">
        <input type="text" class="form-control" id="search" placeholder="Con UVUS...." name="search" >
      
      </div>                            

      <div class="form-group"> 
        <label class="radio-inline"><input @if($veractivas) checked="true" @endif type="radio" name="veractivas" id="versactivas" value="1">Activas</label>
        <label class="radio-inline"><input @if(!$veractivas) checked="true" @endif type="radio" name="veractivas" id="versactivas" value="0">No activas</label>
      </div>        
      
      <div class="form-group ">
          <select class="form-control " name = "colectivo" id="selectColectivo">
            <option value="" @if(empty($colectivo)) selected="selected" @endif >Colectivo</option>
              @foreach($colectivos as $nombreColectivo)
                <option value="{{$nombreColectivo}}" 
                  @if ($colectivo == $nombreColectivo) selected="selected" @endif>
                    {{$nombreColectivo}}
                </option>
              @endforeach
          </select>
      </div>    

      <div class="form-group ">
          <select class="form-control" name = "perfil" id="selectPerfil">
            <option value="" @if(empty($perfil)) selected="selected" @endif >Rol</option>
            @foreach($perfiles as $key => $nombrePerfil)
              <option value="{{$key}}" 
                @if ($perfil == $key) selected="selected" @endif>
                    {{$nombrePerfil}}
              </option>
            @endforeach
          </select>
      </div>    
      <div class="form-group ">
        <button type="submit" class="btn btn-primary form-control"><i class="fa fa-filter fa-fw"></i> Aplicar filtro</button>
      </div> 
    </form>
  </div>
</div>