<?php

class GrupoRecursoValidador extends Eloquent{

 	protected $table = 'grupoRecursos_validador';

 	//Devuelve los recursos del grupo
 	public function grupo(){
 		return $this->hasMany('GrupoRecurso','grupoRecurso_id','id');
 	}

 	//Devuelve los usuarios validadores del grupo de recursos
    public function validadores(){
        return $this->belongsToMany('User','user_id');
    }

 }// fin clase GrupoRecurso_validadores