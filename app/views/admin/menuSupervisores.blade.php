<div class="col-lg-12">
        <h3 class=""><i class="fa fa-users fa-fw"></i> Gestión de Supervisores</h3>
        
        <form class="navbar-form navbar-left">    
            <div class="form-group ">
                <a href="" class="active btn btn-danger" title="Añadir nuevo supervisor" id="addsupervisor"><i class="fa fa-plus fa-fw"></i> Nuevo supervisor</a></li>
            </div>
            <div class="form-group">
                <a href="{{route('supervisores',['idRecurso' => $idRecurso])}}" class="btn btn-primary" title="Listar supervisores"><i class="fa fa-list fa-fw"></i> Listar supervisores de {{$recurso}}</a>
            </div>                      
                
        </form>
         <form class="navbar-form navbar-right">
            <div class="form-group ">
                <a  href="{{route('recursos')}}" class="btn btn-primary"><i class="fa fa-institution fa-fw"></i> Volver a Espacios y equipos<span class="fa arrow"></span></a>
            </div>
    	</form>

        
</div>