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
                    <li>{{$recurso->nombre}} 
                        @if ( $recurso->esSupervisadoPor(Auth::user()->id) ) 
                            <!-- editar -->
                            <a href="#" title="Editar recurso" class="linkEditRecurso" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a>    
                        @endif
                    </li>
                @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>