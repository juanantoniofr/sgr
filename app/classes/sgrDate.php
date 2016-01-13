<?php

class sgrDate{

	/**
	 * Devuelve timestamp del lunes inmediatamente anterior a fecha=$day-$month-$year
 	 * 
 	 * @param $day string
 	 * @param $month string
 	 * @param $year string
 	 * @return $timestamp int timestamp lunes inmediatamente anterior a $day-$month-$year
	*/
	public static function timestamplunesanterior($day,$month,$year){
		
		$timestamp = '';
		
		$time = mktime(0,0,0,$month,$day,$year);
		if (1 == date('N',$time)) $timestamp = $time;
		else {
			do {
				$time = strtotime('-1 day', $time);
			} while(date('N',$time)!=1);
			$timestamp = $time;
		} 
		return $timestamp;
	}
	/**
	* Devuelve una abreviatura de tres letras del día de la semana en español
	* @param $month número del mes
	* @return $abreviatura 
	*/
	public static function abrDiaSemana($timestamp){

		$abreviatura = '';
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$abreviatura="Error setlocale";}
		
		$abreviatura = ucfirst(strftime('%a',$timestamp));
		return $abreviatura;
	}
}
?>