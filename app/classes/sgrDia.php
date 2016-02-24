<?php

class sgrDia {

	private $timestamp; //timestamp de la fecha
	private $events; 	//array de objetos de tipo Eventos
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
	public function __construct($tsfecha = '',$horario = ''){

		if(empty($tsfecha)) $tsfecha = strtotime('1970-1-1');
		$this->timestamp = $tsfecha;

		if(!empty($horario) && is_array($horasdisponibles)) $this->horario = $horario;
			
		$this->festivo = $this->setFestivo($tsfecha);
			
		$this->numdiames = date('j',$this->timestamp);

		$this->fecha = date('j-n-Y',$this->timestamp);

		$this->numMes = date('n',$this->timestamp);//Sin ceros iniciales

		$this->year = date('Y',$this->timestamp);//año actual
		
		return $this;
	}

	/**
	* Devuelve una abreviatura de tres letras del día de la semana en español
	* @param $month número del mes
	* @return $abreviatura 
	*/
	public function abrDiaSemana(){

		$abreviatura = '';
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$abreviatura="Error setlocale";}
		
		$abreviatura = ucfirst(strftime('%a',$this->timestamp));
		return $abreviatura;
	}

	/**
	*	Devuelve un array de objetos Evento para $this en el recurso $id_recurso || en el grupo de de recurso identificados por $id_grupo
	*	@param $id_recurso int
	*	@param $id_gruppo int
	*	@return array objetos Evento
	*/
	public function events($id_recurso='',$id_grupo='',$view='',$hora = ''){

		
		$numMes = $this->numMes;
		$year = $this->year;
		
		$fechaEvento = date('Y-m-d',mktime(0,0,0,(int) $numMes,(int) $this->numdiames,(int) $year));
		$whereRaw = '1=1';
		if(!empty($hora) && $view == 'week') {
			
			$whereRaw = "horaInicio <= '".$hora."' and horaFin > '".$hora."'";
		}

		if ($id_recurso == 0){
			//Eventos para todos los equipos//puestos para fechaEvento del id_grupo
			$events = Evento::where('fechaEvento','=',$fechaEvento)->whereRaw($whereRaw)->orderBy('horaInicio','asc')->groupby('titulo')->groupby('evento_id')->get();
			return $events->filter(function($event) use ($id_grupo) {
				return $event->recurso->grupo_id == $id_grupo;
			});
		}		
		else{
			return Recurso::find($id_recurso)->events()->where('fechaEvento','=',$fechaEvento)->whereRaw($whereRaw)->orderBy('horaInicio','asc')->get();
		}	
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

}
?>