<?php

class sgrWeek {

	private $dias;


	/**
	*	@param $day
	*	@param $numMes
	*	@param $year
	*/
	function __construct($day = '',$month = '',$year = ''){

		if (empty($day)) $day = date('d');//dia actual
		if (empty($month)) $month = date('m');//mes actual
		if (empty($year)) $year = date('Y');//año actual

		//timeStamp del lunes de la semana elegida por el usuario
		$timestamplunes = sgrDate::timestamplunesanterior($day,$month,$year);
		
		for($i=0;$i<7;$i++) $this->dias[$i] = new sgrDia(strtotime('+ '.$i.' days',$timestamplunes));
	}

	/**
	 * Devuelve el objeto sgrDia cuyo numero de dia es $numDia si existe, en caso contrario devuelve false
 	 * 
 	 * @param $numDia int Número del día la semana 0->lunes,..., 6->domingo
 	 * @return Obj sgrDia | false
	*/
	public function dia($numDia){
		
		if (isset($this->dias[$numDia])) return $this->dias[$numDia];
		return false;
		
	}

}

?>