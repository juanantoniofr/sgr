<?php

class sgrGrupo {

	private $grupo; //Objeto de tipo GrupoRecurso
	private $sgrRecursos = array(); //array de objetos sgrRecurso

	public function __construct($grupo = ''){
			
			if (empty($grupo)) {
				$this->grupo = new GrupoRecurso;
				$this->sgrRecursos[] = Factoria::getRecursoInstance();
			}	
			else 								{
				$this->grupo = $grupo;
				foreach ($grupo->recursos as $recurso) {
					$this->sgrRecursos[] = Factoria::getRecursoInstance($recurso);
				}
			}
			return $this;
	}

	public function id(){

		return $this->grupo->id;
	}

	public function nombre(){

		return $this->grupo->nombre;
	}

	public function descripcion(){

		return $this->grupo->descripcion;
	}

	public function tipo(){

		return $this->grupo->tipo;
	}

	/**
		* // true si $id es administrador del grupo.
		* @param $id int
		* @return true | false 
	*/
	public function esAdministrador($id){

		return $this->grupo->administradores->contains($id);
	}

	/**
		* //devuelve los recursos (sgrRecursos) en el grupo
	*/
	public function recursos(){

		return $this->sgrRecursos;
	}


}
?>