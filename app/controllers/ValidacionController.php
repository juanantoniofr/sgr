<?php

class ValidacionController extends BaseController {

	
	public function index(){
		
		$sortby = Input::get('sortby','estado');
	    $order = Input::get('order','desc');
	    $id_recurso = Input::get('id_recurso','0');
	    $id_user = Input::get('id_user','0');
	   	$evento_id = Input::get('evento_id','');
	   	$verpendientes = Input::get('verpendientes',false);
		$veraprobadas = Input::get('veraprobadas',false);
		$verdenegadas = Input::get('verdenegadas',false);
		$msgforuser = Input::get('msgforuser','');
	   	$espaciosConValidacion = $this->espaciosConValidacion(); //por ejemplo array('10,'11,'21') DONDE 10 -> Salón de actos,11->Sala de juntas, 21->Seminario B2

		$solapamientos = Input::get('solapamientos',false);
		$idEventoValidado = Input::get('idEventoValidado',''); 
		//resultado de la valida

		$estados = array('pendiente');
		$userFilterestado = array();
		if ($verpendientes) $userFilterestado[] = 'pendiente';
		if ($veraprobadas) $userFilterestado[] = 'aprobada';
		if ($verdenegadas) $userFilterestado[] = 'denegada';
		if (!$verdenegadas && !$veraprobadas) $userFilterestado [] = 'pendiente';
		if(!empty($userFilterestado)) {
			$estados = $userFilterestado;
			$verpendientes = true;
		}

		//mostramos la lista de eventos para todos los usuarios
		if ($id_recurso == 0 && $id_user == 0){ 
			//todos los eventos pendientes
			$events = Evento::whereIn('estado',$estados)->whereIn('recurso_id',$espaciosConValidacion)->groupby('evento_id')->orderby('fechaInicio','Asc')->orderby($sortby,$order)->paginate(10);
		}
		else if ($id_recurso == 0 && $id_user != 0){
			//solo los eventos de un usuario (todos los recursos)
			$events = Evento::whereIn('estado',$estados)->whereIn('recurso_id',$espaciosConValidacion)->where('user_id','=',$id_user)->groupby('evento_id')->orderby('fechaInicio','Asc')->orderby($sortby,$order)->paginate(10);
			
		}	
		else if ($id_recurso != 0 && $id_user == 0){
			//solo los eventos de un recurso (todos los usuarios)
			$events = Evento::where('recurso_id','=',$id_recurso)->whereIn('estado',$estados)->groupby('evento_id')->orderby('fechaInicio','Asc')->orderby($sortby,$order)->paginate(10);
		}
		else if ($id_recurso != 0 && $id_user != 0){
			//solo eventos de un usuario en un determinado recurso
			$events = Evento::where('recurso_id','=',$id_recurso)->where('user_id','=',$id_user)->whereIn('estado',$estados)->groupby('evento_id')->orderby('fechaInicio','Asc')->orderby($sortby,$order)->paginate(10);
		}
		
		
		 


		//Recursos q requiren validación
		//$eventsByrecurso = Evento::whereIn('recurso_id',$espaciosConValidacion)->groupby('recurso_id')->get();

		//Usuarios con solicitudes en espacios con validadción
		$eventsByUser = Evento::whereIn('recurso_id',$espaciosConValidacion)->groupby('user_id')->get();
		
		$recursos = Auth::user()->valida;
		
		

				

		return View::make('validador.validaciones')->with('msg',$msgforuser)->with(compact('events','verpendientes','veraprobadas','verdenegadas'))->with('sortby',$sortby)->with('order',$order)->with('idrecurso',$id_recurso)->with('iduser',$id_user)->with('solapamientos',$solapamientos)->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuValidador','validador.menuValidador',compact('recursos','id_recurso','id_user','eventsByUser','verpendientes','veraprobadas','verdenegadas'));

	}

	public function valida(){
		
		$sortby = Input::get('sortby','estado');
	    $order = Input::get('order','desc');
	    $id_recurso = Input::get('id_recurso','0');
	    $id_user = Input::get('id_user','0');
		$evento_id = Input::get('evento_id','');
		$action = Input::get('action','');
		$idRecurso = Input::get('idRecurso','0');
		$verpendientes = Input::get('verpendientes');
		$veraprobadas = Input::get('veraprobadas');
		$verdenegadas = Input::get('verdenegadas');
		//$iduser = Input::get('idUser','0';)
		
		$espaciosConValidacion = $this->espaciosConValidacion(); //por ejemplo array('10,'11,'21') DONDE 10 -> Salón de actos,11->Sala de juntas, 21->Seminario B2		//validamos (aprobar o denegar) evento
		$solapamientos = false;
		$msgforuser = '';
		if ($action == 'aprobar'){
			//vemos si hay solapamientos con solicitudes ya aprobadas
			
			$events = Evento::where('evento_id','=',$evento_id)->get();
			foreach ($events as $event) {

				$where  =	"fechaEvento = '".$event->fechaEvento."' and ";
				$where .= 	" (( horaInicio <= '".$event->horaInicio."' and horaFin >= '".$event->horaFin."' ) "; 
				$where .= 	" or ( horaFin > '".$event->horaFin."' and horaInicio < '".$event->horaFin."')";
				$where .=	" or ( horaInicio > '".$event->horaInicio."' and horaInicio < '".$event->horaFin."')";
				$where .=	" or horaFin < '".$event->horaFin."' and horaFin > '".$event->horaInicio."')";
				$where .= 	" and evento_id != '".$evento_id."'";
				$where .= 	" and estado = 'aprobada'";
				
				$numSolapamientos = Recurso::find($idRecurso)->events()->whereRaw($where)->count();
					
				if($numSolapamientos > 0) $solapamientos = true;
			
			}
			
			if(!$solapamientos){
				//update column estado en BD
				$filasAfectadas = Evento::where('evento_id','=',$evento_id)->update(array('estado'=>'aprobada'));
				$accion = 'aprobar';
				$solapamientos = false;
				$msgforuser = 'Evento aprobrado....';
				//Aquí mail to -> user_id && validadores de recurso_id ($accion = aprobar | denegar)
				$sgrMail = new sgrMail();
				$sgrMail->notificaValidacion($accion,$evento_id);
			}
			else
			{
				$solapamientos = true;
			}
		}
		else {
			Evento::where('evento_id','=',$evento_id)->update(array('estado'=>'denegada'));
			$accion = 'denegar';
			$msgforuser = 'Evento denegado....';
			//Aquí mail to -> user_id && validadores de recurso_id ($accion = aprobar | denegar)
			$sgrMail = new sgrMail();
			$sgrMail->notificaValidacion($accion,$evento_id);
		}

		

		return Redirect::to(route('validadorHome.html',array(	'solapamientos'		=> $solapamientos,
																'sortby' 			=> $sortby,
																'order'				=> $order,
																'id_recurso'		=> $id_recurso,
																'id_user'			=> $id_user,
																'idEventoValidado' 	=> $evento_id,
																'verpendientes'		=> $verpendientes,
																'veraprobadas'		=> $veraprobadas,
																'verdenegadas'		=> $verdenegadas,
																'msgforuser'		=> $msgforuser,
															)));

	}

	private function espaciosConValidacion(){
		$idsEspaciosConValidacion = array('');
		$recursos = Auth::user()->valida;

		foreach ($recursos as $recurso) {
			if ( $recurso->validacion() ) $idsEspaciosConValidacion[]=$recurso->id;
		}
		return $idsEspaciosConValidacion;

	}

}