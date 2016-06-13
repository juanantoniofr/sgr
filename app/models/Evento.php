<?php

//use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Evento extends Eloquent{
	
	//use SoftDeletingTrait;
	protected $table = 'eventos';
	//protected $dates = ['deleted_at'];
 	protected $fillable = array('horaInicio','titulo', 'recurso_id','fechaEvento','fechaInicio','repeticion', 'dia','diasRepeticion','fechaFin','user_id','created_at','user_id');
 	protected $softDelete = true;

 	//Devuelve el objeto User propietario del evento
	public function user(){
		return $this->belongsTo('User','user_id','id');
 	}	

 	public function reservadoPor(){
		return $this->belongsTo('User','reservadoPor_id','id');
 	}	
 	//Devuelve el objeto Recurso donde se realiza el evento
 	public function recurso(){
 		return $this->belongsTo('Recurso','recurso_id','id');
 	} 

 	//relación: Un evento es atendido una o cero veces
 	public function atencion(){
 		return $this->hasOne('AtencionEvento','evento_id','id');
 	}
 	//devuelve true si hay una atención y false en caso contrario
	public function atendido(){
		return $this->atencion()->count() > 0;
	} 
 	//Relación: Un evento es finalizado una o cero veces
 	public function finalizacion(){
 		return $this->hasOne('FinalizarEvento','evento_id','id');
 	}
 	
 	public function numeroHoras(){
 		return (strtotime($this->horaFin) - strtotime($this->horaInicio)) / (60*60) ;
 		//return floor(( strtotime(date('H:00',strtotime($this->horaFin))) - strtotime(date('H:00',strtotime($this->horaInicio))) ) / (60*60));
 	}

	//devuelve true si hay la reserva fue finalizada y false en caso contrario
	public function finalizada(){
		return $this->finalizacion()->count() > 0;
	} 

 	/**
 		* Determina si el evento es editable (Auth::user es propietario || Auth::user reservo en favor de otro && la fecha del evento es un día disponible para el Auth::user)
 		* @param $idUser int identificador de usuario para comprobar si tiene permiso para editar el evento
 		* @return boolean
 	*/
 	public function esEditable($idUser){
 		$user = User::findOrFail($idUser); //@param identificador de usuario autenticado
 		if ($user->count() == 0) return false;//no hay usuario
 		
 		if ($this->user->id != $idUser && $this->reservadoPor->id != $idUser) 
 			return false;//Usuario autenticado no es propietario del evento, ni reservó para otro
 		

 		$timestamp = strtotime($this->fechaEvento);//la fecha del evento permite editar?? (depende del rol de usuario)
 		if ($this->user->isDayAviable($timestamp,$this->recurso_id) || $user->isDayAviable($timestamp,$this->recurso_id)) return true;
 		//el día está disponible para editar eventos para el usuario propiestario del evento ($this->user) o para el ususario autenticado ($user) es 

 		return false;
 	}

 	/**
		* Los eventos se podrán anular hasta el día anterior a su fecha de realización.
		* @param $idUser int identificador de usuario para comprobar si tiene permiso para anular el evento
		* @return boolean
	*/
	public function esAnulable($idUser){
		//$idUser no existe	
 		if (User::where('id','=',$idUser)->count() == 0) return false;
		//$idUser no es propietario y no ha reservado para otro
 		if ($this->user->id != $idUser && $this->reservadoPor->id != $idUser) return false;
 		//la fecha del evento permite anular (igual para todos los usuarios)
		$hoy = strtotime('today');
		$timestamp = strtotime($this->fechaEvento);
		if ($timestamp > $hoy) return true;
		
		return false;
	}

 	/**
 		* Devevelve el número de recurso (equipos o espacios reservados por un evento)
 	*/
 	public function numeroRecursos(){
 		$muestraItem = '';
    $numRecursos = 0;
    if ($this->recurso->tipo != 'espacio') {
     	$numRecursos = $this->total();
        		

			//Bug PODController, quitar el año q viene
			$userPOD = User::where('username','=','pod')->first(); 
			$idPOD = $userPOD->id;
			$iduser = 0;
			$iduser = $this->user_id;
			if ( $iduser == $idPOD ) {
				$recursos = Recurso::where('grupo_id','=',$this->recurso->grupo_id)->get();
				$alist_id = array();
				foreach($recursos as $recurso){
					$alist_id[] = $recurso->id;
				}
				$numRecursos = Evento::whereIn('recurso_id',$alist_id)->where('recurso_id','!=',$this->recurso_id)->where('fechaEvento','=',$this->fechaEvento)->where('horaInicio','=',$this->horaInicio)->where('titulo','=',$this->titulo)->count();

			}
			//fin del bug
    }
    return $numRecursos;
 	}
 
 	/**
 		* Determina si un evento en BD tiene solape 
 		* @param $mon mes dos dígitos
 		* @param $day día dos dígitos
 		* @param $year año cuatro dígitos
 		* @return $solapado boolean
 	*/
 	public function solape($timestamp){
 		$solapado = false;
		$hi = date('H:i:s',strtotime($this->horaInicio));
		$hf = date('H:i:s',strtotime($this->horaFin));
	  $where  = "fechaEvento = '".date('Y-m-d',$timestamp)."' and ";
	  $where .= "estado != 'denegada' and ";
	  $where .= "evento_id != '".$this->evento_id."' and ";
	  $where .= " ((horaInicio <= '".$hi."' and horaFin > '".$hi."')"; 
		//$where .= " (( horaInicio <= '".$hi."' and horaFin > '".$hi."' ) "; 
		$where .= " or ( horaFin > '".$hf."' and horaInicio < '".$hf."'))";
		//$where .= " or ( horaInicio > '".$hi."' and horaInicio < '".$hf."')";
		//$where .= " or (horaFin < '".$hf."' and horaFin > '".$hi."'))";
		$nSolapamientos = $this->recurso->events()->whereRaw($where)->count();
 		if ($nSolapamientos > 0) $solapado = true;
 		return $solapado;
 	}
 	
 	/**
 		* determina si un evento puede ser finalizado 
 		* @return boolean
 	*/
	public function esFinalizable(){
		$eventoEsFinalizable = false;
		
		if ( strtotime($this->fechaEvento) == strtotime(date('Y-m-d')) && strtotime($this->horaFin) > strtotime(date('H:i')) ) $eventoEsFinalizable = true;
		
		return $eventoEsFinalizable;
	}

 	/**
 	 	* Implementa requisito: ofrecer sumatorio de puestos o equipos reservados
 	 	* 
 	 	* @param void
 		 * @return $total int número total de puestos o equipos asociados a una misma reserva 
 	*/
 	public function total(){
 		$total = 0;
 		if ($this->recurso->tipo != 'espacio') $total = Evento::where('evento_id','=',$this->evento_id)->where('horaInicio','=',$this->horaInicio)->where('fechaEvento','=',$this->fechaEvento)->count();
 		
			//Bug PODController, quitar el año q viene
			$userPOD = User::where('username','=','pod')->first(); 
			//$eventoTest = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->first();
			$idPOD = $userPOD->id;
			$iduser = 0;
			$iduser = $this->user_id;
			if ( $iduser == $idPOD ) {
				$recursos = Recurso::where('grupo_id','=',$this->recurso->grupo_id)->get();
				$alist_id = array();
				foreach($recursos as $recurso){
					$alist_id[] = $recurso->id;
				}

				$total = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$this->fechaEvento)->where('horaInicio','=',$this->horaInicio)->where('titulo','=',$this->titulo)->count();

			}
			//fin del bug
 		return $total;
 	}

 	//Requisitos antes de salvar eventos.
		//req1: alumno solo pueden reservar entre firstMonday y lastFriday  (por implementar)
  	//req2: alumno supera el máximo de horas a la semana (12)
  	//req3: espacio ocupado (no solapamientos)
  	//req4: no se puede reservar en sábados y domingos
  	//req5: alumnos y pdi: solo pueden reservar a partir de firstmonday
  	//rqe6: alumnos no pueden reservar dos recursos a la misma hora mismo día
  	//reservaUnica: alumnos no puden reservar dos equipos o puestos a la misma hora
		//existeuvus: al añadir un evento para uvus: debe existir en la base de datos.
  	//datefincurso: las reservas no pueden finalizar después de la fecha de fin del presente curso académico para alumnos y pdi
  	//dateiniciocurso: las reservas no pueden empezar antes del inicio del presente curso académico para alumnos y pdi
  	//deshabilitado: no permite añadir reservas en espacios deshabilitados 
 	private $rules = array (
			'titulo' 			=>	'required',
			'actividad'		=>	'required',
			'fInicio' 		=>  'required|date|date_format:Y-m-d',
			'hInicio'			=>	'required|date_format:H:i',
			'hFin'				=>	'required|date_format:H:i',
			'dias'				=> 	'required_with:fInicio,fEvento',
			'id_recurso'	=>	'required|exists:recursos,id',
			);

	private	$messages = array (
			'required'										=>	' El campo <strong>:attribute</strong> es obligatorio.',
			'dias.required_with'					=>	' El campo <strong>"Días"</strong> es obligatorio. ',
			'date'												=>	'<strong>Fecha no válida</strong>. <br />',
			'fInicio.date_format'  				=>	'Fecha inicial: <strong>formato no válido</strong>. Formato admitido: j-n-Y.',
			'date_format'									=>  ':attribute',
			'hInicio.date_format'  				=>	'Hora de inicio: <strong>formato no válido</strong>. Formato admitido: H:m.',
			'hFin.date_format'  					=>	'Hora de finalización: <strong>formato no válido</strong>. Formato admitido: H:m.',
			'fInicio.after' 							=>	' La <strong>Fecha de Inicio</strong> debe ser posterior al día actual.',
			'fInicio.req1' 								=>	'',
			'fInicio.req5' 								=>	'',
			'fInicio.req6' 								=>	'',
			'fInicio.dateiniciocurso' 		=>	'',
			
			'fFin.after'									=>	' La <strong>"fecha de finalización"</strong> debe ser posterior a la <strong>"fecha de inicio"</strong>. <br />',
			'fFin.datefincurso' 					=>  '',

			'hFin.after'									=>	' La <strong>"hora de inicio"</strong> tiene que ser anterior a la <strong>"hora de finalización"</strong>. ',
			'hFin.req2' 									=>	' Se supera el máximo de horas a la semana.. (12h). ',			
			'dias.req4'										=>	'',			
			'fEvento.reservaunica'				=> 	' No está permitido reservar dos puestos o equipos el mismo día y a la misma hora. Puede consultar su Agenda para comprobar coincidencias.',
			
			'titulo.required' 						=>	' El campo <strong>título</strong> es obligatorio.',
			'titulo.req3' 								=>	' Recurso ocupado, la solicitud de reserva no se puede registrar.',
			'reservarParaUvus.existeuvus' => '',
			'titulo.deshabilitado'				=> 'Espacio deshabilitado temporalmente..',
			);	
	
  private $errors = array();
  private $data =array();

	public function validate($data){
    
		//$this->data = $data;
    //return false;	
    //formatear fechas
    if (!empty($data['fEvento'])){
    	$date = DateTime::createFromFormat('d-m-Y',$data['fEvento']);
			$data['fEvento'] = $date->format('Y-m-d');
    }
    if (!empty($data['fInicio'])){
    	$date = DateTime::createFromFormat('d-m-Y',$data['fInicio']);
			$data['fInicio'] = $date->format('Y-m-d');
    } 
    if (!empty($data['fFin'])){
    	$date = DateTime::createFromFormat('d-m-Y',$data['fFin']);
			$data['fFin'] = $date->format('Y-m-d');
    }  
    

    //mensages
    //req1: alumno solo pueden reservar entre firstMonday y lastFriday  (por implementar)	
    if (Auth::user()->isUser()){
    	setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    	$this->messages['fInicio.req1'] = '<br />Puedes reservar entre el <strong>' . strftime('%A, %d de %B de %Y',sgrCalendario::fristMonday()) . '</strong> y el <strong>' .strftime('%A, %d de %B de %Y',sgrCalendario::lastFriday()) .'</strong><br />';
    }

    if (Auth::user()->isAvanceUser()){
    	setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    	$this->messages['fInicio.req5'] = 'Puedes reservar a partir del <strong>' . strftime('%A, %d de %B de %Y',sgrCalendario::fristMonday()) . '</strong><br />';
    }

    if (Auth::user()->isTecnico() || Auth::user()->isSupervisor() || Auth::user()->isValidador()){
    	setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    	$tsToday = strtotime('today');
    	$this->messages['fInicio.req6'] = 'Puedes reservar a partir del <strong>' . strftime('%A, %d de %B de %Y',$tsToday) . '</strong><br />';
    }

    if (isset($data['dias']) && in_array('6', $data['dias'])){
    	$this->messages['dias.req4'] = $this->messages['dias.req4'] . " No se puede reservar en <strong>sábado</strong><br />";
    }
    
    if (isset($data['dias']) && in_array('0', $data['dias']) )
    	$this->messages['dias.req4'] = $this->messages['dias.req4'] . " No se puede reservar en <strong>domingo</strong><br />";
    	
    if ( !empty($data['fFin']) ){
    	$this->messages['fFin.datefincurso'] = 'Las reservas deben de finalizar dentro del curso académico actual. (Fecha limite: '.date('d-m-Y',strtotime(Config::get('options.fin_cursoAcademico'))).')';
    }

   	if ( !empty($data['fInicio']) ){
   		$this->messages['fInicio.dateiniciocurso'] = 'No es posible realizar reservas hasta que se finalice la carga del POD: fecha prevista a partir del día ' . date('d-m-Y',strtotime(Config::get('options.inicio_gestiondesatendida')));
   	}

    if (!empty($data['reservarParaUvus'])){
    	$this->messages['reservarParaUvus.existeuvus'] = 'Usuario "'. $data['reservarParaUvus'] .'" no registrado.';
    }
    //fin mensages
       
    // make a new validator object
    $v = Validator::make($data, $this->rules, $this->messages);
   
   	//requisito: reserva para otro usuario -> debe de existir en la Base de Datos
    if (!empty($data['reservarParaUvus'])){
     	$v->sometimes('reservarParaUvus','existeuvus',function($data){
 														if (User::where('username','=',$data['reservarParaUvus'])->count() == 0) return true;
 													});	
    }

 	  //requisito: alumnos no pueden reservar dos recurso a la misma hora, mismo día
		if ( !empty($data['fEvento']) && !empty($data['hFin']) && !empty($data['hInicio']) ){
			$v->sometimes('fEvento','reservaunica',function($data){
									if (Auth::user()->capacidad == '1'){
										//setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
						    		//determinar si tiene reserva en otro recurso con misma fechaEvento, horainicio y horafin solapadas.
						    		$id_recurso = Recurso::find($data['id_recurso'])->id;
						    		$where = 	" (( horaInicio <= '".$data['hInicio']."' and horaFin >= '".$data['hFin']."' ) "; 
										$where .= 	" or ( horaFin > '".$data['hFin']."' and horaInicio < '".$data['hFin']."')";
										$where .=	" or ( horaInicio > '".$data['hInicio']."' and horaInicio < '".$data['hFin']."')";
										$where .=	" or horaFin < '".$data['hFin']."' and horaFin > '".$data['hInicio']."')";
										$where .=	" and recurso_id != " . $id_recurso;
								
						    		$numEventosOtroRecurso = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','=',date('Y-m-d',strtotime($data['fEvento'])))->whereRaw($where)->count();
						    	
						    		if ($numEventosOtroRecurso > 0) return true;
						    		
						    	}
								});
    }


	  //req1: alumno solo pueden reservar entre firstMonday y lastFriday  
	  if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false){
			$v->sometimes('fInicio','req1',function($data){
					if (Auth::user()->isUser()) {
						if ( sgrCalendario::fristMonday() > strtotime($data['fInicio'])  || sgrCalendario::lastFriday()  < strtotime($data['fInicio']) ) return true;
					}
			});
		}

		//req2: alumno supera el máximo de horas a la semana (12)
		// empty($data['action'] -> solo se comprueba en la reserva nueva (add)
		if (!empty($data['hFin']) && !empty($data['hInicio']) && empty($data['action'])){
			
			$v->sometimes('hFin','req2',function($data){
										if (Auth::user()->isUser()){
											$nh = Auth::user()->numHorasReservadas();//Número de horas ya reservadas
											$nh2 = sgrDate::diffHours($data['hInicio'],$data['hFin']);//números de horas que se quiere reservar
											$maximo = Config::get('options.max_horas');
											$credito = $maximo - $nh; //número de horas que aún puede el alumno reservar
									    		if ($credito < $nh2) return true;
									  }
			});
		}
		//deshabilitado
		if (isset($data['id_recurso']) && $data['id_recurso'] != 0){
			$v->sometimes('titulo','deshabilitado',function($data){
				if ( 1 == Recurso::findOrFail($data['id_recurso'])->disabled ) return true;
			});
		}

    //req3:
    if (isset($data['fInicio']) && strtotime($data['fInicio']) != false && isset($data['dias']) ){
			$v->sometimes('titulo','req3',function($data){
					$idrecurso = $data['id_recurso'];
					$recurso = Recurso::findOrFail($idrecurso);
      		$sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      		$sgrRecurso->setRecurso($recurso);
      		$excluyeId = '';
      		if (isset($data['idSerie'])) $excluyeId = $data['idSerie'];
      		return $sgrRecurso->recursoOcupado($data,$excluyeId);		
			});
		}

    //req4: Sábados y domingos no se puede reservar
		if (isset($data['dias'])){
			$v->sometimes('dias','req4',function($data){
				$dias = $data['dias'];
				// 0 = domingo, 6 = sábado
				if (in_array('0', $dias) || in_array('6', $dias)) return true;
			});
		}
		
		//Req5: 
		// --> alumnos y pdi (capacidades 1 y 2): solo pueden reservar a partir de firstmonday 
		if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false){
			$v->sometimes('fInicio','req5',function($data){
				if (Auth::user()->isAvanceUser()) {
					if ( sgrCalendario::fristMonday() > strtotime($data['fInicio']) ) return true;
				}
			}); 
		}	
		
		//Req6: 
		// --> técnicos  (capacidad 3, 4 y 5): reservas a partir del día de hoy (para mañana)
		if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false){
			$v->sometimes('fInicio','req6',function($data){
				if (Auth::user()->isTecnico() || Auth::user()->isSupervisor() || Auth::user()->isValidador()) {
					if ( strtotime('today') > strtotime($data['fInicio']) ) return true;
				}
			}); 
		}	
		
		//after: fInicio & fFin > today	
 		if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false ){
      $intFinicio = strtotime($data['fInicio']);
		  $intNow = strtotime('now');
	    $intDiaAnterior = strtotime('-1 day',$intFinicio);
			//fecha posterior al día actual
			$v->sometimes('fInicio','after:'. date('d-m-Y',$intDiaAnterior),function($data){return true;});
			//fecha fin mayor o igual que fecha inicio => mayor que el día anterior a fecha inicio
			if ($data['repetir'] == 'CS'){ 
	    	$v->sometimes('fFin','required|date|date_format:Y-m-d|after:'. date('d-m-Y',$intDiaAnterior),function($data){return true;});
	    }
	  }
    
    //after:hinicio < hfin
		if (!empty($data['hInicio'])){
			$aHini = explode(':',$data['hInicio']);
			$timehorainicio = mktime($aHini[0],$aHini[1]);
			$v->sometimes('hFin','required|date_format:H:i|after:'.date('H:i',$timehorainicio),function($data){return true;});
    }
        
    // requisito: reservas debe finalizar dentro del curso académico actual (Restringido a todos los usuarios menos a los validadores)
    if (!empty($data['fFin'])  && !empty($data['repetir']) && !Auth::user()->isValidador()){
			$v->sometimes('fFin','datefincurso',function($data){
		 	$fechaFinCurso = Config::get('options.fin_cursoAcademico');
		 	$fechaMaximaEvento = $data['fEvento'];
		 	if ($data['repetir'] == 'CS') $fechaMaximaEvento = $data['fFin']; //Reptición cada semana
		  if (strtotime($fechaMaximaEvento) > strtotime($fechaFinCurso)) return true;});
    }

    // requisito dateiniciocurso: lasreservas debe de ser posteriores a la fecha de inicio curso actual (Restringido a usuarios alumnos, pdi, supervisores y tecnicos)
    if (!empty($data['fInicio'])  && !Auth::user()->isValidador() && !Auth::user()->isAdmin() ){
			$v->sometimes('fInicio','dateiniciocurso',function($data){
		    	$hoy = strtotime('today');
					$diaInicio = strtotime(Config::get('options.inicio_gestiondesatendida'));
					if ($diaInicio > $hoy) return true;});
    }

		// check for failure
    if ($v->fails()){
      $this->errors = $v->messages()->toArray();
      return false;
    }
    $this->data = $data;
    //return false;	
    // validation pass
    return true;
  }

  public function errors(){
        return $this->errors;
  }

  public function getdata(){
  		return $this->data;
  }
 
}