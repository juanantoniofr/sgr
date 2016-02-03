<?php

//req1: alumno solo pueden reservar entre firstMonday y lastFriday  
Validator::extend('req1', function($attribute, $value, $parameters){
	    return false;
	});   
//req2: alumno supera el máximo de horas a la semana (12)
Validator::extend('req2', function($attribute, $value, $parameters){
		return false;
	});

//req3: espacio ocupado (no solapamientos)
Validator::extend('req3', function($attribute, $value, $parameters){
	return false;
	});
//req4: no se puede reservar en sábados y domingos
Validator::extend('req4', function($attribute, $value, $parameters){
	return false;
	});
//req5: alumnos y pdi: solo pueden reservar a partir de firstmonday 
Validator::extend('req5', function($attribute, $value, $parameters){
	return false;
	});
//req6: tecnicos, validadores y supervisores: reservar a patir hoy (incluido) 
Validator::extend('req6', function($attribute, $value, $parameters){
	return false;
	});

//date_es: la fecha tiene formato d-m-y
Validator::extend('date_es',function($attributes,$value,$parameters){
	return false;
});
//date_finCurso: fecha máxima de reservas dentro del curso académico 
Validator::extend('datefincurso',function($attributes,$value,$parameters){
	return false;
});
//reservaUnica: alumnos no puden reservar dos equipos o puestos a la misma hora
Validator::extend('reservaunica',function($attributes,$value,$parameters){
	return false;
});

//existeuvus: al añadir un evento para uvus: debe existir en la base de datos.
Validator::extend('existeuvus',function($attributes,$value,$parameters){
	return false;
});

//recurso deshabilitado
Validator::extend('deshabilitado',function($attributes,$value,$parameters){
	return false;
});

?>