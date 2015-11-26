<?php

return array (

	//Último día de la semana en curso para poder reservar para la semana siguiente (en este caso es el jueves día 4 de la semana)
	'ant_ultimodia' => '4', 

	//Dias de antelación minima (7 - ant_minDias)
	'ant_minDias' => '3',

	//Número de semanas de antelación minima (en este caso Una semana)
	'ant_minSemanas' => '1',

	//Máximo de horas a la semana para usuarios del perfil alumno
	'max_horas'	=> '12',
 
	//eventos que generan mail
	'required_mail' => array('add' => 0,
							 'edit' => 1,
							 'del'	=> 2,
							 'allow' => 3,
							 'deny' => 4,
							 'request' => 5,
							 ),

	'fin_cursoAcademico' => '2016-07-15',
	
	'inicio_gestiondesatendida' => '2015-09-1',
	
	//definición de perfiles (roles//capacidades)
	'perfiles' => array(	'1' =>	'Usuarios (Alumnos)',
							'2'	=>	'Usuarios Avanzados (PDI & PAS de Administración)',
							'3'	=>	'Tecnicos (PAS Técnico MAV)',
							'4'	=>	'Administradores de SGR',
							'5'	=>	'Validadores (Dirección-Decanato)',
							'6'	=>	'Supervisores (Responsable Unidad)',
							),
	
	'gestionAtendida' 	=> 'Atendida (requiere validación)',
	'gestionDesatendida' => 'Desatendida (sin validación)', 
	'colectivos' => array('PAS','PDI','Alumno'),

	);
	

?>