<?php

	class sgrEquipo implements sgrInterfaceRecurso{
		
		private $recurso;

		public function __construct(){
			$this->recurso = new Recurso;
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
			* // Devuelve true si User con id = $id atiende $this->recurso
			* @param $id int
			* @return $atendido boolean
		*/
		public function atendidoPor($id){
			$atendido = false;
			if (User::findOrFail($id)->atiende->count() > 0) $atendido = true;
			return $atendido;
		}
		
		public function setRecurso($recurso){
			$this->recurso = $recurso;
		}

		public function getEvents($fechaEvento){
			return $this->recurso->events()->where('fechaEvento','=',$fechaEvento)->get();
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