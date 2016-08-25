<?php

class GrupoRecurso extends Eloquent{

 	protected $table = 'grupoRecursos';

 	protected $fillable = array('nombre','descripcion','tipo');

 	
 	//Devuelve los recursos de un grupo
 	public function recursos(){
 		return $this->hasMany('Recurso','grupo_id','id');
 	}

    //Devuelve los usuarios administradores de un grupo
    public function administradores(){
        return $this->belongsToMany('User','grupo_administradores','grupo_id','user_id');
    }
 	
    //??
    public function usuariopuedereservartodo($id){

        if (User::findOrFail($id)->isUser() || $this->tipo != Config::get('options.equipo') ) return false;
        return true;    
    }

 }
