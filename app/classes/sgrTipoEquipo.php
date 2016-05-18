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
		*
   	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  */
	public function eventosfuturos(){
		if ($this->recurso->equipos->count() > 0){
				//Tiene puestos
				foreach($this->recurso->equipos as $equipo)	$id_equipos[] = $equipo->id;
  		  return Evento::whereIn('recurso_id',$id_equipos)->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
  		}
			else //No tiene puestos
				return $this->recurso->events()->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();		
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
			$equipo->disabled =  1;
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
	
	/**
		*
		* //elimina la relación equipo-tipoequipo y elimina el recurso (Softdelete)
	*/
	public function del(){
		foreach ($this->equipos as $equipo) {
			$equipo->tipoequipo_id = 0;
			$equipo->save();
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
		$idrecursocontenedor = $this->recurso->id;
		//para cada uno de los puestos
		foreach ($this->recurso->equipos as $item) {
			//si cambia el tipo
			if ($data['tipo'] != $this->recurso->tipo){
				$item->tipo = Config::get('options.puesto');
				$item->espacio_id = $idrecursocontenedor;
				$item->tipoequipo_id = 0;
			}
			
			//updateAcl
			$item->acl = $data['acl'];
			$item->save();
		}
		
		//update recurso		
		$this->recurso->update($data);
		return true;
	}

	public function add($data){
		foreach ($data as $key => $value) {
			$this->recurso->$key = $value;
		}
		//$this->recurso->reservable = false; //Los tipo equipos no se pueden reservar, hay que reservar un item concreto
		return true;
	}	

}	
?>