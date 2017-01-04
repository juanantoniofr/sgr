<?php
  /* marca branch master2 */
  class Factoria{
  
    public static function getRecursoInstance($recurso = ''){
    	if (empty($recurso)) return new recursoItem(new Recurso);
    	
      if (in_array($recurso->tipo,Config::get('options.recursosContenedores')))
        return new recursoContenedor($recurso);
      else 
        return new recursoItem($recurso);
    } 

    
  
  }//fin factoria

?>