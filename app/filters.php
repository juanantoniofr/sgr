<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
/*		El filtro req2 permite bloquear el acceso al calendario para reservar a los alumnos con el número de horas máximo a la semana (12h) agotado
*/

//Developed
/*Route::filter('auth', function()
{
	//$user = User::find('30');
	//Auth::login($user);
	if (Auth::guest()) return Redirect::to(route('loginsso'));
	
});*/
Route::filter('auth', function()
{
	
    if (!Cas::isAuthenticated() || !Auth::check()) 
    	if (Request::ajax()) {

    		return Response::make('Necesitas iniciar sesión de nuevo. Por favor, recarga la página', 401);
    	}
    	else return Redirect::to(route('wellcome'));
});

//Comprueba si la petición se realizó por ajax y el usaurio está autenticado
Route::filter('ajax_check',function(){
		
	if(!Request::ajax()) return Redirect::to(route('wellcome'));
	
});

//Comprobar si el usuario autentivcado tiene privilegios para realizar la acción requerida
Route::filter('capacidad',function($ruta,$peticion,$capacidad,$redirect) {
	
	$roles  = explode("-",$capacidad);
	if (!in_array(Auth::user()->capacidad, $roles)){
		$msg = 'Privilegios insuficientes';
		$title = 'Error de acceso';
		return Redirect::to($redirect)->with(compact('title','msg'));
	}
});

//Comprobar si el sistema permite a los usarios registrar reservas.
Route::filter('inicioCurso',function(){

	if (Auth::user()->isUser() || Auth::user()->isAvanceUser()){
		$hoy = strtotime('today');
		$diaInicio = strtotime(Config::get('options.inicio_gestiondesatendida'));
		if ($diaInicio > $hoy) {
			$title = 'Acceso limitado';
			$msg = 'No es posible realizar reservas hasta que se finalice la carga del POD: fecha prevista a partir del día ' . date('d-m-Y',strtotime(Config::get('options.inicio_gestiondesatendida')));
			return Redirect::to('loginerror')->with(compact('title','msg'));
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to(ACL::getHome());
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});



