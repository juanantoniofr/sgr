@extends('layout')

@section('title')
    SGR: Gestión de Espacios y Equipos
@stop

@section('css')
    
    
@stop

@section('content')
<div class="container">
<div class="row">
    <h2 class=""><i class="fa fa-institution fa-fw"></i> Gestión de espacios y equipos</h2>
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li ><a  href="{{route('recursos')}}"> <i class="fa fa-list fa-fw"></i> Recursos</a></li>
            <li class="active"><a href="{{route('grupos')}}"> <i class="fa fa-list fa-fw"></i> Grupos</a></li>
        </ul>
    </div>
</div>


<div class="row" style="margin-top:5px" >
    <div id = "espera" style="display:none"></div>
    <div class="panel panel-info">
            
        <div class="panel-heading">
            <h3><i class="fa fa-list fa-fw"></i> Listado de grupos</h3>
        </div>

        <div class="panel-body">
                                    
          
            <form class="navbar-form navbar-left">
                <div class="form-group ">
                    <a href="#" class="btn btn-danger" id="btnNuevoGrupo" title="Añadir nuevo Grupo"><i class="fa fa-plus fa-fw"></i> Nuevo Grupo</a>
                </div>
            </form>
                
            <form class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <div class="input-group ">
                        <span class="alert-info input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                        <input type="text" class="form-control" id="search" placeholder="Buscar grupo...." name="search" >
                        <!--<button type="submit" class="btn btn-primary form-control"><i class="fa fa-search fa-fw"></i> Buscar</button> -->
                    </div>                            
                </div>
            </form> 

            
            <div class="alert alert-success text-center" id = "success_recurselist_msg" style="display:none" role="alert"> 
            </div>
            
            <table class="table table-hover table-striped">
                <thead>
                    <th style="width:2%" >Id.</th>
                    <!-- Order column by nombre de grupo--> 
                    <th style="width:30%" >
                        @if ($sortby == 'nombre' && $order == 'asc') {{
                            link_to_action(
                                'recursosController@listargrupos',
                                'Nombre',
                                array(
                                    'sortby' => 'nombre',
                                    'order' => 'desc',
                                )
                            )
                        }}
                        @else {{
                            link_to_action(
                               'recursosController@listargrupos',
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
                    <th style="width:68%">Recursos
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
                                    <li>{{$recurso->nombre}}</li>
                                @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            
                
            </div><!-- /.panel-body -->

        </div>
        <!-- /.panel-default -->
    
</div>
<!-- /.row -->    
</div> <!-- .container-fluid -->    

<!-- modal eliminar recurso -->
<div class="modal fade" id="modalborrarGrupo" tabindex="-8" role="dialog" aria-labelledby="borrarGrupo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar grupo</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el grupo: "<b><span id="borrarNombreGrupo"></span>"</b> ?</div>
                       
            </div><!-- ./.modal-body -->

            <div class="modal-footer">
                <a class="btn btn-primary" href="" role="button" id="grupoModal_btnEliminar"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- ./.modal-content -->
    </div><!-- ./.modal-dialog -->
</div><!-- ./#modalborrarGrupo -->

{{ $modalEditGrupo                  or '' }}
{{ $modalAddGrupo                  or '' }}




<!-- ./ nuevo recurso -->
@stop

@section('js')
    {{HTML::script('assets/ckeditor/ckeditor.js')}}
    <script type="text/javascript">CKEDITOR.replace( 'motivo' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'descripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'editdescripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'updatedescripciongrupo' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'fm_addgrupo_inputdescripcion' );</script>
    
    {{HTML::script('assets/js/admin.js')}}
  
@stop