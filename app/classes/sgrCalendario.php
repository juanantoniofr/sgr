<?php

class sgrCalendario {
	
	private $fecha;						//objeto DateTime (php) || empty
	private $sgrRecurso;			//Objeto sgrRecurso 
	private $currentDay;			//timestamp	
	private $sgrWeeks; 				//array de objetos sgrWeek

	
	
	/**
		*	@param $fecha 			DateTime
		* @param $sgrRecurso 	sgrRecurso
	*/
	function __construct($fecha,$sgrRecurso){
		
		//sgrRecurso
		$this->sgrRecurso = $sgrRecurso;
		
		//DateTime
		//if (empty($fecha)) $this->fecha = new DateTime();
		//else $this->fecha = $fecha;
		$this->fecha = $fecha;
		//timestamp
		$this->currentDay 	= $this->fecha->getTimestamp();
		
		//sgrWeeks
		$this->setSemanas();
		return $this;
	}

	//$fecha debe ser un objeto DateTime
	public function setFecha($fecha){
		$this->fecha = $fecha;
		}

	public function dia(){
		return $this->fecha->format('d');
		}

	public function mes(){
		return $this->fecha->format('m');
		}

	public function year(){
		return $this->fecha->format('Y');
		}

	public function fecha(){
		return $this->fecha;
		}

	public function sgrRecurso(){
		return $this->sgrRecurso;
		}

	/**
		*	
		*	@return $sgrWeek array object srgDia 
	*/
	public function sgrWeek(){

		foreach ($this->sgrWeeks as $sgrWeek) {
			foreach ($sgrWeek->sgrDays() as $sgrDia) {
				if ($this->fecha->getTimestamp() == $sgrDia->timestamp()) return $sgrWeek;
			}
		}
		
		return false;//no existe la semana que contenga $this->fecha->getTimestamp()
	}

	//public functions
	public function sgrWeeks(){
		return $this->sgrWeeks;
	}
	
	public function nombreMes(){
		$nombreMes = '';
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$nombreMes="Error setlocale";}
		$m = $this->fecha->format('m');	  		
		$timestamp = strtotime('1970-'.$m.'-1');
		$nombreMes = ucfirst(strftime('%B',$timestamp));
		return $nombreMes;
	}

	public function ultimoDia(){
		return (int) date('t', mktime(0,0,0,$this->fecha->format('m'),1,$this->fecha->format('Y')));
	}
	
	/**
	 * Devuelve el objeto sgrDia cuyo numero de dia es $numDia si existe, en caso contrario devuelve false
 	 * 
 	 * @param $numDia int Número del día mes [1-31]
 	 * @return Obj sgrDia | false
	*/
	public function sgrDia($numDia){
		
		if (array_key_exists($numDia, $this->diasMes))	return $this->diasMes[$numDia];
		return false;
	}


	//private functions
	/**
	*	 Genera un array de objetos sgrWeek (semanas del mes)
	*/
	private function setSemanas(){
		
		$semanas = array();
		$day = 1;
		$i=0;
		$timestamp = mktime(0,0,0,(int) $this->fecha->format('m'),1,(int) $this->fecha->format('Y'));
		$maxday = date("t",$timestamp);
		$semanas[$i] =  new sgrWeek($this->sgrRecurso,(int) $day,(int) $this->fecha->format('m'),(int) $this->fecha->format('Y'));
		do {
			$day = $day + 7;
		 	$i = $i + 1;
		 	$semanas[$i] =  new sgrWeek($this->sgrRecurso,(int) $day,(int) $this->fecha->format('m'),(int) $this->fecha->format('Y'));
		} while ($day <= $maxday);
		
		return $this->sgrWeeks = $semanas;
	}

	
	//static functions

	/**
	 *
	 *		@param void 
	 *		@return  $l timestamp del lunes de la primera semana reservable a partir del día actual
	*/
	public static function fristMonday(){
		
		$l = '';
		//Parámetros
		$lastDay = Config::get('options.ant_ultimodia'); //por defecto el jueves (dia 4 de la semana)
		$n = Config::get('options.ant_minSemanas'); 
		//día actual
		$today = date('Y-m-d');
		$numWeekCurrentDay = date('N');//,strtotime($today));//1 lunes,... 7 domingo
		
		//Si es de lunes a jueves 
		if ($numWeekCurrentDay <= $lastDay){
	   		// y si la fecha de realización de la reserva está entre las fechas de la semana siguiente a la actual
	   		$l = strtotime('next monday ' . $today ); //lunes semana siguiente
		}
		else {
			// y si la reserva está entre las fechas de 2ª semana posterior a la actual 
			$l = strtotime('next monday ' . $today .' +1 week'); //lunes de la 2ª semana siguiente
	   	}

	   return $l;
	
	}

	/**
	 *		@param void 
	 *		@return  $v timestamp del viernes de la primera semana reservable a partir del día actual
	*/
	
	public static function lastFriday(){

		$v = '';
		//Parámetros
		$lastDay = Config::get('options.ant_ultimodia'); //por defecto el jueves (dia 4 de la semana)
		$n = Config::get('options.ant_minSemanas'); 
		//día actual
		$today = date('Y-m-d');
		$numWeekCurrentDay = date('N');//,strtotime($today));//1 lunes,... 7 domingo
		
		//Si es de lunes a jueves 
		if ($numWeekCurrentDay <= $lastDay){
		   // y si la fecha de realización de la reserva está entre las fechas de la semana siguiente a la actual
		   $v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes semana siguinte
		// si es viernes, sabado o domingo   
		}
		else {
		  	$v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes la n-esima semana siguinte
		}

		return $v;

	}
}

?>