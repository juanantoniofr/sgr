<?php

class sgrEvento {
	
	private $evento;
	private $idserie;	
	private $numeroitems;


	public function __construct($evento = ''){
			if (empty($evento)) {
				$this->evento = new Evento;
				$this->idserie = $this->uniqueId();
				$this->numeroitems = 0;
			}
			else	{
				$this->evento = $evento;
				$this->idserie = $this->serieId();
				$this->numeroitems = $evento->numeroRecursos();
			}	
			
			return $this;
	}
	
	//Save
	public function save(){
		$result = array('error' => false,
										'ids' => array(),
										'idsSolapamientos' => array(),
										'msgErrors' => array(),
										'msgSuccess' => '',
										'data'	=> array(),);
		$testDataForm = new Evento();
				
		if(!$testDataForm->validate(Input::all())){
			$result['error'] = true;
			$result['msgErrors'] = $testDataForm->errors();
			$result['data']	= $testDataForm->getdata();
		}
		else {
			$result['data'] = Input::all();
			
			/* nuevo */
			$id = Input::get('id_recurso','');
			$recurso = Recurso::findOrFail($id);
			$sgrRecurso = Factoria::getRecursoInstance($recurso);
			$id_serie = $this->getIdUnique(); //identificador de la serie de eventos (reservas periodicas, o puntuales sobre varios equipos o puestos)

			$datosdesdeform = Input::all();
			
			$datosdesdeform['reservarParaUvus'] = User::where('username','=',Input::get('reservarParaUvus'))->first()->id;

			
			$repeticion = 0;
			if (Input::get('repetir') == Config::get('options.repeticionSemanal')) 	$repeticion = 1; 
			$datosdesdeform['repetir'] = $repeticion;
			$dias = Input::get('dias');//array de dias de la semana (entrada formulario) donde 1 = lunes y 7 = domingo
			$diasSemana = array('0' => 'Sunday', '1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday');


			if($repeticion == 1){
				
				foreach ($dias as $dia) {
					$tsInicio = strtotime($diasSemana[$dia],$this->getTimeStamp(Input::get('fInicio'),'-'));
					$tsFin = $this->getTimeStamp(Input::get('fFin'),'-');
					$tsIncremento = 7 * 24 * 60 * 60; //Una semana
					$datosdesdeform['fInicio'] = date('Y-m-d',$tsInicio);
					$datosdesdeform['fFin'] = date('Y-m-d',$tsFin);
					
					for($i = $tsInicio; $i <= $tsFin; $i = $i+$tsIncremento){
						$datosdesdeform['fEvento'] = date('Y-m-d',$i);
						$result['idEvents'] = $sgrRecurso->addEvento($datosdesdeform,$id_serie);						
					}
					
				}
			}
			else{
				$ts = $this->getTimeStamp(Input::get('fEvento'),'-');
				$datosdesdeform['fInicio'] = date('Y-m-d',$ts);
				$datosdesdeform['fFin'] = date('Y-m-d',$ts);
				$datosdesdeform['fEvento'] = date('Y-m-d',$ts);
				$result['idEvents'] = $sgrRecurso->addEvento($datosdesdeform,$id_serie);						
			}
			
			//Msg confirmación al usuario (add reserva)
			$event = Evento::Where('evento_id','=',$result['idEvents'])->first();
			
			if ($event->estado == 'aprobada'){
				$result['msgSuccess'] = '<strong class="alert alert-info" > Reserva registrada con éxito. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $result['idEvents'])).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
			}
			if ($event->estado == 'pendiente'){
				$result['msgSuccess'] = '<strong class="alert alert-danger" >Reserva pendiente de validación. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $result['idEvents'])).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
			}
			//notificar a validadores si espacio requiere validación
			if ( $event->recurso->validacion() ){
				$sgrMail = new sgrMail();
				$sgrMail->notificaNuevoEvento($event);
			}
		}
		
		return $result;
	}


 	/** //Devuelve el número de recurso (equipos o espacios reservados por un evento) */
  public function numeroItems(){
  	return $this->numeroitems;//Evento::where('evento_id','=',$this->evento->evento_id)->groupby('recurso_id')->count();
  }

  public function serieId(){
  	return $this->evento->evento_id;
  }

  //private functions
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
		* 
	*/
	public function delete(){

		return $this->evento->delete();
	} 

	/**
		* //finalizar evento: cambia la hora fin del evento
		* @param $idevento int 
		* @return $result array
	*/
	public function finalizarEvento($idEvento = ''){
		
		$result = array('error' => false,
						'msgError' => '',
						'msgSuccess' => '');

		//$idEvento = Input::get('idevento','');
		if (empty($idEvento)) {
			$result['error'] = true;
			$result['msgError'] = 'Identificador de evento vacio...';
			return $result;	
		}

		$finalizarEvento = new FinalizarEvento;
		

		$evento = Evento::findOrFail($idEvento);
		//$finalizarEvento->evento_idSerie = $evento->evento_id;
		$finalizarEvento->evento_id = $evento->id;
		//$finalizarEvento->user_id = $evento->user->id;
		$sgrUser = new sgrUser(Auth::user);
		$finalizarEvento->tecnico_id = $sgrUser->id();
		$finalizarEvento->momento = date('Y-m-d H:i:s',time());//momento actual
		$finalizarEvento->observaciones = Input::get('observaciones','');
		
		
		$finalizarEvento->save();
		//$evento->finalizada = true;
		$evento->horaFin = date('H:i',mktime(date('H'),30));
		$evento->save();

		$result['msgSuccess'] = 'Evento finalizado con éxito...';

		return $result;
	}

	/**
		* Atender evento
		* @param $data array datos de formulario
		* @return boolean 
	*/
	public function atenderEvento($data){
		//$data
		$idevento = $data['idevento'];
		$observaciones = $data['observaciones'];
		$idtecnico = $data['idtecnico'];
		
		//save atención 
		$atencionEvento = AtencionEvento::firstOrNew(array('evento_id' => $idevento));
		$atencionEvento->tecnico_id = $idtecnico;
		$atencionEvento->momento = date('Y-m-d H:i:s',time());//momento actual
		$atencionEvento->observaciones = $observaciones;
		$atencionEvento->save();
		//evento
		//$evento = Evento::findOrFail($data['idEvento']);
		//$evento->atendida = true;
		//$evento->save();		
		return true;
	}

	/*
		Recibe una fecha en formato ES (d-m-Y)
		devuelve el timeStamp correspondiente a esa fecha.
	*/
	private function getTimeStamp($fecha,$delimiter = '-'){

		$f = explode($delimiter,$fecha);
		//formato: mktime(hours,minutes,segundos,mes,día,año);
		$result = mktime(0,0,0,$f[1],$f[0],$f[2]);

		return $result;
	}

	/**
		* @param $fInicio:	fecha en formato dd-mm-yyyy
		* @param $fFin:		fecha en formato dd-mm-yyyy
		* @param $dWeek:		día de la semana en formato 0->domingo,1->lunes,.... 6->sábado
		* @return	$numRepeticiones: Entero con el número de veces que se repite $dWeek entre $fInicio y $fFin 
	*/
	/*private function numRepeticiones($fInicio,$fFin,$dWeek){
		
		$numRepeticiones = 0;
		$aDaysWeek = array('0' => 'Sunday', '1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday');
		$self = new self();
					
		$startTime = strtotime($aDaysWeek[$dWeek],$self->getTimeStamp($fInicio,'-'));
		$endTime = $self->getTimeStamp($fFin,'-');
		$currentTime = $startTime;
		
		if ($startTime <= $endTime){
			do {
				$numRepeticiones++;
				$nextTime = strtotime('Next ' . $aDaysWeek[$dWeek],$currentTime);
				$currentTime = $nextTime;
			} while($nextTime <= $endTime);	
		}
		return $numRepeticiones;
	}*/
	/*
	private function saveEvents($data){
		$dias = $data['dias']; //1->lunes...., 5->viernes
		$respuesta = array();
		$evento_id = $this->getIdUnique();
	
		foreach ($dias as $dWeek) {
			//número de repeticiones de $dWeek entre $data['fInicio'],$data['fFin'] || 1 si no hay repetición
			if ($data['repetir'] == 'SR') $nRepeticiones = 1;
			else $nRepeticiones = sgrDate::numRepeticiones($data['fInicio'],$data['fFin'],$dWeek);
			
			for($j=0;$j<$nRepeticiones;$j++){
				$startDate = sgrDate::timeStamp_fristDayNextToDate($data['fInicio'],$dWeek);//return timestamp
				$currentfecha = sgrDate::fechaEnesimoDia($startDate,$j);//return string Y-m-d
				$respuesta[] =$this->saveEvent($data,$currentfecha,$evento_id);
			}
		}
		return $evento_id;
	}*/
	/*
	private function saveEvent($data,$currentfecha,$evento_id){
		$idrecurso = $data['id_recurso'];//$direcurso puede indentificar a un puesto/un equipo/un tipoequipo/espacio (con o sin puestos)
		$recurso = Recurso::findOrFail($idrecurso);
		$sgrRecurso = Factoria::getRecursoInstance($recurso);
		

		return $sgrRecurso->addEvent($data,$currentfecha,$evento_id);//addEvent devuelve el identificador del evento añadido
	}*/

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
			$sgrUser = new sgrUser(Auth::user());
			if ($sgrUser->isUserSgr() && $this->superaHoras()){
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
					Evento::where('evento_id','=',Input::get('idSerie'))->forceDelete();
				}
				else {
					Evento::where('evento_id','=',Input::get('idSerie'))->where('recurso_id','=',Input::get('id_recurso'))->forceDelete();
				}
				//Añadir los nuevos
				$result['idEvents'] = $this->editEvents($fechaInicio,$fechaFin,$idSerie);

				//Msg confirmación al usuario (edición de evento)
				$newEvent = Evento::Where('evento_id','=',$idSerie)->first();
				if ($newEvent->estado == 'aprobada') $result['msgSuccess'] = '<strong class="alert alert-info" > Reserva registrada con éxito. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $newEvent->evento_id)).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
				if ($newEvent->estado == 'pendiente')
					$result['msgSuccess'] = '<strong class="alert alert-danger" >Reserva pendiente de validación. Puede <a target="_blank" href="'.route('justificante',array('idEventos' => $newEvent->evento_id)).'">imprimir comprobante</a> de la misma si lo desea.</strong>';
				
				//notificar a validadores si espacio requiere validación
				if ( $event->recurso->validacion() ){
					$sgrMail = new sgrMail();
					$sgrMail->notificaEdicionEvento($newEvent);
				}
				

			} //fin else	
		}
		
		return $result;			
	} 
		

	/**
		* Devuelve horaInicio (H:m:s) como H:m
		*
	*/
	public function horaInicio(){
		return $this->evento->horaInicio;
	}	

	/**
		* Devuelve horaFin (H:m:s) como H:m
		*
	*/
	public function horaFin(){
		return $this->evento->horaFin;
	}	

	public function estado(){
		return $this->evento->estado;
	}

	public function actividad(){
		return $this->evento->actividad;
	}

	public function repeticion(){
		return $this->evento->repeticion;
	}

	//devuelve true si hay el evenyo fue finalizado o false en caso contrario
  public function finalizado(){
    return $this->evento->finalizacion()->count() > 0;
  } 

  public function id(){
  	return $this->evento->id;
  }

  public function recursoId(){
  	return $this->evento->recurso_id;
  }

  public function titulo(){
  	return $this->evento->titulo;
  }

  public function fechaEvento(){
  	return $this->evento->fechaEvento;
  }

  public function evento(){
  	return $this->evento;
  }

  public function userId(){
  	return $this->evento->user_id;
  }

  public function nombrePropietario(){
  	return $this->evento->user->nombre;
  }

  public function apellidosPropietario(){
  	return $this->evento->user->apellidos;
  }

  public function reservadoporId(){
  	return $this->evento->reservadoPor_id;
  }

  public function user(){
  	return $this->evento->user;
  }

  public function recurso(){
  	return $this->evento->recurso;
  }

	//devuelve true si hay la reserva fue finalizada y false en caso contrario
  public function finalizada(){
    return $this->evento->finalizacion()->count() > 0;
  } 


  public function tiporecurso(){
  	return $this->evento->recurso->tipo;
  }

	private function editEvents($fechaInicio,$fechaFin,$idSerie){
		
		$result = '';
		
		$repetir = Input::get('repetir');	
		$dias = Input::get('dias'); //1->lunes...., 5->viernes
		if ($repetir == 'SR') { //SR == sin repetición (no periódico)
			//$dias = array(date('N',sgrDate::gettimestamp($fechaInicio,'d-m-Y')));
			$dias = array(date('N',strtotime($fechaInicio)));
			$fechaFin = $fechaInicio;
		}
							
		foreach ($dias as $dWeek) {
							
			if (Input::get('repetir') == 'SR') $nRepeticiones = 1;
			else { $nRepeticiones = sgrDate::numRepeticiones($fechaInicio,$fechaFin,$dWeek);}
							
			for($j=0;$j<$nRepeticiones;$j++){
				$startDate = sgrDate::timeStamp_fristDayNextToDate($fechaInicio,$dWeek);//return timestamp
				$currentfecha = sgrDate::fechaEnesimoDia($startDate,$j);//return string Y-m-d
				$result = $this->saveEvent(Input::all(),$currentfecha,$idSerie);
			}
						
		}				

		
		return $result;
	}

	public function numeroHoras(){
    return (strtotime($this->evento->horaFin) - strtotime($this->evento->horaInicio)) / (60*60) ;
  }
	
	
	private function superaHoras(){
		
		$supera = false;

		//Número de horas ya reservadas en global
		$nh = Auth::user()->numHorasReservadas();
		
		//número de horas del evento a modificar (hay que restarlas de $nh)
		$event = Evento::find(Input::get('idEvento'));
		$nhcurrentEvent = sgrDate::diffHours($event->horaInicio,$event->horaFin);
		
		//Actualiza el valor de horas ya reservadas quitando las del evento que se modifica
		$nh = $nh - $nhcurrentEvent;

		//Estas son las horas que se quieren reservar 
		$nhnewEvent = sgrDate::diffHours(Input::get('hInicio'),Input::get('hFin'));
		
		//máximo de horas a la semana	
		$maximo = Config::get('options.max_horas');

		//credito = máximo (12) menos horas ya reservadas (nh)
		$credito = $maximo - $nh; //número de horas que aún puede el alumno reservar
		if ($credito < $nhnewEvent) $supera = true;
		//$supera = 'nh='.$nh.',$nhnewEvent='.$nhnewEvent.',nhcurrentEvent='.$nhcurrentEvent;
		return $supera;
	}

	/**
    * Los eventos se podrán anular hasta el día anterior a su fecha de realización.
    * @param $idUser int identificador de usuario para comprobar si tiene permiso para anular el evento
    * @return boolean
  */
  public function esAnulable($idUser){
    //$idUser no existe 
    if (User::where('id','=',$idUser)->count() == 0) return false;
    //$idUser no es propietario y no ha reservado para otro
    if ($this->evento->user_id != $idUser && $this->evento->reservadoPor_id != $idUser) return false;
    //la fecha del evento permite anular (igual para todos los usuarios)
    $hoy = strtotime('today');
    $timestamp = strtotime($this->evento->fechaEvento);
    if ($timestamp > $hoy) return true;
    
    return false;
  }
	
	/**
    * determina si un evento puede ser finalizado 
    * @return boolean
  */
  public function esFinalizable(){
    $eventoEsFinalizable = false;
    
    if ( strtotime($this->evento->fechaEvento) == strtotime(date('Y-m-d')) && strtotime($this->evento->horaFin) > strtotime(date('H:i')) ) $eventoEsFinalizable = true;
    
    return $eventoEsFinalizable;
  }

  
	/*private function delEvents(){
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
	}*/

	//Anular evento: realiza un soft_delete
	public function anularEvento(){
		
		$result = array('error' => false,
						'msgError' => '',
						'msgSuccess' => '');

		$idEvento = Input::get('idevento','');
		if (empty($idEvento)) {
			$result['error'] = true;
			$result['msgError'] = Config::get('msg.idempty');
			return $result;	
		}

		//$finalizarEvento = new FinalizarEvento;
		

		$evento = Evento::findOrFail($idEvento);
		//$evento->estado = 'anulado';
		$evento->delete();//Softdelete
		
		$result['msgSuccess'] = Config::get('msg.actionSuccess');

		return $result;
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

		$where  =	"fechaEvento = '".$currentfecha."' and ";
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