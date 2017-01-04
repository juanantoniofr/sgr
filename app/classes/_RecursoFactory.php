<?php
  /* marca branch master2 */
  class RecursoFactory{
  
    public static function getRecursoInstance($tipo){
        
        switch($tipo){
          case Config::get('options.espacio'):
            return new sgrEspacio();
            break;
        
          case Config::get('options.equipo'):
            return new sgrEquipo();
            break;
        
          case Config::get('options.puesto'):
            return new sgrPuesto();
            break;
          
          case Config::get('options.tipoequipos'):
            return new sgrTipoEquipo();
            break;

          default:
            throw new InvalidArgumentException('Tipo de recurso no válido');
            break;
          }
      }
  }

?>