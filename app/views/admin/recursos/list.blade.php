@extends('layout')
<!-- :) 1-5-2017 list recurso-->
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
              <a href="#" class="btn btn-warning" id="btnNuevoGrupo" title="Añadir nuevo Grupo"><i class="fa fa-object-group fa-fw"></i> Nuevo Grupo</a>
            </div>
          </form>
                  
          <div id="msg"></div> 
          
          <div id="tableRecursos">
            {{$table or ''}}
          </div>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-info -->
    </div><!-- /.row -->    

  </div> <!-- .container-fluid -->    

  <!-- grupos -->
  {{ $modalAddGrupo                 or '' }}
  {{ $modalEditGrupo                or '' }}
  {{ $modalDelGrupo                 or '' }}
  {{ $modalAddRecursosToGrupo       or '' }}
  
  <!-- relaciones -->
  {{ $modalAddRelacion         or '' }}
  {{ $modalRemoveRelacion      or '' }}

  <!-- recursos -->
  {{ $modalAddRecurso         or '' }}
  {{ $modalEditRecurso        or '' }}
  {{ $modalDelRecurso         or '' }}
  {{ $modalEnabledRecurso     or '' }}
  {{ $modalDisabledRecurso    or '' }}
  

  <!-- Puestos -->
  
  
  {{ $modalAddPuestoExistente or '' }}

  <!-- Equipos -->
  {{ $modalAddEquipoExistente or '' }}
  
  
@stop

@section('js')
  {{HTML::script('assets/ckeditor/ckeditor.js')}}
  <script type="text/javascript">CKEDITOR.replace( 'fm_addgrupo_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_editgrupo_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_addrecurso_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_editrecurso_inputdescripcion' );</script>
  <script type="text/javascript">CKEDITOR.replace( 'fm_disabledrecurso_motivo' );</script>
  
  {{  HTML::script('assets/js/relaciones.js') }}    
  {{  HTML::script('assets/js/recursos.js')   }}
  {{  HTML::script('assets/js/grupo.js')      }}
  {{  HTML::script('assets/js/comun.js')      }}
  {{  HTML::script('assets/js/all.js')      }}
  
@stop