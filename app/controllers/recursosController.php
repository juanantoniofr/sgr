<?php

class recursosController extends BaseController{

  //Devuelve el campo descripción dado un id_recurso
  public function getDescripcion(){

    $idRecurso = Input::get('idrecurso','');
    if (empty($idRecurso)) return '-1';

    $descripcion = '';
    $recurso = Recurso::find($idRecurso);
    $descripcion = $recurso->descripcion; //descripción del elemento
    
    if (empty($descripcion)) $descripcion = $recurso->descripcionGrupo; //descripción general de todos los espacios,equipos o puestos del grupo
    
    return $descripcion;
  } 

  //devuelve el recurso dado id y su visibilidad
  public function getrecurso(){
    
    $respuesta = array( 'atributos' => '',
                        'visibilidad' => array());
    $id = Input::get('id','');
    $recurso = Recurso::find($id)->toArray();
    $respuesta['atributos'] = $recurso;
    $acl = json_decode($recurso['acl']);
    $respuesta['visibilidad'] = explode(',',$acl->r);
    
    return $respuesta;
  }

  //Devuelve los recursos de una misma agrupación/grupo
  public function getRecursos(){
    
    $respuesta = array( 'recursos' => '',
                        'optionTodos' => false,
                        'tipoRecurso' => '');

    $grupo = Input::get('groupID','');
    $recursos = Recurso::where('grupo_id','=',$grupo)->get();
    //se filtran para obtener sólo aquellos con acceso para el usuario logeado
    $recursos = $recursos->filter(function($recurso){
        return $recurso->visible();
    });
    
    $respuesta['recursos'] = $recursos->toArray();
    if (!Auth::user()->isUser() && $recursos[0]->tipo != 'espacio') $respuesta['optionTodos'] = true;
    $respuesta['tipoRecurso'] = $recursos[0]->tipo;
    

    return $respuesta;
  }

  public function eliminar(){
 
    $id = Input::get('id','');

    
    if (empty($id)){
      Session::flash('message', 'Identificador vacio: No se ha realizado ninguna acción....');
      return Redirect::to($url);
    }

    $recurso = Recurso::findOrFail($id);
    $recurso->delete();
    $recurso->administradores()->detach();
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
  
  //devuelve los supervisores de un recurso
  public function supervisores(){

    $sortby = Input::get('sortby','username');
    $order = Input::get('order','asc');
    $offset = Input::get('offset','10');
    $search = Input::get('search','');
    $idRecurso = Input::get('idRecurso','');

    $recurso = Recurso::find($idRecurso);
    $supervisores = $recurso->supervisores()->orderby($sortby,$order)->paginate($offset);
    return View::make('admin.supervisores')->with(compact('recurso','supervisores','sortby','order','offset','search'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menu','admin.menuSupervisores',['idRecurso' => $recurso->id, 'recurso' => $recurso->nombre])->nest('modalAddSupervisor','admin.supervisorModalAdd',['recurso' => $recurso])->nest('modalConfirmaBajaSupervisor','admin.supervisorModalBaja');
  }

  //añade relación usuario recurso
  public function addUserWithRol(){
    
    //input
    $idRecurso = Input::get('idRecurso','');
    $username  = Input::get('username','');
    $rol       = Input::get('rol','');      
    //output
    $respuesta = array( 'error' => false,
                        'msg' => '',
                        );

    if (empty($rol)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Rol no seleccionado.';
      return $respuesta;
    }

    if (empty($idRecurso)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Identificador de recurso vacio.';
      return $respuesta;
    }

    if (empty($username)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Identificador de usuario UVUS vacio.';
      return $respuesta;
    }

    if (User::where('username','=',$username)->count() == 0) {
       $respuesta['error'] = true;
       $respuesta['msg'] = 'No existe usuario con UVUS <i>'.$username.'</i>.';
       return $respuesta;
    }

    if (Recurso::where('id','=',$idRecurso)->count() == 0) {
       $respuesta['error'] = true;
       $respuesta['msg'] = 'No existe recurso con identificador <i>'.$idRecurso.'</i>.';
       return $respuesta;
    }

    

    $recurso = Recurso::find($idRecurso);
    switch ($rol) {
      //tecnicos
      case '1':
        $tecnicos = Recurso::find($idRecurso)->tecnicos;
        $idUser = User::where('username','=',$username)->first()->id;
        if ($tecnicos->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este recurso.';
          return $respuesta;
        }
        $recurso->tecnicos()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>técnico</b></i> con éxito.';
        break;
      //Supervisor
      case '2':
        $supervisores = Recurso::find($idRecurso)->supervisores;
        $idUser = User::where('username','=',$username)->first()->id;
        if ($supervisores->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>supervisor</b></i> de este recurso.';
          return $respuesta;
        }
        $recurso->supervisores()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>supervisor</b></i> con éxito.';
        break;
      //Validador
      case '3':
        $validadores = Recurso::find($idRecurso)->validadores;
        $idUser = User::where('username','=',$username)->first()->id;
        if ($validadores->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este recurso.';
          return $respuesta;
        }
        $recurso->validadores()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
        break;
      
      default:
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Identificador de rol del esperado: ' . $rol;
        break;
    }

    

    return $respuesta;
  }
  //elimina la relación 
  public function removeUserWithRol(){
    
    //input
    $idRecurso  = Input::get('idrecurso','');
    $idUser     = Input::get('iduser','');
    
    //output
    $respuesta = array( 'error' => false,
                        'msg' => '',
                        );

    if (empty($idRecurso)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Identificador de recurso vacio.';
      return $respuesta;
    }

    if (empty($idUser)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Identificador de usuario vacio.';
      return $respuesta;
    }

    if (User::where('id','=',$idUser)->count() == 0) {
       $respuesta['error'] = true;
       $respuesta['msg'] = 'No existe usuario con identificador <i>'.$idUser.'</i>.';
       return $respuesta;
    }

    if (Recurso::where('id','=',$idRecurso)->count() == 0) {
       $respuesta['error'] = true;
       $respuesta['msg'] = 'No existe recurso con identificador <i>'.$idRecurso.'</i>.';
       return $respuesta;
    }

    $supervisores = Recurso::find($idRecurso)->supervisores;
    if (!$supervisores->contains($idUser)){
      $respuesta['error'] = true;
      $respuesta['msg'] = 'Usuario con identificador <i>'.$idUser.'</i> no es supervisor de este recurso.';
      return $respuesta;
    }

    $recurso = Recurso::find($idRecurso);
    $recurso->supervisores()->detach($idUser);
    $respuesta['error'] = false;
    $respuesta['msg'] = 'Operación realizada con éxito.';

    return $respuesta;
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

      
    }

    return $respuesta;
  }

  public function listar(){
    
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
    $offset = Input::get('offset','10');
    $search = Input::get('search','');
    $idgruposelected = Input::get('grupoid','');
    
    $recursosListados = 'Todos los recursos';
    if (!empty($idgruposelected)) $recursosListados = Recurso::where('grupo_id','=',$idgruposelected)->first()->grupo;

    //Output
    if (Auth::user()->isAdmin()){
      $grupos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
      $recursos = Recurso::where('nombre','like',"%$search%")->where('grupo_id','like','%'.$idgruposelected.'%')->orderby($sortby,$order)->paginate($offset);
    }
    else {
      $grupos = User::find(Auth::user()->id)->supervisa()->groupby('grupo_id')->orderby('grupo','asc')->get();
      $recursos = User::find(Auth::user()->id)->supervisa()->where('nombre','like',"%$search%")->where('grupo_id','like','%'.$idgruposelected.'%')->orderby($sortby,$order)->paginate($offset); 
    }
    

    return View::make('admin.recurselist')->with(compact('recursos','sortby','order','grupos','idgruposelected','recursosListados'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAdd','admin.recurseModalAdd',compact('grupos'))->nest('modalEdit','admin.recurseModalEdit',array('recursos'=>$grupos))->nest('modalEditGrupo','admin.modaleditgrupo')->nest('modalAddSupervisor','admin.supervisorModalAdd')->nest('modalConfirmaBajaSupervisor','admin.supervisorModalBaja');
  } 


  public function formEdit(){

    $id = Input::get('id');
    $recursos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
    $recurso = Recurso::find($id);
    
    $modo = 0;//Con validación
    if (!$recurso->validacion()) $modo = 1;//sin validación
    
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


}