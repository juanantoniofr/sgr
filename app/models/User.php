<?php
/* :) 5-1-2017 */
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
class User extends Eloquent implements UserInterface, RemindableInterface{
	protected $table = 'users';
	public $timestamps = true;
	protected $softDelete = false;
	//protected $hidden = array('password');
	public function recursosAdministrados(){
    
    return $this->belongsToMany('Recurso', 'recurso_administradores', 'user_id', 'recurso_id');
  }
  public function gruposAdministrados(){
    
    return $this->belongsToMany('GrupoRecurso', 'grupo_administradores', 'user_id', 'grupo_id');
  }
  //devuelve los recursos que gestiona sus reservas: anula, libera, finaliza reservas
  public function recursosGestionados(){
    
    return $this->belongsToMany('Recurso', 'recurso_gestores', 'user_id', 'recurso_id');
  }
  //devuelve los recursos que valida (aprueba//deniega reservas)
  public function recursosValidados(){
    
    return $this->belongsToMany('Recurso', 'recurso_validadores', 'user_id', 'recurso_id');
  }

  //modela la relación "atender evento": 1 usuario (técnico) atiende muchos eventos
  public function atenciones(){

    return $this->hasMany('atencionEventos','tecnico_id');
  }
  //devuelve los eventos del usuario
  public function eventos(){

    return $this->hasMany('Evento','user_id');
  }
 
  /**
   * Implementa requisito: usuarios del perfil alumno (capacidad = 1) pueden reservar como másimo 12 horas a la semana.
   * 
   *  @param void
   *  @return $nh int Número de horas reservadas por el usuario logueado en la semana reservable inmediatemente siguiente a la actual (perfil alumno) 
  */
  /*public function numHorasReservadas(){
    
    $nh = 0;
    $fristMonday = sgrCalendario::fristMonday(); //devuelve timestamp
    $lastFriday = sgrCalendario::lastFriday(); //devuelve timestamp 
    $fm = date('Y-m-d',$fristMonday); //formato para la consulta sql (fechaIni en Inglés)
    $lf = date('Y-m-d',$lastFriday); //formato para la consulta sql (fechaFin en Inglés)
    $events = $this->userEvents()->where('fechaEvento','>=',$fm)->where('fechaEvento','<=',$lf)->get();
    foreach ($events as $key => $event) {
      $nh = $nh + sgrDate::diffHours($event->horaInicio,$event->horaFin);
    }
    
    return $nh;
  } */
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier(){

		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword(){

		//return $this->password;
		return null;
	}
	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail(){

		return $this->email;
	}
	public function getRememberToken(){
	    //return $this->remember_token;
	    return null;
	}
	public function setRememberToken($value){

	   // $this->remember_token = $value;
	}
	public function getRememberTokenName(){
	    //return 'remember_token';
	    return null;
	}
	/**
		*
  	* Overrides the method to ignore the remember token.
  */
 	public function setAttribute($key, $value){
	   $isRememberTokenAttribute = $key == $this->getRememberTokenName();
	   if (!$isRememberTokenAttribute)
	   {
	     parent::setAttribute($key, $value);
	   }
	}
	/**
   * Implementa requisito: Alumnos no pueden hacer reservas periodicas
   * @param void
   * @return $repetir boolean true si el usuario puede hacer reservas periodicas (usuarios con capacidad 1, alumnos, no pueden)
  */
  /*public function puedePeriodica(){
    
    $repetir = true;
    //Perfil alumno: -> No puede realizar reservas periodicas
    if ($this->isUser()) $repetir = false; 
    return $repetir;
  }*/
  //Alumnos
  /*public static function isUser(){
    $isUser = false;
    if (Auth::user()->capacidad == 1) $isUser = true;
    return $isUser;
  }*/
  //PDI
  /*public static function isAvanceUser(){
    $isUser = false;
    if (Auth::user()->capacidad == 2) $isUser = true;
    return $isUser;
  }*/
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

}