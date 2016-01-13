<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface{

	protected $table = 'users';
	public $timestamps = true;
	protected $softDelete = false;
	//protected $hidden = array('password');

	//devuelve los recurso que supervisa (gestiona recurso -> añade/elimina/edita/deshabilita)
	public function supervisa(){
    	//if ($this->isAdmin()) return Recurso::all(); 	
        return $this->belongsToMany('Recurso','recurso_supervisor');
    	}

    //devuelve los recurso que atiende (gestiona reservas)
	public function atiende(){
        return $this->belongsToMany('Recurso','recurso_tecnico');
    	}
    
    //devuelve los recurso que valida (aprueba//deniega reservas)
	public function valida()
    {
        return $this->belongsToMany('Recurso','recurso_validador');
    }

    //devuelve los eventos del usuario
	public function userEvents(){

		return $this->hasMany('Evento','user_id');
	
	}

	public function isDayAviable($day,$mon,$year){
		$isAviable = false;
		//$self = new self();
		$capacidad = $this->capacidad;
		switch ($capacidad) {
				case '1': //alumnos
					$intfristMondayAviable = sgrCalendario::fristMonday();
					$intlastFridayAviable = sgrCalendario::lastFriday();
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year);
					if ($intCurrentDate >= $intfristMondayAviable && $intCurrentDate <= $intlastFridayAviable) $isAviable = true;
					break;	
				case '2': //pdi & pas administración
					$intfristMondayAviable = sgrCalendario::fristMonday(); //Primer lunes disponible
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year); // fecha del evento a valorar
					if ($intCurrentDate >= $intfristMondayAviable) $isAviable = true;
					break;
				case '3': //Técnicos MAV
				case '4': //administradores SGR
				case '5': //Validadores
				case '6': //Supervisores (EE MAV)
					$intfristdayAviable = strtotime('today'); //Hoy a las 00:00
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year); // fecha del evento a valorar
					if ($intCurrentDate >= $intfristdayAviable) $isAviable = true;
					break;
		}

		return $isAviable;
	}
	
	/**
	 * Implementa requisito: usuarios del perfil alumno (capacidad = 1) pueden reservar como másimo 12 horas a la semana.
 	 * 
	 *	@param void
	 *	@return $nh int	Número de horas reservadas por el usuario logueado en la semana reservable inmediatemente siguiente a la actual (perfil alumno) 
	*/

	public function numHorasReservadas(){
		
		$nh = 0;

		$fristMonday = sgrCalendario::fristMonday(); //devuelve timestamp
		$lastFriday = sgrCalendario::lastFriday(); //devuelve timestamp	

		$fm = date('Y-m-d',$fristMonday); //formato para la consulta sql (fechaIni en Inglés)
		$lf = date('Y-m-d',$lastFriday); //formato para la consulta sql (fechaFin en Inglés)

		$events = $this->userEvents()->where('fechaEvento','>=',$fm)->where('fechaEvento','<=',$lf)->get();

		foreach ($events as $key => $event) {
			$nh = $nh + Date::diffHours($event->horaInicio,$event->horaFin);
		}
		
		return $nh;
	}	
	/**
	*
	* @return grupos de recursos con acceso para el usuario logueado?? 
	* --Revisar:devuelve el objeto recurso, cuando solo necesita el nombre del grupo--
	*/
	public function gruposRecursos(){
		
		$conAcceso = array();
		$visible = array();
		$result = array();

		$recursos = Recurso::all();
		//se filtran para obtener sólo aquellos con acceso para el usuario logeado
		$recursos = $recursos->filter(function($recurso){
    		return $recurso->visible();
		});
		
		
		//Nos quedamos con los grupos sin repeticiones
		foreach ($recursos as $recurso) {
			if (in_array($recurso->grupo_id,$conAcceso) === false){
				$conAcceso[] = $recurso->grupo_id;
				$result[] = $recurso;	
			} 
				
		}

		return $result;
	}
	
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		//return $this->password;
		return null;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
	    //return $this->remember_token;
	    return null;
	}

	public function setRememberToken($value)
	{
	   // $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    //return 'remember_token';
	    return null;
	}

	/*
  		* Overrides the method to ignore the remember token.
  	*/
 	public function setAttribute($key, $value)
 	{
	   $isRememberTokenAttribute = $key == $this->getRememberTokenName();
	   if (!$isRememberTokenAttribute)
	   {
	     parent::setAttribute($key, $value);
	   }
	}

	

	public function caducado(){
		return strtotime($this->caducidad) < strtotime('today');
	}

	public function getRol(){
		
		
		switch ($this->capacidad) {
			case '1':
				return 'Usuario (Alumno)';
			case '2':
				return 'PDI // PAS Administración';
			case '3':
				return 'PAS (técnico MAV)';
			case '4':
				return 'Administrador (SGR)';
			case '5': 
				return 'Validador';
			case '6': 
				return 'Supervisor (E.E Unidad)';	
			default:
				return 'No definido..';
			}
	}

	
	public function home(){
		
		switch ($this->capacidad) {
			case '6': //Supervisor
				return route('recursos');
			case '5': //validador
				return route('validadorHome.html');
			case '4': //root
				return route('adminHome.html');
			case '3': //pas - técnico MAV
				return route('calendarios.html');
			case '2': //pdi - pas Administración
				return route('calendarios.html');
			case '1': //alumno
				return route('calendarios.html');
			default:
				return 'No definido..';
			}

	}
	
	public function dropdownMenu(){
		
		switch ($this->capacidad) {
			case '5': //validador
				return 'validador.dropdown';
			case '4': //root
				return 'admin.dropdown';
			case '3': //pas - técnico 
				return 'tecnico.dropdown';
			case '6': //pas - supervisor
				return 'tecnico.dropdown';
			case '2': //pdi
				return 'emptydropdown';
			case '1': //alumno
				return 'emptydropdown';
			default:
				return 'emptydropdown';
			}
	}

	/**
	 * Implementa requisito: Alumnos no pueden hacer reservas periodicas
	 * @param void
	 * @return $repetir boolean true si el usuario puede hacer reservas periodicas (usuarios con capacidad 1, alumnos, no pueden)
	*/
	public function puedePeriodica(){
		
		$repetir = true;

		//Perfil alumno: -> No puede realizar reservas periodicas
		if ($this->isUser()) $repetir = false; 

		return $repetir;
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


}