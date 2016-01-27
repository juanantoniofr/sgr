<?php

class CalendarController extends BaseController {
	
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


		$table['tCaption'] = CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$sgrCalendario->getYear());
		$table['tHead'] = CalendarController::head($viewActive,$day,$month,$year);

		switch ($viewActive) {
			case 'month':
				$diaActual = 1;
				$j=1;
				$nameMonth = $sgrCalendario->nombreMes();//representación textual del mes (enero,febrero.... etc)
				$days = $sgrCalendario->dias();
				$diaSemanaPimerDiaMes = date('N',$days[1]->timestamp());
				$table['tBody'] = View::make('calendario.printBodyMonth')->with('sgrCalendario',$sgrCalendario)->with('mon',$month)->with('year',$year)->with('diaActual',$diaActual)->with('j',$j)->with('diaSemanaPimerDiaMes',$diaSemanaPimerDiaMes)->with('days',$days)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo)->with('datatoprint',$datatoprint);		
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
		$respuesta['usuario'] = $evento[0]->userOwn->nombre .', ' . $evento[0]->userOwn->apellidos;
		$respuesta['espacio'] = $evento[0]->recursoOwn->nombre;
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

	//Carga la vista por defecto: Mensual
	public function showCalendarViewMonth(){
		
		$viewActive = Input::get('view','month'); //vista por defecto
		$uvus = Input::get('uvus','');
		$msg = '';
		$nh = Auth::user()->numHorasReservadas();//Número de horas reservadas

			
		$day = Input::get('day',date('d'));
		$numMonth = Input::get('numMonth',date('n'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('id_recurso','');
		$id_grupo = Input::get('groupID','');
		$tsPrimerLunes = sgrCalendario::fristMonday();//timestamp primer lunes reservable...
		$datefirstmonday = getdate($tsPrimerLunes);
		$sgrCalendario = new sgrCalendario(1,2016);
			
		//Los usuarios del rol "alumnos" sólo pueden reservar 12 horas a la semana como máximo
		if (Auth::user()->isUser() && $nh >=12 ){
			$msg = 'Has completado el número máximo de horas que puede reservar (' . Config::get('options.max_horas').' horas a la semana )'; 
		}	
		
		$tHead = CalendarController::head($viewActive,$day,$numMonth,$year);
		$tBody = CalendarController::body($viewActive,$day,$numMonth,$year,$id_recurso,$id_grupo);
		
		
		$gruposderecursos = Auth::user()->gruposRecursos();		
		$recursos = array();//No hay recurso seleccionado la primera vez
		$dropdown = Auth::user()->dropdownMenu();
		//se devuelve la vista calendario.
		return View::make('Calendarios')->with('tsPrimerLunes',$tsPrimerLunes)->with('day',$day)->with('numMonth',$numMonth)->with('year',$year)->with('tHead',$tHead)->with('tBody',$tBody)->with('nh',$nh)->with('viewActive',$viewActive)->with('uvusUser',$uvus)->nest('sidebar','sidebar',array('tsPrimerLunes' => $tsPrimerLunes,'msg' => $msg,'grupos' => $gruposderecursos,'recursos' => $recursos))->nest('dropdown',$dropdown)->nest('modaldescripcion','modaldescripcion')->nest('modalAddReserva','modalAddReserva')->nest('modalDeleteReserva','modalDeleteReserva')->nest('modalfinalizareserva','modalfinalizareserva')->nest('viewCaption','calendario.caption',array('view'=>$viewActive,'day' => $day,'nombreMes' => $sgrCalendario->nombreMes(),'year' => $sgrCalendario->getYear()))->nest('viewHead','calendario.headMonth');
	}
	//Ajax functions
	public function getTablebyajax(){
		
		$table = array( 'tHead' => '',
						'tBody' => '');
		
		$viewActive = Input::get('viewActive','month'); //vista por defecto
		$day = Input::get('day',date('d'));
		$numMonth = Input::get('month',date('n'));
		$year = Input::get('year',date('Y'));
		$id_recurso = Input::get('id_recurso','');
		$id_grupo = Input::get('groupID','');

       	$sgrCalendario = new sgrCalendario($numMonth,$year);

       	$table['tHead'] = CalendarController::head($viewActive,$day,$numMonth,$year);
		$table['tBody'] = CalendarController::body($viewActive,$day,$numMonth,$year,$id_recurso,$id_grupo);
		$table['tCaption'] = CalendarController::caption($viewActive,$day,$sgrCalendario->nombreMes(),$sgrCalendario->getYear());
				
	    return $table;
	}

	public static function body($viewActive,$day,$numMonth,$year,$id_recurso,$id_grupo){
		
		$sgrCalendario = new sgrCalendario($numMonth,$year);
		$nameMonth = $sgrCalendario->nombreMes();//representación textual del mes (enero,febrero.... etc)
		//$days = $sgrCalendario->dias();
		//$diaSemanaPimerDiaMes = date('N',$days[1]->timestamp());//Representación numérica ISO-8601 del día de la semana 1 lunes,.7 domingo
		$diaActual = 1;
		$j=1;
		switch ($viewActive) {
			case 'year':
				return '<p>Aún en desarrollo....</p>';
				break;
			case 'month':
				return (string) View::make('calendario.bodyMonth')->with('sgrCalendario',$sgrCalendario)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo);
				break;
			case 'week':
				$horas = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
					//$sgrWeek = new sgrWeek($day,$numMonth,$year);
					$sgrWeek = $sgrCalendario->sgrWeek(strtotime($year.'-'.$numMonth.'-'.$day));
				return (string) View::make('calendario.bodyWeek')->with('horas',$horas)->with('sgrWeek',$sgrWeek)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo);	
				break;
			case 'day':
				return '<p>Aún en desarrollo.....</p>';	
				break;
			case 'agenda':
				$maxEventsByPage = 10;
				$startDate = date('Y-m-d',strtotime($year . '-' . $numMonth .'-'. $day));
				$events = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','>=',$startDate)->orderby('fechaEvento','Asc')->orderby('horaInicio','Asc')->orderby('titulo','Asc')->paginate($maxEventsByPage);
		
				return (string) View::make('calendario.bodyAgenda')->with('events',$events);
				break;
			default:
				$table['tBody'] = 'Error al generar vista...';
				break;	
		}
	}

	public static function head($viewActive,$day,$numMonth,$year){
		
		switch ($viewActive) {
			case 'month':
				return (string) View::make('calendario.headMonth');
				break;
			
			case 'week':
				$timefirstMonday = sgrDate::timestamplunesanterior($day,$numMonth,$year);
				$numOfMonday = date('j',$timefirstMonday); //Número del mes 1-31
				for($i=0;$i<7;$i++){
					$time = strtotime('+'.$i.' day',$timefirstMonday);	
					$text[$i] = sgrDate::abrDiaSemana($time) . ', '.strftime('%d/%b',$time);
				}
				return (string) View::make('calendario.headWeek')->with('view',$viewActive)->with('text',$text);
				break;
		}
	}

	public static function caption($viewActive,$day,$nombreMes,$year){

		return (string) View::make('calendario.caption')->with('view',$viewActive)->with('day',$day)->with('nombreMes',$nombreMes)->with('year',$year);
	}

}//fin del controlador