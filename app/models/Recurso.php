<?php

class Recurso extends Eloquent {

 	protected $table = 'recursos';

 	protected $fillable = array('acl', 'admin_id','descripcion','nombre', 'tipo','grupo_id');


	//Devuelve los usarios administradores de un recurso
    public function administradores(){
        return $this->belongsToMany('User');
    }


    public function events(){
        return $this->hasMany('Evento','recurso_id','id');
    }

    
    public function scopetipoDesc($query)
    {
        return $query->orderBy('tipo','DESC');
    }

	public function scopegrupoDesc($query)
    {
        return $query->orderBy('grupo','DESC');
    }   
 
    
    public function perfiles(){
        $perfiles = array();
        $aPerfilesSGR = Config::get('options.perfiles');
        $aclrecurso = json_decode($this->acl,true);
        $capacidades = explode(',',$aclrecurso['r']);
        foreach ($capacidades as $capacidad) {
          $perfiles[] = $aPerfilesSGR[$capacidad]; 
        }
        return $perfiles;
        
    }

    public function tipoGestionReservas(){

        $result = 'No está definida....';
        $modo = '1';
        $aclrecurso = json_decode($this->acl,true);
        if (isset($aclrecurso['m'])) $modo = $aclrecurso['m'];

        switch ($modo) {
            case 0: //Gestión atendida con validación
                $result = Config::get('options.gestionAtendida');
                break;
            case 1: //Gestión atendida con validación
                $result = Config::get('options.gestionDesatendida');
                break;
        }
        
        return $result;

    }

}