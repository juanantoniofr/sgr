<?php

	class sgrEspacio implements sgrInterfaceRecurso{

		private $recurso;
		private $puestos;

		public function __construct($id){
			$this->recurso = Recurso::findOrFail($id);
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

	}	

?>