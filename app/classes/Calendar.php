<?php

class Calendar {
  
    private $aHour = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
    private $aDaysWeek = array('lunes','martes','miércoles','jueves','viernes','sabado','domingo');
	
	private $aAbrNameDaysWeek = array('1'=>'Lun','2'=>'Mar','3'=>'Mie','4'=>'Jue','5'=>'Vie','6'=>'Sab','7'=>'Dom');

	public static function getBodyTableAgenda($day,$month,$year){
		
		$html = '';

		$date = $day .'-'. $month .'-'. $year;
		$startDate = Date::toDB($date,'-');
		
		$haveEvents = false;
		//si hay eventos
		if (Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','>=',$startDate)->count() > 0){
			//Desde la fecha de inicio (pasada por parámetros), calculo la fecha máxima para que el número de eventos sea menor que 20
			$currentMaxDate = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','>=',$startDate)->max('fechaEvento');
			do{			
				$numEvents = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','>=',$startDate)->where('fechaFin','<=',$currentMaxDate)->count();
				$maxDate = $currentMaxDate;
				$currentMaxDate = Date::prevDay($currentMaxDate);
			}while ($numEvents>15);
		
			//$maxDate = Evento::where('user_id','=',Auth::user()->id)->where('fechaInicio','>',$startDate)->max('fechaInicio');

			$currentDate=$startDate;
			while($currentDate <= $maxDate){
				$events = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','=',$currentDate)->orderBy('titulo','ASC')->get();
		
				if (count($events) > 0) {
					$haveEvents = true; 
					$html .= '<tr style="border-bottom:1px solid #666">';
				
					$html .= '<td width="10%">';
					$html .= '<div style="color:blue">';
					$html .= 	Date::dateTohuman($currentDate,'EN','-');
					$html .= '</div>';
					$html .= '</td>';
					$html .= '<td width="90%">';
					$html .= '<table width="100%" style="border-collapse:separate;">';
					foreach ($events as $event) {
						switch ($event->estado) {
							case 'pendiente':
								$class = "alert alert-danger";
								break;
							default:
								$class = 'alert alert-success';
								break;
						}
						$classLink = '';
						if (Date::isPrevTodayByTimeStamp(Date::getTimeStampEN($currentDate))) {
							$class = "alert alert-warning";
							$classLink = 'disabled';
						}
						$html .= '<tr class="'.$class.'" id="'.$event->id.'">';
						$html .= '<td style="border:1px dotted #aaa">';
						$html .= '<div style="" width="20%">';								
						$html .= 	strftime('%H:%M',strtotime($event->horaInicio)) .'-'.strftime('%H:%M',strtotime($event->horaFin));
						$html .= '</div>';
						$html .= '</td>';	
						$html .= '<td width="50%" style="text-align:left;border:1px dotted #aaa" >';
						$recurso = Recurso::find($event->recurso_id);
						$html .= '<a href="#" class="agendaLinkTitle linkEvent" data-id-serie="'.$event->evento_id.'"" style="margin:10px;margin-left:0px;display:block"><span class="caret"></span> '. htmlentities($event->titulo) . '</a>';
						$html .= '<div class="agendaInfoEvent" style = "margin:0px;margin-left:0px;margin-top:0px;width:100%;padding:5px;padding-left:0px">';
						$html .= '<p style="border-top:1px solid #eee;margin:0px"><strong>Actividad: </strong>'. $event->actividad;
						$html .= 	', ';
						$html .= '<strong>Estado: </strong>'.$event->estado . '</p>';
						$html .= '<p class="AgendaAction" style="border-bottom:1px solid #eee" >';
						$html .= '<ul class="nav nav-pills">';

						$html .= '<li class = "'.$classLink.'"><a class = "comprobante" href="'.URL::route('justificante',array('idEventos' => $event->evento_id)).'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'" title="Comprobante" target="_blank"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></a></li>';

						//
        				 
						$html .= '<li class = "'.$classLink.'"><a href="" class="agendaEdit edit_agenda_'.$event->id.'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'">Editar</a></li>';
						//$html .= ' | ';
						$html .= '<li class = "'.$classLink.'"><a href="#" class="delete_agenda" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'" >Eliminar</a></li>';
						$html .= '</span>';
						$html .= '</ul>';
						$html .= '</div>';
						$html .= '</td>';
						$html .= '<td width="30%" style="border:1px dotted #aaa">';
						$html .=  $recurso->nombre .' <small>('. $recurso->grupo.')</small>';//: '.$recurso->nombre;
						$html .= '</td>';
						$html .= '</tr>';
					} //fin foreach
					$html .= '</table>';
					$html .= '</td>';
					$html .= '</tr>';
				}//fin count(events)
				$lastDate = $currentDate;
				$currentDate=Date::nextDay($currentDate);
			}//fin while $currentDate <= $maxDate
			$html .= '<tr style="">';
			$html .= '<td colspan="2">';
			$html .= '<div class="alert alert-success" role="alert">';
			$html .= 	'Se muestran los eventos programados hasta el <strong>'. Date::dateTohuman($lastDate,'EN','-').'</strong>';
			$html .= ' [ <a href=""  class="alert-link" id="agendaVerMas" data-date="'.Date::nextDay($lastDate).'">Ver más</a> ]';
			$html .= '</div>';
			$html .= '</td>';
			$html .= '</tr>';
		}
		else{
			$html = '<tr><td><div class="alert alert-danger pull-left col-sm-12" role="alert" id="alert_evento"><strong> No hay eventos</strong></div></td></tr>';
		}		
		//$events->setBaseUrl('/laravel/public/home/user/calendario?year='.$year);
		return $html;
	}

	public static function getBodytableMonth($mon,$year,$id_recurso = ''){

		$self = new self();
		$html = '';		
		$daysOfMonth = Date::getDays($mon,$year);
		foreach ($daysOfMonth as $week) {
      		$html .= '<tr class="fila">';
      			foreach($week as $day){
	       			$html .= '<td class="celda">';
       				if ($self->isDayOtherMonth($day)) $html .= $self->getContentDisable_td($day,$mon,$year);
       				else {
        					if($self->isFestivo($day,$mon,$year)) $html .= $self->getContentTDFestivo($day,$mon,$year);
        					else{
        						//No es un día de otro mes y no es festivo: entonces
        						if($self->isDayAviable($day,$mon,$year)){ //Depende del rol
        							$events = $self->getEvents($day,$mon,$year,$id_recurso);
									$enabled = true;
									$html .= $self->getContentTD($day,$mon,$year,$id_recurso,$events,$enabled);
        						}
        						else {
        							//$html .= $self->getCellDisable($day);	
		   							$events = $self->getEvents($day,$mon,$year,$id_recurso);
		   							$enabled = false;
									$html .= $self->getContentTD($day,$mon,$year,$id_recurso,$events,$enabled);
        						}         						
        					}
        				}
        			$html .= '</td>';
        		}
        	$html .= '</tr>';
        }	
 		return $html;
	}//getBodyTableMonth

	public static function getPrintBodytableMonth($data,$mon,$year,$id_recurso = ''){

		$self = new self();
		$html = '';		
		$daysOfMonth = Date::getDays($mon,$year);
		$diaSemanaPrimerDiaMesActual = date('N',mktime(0,0,0,$mon,'1',$year));//1 -> lunes... 7->domingo
		$currentDiaUltimaSemanaMesAnterior = 0;
		$numeroDediasPrimeraSemanaMesAnterior = $diaSemanaPrimerDiaMesActual - 1; //puede ser cero si el mes empieza en lunes
		foreach ($daysOfMonth as $week) {
      		$html .= '<tr>';
      			foreach($week as $day){
      				$ancho ='18%';
      				if ($day == 0) {
      					$currentDiaUltimaSemanaMesAnterior++;
      					$diff = $currentDiaUltimaSemanaMesAnterior - $diaSemanaPrimerDiaMesActual;
      					$diaSemana = date('N',strtotime($diff . ' days',mktime(0,0,0,$mon,'1',$year)));
      					if ($diaSemana == '6' || $diaSemana == '7') $ancho = '3%';
      					$html .= '<td width="'.$ancho.'" >';
       					$html .= $self->getContentDisable_td($day,$mon,$year);
       					$html .= '</td>';
      				}
      				else {
      					if($self->isFestivo($day,$mon,$year)) {
      						$ancho = '3%';
        					$html .= '<td width="'.$ancho.'" >';
        					$html .= $self->getContentTDFestivo($day,$mon,$year);
        					$html .= '</td>';
        				}
        				else{
        					$html .= '<td width="'.$ancho.'">';
        					$events = $self->getEvents($day,$mon,$year,$id_recurso);
		   					$enabled = false;
							$html .= $self->getContentTDtoPrint($data,$day,$mon,$year,$id_recurso,$events,$enabled);
        					$html .= '</td>';
        					}
        				}
        			
        		}
        	$html .= '</tr>';
        }	
 		return $html;
	}

	private function getContentTDtoPrint($data,$day,$mon,$year,$id_recurso,$events,$hour=0,$min=0,$view='month',$enabled='false')	{
		
		$html = '';
		$self = new self();
		$aColorLink = array();

		
        if ($view == 'month' && $day <= Date::daysMonth($mon,$year)) $html .= '<small>'. $day .'</small>' ;
        
        
        
       foreach($events as $event){

        	if ($event->estado == 'denegada'){
        		$style = "color:#A94442;";

        	}
        	else if ($event->estado == 'aprobada'){
        		$style = "color:#2B542C;";
        	}
        	else {
				$hi = date('H:i:s',strtotime($event->horaInicio));
				$hf = date('H:i:s',strtotime('+1 hour',strtotime($event->horaInicio)));
	        	$where  = "fechaEvento = '".date('Y-m-d',mktime(0,0,0,$mon,$day,$year))."' and ";
	        	$where .= "estado != 'denegada' and ";
	        	$where .= "evento_id != '".$event->evento_id."' and ";
				$where .= " (( horaInicio <= '".$hi."' and horaFin > '".$hi."' ) "; 
				$where .= " or ( horaFin > '".$hf."' and horaInicio < '".$hf."')";
				$where .= " or ( horaInicio > '".$hi."' and horaInicio < '".$hf."')";
				$where .= " or (horaFin < '".$hf."' and horaFin > '".$hi."'))";
				$nSolapamientos = Recurso::find($id_recurso)->events()->whereRaw($where)->count();
	        	
				if ($nSolapamientos > 0){
					//text-danger
					$style = "color:#A94442;";
				}
				else {
					//text-info
					$style = "color:#245269;";			
				}
			} 

        	$time = mktime($hour,$min,0,$mon,$day,$year);
        	
        	$classEstado = '';
        	if($event->estado == 'aprobada')  $classEstado = "alert alert-success";
        	if($event->estado == 'pendiente') $classEstado = "alert alert-danger";

        	
        	$muestraItem = '';
        	if ($event->recursoOwn->tipo != 'espacio') {
        		$numRecursos = Evento::where('evento_id','=',$event->evento_id)->where('recurso_id','!=',$event->recurso_id)->where('fechaEvento','=',$event->fechaEvento)->count();
        		if ($numRecursos > 0) {
        			$muestraItem =  ' ('.($numRecursos + 1). ' ' .$event->recursoOwn->tipo.'s)';}
        		else $muestraItem =  ' ('.$event->recursoOwn->nombre.')';
        	}
			

        	$tipoReserva = 'Reserva Periódica';
        	if ($event->repeticion == 0) $tipoReserva = 'Reserva Puntual';

        	($view != 'week') ? $strhi = Date::getstrHour($event->horaInicio).'-'. Date::getstrHour($event->horaFin) : $strhi = '';
        	$classPuedeEditar = '';
        	
        	$own = Evento::find($event->id)->userOwn;
    		
    		$showInfo = $self->setinfo($data,$event);
        	$textLink = '<p style ="'.$style.'"><i>'. $strhi.'</i> '.$showInfo .'</p>';
        	
        	$html .= $textLink;
        	
        }//fin del foreach ($events as $event)
        
 		
		return $html;
	}
	
	private function getEvents($day,$mon,$year,$id_recurso){
		$events = '';
		$strDate = date('Y-m-d',mktime(0,0,0,$mon,$day,$year));
		
		//si "reservar todo"
		$valueGrupo_id = Input::get('groupID');
		if ($id_recurso == 0 && !empty($valueGrupo_id)){
			//Vista "todos los equipos//puestos"
			$recursos = Recurso::where('grupo_id','=',$valueGrupo_id)->get();
			$alist_id = array();
			foreach($recursos as $recurso){
				$alist_id[] = $recurso->id;
			}

			$events = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->get();

			//Bug PODController, quitar el año q viene
			$userPOD = User::where('username','=','pod')->first(); 
			//$eventoTest = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->first();
			$idPOD = $userPOD->id;
			
			$iduser = 0;
			foreach ($events as $event) {
			 	$iduser = $event->user_id;
			 } 
			if ( $iduser == $idPOD ) $events = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('horaInicio')->groupby('titulo')->get();

		}
		else{
			//Vista un puesto o equipo
			$events = Evento::where('recurso_id','=',$id_recurso)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->get();	
		}
		
		
		return $events;
	}

	private function getEventsViewWeek($day,$mon,$year,$id_recurso,$hour,$min){
		
		$currentTimeStamp = mktime(0,0,0,$mon,$day,$year);
		$events = array();

		$date = date('Y-m-d',$currentTimeStamp);
        $hi = date('H:i:s',mktime($hour,$min,0,0,0,0));
				//si "reservar todo"
        $valueGrupo_id = Input::get('groupID');
		if ($id_recurso == 0 && !empty($valueGrupo_id)){
			$recursos = Recurso::where('grupo_id','=',$valueGrupo_id)->get();
			$alist_id = array();
			foreach($recursos as $recurso){
				$alist_id[] = $recurso->id;
			}
			//$alist_id = array('6','9');
			$events = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$date)->where('horaInicio','<=',$hi)->where('horaFin','>',$hi)->groupby('evento_id')->get();

			//Bug PODController, quitar el año q viene
			$userPOD = User::where('username','=','pod')->first(); 
			//$eventoTest = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->first();
			$idPOD = $userPOD->id;
			
			$iduser = 0;
			foreach ($events as $event) {
			 	$iduser = $event->user_id;
			 } 
			if ( $iduser == $idPOD ) $events = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$date)->where('horaInicio','<=',$hi)->where('horaFin','>',$hi)->groupby('titulo')->get();

		}
		else{
			$events = Evento::where('recurso_id','=',$id_recurso)->where('fechaEvento','=',$date)->where('horaInicio','<=',$hi)->where('horaFin','>',$hi)->get();
		}

		return $events;
	}
	private function setinfo($data,$event){
		
		$showInfo = 'No se ha seleccionado información a mostrar';
        $info = '';
        if ($data['titulo'] == 'true') 	$info = '-'.$event->titulo;
        if ($data['nombre'] == 'true') 	$info .= '-'.$event->userOwn->nombre . ' ' . $event->userOwn->apellidos;
        if ($data['colectivo'] == 'true') $info .= '-' .$event->userOwn->colectivo;
		if ($data['total'] == 'true' && $event->total() > 0) $info .= '-' . $event->total() . ' ' .$event->recursoOwn->tipo . '/s';
		if (!empty($info)) $showInfo = $info;

		return $showInfo;
	}

	public static function getBodytableWeek($day,$month,$year,$id_recurso){

		$html = '';
		$self = new self();

		//timeStamp lunes semana de $day - $month -$year seleccionado por el usuario
		$timefirstMonday = Date::timefirstMonday($day,$month,$year);
		//número de día del mes del lunes de la semana seleccionada
		$firstMonday = date('j',$timefirstMonday);
	
		for($j=0;$j<count($self->aHour)-1;$j++) {

			$hour = // $itemsHours[0];
			
      		$html .= '<tr>';
      		$html .= '<td style="width:10px;text-align:center;font-weight: bold;" class="week">'.$self->aHour[$j].'-'.$self->aHour[$j+1];
      		$html .= '</td>';
      		$currentTime = $timefirstMonday;
      		for($i=0;$i<7;$i++){
      			$html .= '<td class="celda">';
      			//$currentTime = mktime(0,0,0,$month,($firstMonday + $i),$year);
      			
      			//$currentDay = $firstMonday + $i;
      			//$html .= $currentDay;
      			$currentDay = date('j',$currentTime);
      			$currentMon = date('n',$currentTime);
      			$currentYear = date('Y',$currentTime);
				if($self->isFestivo($currentDay,$currentMon,$currentYear)) $html .= $self->getContentTDFestivo($currentDay,$currentMon,$currentYear,'week');
				//else $html .= '<div>'.$hour.'</div>';
				else{	
					//Los días disponibles para reserva depende del rol de usuario
					if( $self->isDayAviable($currentDay,$currentMon,$currentYear) ){
					
						$startHour = $self->aHour[$j];
						$itemsHours = explode(':',$startHour);
						$hour = $itemsHours[0];		
						$events = $self->getEventsViewWeek($currentDay,$currentMon,$currentYear,$id_recurso,$hour,30);
						$html .= $self->getContentTD($currentDay,$currentMon,$currentYear,$id_recurso,$events,$hour,30,'week'); 
					}
					else { $html .= $self->getCellDisable($currentDay,'week');}
					
				}
				$html .='</td>';
				$currentTime = strtotime('+1 day',$currentTime);
			}
			$html .= '</tr>';
		}
		return $html;
	}//getBodyTableWeek

	public static function getPrintBodytableWeek($data,$day,$month,$year,$id_recurso){

		$html = '';
		$self = new self();

		//timeStamp lunes semana de $day - $month -$year seleccionado por el usuario
		$timefirstMonday = Date::timefirstMonday($day,$month,$year);
		//número de día del mes del lunes de la semana seleccionada
		$firstMonday = date('j',$timefirstMonday);
	
		for($j=0;$j<count($self->aHour)-1;$j++) {

			$hour = // $itemsHours[0];
			
      		$html .= '<tr>';
      		$html .= '<td style="width:80px;vertical-align:middle"  ALIGN="middle"><small>'.$self->aHour[$j].'-'.$self->aHour[$j+1].'</small>';
      		$html .= '</td>';
      		$currentTime = $timefirstMonday;
      		for($i=0;$i<5;$i++){
      			$html .= '<td>';
      			
      			$currentDay = date('j',$currentTime);
      			$currentMon = date('n',$currentTime);
      			$currentYear = date('Y',$currentTime);
				if($self->isFestivo($currentDay,$currentMon,$currentYear)) $html .= $self->getContentTDFestivo($currentDay,$currentMon,$currentYear,'week');
				//else $html .= '<div>'.$hour.'</div>';
				else{	
					//Los días disponibles para reserva depende del rol de usuario
					if( $self->isDayAviable($currentDay,$currentMon,$currentYear) ){
					
						$startHour = $self->aHour[$j];
						$itemsHours = explode(':',$startHour);
						$hour = $itemsHours[0];		
						$events = $self->getEventsViewWeek($currentDay,$currentMon,$currentYear,$id_recurso,$hour,30);
						$html .= $self->getContentTDtoPrint($data,$currentDay,$currentMon,$currentYear,$id_recurso,$events,$hour,30,'week'); 
					}
					else { $html .= $self->getCellDisable($currentDay,'week');}
					
				}
				$html .='</td>';
				$currentTime = strtotime('+1 day',$currentTime);
			}
			$html .= '</tr>';
		}
		return $html;
	}//getPrintBodyTableWeek

	public static function getCaption($day = '',$month = '',$year = ''){

		$caption = '<span id="alternate">'.Date::getNameMonth($month,$year).' / '.$year.'</span>';
		return $caption;
	}

	public static function gettHead($viewActive='month',$day='',$month='',$year=''){

		
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$table['tBody']="error setlocale";}

		$self = new self();	  		
		
		$html = '';
		switch ($viewActive) {
			case 'month':
				$html .='<tr>
					        <th>Lunes</th>
					        <th>Martes</th>
					        <th>Miércoles</th>
					        <th>Jueves</th>
					        <th>Viernes</th> 
					        <th class="hidden-print">Sabado</th>
					        <th class="hidden-print">Domingo</th>
					    </tr>';
			    break;
			case 'week':
				$timefirstMonday = Date::timefirstMonday($day,$month,$year);// strtotime('Monday this week',mktime(0,0,0,$month,$day,$year));	
				$numOfMonday = date('j',$timefirstMonday); //Número del mes 1-31
				//$month = date('n',$timefirstMonday);
				//$year = date('Y',$timefirstMonday);
				$html .='<tr><th></th>';
				for($i=0;$i<7;$i++){
					//$time = Date::timeStamp(($numOfMonday + $i),$month,$year);
					$time = strtotime('+'.$i.' day',$timefirstMonday);	
					//strftime('%a, %d/%b',$time)
					$text = $self->aAbrNameDaysWeek[date('N',$time)] . ', '.strftime('%d/%b',$time);
					$html .= '<th style = "white-space:nowrap;font-size-adjust:none">'.$text.'</th>';
				}
				$html .='</tr>';
			    break;
			case 'agenda':
				$html .='<tr>
							<th>Fecha</th>
							<th>Horario</th>
							<th>información</th>
			         	</tr>';
				break;
			default:
				$html = 'error al generar cabecera de tabla';
			break;
		}
		
		
		return $html;
	}

	public static function getPrintHead($viewActive='month',$day='',$month='',$year=''){

		
		if(!setlocale(LC_ALL,'es_ES@euro','es_ES','esp')){
			  		$table['tBody']="error setlocale";}

		$self = new self();	  		
		
		$html = '';
		switch ($viewActive) {
			case 'month':
				$html .='<tr >
					        <th style="border:1px solid green;height:40px" width="18%">Lunes</th>
					        <th style="border:1px solid green;height:40px" width="18%">Martes</th>
					        <th style="border:1px solid green;height:40px" width="18%">Miércoles</th>
					        <th style="border:1px solid green;height:40px" width="18%">Jueves</th>
					        <th style="border:1px solid green;height:40px" width="18%">Viernes</th> 
					        <th style="border:1px solid green;height:40px" width="3%">S</th> 
					        <th style="border:1px solid green;height:40px" width="3%">D</th> 
					    </tr>';
			    break;
			case 'week':
				$timefirstMonday = Date::timefirstMonday($day,$month,$year);// strtotime('Monday this week',mktime(0,0,0,$month,$day,$year));	
				$numOfMonday = date('j',$timefirstMonday); //Número del mes 1-31
				//$month = date('n',$timefirstMonday);
				//$year = date('Y',$timefirstMonday);
				$html .='<tr><th style="width:80px;height:20px">Hora</th>';
				for($i=0;$i<5;$i++){
					//$time = Date::timeStamp(($numOfMonday + $i),$month,$year);
					$time = strtotime('+'.$i.' day',$timefirstMonday);	
					//strftime('%a, %d/%b',$time)
					$text = $self->aAbrNameDaysWeek[date('N',$time)] . ', '.strftime('%d/%b',$time);
					$html .= '<th>'.$text.'</th>';
				}
				$html .='</tr>';
			    break;
			case 'agenda':
				$html .='<tr>
							<th>Fecha</th>
							<th>Horario</th>
							<th>información</th>
			         	</tr>';
				break;
			default:
				$html = 'error al generar cabecera de tabla';
			break;
		}
		
		
		return $html;
	}//getPrintHead

	public static function hasSolapamientos($evento_id,$id_recurso){
		
		$result = false;

		$events = Evento::where('evento_id','=',$evento_id)->get();
		foreach ($events as $event) {

			$where  =	"fechaEvento = '".$event->fechaEvento."' and ";
			$where .= 	" (( horaInicio <= '".$event->horaInicio."' and horaFin >= '".$event->horaFin."' ) "; 
			$where .= 	" or ( horaFin > '".$event->horaFin."' and horaInicio < '".$event->horaFin."')";
			$where .=	" or ( horaInicio > '".$event->horaInicio."' and horaInicio < '".$event->horaFin."')";
			$where .=	" or horaFin < '".$event->horaFin."' and horaFin > '".$event->horaInicio."')";
			$where .= 	" and evento_id != '".$evento_id."'";
			
			$numSolapamientos = Recurso::find($id_recurso)->events()->whereRaw($where)->count();
				
			if($numSolapamientos > 0) $result = true;
		
		}
		return $result;			
	}

	public static function getNumSolapamientos($idRecurso,$currentfecha,$hi,$hf,$condicionEstado = ''){
		
		$numSolapamientos = 0;
		
		$hi = date('H:i:s',strtotime($hi));
		$hf = date('H:i:s',strtotime($hf));

		//si estamos editando un evento => Existe Input::get('idEvento'), hay que excluir para poder modificar por ejemplo en nombre del evento
		$idEvento = Input::get('idEvento');
		$option = Input::get('option');
		$action = Input::get('action');
		$excludeEvento = '';
		//if ($action == 'edit') $excludeEvento = " and id != '".$idEvento."'";

		//Excluye eventos de la misma serie en cualquier espacio para poder cambiar el nombre a reservas tanto de un solo equipo//puesto o espacio como a reservas de todos los equipos/puestos
		$idSerie = Input::get('idSerie');
		$excludeEvento = '';
		if (!empty($idSerie) && $action == 'edit') $excludeEvento = " and evento_id != '".$idSerie."'";


		$where  =	"fechaEvento = '".Date::toDB($currentfecha,'-')."' and ";
		if (!empty($condicionEstado))	$where .=	"estado = '".$condicionEstado."' and ";	
		$where .= 	" (( horaInicio <= '".$hi."' and horaFin > '".$hi."' ) "; 
		$where .= 	" or ( horaFin > '".$hf."' and horaInicio < '".$hf."')";
		$where .=	" or ( horaInicio > '".$hi."' and horaInicio < '".$hf."')";
		$where .=	" or horaFin < '".$hf."' and horaFin > '".$hi."')";
		$where .= 	$excludeEvento;
		$numSolapamientos = Recurso::find($idRecurso)->events()->whereRaw($where)->count();
		
		//$numSolapamientos = 1;
		return $numSolapamientos;
	}
	
	//functions private
	private function getHour($j){
		$hour = '';		
		$startHour = $self->aHour[$j];
		$itemsHours = explode(':',$startHour);
		$hour = $itemsHours[0];
		return $hour;
	} 

	private  function isFestivo($day,$mon,$year){
		$isfestivo = false;
		if ( Date::isDomingo($day,$mon,$year) || Date::isSabado($day,$mon,$year) ) $isfestivo = true;
        return $isfestivo;
	}
	
	public static function testgetContentTD($day,$mon,$year,$id_recurso){
		$self = new self();
		$events = Evento::where('recurso_id','=',$id_recurso)->get();
		return $self->getContentTD($day,$mon,$year,$id_recurso,$events,0,0,$view='month',$enabled='false');
	}

	private function getContentTD($day,$mon,$year,$id_recurso,$events,$hour=0,$min=0,$view='month',$enabled='false'){
		$html = '';
		$self = new self();
		$aColorLink = array();

		//Establece el estilo de las diferentes celdas del calendario
		if(Date::isPrevToday($day,$mon,$year) || !$self->isDayAviable($day,$mon,$year)) $class = 'day '.$view.' disable';
        else { 	$class = 'day '.$view.' formlaunch';}
		
		$html .= '<div class = "'.$class.'" id = '.date('jnYGi',mktime($hour,$min,0,$mon,$day,$year)).' data-fecha="'.date('j-n-Y',mktime($hour,$min,0,$mon,$day,$year)).'" data-hora="'.date('G:i',mktime($hour,$min,0,$mon,$day,$year)).'">';

        if ($view == 'month' && $day <= Date::daysMonth($mon,$year)) $strTitle = '<small>'. $day .'</small>';
        else $strTitle = '';
        
        $html .= '<div class="titleEvents">'.$strTitle.'</div>';
        $html .= '<div class="divEvents" data-numero-de-eventos="'.count($events).'">';
        
        //condición ? si true : si false
        ($view == 'week') ? $limit = 4 : $limit = 4;
        count($events) > $limit ? $classLink='mas' : $classLink='';       
       	
       	if (count($events) > $limit) $html .= '<a style="display:none" class="cerrar" href="">Cerrar</a>';
        foreach($events as $event){

        	if ($event->estado == 'denegada'){
        		$class_danger = 'text-warning';
				$alert = '<span data-toggle="tooltip" title="Solicitud denegada" class=" fa fa-ban fa-fw text-warning" aria-hidden="true"></span>';
        	}
        	else if ($event->estado == 'aprobada'){
        		$class_danger = 'text-success';
				$alert = '<span data-toggle="tooltip" title="Solicitud aprobada" class=" fa fa-check fa-fw text-success" aria-hidden="true"></span>';
        	}
        	
        	else {
				$hi = date('H:i:s',strtotime($event->horaInicio));
				$hf = date('H:i:s',strtotime('+1 hour',strtotime($event->horaInicio)));
	        	$where  = "fechaEvento = '".date('Y-m-d',mktime(0,0,0,$mon,$day,$year))."' and ";
	        	$where .= "estado != 'denegada' and ";
	        	$where .= "evento_id != '".$event->evento_id."' and ";
				$where .= " (( horaInicio <= '".$hi."' and horaFin > '".$hi."' ) "; 
				$where .= " or ( horaFin > '".$hf."' and horaInicio < '".$hf."')";
				$where .= " or ( horaInicio > '".$hi."' and horaInicio < '".$hf."')";
				$where .= " or (horaFin < '".$hf."' and horaFin > '".$hi."'))";
				$nSolapamientos = Recurso::find($id_recurso)->events()->whereRaw($where)->count();
	        	
				if ($nSolapamientos > 0){

					$class_danger = 'text-danger';
					$alert = '<span data-toggle="tooltip" title="Solicitud con solapamiento" class="fa fa-exclamation fa-fw text-danger" aria-hidden="true"></span>';
				}
				else {

					$class_danger = 'text-info';
					$alert = '<span data-toggle="tooltip" title="Solicitud pendiente de validación" class="fa fa-question fa-fw text-info" aria-hidden="true"></span>';				
				}
			} 

        	$title = htmlentities($alert . ' <span class = "title_popover '.$class_danger.' ">' . htmlentities($event->titulo) . '</span><span><a href="" class="closePopover"> X </a></span>');
        	$time = mktime($hour,$min,0,$mon,$day,$year);
        	
        	$classEstado = '';
        	if($event->estado == 'aprobada')  $classEstado = "alert alert-success";
        	if($event->estado == 'pendiente') $classEstado = "alert alert-danger";

        	
        	$muestraItem = '';
        	$numRecursos = 0;
        	if ($event->recursoOwn->tipo != 'espacio') {
        		$numRecursos = $event->total();//Evento::where('evento_id','=',$event->evento_id)->where('recurso_id','!=',$event->recurso_id)->where('fechaEvento','=',$event->fechaEvento)->where('horaInicio','=',$event->horaInicio)->count();
        		

				//Bug PODController, quitar el año q viene
				$userPOD = User::where('username','=','pod')->first(); 
				//$eventoTest = Evento::whereIn('recurso_id',$alist_id)->where('fechaEvento','=',$strDate)->orderBy('horaInicio','asc')->groupby('evento_id')->first();
				$idPOD = $userPOD->id;
				$iduser = 0;
				$iduser = $event->user_id;
				if ( $iduser == $idPOD ) {
					$recursos = Recurso::where('grupo_id','=',$event->recursoOwn->grupo_id)->get();
					$alist_id = array();
					foreach($recursos as $recurso){
						$alist_id[] = $recurso->id;
					}

					$numRecursos = Evento::whereIn('recurso_id',$alist_id)->where('recurso_id','!=',$event->recurso_id)->where('fechaEvento','=',$event->fechaEvento)->where('horaInicio','=',$event->horaInicio)->where('titulo','=',$event->titulo)->count();

				}
				//fin del bug

        		if ($numRecursos > 0) $muestraItem =  ' ('.($numRecursos). ' ' . $event->recursoOwn->tipo.'/s)';
        		else $muestraItem =  ' ('.$event->recursoOwn->nombre.')';
        	}
			

        	//$tipoReserva = 'Reserva Periódica';
        	//if ($event->repeticion == 0) $tipoReserva = 'Reserva Puntual';
        	$tipoReserva = Config::get('options.tiporeserva')[$event->repeticion];

        	$contenido = htmlentities('<p style="width=100%;text-align:center" class="'.$classEstado.'">Estado:<strong> '.ucfirst($event->estado).'</strong>'.$muestraItem.'</p><p style="width=100%;text-align:center">'.ucfirst(strftime('%a, %d de %B, ',$time)). Date::getstrHour($event->horaInicio).' - ' .Date::getstrHour($event->horaFin) .'</p><p style="width=100%;text-align:center">'.$event->actividad.'</p><p style="width=100%;text-align:center">'.$tipoReserva.'</p><p style="width=100%;text-align:center">'.$event->userOwn->nombre .' ' .$event->userOwn->apellidos. '</p>');
        	
        	($view != 'week') ? $strhi = Date::getstrHour($event->horaInicio).'-'. Date::getstrHour($event->horaFin) : $strhi = '';
        	$classPuedeEditar = '';
        	
        	//Default text link
        	$textLink = '<strong>'. $strhi.'</strong> '.htmlentities($event->titulo);

        	//Si usuario autenticado puede editar el evento:
        	$contenido .= htmlentities('<hr />');
        	if($self->puedeEditar(Auth::user()->id,$event->user_id,$event->reservadoPor_id) && $self->isDayAviable($day,$mon,$year)){
        		$contenido .= htmlentities('
        				<a class = "comprobante" href="'.URL::route('justificante',array('idEventos' => $event->evento_id)).'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'" title="Comprobante" target="_blank"><span class="fa fa-file-pdf-o fa-fw text-success" aria-hidden="true"></span></a>
        				 |
        				<a href="#" id="edit_'.$event->id.'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'" title="Editar reserva"><span class="fa fa-pencil fa-fw text-success" aria-hidden="true"></span></a>
        				 |
        				<a href="#" id="delete" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-periodica="'.$event->repeticion.'" title="Eliminar reserva"><span class="fa fa-trash fa-fw text-success" aria-hidden="true"></span></a>
        				|');
        		//$classPuedeEditar = 'puedeEditar';
        		$textLink = '<strong>'. $strhi.'</strong> '.htmlentities($event->titulo);
        	}
        	/*
        	else{
        		//$classPuedeEditar = 'noEdit';
        		$textLink = '<strong>'. $strhi.'</strong> '.htmlentities($event->titulo);// $strhi.' '.$own->apellidos.', '.$own->nombre;
        	}*/
        	
        	//Si usuario autenticado puede liberar evento (anular por técnico)
        	/*if ($self->puedeAnular($event)) {
        		$contenido .= htmlentities('
        				
        				<a  href="#"  class="libera" id="libera_'.$event->id.'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-titulo="'.$event->titulo.'" data-usuario="'.$event->userOwn->nombre.'" data-periodica="'.$event->repeticion.'" title="Liberar reserva"><span class="fa fa-eraser fa-fw text-warning" aria-hidden="true"></span></a>
        				|
        				<a  href="#" class="finaliza" id="finaliza_'.$event->id.'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-titulo="'.$event->titulo.'" data-usuario="'.$event->userOwn->nombre.'" data-periodica="'.$event->repeticion.'" title="Finalizar reserva"><span class="fa fa-clock-o fa-fw text-warning" aria-hidden="true"></span></a>');	

        	}*/
        	if ($self->puedeFinalizar($event)) {
        		$contenido .= htmlentities('
        				<a  href="#" class="finaliza" id="finaliza_'.$event->id.'" data-id-evento="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-titulo="'.$event->titulo.'" data-usuario="'.$event->userOwn->nombre.'" data-periodica="'.$event->repeticion.'" title="Finalizar reserva"><span class="fa fa-clock-o fa-fw text-warning" aria-hidden="true"></span></a>');	

        	}
        	
        	
        	$html .= '<div class="divEvent" data-fecha="'.date('j-n-Y',mktime($hour,$min,0,$mon,$day,$year)).'" data-hora="'.substr($event->horaInicio,0,2).'" >';
        	
        	$html .= '<a class = " '.$class_danger.' linkpopover linkEvento '.$event->evento_id.'  '.$event->id.'" id="'.$event->id.'" data-id-serie="'.$event->evento_id.'" data-id="'.$event->id.'"  href="" rel="popover" data-html="true" data-title="'.$title.'" data-content="'.$contenido.'" data-placement="auto right"> ' . $alert . ' ' . $textLink.'</a>';
        	
        	$html .='</div>';
        }//fin del foreach ($events as $event)
        
        $html .= '</div>'; //Cierre div.divEvents
 		 if (count($events) > $limit) $html .= '<a class="linkMasEvents" href=""> + '.(count($events)-$limit).'  más </a>';
		$html .='</div>'; //Cierra div con id = idfecha

		return $html;
	}//getContentTD

	private function puedeFinalizar($event){
		$puede = false;
		$self = new self();
		if ($self->userPuedeFinalizar($event->recursoOwn->id) && $self->eventoEsFinalizable($event->fechaEvento,$event->horaInicio,$event->horaFin)) $puede = true;
		return $puede;
	}

	private function userPuedeFinalizar($idrecurso){
		$userPuedeFinalizar = false;
		
		//Auth::user tiene que atender las reservas en el recurso donde se realiza el evento
		$recursos = Auth::user()->atiende;
		if ($recursos->contains($idrecurso)) {
			$userPuedeFinalizar = true;
		}

		return $userPuedeFinalizar;
	}

	private function eventoEsFinalizable($fechaEvento,$horaInicio,$horaFin){
		$eventoEsFinalizable = false;
		
		if ( strtotime($fechaEvento) == strtotime(date('Y-m-d')) && (strtotime($horaInicio) + Config::get('options.tiempocortesia')) < strtotime(date('H:i')) && strtotime($horaFin) > strtotime(date('H:i')) ) $eventoEsFinalizable = true;
		
		return $eventoEsFinalizable;
	}


	private function puedeAnular($event){
		$puede = false;

		//$recurso = $event->recursoOwn->id;
		$recursos = Auth::user()->atiende;

		if ($recursos->contains($event->recursoOwn->id)) {
			$puede = true;
		}

		return $puede;
	}

	private function puedeEditar($id_currentloginuser,$id_reservadoPara,$id_reservadoPor){
		$puede = false;
		//User es propietario de la reserva
		if($id_currentloginuser == $id_reservadoPara) $puede = true;
		//fin
		//user es administrador y reserva es de POD
		$userPOD = User::where('username','=','pod')->first();
		$idUserPOD = 0;
		if (!empty($userPOD)) $idUserPOD=$userPOD->id;
		if(User::find($id_currentloginuser)->capacidad == 4 && $id_reservadoPor == $idUserPOD) $puede = true;
		//fin
		//UserAuth es técnico (capacidad 3) y realizó la reserva para otro usuario
		if(User::find($id_currentloginuser)->capacidad == 3 &&  $id_currentloginuser == $id_reservadoPor) $puede = true;
		return $puede;
	}

	private function isDayOtherMonth($day){
		$otherMonth = false;
		if($day == 0) $otherMonth = true;
		return $otherMonth;
	}

	
	private function isDayAviable($day,$mon,$year,$view = 'month'){
		$isAviable = false;
		$self = new self();
		$capacidad = Auth::user()->capacidad;
		switch ($capacidad) {
				case '1': //alumnos
					$intfristMondayAviable = $self->fristMonday();
					$intlastFridayAviable = $self->lastFriday();
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year);
					if ($intCurrentDate >= $intfristMondayAviable && $intCurrentDate <= $intlastFridayAviable) $isAviable = true;
					break;	
				case '2': //pdi & pas administración
					$intfristMondayAviable = $self->fristMonday(); //Primer lunes disponible
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year); // fecha del evento a valorar
					if ($intCurrentDate >= $intfristMondayAviable) $isAviable = true;
					break;
				case '3': //Técnicos MAV
				case '4': //administradores SGR
				case '5': //Validadores
				case '6': //Supervisores (EE MAV)
					$intfristdayAviable = strtotime('today'); //Hoy a las 00:00
					$intCurrentDate = mktime(0,0,0,$mon,$day,$year); // fecha del evento a valorar
					if ($intCurrentDate >= $intfristdayAviable) $isAviable = true;
					break;
		}
		

		
		return $isAviable;
	}
	
	private function getContentDisable_td($day,$mon,$year){
		$html = '';
		$lastDay = Date::daysMonth($mon,$year);
		$html = '<div  class = "day disable">';
		if ($day <= $lastDay && 0 != $day) $html .=  '<small>'. $day .'</small>';
		$html .= '</div>';
		return $html;
	}
	
	private function getCellDisable($day,$view = 'month'){
		if ($view == 'month') $strTitle = $day;
        else $strTitle = '';
		$html = '<div  class = "day '.$view.' disable">'. $strTitle .'</div>';
		return $html;
	}

	private function getContentTDFestivo($day,$mon,$year,$view='month'){
		$idfecha = date('jnY',mktime(0,0,0,$mon,$day,$year));
		if ($view == 'month' && $day <= Date::daysMonth($mon,$year)) $strTitle = '<small>'. $day .'</small>';
        else $strTitle = '';
		$html = '<div  id='.$idfecha.' class = "day '.$view.' festivo disable hidden-print"  data-fecha="'.date('j-n-Y',mktime(0,0,0,$mon,$day,$year)).'">'. $strTitle .'</div>';
		return $html;
	}
	
	private function randomColor() {
	    $str = '#';
	    for($i = 0 ; $i < 6 ; $i++) {
	        $randNum = rand(0 , 15);
	        switch ($randNum) {
	            case 10: $randNum = 'A'; break;
	            case 11: $randNum = 'B'; break;
	            case 12: $randNum = 'C'; break;
	            case 13: $randNum = 'D'; break;
	            case 14: $randNum = 'E'; break;
	            case 15: $randNum = 'F'; break;
	        }
	        $str .= $randNum;
	    }
	    return $str;
	}

	
 	/**
	 *
	 *		@param void 
	 *		@return  $l timestamp del lunes de la primera semana reservable a partir del día actual
	*/
	public static function fristMonday(){
		
		$l = '';
		//Parámetros
		$lastDay = Config::get('options.ant_ultimodia'); //por defecto el jueves (dia 4 de la semana)
		$n = Config::get('options.ant_minSemanas'); 
		//día actual
		$today = date('Y-m-d');
		$numWeekCurrentDay = date('N');//,strtotime($today));//1 lunes,... 7 domingo
		
		//Si es de lunes a jueves 
		if ($numWeekCurrentDay <= $lastDay){
	   		// y si la fecha de realización de la reserva está entre las fechas de la semana siguiente a la actual
	   		$l = strtotime('next monday ' . $today ); //lunes semana siguiente
		}
		else {
			// y si la reserva está entre las fechas de 2ª semana posterior a la actual 
			$l = strtotime('next monday ' . $today .' +1 week'); //lunes de la 2ª semana siguiente
	   	}

	   return $l;
	
	}

	/**
	 *		@param void 
	 *		@return  $v timestamp del viernes de la primera semana reservable a partir del día actual
	*/
	
	public static function lastFriday(){

		$v = '';
		//Parámetros
		$lastDay = Config::get('options.ant_ultimodia'); //por defecto el jueves (dia 4 de la semana)
		$n = Config::get('options.ant_minSemanas'); 
		//día actual
		$today = date('Y-m-d');
		$numWeekCurrentDay = date('N');//,strtotime($today));//1 lunes,... 7 domingo
		
		//Si es de lunes a jueves 
		if ($numWeekCurrentDay <= $lastDay){
		   // y si la fecha de realización de la reserva está entre las fechas de la semana siguiente a la actual
		   $v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes semana siguinte
		// si es viernes, sabado o domingo   
		}
		else {
		  	$v = strtotime('next friday ' . $today . '+'.$n.' week');//viernes la n-esima semana siguinte
		}

		return $v;

	}
}