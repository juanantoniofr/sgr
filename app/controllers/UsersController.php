<?php

class UsersController extends BaseController {
  
  private $offset = '10';
  private $order = 'asc';
  private $sortby = 'username';

  public function listar(){ //:)
    //Input  
    $veractivas = Input::get('veractivas',1);
    $colectivo  = Input::get('colectivo','');
    $perfil     = Input::get('perfil','');
    $perfiles   = Config::get('string.capacidades');
      
    $sortby     = Input::get('sortby',$this->sortby);
    $order      = Input::get('order',$this->order);
    $offset     = Input::get('offset',$this->offset); 
    $search     = Input::get('search','');
      
    
    $colectivos = Config::get('options.colectivos');
    $page       = Input::get('page','1'); 
    //Output
    $usuarios = User::where('username','like',"%$search%")->where('estado','=',$veractivas)->where('colectivo','like',"%".$colectivo)->where('capacidad','like',"%".$perfil)->orderby($sortby,$order)->paginate($offset);
    $usuarios->setBaseUrl(route('users'));
    //$links = (string) View::make('admin.usuarios.links',compact('usuarios'));
    foreach ($usuarios as $user) {
      $sgrUsuarios[] = new sgrUser($user);
    }
    $sgrUser = new sgrUser(Auth::user());
      
    return View::make('admin.usuarios.listado')->nest('tableUsuarios','admin.usuarios.usuarios',compact('sgrUsuarios','sortby','order','veractivas'))->nest('links','admin.usuarios.links',compact('usuarios','page'))->nest( 'dropdown','admin.dropdown',compact('sgrUser') )->nest('menuUsuarios','admin.usuarios.menu',compact('veractivas','colectivo','colectivos','perfil','perfiles'))->nest('modalAddUser','admin.modalusuarios.add')->nest('modalEditUser','admin.modalusuarios.edit')->nest('modalDeleteUser','admin.modalusuarios.delete');
  }

  public function ajaxGetUsuarios(){ // :)
    //Input
    $veractivas = Input::get('veractivas',1);
    $colectivo  = Input::get('colectivo','');
    $perfil     = Input::get('perfil','');
    //$perfiles   = Config::get('string.capacidades');
      
    $sortby     = Input::get('sortby',$this->sortby);
    $order      = Input::get('order',$this->order);
    $offset     = Input::get('offset',$this->offset);
      
    $search     = Input::get('search','');
    $pagina     = Input::get('pagina','1');
    //Output
    $usuarios = User::where('username','like',"%$search%")->where('estado','=',$veractivas)->where('colectivo','like',"%".$colectivo)->where('capacidad','like',"%".$perfil)->orderby($sortby,$order)->take($offset)->skip($offset * ($pagina-1))->get();
    
    foreach ($usuarios as $user) {
     $sgrUsuarios[] = new sgrUser($user);
    }
    return View::make('admin.usuarios.usuarios',compact('sgrUsuarios','sortby','order','veractivas','pagina'));
  }

  public function ajaxAdd(){ // :)
    
    //Output  
    $respuesta = array( 'error'   => false,
                        'errors'  => array(),
                        'msg'     => '',  
                        );
    //Creamos un nuevo usuario
    $rules = array(
        'nombre'                => 'required',
        'apellidos'             => 'required',
        'colectivo'             => 'required',
        'username'              => 'required|unique:users',
        'caducidad'             => 'required|date|date_format:d-m-Y',
        'capacidad'             => 'required|in:1,2,3,4',
        'email'                 => 'required|email',
        
      );

    $messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio.',
          'date'          => 'El campo <strong>:attribute</strong> debe ser una fecha válida, ',
          'date_format'   => 'El campo <strong>:attribute</strong> debe tener el formato d-m-Y',
          'in'            => 'El campo <strong>:attribute</strong> es erroneo.',
          'email'         => 'El campo <strong>:attribute</strong> debe ser una dirección de email válida',
          'unique'        => 'El UVUS ya existe.'
        );

    $validator = Validator::make(Input::all(), $rules, $messages);
           
    if ($validator->fails())
      {
        $respuesta['error'] = true;
        $respuesta['errors'] = $validator->errors()->toArray();
        return $respuesta;
      }
    else{  

        // salvamos los datos.....
        $user = new User;

        $user->nombre = Input::get('nombre',''); 
        $user->apellidos = Input::get('apellidos','');
        $user->colectivo = Input::get('colectivo');
        $user->username = Input::get('username'); 
        $user->capacidad = Input::get('capacidad');
        // La fecha se debe guardar en formato USA Y-m-d  
        $fecha = DateTime::createFromFormat('j-m-Y',Input::get('caducidad'));
        $user->caducidad = $fecha->format('Y-m-d');
        $user->estado = 1; //Activamos al crear
        $user->email = Input::get('email');
        $user->save();
        $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
        return $respuesta;
    }
  }

  public function ajaxEdit(){ //:)

    //Output  
    $respuesta = array( 'error'   => false,
                        'errors'  => array(),
                        'msg'     => '',  
                        );
        
    $rules = array(
      'id'        => 'required|exists:users',
      'nombre'    => 'required',
      'apellidos' => 'required',
      'estado'    => 'required',
      'colectivo' => 'required',
      'caducidad' => 'required|date|date_format:d-m-Y',
      'capacidad' => 'required|in:1,2,3,4',
      'email'     => 'required|email',
      );

    $messages = array(
      'required'      => 'El campo <strong>:attribute</strong> es obligatorio.',
      'date'          => 'El campo <strong>:attribute</strong> debe ser una fecha válida, ',
      'date_format'   => 'El campo <strong>:attribute</strong> debe tener el formato d-m-Y',
      'after'         => 'El campo <strong>:attribute</strong> debe ser una fecha posterior al día actual',
      'in'            => 'El campo <strong>:attribute</strong> es erroneo',
      'email'         => 'El campo <strong>:attribute</strong> debe ser una dirección de email válida',
      'exists'        => 'Identificador de usuario no valido...',
           );
  
 
    $validator = Validator::make(Input::all(), $rules, $messages);
    
    if ($validator->fails()) {
      $respuesta['error'] = true;
      $respuesta['errors'] = $validator->errors()->toArray();
      return $respuesta;
    }
    else{  
      // salvamos los datos.....
      $user = User::find(Input::get('id'));
      
      // La fecha se debe guardar en formato USA Y-m-d  
      $fecha = DateTime::createFromFormat('j-m-Y',Input::get('caducidad'));
      $user->caducidad = $fecha->format('Y-m-d');
      $user->capacidad = Input::get('capacidad');
      $user->colectivo = Input::get('colectivo');
      $user->estado = Input::get('estado','0');
      $user->email = Input::get('email');
      $user->nombre = Input::get('nombre');
      $user->apellidos = Input::get('apellidos');
      $user->observaciones = Input::get('observaciones');

      $user->save();

      $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
      return $respuesta;
      
    }
  }
  
  public function ajaxDelete(){ // :)
    //Input
    $id = Input::get('id','');
    //Output  
    $respuesta = array( 'error'   => false,
                        'errors'  => array(),
                        'msg'     => '',  
                        );

    $rules = array(
        'id'  => 'required|exists:users',//$id requerido y debe existir en la tabla users        
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
      return $respuesta;
    }
  }
  
  //Página de inicio para administradores sgr (Notificaciones de registro de usuarios)
  public function home(){ // :)
    
    $sgrUser = new sgrUser(Auth::user());
    $notificaciones = Notificacion::where('estado','=','abierta')->orderby('id','desc')->get();
    return View::make('admin.index')->with(compact('notificaciones'))->nest('dropdown','admin.dropdown',compact('sgrUser'))->nest('modalvalidaRegistroUser','admin.modalusuarios.validaRegistroUser');
  }
  
  
  public function ajaxUpdateUser(){ // :)
    
    //Output  
    $respuesta = array( 'error'   => false,
                        'errors'  => array(),
                        'msg'     => '',  
                        );

    $rules = array(
        'idnotificacion'  => 'required|exists:notificaciones,id',//$id requerido y debe existir en la tabla notificaciones 
        'username'        => 'required|exists:users',//requerido y debe existir en la tabla users
        'caducidad'       => 'required|date|date_format:d-m-Y',      
    );

    $messages = array(
          'required'          => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idnotificacion.exists'     => Config::get('msg.idnotfound'),
          'username.exists'   => Config::get('msg.usernamenotfound'),
          'date'              => 'El campo <strong>:attribute</strong> debe ser una fecha válida',
          'date_format'       => 'El campo <strong>:attribute</strong> debe tener el formato d-m-Y',
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
      $activar = Input::get('activar',false);

      $user = User::where('username','=',$username)->first();
      $user->estado = $activar; //Activación cuenta
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
      $sgrMail->notificaActualizacionCuenta($user->id);            
      
      $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
      return $respuesta;

    }
  }
  

  /**
    * @param $id int
    * @return $user Object User
  */
  public function user(){ // ?????
    $id = Input::get('id','');
    $user = User::findOrFail($id);

    return $user;
  }
  
  

 

  public function newUser(){
    return View::make('admin.userNew')->with("user",Auth::user())->nest('dropdown',Auth::user()->dropdownMenu());
  }
 
  

  public function ajaxDelete_2(){ //???
    $result = array('success' => false);
    
    $username = Input::get('username','');
    $colectivo = Input::get('colectivo','');
    $caducidad = Input::get('caducidad','');
    $rol = Input::get('rol','1');
    

    $user = User::where('username','=',$username)->first();

    if (!empty($user)) {
      Notificacion::where('source','=',$username)->delete();
      $user->delete();
      $result['success'] = true;
    }
    return $result;
  }

  

  private function cierraNotificacion($id){
      Notificacion::where('id','=',$id)->update(array('estado' => 'cerrada'));
      return true;
  }

  
 
  
  /**
    * Store a newly created resource in storage.
    *
    * @return Response
  */
  public function store()
   {
    //
  }
 
  /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
  */
  public function show($id)
  {
    //
  }
 
  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
  */
  public function destroy($id)
  {
    //
  }
 
}