<table class="table table-hover table-striped">
    
  <thead>
        <tr>
            <th style="width:2%;text-align:center" >Id.</th>
            <!-- Order column by nombre de grupo--> 
            <th style="width:20%;text-align:center" >
                            @if ($sortby == 'nombre' && $order == 'asc') {{
                                link_to_action(
                                    'GruposController@listar',
                                    'Grupo',
                                    array(
                                        'sortby' => 'nombre',
                                        'order' => 'desc',
                                    )
                                )
                            }}
                            @else {{
                                link_to_action(
                                   'GruposController@listar',
                                    'Grupo',
                                    array(
                                        'sortby' => 'nombre',
                                        'order' => 'asc',
                                        )
                                    )
                                }}
                            @endif
                            <i class="fa fa-sort fa-fw text-info"></i>
            </th>
            <th style="width:35%;text-align:center">Recursos</th>
            <th style="width:43%;text-align:center">Personas</th>
        </tr>
  </thead>
    
  <tbody>
  @foreach($grupos as $grupo)
    <tr id="tr_{{$grupo->id}}">
      <td ><ul class="list-unstyled"><li>{{$grupo->id}}</li></ul></td>
      <td>
        <ul class="list-unstyled">
          <li>
            <!-- editar -->
            <a href="#" title="Editar grupo" class="linkEditGrupo" data-idgrupo="{{$grupo->id}}" data-descripcion="{{$grupo->descripcion}}" data-nombre="{{$grupo->nombre}}"><i class="fa fa-pencil fa-fw"></i></a>
            <!-- eliminar -->
            <a href="#" title="Eliminar grupo" class = "linkdelgrupo" data-idgrupo="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" data-numeroelementos="{{$grupo->recursos->count()}}"><i class="fa fa-trash-o fa-fw"></i></a>
            
            @if ( Auth::user()->isAdmin() || $grupo->supervisores->contains(Auth::user()->id) )
              <!-- personas 
              <a href="#" class="thpersonas" data-grupoid="{{$grupo->id}}"><i class="fa fa-users fa-fw"></i></a>-->
            @endif
            
            <!-- añadir recursos al grupo -->
            <a href="#" title="Añadir recursos al grupo" class = "addrecursotogrupo" data-nombre="{{$grupo->nombre}}" data-idgrupo="{{$grupo->id}}" ><i class="fa fa-plus fa-fw"></i></a>
            {{$grupo->nombre}}
          </li>
          
                 
                

        </ul>
      </td>
      <!-- recursos -->
      <td>
        <ul class="list-unstyled">
          @foreach($grupo->recursos as $recurso)
           <li > 
              @if ( Auth::user()->isAdmin() || $grupo->supervisores->contains(Auth::user()->id) ) 
                <!-- editar -->
                <a href="#" title="Editar recurso" class="linkEditRecurso text-info" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a>
                <!-- eliminar -->
                <a href="#" title="Eliminar recurso" class = "linkEliminaRecurso text-info" data-idrecurso="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" ><i class="fa fa-trash-o fa-fw"></i></a>
                <!-- enabled/disabled -->
                @if($recurso->disabled)
                  <!-- habilitar -->
                  <a id="link_{{$recurso->id}}" href="" class = "enabled text-success" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Habilitar recurso"><i class="fa fa-toggle-off fa-fw"></i></a>
                @else    
                  <!-- deshabilitar -->
                  <a id="link_{{$recurso->id}}" href="" class = "disabled text-warning" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Deshabilitar recurso"><i class="fa fa-toggle-on fa-fw "></i></a>
                @endif 

                @if( $recurso->tipo == Config::get('options.espacio') )
                  <!-- Añadir Puesto -->
                  <a href="#" title="Añadir Puesto" class = "linkAddPuesto text-info" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-plus-square fa-fw"></i></a>
                  <!-- eliminar Puesto -->
                  <a href="#" title="Eliminar Puesto" class = "linkEliminaPuesto text-info" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-minus-square fa-fw"></i></a>
                  <!-- Ver Puestos -->
                  <a href="#" title="Ver Puestos" class = "linkVerPuesto text-info" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-eye fa-fw"></i></a>
                @endif   
              @endif
              <span class="@if($recurso->disabled) text-warning @else text-success @endif">{{$recurso->nombre}}</span>
              @foreach ($recurso->puestos as $puesto)
                <span>{{$puesto->nombre}}</span>
              @endforeach
            </li>
          @endforeach
        </ul>
      </td>
     
      <!-- Personas -->
      @if ( Auth::user()->isAdmin() || $grupo->supervisores->contains(Auth::user()->id) )
      <td>
         <div id="personas_{{$grupo->id}}" style="">
                    <a class="addUserWithRol text-danger"  href="" data-idgrupo="{{$grupo->id}}" data-nombregrupo="{{$grupo->nombre}}"  title="Añade usuarios con relación de supervisor, tecnico y/o validador" ><i class="fa fa-user-plus fa-fw"></i></a>
                    <a class="removeUserWithRol text-danger"  href="" data-idgrupo="{{$grupo->id}}" data-nombregrupo="{{$grupo->nombre}}" title="Elimina usuarios con relación de supervisor, tecnico y/o validador" ><i class="fa fa-user-times fa-fw"></i></a>
                
                    <table class="table table-hover table-striped" >
                      <tbody>
                        <tr>
                          <td><b>Supervisores</b></td>
                          <td>
                            <div id="supervisores_{{$grupo->id}}">
                              @foreach($grupo->supervisores as $supervisor)
                                {{$supervisor->nombre}} {{$supervisor->apellidos}} ({{$supervisor->username}}).<br /> 
                              @endforeach
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b>Validadores</b></td>
                          <td>
                            <div id="validadores_{{$grupo->id}}">
                              @foreach($grupo->validadores as $validador)
                                {{$validador->nombre}} {{$validador->apellidos}} ({{$validador->username}}).<br /> 
                              @endforeach
                            </div>
                          </td>
                        <tr>
                          <td><b>Técnicos</b></td>
                          <td>
                            <div id="tecnicos_{{$grupo->id}}">
                              @foreach($grupo->tecnicos as $tecnico)
                                {{$tecnico->nombre}} {{$tecnico->apellidos}} ({{$tecnico->username}}).<br /> 
                              @endforeach
                            </div>                    
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
      </td> 
      @endif 
    </tr>
    @endforeach
    </tbody>
</table>