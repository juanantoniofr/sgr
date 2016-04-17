<?php

	class sgrEspacio implements sgrInterfaceRecurso{

	private $recurso;
	private $puestos;

	public function __construct(){
		$this->recurso = new Recurso;
	}

	public function recurso(){
			return $this->recurso;
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
		$this->puestos = $this->recurso->puestos;
	}
	
	public function getEvents($fechaEvento){

			if ($this->recurso->puestos->count() > 0){
				foreach($this->recurso->puestos as $puesto)	$id_puestos[] = $puesto->id;
  		  return Evento::whereIn('recurso_id',$id_puestos)->where('fechaEvento','=',$fechaEvento)->groupby('evento_id')->orderby('horaInicio')->orderby('horaFin','desc')->get();
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
			$puesto->delete();
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
		return $this->recurso->update($data);
	}

	public function add($data){
		foreach ($data as $key => $value) {
			$this->recurso->$key = $value;
		}
		return true;
	}	



	}	
?>