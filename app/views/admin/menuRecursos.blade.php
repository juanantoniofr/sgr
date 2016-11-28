<div class="col-lg-12">
        <h3 class=""><i class="fa fa-institution fa-fw"></i> Gestión de espacios y equipos</h3>
        
        <form class="navbar-form navbar-left">
            <div class="form-group ">
                <a href="{{route('addRecurso')}}" class="active btn btn-danger" id="btnNuevoRecurso" title="Añadir nuevo Espacio o Equipo"><i class="fa fa-plus fa-fw"></i> Añadir</a>
            </div>
            <div class="form-group ">
                <a href="{{route('recursos')}}" class="btn btn-primary" title="Listar Espacios o Medios"><i class="fa fa-list fa-fw"></i> Listar todos</a>
            </div>                            
                
        </form>
        
        <form class="navbar-form navbar-right" role="search">
            
            <div class="form-group ">
                <input type="text" class="form-control" id="search" placeholder="Busqueda por nombre...." name="search" >
                <button type="submit" class="btn btn-primary form-control"><i class="fa fa-search fa-fw"></i> Buscar</button> 
            </div>                            
                
        </form>
</div>