<?php

class sgrDia {

	private $timestamp; //timestamp de la fecha
	private $eventos; 	//array de objetos de tipo Eventos
	private $esDomingo;
	private $esSabado;
	private $numdiames;
	private $festivo;
	private $fecha;
	
	/*function __construct($numerodia,$festivo){

		$this->numerodia = (int) $numerodia;
		$this->festivo = $festivo;
		
	}*/
	public function __construct($tsfecha){

		$this->timestamp = $tsfecha;
			
		$this->festivo = $this->setFestivo($tsfecha);
			
		$this->numdiames = date('j',$this->timestamp);

		$this->fecha = date('j-n-Y',$this->timestamp);

		return $this;
	}


	//public functions 
	public function numeroEventos(){

		//return $this->numeroEventos;
		return 0;
	}	

	public function events(){
		return array();
		//return $this->events;
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