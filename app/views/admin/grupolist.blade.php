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
                    <a  href="{{route('addRecurso')}}" class="btn btn-danger" id="btnNuevoRecurso" title="Añadir nuevo Espacio o Equipo"><i class="fa fa-plus fa-fw"></i> Añadir Recurso</a>
                </div>
                <div class="form-group ">
                    <a href="#" class="btn btn-danger" id="btnNuevoGrupo" title="Añadir nuevo Grupo"><i class="fa fa-object-group fa-fw"></i> Añadir Grupo</a>
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

{{ $modalAddGrupo   or '' }}
{{ $modalEditGrupo  or '' }}
{{ $modalDelGrupo   or '' }}



<!-- ./ nuevo recurso -->
@stop

@section('js')
    {{HTML::script('assets/ckeditor/ckeditor.js')}}
    <script type="text/javascript">CKEDITOR.replace( 'fm_addgrupo_inputdescripcion' );</script>
    <script type="text/javascript">CKEDITOR.replace( 'fm_editgrupo_inputdescripcion' );</script>
    
    {{HTML::script('assets/js/admin.js')}}
  
@stop