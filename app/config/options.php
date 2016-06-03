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
	'required_mail' => array(	'add' => 0,
							 							'edit' => 1,
							 							'del'	=> 2,
							 							'allow' => 3,
							 							'deny' => 4,
							 							'request' => 5,
							 						),

	'fin_cursoAcademico' => '2016-07-15',
	
	'inicio_gestiondesatendida' => '2015-05-1',
	
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
	'tipoReserva' => array ( '1'	=>	'Reserva periódica',
							 '0'	=>	'Reserva puntual',),

	'estadoEventos' => array(	'0' =>	'denegada',
														'1'	=>	'aprobada',
														'2'	=>	'pendiente',
														'3'	=>	'finalizada',
														'4'	=>	'anulada',
														'5'	=>	'liberada',),
	'estadosEvento' => array(	'denegada','aprobada','pendiente','finalizada','anulada','liberada',),
	'reservaAprobada'		=> 'aprobada',
	'reservaPendiente'	=> 'pendiente',
	//tiempo cortesia para liberar una reserva (20 minutos) en segundos
	'tiempocortesia'	=> '1200',
	'horarioApertura'  	=> array('8:30','9:30','10:30','11:30','12:30','13:30','14:30','15:30','16:30','17:30','18:30','19:30','20:30','21:30'),
	'horaIntervalo'  	=> array('8:30','9:00','9:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30'),
	'modoGestion'		=> array('0','1'), //0 => con validación, 1 => sin validación
	'idroladministrador'	=> '4', 
	
	'recursos'						=> 	array('espacio','tipoequipos','puesto','equipo'),	
	'tipoGrupos'					=> 	array('espacio','tipoequipos'),
	'tipoRecursos'				=> 	array('espacio' => 'Espacios','tipoequipos' => 'Equipos'),
	'defaulttiporecurso' 	=> 	'espacio',
	'tipoItem'						=>  array('puesto' => 'puesto','equipo' => 'equipo'),
	'defaultitem'				 	=> 	'equipo',
	'espacio'							=> 	'espacio',
	'equipo'							=> 	'equipo',
	'puesto'							=> 	'puesto',
	'tipoequipos'					=>	'tipoequipos',
		
	'defaultview'					=>	'month',
	'viewsCalendar'				=> 	array('month','week','year','day'),
	'intervalo'				  	=> array('8:30' => 0,'9:00' => 1,'9:30' => 2,'10:00' => '3','10:30' => 4,'11:00' => 5,'11:30' => 6,'12:00' => 7,'12:30' => 8,'13:00' => 9,'13:30' => 10,'14:00' => 11,'14:30' => 12,'15:00' => 13,'15:30'	=> 14,'16:00' => 15,'16:30' => 16,'17:00' => 17,'17:30' => 18,'18:00' => 19,'18:30' => 20,'19:00' => 21,'19:30' => 22,'20:00' => 23,'20:30' => 24,'21:00' => 25,'21:30'=> 26),
	'pxintervalo'					=> '82',

	);
?>