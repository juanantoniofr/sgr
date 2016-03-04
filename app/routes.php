<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('loginerror',array('as' => 'msg',function(){
	$msg = Session::get('msg');//Privilegios insuficientes';
	$title = Session::get('title');//Error de acceso';
	return View::make('loginerror')->with(compact('msg','title'));
}));

Route::get('msg',array('as' => 'msg',function(){
	$msg = Session::get('msg');//Privilegios insuficientes';
	$title = Session::get('title');//Error de acceso';
	return View::make('msg')->with(compact('msg','title'));
}));

Route::get('wellcome',array('as'=>'wellcome','uses' => 'HomeController@showWellcome'));

Route::get('ayuda.html',array('as'=>'ayuda','uses'=>'HomeController@ayuda'));

Route::get('contactar.html',array('as'=>'contactar','uses'=>'HomeController@contacto','before'=>'auth'));
Route::post('contactar.html',array('as'=>'enviaformulariocontacto','uses'=>'HomeController@sendmailcontact','before'=>'auth'));

Route::get('justificante', array('as' => 'justificante', function(){
	
	if (Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->count() == 0) return View::make('pdf.msg'); 

	$event = Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->first();
	$events = Evento::withTrashed()->where('evento_id','=',Input::get('idEventos'))->get();
		

	$recursos = Evento::where('evento_id','=',Input::get('idEventos'))->groupby('recurso_id')->get();
	
	setlocale(LC_TIME,'es_ES@euro','es_ES.UTF-8','esp');
	
   	$strDayWeek = sgrDate::getStrDayWeek($event->fechaEvento);
	$strDayWeekInicio = sgrDate::getStrDayWeek($event->fechaInicio);
	$strDayWeekFin = sgrDate::getStrDayWeek($event->fechaFin);
	$created_at = utf8_encode(ucfirst(strftime('%A %d de %B  a las %H:%M:%S',strtotime($event->created_at))));
   
    $html = View::make('pdf.justificante')->with(compact('event','events','strDayWeek','strDayWeekInicio','strDayWeekFin','recursos','created_at'));
   	
   	$result = myPDF::getPDF($html,'comprobante');

   	return Response::make($result)->header('Content-Type', 'application/pdf');
	})
);

Route::get('/',array('as' => 'loginsso','uses' => 'AuthController@doLogin'));
Route::get('logout',array('as' => 'logout','uses' => 'AuthController@doLogout'));


//Validador (roles 4 (admin) y 5 (validador))
Route::get('validador/home.html',array('as' => 'validadorHome.html','uses' => 'ValidacionController@index','before' => array('auth','capacidad:4-5,msg')));

Route::get('validador/valida.html',array('as' => 'valida.html','uses' => 'ValidacionController@valida','before' => array('auth','capacidad:4-5,msg')));

//Admin (rol = 4)
Route::get('admin/home.html',array('as' => 'adminHome.html','uses' => 'UsersController@home','before' => array('auth','capacidad:4,msg')));

//Gestión supervisores: rol -> admin (4)
Route::get('admin/usersWithRelation.html',array('uses' => 'recursosController@usersWithRelation','before' => array('auth','auth_ajax','capacidad:4,msg')));	
Route::post('admin/addUserWithRol',array('uses' => 'recursosController@addUserWithRol','before' => array('auth','auth_ajax','capacidad:4,msg')));
Route::post('admin/removeUsersWithRol',array('uses' => 'recursosController@removeUsersWithRol','before' => array('auth','auth_ajax','capacidad:4,msg')));


//routes gestión de usuarios
Route::get('admin/users.html',array('as' => 'users','uses' => 'UsersController@listUsers','before' => array('auth','capacidad:4,msg')));
Route::get('admin/user.html',array('uses' => 'UsersController@user','before' => array('auth','auth_ajax','capacidad:4,msg')));
Route::get('admin/editarUsuario.html',array('as' => 'updateUser.html','uses' => 'UsersController@edit','before' => array('auth','auth_ajax','capacidad:4,msg')));

Route::get('admin/adduser.html',array('as' => 'adduser','uses' => 'UsersController@newUser','before' => array('auth','capacidad:4,msg')));
//Route::post('admin/user/new',array('as' => 'post_addUser','uses' => 'UsersController@create','before' => array('auth','capacidad:4,msg')));
Route::post('admin/salvarNuevoUsuario',array('as' => 'post_addUser','uses' => 'UsersController@create','before' => array('auth','ajax_check','capacidad:4,msg')));





Route::get('admin/eliminaUser.html',array('as' => 'eliminaUser.html','uses' => 'UsersController@delete','before' => array('auth','capacidad:4,msg')));
Route::get('admin/ajaxBorraUser',array('as' => 'ajaxBorraUser','uses' => 'UsersController@ajaxDelete','before' => array('auth','capacidad:4,msg','ajax_check')));

//routes POD
Route::get('admin/pod.html',array('as' => 'pod.html','uses' => 'PodController@index','before' => array('auth','capacidad:4,msg')));
Route::post('admin/pod.html',array('as' => 'uploadPOD','uses' => 'PodController@savePOD','before' => array('auth','capacidad:4,msg')));

//routes logs & config
Route::get('admin/config.html',array('as' => 'config.html',function(){
			return View::make('admin.config')->nest('dropdown',Auth::user()->dropdownMenu());
			},
			'before' => array('auth','capacidad:4,msg')
		));

Route::get('admin/logs.html',array('as' => 'logs.html',function(){
			return View::make('admin.logs')->nest('dropdown',Auth::user()->dropdownMenu());
			},
			'before' => array('auth','capacidad:4,msg')
		));

//EE de equipo (capacidad = 6) y administradores de la aplicación (capacidad = 4)
Route::get('admin/addrecurso.html',array('as' => 'addRecurso','uses' => 'recursosController@formAdd','before' => array('auth','capacidad:4-6,msg')));
Route::get('admin/salvarNuevoRecurso',array('as' => 'postAddRecurso','uses' => 'recursosController@addRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg')));



Route::get('admin/listarecursos.html',array('as' => 'recursos','uses' => 'recursosController@listar','before' => array('auth','capacidad:4-6,msg')));





//Gestión de recursos
Route::get('admin/recursos.html',array('as' => 'getListadoGrupos','uses' => 'GruposController@listar','before' => array('auth','capacidad:4-6,msg')));
Route::get('admin/getTableGrupos',array('uses' => 'GruposController@getTable','before' => array('auth','ajax_check','capacidad:4-6,msg')));//devuelve tabla todos los grupos
Route::post('admin/addgrupo',array('uses' => 'GruposController@add','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Nuevo grupo
Route::post('admin/editgrupo',array('uses' => 'GruposController@edit','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Edita propiedades grupo (nombre y descripción)
Route::post('admin/delgrupo',array('uses' => 'GruposController@del','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Elimian grupo







Route::get('admin/editarecurso.html',array('as' => 'editarecurso.html','uses' => 'recursosController@formEdit','before' => array('auth','capacidad:4-6,msg')));
Route::post('admin/updateRecurso.html',array('uses' => 'recursosController@editRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Update propiedades recurso




Route::get('admin/eliminarecurso.html',array('uses'=>'RecursosController@eliminar','before' => array('auth','capacidad:4-6,msg')));
Route::post('admin/deshabilitarRecurso.html',array('uses'=>'RecursosController@deshabilitar','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::post('admin/habilitarRecurso.html',array('uses'=>'RecursosController@habilitar','before' => array('auth','capacidad:4-6,msg')));

Route::get('admin/getrecurso',array('uses'=>'RecursosController@getrecurso','before' => array('auth','capacidad:4-6,msg')));

//Calendarios
Route::get('calendarios.html',array('as' => 'calendarios.html','uses' => 'CalendarController@showCalendarViewMonth','before' => array('auth','inicioCurso')));
Route::get('ajaxCalendar',array('uses' => 'CalendarController@getTablebyajax','before' => array('auth','ajax_check')));


Route::get('getRecursos',array('as' => 'getRecursos','uses' => 'recursosController@getRecursos','before' => array('auth','ajax_check')));
Route::get('validador/ajaxDataEvent',array('uses' => 'CalendarController@ajaxDataEvent','before' =>array('auth','ajax_check') ));


//EventoController
Route::post('saveajaxevent',array('uses' => 'EventoController@save','before' => array('auth','ajax_check')));		
Route::post('editajaxevent',array('uses' => 'EventoController@edit','before' => array('auth','ajax_check')));

Route::get('geteventbyId',array('uses' => 'EventoController@getbyId','before' => array('auth','ajax_check')));
Route::get('tecnico/geteventbyId',array('uses' => 'EventoController@getbyId','before' => array('auth','ajax_check')));

Route::post('delajaxevent',array('uses' => 'EventoController@del','before' => array('auth','ajax_check')));
Route::post('finalizaevento',array('uses' => 'EventoController@finalizar','before' => array('auth','ajax_check')));
Route::post('anulaevento',array('uses' => 'EventoController@anular','before' => array('auth','ajax_check')));

//Atención de eventos
Route::get('tecnico/getUserEvents',array(	'uses' => 'EventoController@getUserEvents','before' => array('auth','capacidad:3-4,msg')));
Route::post('tecnico/saveAtencion',array('uses' => 'EventoController@atender','before' => array('auth','capacidad:3-4,msg')));






Route::post('admin/ajaxActiveUser',array('uses' => 'UsersController@activeUserbyajax','before' => array('auth','ajax_check')));
Route::post('admin/ajaxDesactiveUser',array('uses' => 'UsersController@desactiveUserbyajax','before' => array('auth','ajax_check')));
Route::post('admin/ajaxBorraUser',array('as' => 'ajaxBorraUser','uses' => 'UsersController@ajaxDelete','before' => array('auth','capacidad:4,msg','ajax_check')));

Route::get('getDescripcion',array('as' => 'getDescripcion','uses' => 'recursosController@getDescripcion','before' => array('auth','ajax_check')));



Route::get('print',array('uses' => 'CalendarController@imprime'));




App::missing(function($exception)
{
    return View::make('404');
});


App::error(function(ModelNotFoundException $e)
  {
    $msg = 'Error base de datos: Objeto no encontrado.... ';
    $title = 'Error';
	return View::make('msg')->with(compact('msg','title'));
  
  });

Route::get('test',array('as'=>'test',function(){
	
	

	$grupos = GrupoRecurso::all()->filter(function($grupo){
		
		
		$recursos = $grupo->recursos->each(function($recurso){
				return $recurso->supervisores->contains(Auth::user()->id); 	
		});	
		
		if ($recursos->count() > 0) return true;
			
	});

	//$grupos = array_slice($grupos->toArray(), 1 * 1, 1);
	foreach ($grupos as $grupo) {
		echo "nombre grupo = " . $grupo->nombre . "<br />";
	}
		
	$paginator = Paginator::make($grupos->toArray(), $grupos->count(), 1);

	echo "<pre>";
	var_dump($grupos);
	echo "</pre>";
	
	

	
 }));


Route::get('data',array('as'=>'ToValidate',function(){
	
	
	$limit = Input::get('limit','10');
	$offset = Input::get('offset','0');
	$sort = Input::get('sort','asc');	
	$order = Input::get('order','asc');
	$search = Input::get('search','');
	
	if($search == "") {
		$events = Evento::Where('estado','=','pendiente')->get()->toArray();
	} else {
		$events = Evento::Where('estado','=','pendiente')->Where('titulo','like','%'.$search.'%')->get()->toArray();
	}
	
	$count = count($events);

	if($order != "asc") {
		$events = array_reverse($events);
	}
		
	$events = array_slice($events, $offset, $limit);
	
	$jsonString =  "{";
	$jsonString .= '"total": ' . $count . ',';
	$jsonString .= '"rows": ';
	$jsonString .=	json_encode($events);
	$jsonString .= "}"; 
	
	return $jsonString;

}));