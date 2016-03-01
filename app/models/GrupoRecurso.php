<?php

class GrupoRecurso extends Eloquent{

 	protected $table = 'grupoRecursos';

 	protected $fillable = array('nombre','descripcion');

 	
 	//Una atención tiene asociado un evento
 	public function recursos(){
 		return $this->hasMany('Recurso','grupo_id','id');
 	}
	

 }// fin clase GrupoRecurso
