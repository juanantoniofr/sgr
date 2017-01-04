<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
/* marca branch master2 */
class User extends Eloquent implements UserInterface, RemindableInterface{

	protected $table = 'users';
	public $timestamps = true;
	protected $softDelete = false;
	//protected $hidden = array('password');

	//devuelve los recurso que valida
	public function supervisa()
    {
        return $this->belongsToMany('Recurso');
    }

    //devuelve los eventos del usuario
	public function userEvents(){

		return $this->hasMany('Evento','user_id');
	
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

	//Validador
    public function isValidador(){
        $isValidador = false;
 
        if (Auth::user()->capacidad == 5) $isValidador = true;
 
        return $isValidador;
    }

	//PDI
    public function isPDI(){
        $isPDI = false;
 
        if (Auth::user()->capacidad == 2) $isPDI = true;
 
        return $isPDI;
    }

    //Supervisor
    public function isSupervisor(){
        $isSupervisor = false;
 
        if (Auth::user()->capacidad == 6) $isSupervisor = true;
 
        return $isSupervisor;
    }
    //Admin
    public function isAdmin(){
        $isAdmin = false;
 
        if (Auth::user()->capacidad == 4) $isAdmin = true;
 
        return $isAdmin;
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

	
	public function getHome(){
		
		switch ($this->capacidad) {
			case '6': //validador
				return route('recursos');
			case '5': //validador
				return route('validadorHome.html');
			case '4': //root
				return route('adminHome.html');
			case '3': //pas - técnico MAV
				return route('tecnicoHome.html');
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

}