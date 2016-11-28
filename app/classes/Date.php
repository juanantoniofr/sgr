<?php

class Date{
	
	/**
     * Devuelve $fecha en formato $farmatosalida
     * 
     * @param $fecha datetime 
     * @param $formatoentrada string formato de entrada de $fecha
     * @param $formatosalida string formato de salida para $fecha
     * @return $result datetime formateado según $formatosalida
    */
    public static function parsedatetime($fecha,$formatoentrada,$formatosalida){
         
        $result = '';
 
        $date = DateTime::createFromFormat($formatoentrada,$fecha);
        $result = $date->format($formatosalida);
        return $result;
    }

    
	public static function diffHours($h1,$h2){ 
	    //In: $h1,$h2 -> horas en formato H:m:s
	    $tsh1 = strtotime($h1); //número de segundos desde 1 enero de 1970
	    $tsh2 = strtotime($h2); //número de segundos desde 1 enero de 1970

	    $diff = ($tsh2 - $tsh1) / (60 * 60) ; //diferencia en horas
		
		return $diff;

		}  
	
	public static function nextDay($currentDate){
		$nextDay = '';

		$self = new self();
		$nextDayTimeStamp = strtotime('+1 day',$self->getTimeStampEN($currentDate,'-'));
		$nextDay = date('Y-m-d',$nextDayTimeStamp);
		return $nextDay;
	}

	public static function prevDay($currentDate){
		$prevDay = '';

		$self = new self();
		$prevDayTimeStamp = strtotime('-1 day',$self->getTimeStampEN($currentDate,'-'));
		$prevDay = date('Y-m-d',$prevDayTimeStamp);
		return $prevDay;
	}

	public static function dateToHuman($strDate,$format = 'EN',$delimiter = '-'){
		$humanDate = '';
		$error = false;
		$self = new self();
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$error = true;}
		if(!$error){
		switch ($format) {
			case 'EN':
					$timestamp = $self->getTimeStampEN($strDate,$delimiter);
					$humanDate = ucfirst(strftime('%a %d de %b',$timestamp));// . $nameMonth;
				break;
			default:
				$humanDate = 'Error al generar fecha para humanos.....';
				break;
		}
		}
		else $humanDate = "error al establecer locales";
		return $humanDate;
	}
	
	/*
		Recibe una fecha en formato ES (d-m-Y)
		devuelve el timeStamp correspondiente a esa fecha.
	*/
	public static function getTimeStamp($fecha,$delimiter = '-'){

		$f = explode($delimiter,$fecha);
		//formato: mktime(hours,minutes,segundos,mes,día,año);
		$result = mktime(0,0,0,$f[1],$f[0],$f[2]);

		return $result;
	}
	
	public static function getTimeStampEN($fecha_EN,$delimiter='-'){

		$f = explode($delimiter,$fecha_EN);
		//formato: mktime(hours,minutes,segundos,mes,día,año);
		$result = mktime(0,0,0,$f[1],$f[2],$f[0]);

		return $result;
	}

	public static function timeStamp($day,$mon,$year){
		return mktime(0,0,0,$mon,$day,$year);
	}

	public static function timefirstMonday($day,$month,$year){
		$timefirstMonday = '';
		
		$time = mktime(0,0,0,$month,$day,$year);
		if (1 == date('N',$time)) $timefirstMonday = $time;
		else {
			do {
				$time = strtotime('-1 day', $time);
			} while(date('N',$time)!=1);
			$timefirstMonday = $time;
		} //$timefirstMonday = strtotime('previous monday'. $year .'-'.$month .'-'.$day,$time);

		return $timefirstMonday;
	}

	   
	/*
		Recibe como entrada una fecha en formato ES: dia # mes # año
	 	donde # es el delimitador que puede ser / o -

	 	devuelve la fecha en formato para mysql: año (cuatro cifras) - mes (01-12) - dia (01-31)
	 */
	public static function toDB($fecha_ES,$delimiter = '-'){
		/*
		In: $fecha_ES = fecha en formato dd?mm?yy donde es $delimiter
			$delimiter = delimitador, normalmente - o /
		Out: $result = fecha en formato EN yy-mm-dd
		*/
		$self = new self();
		$timeStamp = $self->getTimeStamp($fecha_ES,$delimiter);// mktime(0,0,0,$items[1],$items[0],$items[2]); 
		
 		$result = date('Y-m-d',$timeStamp); // ej: 2014-09-01
		return $result;
	}

	public static function datetoES($fecha_EN,$delimiter = '-'){
		
		$self = new self();
		$timeStamp = $self->getTimeStampEN($fecha_EN,$delimiter);// mktime(0,0,0,$items[1],$items[0],$items[2]); 
		$result = date('d-m-Y',$timeStamp); // ej: 01-09-2014
		return $result;
	}
	
	//	Devuelve la representación textual de $month->(1-12)
	public static function getNameMonth ($month = '',$year = ''){

		$nameNonth = '';
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$nameNonth="error setlocale";}
		
		$time = mktime(0,0,0,$month,1,$year);
		$nameNonth = ucfirst(strftime('%B',$time));

		return $nameNonth;
	}

	public static function getstrHour($hour){
		$aHour = explode(':',$hour);
		$time = mktime($aHour[0],$aHour[1]);
		return date('G:i',$time);
	}

	/*Recibe:
				$month: numérico (1-12)
				$year: cuatro dígitos
	devuelve un array de semanas, donde cada semana es un array de días, con el siguiente formato:

		week[j][i] 	-> si es igual a 0 entonces el dia i de la semana j no pertenece al mes
					-> si tiene un valor entre (1-31), entonces el día i de la semana j pertenece al mes
		valores de i:
			i = 1 -> lunes,
			i = 2 -> martes,
			i = 3 -> miércoles,
			i = 4 -> jueves,
			i = 5 -> viernes,
			i = 6 -> sabado,
			i = 7 -> domingo,*/
	public static function getDays($month,$year){

		// Falta por escribir la función validDate
		// if (!validDate($month,$year)) return false;

		$daysMonth = array();

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
    		if($currentDay < $startday) $daysMonth[$j][$i] = 0;
    		else $daysMonth[$j][$i] = $currentDay - $startday + 1;
    		$i++; //inc indice día semana en curso ($j)
    	}

    	//completar última semana con ceros los días que no son del mes $month
    	$numDaysLastWeek = count($daysMonth[$j]);
    	$inc = 1;
    	if ( $numDaysLastWeek < 7 ){
    		while ( $numDaysLastWeek < 7) {
    			$daysMonth[$j][$numDaysLastWeek] = $maxday + $inc;
    			$inc++;
    			$numDaysLastWeek++;	
    		} 
    	}

    	return $daysMonth;
	}

	public static function daysMonth($month,$year){
		$timestamp = mktime(0,0,0,$month,1,$year);
		$daysMonth = date("t",$timestamp);
		return $daysMonth;
	}

	public static function isDomingo($day,$mon,$year){
		$isDomingo = false;
		if (date('N',mktime(0,0,0,$mon,$day,$year)) == '7') $isDomingo = true; 
		return $isDomingo;
	}

	public static function isSabado($day,$mon,$year){
		$isSabado = false;
		if (date('N',mktime(0,0,0,$mon,$day,$year)) == '6') $isSabado = true; 
		return $isSabado;
	}

	public static function isPrevToday($day,$mon,$year){
		$isPrevToday = false;

		setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
		
		$today = strtotime('today');//time();
		$fecha = mktime(0,0,0,$mon,$day,$year);
		if ($fecha < $today) $isPrevToday = true;

		return $isPrevToday;
	}

	public static function isPrevTodaybyTimeStamp($timestamp){
		$isPrevToday = false;

		setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
		
		$today = time();
		if ($timestamp < $today) $isPrevToday = true;

		return $isPrevToday;
	}

	/*
		Params:
			in -> 	$fInicio:	fecha en formato dd-mm-yyyy
					$fFin:		fecha en formato dd-mm-yyyy
					$dWeek:		día de la semana en formato 0->domingo,1->lunes,.... 6->sábado
			out -> $numRepeticiones: Entero con el número de veces que se repite $dWeek entre $fInicio y $fFin 
	*/
	public static function numRepeticiones($fInicio,$fFin,$dWeek){
		
		$numRepeticiones = 0;
		$aDaysWeek = array('0' => 'Sunday', '1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday');
		$self = new self();
					
		$startTime = strtotime($aDaysWeek[$dWeek],$self->getTimeStamp($fInicio,'-'));
		$endTime = $self->getTimeStamp($fFin,'-');
		$currentTime = $startTime;
		//$nextTime = strtotime('Next ' . $aDaysWeek[$dWeek],$currentTime);
		//if ($startTime == $self->getTimeStamp($fInicio,'-')) $numRepeticiones++;
		if ($startTime <= $endTime){
			do {
				$numRepeticiones++;
				$nextTime = strtotime('Next ' . $aDaysWeek[$dWeek],$currentTime);
				$currentTime = $nextTime;
			} while($nextTime <= $endTime);	
		}
		//if ($endTime == $self->getTimeStamp($fFin,'-')) $numRepeticiones++;
		//echo $numRepeticiones;
		return $numRepeticiones;
	}

	//Return date with format (dia-mes-año) for frist day of week "dWeek" last of date "$f"
	public static function timeStamp_fristDayNextToDate($f,$dWeek){
		$aDaysWeek = array('0' => 'Sunday','1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday');
		$self = new self();
		$startTime = strtotime($aDaysWeek[$dWeek],$self->getTimeStamp($f,'-'));
		return date('j-n-Y',$startTime);
	}
	
	/*
		Params:

			In 	-> 	$fInicio:		fecha en formato dd-mm-yyyy
					$numRepeticion:	Número enterno mayor que cero

			Out ->	$fecha: 		fecha en formato dd-mm-yyyy. Valor de la fecha de la repetición n-esima a partir de $fInicio	
	*/
	public static function currentFecha($fInicio,$numRepeticion){
				
		$self = new self();
		if ($numRepeticion == 0) return $fInicio;
		$currentTime = strtotime('+ '.$numRepeticion.' Week',$self->getTimeStamp($fInicio,'-'));
		$fecha = date('j-n-Y',$currentTime);
		return $fecha;
	
	}

	public static function compareDate($date1,$date2){
		//format $date1=$date2 = d-m-Y
		$result = '';
		$self = new self();
		if ($self->getTimeStamp($date1) < $self->getTimeStamp($date2)) $result = -1;
		else if ($self->getTimeStamp($date1) == $self->getTimeStamp($date2)) $result = 0;
		else if ($self->getTimeStamp($date1) > $self->getTimeStamp($date2)) $result = 1;
		//return   -1 -> 	$date1 < $date2
		//			0 -> 	$date1 = $date2
		//			1 -> 	$date1 > $date2
		return $result;
	}

	//Devuelve el 1->lunes,.... 7-> domingo
	public static function getDayWeek($fecha){
		$day = '';

		$self = new self();
		$stamp = $self->getTimeStamp($fecha);
		$day = date('N',$stamp);

		return $day; 
	}

	public static function getStrDayWeek($fecha){
		$str = '';
		setlocale(LC_TIME,'es_ES@euro','es_ES.UTF-8','esp');	
		$str = ucfirst(strftime('%A',strtotime($fecha)));

		return $str;
	}


	public static function DaysWeekToStr($aNumDays){
		$strDaysWeek = '';
		$aDaysWeek = array('1' => 'Lunes','2' => 'Martes','3' => 'Miércoles','4' => 'Jueves','5' => 'Viernes','6' => 'Sábado','7' => 'Domingo');

		//setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
		$numdays = count($aNumDays);
		$cont = 0;
		foreach ($aNumDays as $value) {
			$strDaysWeek .= $aDaysWeek[$value];
			$cont++;
			if ($cont < $numdays) $strDaysWeek .= ', ';
		}


		return $strDaysWeek;
	}

	public static function dateCSVtoDB($date){
		//Esperamos de entrada fecha en formato dd-mesAbr(3)-yyyy, ejemplo 01-ene-2015

		$mifecha = explode('-',$date);
		$dia = $mifecha[0];
		$mes = strtolower($mifecha[1]);
		$anno = $mifecha[2];

		$translateMonth = array('ene'	=>	'01',
								'feb'	=>	'02',
								'mar'	=>	'03',
								'abr'	=>	'04',
								'may'	=>	'05',
								'jun'	=>	'06',
								'jul'	=>	'07',
								'ago'	=>	'08',
								'sep'	=>	'09',
								'oct'	=>	'10',
								'nov'	=>	'11',
								'dic'	=>	'12',);

		$numMes = $translateMonth[$mes];
		$timeStamp = mktime(0,0,0,$numMes,$dia,$anno);
		$fechaDB = date('Y-m-d',$timeStamp);

		return $fechaDB;
	}

	public static function dateCSVtoSpanish($date){
		//Esperamos de entrada fecha en formato dd-mesAbr(3)-yyyy, ejemplo 01-ene-2015

		$mifecha = explode('-',$date);
		$dia = $mifecha[0];
		$mes = strtolower($mifecha[1]);
		$anno = $mifecha[2];

		$translateMonth = array('jan'	=>	'01',
								'feb'	=>	'02',
								'mar'	=>	'03',
								'apr'	=>	'04',
								'may'	=>	'05',
								'jun'	=>	'06',
								'jul'	=>	'07',
								'aug'	=>	'08',
								'sep'	=>	'09',
								'oct'	=>	'10',
								'nov'	=>	'11',
								'dec'	=>	'12',);

		$numMes = $translateMonth[$mes];
		$timeStamp = mktime(0,0,0,$numMes,$dia,$anno);
		$fechaDB = date('d-m-Y',$timeStamp);

		return $fechaDB;
	}

	public static function sgrStrftime($format,$date){
		
		//'%A, %d de %B de %Y'
		//$date = $event->fechaInicio;
		setlocale(LC_ALL,'es_ES@euro','es_ES.UTF-8','esp');
		$result = ucfirst(strftime($format,strtotime($date)));
		return $result;
	}

	public static function sgrdiassemana($aDias){
		//texto para días semana
		$diasSemana = array('1'=>'lunes','2'=>'martes','3'=>'miércoles','4'=>'jueves','5'=>'viernes','6'=>'sabado','7'=>'domingo');
		$dias = explode(',',str_replace(array('[',']','"'), '' , $aDias));
		$str = '';
		$cont = 0;
		for($j = 0;$j < count($dias) - 1;$j++){
			if (count($dias) == 2)
			$str .= $diasSemana[$dias[$j]] . ' y ';
			else
			$str .= $diasSemana[$dias[$j]] . ', ';
			$cont++;
		}
		$str .= $diasSemana[$dias[$cont]];
		return $str;
	}

	//devuelve el día del mes (dos dígitos) de la fecha actual (hoy)
	public static function currentDay(){
		return date('d');
	}
	//devuelve el número del mes (cuatro dígitos) de la fecha actual (hoy)
	public static function currentMonth(){
		return date('n');
	}
	//devuelve el año (cuatro dígitos) del la fecha actual (hoy)
	public static function currentYear(){
		return date('Y');
	}
}
?>