<?php

class sgrDia {

	private $timestamp; //timestamp de la fecha
	private $events; 	//array de objetos de tipo Eventos
	private $esDomingo;
	private $esSabado;
	private $numdiames;
	private $festivo;
	private $fecha;
	private $numMes;
	private $year;
	
	
	public function __construct($tsfecha){

		$this->timestamp = $tsfecha;
			
		$this->festivo = $this->setFestivo($tsfecha);
			
		$this->numdiames = date('j',$this->timestamp);

		$this->fecha = date('j-n-Y',$this->timestamp);

		$this->numMes = date('n',$this->timestamp);//Sin ceros iniciales

		$this->year = date('Y',$this->timestamp);//año actual
		
		return $this;
	}

	/**
	*	Devuelve un array de objetos Evento para $this en el recurso $id_recurso || en el grupo de de recurso identificados por $id_grupo
	*	@param $id_recurso int
	*	@param $id_gruppo int
	*	@return array objetos Evento
	*/
	public function events($id_recurso='',$id_grupo=''){

		
		$numMes = $this->numMes;
		$year = $this->year;
		
		$fechaEvento = date('Y-m-d',mktime(0,0,0,(int) $numMes,(int) $this->numdiames,(int) $year));

		if ($id_recurso == 0){
			//Eventos para todos los equipos//puestos para fechaEvento del id_grupo
			$events = Evento::where('fechaEvento','=',$fechaEvento)->orderBy('horaInicio','asc')->groupby('titulo')->groupby('evento_id')->get();
			return $events->filter(function($event) use ($id_grupo) {
				return $event->recursoOwn->grupo_id == $id_grupo;
			});
		}		
		else{
			return Recurso::find($id_recurso)->events()->where('fechaEvento','=',$fechaEvento)->orderBy('horaInicio','asc')->get();
		}	
		
		
	}


	public function numerodia(){
		return $this->numerodia;
	}

	public function festivo(){
		return $this->festivo;
	}

	public function timestamp(){

		return $this->timestamp;
	}
	//private
	/**
	 * Determina si el día es festivo (domingo || sábabo)
 	 * 
 	 * @param $tsfecha int timestamp 
 	 * @return true 
	*/
	private function setFestivo($tsfecha){
		
		if (date('N',$tsfecha) == '7') $this->esDomingo = true;

		if (date('N',$tsfecha) == '6') $this->esSabado = true; 
			
		if($this->esSabado || $this->esDomingo) return $this->festivo = true;
		else return $this->festivo = false;
	
	}

}
?>