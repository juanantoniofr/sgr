<?php

class sgrEvento {

	//Save
	public function save(){

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
			if ( $event->recursoOwn->validacion() ){
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
					$evento->estado = $this->setEstado($data['grupo_id'],$id_recurso,$currentfecha,$hInicio,$hFin);
				
					$repeticion = 1;
					$evento->fechaFin = Date::parsedatetime($data['fFin'],'d-m-Y','Y-m-d');
					$evento->fechaInicio = Date::parsedatetime($data['fInicio'],'d-m-Y','Y-m-d');
					$evento->diasRepeticion = json_encode($data['dias']);
				
					if ($data['repetir'] == 'SR') {
						$repeticion = 0;
						$evento->fechaFin = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
						$evento->fechaInicio = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
						$evento->diasRepeticion = json_encode(array(date('N',Date::gettimestamp($currentfecha,'d-m-Y'))));
					}
				
					$evento->evento_id = $evento_id;
					$evento->titulo = $data['titulo'];
					$evento->actividad = $data['actividad'];
					$evento->recurso_id = $id_recurso;
					$evento->fechaEvento = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
					$evento->repeticion = $repeticion;
					$evento->dia = date('N',Date::gettimestamp($currentfecha,'d-m-Y'));
					$evento->horaInicio = $data['hInicio'];
					$evento->horaFin = $data['hFin'];
					$evento->reservadoPor_id = Auth::user()->id;//Persona que reserva
					
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
			$evento->estado = $this->setEstado($data['grupo_id'],$data['id_recurso'],$currentfecha,$hInicio,$hFin);
			

			
			$repeticion = 1;
			$evento->fechaFin = Date::parsedatetime($data['fFin'],'d-m-Y','Y-m-d');
			$evento->fechaInicio = Date::parsedatetime($data['fInicio'],'d-m-Y','Y-m-d');
			$evento->diasRepeticion = json_encode($data['dias']);
			
			if ($data['repetir'] == 'SR') {
				$repeticion = 0;
				$evento->fechaFin = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
				$evento->fechaInicio = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
				$evento->diasRepeticion = json_encode(array(date('N',Date::gettimestamp($currentfecha,'d-m-Y'))));
			}
			
			$evento->evento_id = $evento_id;
			$evento->titulo = $data['titulo'];
			$evento->actividad = $data['actividad'];
			$evento->recurso_id = $data['id_recurso'];
			$evento->fechaEvento = Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d');
			$evento->repeticion = $repeticion;
			$evento->dia = date('N',Date::gettimestamp($currentfecha,'d-m-Y'));
			$evento->horaInicio = $data['hInicio'];
			$evento->horaFin = $data['hFin'];
			$evento->reservadoPor_id = Auth::user()->id;//Persona que reserva

					
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
	public function edit(){

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
			if (Auth::user()->isUser() && $this->superaHoras()){
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
				if ( $event->recursoOwn->validacion() ){
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

	
	private function superaHoras(){
		
		$supera = false;

		//Número de horas ya reservadas en global
		$nh = Auth::user()->numHorasReservadas();
		
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

	//del
	public function delete(){

		$result = '';

		$result = $this->delEvents();
		return $result;
	} 
	
	private function delEvents(){
		$result = '';
		$eventToDel = Evento::find(Input::get('idEvento'))->first();
		$event = Evento::find(Input::get('idEvento'));
		if (Input::get('id_recurso') == 0){
			Evento::where('evento_id','=',Input::get('idSerie'))->delete();
		}
		else {
			Evento::where('evento_id','=',Input::get('idSerie'))->where('recurso_id','=',Input::get('id_recurso'))->delete();
		}
		
		
		return $result;
	}

	//finalizar evento
	public function finaliza(){
		
		$result = array('error' => false,
						'msgError' => '',
						'msgSuccess' => '');

		$idEvento = Input::get('idevento','');
		if (empty($idEvento)) {
			$result['error'] = true;
			$result['msgError'] = 'Identificador de evento vacio...';
			return $result;	
		}

		$finalizarEvento = new FinalizarEvento;
		

		$evento = Evento::findOrFail($idEvento);
		$finalizarEvento->evento_idSerie = $evento->evento_id;
		$finalizarEvento->evento_id = $evento->id;
		$finalizarEvento->user_id = $evento->userOwn->id;
		$finalizarEvento->tecnico_id = Auth::user()->id;
		$finalizarEvento->momento = date('Y-m-d H:i:s',time());//momento actual
		$finalizarEvento->observaciones = Input::get('observaciones','');
		
		
		$finalizarEvento->save();
		$evento->finalizada = true;
		$evento->horaFin = date('H:i',mktime(date('H'),30));
		$evento->save();

		$result['msgSuccess'] = 'Evento finalizado con éxito...';

		return $result;
	}
	
	//Anular evento
	public function anula(){
		
		$result = array('error' => false,
						'msgError' => '',
						'msgSuccess' => '');

		$idEvento = Input::get('idevento','');
		if (empty($idEvento)) {
			$result['error'] = true;
			$result['msgError'] = 'Identificador de evento vacio...';
			return $result;	
		}

		//$finalizarEvento = new FinalizarEvento;
		

		$evento = Evento::findOrFail($idEvento);
		//$evento->estado = 'anulado';
		$evento->delete();
		
		$result['msgSuccess'] = 'Evento finalizado con éxito...';

		return $result;
	}

	//Atender evento
	public function atender($data){
		//data
		$idevento = $data['idevento'];
		$observaciones = $data['observaciones'];
		$idtecnico = $data['idtecnico'];
		//atencionEvento
		$atencionEvento = AtencionEvento::firstOrNew(array('tecnico_id' => $idtecnico));
		$atencionEvento->evento_id = $idevento;
		$atencionEvento->momento = date('Y-m-d H:i:s',time());//momento actual
		$atencionEvento->observaciones = $observaciones;
		$atencionEvento->save();
		//evento
		$evento = Evento::findOrFail($data['idEvento']);
		$evento->atendida = true;
		$evento->save();		
		return 'success';
	}

	//private functions
	
	/**
	 * calcula el número de horas de una reserva: diferencia entre horaInicio y horaFin
 	 * 
 	 * @param void
 	 * @return $numerohorasreservadas int número de horas de una reserva: diferencia entre horaInicio y horaFin
	*/
	private function diffHours($h1,$h2){ 
	    //In: $h1,$h2 -> horas en formato H:m:s
	    $tsh1 = strtotime($h1); //número de segundos desde 1 enero de 1970
	    $tsh2 = strtotime($h2); //número de segundos desde 1 enero de 1970

	    $diff = ($tsh2 - $tsh1) / (60 * 60) ; //diferencia en horas
		
		return $diff;
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

	/**
 	* Determina si un nuevo evento en $idRecurso en $currentfecha, con hora inicio $hi, hora de finalización $hf, solapa con eventos con $condicionEstado existentes en BD
 	*
 	* @param $idGrupo int
 	* @param $idRecurso int
 	* @param $currentfecha (d-m-Y)
 	* @param $hi
 	* @param $hf
 	* @param $condicionEstado
 	* @return $numSolapamientos int
 	*
 	*/
	public function solapa($idGrupo,$idRecurso,$currentfecha,$hi,$hf,$condicionEstado = ''){
		
		$numSolapamientos = 0;
		
		$hi = date('H:i:s',strtotime($hi));
		$hf = date('H:i:s',strtotime($hf));

		//si estamos editando un evento => Existe Input::get('idEvento'), hay que excluir para poder modificar por ejemplo en nombre del evento
		$idEvento = Input::get('idEvento');
		$option = Input::get('option');
		$action = Input::get('action');
		$excludeEvento = '';
		
		//Excluye eventos de la misma serie en cualquier espacio para poder cambiar el nombre a reservas tanto de un solo equipo//puesto o espacio como a reservas de todos los equipos/puestos
		$idSerie = Input::get('idSerie');
		$excludeEvento = '';
		if (!empty($idSerie) && $action == 'edit') $excludeEvento = " and evento_id != '".$idSerie."'";


		$where  =	"fechaEvento = '".Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d')."' and ";
		if (!empty($condicionEstado))	$where .=	"estado = '".$condicionEstado."' and ";	
		$where .= 	" (( horaInicio <= '".$hi."' and horaFin > '".$hi."' ) "; 
		$where .= 	" or ( horaFin > '".$hf."' and horaInicio < '".$hf."')";
		$where .=	" or ( horaInicio > '".$hi."' and horaInicio < '".$hf."')";
		$where .=	" or horaFin < '".$hf."' and horaFin > '".$hi."')";
		$where .= 	$excludeEvento;
		
		if ($idRecurso != 0) $numSolapamientos = Recurso::find($idRecurso)->events()->whereRaw($where)->count();
		else {
			//Si idRecurso == 0 entonces tenemos un espacio con puestos o un grupo de equipos, por ejemplo cámaras
			$recursos = Recurso::where('grupo_id','=',$idGrupo)->get();
			
			foreach ($recursos as $recurso) {
				$numSolapamientos = $numSolapamientos + $recurso->events()->whereRaw($where)->count();	
			 } 
		}
		
		
		return $numSolapamientos;
	}
	


	private function setEstado($idGrupo,$idRecurso,$currentfecha,$hi,$hf){
		$estado = 'denegada';

		$self = new self();
		
		//si modo automatico validacion = false	
		if( !Recurso::find($idRecurso)->validacion() ){
			//Ocupado??; -> Solo busco solapamientos con solicitudes ya aprobadas
			$condicionEstado = 'aprobada';
			//$currentFecha tiene formato d-m-Y
			$numEvents =  $self->solapa($idGrupo,$idRecurso,$currentfecha,$hi,$hf,$condicionEstado);
			//si ocupado
			if($numEvents > 0){
				//si ocupado
				$estado = 'denegada';
			}
			//si libre
			else{
				$estado = 'aprobada';
			}

		}
		//si modo no automático (necesita validación)
		else{
			//ocupado??; estado = aprobado | pendiente | solapada (cualquiera de los posibles)
			$condicionEstado = '';
			$numEvents = $self->solapa($idGrupo,$idRecurso,$currentfecha,$hi,$hf,$condicionEstado);
			if($numEvents > 0){
				//si ocupado
				$estado = 'pendiente';
			}
			else{
				//si libre
				// Validadores realizan reservas no solicitudes
				if (!Auth::user()->isValidador())
					$estado = 'pendiente';
				else
					$estado = 'aprobada';
				
			}
		}

		return $estado;

	}
}
?>