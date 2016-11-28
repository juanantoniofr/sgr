<?php

class AtencionEvento extends Eloquent{

 	protected $table = 'atencionEventos';

 	protected $fillable = array('evento_id','user_id','atendidaPor_id','reservadoPor_id','momento','observaciones');

 	public function evento(){
 		return $this->belongsTo('Evento','evento_id','id');
 	}
	

 }// fin clase AtencionEvento