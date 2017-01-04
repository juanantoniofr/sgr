<?php
/* marca branch master2 */
class Notificacion extends Eloquent {

 	protected $table = 'notificaciones';

    public $timestamps = false;

  //identifica usuario origen de la notificación
  public function user(){
    
    return $this->hasOne('User','username','source');   
  } 

}

