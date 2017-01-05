<?php
	interface sgrInterfaceRecurso{
	/* :) 1-5-2017 */		
		public function enabled();
		public function disabled();
		public function save();
		public function del();
		public function update($data);
		public function add($data);
		/**
			* //obtiene todos los eventos con 'estado' igual a alguno de los definidos en el array $estado
			*
			* @param $fecha string formato Y-m-d
			* @param $estados array (vacio | aprobada | pendiente | denegada)
		*/
		public function getEvents($fecha,$estados);
		public function recurso();
		public function atendidoPor($id);
		public function items();
		public function visible();
		/**
			* //devuelve true si el recurso está ocupado por algún evento con identificador distinto de $excluyeId
			* @param $dataEvento array datos del evento
			* @param $excluyeId int identificador de evento
			*
			* @return boolean true si ocupado | false en caso contrario
		*/
		public function recursoOcupado($dataEvento,$excluyeId);
		public function addEvent($dataEvento,$fecha,$idserie);
		public function deleteEvent($idSerie);
	}

?>