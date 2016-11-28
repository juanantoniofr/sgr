@extends('layout')

@section('title')
    SGR: inicio para técnicos MAV
@stop


@section('content')
<div id = "espera" style="display:none"></div>
<div class="container">
    
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-header"><i class="fa fa-check fa-fw"></i> Atención de reservas</h2>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    
    <div class="row">
            <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3><i class="fa fa-credit-card fa-fw"></i> Lector carnet</h3>
                </div>

                <div class="panel-body">
            
                    <applet id="lector"  
                        code="fcom.maviuno.LectorCarnetUniversitario/InfoUI.class" 
                        codebase="https://servidorfcom.us.es/sgr/assets/applet"
                        archive="LectorCarnetUniversitario.jar, json-simple-1.1.1.jar" 
                        width=448
                        height=358>
                    </applet><!-- ./applet -->
                    
                    
                </div><!-- /.panel-body -->
                
                <div class="panel-footer">
                    
                    <p><span id = "dni" style="display:none" ></span><p>
                    
                </div>
            </div><!-- /.panel -->
            </div>

            <div class="col-md-6">   
            <div class="panel panel-warning">
                

                <div class="panel-heading">
                    <h3><i class="fa fa-list fa-fw"></i> Reservas usuario UVUS: <b><span id="uvus" style="display:none"></span></b></h3>
                </div><!-- /.panel-heading -->
        
                <div class="panel-body"  >
                    <div style = "display:none" class="alert alert-success alert-dismissable col-md-12 text-center" role="alert" id="success"><span></span></div>
                    <div style = "display:none" class="alert alert-danger alert-dismissable col-md-12 text-center" role="alert" id="error"><span></span></div>
                    <div style = "display:none" class="alert alert-danger alert-dismissable col-md-12 text-center" role="alert" id="errorgetEvents"><span></span></div>
                    
                    
                    <div id="divSearch" style="display:none">
                       <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-bottom:10px;">Nueva reserva diaria:</h4>
                         
                        <a href="{{route('calendarios.html')}}"  class="btn btn-danger" id="btnNuevaReserva" ><i class="fa fa-calendar fa-fw"></i> Añadir reserva para <b><span id="uvusBtn"></span></b></a>
                        <a href="" class="btn btn-primary updateList"><i class="fa fa-refresh fa-fw"></i> Actualizar lista</a>

                       <h4 style = "border-bottom:1px solid #bbb;color:#999;margin:0px;margin-top:10px;">Reservas:</h4>
                        <p class="" id="resultsearch" ></p>

                        
                    </div>                    
                </div><!-- /.panel-body -->

                
            
            </div><!-- /.panel -->
            </div>
    </div>

    
</div><!-- /.container -->
{{$addModal or ''}}

<!-- actualizar lista de eventos -->
<div class="modal fade" id="update">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Actualizar lista de reservas</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning text-center" role="alert">
            <p>La lista de reservas necesita ser actualizada... </p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary updateList" id="#btnUpdateList" data-dismiss="modal"><i class="fa fa-refresh fa-fw"></i> Actualizar lista</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('js')
{{HTML::script('assets/js/applet.js')}}
<script type="text/javascript">
        function writeToContainer(valor){
            
            
            
                $('#dni').html(valor).change();
            
            
            
            
            }    
</script>

@stop