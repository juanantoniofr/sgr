<?php
/* marca branch master2 */
class AuthController extends BaseController {
 
  /**
    * Attempt user login
  */
  public function doLogin(){

    if (Cas::authenticate()){
      // login en sso ok 
      $attributes = Cas::attr();
      
      //Comprobando relación US ES
      $usesrelacion = $attributes['usesrelacion'];
      //$usesrelacion = array('PDIEXTERNO', 'ALUMNOEEPP', 'ALUMNOSECUNDARIA');
      $relacionvalida = false;
      $relacionespermitidas = array('PAS', 'PDI', 'ALUMNO');
      if (is_array($usesrelacion)){
        foreach ($usesrelacion as $relacion) {
          if ( in_array(strtoupper($relacion),$relacionespermitidas) === true) {
            $relacionvalida = true;
          }
        }
        if (!$relacionvalida) $msg = '<b>Acceso no permitido:</b> el servidor de autenticación devolvió que su relación actual con la Universidad de Sevilla es de: '. implode(', ',$usesrelacion) ;
      }
      elseif (is_string($usesrelacion)){
          if ( in_array(strtoupper($usesrelacion),$relacionespermitidas) === true) $relacionvalida = true;
          else $msg = '<b>Acceso no permitido:</b> el servidor de autenticación devolvió que su relación actual con la Universidad de Sevilla es de: '. $usesrelacion ;
      }
      
      if (!$relacionvalida) {
        Auth::logout();
        //$msg = $msg . '<br /><div class="text-center alert" role="alert">Relaciones permitidas: '.implode(', ',$relacionespermitidas) .'</div>';
        return View::make('loginError')->with(compact('msg'));
      }
    


      $statusUvus = stripos($attributes['schacuserstatus'],'uvus:OK');


      if ($statusUvus == false){
        $msg = 'Has iniciado sesión correctamente pero, <b>su UVUS no es válido</b><br />';
        Auth::logout();
        return View::make('loginError')->with(compact('msg'));
      }



      $uid = $attributes['uid'];
            
      $user= User::where('username','=',$uid)->first();
            
      
      if (!empty($user)){
        // Registrado pero -> No activo
        if (!$user->estado) {
          $msg = '<b>Usuario sin activar</b><br />
            Si en 24/48 horas persiste esta situación, puede ponerse en contacto con la Unidad TIC de la F. de Comunicación para solucionarlo.';
          Auth::logout();
          return View::make('loginError')->with(compact('msg'));
        }

        //Registrado pero -> Caducada
        if (strtotime($user->caducidad) < strtotime(date('Y-m-d'))){
          AuthController::cuentaCaducada($attributes);
          Auth::logout();
          return View::make('loginError')->with('msg','Su acceso a <i>reservas fcom</i></b> ha caducado.<br />En 24/48 horas activaremos su cuenta.');
        }

        //-> login en laravel
        Auth::loginUsingId($user->id); 
        return Redirect::to(ACL::getHome());
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
        $user->caducidad = date('Y-m-d',strtotime(Config::get('options.inicio_cursoAcademico') .' +1 years')); //Caducidad 1 año
        $user->estado = false;//No activa
        $user->save();

        $notificacion = new Notificacion();
        $msg = '('.date('d-m-Y H:i').') Registro de ' . $apellidos .', '.$nombre.'('.$uid.') <br />
                                        <b>Relación US:</b> '.$usesrelacion.', <b>Unidad organizativa: </b> '.$ou.', <b>Unidad:</b> '.$usesunidadadministrativa.' , <b>SubUnidad:</b> ' . $usessubunidad .', <b>Centro:</b> '.$centro. ', <b>Titulación:</b> '.$titulacion;
                
                
                 
        $notificacion->msg = $msg;
        $notificacion->target = '1';//identificador generico para todos los administradores....
        $notificacion->source = $uid;
        $notificacion->estado = 'abierta';
        $notificacion->save();

        //mail administradores
        $sgrMail = new sgrMail();
        $sgrMail->notificaRegistroUser($user);

        //-> login en laravel
        //Auth::loginUsingId($user->id); 

        $msg = 'Usuario registrado en <i>reservas fcom</i>.<br />En 24/48 horas activaremos su cuenta<br />';
        return View::make('loginError')->with(compact('msg'));
      }

            
    }
    else{
      $msg = '<b>error autenticación sso</b><br />';
      Auth::logout();
      return View::make('loginError')->with(compact('msg'));
    }
   
  }
 
  public static function cuentaCaducada($attributes){
    
    $uid = $attributes['uid'];
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

    $user = User::where('username','=',$uid)->first();
    
    
    /*
    $user->username = $uid;
    $user->nombre = $nombre;
    $user->apellidos =  $apellidos;
    $user->email =  $email;
    $user->dni =  $dni;
    $user->caducidad = date('Y-m-d',strtotime(Config::get('options.inicio_cursoAcademico') .' +1 years')); //Caducidad 1 año
    $user->estado = false;//No activa
    $user->save();*/
    if (Notificacion::where('source','=',$uid)->where('estado','=','abierta')->count() == 0){
      $notificacion = new Notificacion();
      $msg = '<span class="text-warning">('.date('d-m-Y H:i').') Cuenta caducada de ' . $apellidos .', '.$nombre.'('.$uid.') <br /> <b>Relación US:</b> '.$usesrelacion.', <b>Unidad organizativa: </b> '.$ou.', <b>Unidad:</b> '.$usesunidadadministrativa.' , <b>SubUnidad:</b> ' . $usessubunidad .', <b>Centro:</b> '.$centro. ', <b>Titulación:</b> '.$titulacion .'</span>';
                
      $notificacion->msg = $msg;
      $notificacion->target = '1';//identificador generico para todos los administradores....
      $notificacion->source = $uid;
      $notificacion->estado = 'abierta';
      $notificacion->save();

      //mail administradores
      $sgrMail = new sgrMail();
      $sgrMail->notificaCaducada($user);
    }
    //-> login en laravel
    Auth::loginUsingId($user->id); 
    
    //$msg = 'Usuario registrado en <i>reservas fcom</i>.<br />En 24/48 horas activaremos su cuenta<br />';
    return true;//View::make('loginError')->with(compact('msg'));
  }

  public function doLogout(){
        
    Auth::logout();
    if (!Cas::isAuthenticated()) return View::make('wellcome');
    else{
      Cas::logout();
    }
  }

}

