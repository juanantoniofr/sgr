<?php

	class recursoContenedor extends sgrRecurso {
		
		//private $recurso;	//array obj recurso (espacio / tipoequipo)
		//private $items = array(); //array obj recursoItem (puesto / equipo)
		
		//Eventos
		public function addEvento($datosevento,$id_serie){
			
			foreach ($this->items as $item) {
				$item->addEvento($datosevento,$id_serie);
			}	
			
			return $id_serie;
		}

		/**
			* //Devuelve los eventos entre $fechas con estado en $estado
			* @param $fini int timestamp fecha inicial
			*	@param $estado array estado de los eventos a obtener (aprobada | denegada | pendiente)
			* @param $ffin int timestamp fecha final
			*	@return Collection Objets type Evento 
			*
		*/
		public function getEventos($fini = '',$estados = array(),$ffin = ''){//
			/*
				//sino se especifica ningún estado para los eventos a obtener se obtienen todos independientemente de su estado
				'estadoEvento' => array('denegada',
																'aprobada',
																'pendiente',
																'finalizada',
																'anulada',
																'liberada',)
			*/
			if (empty($estados)) $estados = Config::get('options.estadosEvento');   
			
			$aFechas = array($fini,$ffin);
			if (empty($fini)) $aFechas[0] = strtotime('1970-1-1');
			if (empty($ffin))	$aFechas[1] = (int) Config::get('options.maxtimestamp');
			

			$datos = array('aFechas' => $aFechas, 'estados' => $estados);
			
			
			$eventos = $this->recurso->eventosItems/*->groupby('evento_id')*/->filter(function($evento) use ($datos){
				return strtotime($evento->fechaEvento) >= $datos['aFechas'][0] && strtotime($evento->fechaEvento) <= $datos['aFechas'][1] && in_array($evento->estado,$datos['estados']);
			});

			return $eventos;
		}

		/**
			*
			* habilita recurso y todos sus items (puestos/equipos) para su reserva
		*/
		public function enabled(){
			foreach ($this->items as $item) {
				$item->enabled();
			}		
			$this->recurso->disabled = 0;
			$this->recurso->motivodisabled = ''; 
			$this->recurso->save();
			return true;
		}
	
		/**
			* deshabilita recurso y todos sus items (puestos/equipos) para su reserva
			* @param $motivo string 
		*/
		public function disabled($motivo = ''){
			foreach ($this->items as $item) {
				$item->disabled($motivo);
			}		
			$this->recurso->disabled = true; //true
			$this->recurso->motivodisabled = $motivo;
			$this->recurso->save();
			return true;
		}

		/**
			* //Devuelve true si alguno de los items del recurso están enabled
			*
		*/
		public function isEnabled(){
			foreach ($this->items as $item) {
				if ($item->isEnabled()) return true;
			}
			return false;
		}

		/**
			* Devuelve true si $capacidad da permiso para ver (listar) recurso
    	* @param void
    	* @return $visible boolean 
  	*/
		public function esVisible($capacidad = ''){
			foreach ($this->items as $item) {
				if ($item->esVisible($capacidad)) return true; //true si al menos un puesto / equipo es visible
			}
			$permisos = json_decode($this->recurso->acl,true); 
      if (strpos($permisos['r'],$capacidad) !== false) return true;
			
			return false; // si no salimos antes
		}
		
		/**
			* // Devuelve true si User con id = $id atiende $this->recurso
			* @param $id int
			* @return $atendido boolean
		*/
		public function esAtendidoPor($id = ''){
			if (empty($id)) return false;
			
			foreach ($this->items as $item) {
				if ($item->esAtendidoPor($id)) return true;	
			}
			return false;
		}

		
		/**
			* // Devuelve los itmes visibles (acl tiene permiso de lectura "r") para el usuario
			* @return array Object recurso (tipo=puesto)
		*/
		public function itemsVisiblesParaCapacidad($capacidad = ''){
			$visibles = array();
			foreach ($this->items as $item) {
				if ($item->esVisible($capacidad)) $visibles[] = $item;
			}
			return $visibles;
		}

		/**
			* da valor a los atributos del objeto tipo Recurso ($this->recurso)
			* @param $data array definición de los datos de un recurso
		*/
		public function setdatos($data){
			foreach ($data as $key => $value) {
				$this->recurso->$key = $value;
			}
			$this->updatetipoitems();
		}	
		
		public function updatetipoitems(){
			foreach ($this->items as $item) {
				$datos = array('tipo' => Config::get('options.itemsdelcontenedor')[$this->recurso->tipo]);
				$item->setdatos($datos);
				$item->save();	
			}
			return true;
		}

		
		/**
			* // Guarda relación gestor recurso-user
			* @param $id int identificador de usuario
			*
			* @return true 
		*/
		public function attach_gestor($id){
			if (!$this->recurso->gestores->contains($id)) $this->recurso->gestores()->attach($id);
			foreach ($this->items as $item) {
				$item->attach_gestor($id);
			}
			$this->recurso->save();
			return true;
		}	

		/**
			* // Guarda relación administrador recurso-user
			* @param $id int identificador de usuario
			*
			* @return true 
		*/
		public function attach_administrador($id){
			if (!$this->recurso->administradores->contains($id)) $this->recurso->administradores()->attach($id);
			foreach ($this->items as $item) {
				$item->attach_administrador($id);
			}
			$this->recurso->save();
			return true;
		}	

		/**
			* // Guarda relación validador recurso-user
			* @param $id int identificador de usuario
			*
			* @return true 
		*/
		public function attach_validador($id){
			if (!$this->recurso->validadores->contains($id)) $this->recurso->validadores()->attach($id);
			foreach ($this->items as $item) {
				$item->attach_validador($id);
			}
			$this->recurso->save();
			return true;
		}

		/**
			* // Remove relación gestor recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_gestor($id){
			if ($this->recurso->gestores->contains($id)) $this->recurso->gestores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_gestor($id);
			}
			$this->recurso->save();
			return true;
		}	

		/**
			* // Remove relación administrador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_administrador($id){
			if ($this->recurso->administradores->contains($id)) $this->recurso->administradores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_administrador($id);
			}
			$this->recurso->save();
			return true;
		}	

		/**
			* // Remove relación validador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_validador($id){
			if ($this->recurso->validadores->contains($id)) $this->recurso->validadores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_validador($id);
			}
			$this->recurso->save();
			return true;
		}	

		/**
			* // Remove todas las relaciones (administrador/gestor/validador) recurso-user
			* @param void
		*/
		public function detach_all(){
			$this->recurso->gestores()->detach();
			$this->recurso->administradores()->detach();
			$this->recurso->validadores()->detach();
			foreach ($this->items as $item) {
				$item->detach_all();
			}
			$this->recurso->save();
			return true;
		}	

		

		/**
			* // elimina el recurso de la BD
			*
		*/
		public function del(){
			foreach ($this->items as $item) {
					$item->del();
				}	
			$this->recurso->delete();
			return true;
		}

		/**
			* //Devuelve los grupos contenedores del mismo tipo que $this->recurso
			* @param void
			*	@return array
		*/
		public function getContenedores(){
			$contenedores = array();
			$contenedores =  GrupoRecurso::where('tipo','=',$this->recurso->tipo)->get()->toArray();
			return $contenedores;
		}

		/**
			* //Edita los valores de $this->recurso en memoria (no salva a BD)
			*
		*/
		public function edit($datos){
			//input's check 
			$roles = array();
			if (isset($datos['roles'])) $roles = $datos['roles'];
			$maxhd = array();
			$currentAcl = json_decode($this->recurso->acl,true);
			if (isset($currentAcl['maxhd'])) $maxhd = $currentAcl['maxhd'];

			$acl = sgrACL::buildJsonAcl($datos['modo'],$roles,$maxhd);
			$data = array('nombre'        => $datos['nombre'],
                    'tipo'          => $datos['tipo'],
                    'grupo_id'      => $datos['padre_id'],
                    'contenedor_id' => 0,
                    'descripcion'   => $datos['descripcion'],
                    'id_lugar'      => $datos['id_lugar'],
                    'acl'           => $acl,
                    );
			$this->setdatos($data);
			foreach ($this->items as $item) {
				$item->updateAcl($acl);
				$item->save();
			}
			return true;
		}	


		
		
		

		/**
	   	*
  	 	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy en cualquier item
  	 	*
  	*/
		public function eventosfuturos(){
			$conEstado = array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente'));
			return $this->recurso->eventosItems()->whereIn('estado',$conEstado)->where('fechaEvento','>=',date('Y-m-d'))->get();
		}

		/**
			* //elimina eventos entre fechaini y fechafin
			* @param fini int timestamp
			* @param ffin int tiemstamp
			* @return boolean
		*/
		public function delEventos($fini = '',$fini = ''){
			//Softdelete eventos
			foreach ($this->items as $item) {
				$item->delEventos($fini,$ffin);
			}
			return true;
		}

		/**
			* //Devuelve los eventos para el día $fechaEvento
			*	@param $fechaEvento string formato Y-m-d
			* @param $estado array estados de la reserva
			*	@return Collection Object Evento
		*/
		public function eventos($fechaEvento,$estado = ''){
			if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independientemente de su estado	
			return $this->recurso->eventosItems()->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->get();
		}

		/**
			* //Comprueba si algún item está ocupado
			* @param $dataEvento array
			*	@param $excluyeId int excluir de la comprobación el evento con id igual $excluyeId 
			*
			* @return boolean
		*/
		public function recursoOcupado($dataEvento,$excluyeId = ''){
			foreach ($this->items as $item) {
				if ($item->recursoOcupado($dataEvento,$excluyeId)) return true;
			}
			return false;
		}

		/**
			* //Comprueba si el recurso está ocupado para el evento definido por $dataEvento en la fecha $fecha 
			* @param $dataEvento array
			*	@param $fecha string (Y-m-d)
			*
			* @return boolean
		*/
		public function solapaEvento($dataEvento,$fecha){
			foreach ($this->items as $item) {
				if ($item->solapaEvento($dataEvento,$fecha)) return true;
			}
			return false;
		}

		public function userPuedeReservar($timestamp,$user){
			
			//si algún item no se puede reservar, y se retorna false
			foreach ($this->items as $item) {
				if ($item->userPuedeReservar($timestamp,$user) === false) return false;
			}

			//si no tiene items y es reservable (si tiene --> si llega aquí todos los items se pueden reservar)
			$sgrUser = new sgrUser($user);
			return $sgrUser->userPuedeReservar($timestamp,$this->recurso->id);
		
		}
		
		
  }
?>