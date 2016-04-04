<?php
	
	interface sgrInterfaceRecurso{
		
		public function enabled();
		public function disabled();
		public function save();
		public function del();
		public function update($data);
		public function add($data);
		public function getEvents($fecha);
	
	}

?>