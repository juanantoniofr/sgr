<?php

class recursosController extends BaseController {

  public function getrecurso(){
    $result = array('atributos' => '',
                    'visibilidad' => array());
    $id = Input::get('id','');
    $recurso = Recurso::find($id)->toArray();
    $result['atributos'] = $recurso;
    $acl = json_decode($recurso['acl']);
    $result['visibilidad'] = explode(',',$acl->r);
    //$result['acl'] = $acl->r;
    return $result;
  }


  public function eliminar(){
 
    $id = Input::get('id','');

    
    if (empty($id)){
      Session::flash('message', 'Identificador vacio: No se ha realizado ninguna acción....');
      return Redirect::to($url);
    }

    $recurso = Recurso::findOrFail($id);
    $recurso->administradores()->detach();
    $recurso->delete();
    
    Session::flash('message', 'Recurso eliminado con éxito....');
    return Redirect::back();
    
  }

  public function deshabilitar(){
 
    $id = Input::get('id','');

    
    if (empty($id)){
      Session::flash('message', 'Identificador vacio: No se ha realizado ninguna acción....');
      return Redirect::to($url);
    }

    $recurso = Recurso::where('id','=',$id)->update(array('disabled' => true));
    
    //Enviar mail a usuarios con reserva futuras
    $sgrMail = new sgrMail();
    $sgrMail->notificaDeshabilitaRecurso($id);         

    Session::flash('message', 'Recurso <b>deshabilitado</b> con éxito....');
    return Redirect::back();
    
  }
  
  public function habilitar(){
 
    $id = Input::get('id','');

    
    if (empty($id)){
      Session::flash('message', 'Identificador vacio: No se ha realizado ninguna acción....');
      return Redirect::to($url);
    }

    $recurso = Recurso::where('id','=',$id)->update(array('disabled' => false));
    
    //Enviar mail a usuarios con reserva futuras
    $sgrMail = new sgrMail();
    $sgrMail->notificaHabilitaRecurso($id); 

    Session::flash('message', 'Recurso <b>habilitado</b> con éxito....');
    return Redirect::back();
    
  }
  public function admins(){

    $sortby = Input::get('sortby','username');
    $order = Input::get('order','asc');
    $offset = Input::get('offset','10');
    $search = Input::get('search','');
    $idRecurso = Input::get('idRecurso','');

    $recurso = Recurso::find($idRecurso);
    $administradores = $recurso->administradores()->orderby($sortby,$order)->paginate($offset);
    return View::make('admin.recurseAdmins')->with(compact('recurso','administradores','sortby','order','offset','search'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuAdministradores','admin.menuAdministradores',['idRecurso' => $recurso->id, 'recurso' => $recurso->nombre]);
  }

  public function addAdmin(){
    
    $idRecurso = Input::get('idRecurso','');
    $username = Input::get('username','');
    
    $recurso = Recurso::find($idRecurso);
    $users = array();
    if(!empty($username)){
      $users = User::where('username','like',"%$username%")->where('capacidad', '>', '2')->get();
      $aIdRecurso = array($idRecurso);
      $users = $users->filter(function($user) use ($idRecurso) {
          return !ACL::isAdminForRecurso($user->id,$idRecurso);
        });
      }  

    return View::make('admin.recurseAddAdmin')->with(compact('username','users','recurso'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuAdministradores','admin.menuAdministradores',['idRecurso' => $recurso->id, 'recurso' => $recurso->nombre]);
  }

  public function addRecursoAdmin(){

    $idRecurso = Input::get('idRecurso','');
    $username = Input::get('username','');
    $admins = Input::get('admins',array());

    if(!empty($admins)){
      $recurso = Recurso::find($idRecurso);
      $recurso->administradores()->attach($admins);
      Session::flash('msg', 'administrador/es añadido/s con éxito......');
    }
    else Session::flash('msg' , 'No se ha marcado ningún usuario......');
    

    $url = URL::route('addRecursoAdmin',['username' => $username, 'idRecurso' => $idRecurso]); 
    return Redirect::to($url);
    
  }


	public function formAdd(){

    $recursos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
    return View::make('admin.recurseAdd')->with(compact('recursos'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos');
  }

  public function addRecurso(){
    
    //@params
    $idgrupo = Input::get('idgrupo','');
    $nuevogrupo = Input::get('nuevogrupo','');
    //out
    $respuesta = array( 'error' => false,
                        'msg'   => 'Mensaje para el usuario....idgrupo = ' . $idgrupo .' y, nuevogrupo = ' . $nuevogrupo,
                        'errors' => array());
    
    

    
    $rules = array(
        'nombre'      => 'required|unique:recursos',
        'nuevogrupo'  => 'required_if:idgrupo,0',
        );

     $messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
          'unique'        => 'Existe un recurso con el mismo nombre....',
          'nuevogrupo.required_if'  => 'Campo requerido....',
        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    
    if ($validator->fails()){
        $respuesta['error'] = true;
        $respuesta['errors'] = $validator->errors()->toArray();
      }

    else{  
      $recurso = new Recurso;
      $recurso->nombre = Input::get('nombre');
      $recurso->grupo = $this->getNombre();
      $recurso->grupo_id = $this->getIdGrupo();
      $recurso->tipo = Input::get('tipo');
      $recurso->descripcion = Input::get('descripcion');
      $recurso->acl = $this->getACL();
      $recurso->id_lugar = Input::get('id_lugar');

      if ($recurso->save()) Session::flash('message', 'Recurso <strong>'. $recurso->nombre .' </strong>añadido con éxito');
    
      //Añadir administradores
      $ids = array();
      if (Auth::user()->capacidad != 4) $ids[] = Auth::user()->id; //El propio usuario que lo añade si no es administrador
     
      if (!empty($ids)) $recurso->administradores()->attach($ids);

      
    }//fin else

    return $respuesta;
  }//fin function

  public function listar(){
      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
    $offset = Input::get('offset','10');
    $search = Input::get('search','');

    $idgruposelected = Input::get('grupoid','');
    
    $recursosListados = 'Todos los recursos';
    if (!empty($idgruposelected)) $recursosListados = Recurso::where('grupo_id','=',$idgruposelected)->first()->grupo;

    if (Auth::user()->capacidad == '4'){//administrador puede ver todo
      $recursos = Recurso::where('nombre','like',"%$search%")->orderby($sortby,$order)->paginate($offset);
      $grupos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
      if (!empty($idgruposelected)) $recursos = Recurso::where('nombre','like',"%$search%")->where('grupo_id','=',$idgruposelected)->orderby($sortby,$order)->paginate($offset);
      
      return View::make('admin.recurselist')->with(compact('recursos','sortby','order','grupos','idgruposelected','recursosListados'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAdd','admin.recurseModalAdd',array('grupos'=>$grupos))->nest('modalEdit','admin.recurseModalEdit',array('recursos'=>$grupos))->nest('modalEditGrupo','admin.modaleditgrupo');
    }
    

    $recursos = User::find(Auth::user()->id)->supervisa()->where('nombre','like',"%$search%")->orderby($sortby,$order)->paginate($offset);
    $grupos = User::find(Auth::user()->id)->supervisa()->groupby('grupo_id')->orderby('grupo','asc')->get();
    if (!empty($idgruposelected)) $recursos = Recurso::where('nombre','like',"%$search%")->where('grupo_id','=',$idgruposelected)->orderby($sortby,$order)->paginate($offset);

    return View::make('admin.recurselist')->with(compact('recursos','sortby','order','grupos','idgruposelected','recursosListados'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAdd','admin.recurseModalAdd',compact('grupos'))->nest('modalEdit','admin.recurseModalEdit',array('recursos'=>$grupos))->nest('modalEditGrupo','admin.modaleditgrupo');
  } 


  public function formEdit(){

    $id = Input::get('id');
    $recursos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
    $recurso = Recurso::find($id);
    
    $modo = 0;//Con validación
    if (ACL::automaticAuthorization($id)) $modo = 1;//sin validación
    
    $permisos = json_decode($recurso->acl,true);
    $capacidades = $permisos['r']; //array con los valores de la capacidades con acceso

    return View::make('admin.recurseEdit')->with(compact('recursos','recurso','modo','capacidades'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos');
  }

  public function editRecurso(){
   
    $id = Input::get('id');
    $idgrupo = Input::get('idgrupo','');
    $nuevogrupo = Input::get('nuevogrupo','');
    //Output
    $respuesta = array( 'errores'   => array(),
                        'hasError'  => false);
    $rules = array(
        'nombre'      => 'required|unique:recursos,nombre,'.$id,
        'nuevogrupo'  => 'required_if:idgrupo,0',
        );

     $messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
          'unique'        => 'Existe un recurso con el mismo nombre....',
          'nuevogrupo.required_if'  => 'El valor no puede quedar vacio....',
        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    //$url = URL::route('editarecurso.html',['id' => $id]); 
    if ($validator->fails()){
        //return Redirect::to($url)->withErrors($validator->errors())->withInput(Input::all());;
        $respuesta['errores'] = $validator->errors()->toArray();
        $respuesta['hasError'] = true;
        return $respuesta;
      }
    else{  
      
      $recurso = Recurso::find($id);

      $recurso->nombre = Input::get('nombre');
      $recurso->grupo = $this->getNombre();
      $recurso->grupo_id = $this->getIdGrupo();
      $recurso->tipo = Input::get('tipo','espacio');
      $recurso->descripcion = Input::get('descripcion');
      $recurso->acl = $this->getACL();
      $recurso->id_lugar = Input::get('id_lugar');

      if ($recurso->save()) Session::flash('message', 'Cambios en <strong>'. $recurso->nombre .' </strong> salvados...');
    }

    
    return $respuesta;
  }

  public function updateDescripcionGrupo(){

    
    //Input
    $idRecurso = Input::get('idRecurso','');
    $grupo = Input::get('grupo','');
    $descripcionGrupo = Input::get('descripcion','');
 
    //Output
    $respuesta = array( 'errores'   => array(),
                        'hasError'  => false);
    //check input
    if ( empty($idRecurso) ) {
      $respuesta['hasError']=true;
      Session::flash('message','Error en el envío del formulario...');
      return $respuesta;
    }

    $rules = array(
        'grupo'      => 'required',
        );

     $messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
          );
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()){
        $respuesta['errores'] = $validator->errors()->toArray();
        $respuesta['hasError'] = true;
        return $respuesta;
      }
    else{  
        $groupToUpdate = Recurso::find($idRecurso)->grupo;
        $recursosDelMismoGrupo = Recurso::where('grupo','=',$groupToUpdate)->update(array('descripcionGrupo' => $descripcionGrupo, 'grupo' => $grupo));
        Session::flash('message', 'Cambios en <strong>'. $grupo . $idRecurso . ' </strong> salvados con éxito...');
      }
    

    //$respuesta = Input::all();
    return $respuesta;
  }

  //private
  private function getNombre(){

    $idgrupo = Input::get('idgrupo');
    $nuevogrupo = Input::get('nuevogrupo','');

    if (empty($nuevogrupo)) $nombregrupo = Recurso::where('grupo_id','=',$idgrupo)->first()->grupo;
    else $nombregrupo = $nuevogrupo;
   
    return $nombregrupo;
  }

  private function getIdGrupo(){

    $idgrupo = Input::get('idgrupo');
    $nuevogrupo = Input::get('nuevogrupo','');

    if (!empty($nuevogrupo)){
      //
      $identificadores = Recurso::select('grupo_id')->groupby('grupo_id')->get()->toArray();
      $idgrupo = 1;
      $salir = false;
      while(array_search(['grupo_id' => $idgrupo], $identificadores) !== false){
        $idgrupo++;
      }
    }

    return $idgrupo;
  }

  private function getACL(){

    $aACL = array('r' => '',
                  'm' => '0',//por defecto gestión Atendida de las solicitudes de uso.
                  );
    $aACL['m'] = Input::get('modo','0');
    $acceso = Input::get('acceso',array());
    $acceso[] = 4; //Añadir rol administrador
    $listIdRolesConAcceso = implode(',',$acceso);
    $aACL['r'] = $listIdRolesConAcceso;

    return json_encode($aACL);

  }


}//Fin de la Clase