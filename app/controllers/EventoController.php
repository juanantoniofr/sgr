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

	public function finalizar(){
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->finalizarEvento(Input::get('idevento'));

		return $result;
	}

	public function anular(){
		
		$sgrEvento = new sgrEvento;

		$result = $sgrEvento->anularEvento();

		return $result;
	}

	public function atender(){
		$result = 'fail';

		$sgrEvento = new sgrEvento;

		if ($sgrEvento->atenderEvento(Input::all()))  $result = 'success';
		
		return $result;

		//return "success";
	}

	/**
	 * //Devulve los datos de un evennto dado su identificador ($id) 
	 * @param $id int (identificador de evento)
	 * @return $result array 
	*/
	public function getbyId(){
		$result = array('event' => array(),
						'usernameReservadoPor' => '',
						'usernameReservadoPara' => '',
						'nombreRecursoReservado' => '',);
		$event = Evento::findOrFail(Input::get('id'));
		$result['event'] = $event->toArray();
		$result['usernameReservadoPara'] = $event->user->username;
		$result['usernameReservadoPor'] = $event->reservadoPor->username;
		$result['nombreRecursoReservado'] = $event->recurso->nombre;
		return $result;		
	}

	/**
	* Devuelve evento de usuario dado su username (uvus) para su atención: debe cumplirse:
	*	1. El eventos está programado en un recurso "atendido" por Auth::user
	*	2. fechaEvento = today && horaFin < now
	*	
	* @param Input array
	* @return object Evento
	* 
	*/
	public function getUserEvents(){

		$username = Input::get('username','');
		$usuarioAtendido = User::where('username','=',$username)->first();
		if (empty($usuarioAtendido)) return '-1';//No hay usuario

		$today = date('Y-m-d');
		$now = date('H:i:s');

		$eventosCandidatos = Evento::where('user_id','=',$usuarioAtendido->id)->where('fechaEvento','=',$today)->where('horaFin','>',$now)->get();
		$recursosAtendidos = Auth::user()->atiende;
		//recolectar identificadores
		$idsRecursos = array();
		foreach ($recursosAtendidos as $recurso) {
			$idsRecursos[] = $recurso->id;
		}
		//filtrar eventos candidartos
		$eventos = $eventosCandidatos->filter(function($evento) use ($idsRecursos){
			return in_array($evento->recurso_id, $idsRecursos);//si el evento se realiza en un recurso atendido por Auth::user()
		});

		
		return View::make('tecnico.resultSearch',compact('eventos','username','usuarioAtendido'));
	}
	
}
?>