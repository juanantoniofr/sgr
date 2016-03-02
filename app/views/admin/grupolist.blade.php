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
    <!--
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li ><a  href="{{route('recursos')}}"> <i class="fa fa-list fa-fw"></i> Recursos</a></li>
            <li class="active"><a href="{{route('getListadoGrupos')}}"> <i class="fa fa-list fa-fw"></i> Grupos</a></li>
        </ul>
    </div>
    -->
</div>


<div class="row" style="margin-top:5px" >
    <div id = "espera" style="display:none"></div>
    <div class="panel panel-info">
            
        <div class="panel-heading">
            <h3><i class="fa fa-list fa-fw"></i> Listado de recursos</h3>
        </div>

        <div class="panel-body">
                                    
          
            <form class="navbar-form">
                <div class="form-group ">
                    <a  href="{{route('addRecurso')}}" class="btn btn-primary" id="btnNuevoRecurso" title="Añadir nuevo Espacio o Equipo"><i class="fa fa-plus fa-fw"></i> Añadir Recurso</a>
                </div>
                <div class="form-group ">
                    <a href="#" class="btn btn-primary" id="btnNuevoGrupo" title="Añadir nuevo Grupo"><i class="fa fa-object-group fa-fw"></i> Añadir Grupo</a>
                </div>
            </form>
            
            
            <div class="" id = "success_recurselist_msg" style="display:none" role="alert"> 
            </div>
            

            <div id="tableRecursos">
                {{$tableRecursos or ''}}
            </div>
            
                
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
    <script type="text/javascript">CKEDITOR.replace( 'descripcion' );</script>
    
    {{HTML::script('assets/js/admin.js')}}
  
@stop