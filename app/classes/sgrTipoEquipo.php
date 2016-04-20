<?php

	class sgrTipoEquipo implements sgrInterfaceRecurso{

	private $recurso;
	private $equipos;

	public function __construct(){
		$this->recurso = new Recurso;
	}

	public function recurso(){
			return $this->recurso;
	}
	
	/**
		* // Devuelve los equipos visibles (acl tiene permiso de lectura "r") para el usuario
		* @return array Object recurso (tipo=puesto)
	*/
	public function items(){
		$items = $this->recurso->equipos->filter(function($equipo){ return $equipo->visible(); });
  	return $items;
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
		$this->equipos = $this->recurso->equipos;
		return true;
	}
	
	public function getEvents($fechaEvento){
		if ($this->recurso->equipos->count() > 0){
			foreach($this->recurso->equipo as $equipo)	$id_equipos[] = $equipo->id;
  	  return Evento::whereIn('recurso_id',$id_equipos)->where('fechaEvento','=',$fechaEvento)->groupby('evento_id')->orderby('horaFin','desc')->orderby('horaInicio')->get();
  	}
		else
		return $this->recurso->events()->where('fechaEvento','=',$fechaEvento)->get();
	}

	public function enabled(){
		foreach ($this->equipos as $equipo) {
			$equipo->disabled =  0;
		}		
		$this->recurso->disabled = 0;
		return true;
	}

	public function disabled(){
		
		foreach ($this->equipos as $equipo) {
			$puesto->disabled =  1;
		}		
		$this->recurso->disabled = 1;
		return true;
	}

	public function save(){
		foreach ($this->equipos as $equipo) {
			$equipo->save();
		}		
		$this->recurso->save();
		return true;
	}

	public function del(){
		//Softdelete recurso
    foreach ($this->equipos as $equipo) {
			$equipo->delete();
		}

		$this->recurso->delete();		
	}

	public function delEvents(){
		//Softdelete eventos
		foreach ($this->equipos as $equipo) {
			$equipo->events()->delete();
		}
		$this->recurso->events()->delete();
	}

	public function update($data){
		return $this->recurso->update($data);
	}

	public function add($data){
		foreach ($data as $key => $value) {
			$this->recurso->$key = $value;
		}
		$this->recurso->reservable = false; //Los tipo equipos no se pueden reservar, hay que reservar un item concreto
		return true;
	}	

}	
?>