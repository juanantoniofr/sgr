<?php
  
  class Factoria{
  
    public static function getRecursoInstance($recurso = ''){
    	if (empty($recurso)) return new recursoItem(new Recurso);
    	
    	if ($recurso->items->count() > 0)
	      return new recursoContenedor($recurso);
	    else
	      return new recursoItem($recurso);
	    
    } 
  
  }//fin factoria

?>