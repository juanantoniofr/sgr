<?php

class GrupoRecursoSupervisor extends Eloquent{

 	protected $table = 'grupoRecursos_supervisor';

 	//Devuelve los recursos del grupo
 	public function grupo(){
 		return $this->hasMany('GrupoRecurso','grupoRecurso_id','id');
 	}

 	//Devuelve los usuarios supervisores del grupo de recursos
    public function supervisores(){
        return $this->belongsToMany('User','user_id');
    }

 }// fin clase GrupoRecurso_supervisores