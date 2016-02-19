<?php

class Recurso extends Eloquent {

 	protected $table = 'recursos';

 	protected $fillable = array('acl', 'admin_id','descripcion','nombre', 'tipo');


	//Devuelve los usuarios supervisores de un recurso
    public function supervisores(){
        return $this->belongsToMany('User','recurso_supervisor');
    }

    //Devuelve los usuarios técnicos que atienden un recurso
    public function tecnicos(){
        return $this->belongsToMany('User','recurso_tecnico');
    }

    //Devuelve los usuarios validadores de un recurso
    public function validadores(){
        return $this->belongsToMany('User','recurso_validador');
    }
    

    public function events(){
        return $this->hasMany('Evento','recurso_id','id');
    }

    /**
    *   Devuelve true si el identificador de usuario es uno de los técnicos que atienden el recurso
    *   @param $id 
    *   @return boolean
    */

    public function isAtendidoPor($id = ''){
        $result = false;
        
        if ($this->tecnicos->contains($id)) $result = true;

        return $result;
    }

    /**
     * @param void
     * @return $visible boolean true si el Auth::user puede ver (para reservar) el recurso $this   
    */
    public function visible(){

        $visible = false;

        //$acl es un string con el formato {"r":"2,3"}, Esto quiere decir que los usuarios con capacidades 2 y 3 pueden "reservar" ese recurso
        $permisos = json_decode($this->acl,true); //array con key = 'r', y value igual a '2,3'
        if (strpos($permisos['r'],Auth::user()->capacidad) !== false) $visible = true; // si la capacidad del usuario forma parte de la cadena $permisos['r'], entonces es visible (puede reservar)
        
        return $visible;
    }

    /**
     * Implementa requisito: la gestión de las solicitudes de reservas pueden ser con validación o sin validación.
     * @param void
     * @return $validacion boolean true si la gestión de las solicitudes de reserva es atendida (necesita validación)
     */

    public function validacion(){

        //Por defecto el modo de reservar es automatico (sin validación)
        $validacion = false;

        //$recurso->acl tiene el formato {"r":"2,3,4,5","m":"0","fl":"Y-m-d"} donde:
        //          --> "r" son los permisos de accceso para los roles de capacidades 2,3,4 o 5.
        //          --> "m" cuando vale 0 (cero) indica que las reservas necesitan validación y 1 validación automática.
        //          --> "fl" fecha limite para validaciones no automáticas.
        $permisos = json_decode($this->acl,true);//

        //Si el modo es no automático (si necesita validación, entonces m=0) 
        if(strpos($permisos['m'],'0') !== false){
            //Si hay definida una fecha limite para el periodo no automático (m=0, existe fl y tiene un valor válido)
            if (isset($permisos['fl']) && $permisos['fl'] != null){
                $intdl = strtotime($permisos['fl']);
                $inttd = strtotime('today');
                //si aún estamos el periodo no automático (esto está limitado por fecha)
                if ($inttd  < $intdl) $validacion = true;
                
            }
            //Si no hay definida fecha limite o es null (modo no automático para siempre)
            else  $validacion = true;
        }
       
        return $validacion;

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
            case 1: //Gestión atendida sin validación
                $result = Config::get('options.gestionDesatendida');
                break;
        }
        
        return $result;
    }

}