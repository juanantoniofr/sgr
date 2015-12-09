<?php

class EventoController extends BaseController {

	public function save(){

		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->save();

		return $result;
	}

	public function edit(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->edit();

		return $result;
	}

	public function del(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->delete();

		return $result;
	}

	public function finaliza(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->finaliza();

		return $result;
	}

	public function atiende(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->atiende();

		return $result;
	}

	/**
	 * @param void
	 * @return $result array $event = datos de evento identificado por id, $reservadopara = username (UVUS) del usuario propietario del evento  
	*/
	public function getbyId(){
		$result = array('event' 		=> '',
						'reservadoPara' => '',
						'reservadoPor' => '',
						);
		//$event = Evento::where('id','=',Input::get('id'))->get();
		$event = Evento::findOrFail(Input::get('id'));
		$result['event'] = $event->toArray();
		$result['reservadoPara'] = $event->userOwn->username; 
		$result['reservadoPor'] = User::find($event->reservadoPor)->username; 
		return $result;
	}



}
?>