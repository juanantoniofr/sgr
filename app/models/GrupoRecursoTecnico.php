<?php

class GrupoRecursoTecnico extends Eloquent{

 	protected $table = 'grupoRecursos_tecnico';

 	//Devuelve los recursos del grupo
 	public function grupos(){
 		return $this->hasMany('GrupoRecurso','grupoRecurso_id','id');
 	}

 	//Devuelve los usuarios técnicos que atienden un grupo de recursos
    public function tecnicos(){
        return $this->belongsToMany('User','user_id');
    }

 }// fin clase GrupoRecurso_tecnico