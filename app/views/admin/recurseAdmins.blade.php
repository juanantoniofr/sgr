@extends('layout')

@section('title')
    SGR: Administradores
@stop



@section('content')
<div class="container">
<div class="row">
    {{$menuAdministradores or ''}}
</div>


<div class="row">
    
    <div class="panel panel-info">
            
        <div class="panel-heading"><h3><i class="fa fa-list fa-fw"></i> Administradores de: <b>{{$recurso->nombre}}</b></h3></div>

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
                        
                    <th  style="width: 10%;">
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
                       
                        
                        <th style="width: 10%;">
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
                            <th style="width: 10%;">
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
                             <th style="width: 30%;">
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
                    </thead>
                    <tbody>
                         @foreach($administradores as $user)
                                <tr>
                                  @if($user->estado)
                                    <td class="text-success">
                                      <i class="fa fa-check fa-fw" title='Cuenta Activa'></i><span class="text-success">{{$user->username}}</span>
                                  @else
                                    <td class="text-danger">
                                      <i class="fa fa-minus-circle fa-fw" title='Cuenta Desactivada'></i><span class="text-danger">{{$user->username}}</span>
                                  @endif
                                            
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
                                </tr>
                                 @endforeach
                    </tbody>
                    </table>

                {{$administradores->appends(Input::except('page','result'))->links();}}
                
            </div><!-- /.panel-body -->

        </div><!-- /.panel-default -->
    
</div><!-- /.row -->    

</div><!-- /.container -->
                     
@stop
@section('js')
  {{HTML::script('assets/js/admin.js')}}
@stop