<!-- marca branch master2 --><div class="col-lg-12">
        <h3 class=""><i class="fa fa-users fa-fw"></i> Gestión de Administradores</h3>
        
        <form class="navbar-form navbar-left">    
            <div class="form-group ">
                <a href="" class="active btn btn-danger" title="Añadir nuevo administrador" id="addadministrador"><i class="fa fa-plus fa-fw"></i> Nuevo administrador</a></li>
            </div>
            <div class="form-group">
                <a href="{{route('administradores',['idRecurso' => $idRecurso])}}" class="btn btn-primary" title="Listar administradores"><i class="fa fa-list fa-fw"></i> Listar administradores de {{$recurso}}</a>
            </div>                      
                
        </form>
         <form class="navbar-form navbar-right">
            <div class="form-group ">
                <a  href="{{route('recursos')}}" class="btn btn-primary"><i class="fa fa-institution fa-fw"></i> Volver a Espacios y equipos<span class="fa arrow"></span></a>
            </div>
    	</form>

        
</div>