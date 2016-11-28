<?php

class Evento extends Eloquent{

 	protected $table = 'eventos';

 	protected $fillable = array('titulo', 'recurso_id','fechaEvento','fechaInicio','repeticion', 'dia','diasRepeticion','fechaFin','user_id','created_at','user_id');

	public function userOwn(){
		
 		return $this->belongsTo('User','user_id','id');
 	}	

 	public function recursoOwn(){
 		return $this->belongsTo('Recurso','recurso_id','id');
 	} 

 	public function atencion(){
 		return $this->belongsTo('AtencionEvento','id','evento_id');
 	}

 	public function total(){
 		$total = '';
 		if ($this->recursoOwn->tipo != 'espacio') $total = Evento::where('evento_id','=',$this->evento_id)->where('horaInicio','=',$this->horaInicio)->where('fechaEvento','=',$this->fechaEvento)->count();
 		

			//Bug PODController, quitar el año q viene
			$userPOD = User::where('username','=','pod')->first(); 
			//$eventoTest = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->first();
			$idPOD = $userPOD->id;
			$iduser = 0;
			$iduser = $this->user_id;
			if ( $iduser == $idPOD ) {
				$recursos = Recurso::where('grupo_id','=',$this->recursoOwn->grupo_id)->get();
				$alist_id = array();
				foreach($recursos as $recurso){
					$alist_id[] = $recurso->id;
				}

				$total = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$this->fechaEvento)->where('horaInicio','=',$this->horaInicio)->where('titulo','=',$this->titulo)->count();

			}
			//fin del bug
 		return $total;
 	}
	


 	private $rules = array (
			'titulo' 			=>	'required',
			'actividad'			=>	'required',
			'fInicio' 			=>  'required|date|date_format:d-m-Y',
			'hInicio'			=>	'required|date_format:H:i',
			'hFin'				=>	'required|date_format:H:i',
			'dias'				=> 	'required_with:fInicio,fEvento',
			
			);

	private	$messages = array (
			'required'				=>	' El campo <strong>:attribute</strong> es obligatorio.',
			'dias.required_with'	=>	' El campo <strong>"Días"</strong> es obligatorio. ',
			'date'					=>	'<strong>Fecha no válida</strong>. <br />',
			'date_format'  			=>	'<strong>Formato de fecha no válido</strong>. Formato admitido: d-m-Y.',

			'fInicio.after' 		=>	' La <strong>Fecha de Inicio</strong> debe ser posterior al día actual.',
			'fInicio.req1' 			=>	'',
			'fInicio.req5' 			=>	'',
			'fInicio.req6' 			=>	'',

			'fFin.after'			=>	' La <strong>"fecha de finalización"</strong> debe ser posterior a la <strong>"fecha de inicio"</strong>. <br />',
			'fFin.datefincurso' 	=>  '',
			'fInicio.dateiniciocurso' 	=>  '',
			'fInicio.dateiniciotitulospropios' 	=>  '',

			'hFin.after'			=>	' La <strong>"hora de inicio"</strong> tiene que ser anterior a la <strong>"hora de finalización"</strong>. ',
			'hFin.req2' 			=>	' Se supera el máximo de horas a la semana.. (12h). ',
			
			'dias.req4'				=>	'',
			
			'fEvento.reservaunica'	=> 	' No está permitido reservar dos puestos o equipos el mismo día y a la misma hora. Puede consultar la su Agenda para comprobar coincidencias.',
			
			'titulo.required' 		=>	' El campo <strong>título</strong> es obligatorio.',
			'titulo.req3' 			=>	' Recurso ocupado, la solicitud de reserva no se puede registrar.',
			'titulo.existeuvus'		=>	' No es posible añadir reservas para usuarios no registrados.',
            'titulo.deshabilitado'  =>  'Espacio deshabilitado temporalmente..',			
			);	
	
    private $errors = array();

		//Requisitos antes de salvar eventos.
			//req1: alumno solo pueden reservar entre firstMonday y lastFriday  (por implementar)
    		//req2: alumno supera el máximo de horas a la semana (12)
    		//req3: espacio ocupado (no solapamientos)
    		//req4: no se puede reservar en sábados y domingos
    		//req5: alumnos y pdi: solo pueden reservar a partir de firstmonday
    		//rqe6: alumnos no pueden reservar dos recursos a la misma hora mismo día
    		//reservaUnica: alumnos no puden reservar dos equipos o puestos a la misma hora
			//existeuvus: al añadir un evento para uvus: debe existir en la base de datos.
    		//deshabilitado: no permite añadir reservas en espacios deshabilitados
    
    public function validate($data)
    	{

        
    	//mensages
    	//req1: alumno solo pueden reservar entre firstMonday y lastFriday  (por implementar)	
    	if (ACL::isUser()){
    		setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    		$this->messages['fInicio.req1'] = '<br />Puedes reservar entre el <strong>' . strftime('%A, %d de %B de %Y',ACL::fristMonday()) . '</strong> y el <strong>' .strftime('%A, %d de %B de %Y',ACL::lastFriday()) .'</strong><br />';
    	}

    	
    	if (ACL::isAvanceUser()){
    		setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    		$this->messages['fInicio.req5'] = 'No permitidas reservas en la semana en curso o anteriores. Puedes reservar a partir del <strong>' . strftime('%A, %d de %B de %Y',ACL::fristMonday()) . '</strong><br />';
    	}

    	if (ACL::isTecnico() || ACL::isSupervisor() || ACL::isValidador()){
    		setlocale(LC_ALL,'es_ES@euro','es_ES','esp');
    		$tsToday = strtotime('today');
    		$this->messages['fInicio.req6'] = 'Puedes reservar a partir del <strong>' . strftime('%A, %d de %B de %Y',$tsToday) . '</strong><br />';
    	}

    	if (isset($data['dias']) && in_array('6', $data['dias'])){
    		$this->messages['dias.req4'] = $this->messages['dias.req4'] . " No se puede reservar en <strong>sábado</strong><br />";
    	}
    	if (isset($data['dias']) && in_array('0', $data['dias']) )
    		$this->messages['dias.req4'] = $this->messages['dias.req4'] . " No se puede reservar en <strong>domingo</strong><br />";
    	
    	if (!empty($data['fFin'])){
    		$this->messages['fFin.datefincurso'] = 'Las reservas deben de finalizar dentro del curso académico actual. (Fecha limite: '.date('d-m-Y',strtotime(Config::get('options.fin_cursoAcademico'))).')';
    	}
    	if (!empty($data['fInicio'])){
    		$this->messages['fInicio.dateiniciocurso'] = 'Las reservas se habilitarán a partir del día '.date('d-m-Y',strtotime(Config::get('options.inicio_cursoAcademico')));
    	}
    	if (!empty($data['fInicio'])){
    		$this->messages['fInicio.dateiniciotitulospropios'] = 'Las reservas para titulos propios se habilitarán a partir de '.date('d-m-Y',strtotime(Config::get('options.inicio_titulospropios')));
    	}

    	/*if (!empty($data['reservarParaUvus'])){
    		$this->messages['titulo.existeuvus'] = 'No es posible añadir reservas para usuarios no registrados.';
    	}*/
    	
    	//fin mensages
       
    	
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->messages);
   
   		//requisito: reserva para otro usuario -> debe de existir en la Base de Datos
        if (!empty($data['reservarParaUvus'])){
        	$v->sometimes('titulo','existeuvus',function($data){
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
				if (ACL::isUser()) {
					if ( ACL::fristMonday() > Date::getTimeStamp($data['fInicio'])  || ACL::lastFriday()  < Date::getTimeStamp($data['fInicio'])) return true;
				}
			});
		}

		//req2: alumno supera el máximo de horas a la semana (12)
		// empty($data['action'] -> solo se comprueba en la reserva nueva (add)
		if (!empty($data['hFin']) && !empty($data['hInicio']) && empty($data['action'])){
			
			$v->sometimes('hFin','req2',function($data){
				if (ACL::isUser()){
					$nh = ACL::numHorasReservadas();//Número de horas ya reservadas
					$nh2 = Date::diffHours($data['hInicio'],$data['hFin']);//números de horas que se quiere reservar
					$maximo = Config::get('options.max_horas');
					$credito = $maximo - $nh; //número de horas que aún puede el alumno reservar
			    		if ($credito < $nh2) return true;
			    	}
			    });
		}
		
		//req3: espacio ocupado (no solapamientos)
       	if (isset($data['fInicio']) && strtotime($data['fInicio']) != false && isset($data['dias']) ){
			
			$v->sometimes('titulo','req3',function($data) {
				
				if ($data['id_recurso'] == 0){
					$recursos = Recurso::where('grupo_id','=',$data['grupo_id'])->get();
					foreach($recursos as $recurso){
						//si modo automatico
						//$ocupado = false;
						$id_recurso = $recurso->id;	
						if(ACL::automaticAuthorization($id_recurso)){
							//Ocupado??; -> Solo busco solapamientos con solicitudes ya aprobadas
							$estado = 'aprobada';
							//$currentFecha tiene formato d-m-Y
							$dias = $data['dias']; //0->domingo, 1->lunes...., 5->viernes, 6->sádabo
							
							foreach ($dias as $dWeek) {
								if ($data['repetir'] == 'SR') $nRepeticiones = 1;
								else $nRepeticiones = Date::numRepeticiones($data['fInicio'],$data['fFin'],$dWeek);

								for($j=0;$j<$nRepeticiones;$j++){
									$startDate = Date::timeStamp_fristDayNextToDate($data['fInicio'],$dWeek);
									$currentfecha = Date::currentFecha($startDate,$j);
									$numEvents = Calendar::getNumSolapamientos($id_recurso,$currentfecha,$data['hInicio'],$data['hFin'],$estado);
									//si ocupado
									if($numEvents > 0){
										return true;
									}
								}
							}
						}
					}
				}
				else{
					//si modo automatico
					//$ocupado = false;	
					if(ACL::automaticAuthorization($data['id_recurso'])){
						//Ocupado??; -> Solo busco solapamientos con solicitudes ya aprobadas
						$estado = 'aprobada';
						//$currentFecha tiene formato d-m-Y
						$dias = $data['dias']; //0->domingo, 1->lunes...., 5->viernes, 6->sádabo
						
						foreach ($dias as $dWeek) {
							if ($data['repetir'] == 'SR') $nRepeticiones = 1;
							else $nRepeticiones = Date::numRepeticiones($data['fInicio'],$data['fFin'],$dWeek);

							for($j=0;$j<$nRepeticiones;$j++){
								$startDate = Date::timeStamp_fristDayNextToDate($data['fInicio'],$dWeek);
								$currentfecha = Date::currentFecha($startDate,$j);
								$numEvents = Calendar::getNumSolapamientos($data['id_recurso'],$currentfecha,$data['hInicio'],$data['hFin'],$estado);
								//si ocupado
								if($numEvents > 0){
									return true;
								}
							}
						}
					}
				}	
				
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
				if (ACL::isAvanceUser()) {
					if ( ACL::fristMonday() > Date::getTimeStamp($data['fInicio']) ) return true;
				}
			}); 
		}	
		
		//Req6: 
		// --> tecnicos  (capacidad 3, 4 y 5): reservas a partir del día de hoy (para mañana)
		if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false){
			$v->sometimes('fInicio','req6',function($data){
				if (ACL::isTecnico() || ACL::isSupervisor() || ACL::isValidador()) {
					if ( strtotime('today') > Date::getTimeStamp($data['fInicio']) ) return true;
				}
			}); 
		}	
		//after: fInicio & fFin > today	
 		if (!empty($data['fInicio']) && strtotime($data['fInicio']) != false ){
        	
	        
		        $intFinicio = Date::getTimeStamp($data['fInicio'],'-');
		        $intNow = Date::getTimeStamp(date('d-m-Y'),'-');
	        	$intDiaAnterior = strtotime('-1 day',$intFinicio);
			    //fecha posterior al día actual
			    $v->sometimes('fInicio','after:'. date('d-m-Y',$intDiaAnterior),function($data){
			    	return true;
				    });
			    //fecha fin mayor o igual que fecha inicio => mayor que el día anterior a fecha inicio
			    if ($data['repetir'] == 'CS'){ 
	        		$v->sometimes('fFin','required|date|date_format:d-m-Y|after:'. date('d-m-Y',$intDiaAnterior),function($data){
			    	    	return true;
			    	});
	        	}
	    }
    //after:hinicio < hfin
		if (!empty($data['hInicio'])){
			$aHini = explode(':',$data['hInicio']);
			$timehorainicio = mktime($aHini[0],$aHini[1]);
			$v->sometimes('hFin','required|date_format:H:i|after:'.date('H:i',$timehorainicio),function($data){return true;});
    }
        
		// requisito: reservas debe iniciarse dentro del curso académico actual (sólo si usuario es alumno)
    if (!empty($data['fInicio']) && !Auth::user()->isPDI() && !Auth::user()->isAdmin()
    	&& !Auth::user()->isValidador() && !Auth::user()->isSupervisor() && !in_array(Auth::user()->username, Config::get('options.userexcluded'))) {
       $v->sometimes('fInicio','dateiniciocurso',function($data){
        $fechaInicioCurso = Config::get('options.inicio_cursoAcademico');
        $fechaMinimaEvento = $data['fInicio'];
        if (strtotime(Date::parsedatetime($fechaMinimaEvento,'d-m-Y','Y-m-d')) < strtotime($fechaInicioCurso)) 
        	return true;
      });
    }

    // requisito: reservas para titulos propios deben iniciarse dentro dentro de la fecha habilitada (para profes)
    if (!empty($data['fInicio']) && Auth::user()->isPDI()	) {
       $v->sometimes('fInicio','dateiniciotitulospropios',function($data){
        $fechaInicioCurso = Config::get('options.inicio_titulospropios');
        $fechaMinimaEvento = $data['fInicio'];
        if (strtotime(Date::parsedatetime($fechaMinimaEvento,'d-m-Y','Y-m-d')) < strtotime($fechaInicioCurso)) 
        	return true;
      });
    }    

    // requisito: reservas debe finalizar dentro del curso académico actual (Restringido a todos los usuarios menos a los validadores y administradores SGR)
    if (!empty($data['fFin'])  && 
     		!empty($data['repetir']) && 
     		!Auth::user()->isValidador() && 
     		!Auth::user()->isAdmin()  
     		){
 
      $v->sometimes('fFin','datefincurso',function($data){
    	  $fechaFinCurso = Config::get('options.fin_cursoAcademico');
        $fechaMaximaEvento = $data['fEvento'];
        if ($data['repetir'] == 'CS') $fechaMaximaEvento = $data['fFin']; //Reptición cada semana
        if (strtotime(Date::parsedatetime($fechaMaximaEvento,'d-m-Y','Y-m-d')) > strtotime($fechaFinCurso)) return true;
      });
    }
		
		//deshabilitado
        if (isset($data['id_recurso']) && $data['id_recurso'] != 0){
 
            $v->sometimes('titulo','deshabilitado',function($data){
                if ( !Auth::user()->isValidador() && 1 == Recurso::findOrFail($data['id_recurso'])->disabled ) return true;
            });
 
        }
 
        

		//}
        // check for failure
        if ($v->fails())
        {
           	$this->errors = $v->messages()->toArray();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    	{
        return $this->errors;
    }
 
}