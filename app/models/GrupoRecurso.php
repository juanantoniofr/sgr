<?php

class GrupoRecurso extends Eloquent{

 	protected $table = 'grupoRecursos';

 	protected $fillable = array('nombre','descripcion','tipo');

 	
 	//Devuelve los recursos del grupo
 	public function recursos(){
 		return $this->hasMany('Recurso','grupo_id','id');
 	}

 	//Devuelve los usuarios supervisores de un grupo de recursos
    /*public function supervisores(){
        return $this->belongsToMany('User','recurso_supervisores','recurso_id');
    }

    //Devuelve los usuarios técnicos que atienden un grupo de recursos
    public function tecnicos(){
        return $this->belongsToMany('User','recurso_tecnico','recurso_id');
    }

    //Devuelve los usuarios validadores de un grupo de recursos
    public function validadores(){
        return $this->belongsToMany('User','recurso_validadores','recursos_id');
    }
    */
	
    public function usuariopuedereservartodo($id){

        if (User::findOrFail($id)->isUser() || $this->tipo != Config::get('options.equipo') ) return false;
        return true;    
    }

 }// fin clase GrupoRecurso
