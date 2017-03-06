<?php

class sgrUser {
	
	private $user;
	

	/**
		*
		*	@param $user object User
	*/
	function __construct($user = ''){

		if (empty($user))  $this->user = new User;
		else $this->user = $user;
		
		return $this->user;
	}

	/**
		* // devuelve el menú dropdown para $this->user
		*	@param void
		* @return string View::make('dropdownmenu')
	*/
	public function dropdownmenu(){
		
		return (string) View::make('dropdownmenu')->with('sgrUser',$this);
	}

	/**
		*
		* //Elimina relaciones: administrador, gestor o validador de un recurso
	*/
	public function detach(){
		$this->user->recursosAdministrados()->detach();
		$this->user->gruposAdministrados()->detach();
		$this->user->recursosGestionados()->detach();
		$this->user->recursosValidados()->detach();
		return true;
	}
	
	/**
		* // Devuelve los eventos de $this->user a partir de un timestamp dado o a partir del momento actual (strtotime('now'))
		* @param $timestamp int
		* @return $eventos array(Object Evento)
	*/
	public function eventos($timestamp = ''){// :)
		//input
		if (empty($timestamp)) $timestamp = strtotime('now');
		//Output
		
		$eventos = $this->user->eventos->filter(function($evento) use ($timestamp){
			return strtotime($evento->fechaEvento) >= $timestamp; 
		});

		return $eventos;
	}

	/**
		* // Elimina eventos de $this->user a partir de un timestamp dado o todos 
		* @param $timestamp int
		* @return  boolean 
	*/
	public function deleteeventos($timestamp = ''){
		//input
		if (empty($timestamp)) return $this->user->eventos()->delete();
		
		$eventos = $this->user->eventos->filter(function($evento) use ($timestamp){
			return strtotime($evento->fechaEvento) >= $timestamp;
		});
		foreach ($eventos as $evento) {
			$evento->delete();
		}
		return true;
	}

	//Roles
	/**
		* @param void
		* @return string rol de usuario: (usuario (alumnos) | Usuarios Avanzados (PDI & PAS de Administración) | Técnicos (PAS) | administrador SGR)
	*/
	public function getRol(){ //:)
		switch ($this->user->capacidad) {
			case '1':
			case '2':
			case '3':
			case '4':
				return Config::get('string.capacidades')[$this->user->capacidad];
			default:
				return 'No definido..';
			}
	}

	public function esAdminSgr(){ //:)
	
		return Config::get('options.capacidadAdminSgr') == $this->user->capacidad;
	}

	public function esValidadorSgr(){ //:)
	
		return (bool) $this->user->recursosValidados()->first();
	}

	public function esGestorSgr(){ //:)

		return (bool) $this->user->recursosGestionados()->first();	
	}

	public function isUserSgr(){ //:)
		
		return Config::get('options.capacidadUsuario') == $this->user->capacidad;
	}

	public function isAdvancedUserSgr(){
		
		return Config::get('options.capacidadUsuarioAvanzado') == $this->user->capacidad;	
	}

	/**
   * Implementa requisito: usuarios del perfil alumno (capacidad = 1) pueden reservar como másimo 12 horas a la semana.
   * 
   *  @param void
   *  @return $nh int Número de horas reservadas por el usuario logueado en la semana reservable inmediatemente siguiente a la actual (perfil alumno) 
  */
  public function numHorasReservadas(){
    
    $nh = 0;
    $fristMonday = sgrCalendario::fristMonday(); //devuelve timestamp
    $lastFriday = sgrCalendario::lastFriday(); //devuelve timestamp 
    $fm = date('Y-m-d',$fristMonday); //formato para la consulta sql (fechaIni en Inglés)
    $lf = date('Y-m-d',$lastFriday); //formato para la consulta sql (fechaFin en Inglés)
    $events = $this->user->eventos()->where('fechaEvento','>=',$fm)->where('fechaEvento','<=',$lf)->get();
    foreach ($events as $key => $event) {
      $nh = $nh + sgrDate::diffHours($event->horaInicio,$event->horaFin);
    }
    
    return $nh;
  } 


	public function delete(){ //:)
	
		return $this->user->delete();
	}

	public function id(){ //:)
		
		return $this->user->id;
	}

	public function estado(){ //:)
		
		return $this->user->estado;
	}

	public function nombre(){ //:)
		
		return $this->user->nombre;
	}

	public function apellidos(){ //:)
		
		return $this->user->apellidos;
	}

	public function username(){ //:)
		
		return $this->user->username;
	}

	public function colectivo(){ //:)
		
		return $this->user->colectivo;
	}

	public function observaciones(){ //:)
		
		return $this->user->observaciones;
	}

	public function updated_at(){ //:)
		
		return $this->user->updated_at;
	}

	public function email(){ // :)
		
		return $this->user->email;
	}

	public function capacidad(){ // :)
		
		return $this->user->capacidad;
	}

	public function caducidad(){ // :)
		
		return $this->user->caducidad;
	}

	public function caducado(){
		
		return strtotime($this->user->caducidad) < strtotime('today');
	}

	public function home(){
		//Usuarios (Alumnos) 																$this->capacidad 	=> '1', 
		//Usuarios Avanzados (PDI & PAS de Administración) 	$this->capacidad 	=> '2', 
		//Técnicos (PAS) 																		$this->capacidad 	=> '3', 
		//Administradores sgrUser														$this->capacidad	=> '4',
		
		switch ($this->user->capacidad) {
			case '1': //Usuarios (Alumnos)
			case '2': //Usuarios Avanzados (PDI & PAS de Administración)
			case '3': //Técnicos (PAS)
				return route('calendarios.html');
			case '4': //Administradores SGR
				return route('adminHome.html');	
			
			default:
				Session::put('msg',Config::get('msg.nohome'));
				Session::put('alertLevel','warning');
				return route('report.html');	
			}

	}

	/**
    *   //Determina si un $timestamp es un dia disponible para que el usuario añada//edite//elimine reservas (depende del rol y de su relación con el recurso (gestor/validador/administrador)) ()
    *   @param $timestamp int fecha a valorar
    *   @return $isAviable boolean 
    *
  */
  public function userPuedeReservar($timestamp,$idrecurso){
    
    $isAviable = false;
    $intCurrentDate = $timestamp; //mktime(0,0,0,(int) $mon,(int) $day,(int) $year);
    $capacidad = $this->user->capacidad;
    switch ($capacidad) {
      case '1': //alumnos
        $intfristMondayAviable = sgrCalendario::fristMonday();
        $intlastFridayAviable = sgrCalendario::lastFriday();
        if ($intCurrentDate >= $intfristMondayAviable && $intCurrentDate <= $intlastFridayAviable) $isAviable = true;
        break;  
      case '2': //pdi & pas administración
        $intfristMondayAviable = sgrCalendario::fristMonday(); //Primer lunes disponible
        if ($intCurrentDate >= $intfristMondayAviable) $isAviable = true;
        break;
      case '3': //Técnicos MAV 
      case '5': //Validadores
      case '6': //administrador delegado (EE MAV)
       
        if ($this->user->recursosGestionados->contains($idrecurso) || $this->user->recursosAdministrados->contains($idrecurso)|| $this->user->recursosValidados->contains($idrecurso) ){       
          $intfristdayAviable = strtotime('today'); //Hoy a las 00:00
          //$intCurrentDate = mktime(0,0,0,(int) $mon,(int) $day,(int) $year); // fecha del evento a valorar
          if ($intCurrentDate >= $intfristdayAviable) $isAviable = true;
        }
        else {
          $intfristMondayAviable = sgrCalendario::fristMonday(); //Primer lunes disponible
          //$intCurrentDate = mktime(0,0,0,(int) $mon,(int) $day,(int) $year); // fecha del evento a valorar
          if ($intCurrentDate >= $intfristMondayAviable) $isAviable = true;
        }

        break;
      case '4': //administradores SGR
        $intfristdayAviable = strtotime('today'); //Hoy a las 00:00
        if ($intCurrentDate >= $intfristdayAviable) $isAviable = true;
        break;
    }
    return $isAviable;
  }


  /**
   * Implementa requisito: Alumnos no pueden hacer reservas periodicas
   * @param void
   * @return $repetir boolean true si el usuario puede hacer reservas periodicas (usuarios con capacidad 1, alumnos, no pueden)
  */
 /* public function puedePeriodica(){
    
    $repetir = true;
    //Perfil alumno: -> No puede realizar reservas periodicas
    if ($this->isUser()) $repetir = false; 
    return $repetir;
  }*/

}
?>