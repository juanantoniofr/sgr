@extends('layout')

@section('title')
    SGR: Añadir administrador
@stop



@section('content')
<div class="container">
	<div class="row">
    	{{$menuAdministradores or ''}}
	</div>
<div class="row">
        
    <div class="col-md-12">
               
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3><i class="fa fa-search fa-fw"></i> Buscar usuario</h3>     
            </div>
            <div class="panel-body">
                               
        		<form  role="search">    
                    <div class="form-group">
                        <label for="dni">UVUS:</label>
                        <input role="search" type="text" class="form-control" id="username" placeholder="UVUS" name="username">
                    </div>
                    <div class="form-group hidden">
                    	{{Form::text('idRecurso',$recurso->id,array('class' => 'form-control'))}}
          			</div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i> Buscar</button>            
                </form>
                                
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>
</div>

@if (!empty($username))
<div class="row">
    <div class="col-md-12">
               
	    <div class="panel panel-success">
            <div class="panel-heading">
			    <h3><i class="fa fa-list fa-fw"></i> Resultados de la busqueda</h3>
            </div>
            
            <div class="panel-body" >
            	@if (Session::has('msg'))
                	<div class="alert alert-warning alert-dismissable">
                  		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  		{{ Session::get('msg') }}
                	</div>
            	@else

                @if (!empty($users))
                	{{Form::open(array('method' => 'POST','route' => 'postaddRecursoAdmin','role' => 'form'))}}
                    	<label>Marcar para añadir como administrador:</label><br />
                       
                       	@foreach ($users as $user)
                        	<div class="checkbox">
                      			<label><input type="checkbox" name = "admins[]" value="{{$user->id}}"> {{$user->nombre}}, {{$user->apellidos}} - ({{$user->username}})</label>
                      		</div>
                        @endforeach
                        <div class="form-group hidden">
                        	{{Form::text('idRecurso',$recurso->id,array('class' => 'form-control'))}}
            			      </div>
            			      <div class="form-group hidden">
                      	 {{Form::text('username',$username,array('class' => 'form-control'))}}
            			     </div>

                       <button type="submit" class="btn btn-primary">Añadir</button>
                  {{Form::close()}}
				        @else
                	<div class="alert alert-warning" rol="alert">
                        <p>No hay usuarios con UVUS {{$username}}</p>
                    </div>  
               	@endif  
              @endif
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->

    </div>
</div>
@endif
</div><!-- /.container -->

</div><!-- /.container -->
@stop