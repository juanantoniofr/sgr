<?php

class CalendarController extends BaseController {
	

	public function imprime(){
		
		$currentDay = Date::currentDay();
		$currentMonth = Date::currentMonth();
		$currentYear = Date::currentYear();
		
		$viewActive = Input::get('view','month');
		$day = Input::get('day',$currentDay);
		$month = Input::get('month',$currentMonth);
		$year = Input::get('year',$currentYear);
		$id_recurso = Input::get('idRecurso','');
		$grupo_id = Input::get('groupID','');

		$titulo = Input::get('titulo',false);
		$nombre = Input::get('nombre',false);
		$colectivo = Input::get('colectivo',false);
		$total = Input::get('total',false);
		$data = array('titulo' => $titulo,'nombre' => $nombre,'colectivo' => $colectivo,'total' => $total);
		
		$table = array( 'tHead' => '',
						'tBody' => '');
		
		switch ($viewActive) {
			case 'year':
				$table['tBody'] = '<p>Aún en desarrollo....</p>';
				break;
			case 'month':
				$table['tCaption'] = Calendar::getCaption($day,$month,$year);
				$table['tHead'] = Calendar::getPrintHead('month',$day,$month,$year);
				$table['tBody'] = Calendar::getPrintBodytableMonth($data,$month,$year,$id_recurso);	
				break;
			case 'week':
				$table['tCaption'] = Calendar::getCaption($day,$month,$year);
			  	$table['tHead'] = Calendar::getPrintHead('week',$day,$month,$year);
				$table['tBody']= Calendar::getPrintBodytableWeek($data,$day,$month,$year,$id_recurso);
				break;
			case 'day':
				$table['tBody'] = '<p>Aún en desarrollo.....</p>';	
				break;
			case 'agenda':
				$table['tCaption'] = Calendar::getCaption($day,$month,$year);
				//$table['tHead'] = Calendar::gettHead('agenda',$input['day'],$input['month'],$input['year']);
				$table['tBody'] = Calendar::getBodytableAgenda($day,$month,$year);
				break;
			default:
				$table['tBody'] = 'Error al generar vista...';
				break;
		}

		if (0 != $id_recurso){
			$recurso = Recurso::find($id_recurso);	
			$nombre = $recurso->nombre;	
		} 
		else {
			$recurso = Recurso::where('grupo_id','=',$grupo_id)->first();
			$nombre = 'Todos los puestos o equipos de ' . $recurso->grupo; 
		}	   		
		$html = View::make('pdf.calendario')->with(compact('table','nombre'));

		$nombreFichero = $day .'-'. $month.'-' . $year .'_'.$recurso->nombre;
		$result = myPDF::getPDF($html,$nombreFichero);
		//return $html;
   		return Response::make($result)->header('Content-Type', 'application/pdf');
	}


	//Devuelve el campo descripción dado un id_recurso
	public function getDescripcion(){

		$idRecurso = Input::get('idrecurso','');
		if (empty($idRecurso)) return '-1';

		$descripcion = '';
		$recurso = Recurso::find($idRecurso);
		$descripcion = $recurso->descripcion; //descripción del elemento
		
		if (empty($descripcion)) $descripcion = $recurso->descripcionGrupo; //descripción general de todos los espacios,equipos o puestos del grupo
		
		return $descripcion;
	}	

	
	
	/*public function getDataEvent(){

		$fechaEvento = Input::get('fechaEvento','');
		$idEvento = Input::get('idEvento','');
		$idSerie = Input::get('idSerie','');

		$event = Evento::where('id','=',$idEvento)->where('fechaEvento','=',$fechaEvento)->first();

		return $event;
	}*/

	

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
			if (Calendar::hasSolapamientos($evento[0]->evento_id,$evento[0]->recurso_id)){
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

	//Se carga la vista por defecto: Mensual
	public function showCalendarViewMonth(){
		
		$input = Input::all();
		$day = Input::get('day',date('d'));
		$numMonth = Input::get('numMonth',date('m'));
		$year = Input::get('year',date('Y'));
		$uvus = INput::get('uvus','');

		//Los usuarios del rol "alumnos" sólo pueden reservar 12 horas a la semana como máximo
		$nh = Auth::user()->numHorasReservadas();
		$msg = '';
		if (Auth::user()->isUser() && $nh >=12 ){
			$msg = 'Has completado el número máximo de horas que puede reservar (' . Config::get('options.max_horas').' horas a la semana )'; 
		}	

		//Calendar::fristMonday() -> devuelve el timestamp del primer lunes disponible para reserva
		$tsPrimerLunes = Calendar::fristMonday();
		if(empty($input)){
			$datefirstmonday = getdate($tsPrimerLunes);
			$numMonth = $datefirstmonday['mon'];//Representación númerica del mes del 1 al 12
			$year = $datefirstmonday['year']; //Representación numérica del año cuatro dígitos
			$nameMonth = Date::getNameMonth($numMonth,$year); //representación textual del mes (enero,febrero.... etc)
			$day = $datefirstmonday['mday']; //Representación númerica del dia del mes: 1 - 31	
		} 
		//else -> los métodos getCaption, getHead y getBodytableMonth optiene los valores de fecha directamente desde el array de entrada post.
		
		$viewActive = 'month'; //vista por defecto
		$tCaption = Calendar::getCaption($day,$numMonth,$year);
		$tHead = Calendar::gettHead($viewActive,$day,$numMonth,$year);
		$tBody = Calendar::getBodytableMonth($numMonth,$year);
		
		//Se obtinen todos los grupos de recursos
		//$grupos = DB::table('recursos')->select('id', 'acl', 'grupo','grupo_id')->groupby('grupo')->get();
		$grupos = Recurso::groupby('grupo')->get();
		//se filtran para obtener sólo aquellos con acceso para el usuario logeado
		$groupWithAccess = array();
		foreach ($grupos as $grupo) {
			if ($grupo->visible())
				$groupWithAccess[] = $grupo;
		}
		
		
		$dropdown = Auth::user()->dropdownMenu();
		//se devuelve la vista calendario.
		return View::make('Calendarios')->with('tsPrimerLunes',$tsPrimerLunes)->with('day',$day)->with('numMonth',$numMonth)->with('year',$year)->with('tCaption',$tCaption)->with('tHead',$tHead)->with('tBody',$tBody)->with('nh',$nh)->with('viewActive',$viewActive)->with('uvusUser',$uvus)->nest('sidebar','sidebar',array('tsPrimerLunes' => $tsPrimerLunes,'msg' => $msg,'grupos' => $groupWithAccess))->nest('dropdown',$dropdown)->nest('modaldescripcion','modaldescripcion')->nest('modalAddReserva','modalAddReserva')->nest('modalDeleteReserva','modalDeleteReserva')->nest('modalfinalizareserva','modalfinalizareserva');
	}

	//Ajax functions
	public function getTablebyajax(){
	
		$input = Input::all();
		
		$table = array( 'tHead' => '',
						'tBody' => '');
		
       	//$input['month'],$input['year'],$input['viewActive']
		switch ($input['viewActive']) {
			case 'year':
				$table['tBody'] = '<p>Aún en desarrollo....</p>';
				break;
			case 'month':
				$table['tCaption'] = Calendar::getCaption($input['day'],$input['month'],$input['year']);
				$table['tHead'] = Calendar::gettHead('month',$input['day'],$input['month'],$input['year']);
				$table['tBody'] = Calendar::getBodytableMonth($input['month'],$input['year'],$input['id_recurso']);	
				break;
			case 'week':
				$table['tCaption'] = Calendar::getCaption($input['day'],$input['month'],$input['year']);
			  	$table['tHead'] = Calendar::gettHead('week',$input['day'],$input['month'],$input['year']);
				$table['tBody']= Calendar::getBodytableWeek($input['day'],$input['month'],$input['year'],$input['id_recurso']);
				break;
			case 'day':
				$table['tBody'] = '<p>Aún en desarrollo.....</p>';	
				break;
			case 'agenda':
				$table['tCaption'] = Calendar::getCaption($input['day'],$input['month'],$input['year']);
				//$table['tHead'] = Calendar::gettHead('agenda',$input['day'],$input['month'],$input['year']);
				$table['tBody'] = Calendar::getBodytableAgenda($input['day'],$input['month'],$input['year']);
				break;
			default:
				$table['tBody'] = 'Error al generar vista...';
				break;
		}
	    return $table;
	}

	
	
	
	public function getRecursosByAjax(){
		
		$html = '';

		$grupo = Input::get('groupID','');
		$recursos = Recurso::where('grupo_id','=',$grupo)->get();
		$selected = 'selected';
		$itemsdisabled = 0;
		foreach ($recursos as $recurso) {

			//Falta: if puede reservar => seguimos
			$html .= '<option '.$selected.' value="'.$recurso->id.'" data-disabled="'.$recurso->disabled.'">'.$recurso->nombre;
			if ($recurso->disabled) {
				$itemsdisabled++;
				$html .= ' (Deshabilitado)';
			}	
			$html .='</option>';
			$selected = '';
			}	
		if (!Auth::user()->isUser() && $recursos[0]->tipo != 'espacio'){
			$disabled = 0;
			if ($itemsdisabled == $recursos->count() ) $disabled = 1;
			$html .= '<option '.$selected.' value="0" data-disabled="'.$disabled.'">Todos los '.$recursos[0]->tipo.'s</option>';
		}
		//$html .= '<option  value="546546">'.$grupo.'</option>';
		return $html;
	}

	//Auxiliares
	/*
	*/
	/*private function updateDias($oldIdSerie = '',$newIdSerie = ''){
		
		//$oldIdSerie = Input::get('idSerie');
		if (!empty($oldIdSerie)){//isset(Input::get('idSerie'))){
		 	
			$events = Evento::select('dia')->where('evento_id','=',$oldIdSerie)->groupby('dia')->get();
			if(count($events) > 0){
				foreach ($events as $event)	$aDias[] = $event->dia;
				Evento::where('evento_id','=',$oldIdSerie)->update(array('diasRepeticion' => json_encode($aDias)));
			}
		}

		if (!empty($newIdSerie)){
			$events = Evento::select('dia')->where('evento_id','=',$newIdSerie)->groupby('dia')->get();
			foreach ($events as $event)	$aDias2[] = $event->dia;
			Evento::where('evento_id','=',$newIdSerie)->update(array('diasRepeticion' => json_encode($aDias2)));
		}
	}*/
/*
	private function updatePeriocidad($newIdSerie = '',$oldIdSerie = ''){
		
		
		if (!empty($oldIdSerie)){
			$oldIdSerie = Input::get('idSerie');
			$numEvents = Evento::where('evento_id','=',$oldIdSerie)->count();
			if ($numEvents == 1) Evento::where('evento_id','=',$oldIdSerie)->update(array('repeticion' => 0));
		}
		
		if(!empty($newIdSerie)){
			$numEvents = Evento::where('evento_id','=',$newIdSerie)->count();
			if ($numEvents == 1) Evento::where('evento_id','=',$newIdSerie)->update(array('repeticion' => 0));
		}
	}

	private function updateFInicio($newIdSerie = '',$oldIdSerie = ''){
		
		if (!empty($oldIdSerie)){
			$fechaPrimerEvento = Evento::where('evento_id','=',$oldIdSerie)->min('fechaEvento');
			if (!empty($fechaPrimerEvento)){
				Evento::where('evento_id','=',$oldIdSerie)->update(array('fechaInicio' => $fechaPrimerEvento));
			}
		}
			
		if (!empty($newIdSerie)){
			$fechaPrimerEvento = Evento::where('evento_id','=',$newIdSerie)->min('fechaEvento');
			if (!empty($fechaPrimerEvento)){
				Evento::where('evento_id','=',$newIdSerie)->update(array('fechaInicio' => $fechaPrimerEvento));
			}
		}
	}

	private function updateFfin($newIdSerie = '',$oldIdSerie = ''){
		
		if (!empty($oldIdSerie)){
			$fechaUltimoEvento = Evento::where('evento_id','=',$oldIdSerie)->max('fechaEvento');
			if (!empty($fechaUltimoEvento)){
				Evento::where('evento_id','=',$oldIdSerie)->update(array('fechaFin' => $fechaUltimoEvento));
			}
		}
		
		if (!empty($newIdSerie)){
			$fechaUltimoEvento = Evento::where('evento_id','=',$newIdSerie)->max('fechaEvento');
			if (!empty($fechaUltimoEvento)){
				Evento::where('evento_id','=',$newIdSerie)->update(array('fechaFin' => $fechaUltimoEvento));
			}
		}

	}
*/	

	
	

	
}//fin del controlador