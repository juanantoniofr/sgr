<?php
/* :) 5-1-2017 */
class AtencionEvento extends Eloquent{

 	protected $table = 'atencionEventos';

 	protected $fillable = array('evento_id','user_id','atendidaPor_id','reservadoPor_id','momento','observaciones');

 	/*public function evento(){
 		return $this->belongsTo('Evento','evento_id','id');
 	}*/
	//Una atención tiene asociado un evento
  public function evento(){
    return $this->hasOne('Evento','evento_id','id');
  }
  
  //Una atención fue realizada por un técnico
  public function tecnico(){
    return $this->belongsTo('User','tecnico_id','id');
  }

 }// fin clase AtencionEvento
