@extends('layout')

@section('title')
    SGR: Gestión de Espacios y Equipos
@stop

@section('css')
    
    
@stop

@section('content')
<div class="container">
<div class="row">
    {{$menuRecursos or ''}}
</div>


<div class="row">
    
    <div class="panel panel-info">
            
        <div class="panel-heading">
            <h2><i class="fa fa-list fa-fw"></i> {{$recursosListados or ''}}</h2>
        </div>

        <div class="panel-body">
                        
            <div class="row">
    
            <form class="navbar-form navbar-right">    
                <div class="form-group ">
                    <select class="form-control" id="selectRecurso" name="grupoid" >
                        <option value ="">Seleccione grupo.....</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{$grupo->grupo_id}}" placeholder="Seleccione recurso...">{{$grupo->grupo}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary form-control" role="submit"><i class="fa fa-filter fa-fw"></i> Filtrar</button> 
                </div>
            </form>
            <form class="navbar-form navbar-right" >    
                           
            </form>

            </div>

            @if (Session::has('message'))
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ Session::get('message') }}
                </div>
            @endif
            
            <table class="table table-hover table-striped">
                <thead>
                    <th style="width:5%" >Id. de Lugar</th>
                    <!-- Order column by nombre de equipo--> 
                    <th style="" >
                        @if ($sortby == 'nombre' && $order == 'asc') {{
                            link_to_action(
                                'recursosController@listar',
                                'Nombre',
                                array(
                                    'sortby' => 'nombre',
                                    'order' => 'desc',
                                )
                            )
                        }}
                        @else {{
                            link_to_action(
                               'recursosController@listar',
                                'Nombre',
                                array(
                                    'sortby' => 'nombre',
                                    'order' => 'asc',
                                    )
                                )
                            }}
                        @endif
                        <i class="fa fa-sort fa-fw text-info"></i>
                    </th>
                    <!-- Order column by grupo --> 
                    <th style="">
                        @if ($sortby == 'grupo' && $order == 'asc') {{
                            link_to_action(
                                'recursosController@listar',
                                'Grupo',
                                array(
                                    'sortby' => 'grupo',
                                    'order' => 'desc'
                                    )
                                )
                            }}
                        @else {{
                            link_to_action(
                                'recursosController@listar',
                                'Grupo',
                                    array(
                                        'sortby' => 'grupo',
                                        'order' => 'asc',
                                    )
                                )
                            }}
                        @endif
                        <i class="fa fa-sort fa-fw text-info"></i>
                    </th>
                     <!-- Order column by tipo --> 
                    <th style="width:5%">
                        @if ($sortby == 'tipo' && $order == 'asc') {{
                            link_to_action(
                                'recursosController@listar',
                                'Tipo',
                                array(
                                    'sortby' => 'tipo',
                                    'order' => 'desc'
                                    )
                                )
                            }}
                        @else {{
                            link_to_action(
                                'recursosController@listar',
                                'Tipo',
                                    array(
                                        'sortby' => 'tipo',
                                        'order' => 'asc',
                                    )
                                )
                            }}
                        @endif
                        <i class="fa fa-sort fa-fw text-info"></i>
                    </th>
                    <th style="width:20%">Disponible para...</th>
                    <th>Tipo de gestión de reservas</th>
                    @if (Auth::user()->capacidad == 4)<th>Gestores</th>@endif
                </thead>
                <tbody>
                    @foreach($recursos as $recurso)
                        <tr >
                            <td>
                                @if($recurso->disabled)  
                                    <i class="fa fa-ban fa-fw text-danger" title="Deshabilitado"></i>
                                @else
                                    <i class="fa fa-check fa-fw text-success" title= "Habilitado"></i>    
                                @endif
                                {{$recurso->id_lugar}}</td>
                            <td>
                                
                                
                                @if (Auth::user()->capacidad == 4)
                                    <a href="{{route('admins',['idRecurso' => $recurso->id])}}" title="Administradores"><i class="fa fa-users fa-fw"></i></a>
                                @endif
                                <!-- editar -->
                                <a href="{{route('editarecurso.html',array('id' => $recurso->id))}}" title="Editar recurso" class="linkEditrecurso" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a>
                                
                                <!-- eliminar -->
                                <a href="" class = "eliminarRecurso" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Eliminar recurso"><i class="fa fa-trash-o fa-fw"></i></a>
                                
                                @if(!$recurso->disabled)
                                    <!-- deshabilitar -->
                                    <a href="" class = "deshabilitarRecurso" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Deshabilitar recurso"><i class="fa fa-toggle-off fa-fw "></i></a>
                                @else    
                                    <!-- habilitar -->
                                    <a href="" class = "habilitarRecurso" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Habilitar recurso"><i class="fa fa-toggle-on fa-fw"></i></a>
                                @endif
                                {{$recurso->nombre}}
                                
                            </td>
                            <td>
                                {{$recurso->grupo}}
                                <!-- editar grupo --><a href="" title="Editar grupo" class="linkEditgrupo" data-descripciongrupo="{{$recurso->descripcionGrupo}}" data-nombregrupo="{{$recurso->grupo}}" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a></td>
                            <td>{{$recurso->tipo}}</td>
                            <td>
                                <ul class="list-unstyled">
                                @foreach($recurso->perfiles() as $perfil)
                                    <li>{{$perfil}}</li>
                                @endforeach
                                </ul>
                            </td>
                            <td>{{$recurso->tipoGestionReservas()}}</td>
                             @if (Auth::user()->capacidad == 4)
                            <td>
                                <ul class="list-unstyled">
                                @foreach($recurso->administradores as $administrador)
                                    <li>{{$administrador->nombre}} {{$administrador->apellidos}} ({{$administrador->username}})</li>
                                @endforeach
                                </ul>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

                {{$recursos->appends(Input::except('page','result'))->links();}}
                
            </div><!-- /.panel-body -->

        </div>
        <!-- /.panel-default -->
    
</div>
<!-- /.row -->    
</div> <!-- .container-fluid -->    

<!-- modal eliminar recurso -->
<div class="modal fade" id="modalborrarRecurso" tabindex="-1" role="dialog" aria-labelledby="borrarRecurso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar recurso</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el recurso: "<b><span id="nombrerecurso"></span>"</b> ?</div>
                <div class="alert alert-warning"> El recurso se eliminará de forma permanente y se borrarán todos las reservas pendientes de realización.... </div>
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnEliminar"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a>

                <!--<button type="button" class="btn btn-primary" value= "" id="btnEliminar" data-idrecurso="" >Eliminar</button>-->
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalborrarRecurso -->


<!-- modal deshabilitar recurso -->
<div class="modal fade" id="modaldisabledRecurso" tabindex="-2" role="dialog" aria-labelledby="disabledRecurso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Deshabilitar recurso</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>deshabilitar</b> el recurso: "<b><span id="nombrerecursoDeshabilitar"></span>"</b> ?</div>
                <div class="alert alert-warning"> Al deshabilitar el recurso:
                    <ul>
                        <li> No se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <lI> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso. </lI>
                    </ul>
                </div>
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnDeshabilitar"><i class="fa fa-toggle-off fa-fw"></i> Deshabilitar</a>

                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modaldisabledRecurso -->                     

<!-- modal habilitar recurso -->
<div class="modal fade" id="modalenabledRecurso" tabindex="-3" role="dialog" aria-labelledby="enabledRecurso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Habilitar recurso</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>Habilitar</b> el recurso: "<b><span id="nombrerecursoHabilitar"></span>"</b> ?</div>
                <div class="alert alert-warning"> Al deshabilitar el recurso:
                    <ul>
                        <li> Se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <li> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso. </li>
                    </ul>
                </div>
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnHabilitar"><i class="fa fa-toggle-on fa-fw"></i> Habilitar</a>

                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modaldisabledRecurso -->   

<!-- modal nuevo recurso -->
{{ $modalAdd or '' }}
{{ $modalEdit or '' }}
{{ $modalEditGrupo or ''}}
<!-- ./ nuevo recurso -->
@stop

@section('js')
    {{HTML::script('assets/ckeditor/ckeditor.js')}}
    <script type="text/javascript">CKEDITOR.replace( 'descripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'editdescripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'updatedescripciongrupo' );</script>
    
    {{HTML::script('assets/js/admin.js')}}
  
@stop