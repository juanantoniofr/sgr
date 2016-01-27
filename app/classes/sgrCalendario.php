<?php

class sgrCalendario {
	
	private $numeroMes; //int numero del mes entre 1 y 12
	private $nombreMes; //string nombre del mes en español
	private $year;	//int año cuatro dígitos
	private $ultimodiasmes; //int timestamp
	private $sgrWeeks; //array de objetos sgrWeek
	
	
	/**
	*	@param $numMes
	*	@param $year
	*/
	function __construct($numMes = '',$year = ''){
		
		if (empty($numMes)) $numMes = date('m');//mes actual
		if (empty($year)) $year = date('Y');//año actual
		

		//$this->setNumeroMes($numMes);
		$this->numeroMes = (int) $numMes;
		//$this->setYear($year);
		$this->year = (int) $year;
		$this->setUltimoDiaMes();//28|29|30|31
		$this->setNombreMes($numMes);//establece el nombre del mes en español
		//$this->setDias();//construye un array con los días del mes
		$this->setSemanas();

		
		return $this;
	}

	//public functions
	public function sgrWeeks(){
		return $this->sgrWeeks;
	}

	/**
	*	@param $timestamp int timestamp fecha
	*	@return $sgrWeek array object srgDia || false si timestamp no es de $this
	*/
	public function sgrWeek($timestamp = 0){

		foreach ($this->sgrWeeks as $sgrWeek) {
			foreach ($sgrWeek->sgrDays() as $sgrDia) {
				if ($timestamp == $sgrDia->timestamp()) return $sgrWeek;
			}
		}
		
		return false;//no existe la semana que contenga $timestamp
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
	/**
	*	 Genera un array de objetos sgrWeek (semanas del mes)
	*/
	private function setSemanas(){
		
		$semanas = array();
		$day = 1;
		$i=0;
		$timestamp = mktime(0,0,0,(int) $this->numeroMes,1,(int) $this->year);
		$maxday = date("t",$timestamp);
		while ($day <= $maxday){
			$semanas[$i] =  new sgrWeek((int) $day,(int) $this->numeroMes,(int) $this->year);
			$day = $day + 7;
		 	$i = $i + 1;
		} 
		
		return $this->sgrWeeks = $semanas;
	}

	private function setUltimoDiaMes(){
		
		return $this->ultimodiasmes = (int) date('t', mktime(0,0,0,$this->numeroMes,1,$this->year));
	}
	

	/*private function setNumeroMes($numeroMes){
		if (empty($numeroMes)) $numeroMes = date('m');
		return $this->numeroMes = (int) $numeroMes;
	}*/

	/*private function setYear($year){
		if (empty($year)) $year = date('Y');
		return $this->year = (int) $year;
	}*/

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