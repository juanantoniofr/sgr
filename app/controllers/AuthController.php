<?php

class AuthController extends BaseController {
 
  /**
    * Attempt user login
  */
  public function doLogin(){
      
      if (Cas::authenticate()){
        // login en sso ok 
        $attributes = Cas::attr();
        $statusUvus = stripos($attributes['schacuserstatus'],'uvus:OK');

        if ($statusUvus == false){ //Uvus no valido :)
          $pagetitle   = Config::get('msg.pagetitleLogin');
          $paneltitle  = Config::get('msg.paneltitle');
          $msg         = Config::get('msg.uvusNoValido');
          $alertLevel  = 'danger';
          return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
        }

        $uid = $attributes['uid'];
        $user= User::where('username','=',$uid)->first();
        
        if (!empty($user)){//  Si ya registrado, 2º acceso o más
          Auth::loginUsingId($user->id);//patch for phpCAS
          
          if (!$user->estado) {// Registrado pero 'No activo' :)
            $pagetitle   = Config::get('msg.pagetitleLogin');
            $paneltitle  = Config::get('msg.paneltitle');
            $msg         = Config::get('msg.uvusNoActivo');
            $alertLevel  = 'danger';
            Auth::logout(); //patch for phpCAS
            return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
          }

          if (strtotime($user->caducidad) < strtotime(date('Y-m-d'))){ //Registrado pero Cuenta Caducada :)
            //Información a para notificación de todos los usuarios    
            $motivo = 'Cuenta caducada';
            $this->salvaNotificacion($attributes,$motivo);
            
            $pagetitle   = Config::get('msg.pagetitleLogin');
            $paneltitle  = Config::get('msg.paneltitle');
            $msg         = Config::get('msg.cuentaCaducada');

            //$btnredirect = '<a class="btn btn-primary" data-username="'.$uid.'" id="renuevaCaducidad" href="">Solicitar renovación de cuenta</a>';
            $alertLevel  = 'danger'; 
            Auth::logout();
            return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel','btnredirect'));
          }

          //2º acceso o más, cuenta activa y no caducada -> login en laravel
          //-> ir a página de inicio de su perfil
          $sgrUser = new sgrUser($user);
          $home =  $sgrUser->home();
          if (empty($home)) {
            $pagetitle   = Config::get('msg.pagetitleLogin');
            $paneltitle  = Config::get('msg.paneltitle');
            $msg         = Config::get('msg.capacidadnovalida');
            $alertLevel  = 'danger'; 
            return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
          }
          return Redirect::to($sgrUser->home());
        }
        else {  //No registrado :)
          //salvo el nuevo usuario
          $user = new User;
          $user->username   = $uid;
          $user->nombre     = $nombre;
          $user->apellidos  =  $apellidos;
          $user->email      =  $email;
          $user->dni        =  $dni;
          $user->caducidad  = date('Y-m-d',strtotime('+1 years')); //Caducidad 5 años
          $user->estado     = false;//No activa
          $user->colectivo  = Config::get('options.colectivoPorDefecto');
          $user->capacidad  = Config::get('options.capacidadPorDefecto');
          $user->save();
          //Información a para notificación de todos los usuarios    
          $motivo = 'Nuevo acceso';
          $this->salvaNotificacion($attributes,$motivo);

          //mail administradores
          $sgrMail = new sgrMail();
          $sgrMail->notificaRegistroUser($user);//notifica a los administradores designados que hay un nuevo usuario a registrar.
  
          $pagetitle      = Config::get('msg.pagetitleLogin');
          $paneltitle     = Config::get('msg.paneltitle');
          $msg            = Config::get('msg.uvusRegistrado');
          $alertLevel     = 'success';
          
          return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
        }
      }
      else{
        $pagetitle   = Config::get('msg.pagetitleLogin');
        $paneltitle  = Config::get('msg.paneltitle');
        $msg         = Config::get('msg.errorSSO');
        $alertLevel  = 'danger'; 
        return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
      }

   
  }//<!-- doLogin -->
  private function salvaNotificacion($attributes,$motivo){
    $nombre    = isset($attributes['givenname']) ? $attributes['givenname'] : "";
    $apellidos = isset($attributes['sn']) ? $attributes['sn'] : "";
    $email = isset($attributes['irismailmainaddress']) ? $attributes['irismailmainaddress'] : "";
    $dni = isset($attributes['irispersonaluniqueid']) ? $attributes['irispersonaluniqueid'] : "";
    $usesrelacion = isset($attributes['usesrelacion']) ? json_encode($attributes['usesrelacion']) : "";    
    //del PAS
    $usessubunidad = isset($attributes['usessubunidad']) ? $attributes['usessubunidad'] : "";
    $usesunidadadministrativa = isset($attributes['usesunidadadministrativa']) ? $attributes['usesunidadadministrativa'] : "";
    $ou = isset($attributes['ou']) ? $attributes['ou'] : "";
    // Alumno
    $centro = isset($attributes['usescentro']) ? json_encode($attributes['usescentro']) : "";
    $titulacion = isset($attributes['usestitulacion']) ? json_encode($attributes['usestitulacion']) : "";
    $uid = $attributes['uid'];
    $notificacion = new Notificacion();
    if (Notificacion::where('source','=',$uid)->where('estado','=','abierta')->count() > 0) 
      $notificacion = Notificacion::where('source','=',$uid)->where('estado','=','abierta')->first();
    
    $msg = '(' . date('d-m-Y H:i') .') ' . $motivo .': ' . $apellidos .', '.$nombre.'('.$uid.') <br /><b>Relación US:</b> '.$usesrelacion.', <b>Unidad organizativa: </b> '.$ou.', <b>Unidad:</b> '.$usesunidadadministrativa.' , <b>SubUnidad:</b> ' . $usessubunidad .', <b>Centro:</b> '.$centro. ', <b>Titulación:</b> '.$titulacion;
                  
    $notificacion->msg = $msg;
    $notificacion->target = '1';//identificador generico para todos los administradores....
    $notificacion->source = $uid;
    $notificacion->estado = 'abierta';
    $notificacion->save();

    return true;
  }

  public function doLogout(){
    Auth::logout();
    Session::flush();
    Cas::logout();
    if (!Cas::isAuthenticated()) return Redirect::to(route('wellcome'));

  }//<!-- doLogout -->

}