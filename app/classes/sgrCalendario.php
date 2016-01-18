<?php

class sgrCalendario {
	
	private $numeroMes;
	private $nombreMes;
	private $year;
	private $ultimodiasmes;
	private $diasMes;
	
	
	/*

		$eventos = '';

		if (!empty($id_recurso) && !empty($numMes) && !empty($numMes)) {
			$recurso = Recurso::findOrFail($id_recurso);
			$eventos = $recurso->events()->where('fechaEvento','<=','Y-m-'.date('t', mktime(0,0,0,(int) $numMes,1,(int) $year)))->where('fechaEvento','>=',date('Y-m-d',strtotime($year.'-'.$numMes.'-1')))->get();
			$this->numMes = $numMes;
			$this->year = $year;
		} 		
	*/
	
	/**
	*	@param $numMes
	*	@param $year
	*/
	function __construct($numMes = '',$year = ''){
		
		if (empty($numMes)) $numMes = date('m');//mes actual
		if (empty($year)) $year = date('Y');//año actual
		

		$this->setNumeroMes($numMes);
		$this->setYear($year);
		$this->setUltimoDiaMes();//28|29|30|31
		$this->setNombreMes($numMes);//establece el nombre del mes en español
		$this->setDias();//construye un array con los días del mes


		
		return $this;
	}

	//public functions
	
	public function dias(){
		
		return $this->diasMes;
	}

	public function numeroMes(){
		
		return $this->numeroMes;
	}

	public function getYear(){
		
		return $this->year;
	}

	public function nombreMes(){
		
		return $this->nombreMes;
	}

	public function ultimoDia(){

		return (int) $this->ultimodiasmes;
	}

	/**
	 * Devuelve el objeto sgrDia cuyo numero de dia es $numDia si existe, en caso contrario devuelve false
 	 * 
 	 * @param $numDia int Número del día mes [1-31]
 	 * @return Obj sgrDia | false
	*/
	public function dia($numDia){
		
		if (array_key_exists($numDia, $this->diasMes))	return $this->diasMes[$numDia];
		return false;
	}


	//private functions
	private function setUltimoDiaMes(){
		
		return $this->ultimodiasmes = (int) date('t', mktime(0,0,0,$this->numeroMes,1,$this->year));
	}
	

	private function setNumeroMes($numeroMes){
		if (empty($numeroMes)) $numeroMes = date('m');
		return $this->numeroMes = (int) $numeroMes;
	}

	private function setYear($year){
		if (empty($year)) $year = date('Y');
		return $this->year = (int) $year;
	}

	/**
	 * Genera un array con indice los días del mes y valor objetos sgrDia
 	 * 
 	 * @return $diasmes array 
	*/
	private function setDias(){

		// Falta por escribir la función validDate
		// if (!validDate($month,$year)) return false;

		$diasmes = array();
		$timestamp = mktime(0,0,0,$this->numeroMes,1,$this->year);
		$maxday = date("t",$timestamp); // número de días de $month
		for($i=1;$i<=$maxday;$i++) {
			$timestamp = mktime(0,0,0,$this->numeroMes,$i,$this->year);
			$diasmes[$i] = new sgrDia($timestamp);
		}

		return $this->diasMes = $diasmes;
		
	}
	
	/**
	 * Establece el nombre del mes en español
 	 * 
 	 * @param $month int Número del mes, 1=enero... 12=diciembre
 	 * @return $mes string Mes en español
	*/
	private function setNombreMes ($month = ''){

		$mes = '';
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$nombremes="Error setlocale";}
		$m = (int) $month;	  		
		$timestamp = strtotime('1970-'.$m.'-1');
		$mes = ucfirst(strftime('%B',$timestamp));
		return $this->nombreMes = $mes;
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