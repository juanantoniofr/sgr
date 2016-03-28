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

Route::get('msg',array('as' => 'msg',function(){
	$pagetitle   = Config::get('msg.pagetitlefilterCapacidad');
    $paneltitle  = Config::get('msg.paneltitlefilterCapacidad');
    $msg         = Config::get('msg.filterCapacidadmsg');
    $alertLevel  = 'danger';
	return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
}));

Route::get('wellcome',array('as'=>'wellcome','uses' => 'HomeController@showWellcome'));

Route::get('ayuda.html',array('as'=>'ayuda','uses'=>'HomeController@ayuda'));

Route::get('contactar.html',array('as'=>'contactar','uses'=>'HomeController@contacto','before'=>'auth'));
Route::post('contactar.html',array('as'=>'enviaformulariocontacto','uses'=>'HomeController@sendmailcontact','before'=>'auth'));


Route::get('/',array('as' => 'loginsso','uses' => 'AuthController@doLogin'));
Route::get('logout',array('as' => 'logout','uses' => 'AuthController@doLogout'));




//Admin (rol = 4)
Route::get('admin/home.html',array('as' => 'adminHome.html','uses' => 'UsersController@home','before' => array('auth','capacidad:4,msg')));



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

// recursosController routes ***********************
Route::post('admin/disabled',array('uses'=>'recursosController@disabled','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::post('admin/enabled',array('uses'=>'recursosController@enabled','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::post('admin/delrecurso',array('uses' => 'recursosController@del','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::post('admin/addrecurso',array('uses' => 'recursosController@add','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::get('admin/getrecurso',array('uses'=>'recursosController@getrecurso','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::get('admin/recursosSinGrupo',array('uses'=>'recursosController@recursosSinGrupo','before' => array('auth','ajax_check','capacidad:4-6,msg')));
Route::post('admin/updaterecurso',array('uses' => 'recursosController@update','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Update propiedades recurso
Route::get('getDescripcion',array('as' => 'getDescripcion','uses' => 'recursosController@getDescripcion','before' => array('auth','ajax_check')));
Route::get('admin/htmlCheckboxPersonas',array('uses' => 'recursosController@htmlCheckboxPersonas','before' => array('auth','auth_ajax','capacidad:4,msg')));	
Route::post('admin/addPersona',array('uses' => 'recursosController@addPersona','before' => array('auth','auth_ajax','capacidad:4,msg')));
Route::post('admin/removePersonas',array('uses' => 'recursosController@removePersonas','before' => array('auth','auth_ajax','capacidad:4,msg')));
Route::get('getRecursos',array('as' => 'getRecursos','uses' => 'recursosController@getRecursos','before' => array('auth','ajax_check')));


//GruposController routes ************************
Route::get('admin/recursos.html',array('as' => 'getListadoGrupos','uses' => 'GruposController@listar','before' => array('auth','capacidad:4-6,msg')));
Route::get('admin/getTableGrupos',array('uses' => 'GruposController@getTable','before' => array('auth','ajax_check','capacidad:4-6,msg')));//devuelve tabla todos los grupos
Route::post('admin/addgrupo',array('uses' => 'GruposController@add','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Nuevo grupo
Route::post('admin/editgrupo',array('uses' => 'GruposController@edit','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Edita propiedades grupo (nombre y descripción)
Route::post('admin/delgrupo',array('uses' => 'GruposController@del','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Elimian grupo
Route::post('admin/addrecursotogrupo',array('uses' => 'GruposController@addrecursos','before' => array('auth','ajax_check','capacidad:4-6,msg')));//Añade recurso al grupo

//ValidacionController routes **********************
//(roles 4 (admin) y 5 (validador))
Route::get('validador/home.html',array('as' => 'validadorHome.html','uses' => 'ValidacionController@index','before' => array('auth','capacidad:4-5,msg')));
Route::get('validador/valida.html',array('as' => 'valida.html','uses' => 'ValidacionController@valida','before' => array('auth','capacidad:4-5,msg')));

//PdfController routes *****************************
Route::get('justificante', array('as' => 'justificante', 'uses' => 'PdfController@build'));



//Calendarios
Route::get('calendarios.html',array('as' => 'calendarios.html','uses' => 'CalendarController@showCalendarViewMonth','before' => array('auth')));
Route::get('ajaxCalendar',array('uses' => 'CalendarController@getTablebyajax','before' => array('auth','ajax_check')));


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





Route::get('print',array('uses' => 'CalendarController@imprime'));




App::missing(function($exception)
	{
    	$pagetitle   = Config::get('msg.404pagetitleLogin');
        $paneltitle  = Config::get('msg.404paneltitle');
        $msg         = Config::get('msg.404msg');
        $alertLevel  = 'warning'; 
    	return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
	});


App::error(function(ModelNotFoundException $e)
  {
    $msg = 'Error base de datos: Objeto no encontrado.... ';
    $title = 'Error';
	return View::make('msg')->with(compact('msg','title'));
  
  });

Route::get('test',array('as'=>'test',function(){

	$grupos = GruposController::gruposVisibles('58');
	var_dump($grupos);
	
 }));