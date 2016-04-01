<?php

class GrupoRecurso extends Eloquent{

 	protected $table = 'grupoRecursos';

 	protected $fillable = array('nombre','descripcion');

 	
 	//Devuelve los recursos del grupo
 	public function recursos(){
 		return $this->hasMany('Recurso','grupo_id','id');
 	}

 	//Devuelve los usuarios supervisores de un grupo de recursos
    public function supervisores(){
        return $this->belongsToMany('User','grupoRecursos_supervisor','grupoRecursos_id');
    }

    //Devuelve los usuarios técnicos que atienden un grupo de recursos
    public function tecnicos(){
        return $this->belongsToMany('User','grupoRecursos_tecnico','grupoRecursos_id');
    }

    //Devuelve los usuarios validadores de un grupo de recursos
    public function validadores(){
        return $this->belongsToMany('User','grupoRecursos_validador','grupoRecursos_id');
    }
	

 }// fin clase GrupoRecurso
