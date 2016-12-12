<?php
class ACL {
/** relacionUSES */

//Primer lunes reservable a partir de la fecha del día de hoy

	public  static function fristMonday(){
		/*
		Params:
			In -> void
			Out -> $l = timestamp
		*/
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


	public static function numHorasReservadas(){
		
		$nh = 0;

		/*
			Necesito saber todas las horas reservadas por el usuario logueado dentro del periodo admitido para los usuario del perfil alumno
			
		*/
		$self = new self();
		$fristMonday = $self->fristMonday(); //devuelve timestamp
		$lastFriday = $self->lastFriday(); //devuelve timestamp	

		$fm = date('Y-m-d',$fristMonday); //formato para la consulta sql (fechaIni en Inglés)
		$lf = date('Y-m-d',$lastFriday); //formato para la consulta sql (fechaFin en Inglés)

		$events = Auth::user()->userEvents()->where('fechaEvento','>=',$fm)->where('fechaEvento','<=',$lf)->get();

		foreach ($events as $key => $event) {
			$nh = $nh + Date::diffHours($event->horaInicio,$event->horaFin);
		}
		
		return $nh;
	}	

	


	//Último viernes reservable a partir del la fecha del día de hoy
	public static function lastFriday(){

		$v = '';
		//Parámetros
		$lastDay = Config::get('options.ant_ultimodia'); //por defecto el jueves (dia 4 de la semana)
		$n = Config::get('options.ant_minSemanas'); 
		//día actual
		$today = date('Y-m-d');
		$numWeekCurrentDay = date('N');//,strtotime($today));//1 lunes,... 7 domingo
		
		//Si es de lunes a jueves 
		if ($numWeekCurrentDay <= $lastDay){
		   // y si la fecha de realización de la reserva está entre las fechas de la semana siguiente a la actual
		   $v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes semana siguinte
		// si es viernes, sabado o domingo   
		}
		else {
		  	$v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes la n-esima semana siguinte
		}

		return $v;

	}

	public static function automaticAuthorization($idRecurso){
		/*
		In -> $idRecurso (int)
		   -> $currentFecha (string: Y-m-d)

		Out -> boolean (true//false)
		*/

		//Por defecto el modo de reservar es automatico (sin validación)
		$siAutomatico = true;

		$recurso = Recurso::find($idRecurso);
		//$recurso->acl tiene el formato {"r":"2,3,4,5","m":"0","fl":"Y-m-d"} donde:
		//  		--> "r" son los permisos de accceso para los roles de capacidades 2,3,4 o 5.
		//			--> "m" cuando vale 0 (cero) indica que las reservas necesitan validación y 1 validación automática.
		//			--> "fl" fecha limite para validaciones no automáticas.
		$permisos = json_decode($recurso->acl,true);//

		//Si el modo es no automático (necesita validación, m=0) 
		if(strpos($permisos['m'],'0') !== false){
			//Si hay definida una fecha limite para el periodo no automático (m=0, existe fl y tiene un valor válido)
			if (isset($permisos['fl']) && $permisos['fl'] != null){
				$intdl = strtotime($permisos['fl']);
				$inttd = strtotime('today');
				//si aún estamos el periodo no automático (esto está limitado por fecha)
				if ($inttd  < $intdl) $siAutomatico = false;
				//else -> no modifica el valor por defecto de $siAutomatico que es true
			}
			//Si no hay definida fecha limite o es null (modo no automático para siempre)
			else  $siAutomatico = false;
		}
		
		return $siAutomatico;

	}


	public static function canReservation($idRecurso,$acl){

		$can = false;

		//$acl es un string con el formato {"r":"2,3"}, Esto quiere decir que los usuarios con capacidades 2 y 3 pueden "reservar" ese recurso

		$permisos = json_decode($acl,true); //array con key = 'r', y value igual a '2,3'
		if (strpos($permisos['r'],Auth::user()->capacidad) !== false) $can = true; // si la capacidad del usuario forma parte de la cadena $permisos['r'], entonces puede reservar
		
		return $can;
	}

	

	public static function withOutRepetition(){
		$withRepetition = false;

		//Perfil alumno: -> No puede realizar reservas periodicas
		if (ACL::isUser()) $withRepetition = true; 

		return $withRepetition;
	}


	//Alumnos
	public static function isUser(){
		$isUser = false;

		if (Auth::user()->capacidad == 1) $isUser = true;

		return $isUser;
	}
	//PDI
	public static function isAvanceUser(){
		$isUser = false;

		if (Auth::user()->capacidad == 2) $isUser = true;

		return $isUser;
	}
	//PAS
	public static function isTecnico(){
		$isTecnico = false;

		if (Auth::user()->capacidad == 3) $isTecnico = true;

		return $isTecnico;
	}
	//root
	public static function isAdmin(){
		$isAdmin = false;

		if (Auth::user()->capacidad == 4) $isAdmin = true;

		return $isAdmin;
	}
	//Validador
	public static function isValidador(){
		$isValidador = false;

		if (Auth::user()->capacidad == 5) $isValidador = true;

		return $isValidador;
	}

	//Supervisor
	public static function isSupervisor(){
		$isSupervisor = false;

		if (Auth::user()->capacidad == 6) $isSupervisor = true;

		return $isSupervisor;
	}

	public static function getHome(){
		//$self = new Self();

		$home = route('wellcome');
		if (ACL::isUser() || ACL::isAvanceUser()) $home = 'calendarios.html'; //PDI, PAS (administración) y alumnos
        if (ACL::isTecnico()) $home = 'tecnico/home.html'; //PAS - Técnicos MAV
        if (ACL::isAdmin()) $home = 'admin/home.html'; //PAS informática
        if (ACL::isValidador()) $home = 'validador/home.html'; //Vicedecanato y administrador de Centro
        if (ACL::isSupervisor()) $home = 'admin/listarecursos.html'; //EE Unidad Administrativa MAV

        return $home;
	}

	public static function isAdminForRecurso($userId,$recursoId){
		$isAdminFroRecurso = false;

		$recurso = Recurso::findOrFail($recursoId);
		$administradores = $recurso->administradores()->get();
		$aIdsAdmin = array();
		foreach ($administradores as $admin) {
			$aIdsAdmin[] = $admin->id;
		}

		if (in_array($userId, $aIdsAdmin)) $isAdminFroRecurso = true;

		return $isAdminFroRecurso;
	}

}//Fin de la clase ACL
?>