<?php

class NotificacionesController extends BaseController {

	/**
		* // Activar / desactivar nuevo acceso
	*/
	public function ajaxUpdateEstado(){ // :)
    //Output  
    $respuesta = array( 'error'           => false,
                        'errors'          => array(),
                        'msg'             => '',
                        'idnotificacion'  => 0,  
                        );

    $rules = array(
        'idnotificacion'  => 'required|exists:notificaciones,id',//$id requerido y debe existir en la tabla notificaciones 
        'username'        => 'required|exists:users',//requerido y debe existir en la tabla users
        'caducidad'       => 'required|date|date_format:d-m-Y',      
    );

    $messages = array(
          'required'                  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idnotificacion.exists'     => Config::get('msg.idnotfound'),
          'username.exists'           => Config::get('msg.usernamenotfound'),
          'date'                      => 'El campo <strong>:attribute</strong> debe ser una fecha válida',
          'date_format'               => 'El campo <strong>:attribute</strong> debe tener el formato d-m-Y',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()){
        $respuesta['error'] = true;
        $respuesta['errors'] = $validator->errors()->toArray();
        return $respuesta;
    }
    else{
      //Input
      $idnotificacion = Input::get('idnotificacion');
      $username = Input::get('username');
      $colectivo = Input::get('colectivo',Config::get('options.colectivoPorDefecto'));
      $capacidad = Input::get('capacidad',Config::get('options.capacidadPorDefecto'));
      $caducidad = Input::get('caducidad');//Por defecto hoy
      $observaciones = Input::get('observaciones','');
      $activar = Input::get('activar',0);

      $user = User::where('username','=',$username)->first();
      $user->estado = $activar; //Activación-desactivación de cuenta
      $user->colectivo = $colectivo;
      $user->capacidad = $capacidad;
      $user->observaciones = $observaciones;
      
      // La fecha se debe guardar en formato USA Y-m-d  
      $fecha = DateTime::createFromFormat('j-m-Y',Input::get('caducidad'));
      $user->caducidad = $fecha->format('Y-m-d');
      
      $user->save();

      $this->cierraNotificacion($idnotificacion);
      //mail to User by Activate
      $sgrMail = new sgrMail();
      if ($activar)   $sgrMail->notificaActivacionCuenta($user->id);            
      else            $sgrMail->notificaDesactivacionCuenta($user->id);            
      
      $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
      $respuesta['idnotificacion'] = $idnotificacion;
      return $respuesta;

    }
  }

  public function ajaxDelete(){ // :)
    //Input
    $id = Input::get('id','');
    $idnotificacion = Input::get('idnotificacion','');
    //Output  
    $respuesta = array( 'error'   => false,
                        'errors'  => array(),
                        'msg'     => '',  
                        );

    $rules = array(	'id'  						=> 'required|exists:users',
    								'idnotificacion'  => 'required|exists:notificaciones,id',
    								); 

    $messages = array(
          'required'  => Config::get('msg.idempty'),
          'exists'    => Config::get('msg.idnotfound'),
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()){
        $respuesta['error'] = true;
        $respuesta['errors'] = $validator->errors()->toArray();
        return $respuesta;
    }
    else{
      $user = User::find($id);
      $sgrUser = new sgrUser($user);
      $sgrUser->detach(); 
      $sgrUser->deleteeventos();
      $sgrUser->delete();
      $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.delusersuccess')));
      $this->cierraNotificacion($idnotificacion);
      return $respuesta;
    }
  }

  /**
  	* //
  	* @param $id int identificador de la notificación
  	*	@return true
  */
 	private function cierraNotificacion($id){ // :)
      Notificacion::where('id','=',$id)->update(array('estado' => 'cerrada'));
      return true;
  } 

}