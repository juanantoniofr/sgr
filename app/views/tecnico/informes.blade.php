@extends('layout')

@section('title')
    SGR: informes perfil técnico
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Informes</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-8">
           
        <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i> Panel de notificaciones    
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-comment fa-fw"></i> Notificación 1
                                    <span class="pull-right text-muted small"><em>hace 4 minutos</em>
                                    </span>
                                </a>
                                
                            </div>
                            <!-- /.list-group -->
                            <a href="#" class="btn btn-default btn-block">Ver todas las notificaciones</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
    </div>
    
</div>

    
</div>
@stop