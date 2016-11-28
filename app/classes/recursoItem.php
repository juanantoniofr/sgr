<?php
	class recursoItem extends sgrRecurso {
  
  	//private $recurso;
		
		/**
			* deshabilita recurso para su reserva
			* @param $motivo string 
		*/
		public function disabled($motivo = ''){
			$this->recurso->disabled = true; //true
			$this->recurso->motivodisabled = $motivo;
			$this->recurso->save();
			return true;
		}

		/**
			*
			* habilita recurso para su reserva
		*/
		public function enabled(){
			$this->recurso->disabled = false; //false
			$this->recurso->motivodisabled = '';
			if ($this->contenedor != null){
				$this->contenedor->disabled = 0;
				$this->contenedor->save();
			}
			$this->recurso->save();
			return true;

		}

		/**
      * Devuelve true si $capacidad tiene permiso para ver (listar) recurso
      * @param void
      * @return $visible boolean 
    */
		public function esVisible($capacidad = ''){
			if (empty($capacidad))  return false;
      //$acl es un string con el formato {"r":"2,3"}, Esto quiere decir que los usuarios con capacidades 2 y 3 pueden "reservar" ese recurso
      $permisos = json_decode($this->recurso->acl,true); 
      if (strpos($permisos['r'],$capacidad) !== false) return true; 
      return false;
		} 
		
		/**
			* // Devuelve true si User con id = $id atiende $this->recurso
			* @param $id int
			* @return $atendido boolean
		*/
		public function esAtendidoPor($id = ''){
			if (empty($id)) return false;
			if ($this->recurso->esAtendidoPor->contains($id)) return true;	
			return false;
		}
		
		/**
			* // Devuelve array con un elemento ($this->recurso) si es visible (acl tiene permiso de lectura "r") para el usuario
			* @return array Object recurso (tipo=puesto)
		*/
		public function itemsVisiblesParaCapacidad($capacidad = ''){
			$visibles = array();
			if ($this->recurso->esVisible($capacidad)) $visibles[] = $this->recurso;
			return $visibles;
		}

		/**
	   	*
  	 	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  	*/
		public function eventosfuturos(){ //?????
			$conEstado = array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente'));
			return $this->recurso->eventos()->whereIn('estado',$conEstado)->where('fechaEvento','>=',date('Y-m-d'))->get();
		}

		/**
			* //Comprueba si el recurso está ocupado para el evento definido por $dataEvento 
			* @param $dataEvento array
			*	@param $excluyeId int excluir de la comprobación el evento con id igual $excluyeId 
			*
			* @return boolean
		*/
		public function recursoOcupado($dataEvento,$excluyeId = ''){
			$estado = array();
			$estado[] = 'aprobada';
			for ($tsfechaEvento = strtotime($dataEvento['fInicio']);$tsfechaEvento<=strtotime($dataEvento['fFin']);$tsfechaEvento = strtotime('+1 week ',$tsfechaEvento)) {
					$eventos = $this->eventos(date('Y-m-d',$tsfechaEvento),$estado);
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
		public function solapaEvento($dataEvento,$fecha){
			$estado = array();
			$estado[] = 'aprobada';
			$eventos = $this->eventos($fecha,$estado);
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
			* da valor a los atributos del objeto tipo Recurso ($this->recurso)
			* @param $data array definición de los datos de un recurso
		*/
		public function setdatos($data){
			foreach ($data as $key => $value) {
				$this->recurso->$key = $value;
			}
		}	
		
		/**
			* // Guarda relación gestor recurso-user
			* @param $id int identificador de usuario
		*/
		public function attach_gestor($id){
			if (!$this->recurso->gestores->contains($id)) $this->recurso->gestores()->attach($id);
			return true;
		}	

		/**
			* // Guarda relación administrardor recurso-user
			* @param $id int identificador de usuario
		*/
		public function attach_administrador($id){
			if (!$this->recurso->administradores->contains($id)) $this->recurso->administradores()->attach($id);
			return true;
		}	

		/**
			* // Guarda relación validador recurso-user
			* @param $id int identificador de usuario
		*/
		public function attach_validador($id){
			if (!$this->recurso->validadores->contains($id)) $this->recurso->validadores()->attach($id);
			return true;
		}	

		/**
			* // Remove relación gestor recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_gestor($id){
			if (!$this->recurso->gestores->contains($id)) $this->recurso->gestores()->detach($id);
			return true;
		}	

		/**
			* // Remove relación administrador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_administrador($id){
			if (!$this->recurso->administradores->contains($id)) $this->recurso->administradores()->detach($id);
			return true;
		}	

		/**
			* // Remove relación validador recurso-user
			* @param $id int identificador de usuario
		*/
		public function detach_validador($id){
			if (!$this->recurso->validadores->contains($id)) $this->recurso->validadores()->detach($id);
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
			return true;
		}	

		/**
			* //elimina eventos entre fechaini y fechafin, se no se indica, se borran todos los eventos
			* @param fini int timestamp
			* @param ffin int tiemstamp
			* @return boolean
		*/
		public function delEventos($fini = '',$fini = ''){
			//Input
			if (empty($fini)) $fini = strtotime('1970-1-1');//inicio timestamp
			if (empty($ffin)) $ffin = strtotime(Config::get('options.maxtimestamp'));
			//filter Evento
			$fechas = array($fini,$ffin);
			if ($this->recurso->eventos->count() > 0){
				$eventos = $this->recurso->eventos->filter(function($evento) use ($fechas){
					return strtotime($evento->fechaEvento) >= $fechas[0] && strtotime($evento->fechaEvento) <= $fechas[1];
				});
				
				$eventos->delete();
		}
			return true;
		}

		
		
		/**
			* //Devuelve los eventos para $fechaEvento con estado en $estados
			* @param $fechaEvento string formato Y-m-d
			*	@param $estado array estado de los eventos a obtener (aprobada | denegada | pendiente)
			*	@return Collection Objets type Evento 
			*
		*/
		public function eventos($fechaEvento,$estados = array()){//???deprecated
			if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independientemente de su estado
			return $this->recurso->eventos()->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->get();
		}


		/**
			* Devuelve recursos contenedores del mismo tipo y del mismo grupo que el recurso contenedor de $this->recurso
			* @param void
			*	@return array
		*/
		public function getContenedores(){
			$contenedores = array();
			if ($this->recurso->contenedor != null)
				$contenedores =  Recurso::where('tipo','=',$this->recurso->contenedor->tipo)->where('grupo_id','=',$this->recurso->contenedor->grupo_id)->get()->toArray();
			else
				$contenedores =  GrupoRecurso::where('tipo','=',$this->recurso->tipo)->get()->toArray();
			return $contenedores;
		}
		
		/**
			* // elimina el recurso de la BD
		*/
		public function del(){
			return $this->recurso->delete();
		}

		/**
			* //Edita los valores de $this->recurso en memoria (no salva a BD)
			* @param $datos array
			* @return true
		*/
		public function edit($datos){
			$data = array('nombre'        => $datos['nombre'],
                    'tipo'          => $datos['tipo'],
                    'grupo_id'      => 0,
                    'contenedor_id' => $datos['padre_id'],
                    'descripcion'   => $datos['descripcion'],
                    'id_lugar'      => $datos['id_lugar'],
                    'acl'           => sgrACL::buildJsonAcl($datos['modo'],$datos['roles']),
                    );
			$this->setdatos($data);
			return true;
		}

		/**
			* //Edita el campo acl de $this->recurso en memoria (no salva a BD)
			* @param $acl string formato json 
			* @return true
		*/
		public function updateAcl($acl){
			$this->recurso->acl = $acl;
			return true;
		}	

		
	
		
  }
?>