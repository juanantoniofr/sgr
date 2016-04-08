<?php

	class sgrEquipo implements sgrInterfaceRecurso{
		
		private $recurso;

		public function __construct(){
			$this->recurso = new Recurso;
		}

		public function recurso(){
			return $this->recurso;
		}
		
		public function setRecurso($recurso){
			$this->recurso = $recurso;
		}

		public function getEvents($fechaEvento){
			return $this->recurso->where('fechaEvento','=',$fechaEvento)->get();
		}

		public function enabled(){
			$this->disabled =  0;
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