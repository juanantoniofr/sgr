<?php
/* marca branch master2 */
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