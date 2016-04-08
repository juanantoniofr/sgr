<?php

class sgrWeek {

	private $sgrDias;
	
	/**
	*	@param $day
	*	@param $numMes
	*	@param $year
	*/
	function __construct($sgrRecurso,$day = '',$month = '',$year = ''){

		if (empty($day)) $day = date('d');//dia actual
		if (empty($month)) $month = date('m');//mes actual
		if (empty($year)) $year = date('Y');//año actual

		//timeStamp del lunes 
		$timestamplunes = $this->timestamplunesanterior((int) $day,(int) $month,(int) $year);
		
		//sgrDias
		for($i=0;$i<7;$i++) {
			$currentday = strtotime('+ '.$i.' days',$timestamplunes);
			$eventos = $sgrRecurso->recurso()->events->filter(function($event) use ($currentday){
				return $event->fechaEvento == date('Y-m-d',$currentday);
				});
			$this->sgrDias[$i] = new sgrDia(strtotime('+ '.$i.' days',$timestamplunes),$eventos);
		}
	}

	/**
	 * Devuelve el objeto sgrDia cuyo numero de dia de la semana es $numDia si existe, en caso contrario devuelve false
 	 * 
 	 * @param $numDia int Número del día la semana 0->lunes,..., 6->domingo
 	 * @return Obj sgrDia | false
	*/
	public function dia($numDia){
		
		if (isset($this->sgrDias[$numDia])) return $this->sgrDias[$numDia];
		return false;
		
	}

	/**
	* @return array object sgrDia
	*/
	public function sgrDays(){
		return $this->sgrDias;
	}


	/**
	 * Devuelve timestamp del lunes inmediatamente anterior a fecha=$day-$month-$year
 	 * 
 	 * @param $day string
 	 * @param $month string
 	 * @param $year string
 	 * @return $timestamp int timestamp lunes inmediatamente anterior a $day-$month-$year
	*/
	private function timestamplunesanterior($day,$month,$year){
		
		$timestamp = '';
		
		$time = mktime(0,0,0,(int) $month,(int) $day,(int) $year);
		if (1 == date('N',$time)) $timestamp = $time;
		else {
			do {
				$time = strtotime('-1 day', $time);
			} while(date('N',$time)!=1);
			$timestamp = $time;
		} 
		return $timestamp;
	}
	

}

?>