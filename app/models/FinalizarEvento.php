<?php

class FinalizarEvento extends Eloquent{

 	protected $table = 'finalizarEventos';

 	protected $fillable = array('evento_id','user_id','tecnico_id','momento','observaciones');

 	public function evento(){
 		return $this->belongsTo('Evento','evento_id','id');
 	}
	

 }// fin clase FinalizarEvento