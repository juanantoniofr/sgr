<?php

	class sgrPuesto implements sgrInterfaceRecurso{
		
		
		private $recurso;

		public function __construct(){
			$this->recurso = new Recurso;
		}

		public function setRecurso($recurso){
			$this->recurso = $recurso;
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

	}	

?>