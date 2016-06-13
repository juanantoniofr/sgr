<?php

	class sgrPuesto implements sgrInterfaceRecurso{
		
		
		private $recurso;

		public function __construct(){
			$this->recurso = new Recurso;
		}
	
	/**
		* //Comprueba si el recurso está ocupado para el evento definido por $dataEvento 
		* @param $dataEvento array
		*
		* @return boolean
	*/	
	public function recursoOcupado($dataEvento,$excluyeId = ''){
			$estado = array('aprobada');
			for ($tsfechaEvento = strtotime($dataEvento['fInicio']);$tsfechaEvento<=strtotime($dataEvento['fFin']);$tsfechaEvento = strtotime('+1 week ',$tsfechaEvento)) {
				$eventos = $this->getEvents(date('Y-m-d',$tsfechaEvento),$estado);
				if ( $eventos->count() > 0 ){
					foreach ($eventos as $evento) {
						if (strtotime($evento->horaInicio) <= strtotime($dataEvento['hInicio']) && strtotime($dataEvento['hInicio']) < strtotime($evento->horaFin) && $evento->evento_id != $excluyeId)
							return true;
						if (strtotime($evento->horaInicio) < strtotime($dataEvento['hFin']) && strtotime($dataEvento['hFin']) < strtotime($evento->horaFin) && $evento->evento_id != $excluyeId)
							return true; 	 	
					}//fin foreach
				}//fin if  	
			}//fin del for
			
			return false;
	}

	/**
		* //Comprueba si el recurso está ocupado para el evento definido por $dataEvento en la fecha $fecha 
		* @param $dataEvento array
		*	@param $fecha string (Y-m-d)
		*
		* @return boolean
	*/
	private function solapaEvento($dataEvento,$fecha){
		$estado = array();
		$estado[] = 'aprobada';
		$eventos = $this->getEvents($fecha,$estado);
		if ( $eventos->count() > 0 ){
			foreach ($eventos as $evento) {
				if (strtotime($evento->horaInicio) <= strtotime($dataEvento['hInicio']) && strtotime($dataEvento['hInicio']) < strtotime($evento->horaFin))
					return true;
				if (strtotime($evento->horaInicio) < strtotime($dataEvento['hFin']) && strtotime($dataEvento['hFin']) < strtotime($evento->horaFin))
					return true; 	 	
			}//fin foreach
		}//fin if 
		return false;
	}

	/**
		* //Añade un evento para la fecha $fecha con identificador de serie $idserie (si el puesto no está deshabilitado)
		* @param $dataEvento array 
		* @param $fecha string Y-m-d
		* @param $idserie string
	*/
	public function addEvent($dataEvento,$currentfecha,$idserie){
		if(0 === $this->recurso->disabled){
			$evento = new Evento();
			$evento = $this->setdataevent($evento,$dataEvento,$currentfecha,$idserie);
			if ($evento->save()) return $evento->id;
			}
		return false;
	}//fin function addEvent
				
		
	private function setdataevent($evento,$data,$currentfecha,$idserie){
		$evento->recurso_id = $this->recurso->id;
		//Procesar información de formulario
		$hInicio = date('H:i:s',strtotime($data['hInicio']));
		$hFin = date('H:i:s',strtotime($data['hFin']));
		
		$date = DateTime::createFromFormat('d-m-Y',$data['fInicio']);
		$evento->fechaInicio = $date->format('Y-m-d');
		$date = DateTime::createFromFormat('d-m-Y',$data['fFin']);
		$evento->fechaFin = $date->format('Y-m-d');
		//Estado inicial del evento (reserva)
		$estado = 'denegada';
		//si no se requiere validación 
		if( $this->recurso->validacion() === false){
			if ( $this->solapaEvento($data,$currentfecha) === false ) $estado = 'aprobada'; //NO validación && recurso no ocupado			
		}
		//si se requiere validación (se pueden solapar las peticiones)
		else {
			$estado = 'pendiente'; //Si validación pendiente por defecto
			if ( !$this->solapaEvento($data,$currentfecha) && Auth::user()->isValidador() ) //NO ocupado	y auth user es validador		
			 $estado = 'aprobada';
		}
		$evento->estado = $estado;
		//fin estado inicial
		
		$repeticion = 1;
		
		$evento->diasRepeticion = json_encode($data['dias']);
				
		if ($data['repetir'] == 'SR') {
			$repeticion = 0;
			$evento->fechaFin = $currentfecha;
			$evento->fechaInicio = $currentfecha;
			$evento->diasRepeticion = json_encode(array(date('N',strtotime($currentfecha))));
		}
		$evento->repeticion = $repeticion;

		$evento->evento_id = $idserie;
		$evento->titulo = $data['titulo'];
		$evento->actividad = $data['actividad'];
		
		$evento->fechaEvento = $currentfecha;
		
		$evento->dia = date('N',strtotime($currentfecha));
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
		
		return $evento;
	}
	
	/**
		* //Devuelve los eventos en $fechaEvento
		* @param $fechaEvento string formato Y-m-d
		*	@param $estado array estado de los eventos a obtener (aprobada | denegada | pendiente)
		*	@return Collection Objets type Evento 
		*
	*/
	public function getEvents($fechaEvento,$estado = ''){
			if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independiente de su estado
			return $this->recurso->events()->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->get();
	}

	public function visible(){
		return $this->recurso->visible();
	}

	public function recurso(){
		return $this->recurso;
	}
		
		public function items(){
    	//return $this->recurso->equipos->filter(function($equipo){return $equipo->visible();})
			return $this->recurso;
		}

		/**
    	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  	*/
		public function eventosfuturos(){
			return $this->recurso->events()->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
		}

		/**
			* // Devuelve true si User con id = $id atiende $this->recurso->espacio ($this->recurso es un puesto)
			* @param $id int
			* @return $atendido boolean
		*/
		public function atendidoPor($id){
			$atendido = false;
			$atendido = $this->recurso->espacio->esAtendidoPor($id);
			return $atendido;
		}

		public function setRecurso($recurso){
			$this->recurso = $recurso;
		}


		public function enabled(){
			$this->recurso->disabled =  0;
			return true;
		}

		public function disabled(){
			$this->recurso->disabled =  1;
			return true;
		}

		public function save(){
			return $this->recurso->save();
		}

		public function del(){
			//Softdelete recurso
     	return $this->recurso->delete();
		}

		public function delEvents(){
			//Softdelete eventos
			return $this->recurso->events()->delete();
		}

		public function update($data){
			return $this->recurso->update($data);
		}

		public function add($data){
			foreach ($data as $key => $value) {
				$this->recurso->$key = $value;
			}
			return true;
		}
	}	

?>