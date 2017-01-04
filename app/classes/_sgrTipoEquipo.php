<?php
/* marca branch master2 */
	class sgrTipoEquipo implements sgrInterfaceRecurso{

	private $recurso;
	private $equipos;
	private $sgrEquipos; //array de elementos de tipo $sgrPuesto o vacio

	public function __construct(){
		$this->recurso = new Recurso;
	}

	public function setRecurso($recurso){
		$this->recurso = $recurso;
		$this->equipos = $this->recurso->equipos;
		$this->sgrEquipos = array();
		foreach ($recurso->equipos as $equipo) {
			$sgrEquipo = RecursoFactory::getRecursoInstance(Config::get('options.equipo'));
			$sgrEquipo->setRecurso($equipo);
			$this->sgrEquipos[] = $sgrEquipo;
		}

		return true;
	}

	/*public function visible(){
		$visible = false;
		$recursoesvisible = false;
		$tienealmenosunequipovisible = false;
		if ($this->recurso->visible() && $this->recurso->equipos()->count() > 0){
			$recursoesvisible = true;
			foreach ($this->recurso->equipos as $equipo) {
					if ($equipo->visible()) $tienealmenosunequipovisible = true;
			}
		}
		if ($recursoesvisible && $tienealmenosunequipovisible) $visible = true; 
		return $visible;
	}*/

	public function recurso(){
			return $this->recurso;
	}
	
	/**
		*
   	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  */
	public function eventosfuturos(){
		if ($this->recurso->equipos->count() > 0){
				//Tiene puestos
				foreach($this->recurso->equipos as $equipo)	$id_equipos[] = $equipo->id;
  		  return Evento::whereIn('recurso_id',$id_equipos)->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
  		}
			else //No tiene puestos
				return $this->recurso->events()->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();		
	}

	/**
		* // Devuelve los equipos visibles (acl tiene permiso de lectura "r") para el usuario
		* @return array Object recurso (tipo=puesto)
	*/
	public function items(){
		$items = $this->recurso->equipos->filter(function($equipo){ return $equipo->visible(); });
  	return $items;
  	//return  $this->recurso->equipos();
	}
	
	/**
		* // Devuelve true si User con id = $id atiende $this->recurso
		* @param $id int
		* @return $atendido boolean
	*/
	public function atendidoPor($id){
		$atendido = false;
		if (User::findOrFail($id)->atiende->count() > 0) $atendido = true;
		return $atendido;
	}

	/**
		* //Comprueba si el recurso está ocupado para el evento definido por $dataEvento 
		* @param $dataEvento array
		*
		* @return boolean
	*/
	public function recursoOcupado($dataEvento){
		$estado = array('aprobada');//Sólo comprueba eventos aprobados.
		for ($tsfechaEvento = strtotime($dataEvento['fInicio']);$tsfechaEvento<=strtotime($dataEvento['fFin']);$tsfechaEvento = strtotime('+1 week ',$tsfechaEvento)) {
				$eventos = $this->getEvents(date('Y-m-d',$tsfechaEvento),$estado);
				if ( $eventos->count() > 0 ){
					foreach ($eventos as $evento) {
						if (strtotime($evento->horaInicio) <= strtotime($dataEvento['hInicio']) && strtotime($dataEvento['hInicio']) < strtotime($evento->horaFin))
							return true;
						if (strtotime($evento->horaInicio) < strtotime($dataEvento['hFin']) && strtotime($dataEvento['hFin']) < strtotime($evento->horaFin))
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
		* //Añade un evento para la fecha $currentfecha con identificador de serie $idserie:
		* 	--> Si tiene equipos, estos son reservables individualmente: se reserva todos los puestos.
		*		--> en caso contrario: no se hace nada
		* @param $dataEvento array 
		* @param $fecha string Y-m-d
		* @param $idserie string
	*/
	public function addEvent($dataEvento,$currentfecha,$idserie){
		
		if ($this->recurso->equipos->count() > 0){
				foreach($this->sgrEquipos as $equipo){
					$result = $equipo->addEvent($dataEvento,$currentfecha,$idserie);
				}
				return $result;
  	}
		/*else {
					$evento = new Evento();
					$evento = $this->setdataevent($evento,$dataEvento,$currentfecha,$idserie);
					if ($evento->save()) return $evento->id;*/
		return true;
		//}
	}//fin function addEvent
				
		
	/*private function setdataevent($evento,$data,$currentfecha,$idserie){
		
		$evento->recurso_id = $this->recurso->id;
		//Procesar información de formulario
		$hInicio = date('H:i:s',strtotime($data['hInicio']));
		$hFin = date('H:i:s',strtotime($data['hFin']));
		
		//Estado inicial del evento (reserva)
		$estado = 'denegada';
		//si no se requiere validación 
		if( !$this->recurso->validacion() ){
			if ( !$this->recursoOcupado($data) ) $estado = 'aprobada'; //NO validación && recurso no ocupado			
		}
		//si se requiere validación (se pueden solapar las peticiones)
		else {
			$estado = 'pendiente'; //Si validación pendiente por defecto
			if ( !$this->recursoOcupado($data) && Auth::user()->isValidador() ) //NO ocupado	y auth user es validador		
			 $estado = 'aprobada';
		}
		$evento->estado = $estado;
		//fin estado inicial
		$repeticion = 1;
		$evento->fechaFin = $data['fFin'];
		$evento->fechaInicio = $data['fInicio'];
		$evento->diasRepeticion = json_encode($data['dias']);
				
		if ($data['repetir'] == 'SR') {
			$repeticion = 0;
			$evento->fechaFin = $currentfecha;
			$evento->fechaInicio = $currentfecha;
			$evento->diasRepeticion = json_encode(array(date('N',strtotime($currentfecha))));
		}
				
		$evento->evento_id = $idserie;
		$evento->titulo = $data['titulo'];
		$evento->actividad = $data['actividad'];
		
		$evento->fechaEvento = $currentfecha;
		$evento->repeticion = $repeticion;
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
	}*/

	/**
		* //Devuelve los eventos para el día $fechaEvento
		*	@param $fechaEvento string formato Y-m-d
		*	@return Collection Object Evento
		*
	*/
	public function getEvents($fechaEvento,$estado = ''){
		if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independiente de su estado
		if ($this->recurso->equipos->count() > 0){
			foreach($this->recurso->equipos as $equipo)	$id_equipos[] = $equipo->id;
  	  return Evento::whereIn('recurso_id',$id_equipos)->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->groupby('evento_id')->orderby('horaFin','desc')->orderby('horaInicio')->get();
  	}
		else
		return $this->recurso->events()->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->get();
	}

	/*public function enabled(){
		foreach ($this->equipos as $equipo) {
			$equipo->disabled =  0;
		}		
		$this->recurso->disabled = 0;
		return true;
	}*/

	/*public function disabled(){
		
		foreach ($this->equipos as $equipo) {
			$equipo->disabled =  1;
		}		
		$this->recurso->disabled = 1;
		return true;
	}*/

	public function save(){
		foreach ($this->equipos as $equipo) {
			$equipo->save();
		}		
		$this->recurso->save();
		return true;
	}
	
	/**
		*
		* //elimina la relación equipo-tipoequipo y elimina el recurso (Softdelete)
	*/
	public function del(){
		foreach ($this->equipos as $equipo) {
			$equipo->tipoequipo_id = 0;
			$equipo->save();
		}

		$this->recurso->delete();		
	}

	public function delEvents(){
		//Softdelete eventos
		foreach ($this->equipos as $equipo) {
			$equipo->events()->delete();
		}
		$this->recurso->events()->delete();
	}

	public function update($data){
		$idrecursocontenedor = $this->recurso->id;
		//para cada uno de los puestos
		foreach ($this->recurso->equipos as $item) {
			//si cambia el tipo
			if ($data['tipo'] != $this->recurso->tipo){
				$item->tipo = Config::get('options.puesto');
				$item->espacio_id = $idrecursocontenedor;
				$item->tipoequipo_id = 0;
			}
			
			//updateAcl
			$item->acl = $data['acl'];
			$item->save();
		}
		
		//update recurso		
		$this->recurso->update($data);
		return true;
	}

	public function add($data){
		foreach ($data as $key => $value) {
			$this->recurso->$key = $value;
		}
		//$this->recurso->reservable = false; //Los tipo equipos no se pueden reservar, hay que reservar un item concreto
		return true;
	}	

}	
?>