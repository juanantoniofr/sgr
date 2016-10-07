<ul class="list-group">   
@foreach($grupos as $grupo)
  
  <li  class="list-group-item col-md-12 listitemgrupo">
    <a class="text-success toggleOpcionesGrupo listarRecursos" href= "#spanopcionesgrupo_{{$grupo->id}}" data-ulrecursosdelgrupo="#ulrecursosdelgrupo_{{$grupo->id}}" data-grupoid="{{$grupo->id}}" id="{{$grupo->id}}"><i  class=" i_{{$grupo->id}} fa fa-angle-double-down fa-fw"></i>{{$grupo->nombre}}</a>
    <!-- Opciones de administración del grupo -->
    @if ( Auth::user()->isAdmin() || $grupo->administradores->contains(Auth::user()->id) ) 
      <span style="display:none" id="spanopcionesgrupo_{{$grupo->id}}" class="opcionesGrupo">
        <!-- grupo -->
        <a class="linkEditGrupo" href="#" title="Editar grupo" data-idgrupo="{{$grupo->id}}" data-descripcion="{{$grupo->descripcion}}" data-tipogrupo="{{$grupo->tipo}}" data-nombre="{{$grupo->nombre}}"><i class="fa fa-pencil fa-fw"></i></a><!-- editar grupo-->
        <a class="linkdelgrupo" href="#" title="Eliminar grupo" data-idgrupo="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" data-numeroelementos="{{$grupo->recursos->count()}}"><i class="fa fa-trash-o fa-fw"></i></a><!-- eliminar grupo-->
                  
        <small style="margin:10px"> | </small>

        <!-- Nuevo recurso -->
        <a class="addnuevorecursotogrupo text-info" href="#" title="Añadir nuevo {{Config::get('string.'.$grupo->tipo)}}" data-idgrupo="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" data-tipo="{{$grupo->tipo}}" data-texttipo="{{Config::get('string.'.$grupo->tipo)}}" data-tipoelemento="grupo"><i class="fa fa-plus fa-fw"></i></a>
        <!-- añadir recurso existente al grupo -->
        <a class="addrecursoexistentetogrupo" href="#" title="Añadir {{Config::get('string.'.$grupo->tipo)}} existente al grupo" data-nombre="{{$grupo->nombre}}" data-idgrupo="{{$grupo->id}}" data-tipogrupo="{{$grupo->tipo}}"><i class="fa fa-chain fa-fw"></i></a>
                 
        <small style="margin:10px"> | </small>

        <!-- presonas -->
        <a class="addRelacionUsuarioGrupo"  href="#" data-id="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}"  title="Añadir administradores, gestores, y/o validadores" ><i class="fa fa-user-plus fa-fw"></i></a>
        <a class="delRelacionUsuarioGrupo"  href="#" data-id="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" ><i class="fa fa-user-times fa-fw"></i></a>
        <a class="verUsuarioConRelacionGrupo"  href="#" data-id="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" title="Ver  Administradores, gestores y/o validadores" ><i class="fa fa-eye fa-fw"></i></a>
      </span>
    @endif
        
    <a href="#" class="badge listarRecursos" data-ulrecursosdelgrupo="#ulrecursosdelgrupo_{{$grupo->id}}" data-grupoid="{{$grupo->id}}"><i class="i_{{$grupo->id}} fa fa-angle-double-down fa-fw"></i>{{$grupo->recursos->count()}}</a>
     
    <!-- recurso en el grupo -->
    <ul style="margin-left:1.5em;display:none" id="ulrecursosdelgrupo_{{$grupo->id}}" class="list-group">
      @foreach($grupo->recursos as $recurso)
        <li class="list-group-item listitemrecurso">
          <a  href= "#spanopcionesrecurso_{{$recurso->id}}" class="@if($recurso->disabled) text-warning @else text-success @endif toggleOpcionesRecurso @if($recurso->items->count() > 0) listarItems @endif" @if($recurso->items->count() > 0) title="Ver {{Config::get('string.items'.$recurso->tipo)}}" data-ulitemsid="#ulitems_{{$recurso->id}}" data-recursoid="{{$recurso->id}}" @endif> {{$recurso->nombre}} @if($recurso->items->count() > 0) <i class="i_{{$recurso->id}} fa fa-angle-double-down fa-fw"></i> @endif</a>
          <!-- opciones de administración de recursos tipo contenedor -->
          @if ( Auth::user()->isAdmin() || $recurso->administradores->contains(Auth::user()->id) ) 
            <span style="display:none" id="spanopcionesrecurso_{{$recurso->id}}" class="opcionesRecurso">
              <!-- editar -->
              <a href="#" class="linkEditRecurso text-info" title="Editar {{Config::get('string.'.$recurso->tipo)}}" data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" data-numeroelementos = "{{ $recurso->items->count()}}"><i class="fa fa-pencil fa-fw"></i></a>
              <!-- eliminar -->
              <a href="#" title="Eliminar {{Config::get('string.'.$recurso->tipo)}}" class="linkEliminaRecurso text-info" data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" data-numeroeventos="{{$recurso->eventosfuturos()->count()}}" data-numeroelementos = "{{ $recurso->items->count()}}"><i class="fa fa-trash-o fa-fw"></i></a>
              <!-- enabled/disabled -->
              @if($recurso->disabled)
                <!-- habilitar -->
                <a href="#" title="Habilitar {{Config::get('string.'.$recurso->tipo)}}" class="enabled text-success" data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}"><i class="fa fa-toggle-off fa-fw"></i></a>
              @else    
                <!-- deshabilitar -->
                <a href="#" title = "Deshabilitar {{Config::get('string.'.$recurso->tipo)}}" class="disabled text-warning" data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" ><i class="fa fa-toggle-on fa-fw "></i></a>
              @endif 
                            
              <small style="margin:10px"> | </small>

              <!-- Nuevo Item -->
              <a class="addnuevoitemtorecurso text-info" href="#" title="Añadir nuevo {{Config::get('string.items'.$recurso->tipo)}}" data-contenedorid="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" data-tipo="{{$recurso->tipo}}"><i class="fa fa-plus fa-fw"></i></a>
                            
              <!-- añadir Item existente -->
              <a href="#" title="Añadir {{Config::get('string.items'.$recurso->tipo)}} existente/s" class=" addItemSinContenedor" data-contenedorid="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}" data-tipo="{{Config::get('options.tipoItemsContenidosEn_'.$recurso->tipo)}}"><i class="fa fa-chain fa-fw"></i></a>

              <small style="margin:10px"> | </small>

              <!-- presonas -->
              <a class="addUserWithRol" href="#" title="Añadir administradores, gestores, y/o validadores"  data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}"><i class="fa fa-user-plus fa-fw"></i></a>
              <a class="removeUserWithRol" href="#" title="Eliminar Administradores, gestores y/o validadores"  data-id="{{$recurso->id}}" data-nombre="{{$recurso->nombre}}"><i class="fa fa-user-times fa-fw"></i></a>
              <a class="verUsuarioConRelacionRecurso"  href="#" data-id="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" title="Ver  Administradores, gestores y/o validadores" ><i class="fa fa-eye fa-fw"></i></a>
            </span>
          @endif
                      
                      <!--items -->
                      @if($recurso->items->count() > 0)
                        <a href="#" class="badge listarItems" data-ulitemsid="#ulitems_{{$recurso->id}}" data-recursoid="{{$recurso->id}}"><i class="i_{{$recurso->id}} fa fa-angle-double-down fa-fw"></i>{{$recurso->items()->count()}}</a>
                      @endif

                      <!-- items -->  
                      @if($recurso->items->count() > 0)
                        <ul style="margin-left:1.5em;display:none" id="ulitems_{{$recurso->id}}" class="list-group">
                          @foreach ($recurso->items as $item)
                            <li class="list-group-item listitem">
                              <a
                                href= "#spanopcionesitem_{{$item->id}}" 
                                class=" @if($item->disabled) text-warning @else text-success @endif
                                        toggleOpcionesItem">
                                {{$item->nombre}}
                              </a>

                               @if ( Auth::user()->isAdmin() || $item->administradores->contains(Auth::user()->id) )
                                
                                <span style="display:none" id="spanopcionesitem_{{$item->id}}" class="opcionesItem">
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
                                </span>
                               @endif   
                            </li>
                          @endforeach
                       </ul>
                      @endif
                     
                      
                     
                    </li>
                @endforeach
              </ul>
       </li>
   
  
@endforeach
</ul>

