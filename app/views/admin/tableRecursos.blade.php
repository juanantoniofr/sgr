<table class="table table-hover table-striped">
    
    <thead>
        <th style="width:2%;text-align:center" >Id.</th>
        <!-- Order column by nombre de grupo--> 
        <th style="width:30%;text-align:center" >
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
        <th style="width:68%;text-align:center">Recursos</th>
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
                        <!-- añadir recursos al grupo -->
                        <a href="#" title="Añadir recursos al grupo" class = "addrecursotogrupo" data-nombre="{{$grupo->nombre}}" data-idgrupo="{{$grupo->id}}" ><i class="fa fa-plus fa-fw"></i></a>
                        {{$grupo->nombre}}
                    </li>
                </ul>
            </td>
            <td>
                <ul class="list-unstyled">
                @foreach($grupo->recursos as $recurso)
                    <li><span class="@if($recurso->disabled) text-warning @else text-success @endif">{{$recurso->nombre}}</span> 
                        @if ( $recurso->esSupervisadoPor(Auth::user()->id) ) 
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
                        @endif
                    </li>
                @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>