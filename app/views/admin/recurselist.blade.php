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
    <div id = "espera" style="display:none"></div>
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

            
            <div class="alert alert-success alert-dismissable text-center" id = "success_recurselist_msg" style="display:none"><p></p> 
            </div>
            
            
            <table class="table table-hover table-striped">
                <thead>
                    <th style="width:5%" >Id. de Lugar</th>
                    <!-- Order column by nombre de equipo--> 
                    <th style="width:20%" >
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
                    <th style="width:15%">
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
                    
                    <th style="width:20%">Disponibilidad</th>
                    <th>Validación</th>
                    <th style="width:20%">Usuarios</th>
                </thead>
                <tbody>
                    @foreach($recursos as $recurso)
                        <tr id="tr_{{$recurso->id}}">
                            <td >
                                @if($recurso->disabled)  
                                    <i class="fa fa-ban fa-fw text-danger" title="Deshabilitado"></i>
                                @else
                                    <i class="fa fa-check fa-fw text-success" title= "Habilitado"></i>    
                                @endif
                                {{$recurso->id_lugar}}
                            </td>
                            <td>
                                <!-- editar -->
                                <a href="{{route('editarecurso.html',array('id' => $recurso->id))}}" title="Editar recurso" class="linkEditrecurso" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a>
                                
                                <!-- eliminar -->
                                <a href="" class = "eliminarRecurso" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" title = "Eliminar recurso"><i class="fa fa-trash-o fa-fw"></i></a>
                                
                                @if($recurso->disabled == false)
                                    <!-- deshabilitar -->
                                    <a id="link_{{$recurso->id}}" href="" class = "enabled" data-idrecurso="{{$recurso->id}}" data-switch="Off" data-nombrerecurso="{{$recurso->nombre}}" title = "Deshabilitar recurso"><i class="fa fa-toggle-off fa-fw "></i></a>
                                @else    
                                    <!-- habilitar -->
                                    <a id="link_{{$recurso->id}}" href="" class = "enabled" data-idrecurso="{{$recurso->id}}" data-switch="On" data-nombrerecurso="{{$recurso->nombre}}" title = "Habilitar recurso"><i class="fa fa-toggle-on fa-fw"></i></a>
                                @endif
                                {{$recurso->nombre}}
                            </td>
                            <td>
                                <a href="" title="Editar grupo" class="linkEditgrupo" data-descripciongrupo="{{$recurso->descripcionGrupo}}" data-nombregrupo="{{$recurso->grupo}}" data-idrecurso="{{$recurso->id}}"><i class="fa fa-pencil fa-fw"></i></a>
                                {{$recurso->grupo}}
                            </td>
                            <!-- <td>{{$recurso->tipo}}</td> -->
                            <td>
                                
                                @foreach($recurso->perfiles() as $perfil)
                                    {{$perfil}}<br />
                                @endforeach
                                
                            </td>
                            <td>{{$recurso->tipoGestionReservas()}}</td>
                             @if (Auth::user()->capacidad == 4)
                            <td><a class="addsupervisor" href="" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}" data-nombregrupo="{{$recurso->grupo}}" title="establecer usuarios con roles uspervisor, tecnico y validador" class="pull-right"><i class="fa fa-user-plus fa-fw"></i></a><hr />
                                <span><b>Supervisores:</b></span><br />
                                @foreach($recurso->supervisores as $supervisor)
                                    <a href="" title='eliminar supervisor' class="bajasupervisor" data-username="{{$supervisor->username}}" data-iduser="{{$supervisor->id}}" data-idrecurso="{{$recurso->id}}" ><i class="fa fa-user-times fa-fw "></i></i></a>
                                   {{$supervisor->nombre}} {{$supervisor->apellidos}}.<br /> 
                                @endforeach
                                <span><b>Validadores:</b></span><br />
                                @foreach($recurso->validadores as $validador)
                                    <a href="" title='eliminar validador' class="bajasupervisor" data-username="{{$validador->username}}" data-iduser="{{$validador->id}}" data-idrecurso="{{$recurso->id}}" ><i class="fa fa-user-times fa-fw "></i></i></a>
                                   {{$validador->nombre}} {{$validador->apellidos}}.<br /> 
                                @endforeach
                                <span><b>Técnicos:</b></span><br />
                                @foreach($recurso->tecnicos as $tecnico)
                                    <a href="" title='eliminar tecnico' class="bajasupervisor" data-username="{{$tecnico->username}}" data-iduser="{{$tecnico->id}}" data-idrecurso="{{$recurso->id}}" ><i class="fa fa-user-times fa-fw "></i></i></a>
                                   {{$tecnico->nombre}} {{$tecnico->apellidos}}.<br /> 
                                @endforeach
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


                    

<!-- modal habilitar recurso -->
<div class="modal fade" id="modalenabledRecurso" tabindex="-3" role="dialog" aria-labelledby="enabledRecurso" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="habilitarecurso">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Habilitar recurso</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>Habilitar</b> el recurso: "<b><span id="nombrerecurso_switchenabled"></span>"</b> ?</div>
                <div class="alert alert-warning"> Al deshabilitar el recurso:
                    <ul>
                        <li> Se podrán añadir nuevas reservas o solicitudes de uso. </li>
                        <li> Se enviará aviso vía correo a los usuarios que tienen reservado el recurso. </li>
                    </ul>
                </div>
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnHabilitar"><i class="fa fa-toggle-on fa-fw"></i> Habilitar</a>

                <input id="modaldisable_idrecurso" type="hidden" name="idDisableRecurso" value=""   />
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modaldisabledRecurso -->   


{{ $modaldeshabilitarecurso     or '' }}
{{ $modalAdd                    or '' }}
{{ $modalEdit                   or '' }}
{{ $modalEditGrupo              or '' }}
{{ $modalAddSupervisor          or '' }}
{{ $modalConfirmaBajaSupervisor or '' }}


<!-- ./ nuevo recurso -->
@stop

@section('js')
    {{HTML::script('assets/ckeditor/ckeditor.js')}}
    <script type="text/javascript">CKEDITOR.replace( 'motivo' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'descripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'editdescripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'updatedescripciongrupo' );</script>
    
    {{HTML::script('assets/js/admin.js')}}
  
@stop