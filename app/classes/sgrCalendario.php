<?php

class sgrCalendario {
	
	private $numeroMes;
	private $nombreMes;
	private $year;
	private $ultimodiasmes;
	private $diasMes;
	
	
	

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

		return $this->ultimodiasmes;
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
		/*$diasmes = array();


		$timestamp = mktime(0,0,0,$month,1,$year);
		$maxday = date("t",$timestamp); // número de días de $month
		$thismonth = getdate($timestamp); //$thismonth = array con información sobre la fecha $timestamp
		
		// día de la semana en la que se inicia el mes $month.  siendo: 0 -> lunes, 1 -> martes,...., 6 -> domingo
		$startday = $thismonth['wday'] - 1 ;
		if ( $startday == -1 )	$startday = 6;
		
		$j = 0; //inic. indice semana del mes $month
		$i = 0; //inic. indice dia de la semana
		for ($currentDay=0; $currentDay<($maxday+$startday); $currentDay++) {
    		
    		if( $currentDay != 0 && ($currentDay % 7) == 0 ){
    			$j++; // inc indice de semana
    			$i = 0; // inicia indice días de nueva semana
    		} 
    		if($currentDay < $startday){
    			$dia = 0;
    			//$festivo = false;
    			$diasmes[$j][$i] = new sgrDia($dia,'hola radiola...');	
    		} 
    		else {
    			$dia = $currentDay - $startday + 1;
    			$diasmes[$j][$i] = new sgrDia($dia,$this->esFestivo($dia));
    		}
    		$i++; //inc indice día semana en curso ($j)
    	}

    	//completar última semana con ceros los días que no son del mes $month
    	$numDaysLastWeek = count($diasmes[$j]);
    	$inc = 1;
    	if ( $numDaysLastWeek < 7 ){
    		while ( $numDaysLastWeek < 7) {
    			$dia = $maxday + $inc;
    			$diasmes[$j][$numDaysLastWeek] = new sgrDia(0,$this->esFestivo(0));
    			$inc++;
    			$numDaysLastWeek++;	
    		} 
    	}

    	return $this->diasMes = $diasmes;*/
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

		$timestamp = strtotime('1970-'.$month.'-1');
		$mes = ucfirst(strftime('%B',$timestamp));
		return $this->nombreMes = $mes;
	}
}

?>