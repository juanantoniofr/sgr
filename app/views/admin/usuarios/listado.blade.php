@extends('layout')
<!-- :( -->
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
                        
        <div id="msg"></div>    
            
            {{$tableUsuarios or ''}}
               
        </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
  </div><!-- /.row -->    
</div><!-- /.container -->


<!-- modal eliminar recurso -->
<div class="modal fade" id="modalEliminaUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminaUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Eliminar usuario</h4>
            </div>

            <div class="modal-body">
        
                <div class="alert alert-danger text-center" role = "alert">¿Estás seguro que deseas <b>eliminar</b> el usuario: "<b><span id="infoUsuario"></span>"</b> ?</div>
                <div class="alert alert-warning text-center" > El usuario se eliminará de forma permanente.</div>
       
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