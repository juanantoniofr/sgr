<?php

class Calendar {
  
    private $aHour = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
    private $aDaysWeek = array('lunes','martes','miércoles','jueves','viernes','sabado','domingo');
	
	//private $aAbrNameDaysWeek = array('1'=>'Lun','2'=>'Mar','3'=>'Mie','4'=>'Jue','5'=>'Vie','6'=>'Sab','7'=>'Dom');

	public static function getBodyTableAgenda($day,$month,$year){
		
		$html = '';
		$startDate = date('Y-m-d',strtotime($year . '-' . $month .'-'. $day));
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
		
			$currentDate=$startDate;
			//$lastDate = $startDate;
			while($currentDate <= $maxDate){
				$events = Evento::where('user_id','=',Auth::user()->id)->where('fechaEvento','=',$currentDate)->orderBy('titulo','ASC')->get();
		
				if (count($events) > 0) {
					$haveEvents = true; 
					$html .= '<tr style="border-bottom:1px solid #666">';
				
					$html .= '<td width="10%">';
					$html .= '<div style="color:blue">';
					$html .= 	date('d-m-Y',strtotime($currentDate));//Date::dateTohuman($currentDate,'EN','-');
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
						if (Date::isPrevTodayByTimeStamp(strtotime($currentDate))) {
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
			
			$lastDate = $currentDate;
			$html .= '<tr style="">';
			$html .= '<td colspan="2">';
			$html .= '<div class="alert alert-success" role="alert">';
			$html .= 	'Se muestran los eventos programados hasta el <strong>'. date('d-m-Y',strtotime($lastDate)).'</strong>';
			$html .= ' [ <a href=""  class="alert-link" id="agendaVerMas" data-date="'.Date::nextDay($lastDate).'">Ver más</a> ]';
			$html .= '</div>';
			$html .= '</td>';
			$html .= '</tr>';
		}
		else{
			$html = '<tr><td><div class="alert alert-danger pull-left col-sm-12" role="alert" id="alert_evento"><strong> No hay eventos</strong></div></td></tr>';
		}		
		return $html;
	}

	/*public static function getBodytableMonth($mon='',$year='',$id_recurso = ''){

		$self = new self();
		$html = '';

		$sgrCalendario = new sgrCalendario($mon,$year);
		$days = $sgrCalendario->dias();
		//N -> Representación numérica ISO-8601 del día de la semana (añadido en PHP 5.1.0): 1 (para lunes) hasta 7 (para domingo)
		$diaSemanaPimerDiaMes = date('N',$days[1]->timestamp());
		//$daysOfMonth = sgrCalendario::dias($mon,$year);
		$diaActual = 1;
		$j=1;
		$mon = (int) $mon;
		$year = (int) $year;
		//$html = '';

		$html = (string) View::make('calendario.bodyMonth')->with('sgrCalendario',$sgrCalendario)->with('mon',$mon)->with('year',$year)->with('diaActual',$diaActual)->with('j',$j)->with('diaSemanaPimerDiaMes',$diaSemanaPimerDiaMes)->with('days',$days)->with('id_recurso',$id_recurso);
		return $html;


		while (mktime(0,0,0,(int) $mon,(int) $sgrCalendario->ultimoDia(),(int) $year) >= mktime(0,0,0,(int) $mon,$diaActual,(int) $year) ){
			//una fila por cada semana del mes
			$html .= '<tr class="fila">';
			for($i=1;$i<=7;$i++){
				//Una celda por cada día de la semama
				$html .= '<td class="celda">';
					//días de la primera semana y de la última que no son del mes $mon-$year.
					if (($diaSemanaPimerDiaMes > $i && $j == 1) || $diaActual > $sgrCalendario->ultimoDia()){
						$html .= (string) View::make('calendario.tdFestivo');
						
					}
					else {
						//Para los días de $mon-$year
						if($days[$diaActual]->festivo()){
							$idfecha = date('jnY',mktime(0,0,0,$mon,$diaActual,$year)); 
							$fecha = date('j-n-Y',mktime(0,0,0,$mon,$diaActual,$year));
							$html .= (string) View::make('calendario.tdFestivo')->with('idfecha',$idfecha)->with('fecha',$fecha)->with('view','month')->with('day',$diaActual)->with('festivo','festivo');
						} 
						else{
        					//No es un día de otro mes y no es festivo: entonces
        					$events = $self->getEvents($diaActual,$mon,$year,$id_recurso);
							
							$html .= (string) View::make('calendario.td')->with('view','month')->with('isDayAviable',Auth::user()->isDayAviable($diaActual,$mon,$year))->with('hour',0)->with('min',0)->with('mon',$mon)->with('day',$diaActual)->with('year',$year)->with('events',$events)->with('time',mktime(0,0,0,$mon,$diaActual,$year));
        				}
        				$diaActual++;		
					}//fin else linea 131
				$html .= '</td>';
				
			}
			$html .= '</tr>';
			$j++;
		}
 		return $html;
	}//getBodyTableMonth

	*/

	public static function getPrintBodytableMonth($data,$mon,$year,$id_recurso = ''){

		$self = new self();
		$html = '';		
		//$daysOfMonth = sgrCalendario::dias($mon,$year);
		
		//return  $mon ;
		$sgrCalendario = new sgrCalendario($mon,$year);
		$days = $sgrCalendario->dias();
		//$diaSemanaPrimerDiaMesActual = date('N',mktime(0,0,0,$mon,'1',$year));//1 -> lunes... 7->domingo
		$diaSemanaPrimerDiaMes = date('N',$days[1]->timestamp());
		$diaActual = 1;
		$j=1;
		while (mktime(0,0,0,$mon,$sgrCalendario->ultimoDia(),$year) >= mktime(0,0,0,$mon,$diaActual,$year) ){
			$html .= '<tr>';
			for($i=1;$i<=7;$i++){
				$ancho = '18%'; //ancho de celda por defecto
				
				//Una celda por cada día de la semama
				
				//días de la primera semana y de la última que no son del mes $mon-$year.
				if (($diaSemanaPrimerDiaMes > $i && $j == 1) || $diaActual > $sgrCalendario->ultimoDia()){
					if (isset($days[$diaActual]) && $days[$diaActual]->festivo()) $ancho = '3%';//si es festivo el ancho es 3%
					if ($diaActual > $sgrCalendario->ultimoDia()) $ancho = '3%';
      				
      				$html .= '<td width="'.$ancho.'" >';
      				$html .= (string) View::make('calendario.tdFestivo');
					$html .= '</td>';
				}
				else {
					if($days[$diaActual]->festivo()){
						$idfecha = date('jnY',mktime(0,0,0,$mon,$diaActual,$year)); 
						$fecha = date('j-n-Y',mktime(0,0,0,$mon,$diaActual,$year));
						$ancho = '3%';//si es festivo el ancho es 3%
						$html .= '<td width="'.$ancho.'" >';
						$html .= (string) View::make('calendario.tdFestivo')->with('idfecha',$idfecha)->with('fecha',$fecha)->with('view','month')->with('day',$diaActual)->with('festivo','festivo');
						$html .= '</td>';
					}
					else{
						//No es un día de otro mes y no es festivo: entonces
        				$events = $self->getEvents($diaActual,$mon,$year,$id_recurso);
						$html .= '<td width="'.$ancho.'" >';
						$html .= $self->getContentTDtoPrint($data,$diaActual,$mon,$year,$id_recurso,$events);
						$html .= '</td>';
        			}
        			$diaActual++;
				}//fin else
				
				
			}//fin for
			$html .= '</tr>';
			$j++;
		}//fin while
		return $html;
	}


	private function getContentTDtoPrint($data,$day,$mon,$year,$id_recurso,$events,$hour=0,$min=0,$view='month',$enabled='false')	{
		
		$html = '';
		$self = new self();
		$aColorLink = array();

		
        //if ($view == 'month' && $day <= sgrCalendario::dias($mon,$year)) $html .= '<small>'. $day .'</small>' ;
        if ($view == 'month') $html .= '<small>'. $day .'</small>' ;

        
        
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

        	($view != 'week') ? $strhi = Date::parsedatetime($event->horaInicio,'H:i:s','g:i').'-'. Date::parsedatetime($event->horaFin,'H:i:s','g:i') : $strhi = '';
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
		$strDate = date('Y-m-d',mktime(0,0,0,(int) $mon,(int) $day,(int) $year));
		
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

	public static function getBodytableWeek($day,$month,$year,$id_recurso,$id_grupo='0'){

		$html = '';
		$self = new self();

		$horas = array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30');
		

		$sgrWeek = new sgrWeek($day,$month,$year);

		return (string) View::make('calendario.bodyWeek')->with('horas',$horas)->with('sgrWeek',$sgrWeek)->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo);
		//{{ (string) View::make('calendario.testtd')->with('view','week')->with('isDayAviable',Auth::user()->isDayAviable($sgrWeek->dia($i)->numerodia(),$sgrWeek->dia($i)->mes(),$sgrWeek->dia($i)->year()))->with('hour',$horas[$j])->with('min',30)->with('mon',$sgrWeek->dia($i)->mes())->with('day',$sgrWeek->dia($i)->numerodia())->with('year',$sgrWeek->dia($i)->year())->with('time',$sgrWeek->dia($i)->timestamp())->with('currentday',$sgrWeek->dia($i))->with('id_recurso',$id_recurso)->with('id_grupo',$id_grupo) }}
		
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
      			$sgrDia = $sgrCalendario->dia($currentDay);
      			$currentMon = date('n',$currentTime);
      			$currentYear = date('Y',$currentTime);
				if($sgrDia->festivo()) {
					$idfecha = date('jnY',mktime(0,0,0,$currentMon,$currentDay,$currentYear)); 
					$fecha = date('j-n-Y',mktime(0,0,0,$currentMon,$currentDay,$currentYear));
					$html .= (string) View::make('calendario.tdFestivo')->with('idfecha',$idfecha)->with('fecha',$fecha)->with('view','week')->with('festivo','festivo');
				}
				else{	
					//Los días disponibles para reserva depende del rol de usuario
					if( Auth::user()->isDayAviable($currentDay,$currentMon,$currentYear) ){
					
						$startHour = $self->aHour[$j];
						$itemsHours = explode(':',$startHour);
						$hour = $itemsHours[0];		
						$events = $self->getEventsViewWeek($currentDay,$currentMon,$currentYear,$id_recurso,$hour,30);
						
						$html .= (string) View::make('calendario.td')->with('view','week')->with('isDayAviable',Auth::user()->isDayAviable($currentDay,$currentMon,$currentYear))->with('hour',$hour)->with('min',30)->with('mon',$currentMon)->with('day',$currentDay)->with('year',$currentYear)->with('events',$events)->with('time',mktime(0,0,0,$currentMon,$currentDay,$currentYear));
						
					}
					else { //$html .= $self->getCellDisable($currentDay,'week');
							$html .= (string) View::make('calendario.tdFestivo')->with('view','week');
					}
					
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
		$timefirstMonday = sgrDate::timestamplunesanterior($day,$month,$year);
		//número de día del mes del lunes de la semana seleccionada
		$firstMonday = date('j',$timefirstMonday);
		$sgrCalendario = new sgrCalendario($month,$year);
		for($j=0;$j<count($self->aHour)-1;$j++) {

			$hour = // $itemsHours[0];
			
      		$html .= '<tr>';
      		$html .= '<td style="width:80px;vertical-align:middle"  ALIGN="middle"><small>'.$self->aHour[$j].'-'.$self->aHour[$j+1].'</small>';
      		$html .= '</td>';
      		$currentTime = $timefirstMonday;
      		for($i=0;$i<5;$i++){
      			$html .= '<td>';
      			
      			$currentDay = date('j',$currentTime);
      			$sgrDia = $sgrCalendario->dia($currentDay);
      			$currentMon = date('n',$currentTime);
      			$currentYear = date('Y',$currentTime);
				if($sgrDia->festivo()){
					$idfecha = date('jnY',mktime(0,0,0,$currentMon,$currentDay,$currentYear)); 
					$fecha = date('j-n-Y',mktime(0,0,0,$currentMon,$currentDay,$currentYear));
					$html .= (string) View::make('calendario.tdFestivo')->with('idfecha',$idfecha)->with('fecha',$fecha)->with('view','week')->with('festivo','festivo');
					$html .= $self->getContentTDFestivo($currentDay,$currentMon,$currentYear,'week');
				}
				else{	
					//Los días disponibles para reserva depende del rol de usuario
					if( Auth::user()->isDayAviable($currentDay,$currentMon,$currentYear) ){
					
						$startHour = $self->aHour[$j];
						$itemsHours = explode(':',$startHour);
						$hour = $itemsHours[0];		
						$events = $self->getEventsViewWeek($currentDay,$currentMon,$currentYear,$id_recurso,$hour,30);
						$html .= $self->getContentTDtoPrint($data,$currentDay,$currentMon,$currentYear,$id_recurso,$events,$hour,30,'week'); 
					}
					else { //$html .= $self->getCellDisable($currentDay,'week');
							$html .= (string) View::make('calendario.tdFestivo')->with('view','week');
					}
					
				}
				$html .='</td>';
				$currentTime = strtotime('+1 day',$currentTime);
			}
			$html .= '</tr>';
		}
		return $html;
	}//getPrintBodyTableWeek

	

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


		$where  =	"fechaEvento = '".Date::parsedatetime($currentfecha,'d-m-Y','Y-m-d')."' and ";
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

	
}