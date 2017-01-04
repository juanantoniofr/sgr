<?php
/* marca branch master2 */
	abstract class sgrItem{

		private $recurso; //obj Recurso

		public function __construct(){
			$this->recurso = new Recurso;
		}

		public function enabled(){
			$this->recurso->disabled =  0;
		return true;
		}

	}
?>