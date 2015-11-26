@extends('layout')

@section('title')
    SGR: Administradores
@stop



@section('content')
<div class="container">
    
    <div class="row">
        {{$menu or ''}}
    </div>


    <div class="row">
        <!-- listado supervisores -->
        <div class="panel panel-info">
            
        <div class="panel-heading"><h3><i class="fa fa-list fa-fw"></i> Supervisores de: <b>{{$recurso->nombre}}</b></h3></div>

        <div class="panel-body">
                        
                
            <form class="navbar-form navbar-left" role="search">    
                    <div class="form-group ">
                        <label>Registros por página</label>
                        <select class="form-control ">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> 
                    </div>                            
                
                </form>
                                    
                      
                
            <table class="table table-hover table-striped">
                <thead>
                    <th style="width: 1%;"></th>    
                    <th  style="width: 15%;">
                        @if ($sortby == 'username' && $order == 'asc') {{
                                link_to_action(
                                    'UsersController@listUsers',
                                    'Username',
                                    array(
                                        'sortby' => 'username',
                                        'order' => 'desc'
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
                                        'order' => 'desc'
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
                                        'order' => 'desc'
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
                                        'order' => 'desc'
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
                         @foreach($supervisores as $user)
                                <tr>
                                  <td class="@if($user->estado) text-success @else text-danger @endif">
                                      <i class="fa @if($user->estado) fa-check @else fa-minus-circle @endif fa-fw" title='estado'></i>
                                </td>
                                <td>
                                    <a href="" title='Quitar como supervisor' class="bajasupervisor" data-username="{{$user->username}}" data-infousuario="{{$user->nombre}} {{$user->apellidos}} - {{$user->username}} -" data-iduser="{{$user->id}}" data-idrecurso="{{$recurso->id}}" data-nombrerecurso="{{$recurso->nombre}}"><i class="fa fa-toggle-off fa-fw "></i></i></a>
                                      {{$user->username}}
                                    </td>
                                    
                                    <td>
                                        {{$user->colectivo}}
                                    </td>
                                    <td>
                                        {{$user->getRol()}}
                                        
                                    </td>
                                    <td>
                                        {{$user->apellidos .', '.$user->nombre}}
                                    </td>
                                    <td> {{$user->observaciones}}</td>
                                    <td><small>{{date('d M Y, H:m',strtotime($user->updated_at))}}</small></td>
                                </tr>
                                 @endforeach
                    </tbody>
                    </table>

                {{$supervisores->appends(Input::except('page','result'))->links();}}
                
            </div><!-- /.panel-body -->

        </div><!-- /.panel-default -->
    </div><!-- /.row -->    

</div><!-- /.container -->

{{$modalAddSupervisor or ''}}
{{$modalConfirmaBajaSupervisor or ''}}

@stop
@section('js')
  {{HTML::script('assets/js/admin.js')}}
@stop