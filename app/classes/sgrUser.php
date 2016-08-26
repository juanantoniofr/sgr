<?php

class sgrUser {

	private $sgrUser;

	/**
		*	@param $user object User
	*/
	function __construct($user = ''){

		if (empty($user))  $this->sgrUser = new User;
		else $this->sgrUser = $user;
		
		return $this->sgrUser;
	}

	public function esAdminSgr(){ //:)
		return Config::get('options.capacidadAdminSgr') == $this->sgrUser->capacidad;
	}

	public function esValidadorSgr(){ //:)
		return (bool) $this->sgrUser->recursosValidables()->first();
	}

	public function test(){
		return Config::get('options.capacidadAdminSgr') .'-'. $this->sgrUser->capacidad;
	}



}
?>