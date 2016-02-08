<?php

class AtencionEvento extends Eloquent{

 	protected $table = 'atencionEventos';

 	protected $fillable = array('evento_id','tecnico_id','momento','observaciones');

 	
 	//Una atención tiene asociado un evento
 	public function evento(){
 		return $this->hasOne('Evento','evento_id','id');
 	}
	
	//Una atención fue realizada por un técnico
	public function tecnico(){
		return $this->belongsTo('User','tecnico_id','id');
	}

 }// fin clase AtencionEvento