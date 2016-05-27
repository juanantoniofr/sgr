<?php

class PodController extends BaseController {

	private $warningSolapesCSV	= array();
	private $warningSolapesDB	= array();
	private $success		 	= array();
	private $warningNoLugar		= array();

	public function index(){
	
		return View::make('admin.pod')->nest('dropdown','admin.dropdown');	
	}

	public function savePOD(){

		
		$numFila = 1;

		$csv = new csv();
		
		$file = Input::file('csvfile'); //controlar que no sea vacio !!!!!
		if (empty($file)){
			$msgEmpty = "No se ha seleccionado ningún archivo *.csv"; 
			return View::make('admin.pod')->with(compact('msgEmpty'));	
		}


		$f = fopen($file,"r");
		
		while (($fila = fgetcsv($f,0,';','"')) !== false){
			//$content es un array donde cada posición almacena los valores de las columnas del csv
			$result = array();
			$columnIdLugar = $csv->getNumColumnIdLugar();
			$id_lugar = $fila[$columnIdLugar];
			
			$datosfila = $csv->filterFila($fila); //nos quedamos con las columnas que hay que guardar en la Base de Datos.

			if( $this->existeLugar($id_lugar) ){
				//Conjunto de espacios a reservas: si el espacio tiene puestos, $recursos contiene cada uno de ellos.
				$recursos = Recurso::where('id_lugar','=',$id_lugar)->get(); 
				

				//Comprueba si el csv tiene solapamientos
				if ($this->existeSolapamientocsv($datosfila,$file,$numFila)){
					$this->warningSolapesCSV[$numFila] = $datosfila;
				}
				elseif ($this->existeSolapamientodb($datosfila,$numFila,$recursos)){
					$this->warningSolapesDB[$numFila] = $datosfila;
				}
				else{
					//identificador único de la serie de eventos
					do {
						$evento_id = md5(microtime());
					} while (Evento::where('evento_id','=',$evento_id)->count() > 0);					

					foreach ($recursos as $recurso) {
						$this->save($datosfila,$numFila,$recurso,$evento_id);
					}
					$this->success[$numFila] = $datosfila;
				}
			}
			else 
				$this->warningNoLugar[$numFila] = $datosfila;
			
			$numFila++;
			
		}
		//$fila = fgetcsv($f,0,';','"');
		fclose($f);
		//$errores = $csv->getErroresLugar();
		//return View::make('admin.test')->with(compact('fila'));
		return View::make('admin.pod')->with(array('events' => $this->success,'noexistelugar' => $this->warningNoLugar,'solapesdb' => $this->warningSolapesDB,'solapescsv' => $this->warningSolapesCSV));
	}

	private function save($data,$numFila,$recurso,$evento_id){
		
		$result = true;
		$fechaDesde = sgrDate::dateCSVtoSpanish($data['F_DESDE_HORARIO1']);
		$fechaHasta = sgrDate::dateCSVtoSpanish($data['F_HASTA_HORARIO1']);
		
		$nRepeticiones = sgrDate::numRepeticiones($fechaDesde,$fechaHasta,$data['COD_DIA_SEMANA']);

		
		
		

		for($j=0;$j < $nRepeticiones; $j++ ){ //foreach 
				$evento = new Evento();
	
				//evento periodico o puntual??			
				if ($nRepeticiones == 1) $evento->repeticion = 0;
				else $evento->repeticion = 1;

				
				$evento->evento_id = $evento_id;
				//fechas de inicio y fin
				$evento->fechaFin = sgrDate::parsedatetime(sgrDate::dateCSVtoSpanish($data['F_HASTA_HORARIO1']),'d-m-Y','Y-m-d');//¿por que, no es $fechaDesde ya calculado??
				$evento->fechaInicio = sgrDate::parsedatetime(sgrDate::dateCSVtoSpanish($data['F_DESDE_HORARIO1']),'d-m-Y','Y-m-d');
				
				//fecha Evento
				$startDate = sgrDate::timeStamp_fristDayNextToDate(sgrDate::dateCSVtoSpanish($data['F_DESDE_HORARIO1']),$data['COD_DIA_SEMANA'],'Y-m-d');
				$currentfecha = sgrDate::fechaEnesimoDia($startDate,$j);
				$evento->fechaEvento = sgrDate::parsedatetime($currentfecha,'d-m-Y','Y-m-d');

				//horario
				$evento->horaInicio = $data['INI'];
				$evento->horaFin = $data['FIN'];

				//obtner identificador de recurso (espacio o medio)
				//$evento->recurso_id = $this->getRecursoByIdLugar($data['ID_LUGAR']);
				$evento->recurso_id = $recurso->id;
				
				$evento->estado = 'aprobada';
				//código día de la semana
				$evento->diasRepeticion = json_encode($data['COD_DIA_SEMANA']);
				$evento->dia = $data['COD_DIA_SEMANA'];
			
				$evento->titulo = $data['ASIGNATURA'] . ' - ' . $data['NOMCOM'];
				$evento->asignatura = $data['ASIGNATURA'];
				$evento->profesor = $data['NOMCOM'];
				$evento->actividad = 'Docencia Reglada P.O.D';
				
				$evento->dia = $data['COD_DIA_SEMANA'];
				//Asignamos a usuario que carga el pod
				$userPOD = User::where('username','=','pod')->first(); 
				//$evento->user_id = Auth::user()->id;
				$evento->user_id = $userPOD->id;
				$evento->save();
			
		}//fin foreach
		

		return $result;
	}

	private function existeLugar($idLugar){
		$result = false;

			$recurso = Recurso::where('id_lugar','=',$idLugar)->get();

			if($recurso->count() > 0) $result = true;

		return $result;
	}

	/*private function getRecursoByIdLugar($idLugar){
		
		$result = false;

			$recurso = Recurso::where('id_lugar','=',$idLugar)->get();

			
			$result = $recurso[0]->id;
		

		return $result;
	}*/
	private function existeSolapamientodb($data,$numFila,$recursos){
		
	
		$fechaDesde = sgrDate::dateCSVtoSpanish($data['F_DESDE_HORARIO1']);
		$fechaHasta = sgrDate::dateCSVtoSpanish($data['F_HASTA_HORARIO1']);
		
		$nRepeticiones = sgrDate::numRepeticiones($fechaDesde,$fechaHasta,$data['COD_DIA_SEMANA']);

		for($j=0;$j < $nRepeticiones; $j++ ){ //foreach 
			
			//fecha Evento
			$startDate = sgrDate::timeStamp_fristDayNextToDate($fechaDesde,$data['COD_DIA_SEMANA'],'Y-m-d');
			$currentfecha = sgrDate::fechaEnesimoDia($startDate,$j);
			$sgrEvento = new sgrEvento;
			foreach ($recursos as $recurso) {
				if ( 0 < $sgrEvento->solapa($recurso->id,$currentfecha,$data['INI'],$data['FIN']) ){
					//hay solape: fin -> return true
					return true;
					}	
			}//fin foreach cada puesto o equipo, (espacio el array recurso solo contiene un elemento)
		}//fin foreach repeticion periodica
		

		return false;
	}

	private function existeSolapamientocsv($data,$file,$numFila){
		
		$solapamientos = false;
		$csv = new csv();
		//estado del evento?? (solapamientos)
		$idLugar = $data['ID_LUGAR'];
		$fechaDesde = sgrDate::dateCSVtoSpanish($data['F_DESDE_HORARIO1']);//d-m-Y
		$fechaHasta = sgrDate::dateCSVtoSpanish($data['F_HASTA_HORARIO1']);//d-m-Y
		$horaInicio = $data['INI'];
		$horaFin = $data['FIN'];
		$diaSemana = $data['COD_DIA_SEMANA'];
		
		$f = fopen($file,"r");
		
		$contadorfila = 1;
		
		
		while (($fila = fgetcsv($f,0,';','"')) !== false && !$solapamientos){
						
			$datosfila = $csv->filterFila($fila);
			if ($datosfila['ID_LUGAR'] == $idLugar && $datosfila['COD_DIA_SEMANA'] == $diaSemana && $contadorfila != $numFila){
				//posible solapamiento
				$filafechaDesde = sgrDate::dateCSVtoSpanish($datosfila['F_DESDE_HORARIO1']);//d-m-Y
				$filafechaHasta = sgrDate::dateCSVtoSpanish($datosfila['F_HASTA_HORARIO1']);//d-m-Y
				$filahoraInicio = $datosfila['INI'];
				$filahoraFin = $datosfila['FIN'];
				if (strtotime(sgrDate::parsedatetime($fechaDesde,'d-m-Y','Y-m-d')) <= strtotime(sgrDate::parsedatetime($filafechaHasta,'d-m-Y','Y-m-d')) &&
					strtotime(sgrDate::parsedatetime($fechaHasta,'d-m-Y','Y-m-d')) >= strtotime(sgrDate::parsedatetime($filafechaDesde,'d-m-Y','Y-m-d')) ){
						//posible solapamiento
						if(strtotime($horaInicio) < strtotime($filahoraFin) && strtotime($horaFin) > strtotime($filahoraInicio)){
							//hay solapamiento
							$solapamientos = true;
							
						}//tercer if
					}//segundo if
			} //primer if
				
			
			$contadorfila++;
		}//fin del while
		fclose($f);	
		
		return $solapamientos;
	}

	private function delPOD(){
		$userPOD = User::where('username','=','pod')->first();
		$filasAfectadas = Evento::where('user_id','=',$userPOD->id)->delete();
	}
}