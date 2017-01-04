<?php
/* marca branch master2 */
class AuthController extends BaseController {
 
  
  /**
    * // Determina si $user existe en BD
    * @param $user Object User
    * @return boolean 
  */  
  private function existsUser($user){
    if($user != null) return true;
    return false;
  }
  
  /**
    * // Salva usuario a la BD
    * @param $attributes array campos sso
    * @return true;
  */
  private function registraAcceso($attributes){

    $user = new User;
    $user->username   =   $attributes['uid'];
    $user->nombre     =   isset($attributes['givenname']) ? $attributes['givenname'] : "";
    $user->apellidos  =   isset($attributes['sn']) ? $attributes['sn'] : "";
    $user->email      =   isset($attributes['irismailmainaddress']) ? $attributes['irismailmainaddress'] : "";
    $user->dni        =   isset($attributes['irispersonaluniqueid']) ? $attributes['irispersonaluniqueid'] : "";
    $user->caducidad  =   date('Y-m-d'); 
    $user->estado     =   false;
    $user->colectivo  =   Config::get('options.colectivoPorDefecto');
    $user->capacidad  =   Config::get('options.capacidadPorDefecto');
    $user->save();

    return true;
  }

  /**
    * //login
    * @return redirect::to
  */
  public function doLogin(){
    
    if (!Cas::authenticate()) return Redirect::to('report.html')->with('msg',Config::get('msg.errorSSO'))->with('alertLevel','danger');

    $attributes = Cas::attr();
    
    $statusUvus = stripos($attributes['schacuserstatus'],'uvus:OK');
    //Uvus no valido :)
    if ($statusUvus == false) return Redirect::to(route('report.html'))->with('msg',Config::get('msg.uvusNoValido'))->with('alertLevel','danger');    
    
    $user = User::where('username','=',$attributes['uid'])->first();    
    //No existe user en BD => Primer Acceso
    if ($this->existsUser($user) == false) {   
        // => registrar acceso
        $this->registraAcceso($attributes);
        // => Salva notificación para admins SGR
        $motivo = 'Nuevo acceso';
        $this->salvaNotificacion($attributes,$motivo);
        // => send mail para admins SGR
        $sgrMail = new sgrMail();
        $sgrMail->notificaRegistroUser($user);//notifica a los administradores designados que hay un nuevo usuario a registrar.
        // => Redirect report for user
        return Redirect::to(route('report.html'))->with('msg',Config::get('msg.uvusRegistrado'))->with('alertLevel','danger');
    }  

    //User existe en BD
    
    // Cuenta desactivada :)
    if ($user->estado == false) 
      return Redirect::to(route('report.html'))->with('msg',Config::get('msg.uvusNoActivo'))->with('alertLevel','danger');

    //Cuenta Caducada :)
    if (strtotime($user->caducidad) < strtotime(date('Y-m-d'))){ 
      // => Salva notificación para admins SGR
      $motivo = 'Cuenta caducada';
      $this->salvaNotificacion($attributes,$motivo);
      return Redirect::to(route('report.html'))->with('msg',Config::get('msg.cuentaCaducada'))->with('alertLevel','danger');    
    }

    //Cuenta OK
    Auth::loginUsingId($user->id);
    $sgrUser = new sgrUser($user);
    return Redirect::to($sgrUser->home());
  }//<!-- doLogin -->
  
  /**
    * //Salva a BD notificación para admins SGR
    * @param $attributes array campos sso
    * @param $motivo string (Cuenta caducada | Nuevo Acceso)
    * @return true
  */
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

  /**
    * // logout sso | logout SGR 
  */
  public function doLogout(){
       
    if (Cas::isAuthenticated()) Cas::logout();
    else{ 
      Auth::logout();
      return View::make('wellcome');
      }
  }//<!-- doLogout -->

  /**
    * // Genera página html para reporte a usuario
    * @param $msg string 
    * @param $alertLevel string (success | info | warning | primary)
    * @return View::make
  */
  public function report(){

      $pagetitle = Config::get('msg.pagetitleLogin');
      $paneltitle = Config::get('msg.pagetitleLogin');
      $msg = Session::get('msg','Error desconocido....');
      $alertLevel = Session::get('alertLevel','info');//'danger';
      return View::make('message')->with(compact('msg','pagetitle','paneltitle','alertLevel'));
  }

}