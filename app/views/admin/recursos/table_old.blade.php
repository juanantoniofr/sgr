<!-- eof --> 
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th style="width:2%;text-align:center" >Id.sdasdasdasdasdasdasd</th>
      <!-- Order column by nombre de grupo--> 
      <th style="width:20%;text-align:center" >
        @if ($sortby == 'nombre' && $order == 'asc') 
          {{
          link_to_action(
                          'recursosController@listar',
                          'Grupo',
                          array(
                                'sortby' => 'nombre',
                                'order' => 'desc',
                                )
                        )
          }}
        @else
          {{
          link_to_action(
                          'recursosController@listar',
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
        <!-- grupos -->
        <td>
          <ul class="list-unstyled">
            <li>
              <!-- editar -->
              <a href="#" title="Editar grupo" class="linkEditGrupo" data-idgrupo="{{$grupo->id}}" data-descripcion="{{$grupo->descripcion}}" data-tipogrupo="{{$grupo->tipo}}" data-nombre="{{$grupo->nombre}}"><i class="fa fa-pencil fa-fw"></i></a>
              <!-- eliminar -->
              <a href="#" title="Eliminar grupo" class = "linkdelgrupo" data-idgrupo="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" data-numeroelementos="{{$grupo->recursos->count()}}"><i class="fa fa-trash-o fa-fw"></i></a>
              <!-- añadir recursos al grupo -->
              <a href="#" title="Añadir {{$grupo->tipo}} existente al grupo" class="addrecursotogrupo" data-nombre="{{$grupo->nombre}}" data-idgrupo="{{$grupo->id}}" data-tipogrupo="{{$grupo->tipo}}"><i class="fa fa-plus fa-fw"></i></a>
              {{$grupo->nombre}}
            </li>
          </ul>
        </td>
        <!-- recursos -->
        <td>
          <ul class="list-unstyled">
            @foreach($grupo->recursos as $recurso)
              <li > 
                @if ( Auth::user()->isAdmin() || $grupo->administradores->contains(Auth::user()->id) ) 
                  <!-- editar -->
                  <a href="#" title="Editar recurso" class="linkEditRecurso text-info" data-idrecurso="{{$recurso->id}}" data-id="{{$recurso->id}}" data-numeroelementos = "{{ $recurso->items->count()}}"><i class="fa fa-pencil fa-fw"></i></a>
                  <!-- eliminar -->
                  <a href="#" title="Eliminar recurso" class = "linkEliminaRecurso text-info" data-idrecurso="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" data-numeroeventos="{{$recurso->eventosfuturos()->count()}}" data-numeroelementos = "{{ $recurso->items->count()}}"><i class="fa fa-trash-o fa-fw"></i></a>
                  <!-- enabled/disabled -->
                  @if($recurso->disabled)
                    <!-- habilitar -->
                    <a id="link_{{$recurso->id}}" href="" class = "enabled text-success" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Habilitar recurso"><i class="fa fa-toggle-off fa-fw"></i></a>
                  @else    
                    <!-- deshabilitar -->
                    <a id="link_{{$recurso->id}}" href="" class = "disabled text-warning" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Deshabilitar recurso"><i class="fa fa-toggle-on fa-fw "></i></a>
                  @endif 
                  
                  <!-- Recursos tipo espacio -->
                  @if( $recurso->tipo == Config::get('options.espacio') )
                    <!-- añadir puesto existente -->
                    <a href="#" title="Añadir puesto existente" class = "linkaddPuestoExistente" data-nombre="{{$recurso->nombre}}" data-idespacio="{{$recurso->id}}" ><i class="fa fa-plus fa-fw"></i></a>
                    <!-- Nuevo Puesto -->
                    <a href="#" title="Nuevo Puesto" class = "linkAddPuesto text-info" data-contenedorid="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-plus-square fa-fw"></i></a>
                    <!-- Ver Puestos -->
                    @if($recurso->items->count() > 0)
                      <a href="#" title="Ver Puestos" class = "linkVerItems text-info" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-eye fa-fw"></i></a>
                    @endif    
                  @endif

                  <!-- Recursos tipo equipos -->  
                  @if( $recurso->tipo == Config::get('options.tipoequipos') )
                    <!-- añadir equipo existente -->
                    <a href="#" title="Añadir equipo existente" class = "linkaddEquipoExistente" data-nombre="{{$recurso->nombre}}" data-idtipoequipo="{{$recurso->id}}" ><i class="fa fa-plus fa-fw"></i></a>
                    <!-- Nuevo Equipo -->
                    <a href="#" title="Nuevo Equipo" class = "linkAddEquipo text-info" data-contenedorid="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-plus-square fa-fw"></i></a>
                    <!-- Ver Equipos -->
                    @if($recurso->items->count() > 0)
                      <a href="#" title="Ver Equipos" class = "linkVerItems text-info" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" ><i class="fa fa-eye fa-fw"></i></a>
                    @endif    
                  @endif
                @endif
                <span class="@if($recurso->disabled) text-warning @else text-success @endif">{{$recurso->nombre}}</span>
                
                @if( $recurso->tipo == Config::get('options.espacio') && $recurso->items->count() > 0 )
                  <!-- puestos -->
                  <div id="items_{{$recurso->id}}" style="display:none;border:1px dotted #ccc;margin:10px;padding:10px">
                  
                    <ul class="list-unstyled">
                    @foreach ($recurso->items as $item)
                      <li>

                        <span class="@if($item->disabled) text-warning @else text-success @endif">{{$item->nombre}}</span>
                        <!-- editar -->
                        <a href="#" title="Editar Puesto" class="linkEditPuesto text-info" data-idrecurso="{{$item->id}}" data-nombrecontenedor="{{$item->contenedor->nombre}}"><i class="fa fa-pencil fa-fw"></i></a>
                        <!-- eliminar Puesto -->
                        <a href="#" title="Eliminar Puesto" class = "linkEliminaRecurso text-info" data-idrecurso="{{$item->id}}" data-nombre="{{$item->nombre}}" data-numeroeventos="{{$item->eventosfuturos()->count()}}" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-trash-o fa-fw"></i></a>
                        @if($item->disabled)
                          <!-- habilitar Puesto -->
                          <a id="link_{{$item->id}}" href="" class = "enabled text-success" data-idrecurso="{{$item->id}}" data-nombrerecurso="{{$item->nombre}}" title = "Habilitar puesto" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-toggle-off fa-fw"></i></a>
                        @else    
                          <!-- deshabilitar Puesto-->
                          <a id="link_{{$item->id}}" href="" class = "disabled text-warning" data-idrecurso="{{$item->id}}" data-nombrerecurso="{{$item->nombre}}" title = "Deshabilitar puesto" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-toggle-on fa-fw "></i></a>
                        @endif 
                        
                      </li>
                    @endforeach
                    </ul>
                  </div>
                @endif
                
                @if( $recurso->tipo == Config::get('options.tipoequipos') && $recurso->items->count() > 0)  
                  <!-- equipos -->
                  <div id="items_{{$recurso->id}}" style="display:none;border:1px dotted #ccc;margin:10px;padding:10px">
                    <ul class="list-unstyled">
                    @foreach ($recurso->items as $item)
                      <li>
                        <span class="@if($item->disabled) text-warning @else text-success @endif">{{$item->nombre}}</span>
                        <!-- editar -->
                        <a href="#" title="Editar Equipo" class="linkEditEquipo text-info" data-idrecurso="{{$item->id}}" data-modeloequipo="{{$item->contenedor->nombre}}" ><i class="fa fa-pencil fa-fw"></i></a>
                        <!-- eliminar Equipo -->
                        <a href="#" title="Eliminar Equipo" class = "linkEliminaRecurso text-info" data-idrecurso="{{$item->id}}" data-nombre="{{$item->nombre}}" data-numeroeventos="{{$item->eventosfuturos()->count()}}" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-trash-o fa-fw"></i></a>
                        @if($item->disabled)
                          <!-- habilitar Equipo -->
                          <a id="link_{{$item->id}}" href="" class = "enabled text-success" data-idrecurso="{{$item->id}}" data-nombrerecurso="{{$item->nombre}}" title = "Habilitar equipo" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-toggle-off fa-fw"></i></a>
                        @else    
                          <!-- deshabilitar Equipo-->
                          <a id="link_{{$item->id}}" href="" class = "disabled text-warning" data-idrecurso="{{$item->id}}" data-nombrerecurso="{{$item->nombre}}" title = "Deshabilitar equipo" data-idrecursopadre="{{$item->contenedor->id}}"><i class="fa fa-toggle-on fa-fw "></i></a>
                        @endif 
                      </li>
                    @endforeach
                    </ul>
                  </div>
                @endif
              </li>
            @endforeach
          </ul>
        </td>
        <!-- Personas -->
        @if ( Auth::user()->isAdmin() || $grupo->administradores->contains(Auth::user()->id) )
        <td>
          <div id="personas_{{$grupo->id}}" style="">
            <a class="addUserWithRol text-danger"  href="" data-idgrupo="{{$grupo->id}}" data-nombregrupo="{{$grupo->nombre}}"  title="Añade usuarios con relación de administrador, tecnico y/o validador" ><i class="fa fa-user-plus fa-fw"></i></a>
            <a class="removeUserWithRol text-danger"  href="" data-idgrupo="{{$grupo->id}}" data-nombregrupo="{{$grupo->nombre}}" title="Elimina usuarios con relación de administrador, tecnico y/o validador" ><i class="fa fa-user-times fa-fw"></i></a>
                  
            <table class="table table-hover table-striped" >
              <tbody>
                <tr>
                  <td><b>administradores</b></td>
                  <td>
                    <div id="administradores_{{$grupo->id}}">
                      @foreach($grupo->administradores as $administrador)
                        {{$administrador->nombre}} {{$administrador->apellidos}} ({{$administrador->username}}).<br /> 
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
                </tr>
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