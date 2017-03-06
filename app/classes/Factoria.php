<?php
  
  class Factoria{
    
    public static function getRecursoInstance($recurso = ''){
    	if (empty($recurso) || $recurso == NULL) return new recursoItem(new Recurso);
    	
      //if (in_array($recurso->tipo,Config::get('options.recursosContenedores')))
      if (in_array($recurso->tipo,Config::get('options.recursosContenedores')) && count($recurso->items) > 0) //si tiene items es un recurso contenedore de otros
        return new recursoContenedor($recurso);
      else 
        return new recursoItem($recurso);
    } 

    
  
  }//fin factoria

?>