<?php
	class recursoContenedor extends sgrRecurso {
  
		//private $recurso;	//array obj recurso (espacio / tipoequipo)
		//private $items = array(); //array obj recursoItem (puesto / equipo)
		
		/**
			*
			* habilita recurso y todos sus items (puestos/equipos) para su reserva
		*/
		public function enabled(){
			foreach ($this->items as $item) {
				$item->enabled();
			}		
			$this->recurso->disabled = 0; 
			return true;
		}
	
		/**
			*
			* deshabilita recurso y todos sus items (puestos/equipos) para su reserva
		*/
		public function disabled(){
			foreach ($this->items as $item) {
				$item->disabled();
			}		
			$this->recurso->disabled = true; //true
			return true;
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
			return false; // si no salimos antes, ningún item (puesto / equipo) es visible => el espacio / tipoequipo tampoco es visible
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
	   	*
  	 	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy en cualquier item
  	 	*
  	*/
		public function eventosfuturos(){
			$conEstado = array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente'));
			return $this->recurso->eventosItems()->whereIn('estado',$conEstado)->where('fechaEvento','>=',date('Y-m-d'))->get();
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
			return true;
		}	

		/**
			* // Guarda relación administrador recurso-user
			* @param $id int identificador de usuario
			*
			* @return true 
		*/
		public function attach_administrador($id){
			if (!$this->recurso->administradores->contains($id)) $this->recursos->administradores()->attach($id);
			foreach ($this->items as $item) {
				$item->attach_administrador($id);
			}
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
			return true;
		}

		/**
			* // Remove relación gestor recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_gestor($id){
			if (!$this->recurso->gestores->contains($id)) $this->recurso->gestores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_gestor($id);
			}
			return true;
		}	

		/**
			* // Remove relación administrador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_administrador($id){
			if (!$this->recurso->administradores->contains($id)) $this->recurso->administradores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_administrador($id);
			}
			return true;
		}	

		/**
			* // Remove relación validador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_validador($id){
			if (!$this->recurso->validadores->contains($id)) $this->recurso->validadores()->detach($id);
			foreach ($this->items as $item) {
				$item->detach_validador($id);
			}
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
			return true;
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
			$acl = sgrACL::buildJsonAcl($datos['modo'],$datos['roles']);
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

	
  }
?>