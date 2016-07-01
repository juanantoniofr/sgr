<?php
  
  class Factoria{
  
    public static function getRecursoInstance($recurso){
        if ($recurso->items->count() > 0)
          return new recursoContenedor($recurso);
        else
          return new recursoItem($recurso);      
    } 
  
  }//fin factoria

?>