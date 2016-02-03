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

	public function anula(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->anula();

		return $result;
	}

	public function atiende(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->atiende();

		return $result;
	}

	/**
	 * //Devulve los datos de un evennto dado el id del evento
	 * @param $id identificador de evento
	 * @return $result array 
	*/
	public function getbyId(){
		$result = array('event' => array(),
						'usernameReservadoPor' => '',
						'usernameReservadoPara' => '',
						'nombreRecursoReservado' => '',);
		$event = Evento::findOrFail(Input::get('id'));
		$result['event'] = $event->toArray();
		$result['usernameReservadoPor'] = $event->userOwn->username;
		$result['usernameReservadoPara'] = $event->reservadoPor->username;
		$result['nombreRecursoReservado'] = $event->recursoOwn->nombre;
		return $result;		
	}

	//Buscar eventos de un usuario dado su username (uvus)
	public function getUserEvents(){

		
		$username = Input::get('username','');

		if(empty($username)) return '-1'; //parámetro de entrada vacio
		
						
		$user = User::where('username','=',$username)->first();
		$today = date('Y-m-d');
		$hora = date('H:i:s');
		if (empty($user)) return '1';//No hay usuario
		
		$event = Evento::where('user_id','=',$user->id)->where('fechaEvento','=',$today)->where('horaFin','>',$hora)->groupby('evento_id')->orderby('recurso_id','asc')->orderby('fechaEvento','asc')->orderby('horaInicio','asc')->first();
		$username = $user->id;
		return View::make('tecnico.resultSearch',compact('event','username'));
	}
	
}
?>