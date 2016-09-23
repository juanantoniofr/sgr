<ul class="list-group">   
@foreach($grupos as $grupo)
  
    
      <li  class="list-group-item col-md-12 listitemgrupo" >
        <a href= "#spanopcionesgrupo_{{$grupo->id}}" class="text-success toggleOpcionesGrupo listarRecursos" data-divrecursosid="#divrecursos_{{$grupo->id}}" data-grupoid="{{$grupo->id}}"><i  class=" i_{{$grupo->id}} fa fa-angle-double-down fa-fw"></i>{{$grupo->nombre}}</a>
        @if ( Auth::user()->isAdmin() || $grupo->administradores->contains(Auth::user()->id) ) 
          <span style="display:none" id="spanopcionesgrupo_{{$grupo->id}}" class="opcionesGrupo">
                  <a href="#" title="Editar grupo" class="linkEditGrupo" data-idgrupo="{{$grupo->id}}" data-descripcion="{{$grupo->descripcion}}" data-tipogrupo="{{$grupo->tipo}}" data-nombre="{{$grupo->nombre}}"><i class="fa fa-pencil fa-fw"></i></a><!-- editar grupo-->
                  <a href="#" title="Eliminar grupo" class = "linkdelgrupo" data-idgrupo="{{$grupo->id}}" data-nombre="{{$grupo->nombre}}" data-numeroelementos="{{$grupo->recursos->count()}}"><i class="fa fa-trash-o fa-fw"></i></a><!-- eliminar grupo-->
                  <a href="#" title="Añadir {{$grupo->tipo}} existente al grupo" class="addrecursotogrupo" data-nombre="{{$grupo->nombre}}" data-idgrupo="{{$grupo->id}}" data-tipogrupo="{{$grupo->tipo}}"><i class="fa fa-chain fa-fw"></i></a><!-- añadir recursos al grupo -->
          </span>
        @endif
        <a href="#" class="badge listarRecursos" data-divrecursosid="#divrecursos_{{$grupo->id}}" data-grupoid="{{$grupo->id}}"><i class="i_{{$grupo->id}} fa fa-angle-double-down fa-fw"></i>{{$grupo->recursos->count()}}</a>
     
    

      
              <ul style="margin-left:1.5em;display:none" id="divrecursos_{{$grupo->id}}" class="list-group"><!-- recurso en el grupo -->
                @foreach($grupo->recursos as $recurso)
                    <li class="list-group-item listitemrecurso">

                      <a  href= "#spanopcionesrecurso_{{$recurso->id}}"
                          class="@if($recurso->disabled) text-warning @else text-success @endif toggleOpcionesRecurso @if($recurso->items->count() > 0) listarItems @endif"
                          @if($recurso->items->count() > 0)
                            title="Ver {{Config::get('string.items'.$recurso->tipo)}}"
                            data-ulitemsid="#ulitems_{{$recurso->id}}"
                            data-recursoid="{{$recurso->id}}"
                          @endif           
                         >
                         {{$recurso->nombre}}
                         @if($recurso->items->count() > 0) <i class="i_{{$recurso->id}} fa fa-angle-double-down fa-fw"></i> @endif
                         
                      </a>
                                           

                      @if ( Auth::user()->isAdmin() || $recurso->administradores->contains(Auth::user()->id) ) 
                        <span style="display:none" id="spanopcionesrecurso_{{$recurso->id}}" class="opcionesRecurso">

                          <!-- Nuevo Item -->
                          <a href="#" title="Nuevo {{Config::get('string.items'.$recurso->tipo)}}" class = "linkAddItem text-info" data-contenedorid="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" >
                            <i class="fa fa-plus fa-fw"></i>
                          </a>
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
                          
                          <small style="margin:10px"> | </small>

                          <!-- añadir Item existente -->
                          <a href="#" title="Añadir {{Config::get('string.items'.$recurso->tipo)}} existente/s" class = "linkaddItemExistente" data-nombre="{{$recurso->nombre}}" data-contenedorid="{{$recurso->id}}" >
                            <i class="fa fa-chain fa-fw"></i>
                          </a>

                          <small style="margin:10px"> | </small>

                          <a class="addUserWithRol"  href="" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}"  title="Añadir administradores, gestores, y/o validadores" >
                            <i class="fa fa-user-plus fa-fw"></i>
                          </a>
                          <a class="removeUserWithRol"  href="" data-idrecuso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title="Eliminar Administradores, gestores y/o validadores" >
                            <i class="fa fa-user-times fa-fw"></i>
                          </a>
                        </span>
                      @endif
                      
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

