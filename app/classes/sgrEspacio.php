<?php

	class sgrEspacio implements sgrInterfaceRecurso{

	private $recurso;
	private $puestos;
	private $sgrPuestos; //array de elementos de tipo $sgrPuesto o vacio

	public function __construct(){
		$this->recurso = new Recurso;
	}

	public function setRecurso($recurso){
		$this->recurso = $recurso;
		$this->puestos = $this->recurso->puestos;
		$this->sgrPuestos = array();
		foreach ($recurso->puestos as $puesto) {
			$sgrPuesto = RecursoFactory::getRecursoInstance(Config::get('options.puesto'));
			$sgrPuesto->setRecurso($puesto);
			$this->sgrPuestos[] = $sgrPuesto;
		}
		return true;
	}
	
	public function visible(){
		$visible = false;
		$recursoesvisible = false;
		$tienealmenosunpuestovisible = false;
		
		//Espacio sin puestos
		if ($this->recurso->visible() && $this->recurso->puestos()->count() == 0){
				$visible = true;
				return $visible;
		}

		//Espacio con puestos
		if ($this->recurso->visible() && $this->recurso->puestos()->count() > 0){
				$recursoesvisible = true;
				foreach ($this->recurso->puestos as $puesto) {
						if ($puesto->visible()) $tienealmenosunpuestovisible = true;
				}
		}
		if ($recursoesvisible && $tienealmenosunpuestovisible) $visible = true; 
		return $visible;
	}

	public function recurso(){
			return $this->recurso;
	}
	
	/**
   	*
   	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  */
	public function eventosfuturos(){
		if ($this->recurso->puestos->count() > 0){
				//Tiene puestos
				foreach($this->recurso->puestos as $puesto)	$id_puestos[] = $puesto->id;
  		  return Evento::whereIn('recurso_id',$id_puestos)->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
  		}
			else //No tiene puestos
				return $this->recurso->events()->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
	}

	/**
		* // Devuelve los puestos visibles (acl tiene permiso de lectura "r") para el usuario
		* @return array Object recurso (tipo=puesto)
	*/
	public function items(){
		$items = $this->recurso->puestos->filter(function($puesto){ return $puesto->visible(); });
  	return $items;
	}
	
	/**
		* // Devuelve true si User con id = $id atiende $this->recurso
		* @param $id int
		* @return $atendido boolean
	*/
	public function atendidoPor($id){
		$atendido = false;
		//if ($this->recurso->grupo->tecnicos->contains($id)) $atendido = true;
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
		$estado = array();
		$estado[] = 'aprobada';
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
		* 	--> Si tiene puestos y son reservables individualmente: se reserva todos los puestos.
		*		--> en caso contrario: se reserva el espacio
		* @param $dataEvento array 
		* @param $fecha string Y-m-d
		* @param $idserie string
	*/
	public function addEvent($dataEvento,$currentfecha,$idserie){
		if ($this->recurso->puestos->count() > 0){
			foreach($this->sgrPuestos as $sgrPuesto){
				$result = $sgrPuesto->addEvent($dataEvento,$currentfecha,$idserie);
			}
			return $result;
  	}
		else {
			$evento = new Evento();
			$evento = $this->setdataevent($evento,$dataEvento,$currentfecha,$idserie);
			if ($evento->save()) return $evento->id;
			return false;
		}
	}//fin function addEvent
				
		
	private function setdataevent($evento,$data,$currentfecha,$idserie){
		$evento->recurso_id = $this->recurso->id;
		//Procesar información de formulario
		$hInicio = date('H:i:s',strtotime($data['hInicio']));
		$hFin = date('H:i:s',strtotime($data['hFin']));
		
		//Estado inicial del evento (reserva)
		$estado = 'denegada';
		//si no se requiere validación 
		if( $this->recurso->validacion() === false){
			if ( $this->solapaEvento($data,$currentfecha) === false ) $estado = 'aprobada'; //NO validación && recurso no ocupado			
			//else $estado = 'pendiente';
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
	}

	/**
			* //Devuelve los eventos para el día $fechaEvento
			*	@param $fechaEvento string formato Y-m-d
			* @param $estado array estados de la reserva
			*	@return Collection Object Evento
			*
	*/
	public function getEvents($fechaEvento,$estado = ''){

			if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independiente de su estado

			if ($this->recurso->puestos->count() > 0){
				foreach($this->recurso->puestos as $puesto)	$id_puestos[] = $puesto->id;
  		  return Evento::whereIn('recurso_id',$id_puestos)->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->groupby('evento_id')->orderby('horaFin','desc')->orderby('horaInicio')->get();
  		}
			else{
				//$estado = 'aprobada';
				//$estado = Config::get('options.estadoEventos');
				//return $this->recurso->events->whereIn('estadodc',$estado)->where('fechaEvento','=',$fechaEvento)->get();
				$use = array('fechaEvento' => $fechaEvento,'estado' => $estado);
				return $this->recurso->events->filter(function($evento) use ($use){
					return in_array($evento->estado,$use['estado']) && $evento->fechaEvento == $use['fechaEvento'];
				});
			}
	}

	public function enabled(){
		foreach ($this->puestos as $puesto) {
			$puesto->disabled =  0;
		}		
		$this->recurso->disabled = 0;
		return true;
	}

	public function disabled(){
		foreach ($this->puestos as $puesto) {
			$puesto->disabled =  1;
		}		
		$this->recurso->disabled = 1;
		return true;
	}

	public function save(){
		foreach ($this->puestos as $puesto) {
			$puesto->save();
		}		
		$this->recurso->save();
		return true;
	}

	public function del(){
		//Softdelete recurso
    foreach ($this->puestos as $puesto) {
			$puesto->espacio_id = 0;
			$puesto->save();
		}

		$this->recurso->delete();		
	}

	public function delEvents(){
		//Softdelete eventos
		foreach ($this->puestos as $puesto) {
			$puesto->events()->delete();
		}
		$this->recurso->events()->delete();
	}

	public function update($data){
		$idrecursocontenedor = $this->recurso->id;
		//para cada uno de los puestos
		foreach ($this->recurso->puestos as $item) {
			//si cambia el tipo
			if ($data['tipo'] != $this->recurso->tipo){
				$item->tipo = Config::get('options.equipo');
				$item->tipoequipo_id = $idrecursocontenedor;
				$item->espacio_id = 0;
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
		return true;
	}	



	}	
?>