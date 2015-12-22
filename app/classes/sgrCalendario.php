<?php

class sgrCalendario {

	/**
	 * Devuelve un array con los días del mes de $month-$year, con ceros en la primera y última fila que no son del mes:
	 *week[j][i] 	-> si es igual a 0 entonces el dia i de la semana j no pertenece al mes
	 *				-> si tiene un valor entre (1-31), entonces el día i de la semana j pertenece al mes
	 *	valores de i:
	 *		i = 1 -> lunes,
	 *		i = 2 -> martes,
	 *		i = 3 -> miércoles,
	 *		i = 4 -> jueves,
	 *		i = 5 -> viernes,
	 *		i = 6 -> sabado,
	 *		i = 7 -> domingo,
 	 * 
 	 * @param $month int Número del mes, 1=enero... 12=diciembre
 	 * @param $year int Año en cuatro dígitos
 	 * @return $diasmes array 
	*/
	public static function dias($month,$year){

		// Falta por escribir la función validDate
		// if (!validDate($month,$year)) return false;

		$diasmes = array();

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
    		if($currentDay < $startday) $diasmes[$j][$i] = 0;
    		else $diasmes[$j][$i] = $currentDay - $startday + 1;
    		$i++; //inc indice día semana en curso ($j)
    	}

    	//completar última semana con ceros los días que no son del mes $month
    	$numDaysLastWeek = count($diasmes[$j]);
    	$inc = 1;
    	if ( $numDaysLastWeek < 7 ){
    		while ( $numDaysLastWeek < 7) {
    			$diasmes[$j][$numDaysLastWeek] = $maxday + $inc;
    			$inc++;
    			$numDaysLastWeek++;	
    		} 
    	}

    	return $diasmes;
	}
}

?>