<?php
	
	interface sgrInterfaceRecurso{
		
		public function enabled();
		public function disabled();
		public function save();
		public function del();
		public function update($data);
		public function add($data);
		public function getEvents($fecha,$estados);
		public function recurso();
		public function atendidoPor($id);
		public function items();
		public function visible();
		public function recursoOcupado($dataEvento);
		public function addEvent($dataEvento,$fecha,$idserie);
	}

?>