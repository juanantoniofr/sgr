<?php

class CalendarController extends BaseController {
	
	//Carga la vista por defecto: Mensual 
	public function index(){//***
		$viewActive = Config::get('options.defaultview'); //vista por defecto
		$fecha = new DateTime();
		$sgrRecurso = Factoria::getRecursoInstance();
		$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);
		$gruposderecursos = GruposController::gruposVisibles(Auth::user()->capacidad);
		$dropdown = Auth::user()->dropdownMenu();
		return View::make('calendario.index')->with('sgrCalendario',$sgrCalendario)->with('viewActive',$viewActive)->nest('sidebar','sidebar',array('tsPrimerLunes' => $sgrCalendario->fecha()->getTimestamp(),'grupos' => $gruposderecursos))->nest('dropdown',$dropdown)->nest('modalDeleteReserva','calendario.modal.deleteEvento')->nest('modalAddReserva','calendario.modal.addEvento')->nest('modalfinalizareserva','calendario.modal.finalizaEvento')->nest('modalanulareserva','calendario.modal.anulaEvento')->nest('modaldescripcion','calendario.modal.descripcion')->nest('modalAtenderReserva','calendario.modal.atenderEvento')->nest('modalMsg','modalMsg');
	}

	public function getCalendar(){
		//In
		$id_recurso = Input::get('id_recurso','');
		$id_item 		= Input::get('id_item',0);
		$viewActive = Input::get('viewActive',Config::get('options.defaultview')); //vista por defecto
		$day = Input::get('day',date('d'));
		$month = Input::get('month',date('n'));
		$year = Input::get('year',date('Y'));

		//out
    $result = array('error' 		=> false,
                    'calendar' 	=> '',
                    'errors' 		=> array());

		//Validación de formulario   
    $rules = array(	'id_recurso' 	=> 'required|exists:recursos,id',
    								'id_item'			=> 'sometimes|required',
    								'viewActive'	=> 'required|in:'.implode(',',Config::get('options.viewsCalendar')),
    							);
    $messages = array(	'id_recurso.exists' => 'id_recurso no encontrado....',
    										'id_item.exists'  	=> 'id_item no encontrado....',
          							'required'					=> 'El campo <strong>:attribute</strong> es obligatorio....',
          							'in'      					=> 'Vista no definida ...',
          						);
    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
			if ($id_item != 0) $id_recurso = $id_item; //$id_item es id_recurso para puesto
			$fecha = new DateTime($year.'-'.$month.'-'.$day);
			$recurso = Recurso::findOrFail($id_recurso);
			$sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
			$sgrRecurso->setRecurso($recurso);
			$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);
			$caption = (string) CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$year);
			$head = (string) CalendarController::head($viewActive,$sgrCalendario);
			$body = (string) CalendarController::body($viewActive,$sgrCalendario);
			//$body = '';
			$result['calendar'] = (string) View::make('calendario.calendar')->with(compact('caption','head','body'));
		}

    return $result;
	}

	public static function head($viewActive,$sgrCalendario){
		switch ($viewActive) {
			case 'month':
				return (string) View::make('calendario.month.head');
				break;
			case 'week':
				return (string) View::make('calendario.week.head')->with('sgrCalendario',$sgrCalendario);
				break;
		}
	}

	public static function caption($viewActive,$day,$nombreMes,$year){
		return (string) View::make('calendario.allViews.caption')->with('view',$viewActive)->with('day',$day)->with('nombreMes',$nombreMes)->with('year',$year);
		}

	public static function body($viewActive,$sgrCalendario){
		
		$diaActual = 1;
		$j=1;
		
		switch ($viewActive) {
			
			case 'month':
				return (string) View::make('calendario.month.body')->with('sgrCalendario',$sgrCalendario);
				break;
			
			case 'week':
				return (string) View::make('calendario.week.body')->with('sgrCalendario',$sgrCalendario);	
				break;
			
			case 'agenda':
				$maxEventsByPage = 10;
				$startDate = date('Y-m-d',strtotime($year . '-' . $numMonth .'-'. $day));
				$events = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','>=',$startDate)->orderby('fechaEvento','Asc')->orderby('horaInicio','Asc')->orderby('titulo','Asc')->paginate($maxEventsByPage);
		
				return (string) View::make('calendario.bodyAgenda')->with('events',$events);
				break;
			
			case 'day':
				return '<p>Aún en desarrollo.....</p>';	
				break;

			case 'year':
				return '<p>Aún en desarrollo....</p>';
				break;	
			
			default:
				$table['tBody'] = 'Error al generar vista...';
				break;	
		}
	}

	//Generación de pdf's para imprimir
	public function imprime(){
		//!!!***Validar parámetros de entrada***!!!
		//get Input or default
		$viewActive = Input::get('view','month');
		$day = Input::get('day',date('d'));
		$month = Input::get('month',date('m'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('idRecurso','');
		//$id_grupo = Input::get('groupID','');
		$id_item = Input::get('item',0);//Todos los items (equipos o puestos)
		$titulo = Input::get('titulo',false);
		$nombre = Input::get('nombre',false);
		$colectivo = Input::get('colectivo',false);
		$total = Input::get('total',false);//Total de puestos o equipos de una reserva
		$datatoprint = array('titulo' => $titulo,'nombre' => $nombre,'colectivo' => $colectivo,'total' => $total);//Información a imprimir seleccionada por el usuario
		
		//Output
		$table = array( 'tCaption'	=> '',
										'tHead' 		=> '',
										'tBody'			=> '');

		$fecha = new DateTime($year.'-'.$month.'-1');
		$recurso = Recurso::findOrFail($id_recurso);
		$sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
		$sgrRecurso->setRecurso($recurso);
		$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);

		$table['tCaption'] = CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$sgrCalendario->year());
		

		switch ($viewActive) {
			case 'month':
				$table['tHead'] = CalendarController::head($viewActive,$sgrCalendario);
				$table['tBody'] = View::make('calendario.printBodyMonth')->with('sgrCalendario',$sgrCalendario)->with('id_recurso',$id_recurso)->with('id_grupo','')->with('datatoprint',$datatoprint);		
				break;
			case 'week':
				$horas = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
				$sgrWeek = $sgrCalendario->sgrWeek(strtotime($year.'-'.$month.'-'.$day));
								
				$table['tHead'] = View::make('calendario.print.weekhead')->with('sgrWeek',$sgrWeek);
				//$table['tBody'] = View::make('calendario.printBodyWeek')->with('horas',$horas)->with('sgrWeek',$sgrWeek)->with('id_recurso',$id_recurso)->with('id_grupo','')->with('datatoprint',$datatoprint);		
				$table['tBody'] = (string) View::make('calendario.print.weekbody')->with('sgrCalendario',$sgrCalendario);
				break;
			default:
				# code...
				break;
		}
		
		
		if (0 != $id_recurso) {
			$nombre = Recurso::find($id_recurso)->nombre;
		}	
		else {
			$recurso = Recurso::where('grupo_id','=',$id_grupo)->first();
			$nombre = $recurso->grupo; 
		}	   		
		$html = View::make('pdf.calendario')->with(compact('table','nombre'));
		//return $html;
		$nombreFichero = $day .'-'. $month.'-' . $year .'_'.$nombre;
		//return $html;
		$result = myPDF::getPDF($html,$nombreFichero);
		return Response::make($result)->header('Content-Type', 'application/pdf');
	}

	//Datos de un evento para un validador
	public function ajaxDataEvent(){
		$respuesta = array();
		$diasSemana = array('1'=>'lunes','2'=>'martes','3'=>'miércoles','4'=>'jueves','5'=>'viernes','6'=>'sabado','7'=>'domingo');

		$evento = Evento::where('id','=',Input::get('id'))->groupby('evento_id')->get();
		

		$respuesta['fPeticion'] = date('d \d\e M \d\e Y \a \l\a\s H:i',strtotime($evento[0]->created_at));
		$respuesta['solapamientos'] = false;
		$respuesta['aprobada'] = false;
		if ($evento[0]->estado == 'aprobada'){
				$respuesta['aprobada'] = true;
				$respuesta['estado'] = 'Solicitud aprobada';
			}
		elseif ($evento[0]->estado == 'denegada'){
			$respuesta['estado'] = 'Solicitud denegada';
		}
		else{
			
			if ($evento[0]->solape(strtotime($evento[0]->fechaEvento))){
				$respuesta['solapamientos'] = true;
				$respuesta['estado'] = 'Pendiente de validar con solapamientos';
			}
			else {
				$respuesta['estado'] = 'Pendiente de validar sin solapamientos';
			}
		}
			
		
		
		$respuesta['titulo'] = $evento[0]->titulo;
		$respuesta['actividad'] = $evento[0]->actividad;
		$respuesta['usuario'] = $evento[0]->user->nombre .', ' . $evento[0]->user->apellidos;
		$respuesta['espacio'] = $evento[0]->recurso->nombre;
		setlocale(LC_ALL,'es_ES@euro','es_ES.UTF-8','esp');
		$respuesta['fInicio'] = ucfirst(strftime('%A, %d de %B de %Y',strtotime($evento[0]->fechaInicio)));
		$respuesta['fFin'] = ucfirst(strftime('%A, %d de %B de %Y',strtotime($evento[0]->fechaFin)));
		$respuesta['horario'] = date('g:i',strtotime($evento[0]->horaInicio)) .'-' .date('g:i',strtotime($evento[0]->horaFin));
		
		$dias = explode(',',str_replace(array('[',']','"'), '' , $evento[0]->diasRepeticion));
		$str = '';
		$cont = 0;
		for($j = 0;$j < count($dias) - 1;$j++){
			if (count($dias) == 2)
			$str .= $diasSemana[$dias[$j]] . ' y ';
			else
			$str .= $diasSemana[$dias[$j]] . ', ';
			$cont++;
		}
		$str .= $diasSemana[$dias[$cont]];
		$respuesta['dSemana'] = $str; 
		$respuesta['evento_id'] = $evento[0]->evento_id;
		$respuesta['id_recurso'] = $evento[0]->recurso_id;
		$respuesta['user_id']	= $evento[0]->user_id;
		
		return $respuesta;
	}	

	

}//fin del controlador