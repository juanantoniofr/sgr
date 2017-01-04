<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
/* marca branch master2 */
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

//msg a usuario :)
  Route::get('msg',array('as' => 'msg',function(){ 
  	$pagetitle   = Config::get('msg.pagetitlefilterCapacidad');
    $paneltitle  = Config::get('msg.paneltitlefilterCapacidad');
    $msg         = Config::get('msg.filterCapacidadmsg');
    $alertLevel  = 'danger';
  	return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
  }));
// fin
//*****************  

//wellcome, ayuda,contactar :)
  Route::get('wellcome',array('as'=>'wellcome','uses' => 'HomeController@showWellcome'));
  Route::get('ayuda.html',array('as'=>'ayuda','uses'=>'HomeController@ayuda'));
  Route::get('contactar.html',array('as'=>'contactar','uses'=>'HomeController@contacto','before'=>'auth'));
  Route::post('contactar.html',array('as'=>'enviaformulariocontacto','uses'=>'HomeController@sendmailcontact','before'=>'auth'));
//fin
//****************************


//login/logout :)
  Route::get('/',array('as' => 'loginsso','uses' => 'AuthController@doLogin'));
  Route::get('logout',array('as' => 'logout','uses' => 'AuthController@doLogout'));
//fin 
//***************

//routes gestión registro de usuarios :)
  Route::get('admin/home.html',array('as' => 'adminHome.html','uses' => 'UsersController@home','before' => array('auth','capacidad:4,msg'))); // :)
  Route::post('admin/ajaxUpdateUser',array('uses' => 'NotificacionesController@ajaxUpdateEstado','before' => array('auth','ajax_check'))); // :)
  Route::post('admin/ajaxBorraUser',array('uses' => 'NotificacionesController@ajaxDelete','before' => array('auth','capacidad:4,msg','ajax_check'))); // :)
//fin 
//*************************************


//routes gestión de usuarios :)
  Route::get('admin/users.html',array('as' => 'users','uses' => 'UsersController@listar','before' => array('auth','capacidad:4,msg'))); // Listar :)
  Route::get('admin/ajaxGetUsuarios',array('uses' => 'UsersController@ajaxGetUsuarios','before' => array('auth','ajax_check','capacidad:4,msg'))); // Listar :)
  Route::post('admin/ajaxAddUsuario',array('uses' => 'UsersController@ajaxAdd','before' => array('auth','ajax_check','capacidad:4,msg'))); // Add user :)
  Route::get('admin/ajaxEditUsuario',array('uses' => 'UsersController@ajaxEdit','before' => array('auth','auth_ajax','capacidad:4,msg'))); // Edit user :)
  Route::post('admin/ajaxEliminausuario',array('uses' => 'UsersController@ajaxDelete','before' => array('auth','ajax_check','capacidad:4,msg'))); // Del user :)
//fin  
//*****************************


//routes gestión de grupos
  Route::get('admin/ajaxGetViewRecursos',array('uses' => 'GruposController@ajaxGetViewRecursos','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::post('admin/addgrupo',array('uses' => 'GruposController@add','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::post('admin/delgrupo',array('uses' => 'GruposController@del','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::post('admin/editgrupo',array('uses' => 'GruposController@edit','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::get('admin/ajaxGetRecursoContenedoresSinGrupo',array('uses' => 'GruposController@ajaxGetRecursoContenedoresSinGrupo','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::post('admin/ajaxAddrecursoSingrupo',array('uses' => 'GruposController@ajaxAddrecursoSingrupo','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
//fin
//**************************

//routes relaciones user-grupo || user-recurso (gestor//administrador//validador)
  Route::post('admin/ajaxAddRelacion',array('uses' => 'RelacionesController@ajaxAddRelacion','before' => array('auth','auth_ajax','capacidad:4-6,msg'))); // :)
  Route::get('admin/ajaxGetGestores',array('uses' => 'RelacionesController@ajaxGetGestores','before' => array('auth','auth_ajax','capacidad:4-6,msg'))); // :)
  Route::get('admin/ajaxGetAdministradores',array('uses' => 'RelacionesController@ajaxGetAdministradores','before' => array('auth','auth_ajax','capacidad:4-6,msg'))); // :)
  Route::get('admin/ajaxGetValidadores',array('uses' => 'RelacionesController@ajaxGetValidadores','before' => array('auth','auth_ajax','capacidad:4-6,msg'))); // :)
  Route::post('admin/ajaxRemoveRelacion',array('uses' => 'RelacionesController@ajaxRemoveRelacion','before' => array('auth','auth_ajax','capacidad:4-6,msg'))); // :)
//fin
//*************************

// routes gestión de recursos :/
  Route::get('admin/recursos.html',array('as' => 'recursos.html','uses' => 'recursosController@listar','before' => array('auth','capacidad:4-6,msg'))); // :)
  Route::post('admin/ajaxAddItemExistente',array('uses'  => 'recursosController@ajaxAddItemExistente','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)
  Route::post('admin/AjaxAddNuevoRecurso',array('uses' => 'recursosController@AjaxAddNuevoRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)

  Route::get('admin/ajaxGetDatosRecurso',array('uses'=>'recursosController@ajaxGetDatosRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)  
  Route::post('admin/ajaxEditRecurso',array('uses' => 'recursosController@ajaxEditRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg')));// :)
  Route::post('admin/ajaxDelRecurso',array('uses' => 'recursosController@ajaxDelRecurso','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)
  Route::post('admin/AjaxDisabled',array('uses'=>'recursosController@AjaxDisabled','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)
  Route::post('admin/AjaxEnabled',array('uses'=>'recursosController@AjaxEnabled','before' => array('auth','ajax_check','capacidad:4-6,msg'))); // :)
//fin gestión de recursos
//******************************* 

//routes POD
  Route::get('admin/pod.html',array('as' => 'pod.html','uses' => 'PodController@index','before' => array('auth','capacidad:4,msg')));
  Route::post('admin/pod.html',array('as' => 'uploadPOD','uses' => 'PodController@savePOD','before' => array('auth','capacidad:4,msg')) );
//fin routes POD

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
//fin routes logs & config


//Calendarios
  Route::get('calendarios.html',array('https','as' => 'calendarios.html','uses' => 'CalendarController@index','before' => array('auth'))); // :/
  Route::get('AjaxGetRecursos',array('uses' => 'GruposController@AjaxGetRecursos','before' => array('auth','ajax_check'))); // :)
  Route::get('getitems',array('uses'=>'recursosController@getitems','before' => array('auth','ajax_check'))); // :/

  Route::get('ajaxCalendar',array('uses' => 'CalendarController@getCalendar','before' => array('auth','ajax_check')));
  Route::get('validador/ajaxDataEvent',array('uses' => 'CalendarController@ajaxDataEvent','before' =>array('auth','ajax_check') ));
//******







//EE de equipo (capacidad = 6) y administradores de la aplicación (capacidad = 4)





  Route::get('getDescripcion',array('as' => 'getDescripcion','uses' => 'recursosController@getDescripcion','before' => array('auth','ajax_check')));
  Route::get('admin/htmlOptionrecursos',array('uses' => 'recursosController@htmlOptionrecursos','before' => array('auth','auth_ajax','capacidad:4-6,msg')));
  
  //Route::post('admin/removePersonas',array('uses' => 'RelacionesController@removePersonas','before' => array('auth','auth_ajax','capacidad:4-6,msg')));
 
  Route::get('admin/htmlOptionGrupos',array('uses' => 'GruposController@htmlOptionGrupos','before' => array('auth','auth_ajax','capacidad:4-6,msg')));




//ValidacionController routes **********************
//(roles 4 (admin) y 5 (validador))
  Route::get('validador/home.html',array('as' => 'validadorHome.html','uses' => 'ValidacionController@index','before' => array('auth','capacidad:4-5,msg')));
  Route::get('validador/valida.html',array('as' => 'valida.html','uses' => 'ValidacionController@valida','before' => array('auth','capacidad:4-5,msg')));
//******

//PdfController routes *****************************
Route::get('justificante', array('as' => 'justificante', 'uses' => 'PdfController@build'));



//EventoController
  Route::post('saveajaxevent',array('uses' => 'EventoController@save','before' => array('auth','ajax_check')));		
  Route::post('editajaxevent',array('uses' => 'EventoController@edit','before' => array('auth','ajax_check')));
  Route::get('geteventbyId',array('uses' => 'EventoController@getbyId','before' => array('auth','ajax_check')));
  Route::get('tecnico/geteventbyId',array('uses' => 'EventoController@getbyId','before' => array('auth','ajax_check')));
  Route::post('delajaxevent',array('uses' => 'EventoController@del','before' => array('auth','ajax_check')));
  Route::post('finalizaevento',array('uses' => 'EventoController@finalizar','before' => array('auth','ajax_check')));
  Route::post('anulaevento',array('uses' => 'EventoController@anular','before' => array('auth','ajax_check')));
//***


//Atención de eventos
  Route::get('tecnico/getUserEvents',array(	'uses' => 'EventoController@getUserEvents','before' => array('auth','capacidad:3-4,msg')));
  Route::post('tecnico/saveAtencion',array('uses' => 'EventoController@atender','before' => array('auth','capacidad:3-4,msg')));
//Fin Atención de eventos

  Route::get('print',array('uses' => 'CalendarController@imprime'));

  Route::get('report',array('as' => 'report.html','uses' => 'AuthController@report'));



App::missing(function($exception){
    $pagetitle   = Config::get('msg.404pagetitleLogin');
    $paneltitle  = Config::get('msg.404paneltitle');
    $msg         = Config::get('msg.404msg');
    $alertLevel  = 'warning'; 
    return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
});

App::error(function(ModelNotFoundException $e){
    $pagetitle   	= Config::get('msg.objectNoFoundpagetitle');
    $paneltitle  	= Config::get('msg.objectNoFoundpagetitlepaneltitle');
    $msg 					= Config::get('msg.objectNoFoundmsg');
    $alertLevel 	= 'danger';
  	return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
});

Route::get('test',array('as'=>'test',function(){
  
  $grupos = GrupoRecurso::all();
    foreach ($grupos as $grupo){
      $sgrGrupos[] = new sgrGrupo($grupo);
    }
  foreach ($sgrGrupos as $sgrGrupo) {
    echo $sgrGrupo->nombre() . ', es visible?';
    foreach ($sgrGrupo->recursos() as $sgrRecurso) {
      echo $sgrRecurso->nombre();
      if ($sgrRecurso->esVisible('4')) echo  ' --> si<br />';
    else echo ' --> no<br />';   
    }
    
    
   }
}));

