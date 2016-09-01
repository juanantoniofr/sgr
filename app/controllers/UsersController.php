<?php

class UsersController extends BaseController {
  
  public function listar(){ //:)
    //Input  
    $veractivas = Input::get('veractivas',1);
    $colectivo  = Input::get('colectivo','');
    $perfil     = Input::get('perfil','');
    $perfiles   = Config::get('string.capacidades');
      
    $sortby     = Input::get('sortby','updated_at');
    $order      = Input::get('order','desc');
     
    $search     = Input::get('search','');
      
    $offset     = Input::get('offset','10');
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
      
    return View::make('admin.usuarios.listado')->nest('tableUsuarios','admin.usuarios.usuarios',compact('sgrUsuarios','sortby','order','veractivas'))->nest('links','admin.usuarios.links',compact('usuarios','page'))->nest( 'dropdown','admin.dropdown',compact('sgrUser') )->nest('menuUsuarios','admin.usuarios.menu',compact('veractivas','colectivo','colectivos','perfil','perfiles'))->nest('modalAddUser','admin.modalusuarios.add')->nest('modalEditUser','admin.userModalEdit')->nest('modalDeleteUser','admin.modalusuarios.delete');
  }

  public function ajaxGetUsuarios(){ // :)
    //Input
    $veractivas = Input::get('veractivas',1);
    $colectivo  = Input::get('colectivo','');
    $perfil     = Input::get('perfil','');
    //$perfiles   = Config::get('string.capacidades');
      
    $sortby     = Input::get('sortby','updated_at');
    $order      = Input::get('order','desc');
      
    $search     = Input::get('search','');
      
    $offset     = Input::get('offset','10');
    
    $pagina     = Input::get('pagina','1');
    //Output
    $usuarios = User::where('username','like',"%$search%")->where('estado','=',$veractivas)->where('colectivo','like',"%".$colectivo)->where('capacidad','like',"%".$perfil)->orderby($sortby,$order)->take($offset)->skip($offset * ($pagina-1))->get();
    
    foreach ($usuarios as $user) {
     $sgrUsuarios[] = new sgrUser($user);
    }
    return View::make('admin.usuarios.usuarios',compact('sgrUsuarios','sortby','order','veractivas','pagina'));
  }

  public function add() // :)
    {
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
        'caducidad'             => 'required',//|date|date_format:d-m-Y|after:'. date('d-m-Y'),
        'capacidad'             => 'required|in:1,2,3,4,5',
        'email'                 => 'required|email',
        'caducidad'             => 'required'
      );

    $messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio.',
          'date_es'       => 'El campo <strong>:attribute</strong> debe ser una fecha válida',
          'date_format'   => 'El campo <strong>:attribute</strong> debe tener el formato d-m-Y',
          'after'         => 'El campo <strong>:attribute</strong> debe ser una fecha posterior al día actual',
          'in'            => 'El campo <strong>:attribute</strong> es erroneo.',
          'email'         => 'El campo <strong>:attribute</strong> debe ser una dirección de email válida',
          'unique'        => 'El UVUS ya existe.'
        );

    $validator = Validator::make(Input::all(), $rules, $messages);
    //validación fecha formato d-m-Y
    $fecha = Input::get('caducidad'); 
    if (!empty($fecha)){
      $data = Input::all();
      $validator->sometimes('caducidad','date_es',function($data){
        $date_es = date_parse_from_format("d-m-Y", $data['caducidad']);
        if ($date_es['warning_count'] > 0 || $date_es['error_count'] > 0) return true;        
      });
    }
       
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

  public function delete(){ // :/
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
          'required'  => Config::get('msgErrors.idempty'),
          'exists'    => Config::get('msgErrors.idnotfound'),
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
      $sgrUser->detach(); //implementar
      $sgrUser->delete(); //implementar
      $respuesta['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
      return $respuesta;
    }
  }
  
  /**
    * //Request by Ajax
    * 
  */
  public function edit(){

    $result = array ('exito' => false, 'errors' => array(), 'user' => array(),'msg' => '','rol' => '','caducada' => '');
    
    
    
    $rules = array(
        'id'        => 'required',
        'nombre'    => 'required',
        'apellidos' => 'required',
        'estado'    => 'required',
        'colectivo' => 'required',
        'caducidad' => 'required|date|date_format:d-m-Y',
        'capacidad' => 'required|in:1,2,3,4,5',
        'email'     => 'required|email',
        );

    $messages = array(
            'required'   => 'El campo <strong>:attribute</strong> es obligatorio.',
            'date_es'    => 'El campo <strong>:attribute</strong> debe ser una fecha válida.',
            'in'         => 'El campo <strong>:attribute</strong> es erroneo.',
            'email'      => 'El campo <strong>:attribute</strong> debe ser una dirección de email válida',
           );
  
 
    $validator = Validator::make(Input::all(), $rules, $messages);
    

    $fecha = Input::get('caducidad'); 
    if (!empty($fecha)){
      $data = Input::all();
      $validator->sometimes('caducidad','date_es',function($data){
        $date_es = date_parse_from_format("j-m-Y", $data['caducidad']);
        if ($date_es['warning_count'] > 0 || $date_es['error_count'] > 0) return true;        
      });
    }
   
   
    if ($validator->fails())
    {
        $result['errors'] = $validator->errors()->toArray(); 
        //return $result;
    }
    else{  
      // salvamos los datos.....
      $user = User::findOrFail(Input::get('id'));
      
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

      //cierra notificación de alta en caso de que exista....
      $this->cierraNotificacion($user->username);
      $result['exito'] = true;
      
    }
    $result['user'] = $user->toArray();
    $result['msg'] = Config::get('msg.success');
    $result['capacidad'] = $user->getRol();
    $result['caducada'] = $user->caducado();
    return $result;
  }

 
  
  /**
    * @param $id int
    * @return $user Object User
  */
  public function user(){
    $id = Input::get('id','');
    $user = User::findOrFail($id);

    return $user;
  }
  
  //Home rol admin
  public function home(){
    $veractivados = Input::get('veractivados',0);
    $verdesactivados = Input::get('verdesactivados',0);
    
    $notificaciones = Notificacion::where('estado','=','abierta')->orderby('id','desc')->get();
    return View::make('admin.index')->with(compact('notificaciones'))->nest('dropdown','admin.dropdown');
  }

 

  public function newUser(){
    return View::make('admin.userNew')->with("user",Auth::user())->nest('dropdown',Auth::user()->dropdownMenu());
  }
 
  public function activeUserbyajax(){

    $result = array('success' => false);
    
    $username = Input::get('username','');
    $colectivo = Input::get('colectivo','');
    $caducidad = Input::get('caducidad','');
    $observaciones = Input::get('observaciones','');

    $rol = Input::get('rol','1');
    //$id = Input::get('id','');

    $user = User::where('username','=',$username)->first();

    if (!empty($user)) {
      
      $user->estado = true;
      $user->colectivo = $colectivo;
      $user->capacidad = $rol;
      $user->observaciones = $observaciones;
      if (empty($caducidad)) $caduca = date('Y-m-d');
      else $caduca = sgrDate::parsedatetime($caducidad,'d-m-Y','Y-m-d');
      $user->caducidad = $caduca;
      $user->save();

      
      $this->cierraNotificacion($username);
      //mail to User by Activate
      $sgrMail = new sgrMail();
      $sgrMail->notificaActivacionUVUS($user->id);            
      
      $result['success'] = true;

    }

    return $result;
  }

  public function ajaxDelete(){
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

  public function desactiveUserbyajax(){
    $result = array('success' => false);
    
    $username = Input::get('username','');
    $colectivo = Input::get('colectivo','');
    $caducidad = Input::get('caducidad','');
    $observaciones = Input::get('observaciones','');
    $rol = Input::get('rol','1');
    

    $user = User::where('username','=',$username)->first();

    if (!empty($user)) {
      
      $user->estado = false;
      $user->colectivo = $colectivo;
      $user->capacidad = $rol;
      $user->observaciones = $observaciones;
      
      if (empty($caducidad)) $caduca = date('Y-m-d');
      else $caduca = sgrDate::parsedatetime($caducidad,'d-m-Y','Y-m-d');
      $user->caducidad = $caduca;
      $user->save();

      
      $this->cierraNotificacion($username);
      
      
      $result['success'] = true;

    }

    return $result;
  }

  private function cierraNotificacion($username){
      Notificacion::where('source','=',$username)->update(array('estado' => 'cerrada'));
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