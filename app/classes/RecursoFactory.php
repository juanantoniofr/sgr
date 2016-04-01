<?php
  
  class RecursoFactory{
  
    public static function getRecursoInstance($id){
        
        $recurso = Recurso::findOrFail($id);
        $tipo = $recurso->tipo;
       
        switch($tipo){
          case Config::get('options.espacio'):
            return new sgrEspacio($id);
            break;
        
          case Config::get('options.equipo'):
            return new sgrEquipo($id);
            break;
        
          case Config::get('options.puesto'):
            return new sgrPuesto($id);
            break;
          
          default:
            throw new InvalidArgumentException('Tipo de recurso no válido');
            break;
          }
      }
  }

?>