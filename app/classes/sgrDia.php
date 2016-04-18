<?php

class sgrDia {

	private $timestamp; //timestamp de la fecha
	private $eventos;//array de objetos de tipo Eventos
	private $sgrRecurso;
	//private $events; 	
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
		$this->eventos = $sgrRecurso->getEvents(date('Y-m-d',$tsfecha));

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


	public function left($event){
		$left = 0;
		$numeroDeEventosSolapaIntervalo = 0;
		$indiceEnEventosQueSolapaIntervalo = 0;

		

		$eventos = $this->eventos->filter(function($evento) use ($event){
			return ( strtotime($evento->horaInicio) < strtotime($event->horaFin) && strtotime($event->horaFin) <= strtotime($evento->horaFin)  ) || (strtotime($evento->horaInicio) <= strtotime($event->horaInicio) && strtotime($evento->horaFin) > strtotime($event->horaInicio));
		});
		
		$numeroDeEventosSolapaIntervalo = $eventos->count();

		$encontrado = false;
		$indice = 0;
		foreach ($eventos as $evento) {
			if ($evento->id != $event->id && !$encontrado) $indice++;
			else $encontrado = true;
		}
		if ($encontrado) $indiceEnEventosQueSolapaIntervalo = $indice;

		$left = 90 - (( 90 / $numeroDeEventosSolapaIntervalo) * ($eventos->count() - $indiceEnEventosQueSolapaIntervalo));
		
		return floor($left);
	}

	public function width($event){
		$width = 95;
		$numeroDeEventosSolapaIntervalo = 0;
		$indiceEnEventosQueSolapaIntervalo = 0;

		

		$eventos = $this->eventos->filter(function($evento) use ($event){
			return ( strtotime($evento->horaInicio) < strtotime($event->horaFin) && strtotime($event->horaFin) <= strtotime($evento->horaFin)  ) || (strtotime($evento->horaInicio) <= strtotime($event->horaInicio) && strtotime($evento->horaFin) > strtotime($event->horaInicio));
		});
		
		$numeroDeEventosSolapaIntervalo = $eventos->count();

		$encontrado = false;
		foreach ($eventos as $evento) {
			if ($evento->id != $event->id && !$encontrado) $indiceEnEventosQueSolapaIntervalo++;
			else $encontrado = true;
		}

		//$width = 95 - (( 100 / $numeroDeEventosSolapaIntervalo) * $indiceEnEventosQueSolapaIntervalo);
		$width = $indiceEnEventosQueSolapaIntervalo +  ( 95 / $numeroDeEventosSolapaIntervalo);
		return floor($width);
	}


	/**
		*
		* @return boolean true|false 
	*/
	public function  reservable($id_user){
		$reservable = false;
		$user = User::findOrFail($id_user);
		$reservable = $user->isDayAviable($this->timestamp(),$this->sgrRecurso->recurso()->id);
		return $reservable;
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
		return $abreviatura;
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
		*
		*	@param $hora int hora del día
		*	@param $minuto int minuto del día
		* 	@return $timestamp int
	*/
	public function timestamp($hora = 0,$minuto = 0){
		return $this->timestamp + (60 * 60 * (int) $hora) + (60 * (int) $minuto);		
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