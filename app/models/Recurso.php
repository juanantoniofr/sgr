<?php

class Recurso extends Eloquent {

 	protected $table = 'recursos';

 	protected $fillable = array('acl', 'admin_id','descripcion','nombre', 'tipo', 'grupo_id','contenedor_id','deleted_at','disabled');
  protected $softDelete = true;
  
  //devuelve los usuarios que atienden el recurso
  public function gestores(){
 
    return $this->belongsToMany('User', 'recurso_gestores', 'recurso_id', 'user_id');
  }

  public function supervisores(){
 
    return $this->belongsToMany('User', 'recurso_supervisores', 'recurso_id', 'user_id');
  }

  public function validadores(){
 
    return $this->belongsToMany('User', 'recurso_validadores', 'recurso_id', 'user_id');
  }

  //identifica el espacio al que pertenece un puesto
  public function contenedor(){
    
    return $this->hasOne('Recurso','id','contenedor_id');   
  }

  public function items(){
 
    return $this->hasMany('Recurso','contenedor_id','id');
  }

  //identifica el grupo al que pertence un recurso
  public function grupo(){
    
    return $this->hasOne('GrupoRecurso','id','grupo_id');   
  }

  public function eventos(){
  
    return $this->hasMany('Evento','recurso_id','id');
  }

  public function eventosItems(){
  
    return $this->hasManyThrough('Evento','Recurso','contenedor_id','recurso_id');
  }
  
  /**
    * //Devuelve los eventos pendientes de realización (aprobados o pendientes) a partir de hoy 
  */
  public function eventosfuturos(){
  
   $sgrRecurso = Factoria::getRecursoInstance($this);
    return $sgrRecurso->eventosfuturos();
  }
 
  /**
    * Devuelve true si usuario identificado por $id puede reservar todos lo puestos de un recurso tipo espacio
    * @param $id int (identificador de usuario)
    * @return boolean 
  */
  public function usuariopuedereservartodoslospuestos($id){
    if (User::findOrFail($id)->isUser() || $this->puestos->count() == 0 )  return false;
    return true;    
  }

  
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
    
  public function scopetipoDesc($query){
   
    return $query->orderBy('tipo','DESC');
  }

	public function scopegrupoDesc($query){
  
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