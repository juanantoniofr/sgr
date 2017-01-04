<!-- marca branch master2 --><div id = "tableusuarios">
  <table class="table table-hover table-striped" >
    
    <thead>
      <th>#</th>  
      <th  style="width: 15%;">
        @if ($sortby == 'username' && $order == 'asc') 
          {{
            link_to_action(
              'UsersController@listar',
              'Username',
              array(
                'sortby' => 'username',
                'order' => 'desc',
                'veractivas' => $veractivas,
              )
            )
          }}
        @else
          {{
            link_to_action(
              'UsersController@listar',
              'Username',
              array(
                'sortby' => 'username',
                'order' => 'asc',
                'veractivas' => $veractivas,
              )
            )
          }}
        @endif
        <i class="fa fa-sort fa-fw text-info"></i>
      </th>
      <th style="width: 9%;">
        @if ($sortby == 'colectivo' && $order == 'asc') 
          {{
	          link_to_action(
	            'UsersController@listar',
	            'Colectivo',
	            array(
	              'sortby' => 'colectivo',
	              'order' => 'desc',
	              'veractivas' => $veractivas,
	            )
	          )
          }}
        @else
        	{{
            link_to_action(
              'UsersController@listar',
              'Colectivo',
              array(
                'sortby' => 'colectivo',
                'order' => 'asc',
          	    'veractivas' => $veractivas,
              )
            )
          }}
        @endif
        <i class="fa fa-sort fa-fw text-info"></i>
      </th>
      <th style="width: 18%;">
        @if ($sortby == 'rol' && $order == 'asc')
        	{{
            link_to_action(
              'UsersController@listar',
              'Rol',
              array(
                'sortby' => 'capacidad',
                'order' => 'desc',
                'veractivas' => $veractivas,
              )
            )
          }}
        @else 
        	{{
            link_to_action(
              'UsersController@listar',
              'Rol',
              array(
                'sortby' => 'capacidad',
                'order' => 'asc',
                'veractivas' => $veractivas,
              )
            )
          }}
        @endif
        <i class="fa fa-sort fa-fw text-info"></i>
      </th>
      <th style="width: 25%;">
      	@if ($sortby == 'apellidos' && $order == 'asc')
      		{{
      			link_to_action(
      				'UsersController@listar',
      				'Apellidos, nombre',
      				array(
      					'sortby' => 'apellidos',
      					'order' => 'desc',
      					'veractivas' => $veractivas,
      				)
      			)
      		}}
      	@else
      		{{
	      	 	link_to_action(
		      	 	'UsersController@listar',
		      	 	'Apellidos, nombre',
		      	 	array(
		      	 		'sortby' => 'apellidos',
		      	 		'order' => 'asc',
		      	 		'veractivas' => $veractivas,
		      	 	)
	      	 	)
      		}}
      	@endif
      	<i class="fa fa-sort fa-fw text-info"></i>
      </th>
      <th style="width: 20%;">Observaciones</th>
      <th >Última modificación</th>
    </thead>
                
    <tbody>
    	@foreach($sgrUsuarios as $user)
	      <tr id = "{{$user->id()}}">
					<td id ="{{$user->id()}}_estado">
	        	<i id ="{{$user->id()}}_activa" class="fa fa-check fa-fw text-success"  title='Cuenta Activa' @if($user->estado() == 0 || $user->caducado())style="display:none" @endif></i>
	          <i id ="{{$user->id()}}_caducada" class="fa fa-clock-o fa-fw text-danger" title='Cuenta Caducada' @if (!$user->caducado()) style="display:none" @endif></i> 
	          <i id ="{{$user->id()}}_desactiva" class="fa fa-minus-circle fa-fw text-danger " title='Cuenta Desactivada' @if($user->estado() == '1') style="display:none" @endif></i>
	        </td>
	        <td>
	        	<!-- delete user -->
	        	<a href="" class="eliminarUsuario" data-infousuario="{{$user->nombre()}} {{$user->apellidos()}} - {{$user->username()}} -" data-id="{{$user->id()}}" data-numreservas="{{$user->eventos()->count()}}"><i class="fa fa-trash fa-fw" title='borrar'></i></a>
	          <!-- edit user -->
	          <a href="" data-id="{{$user->id()}}" data-username="{{$user->username()}}" data-nombre="{{$user->nombre()}}" data-apellidos="{{$user->apellidos()}}" data-email="{{$user->email()}}" data-estado="{{$user->estado()}}" data-observaciones="{{$user->observaciones()}}" data-capacidad="{{$user->capacidad()}}" data-colectivo="{{$user->colectivo()}}" data-caducidad="{{ date('d-m-Y',strtotime($user->caducidad())) }}" class="editUser"><i class="fa fa-pencil fa-fw" title='editar'></i></a>
	          <span id ="username">{{$user->username()}}</span>
	        </td>
	        <td id ="{{$user->id()}}_colectivo">{{$user->colectivo()}}</td>
	        <td id ="{{$user->id()}}_rol">{{$user->getRol()}}</td>
					<td id ="{{$user->id()}}_apellidosnombre">{{$user->apellidos()}},{{$user->nombre()}}</td>
	        <td id ="{{$user->id()}}_observaciones"> {{$user->observaciones()}}</td>
	        <td ><small id ="{{$user->id()}}_updated_at">{{$user->updated_at()}}</small></td>
	      </tr>
      @endforeach
    </tbody>
  
  </table>

</div>