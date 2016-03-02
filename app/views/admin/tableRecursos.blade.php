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
                    <th style="width:68%;text-align:center">Recursos
                    </th>
                </thead>
                <tbody>
                    @foreach($grupos as $grupo)
                        <tr id="tr_{{$grupo->id}}">
                            <td ><ul class="list-unstyled"><li>{{$grupo->id}}</li></ul></td>
                            <td>
                                <ul class="list-unstyled"><li>
                                <!-- editar -->
                                <a href="" title="Editar recurso" class="linkEditGrupo" data-idgrupo="{{$grupo->id}}"><i class="fa fa-pencil fa-fw"></i></a>
                                
                                <!-- eliminar -->
                                <a href="" class = "eliminarRecurso" data-idgrupo="{{$grupo->id}}" data-nombregrupo="{{$grupo->nombre}}" title = "Eliminar recurso"><i class="fa fa-trash-o fa-fw"></i></a>
                                
                                {{$grupo->nombre}}
                                </li></ul>
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                @foreach($grupo->recursos as $recurso)
                                    <li>{{$recurso->nombre}} @if ($recurso->supervisores->contains(Auth::user()->id)) (Supervisa) @endif</li>
                                @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>