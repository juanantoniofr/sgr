<div class="col-lg-12">
        <h3 class=""><i class="fa fa-users fa-fw"></i> Gestión de Administradores</h3>
        
        <form class="navbar-form navbar-left">    
            <div class="form-group ">
                <a href="{{route('addRecursoAdmin',['idRecurso' => $idRecurso])}}" class="active btn btn-danger" title="Añadir nuevo Administrador"><i class="fa fa-plus fa-fw"></i> Nuevo Administrador</a></li>
            </div>
            <div class="form-group">
                <a href="{{route('admins',['idRecurso' => $idRecurso])}}" class="btn btn-primary" title="Listar administradores"><i class="fa fa-list fa-fw"></i> Listar admnistradores {{$recurso}}</a>
            </div>                      
                
        </form>
         <form class="navbar-form navbar-right">
            <div class="form-group ">
                <a  href="{{route('recursos')}}" class="btn btn-primary"><i class="fa fa-institution fa-fw"></i> Volver a Espacios y equipos<span class="fa arrow"></span></a>
            </div>
    	</form>

        
</div>