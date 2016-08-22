<?php

class RecursoSupervisores extends Eloquent{

 	protected $table = 'recurso_supervisores';

 	//Devuelve los recursos del grupo
 	//public function grupo(){
 	//	return $this->hasMany('GrupoRecurso','grupoRecurso_id','id');
 	//}

 	//Devuelve los usuarios supervisores del recurso
    public function supervisores(){
        return $this->belongsToMany('User','user_id');
    }

 }// fin clase GrupoRecurso_supervisores