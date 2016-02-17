@extends('layout')

@section('title')
    SGR: Gestión de Usuarios
@stop



@section('content')
<div class="container">

<div class="row">
    {{$menuUsuarios or ''}}
</div>


<div class="row">
    
    <div class="panel panel-info">
        <div id = "espera" style="display:none"></div>            
        
        <div class="panel-heading"><h2><i class="fa fa-list fa-fw"></i> Listado</h2></div>

        <div class="panel-body">
                        
            <div id = "msg" class = "alert text-center" role="alert" style="display:none">
                
            </div>    
            
            <table class="table table-hover table-striped">
                
                <thead>
                    <th>#</th>  
                    <th  style="width: 15%;">
                        @if ($sortby == 'username' && $order == 'asc') {{
                                link_to_action(
                                    'UsersController@listUsers',
                                    'Username',
                                    array(
                                        'sortby' => 'username',
                                        'order' => 'desc',
                                        'veractivados' => $veractivados,
                                        'verdesactivados' => $verdesactivados,
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'UsersController@listUsers',
                                        'Username',
                                        array(
                                            'sortby' => 'username',
                                            'order' => 'asc',
                                            'veractivados' => $veractivados,
                                            'verdesactivados' => $verdesactivados,
                                            
                                        )
                                    )
                                }}
                            @endif
                            <i class="fa fa-sort fa-fw text-info"></i>

                        </th>
                       
                        
                        <th style="width: 9%;">
                            @if ($sortby == 'colectivo' && $order == 'asc') {{
                                link_to_action(
                                    'UsersController@listUsers',
                                    'Colectivo',
                                    array(
                                        'sortby' => 'colectivo',
                                        'order' => 'desc',
                                        'veractivados' => $veractivados,
                                        'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'UsersController@listUsers',
                                        'Colectivo',
                                        array(
                                            'sortby' => 'colectivo',
                                            'order' => 'asc',
                                            'veractivados' => $veractivados,
                                            'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                                }}
                            @endif
                            <i class="fa fa-sort fa-fw text-info"></i>
                            </th>
                            <th style="width: 18%;">
                            @if ($sortby == 'rol' && $order == 'asc') {{
                                link_to_action(
                                    'UsersController@listUsers',
                                    'Perfil',
                                    array(
                                        'sortby' => 'capacidad',
                                        'order' => 'desc',
                                        'veractivados' => $veractivados,
                                        'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'UsersController@listUsers',
                                        'Perfil',
                                        array(
                                            'sortby' => 'capacidad',
                                            'order' => 'asc',
                                            'veractivados' => $veractivados,
                                            'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                                }}
                            @endif
                            <i class="fa fa-sort fa-fw text-info"></i>
                            </th>
                             <th style="width: 25%;">
                         @if ($sortby == 'apellidos' && $order == 'asc') {{
                                link_to_action(
                                    'UsersController@listUsers',
                                    'Apellidos, nombre',
                                    array(
                                        'sortby' => 'apellidos',
                                        'order' => 'desc',
                                        'veractivados' => $veractivados,
                                        'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'UsersController@listUsers',
                                        'Apellidos, nombre',
                                        array(
                                            'sortby' => 'apellidos',
                                            'order' => 'asc',
                                            'veractivados' => $veractivados,
                                            'verdesactivados' => $verdesactivados,
                                        
                                        )
                                    )
                                }}
                            @endif
                            <i class="fa fa-sort fa-fw text-info"></i>
                     

                        </th>
                        <th style="width: 20%;">Observaciones</th>
                        <th >Última modificación</th>
                </thead>
                
                <tbody>
                    @foreach($usuarios as $user)
                        <tr id = "{{$user->id}}">
                            <td id ="{{$user->id}}_estado">
                                <i id ="{{$user->id}}_activa" class="fa fa-check fa-fw text-success"  title='Cuenta Activa' @if($user->estado == 0 || $user->caducado())style="display:none" @endif></i>
                                <i id ="{{$user->id}}_caducada" class="fa fa-clock-o fa-fw text-danger" title='Cuenta Caducada' @if (!$user->caducado()) style="display:none" @endif></i> 
                                <i id ="{{$user->id}}_desactiva" class="fa fa-minus-circle fa-fw text-danger " title='Cuenta Desactivada' @if($user->estado == '1') style="display:none" @endif></i>
                            </td>
                            <td>
                                <a href="" class="eliminarUsuario" data-infousuario="{{$user->nombre}} {{$user->apellidos}} - {{$user->username}} -" data-id="{{$user->id}}"><i class="fa fa-trash fa-fw" title='borrar'></i></a>
                                <a href="" data-id="{{$user->id}}" class="editUser"><i class="fa fa-pencil fa-fw" title='editar'></i></a>
                                   <span id ="username">{{$user->username}}</span>
                            </td>
                            <td id ="{{$user->id}}_colectivo"> 
                                {{$user->colectivo}}
                            </td>
                            <td id ="{{$user->id}}_rol">
                                {{$user->getRol()}}
                            </td>
                            <td id ="{{$user->id}}_apellidosnombre">
                                {{$user->apellidos}},{{$user->nombre}}
                            </td>
                            <td id ="{{$user->id}}_observaciones"> {{$user->observaciones}}</td>
                            <td ><small id ="{{$user->id}}_updated_at">{{$user->updated_at}}</small></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{$usuarios->appends(Input::except('page','result'))->links();}}
                
        </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
</div><!-- /.row -->    
</div><!-- /.container -->


<!-- modal eliminar recurso -->
<div class="modal fade" id="modalEliminaUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminaUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar recurso</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el usuario: "<b><span id="infoUsuario"></span>"</b> ?</div>
                <div class="alert alert-warning"> El usuario se eliminará de forma permanente.</div>
       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="btnEliminar"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a>

                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalborrarRecurso -->

{{$modalAddUser or ''}}
{{$modalEditUser or ''}}



@stop
@section('js')
<script src="../assets/js/user.js"></script>
@stop