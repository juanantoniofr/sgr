<?php

class sgrUser {

	private $user;
	

	/**
		*	@param $user object User
	*/
	function __construct($user = ''){

		if (empty($user))  $this->user = new User;
		else $this->user = $user;
		
		return $this->user;
	}

	public function esAdminSgr(){ //:)
		return Config::get('options.capacidadAdminSgr') == $this->user->capacidad;
	}

	public function esValidadorSgr(){ //:)
		return (bool) $this->user->recursosValidables()->first();
	}

	/**
		* // Devuelve los eventos de $this->user a partir de un timestamp dado o a partir del momento actual (strtotime('now'))
		* @param $timestamp int
		* @return $eventos array(Object Evento)
	*/
	public function eventos($timestamp = ''){
		//input
		if (empty($timestamp)) $timestamp = strtotime('now');
		//Output
		
		$eventos = $this->user->eventos->filter(function($evento) use ($timestamp){
			return $evento->fechaEvento >= date('Y-m-d',$timestamp); 
		});

		return $eventos;
	}

	public function id(){
		return $this->user->id;
	}

	public function estado(){
		return $this->user->estado;
	}

	public function nombre(){
		return $this->user->nombre;
	}

	public function apellidos(){
		return $this->user->apellidos;
	}

	public function username(){
		return $this->user->username;
	}

	public function colectivo(){
		return $this->user->colectivo;
	}
	public function observaciones(){
		return $this->user->observaciones;
	}

	public function updated_at(){
		return $this->user->updated_at;
	}

	public function getRol(){
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

	
	public function caducado(){
		return strtotime($this->user->caducidad) < strtotime('today');
	}

	public function test($timestamp = ''){
		//input
		if (empty($timestamp)) $timestamp = strtotime('now');
		//Output
		
		$eventos = $this->user->eventos->filter(function($evento) use ($timestamp){
			return $evento->fechaEvento >= date('Y-m-d',$timestamp); 
		});

		return $eventos;
	}

}
?>