<?php

class csv{

	private $columns = array(	'F_HASTA' 			=>	'0', //fecha inicio
								'F_DESDE'			=>	'1', //fecha fin
								'AUL_CODNUM'		=>	'2',
								'ID_LUGAR'			=>	'3', //identifcador de lugar
								'DESTPA'			=>	'4',
								'DESAUL'			=>	'5',
								'PERSONA_RES'		=>	'6',
								'ASS_CODNUM1'		=>	'7',
								'COD_DIA_SEMANA'	=>	'8',
								'INI'				=>	'9', //hora inicio
								'FIN'				=>	'10', //hora fin
								'COD_DIA'			=>	'11', //día de la semana 1->lunes, .... 5->viernes
								'ASIGNATURA'		=>	'12',
								'COD'				=>	'13',
								'PER_CODNUM1'		=>	'14',
								'HOR_CODNUM1'		=>	'15',
								'DES_GRP'			=>	'16',
								'F_DESDE_HORARIO1'	=>	'17',//fecha inicio!!
								'F_HASTA_HORARIO1'	=>	'18',//fecha fin!!
								'EJE_CODNUM1'		=>	'19',
								'FRN_CODNUM1'		=>	'20',
								'DIA_SEMANA'		=>	'21',
								'DIA'				=>	'22',
								'EJE_CODNUM'		=>	'23',
								'HOR_CODNUM'		=>	'24',
								'PER_CODNUM'		=>	'25',
								'DSM_CODNUM'		=>	'26',
								'FRN_CODNUM'		=>	'27',
								'NOMCOM'			=>	'28', //Profesor
								 );

	private $columnValidas = array(	'ID_LUGAR',		//identificador de lugar
									'F_DESDE_HORARIO1',		//fecha fin
									'F_HASTA_HORARIO1',		//fecha inicio
									'DIA_SEMANA',	//Día de la semana (texto)
									'INI',			//hora inicio
									'FIN',			//hora fin
									'DESAUL',		//lugar
									'ASIGNATURA',	//Asignatura
									'NOMCOM', 		//Profesor
									'COD_DIA_SEMANA', //dia de la semana numñerico, lunes->1, martes->2,...									
								 );

	private $errors = array (	'noexistelugar'		=> array(),
								'formatonovalido'	=> array(),
								'haysolapamiento'	=> array());

		

	public function __construct(){

	}

	public function getNumColumnIdLugar(){

		return $this->columns['ID_LUGAR'];
		
	}

	

	public function filterFila($fila){
		
		$result = array();

		foreach ($this->columnValidas as $keyValida) {
			$numColumn = $this->getnumColumn($keyValida); //obtengo el indice de una columna valida
			$result[$keyValida] = $fila[$numColumn];
		}
		
		return $result;

	}

	private function getnumColumn($key){
		$numColumn = '';

		$numColumn = $this->columns[$key];

		return $numColumn; 
	}
}

?>