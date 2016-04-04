<?php

	class sgrEspacio implements sgrInterfaceRecurso{

		private $recurso;
		private $puestos;

		public function __construct(){
			$this->recurso = new Recurso;
		}

		public function setRecurso($recurso){
			$this->recurso = $recurso;
			$this->puestos = $this->recurso->puestos;
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
				$puesto->delete();
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

	}	

?>