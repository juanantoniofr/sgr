<?php

class CalendarController extends BaseController {
	
	//Carga la vista por defecto: Mensual
	public function index(){
		$viewActive = Config::get('options.defaultview'); //vista por defecto
		$fecha = new DateTime();
		$sgrRecurso = RecursoFactory::getRecursoInstance(Config::get('options.defaulttiporecurso'));
		$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);
		$gruposderecursos = GruposController::gruposVisibles(Auth::user()->id);
		$dropdown = Auth::user()->dropdownMenu();
		return View::make('calendario.index')->with('sgrCalendario',$sgrCalendario)->with('viewActive',$viewActive)->nest('sidebar','sidebar',array('tsPrimerLunes' => $sgrCalendario->fecha()->getTimestamp(),'grupos' => $gruposderecursos))->nest('dropdown',$dropdown)->nest('modalDeleteReserva','calendario.modal.deleteEvento')->nest('modalAddReserva','calendario.modal.addEvento')->nest('modalfinalizareserva','calendario.modal.finalizaEvento')->nest('modalanulareserva','calendario.modal.anulaEvento')->nest('modaldescripcion','calendario.modal.descripcion')->nest('modalAtenderReserva','calendario.modal.atenderEvento')->nest('modalMsg','modalMsg');
		
 

	}

	//Ajax functions
	public function calendarAllPuestos(){
		
		//input
		$viewActive = Input::get('viewActive',Config::get('options.defaultview')); //vista por defecto
		$day = Input::get('day',date('d'));
		$month = Input::get('month',date('n'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('id_recurso','');
		
		//Var
		$fecha = new DateTime($year.'-'.$month.'-'.$day);
		$recurso = Recurso::findOrFail($id_recurso);
		$sgrRecurso = RecursoFactory::getRecursoInstance(Config::get('options.espacio'));
		$sgrRecurso->setRecurso($recurso);
		$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);
		$caption = (string) CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$year);
		$head = (string) CalendarController::head($viewActive,$sgrCalendario);
		$body = (string) CalendarController::body($viewActive,$sgrCalendario);
		
		return (string) View::make('calendario.calendar')->with(compact('caption','head','body'));
    
	}

	//Ajax functions
	public function calendar(){
		
		//input
		$viewActive = Input::get('viewActive',Config::get('options.defaultview')); //vista por defecto
		$day = Input::get('day',date('d'));
		$month = Input::get('month',date('n'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('id_recurso','');
		$id_grupo = Input::get('groupID','');
		//$id_puesto = Input::get('id_puesto','');
		/*
		valores posibles para $id_recurso
		empty => error, valor no esperado
		0 		=> tipo debe ser equipo o espacio con puestos
		int 	=> identificador de un espacio // equipo // puesto

		*/

		//Var
		$fecha = new DateTime($year.'-'.$month.'-'.$day);
		$tipo = Config::get('options.defaulttiporecurso');
		$recurso = new Recurso;
		if (!empty($id_recurso) && Recurso::where('id','=',$id_recurso)->count() > 0) {
			$tipo = Recurso::findOrFail($id_recurso)->tipo;
			$recurso = Recurso::findOrFail($id_recurso);
		}

		$sgrRecurso = RecursoFactory::getRecursoInstance($tipo);
		$sgrRecurso->setRecurso($recurso);
		$sgrCalendario = new sgrCalendario($fecha,$sgrRecurso);
		$caption = (string) CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$year);
		$head = (string) CalendarController::head($viewActive,$sgrCalendario);
		$body = (string) CalendarController::body($viewActive,$sgrCalendario);
		
		return (string) View::make('calendario.calendar')->with(compact('caption','head','body'));
    
	}

	public static function head($viewActive,$sgrCalendario){
		switch ($viewActive) {
			case 'month':
				return (string) View::make('calendario.headMonth');
				break;
			
			case 'week':
				$sgrWeek = $sgrCalendario->sgrWeek();
				return (string) View::make('calendario.headWeek')->with('sgrWeek',$sgrWeek);
				break;
		}
	}

	public static function caption($viewActive,$day,$nombreMes,$year){
		
		return (string) View::make('calendario.caption')->with('view',$viewActive)->with('day',$day)->with('nombreMes',$nombreMes)->with('year',$year);
	}

	public static function body($viewActive,$sgrCalendario){
		
		$diaActual = 1;
		$j=1;
		
		switch ($viewActive) {
			
			case 'month':
				return (string) View::make('calendario.bodyMonth')->with('sgrCalendario',$sgrCalendario);//->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo);
				break;
			
			case 'week':
				$sgrWeek = $sgrCalendario->sgrWeek();
				$horarioApertura = Config::get('options.horarioApertura');	
				return (string) View::make('calendario.bodyWeek')->with('horarioApertura',$horarioApertura)->with('sgrWeek',$sgrWeek)->with('sgrCalendario',$sgrCalendario);	
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
		
		//get Input or default
		$viewActive = Input::get('view','month');
		$day = Input::get('day',date('d'));
		$month = Input::get('month',date('m'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('idRecurso','');
		$id_grupo = Input::get('groupID','');
		$titulo = Input::get('titulo',false);
		$nombre = Input::get('nombre',false);
		$colectivo = Input::get('colectivo',false);
		$total = Input::get('total',false);//Total de puestos o equipos de una reserva
		$datatoprint = array('titulo' => $titulo,'nombre' => $nombre,'colectivo' => $colectivo,'total' => $total);//Información a imprimir seleccionada por el usuario
		
		//Output
		$table = array( 'tCaption'	=> '',
						'tHead' 	=> '',
						'tBody'		=> '');

		$sgrCalendario = new sgrCalendario($month,$year);


		$table['tCaption'] = CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$sgrCalendario->year());
		$table['tHead'] = CalendarController::head($viewActive,$day,$month,$year);

		switch ($viewActive) {
			case 'month':
				//$diaActual = 1;
				//$j=1;
				//$nameMonth = $sgrCalendario->nombreMes();//representación textual del mes (enero,febrero.... etc)
				//$days = $sgrCalendario->dias();
				//$diaSemanaPimerDiaMes = date('N',$days[1]->timestamp());
				//$table['tBody'] = View::make('calendario.printBodyMonth')->with('sgrCalendario',$sgrCalendario)->with('mon',$month)->with('year',$year)->with('diaActual',$diaActual)->with('j',$j)->with('diaSemanaPimerDiaMes',$diaSemanaPimerDiaMes)->with('days',$days)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('datatoprint',$datatoprint);
				$table['tBody'] = View::make('calendario.printBodyMonth')->with('sgrCalendario',$sgrCalendario)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('datatoprint',$datatoprint);		
				break;
			case 'week':
				$horas = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
				$sgrWeek = new sgrWeek($day,$month,$year);

				$table['tBody'] = View::make('calendario.printBodyWeek')->with('horas',$horas)->with('sgrWeek',$sgrWeek)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('datatoprint',$datatoprint);	
					
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