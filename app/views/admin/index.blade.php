@extends('layout')
<!-- marca branch master2 -->
@section('title')
    Acceso para administradores: Inicio
@stop

@section('content')
<div class="container">



    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><i class="fa fa-dashboard fa-fw"></i> Escritorio</h2>
        </div>
        <!-- /.col-lg-12 -->
    </div>


    <div class="row">
        
        
              
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2><i class="fa fa-comment fa-fw"></i> Peticiones de alta </h2>   
                </div><!-- /.panel-heading -->
                
                <div class="alert alert-success alert-dismissible" role="alert" id="msgsuccess" style="display:none" ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p id ="textmsgsuccess"></p>
                </div>   
                
                <div class="alert alert-danger alert-dismissible" role="alert" id="msgerror" style="display:none" ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p id ="textmsgerror"></p>
                </div> 
                
                <div class="panel-body" id='listTask'>
                            @if($notificaciones->count()>0)
                                @foreach($notificaciones as $notificacion)
                                    <div class="list-group" data-uvus = "{{$notificacion->source}}" data-sourceId = "{{$notificacion->id}}">
                                        <a href="#"   class="list-group-item" title="Activar" data-toggle="modal" data-target="#modalUser">
                                            {{$notificacion->msg }}
                                           <!-- <span class="pull-right text-muted small"><em>hace 4 minutos</em>
                                            </span>-->
                                        </a>
                                        
                                    </div>
                                    <!-- /.list-group -->
                                @endforeach
                               <!-- <a href="#" class="btn btn-default btn-block">Ver todas las peticiones</a> -->
                            
                            @else
                                <div class="alert alert-warning" role="alert">No hay peticiones pendientes</a>
                            @endif
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
        
        
    </div>



    <div id = "espera" style="display:none"></div>


</div>



<div class="modal fade" id="modalUser">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Petición de alta</h4>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" role="form" id = "activeUser" data-item=''>
        
            <div class="form-group" id="uvus">
                    <label for="uvus"  class="control-label col-md-3" >Uvus: </label>   
                    <div class = "col-md-9">  
                        <input type="text" name = "uvus" class="form-control" id="uvus"  disabled/>
                    </div>             
            </div>

            <div class="form-group" id="colectivo">
                <label for="colectivo"  class="control-label col-md-3" >Colectivo: </label>
                <div class = "col-md-9">  
                    <select class="form-control" name='colectivo'>
                        <option value="Alumno" selected="selected">Alumno</option>
                        <option value="PDI">PDI</option>
                        <option value="PAS">PAS</option>
                    </select> 
                </div>
                
            </div>

            <div class="form-group" id="rol">
                <label for="rol"  class="control-label col-md-3" >Rol: </label>
                <div class = "col-md-9">  
                    <select class="form-control"  name="rol">
                        <option value="1" selected="selected" >Usuario (Alumnos)</option>
                        <option value="2" >Usuario Avanzado (PDI & PAS Administración)</option>
                        <option value="3">Técnico (PAS - Técnico)</option>
                        <option value="4">Administrador (de SGR)</option>
                        <option value="5">Validador (Decanato//Administrador de Centro)</option>
                        <option value="6">Supervisor (EE MAV)</option>
                    </select> 
                </div>
            </div>

            <div class="form-group">
                <label for="caducidad"  class="control-label col-md-3" >Caduca el: </label> 
                <div class="col-md-9">  
                    <input type="text" name="caducidad" class="form-control" id="datepickerCaducidad" value=""/>
                </div>
          </div>
          <div class="form-group">
            <label for="observaciones"  class="control-label col-md-3" >Observaciones (opcional): </label> 
            <div class = "col-md-9">
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>
          </div>      
        </form>    
      </div><!-- ./modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="borrar"><i class="fa fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-warning" id="desactivar"><i class="fa fa-toggle-off" aria-hidden="true"></i> Desactivar</button>
        <button type="button" class="btn btn-primary" id="activar"><i class="fa fa-check" aria-hidden="true"></i> Activar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('js')
{{HTML::script('assets/js/notificaciones.js')}}

@stop

