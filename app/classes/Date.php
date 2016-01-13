<?php

class Date{
	
	/**
	 * calcula el número de horas de una reserva: diferencia entre horaInicio y horaFin
 	 * 
 	 * @param $h1 string formato H:m
 	 * @param $h2 string formato H:m
 	 * @return $diff int número de horas de una reserva: diferencia entre $h1 y $h2
	*/
	public static function diffHours($h1,$h2){ 
	    //In: $h1,$h2 -> horas en formato H:m:s
	    $tsh1 = strtotime($h1); //número de segundos desde 1 enero de 1970
	    $tsh2 = strtotime($h2); //número de segundos desde 1 enero de 1970

	    $diff = ($tsh2 - $tsh1) / (60 * 60) ; //diferencia en horas
		
		return $diff;

		}  
	
	/**
	 * Devuelve la fecha en formato Y-m-d del día siguiente a $fecha
 	 * 
 	 * @param $fecha string fecha en formato Y-m-d
 	 * @return $fechadiasiguiente string fecha en formato Y-m-d
	*/
	public static function nextDay($fecha){
		$fechadiasiguiente = '';

		$fechadiasiguiente = date('Y-m-d',strtotime('+1 day',strtotime($fecha)));

		return $fechadiasiguiente;
	}

	/**
	 * Devuelve la fecha en formato Y-m-d del día anterior a $fecha
 	 * 
 	 * @param $fecha string fecha en formato Y-m-d
 	 * @return $fechadiaanterior string fecha en formato Y-m-d
	*/
	public static function prevDay($fecha){
		$fecha = '';

		$fechadiaanterior = date('Y-m-d',strtotime('-1 day',strtotime($fecha)));

		return $fechadiaanterior;
	}

	/**
	 * Devuelve timestamp de $fecha con $formato
 	 * 
 	 * @param $fecha string fecha
 	 * @param $formato string indica el formato de $fecha (por ejemplo d-m-Y) 
 	 * @return $timestamp int Timestamp de $fecha
	*/
	public static function gettimestamp($fecha,$formato){

		$timestamp = '';
		$date = DateTime::createFromFormat($formato,$fecha);
		$timestamp = $date->getTimestamp();
		return $timestamp;
	}
	
	
  
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
					
		$startTime = strtotime($aDaysWeek[$dWeek],$self->gettimestamp($fInicio,'d-m-Y'));
		$endTime = $self->gettimestamp($fFin,'Y-m-d');
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
		$startTime = strtotime($aDaysWeek[$dWeek],$self->gettimestamp($f,'d-m-Y'));
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
		$currentTime = strtotime('+ '.$numRepeticion.' Week',$self->gettimestamp($fInicio,'d-m-Y'));
		$fecha = date('j-n-Y',$currentTime);
		return $fecha;
	
	}

	public static function compareDate($date1,$date2){
		//format $date1=$date2 = d-m-Y
		$result = '';
		$self = new self();
		$format = 'd-m-Y';
		if ($self->gettimestamp($date1,'d-m-Y') < $self->gettimestamp($date2,$format)) $result = -1;
		else if ($self->gettimestamp($date1,$format) == $self->gettimestamp($date2,$format)) $result = 0;
		else if ($self->gettimestamp($date1,$format) > $self->gettimestamp($date2,$format)) $result = 1;
		//return   -1 -> 	$date1 < $date2
		//			0 -> 	$date1 = $date2
		//			1 -> 	$date1 > $date2
		return $result;
	}

	//Devuelve el 1->lunes,.... 7-> domingo
	public static function getDayWeek($fecha){
		$day = '';

		$self = new self();
		$stamp = $self->gettimestamp($fecha,'d-m-Y');
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
	//devuelve el número del mes (dos dígitos) de la fecha actual (hoy)
	public static function currentMonth(){
		return date('n');
	}
	//devuelve el año (cuatro dígitos) del la fecha actual (hoy)
	public static function currentYear(){
		return date('Y');
	}
}
?>