<?php

	class sgrEquipo implements sgrInterfaceRecurso{
		
		private $recurso;

		public function __construct($id){
			$this->recurso = Recurso::findOrFail($id);
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

	}	

?>