<?php

	abstract class abstractItem{

		private $recurso;
		
		
		public function __construct($recurso){
			$this->recurso = $recurso;
			return $this;
		}

		/**
			* habilita recurso para su reserva
		*/
		public function enabled(){
			$this->recurso->disabled = 0; //false
			$this->recurso->save();
			return true;
		}
		
		/**
			*
			* deshabilita recurso para su reserva
		*/
		public function disabled(){
			$this->recurso->disabled = 1; //true
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
			* //Devuelve los eventos para $fechaEvento con estado en $estados
			* @param $fechaEvento string formato Y-m-d
			*	@param $estado array estado de los eventos a obtener (aprobada | denegada | pendiente)
			*	@return Collection Objets type Evento 
			*
		*/
		public function eventos($fechaEvento,$estados = array()){
			if (empty($estado)) $estado = Config::get('options.estadoEventos'); //sino se especifica ningún estado para los eventos a obtener se obtienen todos independientemente de su estado
			return $this->recurso->eventos()->whereIn('estado',$estado)->where('fechaEvento','=',$fechaEvento)->get();
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
		public function eventosfuturos(){
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
			* 
			* Salva a BD el objeto Recurso ($this->recurso)
		*/
		public function save(){
			return $this->recurso->save();
		}
				
	}
?>