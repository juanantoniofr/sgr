<div class="col-lg-12">
        <h3 class=""><i class="fa fa-check fa-fw"></i> Validaciones</h3>
        
        
        <form class="navbar-form navbar-left">    
            <div class="form-group ">
                <a href="{{route('calendarios.html')}}" class="btn btn-danger" title="Añadir nueva reserva"><i class="fa fa-calendar fa-fw"></i> Nueva reserva</a>
            </div>
           <!-- <div class="form-group">
                <a href="{{route('validadorHome.html',array('verpendientes' => true))}}" class="btn btn-primary" title="Listar solicitudes"><i class="fa fa-list fa-fw"></i> Listar</a>
            </div>                      
               --> 
        </form>

        <form class="navbar-form navbar-right" id ="filter">    
                    
                    <div class="form-group"> 
                        <label>Ver: </label>
                        <label class="checkbox-inline">
                          <input type="checkbox"  name = "verpendientes" id = "verpendientes" value="1" @if ($verpendientes) checked="true" @endif> Pendientes
                        </label>
                         <label class="checkbox-inline">
                          <input type="checkbox" name = "veraprobadas" id = "veraprobadas" value="1" @if ($veraprobadas) checked="true" @endif> Aprobadas
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name = "verdenegadas"  id = "verdenegadas" value="1" @if ($verdenegadas) checked="true" @endif> Denegadas
                        </label>
                    
                    </div>

                    <div class="form-group ">
                       <select class="form-control " name = "id_recurso" id="selectRecurso">
                            <option value="0" @if($id_recurso == 0) selected="selected" @endif >Todos los espacios</option>
                            @foreach($eventsByrecurso as $event)
                                <option value="{{$event->recurso_id}}" 
                                    @if ($id_recurso == $event->recurso_id) selected="selected" @endif>
                                    {{$event->recursoOwn->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>       

                    <div class="form-group">
                        <select class="form-control" id="selectUser" name="id_user">
                            <option value="0" @if($id_user == 0) selected="selected" @endif>Todos los usuarios</option>
                            @foreach($eventsByUser as $event)
                                <option value="{{$event->userOwn->id}}" 
                                    @if($id_user == $event->userOwn->id)selected="selected" @endif>
                                    {{$event->userOwn->apellidos}}, {{$event->userOwn->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div> 
                                    
                    <button type="submit" class="btn btn-primary form-control" role="submit"><i class="fa fa-filter fa-fw"></i> Filtrar listado</button> 
        </form>


        
</div>