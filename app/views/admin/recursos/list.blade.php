@extends('layout')

@section('title')
  SGR: Gestión de Espacios y Equipos
@stop

@section('content')
  <div class="container">
    
    <div class="row">
      <h2 class=""><i class="fa fa-institution fa-fw"></i> Gestión de espacios y equipos</h2>
    </div><!-- ./row -->


    <div class="row" style="margin-top:5px" >
      <div id = "espera" style="display:none"></div>
      <div class="panel panel-info">
                  
        <div class="panel-heading"><h3><i class="fa fa-list fa-fw"></i> Listado de recursos</h3></div>

        <div class="panel-body">
                                          
          <form class="navbar-form">
            <div class="form-group ">
              <a  href="#" class="btn btn-primary" id="btnNuevoRecurso" title="Añadir nuevo Espacio"><i class="fa fa-plus fa-fw"></i> Añadir Recurso</a>
            </div>
            <!--
            <div class="form-group ">
              <a  href="#" class="btn btn-primary" id="btnNuevoRecurso" title="Añadir nuevo Equipo"><i class="fa fa-plus fa-fw"></i> Añadir Equipo</a>
            </div>
            <div class="form-group ">
              <a  href="#" class="btn btn-primary" id="btnNuevoRecurso" title="Añadir nuevo Puesto"><i class="fa fa-plus fa-fw"></i> Añadir Puesto</a>
            </div>
            -->
            <div class="form-group ">
              <a href="#" class="btn btn-warning" id="btnNuevoGrupo" title="Añadir nuevo Grupo"><i class="fa fa-object-group fa-fw"></i> Añadir Grupo</a>
            </div>
          </form>
                  
          <div class=""  id="success_recurselist_msg" style="display:none" role="alert">
            <i class="fa fa-check fa-fw text-success"></i><span id="success_recurselist_textmsg"></span> 
          </div>
          
          <div id="tableRecursos">
            {{$table or ''}}
          </div>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-info -->
    </div><!-- /.row -->    

  </div> <!-- .container-fluid -->    

  <!-- grupos -->
  {{ $modalAddGrupo           or '' }}
  {{ $modalEditGrupo          or '' }}
  {{ $modalDelGrupo           or '' }}
  {{ $modalAddRecursosToGrupo or '' }}

  <!-- recursos -->
  {{ $modalAddRecurso         or '' }}
  {{ $modalEditRecurso        or '' }}
  {{ $modalDelRecurso         or '' }}
  {{ $modalEnabledRecurso     or '' }}
  {{ $modalDisabledRecurso    or '' }}
  {{ $modalAddPersona         or '' }}
  {{ $modalRemovePersona      or '' }}

  <!-- Puestos -->
  {{ $modalAddPuesto          or '' }}
  {{ $modalEditPuesto         or '' }}
  {{ $modalAddPuestoExistente or '' }}

  <!-- Equipos -->
  {{ $modalAddEquipoExistente or ''}}
  {{ $modalAddEquipo          or ''}}
@stop

@section('js')
  {{HTML::script('assets/ckeditor/ckeditor.js')}}
  <script type="text/javascript">CKEDITOR.replace( 'fm_addgrupo_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_editgrupo_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_addrecurso_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_editrecurso_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_addpuesto_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_disabledrecurso_motivo' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_editpuesto_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_addequipo_inputdescripcion' );</script>
      
  {{HTML::script('assets/js/admin.js')}}
@stop