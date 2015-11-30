<?php

class AuthController extends BaseController {
 
    /**
     * Attempt user login
     */
    public function doLogin(){

        if (Cas::authenticate()){
            // login en sso ok 
            //$attributes = phpCAS::getAttributes();
            $attributes = Cas::attr();
            $statusUvus = stripos($attributes['schacuserstatus'],'uvus:OK');

            if ($statusUvus == false){
                $msg = 'Has iniciado sesión correctamente pero, <b>su UVUS no es válido</b><br />';
                return View::make('loginError')->with(compact('msg'));
            }


            $uid = $attributes['uid'];
            $user= User::where('username','=',$uid)->first();
            
            //  Si ya registrado, 2º acceso o más
            if (!empty($user)){
                // Registrado pero -> No activo
                if (!$user->estado) {
                    $msg = '<b>Usuario sin activar</b><br />
                    Si en 24/48 horas persiste esta situación, puede ponerse en contacto con la Unidad TIC de la F. de Comunicación para solucionarlo.';
                    return View::make('loginError')->with(compact('msg'));
                }

                //Registrado pero -> Caducada
                if (strtotime($user->caducidad) < strtotime(date('Y-m-d'))) return View::make('loginError')->with('msg','Su acceso a <i>reservas fcom</i></b> ha caducado.<br />Puede ponerse en contacto con la Unidad TIC de la F. de Comunicación para solucionarlo.');

                //-> login en laravel
                Auth::loginUsingId($user->id); 
                //-> ir a página de inicio de su perfil
                return Redirect::to(Auth::user()->home());
            }
            
            else {
                //No registrado

                //if corto: $resultado = ($a>$b) ? "A es Mayor que B":"B es Mayor que A";
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
                $msg = '(' . date('d-m-Y H:i') .') Registro de usuario  ' . $apellidos .', '.$nombre.'('.$uid.') <br />
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
                Auth::loginUsingId($user->id); 

                $msg = 'Usuario registrado en <i>reservas fcom</i>.<br />
                En 24/48 horas activaremos su cuenta<br />';
                return View::make('loginError')->with(compact('msg'));
            }

            
        }
        else{
            $msg = '<b>error autenticación sso</b><br />';
            return View::make('loginError')->with(compact('msg'));
            }
    
        }
 
    public function doLogout(){
        
        Auth::logout();
        if (!Cas::isAuthenticated()) return View::make('wellcome');
        else{
            Cas::logout();
        }
    }

}

