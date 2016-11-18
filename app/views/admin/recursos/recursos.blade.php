<ul class="list-group">
@foreach($sgrGrupos as $sgrGrupo)
 
  <li  class="list-group-item col-md-12 listitemgrupo">
    <a class="text-success toggleOpcionesGrupo listarRecursos" href= "#spanopcionesgrupo_{{$sgrGrupo->id()}}" data-ulrecursosdelgrupo="#ulrecursosdelgrupo_{{$sgrGrupo->id()}}" data-grupoid="{{$sgrGrupo->id()}}" id="{{$sgrGrupo->id()}}"><i  class=" i_{{$sgrGrupo->id()}} fa fa-angle-double-down fa-fw"></i>{{$sgrGrupo->nombre()}}</a>
    <!-- Opciones de administración del grupo -->
    @if ( Auth::user()->isAdmin() || $sgrGrupo->esAdministrador(Auth::user()->id) ) 
      <span style="display:none" id="spanopcionesgrupo_{{$sgrGrupo->id()}}" class="opcionesGrupo">
        <!-- editar grupo-->
        <a class="linkEditGrupo" href="#" title="Editar grupo" data-idgrupo="{{$sgrGrupo->id()}}" data-descripcion="{{$sgrGrupo->descripcion()}}" data-tipogrupo="{{$sgrGrupo->tipo()}}" data-nombre="{{$sgrGrupo->nombre()}}" data-texttipo="{{Config::get('string.'.$sgrGrupo->tipo())}}"><i class="fa fa-pencil fa-fw"></i></a>
        <!-- eliminar grupo-->
        <a class="linkdelgrupo" href="#" title="Eliminar grupo" data-idgrupo="{{$sgrGrupo->id()}}" data-nombre="{{$sgrGrupo->nombre()}}" data-numeroelementos="{{count($sgrGrupo->recursos())}}"><i class="fa fa-trash-o fa-fw"></i></a>
                  
        <small style="margin:10px"> | </small>

        <!-- Nuevo recurso -->
        <a class="addnuevorecursotogrupo text-info" href="#" title="Añadir nuevo {{Config::get('string.'.$sgrGrupo->tipo())}}" data-id="{{$sgrGrupo->id()}}" data-nombre="{{$sgrGrupo->nombre()}}" data-tipo="{{$sgrGrupo->tipo()}}" data-texttipo="{{Config::get('string.'.$sgrGrupo->tipo())}}" data-tipoelemento="grupo"><i class="fa fa-plus fa-fw"></i></a>
        <!-- añadir recurso existente al grupo -->
        <a class="addrecursoexistentetogrupo" href="#" title="Añadir {{Config::get('string.'.$sgrGrupo->tipo())}} existente al grupo" data-nombre="{{$sgrGrupo->nombre()}}" data-idgrupo="{{$sgrGrupo->id()}}" data-tipogrupo="{{$sgrGrupo->tipo()}}"><i class="fa fa-chain fa-fw"></i></a>
                 
        <small style="margin:10px"> | </small>

        <!-- presonas -->
        <a class="addRelacion"  href="#" data-id="{{$sgrGrupo->id()}}" data-nombre="{{$sgrGrupo->nombre()}}"  title="Añadir administradores, gestores, y/o validadores" data-tipo = "grupo" data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-user-plus fa-fw"></i></a>
        <a class="delRelacion"  href="#" data-id="{{$sgrGrupo->id()}}" data-nombre="{{$sgrGrupo->nombre()}}"  title="Eliminar administradores, gestores, y/o validadores" data-tipo = "grupo" data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-user-times fa-fw"></i></a>
        <a class="verRelacion"  href="#" data-id="{{$sgrGrupo->id()}}" data-nombre="{{$sgrGrupo->nombre()}}" title="Ver  Administradores, gestores y/o validadores" data-tipo = "grupo" data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-eye fa-fw"></i></a>
      </span>
    @endif
        
    <a href="#" class="badge listarRecursos" data-ulrecursosdelgrupo="#ulrecursosdelgrupo_{{$sgrGrupo->id()}}" data-grupoid="{{$sgrGrupo->id()}}"><i class="i_{{$sgrGrupo->id()}} fa fa-angle-double-down fa-fw"></i>{{count($sgrGrupo->recursos())}}</a>
     
    <!-- Opciones de administración de cada recurso en el grupo -->
    <ul style="margin-left:1.5em;display:none" id="ulrecursosdelgrupo_{{$sgrGrupo->id()}}" class="list-group ul_{{$sgrGrupo->id()}} ">
      @foreach($sgrGrupo->recursos() as $sgrRecurso)
        <li class="list-group-item listitemrecurso">
          <a  href= "#spanopcionesrecurso_{{$sgrRecurso->id()}}" class="@if($sgrRecurso->isDisabled()) text-warning @else text-success @endif toggleOpcionesRecurso @if(count($sgrRecurso->items()) > 0) listarItems @endif" @if(count($sgrRecurso->items()) > 0) title="Ver {{Config::get('string.items'.$sgrRecurso->tipo())}}" data-ulitemsid="#ulitems_{{$sgrRecurso->id()}}" data-recursoid="{{$sgrRecurso->id()}}" @endif> {{$sgrRecurso->nombre()}} @if(count($sgrRecurso->items()) > 0) <i class="i_{{$sgrRecurso->id()}} fa fa-angle-double-down fa-fw"></i> @endif</a>
          <!-- opciones de administración de recursos tipo contenedor -->
          @if ( Auth::user()->isAdmin() || $sgrRecurso->isAdministrador(Auth::user()->id) ) 
            <span style="display:none" id="spanopcionesrecurso_{{$sgrRecurso->id()}}" class="opcionesRecurso">
              <!-- editar -->
              <a href="#" class="linkEditRecurso text-info" title="Editar {{Config::get('string.'.$sgrRecurso->tipo())}}" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-numeroelementos = "{{ count($sgrRecurso->items())}}"><i class="fa fa-pencil fa-fw"></i></a>
              <!-- eliminar -->
              <a href="#" title="Eliminar {{Config::get('string.'.$sgrRecurso->tipo())}}" class="linkEliminaRecurso text-info" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" 

              data-numeroeventos="{{$sgrRecurso->getEventos(strtotime('now'),Config::get('options.estadosEvento'),Config::get('options.maxtimestamp'))->count()}}" 

              data-numeroelementos = "{{ count($sgrRecurso->items())}}" data-idrecursopadre="{{$sgrGrupo->id()}}"><i class="fa fa-trash-o fa-fw"></i></a>
              <!-- enabled/disabled -->
              @if($sgrRecurso->isDisabled())
                <!-- habilitar -->
                <a href="#" title="Habilitar {{Config::get('string.'.$sgrRecurso->tipo())}}" class="enabled text-success" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-toggle-off fa-fw"></i></a>
              @else    
                <!-- deshabilitar -->
                <a href="#" title = "Deshabilitar {{Config::get('string.'.$sgrRecurso->tipo())}}" class="disabled text-warning" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-toggle-on fa-fw "></i></a>
              @endif 
                            
              <small style="margin:10px"> | </small>

              <!-- Nuevo Item -->
              <a class="addnuevoitemtorecurso text-info" href="#" title="Añadir nuevo {{Config::get('string.items'.$sgrRecurso->tipo())}}" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-tipo="{{Config::get('options.tipoItemsContenidosEn_'.$sgrRecurso->tipo())}}" data-texttipo="{{Config::get('string.items'.$sgrRecurso->tipo())}}" data-tipoelemento="recurso"><i class="fa fa-plus fa-fw"></i></a>
                            
              <!-- añadir Item existente -->
              <a href="#" title="Añadir {{Config::get('string.items'.$sgrRecurso->tipo())}} existente/s" class=" addItemSinContenedor" data-contenedorid="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-tipo="{{Config::get('options.tipoItemsContenidosEn_'.$sgrRecurso->tipo())}}"><i class="fa fa-chain fa-fw"></i></a>

              <small style="margin:10px"> | </small>

              <!-- presonas -->
              <a class="addRelacion" href="#" title="Añadir administradores, gestores, y/o validadores"  data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-tipo='recurso' data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-user-plus fa-fw"></i></a>
              <a class="delRelacion" href="#" title="Eliminar Administradores, gestores y/o validadores"  data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" data-tipo='recurso' data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-user-times fa-fw"></i></a>
              <a class="verRelacion"  href="#" data-id="{{$sgrRecurso->id()}}" data-nombre="{{$sgrRecurso->nombre()}}" title="Ver  Administradores, gestores y/o validadores" data-tipo='recurso' data-grupoid="{{$sgrGrupo->id()}}"><i class="fa fa-eye fa-fw"></i></a>
            </span>
          @endif
                      
          <!--items -->
          @if(count($sgrRecurso->items()) > 0)
           
            <a href="#" class="badge listarItems" data-ulitemsid="#ulitems_{{$sgrRecurso->id()}}" data-recursoid="{{$sgrRecurso->id()}}"><i class="i_{{$sgrRecurso->id()}} fa fa-angle-double-down fa-fw"></i>{{count($sgrRecurso->items())}}</a>
          @endif

          <!-- items -->  
          @if(count($sgrRecurso->items()) > 0)
            <ul style="margin-left:1.5em;display:none" id="ulitems_{{$sgrRecurso->id()}}" class="list-group ul_{{$sgrRecurso->id()}}">
              @foreach ($sgrRecurso->items() as $item)
                <li class="list-group-item listitem">
                  <a
                    href= "#spanopcionesitem_{{$item->id()}}" 
                    class=" @if($item->isDisabled()) text-warning @else text-success @endif
                            toggleOpcionesItem">{{$item->nombre()}}
                  </a>
                  <!-- opciones de administración de recursos tipo item -->
                  @if ( Auth::user()->isAdmin() || $item->isAdministrador(Auth::user()->id) )
                                
                    <span style="display:none" id="spanopcionesitem_{{$item->id()}}" class="opcionesItem">
                      <!-- editar -->
                      <a href="#" title="Editar {{Config::get('string.'.$item->tipo())}}" class="linkEditRecurso text-info" data-id="{{$item->id()}}" data-nombrecontenedor="{{$item->contenedor()->nombre}}"><i class="fa fa-pencil fa-fw"></i></a>
                      <!-- eliminar -->
                      <a href="#" title="Eliminar {{Config::get('string.'.$item->tipo())}}" class = "linkEliminaRecurso text-info" data-id="{{$item->id()}}" data-nombre="{{$item->nombre()}}" data-numeroeventos="{{$item->getEventos(strtotime('now'),Config::get('options.estadosEvento'),Config::get('options.maxtimestamp'))->count()}}" data-grupoid="{{$item->contenedor()->id}}"><i class="fa fa-trash-o fa-fw"></i></a>
                      
                      @if($item->isDisabled())
                        <!-- habilitar  -->
                        <a href="#" title = "Habilitar {{Config::get('string.'.$item->tipo())}}" class="enabled text-success" data-id="{{$item->id()}}" data-nombre="{{$item->nombre()}}"  data-grupoid="{{$sgrRecurso->id()}}"><i class="fa fa-toggle-off fa-fw"></i></a>
                      @else    
                        <!-- deshabilitar-->
                        <a href="#" title="Deshabilitar {{Config::get('string.'.$item->tipo())}}" class="disabled text-warning" data-id="{{$item->id()}}" data-nombre="{{$item->nombre()}}"  data-grupoid="{{$sgrRecurso->id()}}"><i class="fa fa-toggle-on fa-fw "></i></a>
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