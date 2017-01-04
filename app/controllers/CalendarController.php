<?php
/* marca branch master2 */
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

	//Buscar eventos por uvus
	public function search(){

		
		$username = Input::get('username','');

		if(empty($username)) return '-1';

						
		$user = User::where('username','=',$username)->first();
		$today = date('Y-m-d');
		if (empty($user)) return '1';//false
		
		$events = Evento::where('user_id','=',$user->id)->where('fechaFin','>=',$today)->groupby('evento_id')->orderby('fechaEvento','asc')->orderby('recurso_id','asc')->get();
		return View::make('tecnico.resultSearch',compact('events','username'));
	}
	
	public function getDataEvent(){

		$fechaEvento = Input::get('fechaEvento','');
		$idEvento = Input::get('idEvento','');
		$idSerie = Input::get('idSerie','');

		$event = Evento::where('id','=',$idEvento)->where('fechaEvento','=',$fechaEvento)->first();

		return $event;
	}

	public function saveAtencion(){
		
		$idEvento = Input::get('eventoid','');
		if (empty($idEvento)) return 'error';

		$evento = Evento::find($idEvento);
		$atencionEvento = new AtencionEvento;
		if (isset($evento->atencion->id)) $atencionEvento = AtencionEvento::find($evento->atencion->id);
		
		
		
		$atencionEvento->evento_idSerie = $evento->evento_id;
		$atencionEvento->evento_id = $evento->id;
		$atencionEvento->user_id = $evento->userOwn->id;
		$atencionEvento->tecnico_id = Auth::user()->id;
		$atencionEvento->momento = date('Y-m-d H:i:s', time());//momento actual
		$atencionEvento->observaciones = Input::get('observaciones','');
		
		
		$atencionEvento->save();
		$evento->atendida = true;
		$evento->save();		
		return 'success';
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
		$nh = ACL::numHorasReservadas();
		$msg = '';
		if (ACL::isUser() && $nh >=12 ){
			$msg = 'Has completado el número máximo de horas que puede reservar (' . Config::get('options.max_horas').' horas a la semana )'; 
		}	

				
		if(empty($input)){
			//ACL::fristMonday() -> devuelve el timestamp del primer lunes disponible para reserva
			
			$datefirstmonday = getdate(ACL::fristMonday());
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
		$grupos = DB::table('recursos')->select('id', 'acl', 'grupo','grupo_id')->groupby('grupo')->get();
		
		//se filtran para obtener sólo aquellos con acceso para el usuario logeado
		$groupWithAccess = array();
		foreach ($grupos as $grupo) {
			if (ACL::canReservation($grupo->id,$grupo->acl))
				$groupWithAccess[] = $grupo;
		}
		
		
		$dropdown = Auth::user()->dropdownMenu();		
		//se devuelve la vista calendario.
		return View::make('Calendarios')->with('day',$day)->with('numMonth',$numMonth)->with('year',$year)->with('tCaption',$tCaption)->with('tHead',$tHead)->with('tBody',$tBody)->with('nh',$nh)->with('viewActive',$viewActive)->with('uvusUser',$uvus)->nest('sidebar','sidebar',array('msg' => $msg,'grupos' => $groupWithAccess))->nest('dropdown',$dropdown)->nest('modaldescripcion','modaldescripcion')->nest('modalMsg','modalMsg');
	}

	//Ajax functions
	public function getTablebyajax(){
	
		$input = Input::all();
		
		$table = array( 'tHead' 	=> '',
						'tBody' 	=> '',
						'disabled' 	=> array('title' => '',
                                             'msg'   => 'Espacio Deshabilitado temporalmente.....<br />No es posible añadir nuevas reservas.'),
						);
		
       	
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

		if ($input['id_recurso'] != 0){
            $recurso = Recurso::findOrFail($input['id_recurso']);
            if (1 == $recurso->disabled){
                $table['disabled']['title'] = $recurso->nombre . ' deshabilitado temporalmente';
               	if ( $recurso->id == 10 ) $table['disabled']['msg'] = '<p class="text-center"><b>Vicedecanato de Desarrollo de Proyectos e Infraestructuras</b></p><p>Nos hemos visto en la necesidad de acometer reformas integrales importantes (cableado de sonido y video, equipos, sistema eléctrico, mesas de mezcla, cambio de matriz) en el Salón de Actos, después de doce años de actividad ininterrumpida y de los cambios tecnológicos que se han ido produciendo. El sistema de distribución de señales ha dejado de funcionar y el paso del sistema analógico obsoleto al digital requiere una inversión importante, además de un cierre de este espacio que va a durar previsiblemente sobre dos meses.</p>';
            }
        } 
	    return $table;
	}

	public function geteventbyajax(){

		//$evento_id = Input::get('evento_id');
		$eventos = Evento::where('evento_id','=',Input::get('evento_id'))->get();
		return $eventos;
	}

	public function getajaxeventbyId(){
		//$evento_id = Input::get('evento_id');
		$event = Evento::where('id','=',Input::get('id'))->get();
		return $event;
	}
	
	public function getRecursosByAjax(){
		
		$html = '';

		$grupo = Input::get('groupID','');
		$recursos = Recurso::where('grupo_id','=',$grupo)->get();
		$selected = 'selected';
		$itemsdisabled = 0;
		foreach ($recursos as $recurso) {

			$showdisabled = $recurso->disabled;
			if ( Auth::user()->isValidador() ) $showdisabled = 0;
			//Falta: if puede reservar => seguimos
			$html .= '<option '.$selected.' value="'.$recurso->id.'" data-disabled="'.$showdisabled.'">'.$recurso->nombre;
			if ($recurso->disabled) {
				$itemsdisabled++;
				$html .= ' (Deshabilitado)';
			}	
			$html .='</option>';
			$selected = '';
			}	
		if (!ACL::isUser() && $recursos[0]->tipo != 'espacio'){
			$disabled = 0;
			if ($itemsdisabled == $recursos->count() ) $disabled = 1;
			$html .= '<option '.$selected.' value="0" data-disabled="'.$disabled.'">Todos los '.$recursos[0]->tipo.'s</option>';
		}
		//$html .= '<option  value="546546">'.$grupo.'</option>';
		return $html;
	}

	//del
	public function delEventbyajax(){

		$result = '';

		$result = $this->delEvents();
		return $result;
	} 
	
	private function delEvents(){
		$result = '';
		$eventToDel = Evento::find(Input::get('idEvento'))->first();
		//$actions = Config::get('options.required_mail');
		//cMail::sendMail($actions['del'],$eventToDel->recursoOwn->id,$eventToDel->evento_id);
		

		$event = Evento::find(Input::get('idEvento'));
		if (Input::get('id_recurso') == 0){
			Evento::where('evento_id','=',Input::get('idSerie'))->delete();
		}
		else {
			Evento::where('evento_id','=',Input::get('idSerie'))->where('recurso_id','=',Input::get('id_recurso'))->delete();
		}
		
		
		return $result;
		
	}

	//Save
	public function eventsavebyajax(){

		$result = array('error' => false,
						'ids' => array(),
						'idsSolapamientos' => array(),
						'msgErrors' => array(),
						'msgSuccess' => '');
		$testDataForm = new Evento();
		

		
		if(!$testDataForm->validate(Input::all())){
			$result['error'] = true;
			$result['msgErrors'] = $testDataForm->errors();
		}
		else {
			$result['idEvents'] = $this->saveEvents(Input::all());

			//Msg confirmación al usuario (add reserva)
			$event = Evento::Where('evento_id','=',$result['idEvents'])->first();
			if ($event->estado == 'aprobada'){
				$result['msgSuccess'] = '<strong class="alert alert-info" > Reserva registrada con éxito. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $result['idEvents'])).'">imprimir comprobante</a> de la misma si lo desea.</strong>';

			}
			if ($event->estado == 'pendiente'){
				$result['msgSuccess'] = '<strong class="alert alert-danger" >Reserva pendiente de validación. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $result['idEvents'])).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
			}

			//notificar a validadores si espacio requiere validación
			if (!ACL::automaticAuthorization($event->recursoOwn->id)){
				$sgrMail = new sgrMail();
				$sgrMail->notificaNuevoEvento($event);
			}

		}
		
		

		return $result;
		
	}

	private function saveEvents($data){
		
		$dias = $data['dias']; //1->lunes...., 5->viernes
		$respuesta = array();
		$evento_id = $this->getIdUnique();
	
		foreach ($dias as $dWeek) {
			if ($data['repetir'] == 'SR') $nRepeticiones = 1;
			else $nRepeticiones = Date::numRepeticiones($data['fInicio'],$data['fFin'],$dWeek);
			for($j=0;$j<$nRepeticiones;$j++){
				$startDate = Date::timeStamp_fristDayNextToDate($data['fInicio'],$dWeek);
				$currentfecha = Date::currentFecha($startDate,$j);
				$respuesta[] =$this->saveEvent($data,$currentfecha,$evento_id);
			}
		}
		return $evento_id;
		
	}

	private function saveEvent($data,$currentfecha,$evento_id){

		//Si reservar todos los puestos o equipos
		if ($data['id_recurso'] == 0){
			$recursos = Recurso::where('grupo_id','=',$data['grupo_id'])->get();
			foreach($recursos as $recurso){
				if ($recurso->disabled != 1){
					$id_recurso = $recurso->id;
					$sucess = true;
					$evento = new Evento();
				
					//obtener estado (pendiente|aprobada)
					$hInicio = date('H:i:s',strtotime($data['hInicio']));
					$hFin = date('H:i:s',strtotime($data['hFin']));
					$evento->estado = $this->setEstado($id_recurso,$currentfecha,$hInicio,$hFin);
				
					$repeticion = 1;
					$evento->fechaFin = Date::toDB($data['fFin'],'-');
					$evento->fechaInicio = Date::toDB($data['fInicio'],'-');
					$evento->diasRepeticion = json_encode($data['dias']);
				
					if ($data['repetir'] == 'SR') {
						$repeticion = 0;
						$evento->fechaFin = Date::toDB($currentfecha,'-');
						$evento->fechaInicio = Date::toDB($currentfecha,'-');
						$evento->diasRepeticion = json_encode(array(date('N',Date::getTimeStamp($currentfecha))));
					}
				
					$evento->evento_id = $evento_id;
					$evento->titulo = $data['titulo'];
					$evento->actividad = $data['actividad'];
					$evento->recurso_id = $id_recurso;
					$evento->fechaEvento = Date::toDB($currentfecha,'-');
					$evento->repeticion = $repeticion;
					$evento->dia = date('N',Date::getTimeStamp($currentfecha));
					$evento->horaInicio = $data['hInicio'];
					$evento->horaFin = $data['hFin'];
					$evento->reservadoPor = Auth::user()->id;//Persona que reserva
					
					//Propietaria de la reserva
					$evento->user_id = Auth::user()->id;//Puede ser la persona que reserva
					
					//U otro usuario
					$uvus = Input::get('reservarParaUvus','');
					if (!empty($uvus)) {
						$user = User::where('username','=',$uvus)->first();
						if ($user->count() > 0) $evento->user_id = $user->id;
					}
					
					if ($evento->save()) $result = $evento->id;
				}
			}
		}
		//reserva de un solo puesto o equipo
		else{
			$sucess = true;
			$evento = new Evento();
			
			//obtener estado (pendiente|aprobada)
			$hInicio = date('H:i:s',strtotime($data['hInicio']));
			$hFin = date('H:i:s',strtotime($data['hFin']));
			$evento->estado = $this->setEstado($data['id_recurso'],$currentfecha,$hInicio,$hFin);
			

			
			$repeticion = 1;
			$evento->fechaFin = Date::toDB($data['fFin'],'-');
			$evento->fechaInicio = Date::toDB($data['fInicio'],'-');
			$evento->diasRepeticion = json_encode($data['dias']);
			
			if ($data['repetir'] == 'SR') {
				$repeticion = 0;
				$evento->fechaFin = Date::toDB($currentfecha,'-');
				$evento->fechaInicio = Date::toDB($currentfecha,'-');
				$evento->diasRepeticion = json_encode(array(date('N',Date::getTimeStamp($currentfecha))));
			}
			
			$evento->evento_id = $evento_id;
			$evento->titulo = $data['titulo'];
			$evento->actividad = $data['actividad'];
			$evento->recurso_id = $data['id_recurso'];
			$evento->fechaEvento = Date::toDB($currentfecha,'-');
			$evento->repeticion = $repeticion;
			$evento->dia = date('N',Date::getTimeStamp($currentfecha));
			$evento->horaInicio = $data['hInicio'];
			$evento->horaFin = $data['hFin'];
			$evento->reservadoPor = Auth::user()->id;//Persona que reserva

					
			//Propietaria de la reserva:
			//  --> Puede ser la persona que reserva
			$evento->user_id = Auth::user()->id;
				
			//  --> U otro usuario
			$uvus = Input::get('reservarParaUvus','');
			if (!empty($uvus)) {
				$user = User::where('username','=',$uvus)->first();
				if ($user->count() > 0) $evento->user_id = $user->id;
			}
				
			if ($evento->save()) $result = $evento->id;
		
		}
		return $result;
	}


	//Edit
	public function editEventbyajax(){

		$result = array('error' => false,
						'msgSuccess' => '',
						'idsDeleted' => array(),
						'msgErrors' => array());
		//Controlar errores en el formulario
		$testDataForm = new Evento();
		if(!$testDataForm->validate(Input::all())){
				$result['error'] = true;
				$result['msgErrors'] = $testDataForm->errors();
			}
		//Si no hay errores
		else{
			
			//si el usuario es alumno: comprobamos req2 (MAX HORAS = 12 a la semana en cualquier espacio o medio )	
			if (ACL::isUser() && $this->superaHoras()){
				$result['error'] = true;
				$error = array('hFin' =>'Se supera el máximo de horas a la semana.. (12h)');	
				$result['msgErrors'] = $error;	
			}
			else {
				
				$idSerie = Input::get('idSerie');

				
				$fechaInicio = Input::get('fInicio');
				$fechaFin = Input::get('fFin');
				//Borrar todos los eventos a modificar
				$event = Evento::find(Input::get('idEvento'));
				if (Input::get('id_recurso') == 0){
					Evento::where('evento_id','=',Input::get('idSerie'))->delete();
				}
				else {
					Evento::where('evento_id','=',Input::get('idSerie'))->where('recurso_id','=',Input::get('id_recurso'))->delete();
				}
				//Añadir los nuevos
				$result['idEvents'] = $this->editEvents($fechaInicio,$fechaFin,$idSerie);

				//Msg confirmación al usuario (edición de evento)
				$newEvent = Evento::Where('evento_id','=',$idSerie)->first();
				if ($newEvent->estado == 'aprobada') $result['msgSuccess'] = '<strong class="alert alert-info" > Reserva registrada con éxito. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $newEvent->evento_id)).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
				if ($newEvent->estado == 'pendiente')
					$result['msgSuccess'] = '<strong class="alert alert-danger" >Reserva pendiente de validación. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $newEvent->evento_id)).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
				
				//notificar a validadores si espacio requiere validación
				if (!ACL::automaticAuthorization($event->recursoOwn->id)){
					$sgrMail = new sgrMail();
					$sgrMail->notificaEdicionEvento($newEvent);
				}
				

			} //fin else	
		}
		
		return $result;			
	} 
		
	private function editEvents($fechaInicio,$fechaFin,$idSerie){
		
		$result = '';
		
		$repetir = Input::get('repetir');	
		$dias = Input::get('dias'); //1->lunes...., 5->viernes
		if ($repetir == 'SR') { //SR == sin repetición (no periódico)
			$dias = array(Date::getDayWeek($fechaInicio));
			$fechaFin = $fechaInicio;
		}
							
		foreach ($dias as $dWeek) {
							
			if (Input::get('repetir') == 'SR') $nRepeticiones = 1;
			else { $nRepeticiones = Date::numRepeticiones($fechaInicio,$fechaFin,$dWeek);}
							
			for($j=0;$j<$nRepeticiones;$j++){
				$startDate = Date::timeStamp_fristDayNextToDate($fechaInicio,$dWeek);
				$currentfecha = Date::currentFecha($startDate,$j);
				$result = $this->saveEvent(Input::all(),$currentfecha,$idSerie);
			}
						
		}				

		
		return $result;
	}

	//Auxiliares
	private function superaHoras(){
		
		$supera = false;

		//Número de horas ya reservadas en global
		$nh = ACL::numHorasReservadas();
		
		//número de horas del evento a modificar (hay que restarlas de $nh)
		$event = Evento::find(Input::get('idEvento'));
		$nhcurrentEvent = Date::diffHours($event->horaInicio,$event->horaFin);
		
		//Actualiza el valor de horas ya reservadas quitando las del evento que se modifica
		$nh = $nh - $nhcurrentEvent;

		//Estas son las horas que se quieren reservar 
		$nhnewEvent = Date::diffHours(Input::get('hInicio'),Input::get('hFin'));
		
		//máximo de horas a la semana	
		$maximo = Config::get('options.max_horas');

		//credito = máximo (12) menos horas ya reservadas (nh)
		$credito = $maximo - $nh; //número de horas que aún puede el alumno reservar
		if ($credito < $nhnewEvent) $supera = true;
		//$supera = 'nh='.$nh.',$nhnewEvent='.$nhnewEvent.',nhcurrentEvent='.$nhcurrentEvent;
		return $supera;
	}



	private function uniqueId(){
		
		$idSerie = $this->getIdUnique();
		return $idSerie;
	}

	private function getIdUnique(){
		do {
			$evento_id = md5(microtime());
		} while (Evento::where('evento_id','=',$evento_id)->count() > 0);
		
		return $evento_id;
	}


	private function updateDias($oldIdSerie = '',$newIdSerie = ''){
		
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
	}

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
	private function setEstado($idRecurso,$currentfecha,$hi,$hf){
		$estado = 'denegada';

		
		//si modo automatico	
		if(ACL::automaticAuthorization($idRecurso)){
			//Ocupado??; -> Solo busco solapamientos con solicitudes ya aprobadas
			$condicionEstado = 'aprobada';
			//$currentFecha tiene formato d-m-Y
			$numEvents = Calendar::getNumSolapamientos($idRecurso,$currentfecha,$hi,$hf,$condicionEstado);
	
			//si ocupado
			if($numEvents > 0){
				//si ocupado
				$estado = 'denegada';
				//$msg = 'su reserva no se puede realizar, existen solapamientos con otras reservas ya aprobadas (ver detalles)';
			}
			//si libre
			else{
				$estado = 'aprobada';
				//$msg = 'Su reserva se realizado con éxito. (imprimir justificacante)'
			}

		}
		//si modo no automático (necesita validación)
		else{
			//ocupado??; estado = aprobado | pendiente | solapada (cualquiera de los posibles)
			$condicionEstado = '';
			$numEvents = Calendar::getNumSolapamientos($idRecurso,$currentfecha,$hi,$hf,$condicionEstado);
			if($numEvents > 0){
				//si ocupado
				$estado = 'pendiente';
				//$msg = 'su reserva está pendiente de validación. Existen solapamientos con otras peticiones (ver detalles)';
			}
			else{
				//si libre
				// Validadores realizan reservas no solicitudes
				if (!ACL::isValidador())
					$estado = 'pendiente';
				else
					$estado = 'aprobada';
				
			}
		}

		return $estado;

	}
	
}//fin del controlador