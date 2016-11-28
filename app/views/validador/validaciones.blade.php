@extends('layout')

@section('title')
	SGR: Validaciones
@stop



@section('content')
<div class="container">
<div class="row">
    {{$menuValidador or ''}}
</div>


      		
<div id = "espera" style="display:none"></div>
	  			
<div class="row">
    <div class="panel panel-info">
    	<div class="panel-heading">
            <h4><i class="fa fa-list fa-fw"></i>Listado de solicitudes</h4>
    	</div><!-- /.panel-heading -->
	    <div class="panel-body">
		   
			<!-- msg for user -->
			@if($solapamientos)
			   	<div class="alert alert-danger" role="alert" >
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    	<strong>No se pudo aprobar la solicitud: solapamiento con solicitud ya aprobada.</strong>
				</div>
			@endif

			@if(!empty($msg))
			   	<div class="alert alert-success" role="alert" >
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    	{{ $msg }}
				</div>
			@endif    	   	          

			@if (!$events->count()) 
	            <div class="alert alert-warning" role="alert">No hay solicitudes....</div>
			@else 
				<div class="table-responsive"> 
		  			<table class="table table-hover table-striped">
		  				<thead>
		  					<th style="width: 10%">Estado</th>
		  					<th style="width: 2%;"></th>
		  					<th style="width: 20%;">Calendario</th>
		  					
		  					<th  style="width: 10%;">Espacio</th>
		  					<th style="width: 20%;">Persona</th>
							<th style="width: 15%;">
							  	@if ($sortby == 'titulo' && $order == 'asc') {{
				                	link_to_action(
				                    	'ValidacionController@index',
				                    	'Titulo',
				                        array(
				                        	'sortby' => 'titulo',
				                            'order' => 'desc',
				                            'id_recurso' => $idrecurso,
				                            'id_user'	=>	$iduser
				                            )
				                        )
				                   }}
			                    @else {{
				              	    link_to_action(
				                       'ValidacionController@index',
				                            'Titulo',
				                            array(
				                                'sortby' => 'titulo',
				                                'order' => 'asc',
				                                'id_recurso' => $idrecurso,
				                                'id_user'	=>	$iduser
				                            )
				                        )
				                    }}
				                @endif
				                <i class="fa fa-sort fa-fw"></i>
			  				</th>
							<th>
								@if ($sortby == 'created_at' && $order == 'asc') {{
				                	link_to_action(
				                    	'ValidacionController@index',
				                    	'Fecha de registro',
				                        array(
				                        	'sortby' => 'created_at',
				                            'order' => 'desc',
				                            'id_recurso' => $idrecurso,
				                            'id_user'	=>	$iduser
				                            )
				                        )
				                   }}
			                    @else {{
				              	    link_to_action(
				                       'ValidacionController@index',
				                            'Fecha de registro',
				                            array(
				                                'sortby' => 'created_at',
				                                'order' => 'asc',
				                                'id_recurso' => $idrecurso,
				                            	'id_user'	=>	$iduser
				                            )
				                        )
				                    }}
				                @endif
				                <i class="fa fa-sort fa-fw text-info"></i>
			  				</th>
		  				</thead>
		    			<tbody>

		    				@foreach($events as $event)
		    				
			    					@if ($event->estado == 'aprobada')
			    						<tr  class=" text-success event"  data-idEvent = "{{$event->id}}">
				    						<td >
				                        		<span  class="glyphicon glyphicon-check" aria-hidden="true" data-toggle="tooltip" title="Solicitud aprobada">Aprobada </span> 
				                        	</td>
				    						<td><i class="fa fa-calendar fa-fw "></i></td>
				    						<td>
				    							 <span data-toggle="tooltip" title="Aprobada" class="small"><strong>Inicio</strong>: {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaInicio)}}<br /><strong> Fin:</strong> {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaFin)}}<br /><strong>Horario:</strong> {{$event->horaInicio}} a {{$event->horaFin}}<br /><strong>Dias:</strong> {{Date::sgrdiassemana($event->diasRepeticion)}}</span>
				    						</td>
				    						<td>
				                        		<span aria-hidden="true" data-toggle="tooltip" title="Aprobada">{{$event->recursoOwn->nombre}}</span>
				                        	</td>
				                        
				                    @elseif($event->estado == 'denegada')
			                   			<tr class=" text-danger event"  data-idEvent = "{{$event->id}}">
				    						<td>
				                        		<span  class="fa fa-minus-circle fa-fw" aria-hidden="true" data-toggle="tooltip" title="Solicitud denegada">Denegada</span>
				                        	</td>
				    						<td><i class="fa fa-calendar fa-fw "></i></td>
				    						<td>
				    							 <span title="Click para aprobar o denegar... " class="small"><strong>Inicio</strong>: {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaInicio)}}<br /><strong> Fin:</strong> {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaFin)}}<br /><strong>Horario:</strong> {{$event->horaInicio}} a {{$event->horaFin}}<br /><strong>Dias:</strong> {{Date::sgrdiassemana($event->diasRepeticion)}}</span>
				    						</td>
				    						
				                        	<td>
				                        		<span title="Click para aprobar o denegar... ">{{$event->recursoOwn->nombre}}</span>
				                        	</td>
				                        
			                    	@elseif($event->estado == 'pendiente' && !Calendar::hasSolapamientos($event->evento_id,$event->recurso_id))
			                    	    <tr class="event  text-info"  data-idEvent = "{{$event->id}}">
				    				    	<td>
				                        		<span  class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" title="Solicitud pendiente de validación">Pendiente</span>
				                        	</td>
				    				    	<td><i class="fa fa-calendar fa-fw "></i></td>
				                        	<td>
				                        		<span title="Click para aprobar o denegar... " class="small"><strong>Inicio:</strong> {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaInicio)}}<br /><strong>Fin: </strong>{{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaFin)}}<br /><strong>Horario: </strong> {{$event->horaInicio}} a {{$event->horaFin}}<br /><strong>Dias:</strong> {{Date::sgrdiassemana($event->diasRepeticion)}}</span>
				                        	</td>
				                        	
				                        	<td>
				                        		<span title="Click para aprobar o denegar... ">{{$event->recursoOwn->nombre}}</span>
				                        	</td>
				                        
				                   	@elseif($event->estado == 'pendiente' && Calendar::hasSolapamientos($event->evento_id,$event->recurso_id))
				    					<tr class=" text-warning event"  data-idEvent = "{{$event->id}}">
				    						<td>
				                        		<span  class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip"
				                        		title="Solicitud Pendiente: Con solapamiento">Pendiente (hay Solapamiento)</span>
				                        	</td>
				    						

				    						<td><i class="fa fa-calendar fa-fw "></i></td>
				    						<td>
				    							 <span title="Click para aprobar o denegar... " class="small"><strong>Inicio</strong>: {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaInicio)}}<br /><strong> Fin:</strong> {{Date::sgrStrftime('%A, %d de %B de %Y',$event->fechaFin)}}<br /><strong>Horario:</strong> {{$event->horaInicio}} a {{$event->horaFin}}<br /><strong>Dias:</strong> {{Date::sgrdiassemana($event->diasRepeticion)}}</span>
				    						</td>
				    						
				                        	<td>
				                        		<span title="Click para aprobar o denegar... ">{{$event->recursoOwn->nombre}}</span>
				                        	</td>
				                        
				    			
			                   		@endif
			                    
			    				        
				                        <td><i class="fa fa-user fa-fw "></i><span title="Click para aprobar o denegar... ">{{$event->userOwn->apellidos}}, {{$event->userOwn->nombre}}</span></td>

				                        <td><i class="fa fa-angle-double-right fa-fw "></i><span title="Click para aprobar o denegar... ">{{$event->titulo}}</span></td>
				                        
				                        <td><i class="fa fa-clock-o fa-fw "></i><span title="Click para aprobar o denegar... " class="pull-center  small"><em> {{date('d \d\e M \d\e Y \a \l\a\s H:i',strtotime($event->created_at))}}</em></span></td>
				                    </tr>
				                
			                    @endforeach
		    			</tbody>
					</table>
				</div><!-- /.table-responsive -->
			@endif
			
			{{$events->appends(Input::except('page','result'))->links();}}
	     </div><!-- /.panel-body -->
	</div><!-- /.panel-info -->
</div><!-- /.row -->

</div><!-- /.container -->
@stop

@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalValidacion" tabindex="-1" role="dialog" aria-labelledby="mValida" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="mValida">Solicitud de reserva</h4>
          </div>
          <div class="modal-body">
            <div class="panel panel-default">
                <div class="panel-heading">Registro</div>
                <div class="panel-body">
                    <dl class="dl-horizontal" >
                        <dt>Fecha de la Petición: </dt>
                        <dd id ="fPeticion"></dd>
                        <dt>Estado: </dt>
                        <dd id ="estado"></dd>
                    </dl>
                </div>
            </div> 
            <div class="panel panel-default">
                <div class="panel-heading">Solicitud</div>
                <div class="panel-body">
                    <dl class="dl-horizontal">

                        <dt>Espacio: </dt>
                        <dd id ="espacio"></dd>
                        <dt>Actividad: </dt>
                        <dd id ="actividad"></dd>
                        <dt>Usuario: </dt>
                        <dd id ="usuario"></dd>
                        <dt>Título: </dt>
                        <dd id ="titulo"></dd>
                        <dt>Fecha de inicio: </dt>
                        <dd id ="fInicio"></dd>
                        <dt>Fecha de Finalización: </dt>
                        <dd id ="fFin"></dd>
                        <dt>Horario: </dt>
                        <dd id ="horario"></dd>
                        <dt>Día/s de la semana: </dt>
                        <dd id ="dSemana"></dd>
                    </dl>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-success" href="" role="button" id="aprobar"><i class="fa fa-check fa-fw"></i> Aprobar</a>
            <a class="btn btn-danger" href="" role="button" id="denegar"><i class="fa fa-check fa-fw"></i> Denegar</a>
            
          </div>
        </div>
      </div>
    </div>
@stop

@section('js')
	{{HTML::script('assets/js/validador.js')}}
@stop