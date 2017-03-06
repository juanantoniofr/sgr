<?php

class sgrDia {
	private $timestamp; //timestamp de la fecha
	private $eventos;//array de objetos de tipo Eventos
	private $sgrEventos = array(); // array de objetos tipo sgrEventos
	private $sgrRecurso;
	private $esDomingo;
	private $esSabado;
	
	private $festivo;
	private $fecha;
	private $numMes;
	private $year;
	private $numdiames;
	private $horario = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30'); //array -> intervalos horarios disponibles (por defecto de 8:30 a 21:30 en incrementos de horas completas)

	/**
		*	@param $tsfecha int timestamp 
		*	@param $horasdisponibles array intervalos horarios disponibles (por defecto de 8:30 a 21:30 en incrementos de horas completas)
	*/
	public function __construct($tsfecha = '',$sgrRecurso = '',$horario = ''){

		if(empty($tsfecha)) $tsfecha = strtotime('1970-1-1');
		$this->timestamp = $tsfecha;

		$this->sgrRecurso = $sgrRecurso;
		if (!empty($sgrRecurso)) {
			//getEventos($fini = '',$estados = array(),$ffin = '')
			$this->eventos = $this->sgrRecurso->getEventos($tsfecha,$estados = array(),$tsfecha);
			foreach ($this->eventos as $evento) {
				$this->sgrEventos[] = new sgrEvento($evento);
			}
		}

		if(!empty($horario) && is_array($horasdisponibles)) $this->horario = $horario;
			
		$this->festivo = $this->setFestivo($tsfecha);
			
		$this->numdiames = date('j',$this->timestamp);

		$this->fecha = date('j-n-Y',$this->timestamp);

		$this->numMes = date('n',$this->timestamp);//Sin ceros iniciales

		$this->year = date('Y',$this->timestamp);//año actual
		
		return $this;
	}
	
	/**
		*	Devuelve un array de objetos Evento 
		*	@return array objetos Evento
	*/
	public function events(){
		return $this->eventos;
	}

	public function sgrEventos(){
		return $this->sgrEventos;
		//$fini = $this->timestamp;
		//$ffin = $this->timestamp;
		//return $this->sgrRecurso->getEventos($fini,array(),$ffin);
	}

	public function left($sgrEvento){
		$left = 0;
		$numeroDeEventosSolapaIntervalo = 0;
		$indiceEnEventosQueSolapaIntervalo = 0;
		$eventos = $this->eventos->filter(function($evento) use ($sgrEvento){
			return ( strtotime($evento->horaInicio) < strtotime($sgrEvento->horaFin()) && strtotime($sgrEvento->horaFin()) <= strtotime($evento->horaFin)  ) || (strtotime($evento->horaInicio) <= strtotime($sgrEvento->horaInicio()) && strtotime($evento->horaFin) > strtotime($sgrEvento->horaInicio()));
		});
		
		$numeroDeEventosSolapaIntervalo = $eventos->count();

		$encontrado = false;
		$indice = 0;
		foreach ($eventos as $evento) {
			if ($evento->id != $sgrEvento->id() && !$encontrado) $indice++;
			else $encontrado = true;
		}
		if ($encontrado) $indiceEnEventosQueSolapaIntervalo = $indice;

		if (1 < $numeroDeEventosSolapaIntervalo) $razon = $numeroDeEventosSolapaIntervalo-1;
		else $razon = $numeroDeEventosSolapaIntervalo;
		$left = 90 - (( 90 / $numeroDeEventosSolapaIntervalo) * ($eventos->count() - $indiceEnEventosQueSolapaIntervalo));
		
		return floor($left);
	}

	public function width($sgrEvento){
		$width = 95;
		$numeroDeEventosSolapaIntervalo = 0;
		$indiceEnEventosQueSolapaIntervalo = 0;

		

		$eventos = $this->eventos->filter(function($evento) use ($sgrEvento){
			return ( strtotime($evento->horaInicio) < strtotime($sgrEvento->horaFin()) && strtotime($sgrEvento->horaFin()) <= strtotime($evento->horaFin)  ) || (strtotime($evento->horaInicio) <= strtotime($sgrEvento->horaInicio()) && strtotime($evento->horaFin) > strtotime($sgrEvento->horaInicio()));
		});
		
		$numeroDeEventosSolapaIntervalo = $eventos->count();

		$encontrado = false;
		foreach ($eventos as $evento) {
			if ($evento->id != $sgrEvento->id() && !$encontrado) $indiceEnEventosQueSolapaIntervalo++;
			else $encontrado = true;
		}

		//$width = 95 - (( 100 / $numeroDeEventosSolapaIntervalo) * $indiceEnEventosQueSolapaIntervalo);
		if (1 <= $numeroDeEventosSolapaIntervalo) $razon = $numeroDeEventosSolapaIntervalo;
		else $razon = $numeroDeEventosSolapaIntervalo-1;
		$width = $indiceEnEventosQueSolapaIntervalo +  ( 95 / $razon);
		return floor($width);
	}

	/**
		* Devuelve una abreviatura de tres letras del día de la semana en español
		* @param $month número del mes
		* @return $abreviatura 
	*/
	public function abrDiaSemana(){

		$abreviatura = '';
		$locale = array('es','es_ES');
		try {
       setlocale(LC_ALL,$locale);
        $abreviatura = utf8_encode(ucfirst(strftime('%a',$this->timestamp)));
        }
    catch (Exception $e) {
       
        $abreviatura=utf8_encode(ucfirst(date('D',$this->timestamp)));
    } 
		//return substr($abreviatura,0,1);
		return utf8_decode($abreviatura);
	}

	/**
		* Boolean si $day es de $month
		* @param $month int
		* @return boolean true si $this es un día de month
	*/
	public function isDayOfMonth($month){
		$isDay = false;
		if ((int) $month == (int) $this->numMes) $isDay = true;
		return $isDay;
	}

	public function sgrRecurso(){
		return $this->sgrRecurso;
	}

	public function fecha(){
		return $this->fecha;
		}

	public function mes(){
		return $this->numMes;
		}

	public function year(){
		return $this->year;
		}

	public function numerodia(){
		return $this->numdiames;
		}

	public function festivo(){
		return $this->festivo;
		}
	
	/**
		* // dada una hora determinada por su timestamp, comprueba si hay solapes 
		* @param $timestamp int timestamp de una hora del día 
		* @return true|false 
	*/
	public function haySolape($timestampIni,$timestampFin){

		$haysolapes = false;
		$numsolapes = 0;
		if (empty($timestampIni)) return false;
		if (empty($timestampFin)) return false;
		
		foreach ($this->eventos as $evento) {
			if ( strtotime($evento->horaInicio) <= $timestampIni && strtotime($evento->horaFin) > $timestampIni && $evento->estado == Config::get('options.eventoAprobado') ) $numsolapes++;
			if ( strtotime($evento->horaInicio) < $timestampFin && strtotime($evento->horaFin) > $timestampFin && $evento->estado == Config::get('options.eventoAprobado') ) $numsolapes++;
		}
		if ($numsolapes > 1) $haysolapes = true;
		return $haysolapes;
	}

	/**
		*
		*	@param $hora int hora del día
		*	@param $minuto int minuto del día
		* 	@return $timestamp int
	*/
	public function timestamp($hora = 0,$minuto = 0){
		return $this->timestamp + (60 * 60 * (int) $hora) + (60 * (int) $minuto);		
		}

	/**
		* //Devuelve el número de eventos para $this
		*
	*/
	public function numeroDeEventos(){

		return count($this->sgrEventos);
	}
	//private
	/**
		* Determina si el día es festivo (domingo || sábabo)
 	 	* 
 	 	* @param $tsfecha int timestamp 
 	 	* @return true 
	*/
	private function setFestivo($tsfecha){
		
		if (date('N',$tsfecha) == '7') $this->esDomingo = true;
		if (date('N',$tsfecha) == '6') $this->esSabado = true; 
		if($this->esSabado || $this->esDomingo) return $this->festivo = true;
		else return $this->festivo = false;
		}

}//fin sgrDia Class
?>