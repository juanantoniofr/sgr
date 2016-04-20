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

      if ($statusUvus == false){
        $pagetitle   = Config::get('msg.pagetitleLogin');
        $paneltitle  = Config::get('msg.paneltitle');
        $msg         = Config::get('msg.uvusNoValido');
        $alertLevel  = 'danger';
        return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
      }

      $uid = $attributes['uid'];
      $user= User::where('username','=',$uid)->first();
            
      //  Si ya registrado, 2º acceso o más
      if (!empty($user)){
        // Registrado pero -> No activo
        if (!$user->estado) {
          $pagetitle   = Config::get('msg.pagetitleLogin');
          $paneltitle  = Config::get('msg.paneltitle');
          $msg         = Config::get('msg.uvusNoActivo');
          $alertLevel  = 'danger';
          return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
        }

        //Registrado pero -> Caducada
        if (strtotime($user->caducidad) < strtotime(date('Y-m-d'))){
          $pagetitle   = Config::get('msg.pagetitleLogin');
          $paneltitle  = Config::get('msg.paneltitle');
          $msg         = Config::get('msg.cuentaCaducada');
          $alertLevel  = 'danger'; 
          return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
        }

        //-> login en laravel
        Auth::loginUsingId($user->id); 
        //-> ir a página de inicio de su perfil
        return Redirect::to(Auth::user()->home());
      }
      else {
        //No registrado 
        //Todos    
        $nombre    = isset($attributes['givenname']) ? $attributes['givenname'] : "";
        $apellidos = isset($attributes['sn']) ? $attributes['sn'] : "";
        $email = isset($attributes['irismailmainaddress']) ? $attributes['irismailmainaddress'] : "";    
        $dni = isset($attributes['irispersonaluniqueid']) ? $attributes['irispersonaluniqueid'] : "";    
        $usesrelacion = isset($attributes['usesrelacion']) ? json_encode($attributes['usesrelacion']) : "";    
        //PAS
        $usessubunidad = isset($attributes['usessubunidad']) ? $attributes['usessubunidad'] : "";
        $usesunidadadministrativa = isset($attributes['usesunidadadministrativa']) ? $attributes['usesunidadadministrativa'] : "";
        $ou = isset($attributes['ou']) ? $attributes['ou'] : "";
        //?
        $centro = isset($attributes['usescentro']) ? json_encode($attributes['usescentro']) : "";
        $titulacion = isset($attributes['usestitulacion']) ? json_encode($attributes['usestitulacion']) : "";

        $user = new User;
        $user->username = $uid;
        $user->nombre = $nombre;
        $user->apellidos =  $apellidos;
        $user->email =  $email;
        $user->dni =  $dni;
        $user->caducidad = date('Y-m-d',strtotime('+1 years')); //Caducidad 5 años
        $user->estado = false;//No activa
        $user->save();

        $notificacion = new Notificacion();
        $msg = '(' . date('d-m-Y H:i') .') Registro de usuario  ' . $apellidos .', '.$nombre.'('.$uid.') <br /><b>Relación US:</b> '.$usesrelacion.', <b>Unidad organizativa: </b> '.$ou.', <b>Unidad:</b> '.$usesunidadadministrativa.' , <b>SubUnidad:</b> ' . $usessubunidad .', <b>Centro:</b> '.$centro. ', <b>Titulación:</b> '.$titulacion;
                
        $notificacion->msg = $msg;
        $notificacion->target = '1';//identificador generico para todos los administradores....
        $notificacion->source = $uid;
        $notificacion->estado = 'abierta';
        $notificacion->save();

        //mail administradores
        $sgrMail = new sgrMail();
        $sgrMail->notificaRegistroUser($user);

        //-> login en laravel
        Auth::loginUsingId($user->id); 

        $pagetitle   = Config::get('msg.pagetitleLogin');
        $paneltitle  = Config::get('msg.paneltitle');
        $msg         = Config::get('msg.uvusRegistrado');
        $alertLevel  = 'success'; 
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
 
  public function doLogout(){
    Auth::logout();
    if (!Cas::isAuthenticated()) return View::make('wellcome');
    else Cas::logout();
  }//<!-- doLogout -->

}