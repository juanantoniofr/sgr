<?php
/* marca branch master2 */
class csv{

	private $columns = array(	'F_HASTA' 			=>	'0', //fecha inicio
								'F_DESDE'			=>	'1', //fecha fin
								'AUL_CODNUM'		=>	'2',
								'ID_LUGAR'			=>	'3', //identifcador de lugar
								'DESTPA'			=>	'4',
								'DESAUL'			=>	'5',
								'ASS_CODNUM1'		=>	'6',
								'COD_DIA_SEMANA'	=>	'7',
								'INI'				=>	'8', //hora inicio
								'FIN'				=>	'9', //hora fin
								'COD_DIA'			=>	'10', //día de la semana 1->lunes, .... 5->viernes
								'ASIGNATURA'		=>	'11',
								'COD'				=>	'12',
								'PER_CODNUM1'		=>	'13',
								'HOR_CODNUM1'		=>	'14',
								'DES_GRP'			=>	'15',
								'F_DESDE_HORARIO1'	=>	'16',//fecha inicio!!
								'F_HASTA_HORARIO1'	=>	'17',//fecha fin!!
								'EJE_CODNUM1'		=>	'18',
								'FRN_CODNUM1'		=>	'19',
								'DIA_SEMANA'		=>	'20',
								'DIA'				=>	'21',
								'EJE_CODNUM'		=>	'22',
								'HOR_CODNUM'		=>	'23',
								'PER_CODNUM'		=>	'24',
								'DSM_CODNUM'		=>	'25',
								'FRN_CODNUM'		=>	'26',
								'NOMCOM'			=>	'27', //Profesor
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