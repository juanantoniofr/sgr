<?php

	class sgrEspacio implements sgrInterfaceRecurso{

	private $recurso;
	private $puestos;

	public function __construct(){
		$this->recurso = new Recurso;
	}
	
	public function visible(){
			return true;
	}

	public function recurso(){
			return $this->recurso;
	}
	
	/**
   	*
   	* //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  */
	public function eventosfuturos(){
		if ($this->recurso->puestos->count() > 0){
				//Tiene puestos
				foreach($this->recurso->puestos as $puesto)	$id_puestos[] = $puesto->id;
  		  return Evento::whereIn('recurso_id',$id_puestos)->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
  		}
			else //No tiene puestos
				return $this->recurso->events()->where('fechaEvento','>=',date('Y-m-d'))->whereIn('estado',array(Config::get('options.reservaAprobada'),Config::get('options.reservaPendiente')))->get();
	}

	/**
		* // Devuelve los puestos visibles (acl tiene permiso de lectura "r") para el usuario
		* @return array Object recurso (tipo=puesto)
	*/
	public function items(){
		$items = $this->recurso->puestos->filter(function($puesto){ return $puesto->visible(); });
  	return $items;
	}
	
	/**
		* // Devuelve true si User con id = $id atiende $this->recurso
		* @param $id int
		* @return $atendido boolean
	*/
	public function atendidoPor($id){
		$atendido = false;
		//if ($this->recurso->grupo->tecnicos->contains($id)) $atendido = true;
		if (User::findOrFail($id)->atiende->count() > 0) $atendido = true;
		return $atendido;
	}

	public function setRecurso($recurso){
		$this->recurso = $recurso;
		$this->puestos = $this->recurso->puestos;
		return true;
	}

	
	public function getEvents($fechaEvento){

			if ($this->recurso->puestos->count() > 0){
				foreach($this->recurso->puestos as $puesto)	$id_puestos[] = $puesto->id;
  		  return Evento::whereIn('recurso_id',$id_puestos)->where('fechaEvento','=',$fechaEvento)->groupby('evento_id')->orderby('horaFin','desc')->orderby('horaInicio')->get();
  		}
			else
				return $this->recurso->events()->where('fechaEvento','=',$fechaEvento)->get();
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
			$puesto->espacio_id = 0;
			$puesto->save();
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

	public function update($data){
		$idrecursocontenedor = $this->recurso->id;
		//para cada uno de los puestos
		foreach ($this->recurso->puestos as $item) {
			//si cambia el tipo
			if ($data['tipo'] != $this->recurso->tipo){
				$item->tipo = Config::get('options.equipo');
				$item->tipoequipo_id = $idrecursocontenedor;
				$item->espacio_id = 0;
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
		return true;
	}	



	}	
?>