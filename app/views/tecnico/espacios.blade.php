@extends('layout')

@section('title')
    SGR: espacios y equipos
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"> <i class="fa fa-check-square-o fa-fw"></i> Gestión de Espacios y Medios</h3>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-12 col-md-12 col-xs-12">
           
        <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-list fa-fw"></i> Espacios y medios   
                        </div>
                        <!-- /.panel-heading -->

                        <div class="panel-body">
                                   <div class="row">
                        <form>
                        
                            <!--
                            <div class="col-sm-2 form-group">
                                
                                        <label>Registros por página</label>
                                        <select class="form-control">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> 
                                
                                
                            </div>
                            -->    
                          
                            <div class="col-sm-6 col-sm-offset-4 form-group"> 
                                
                                <div class="col-sm-3 col-sm-offset-6 form-group">   
                                <input type="text" class="form-control" id="search" placeholder="Buscar por dni...." name="search" >
                                </div>
                                
                                <div class="col-sm-3 form-group">   
                                <button type="submit" class="btn btn-primary "><i class="fa fa-search fa-fw"></i> Buscar</button> 
                                </div>

                            </div>                            
                        </form>

                            </div>

                        
                      
                @foreach($grupos as $grupo)

                <table class="table table-hover table-striped">
                    <thead>
                        <!--
                        <th  style="width: 30%;">
                        @if ($sortby == 'nombre' && $order == 'asc') {{
                                link_to_action(
                                    'recursosController@recursosAtendidos',
                                    'Nombre',
                                    array(
                                        'sortby' => 'nombre',
                                        'order' => 'desc'
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'recursosController@recursosAtendidos',
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
                        <th style="width: 40%;">
                         @if ($sortby == 'grupo' && $order == 'asc') {{
                                link_to_action(
                                    'recursosController@recursosAtendidos',
                                    'Grupo',
                                    array(
                                        'sortby' => 'grupo',
                                        'order' => 'desc'
                                        )
                                    )
                               }}
                            @else {{
                                link_to_action(
                                   'recursosController@recursosAtendidos',
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
                        -->
                        
                            <th colspan="2"><a href ="">{{ $grupo->grupo }} <i class="fa fa-sort-desc fa-fw"></i></a></th>
                        
                            
                    </thead>
                    <tbody style ="display:none" id='tbody-{{$grupo->grupo_id}}'>
                    
                    <tr>
                    <tr>
                         @foreach($recursos as $recurso)
                            @if ($recurso->grupo == $grupo->grupo)
                                <tr>

                                    <td>
                                        {{HTML::link('tecnico/updateRecurso.html?id='.$recurso->id , $recurso->nombre)}}
                                    </td>
                                    <td>
                                        {{$recurso->grupo}}
                                    </td>
                                    
                                </tr>
                            @endif
                        @endforeach
                           
                    </tbody>
                    
                    </table>
                    @endforeach 
               

                {{  $recursos->appends(Input::except('page','result'))->links();}}


                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
    </div>
    
</div>

    
</div>
@stop