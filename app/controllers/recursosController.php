<?php

class recursosController extends BaseController{

  


/**
  * 
  */
  public function listargrupos(){
    
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
    $offset = Input::get('offset','1');
    $page = Input::get('page','0');
    $search = Input::get('search','');
    $idgruposelected = Input::get('grupoid','');
    
    
    //if (!empty($idgruposelected)) $recursosListados = Recurso::where('grupo_id','=',$idgruposelected)->first()->grupo;

    //$grupos = User::find(Auth::user()->id)->supervisa()->where('nombre','like',"%$search%")->orderby($sortby,$order)->paginate($offset); 

    //Output
    //if (Auth::user()->isAdmin()){
    //  $grupos = Recurso::groupby('grupo_id')->orderby('grupo','asc')->get();
    //  $recursos = Recurso::where('nombre','like',"%$search%")->where('grupo_id','like','%'.$idgruposelected.'%')->orderby($sortby,$order)->paginate($offset);
    //}
    //else {
    //  $grupos = User::find(Auth::user()->id)->supervisa()->groupby('grupo_id')->orderby('grupo','asc')->get();
    //  $recursos = User::find(Auth::user()->id)->supervisa()->where('nombre','like',"%$search%")->where('grupo_id','like','%'.$idgruposelected.'%')->orderby($sortby,$order)->paginate($offset); 
    //}
    //Grupos que contienen algún recurso supervisado por Auth::user.
    $grupos = GrupoRecurso::all()->filter(function($grupo){
      
      $recursos = $grupo->recursos->each(function($recurso){
        return $recurso->supervisores->contains(Auth::user()->id);  
      }); 
      if ($recursos->count() > 0) return true;
      
     });


    return View::make('admin.grupolist')->with(compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAddGrupo','admin.modalgrupos.add');
  } /**
  * 
  */
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
    

    return View::make('admin.recurselist')->with(compact('recursos','sortby','order','grupos','idgruposelected','recursosListados'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAdd','admin.recurseModalAdd',compact('grupos'))->nest('modalEdit','admin.recurseModalEdit',array('recursos'=>$grupos))->nest('modalEditGrupo','admin.modaleditgrupo')->nest('recurseModalAddUserWithRol','admin.recurseModalAddUserWithRol')->nest('recurseModalRemoveUserWithRol','admin.recurseModalRemoveUserWithRol')->nest('modaldeshabilitarecurso','admin.modaldeshabilitarecurso');
  } 

  //Devuelve los recursos de una misma agrupación/grupo en forma de html options

  public function getRecursos(){
    
    //Default output 
    $addOptionAll = false;
    $tipoRecurso = '';
    $disabledAll = 0;

    $grupo = Input::get('groupID','');
    
    if(empty($grupo)) $recursos = array();
    else {
      $recursos = Recurso::where('grupo_id','=',$grupo)->get();  
      //se filtran para obtener sólo aquellos con visibles o atendidos para el usuario logeado
      $recursos = $recursos->filter(function($recurso){
          return $recurso->visible() || $recurso->esAtendidoPor(Auth::user()->id); });
      //Añadir opción reservar "todos los puestos o equipos"
      if (!Auth::user()->isUser() && $recursos[0]->tipo != 'espacio') $addOptionAll = true;
      //tipo de recurso
      $tipoRecurso = $recursos[0]->tipo;
      //número de puestos//equipos disabled
      $numerodeitemsdisabled = Recurso::where('grupo_id','=',$grupo)->where('disabled','=','1')->count();
      if($numerodeitemsdisabled == $recursos->count()) $disabledAll = 1;

    }

    return View::make('calendario.optionsRecursos')->with(compact('recursos','tipoRecurso','addOptionAll','disabledAll'));
  }



  public function deshabilitar(){
 
    $id = Input::get('idDisableRecurso','');
    $motivo = Input::get('motivo','');

    
    if (empty($id)) return 'Identificador vacio: No se ha realizado ninguna acción....';
    
    $result = Recurso::where('id','=',$id)->update(array('disabled' => true,'motivoDisabled' => $motivo));
    
    //Enviar mail a usuarios con reserva futuras
    $sgrMail = new sgrMail();
    $sgrMail->notificaDeshabilitaRecurso($id,$motivo);         
    
    $recurso = Recurso::findOrFail($id);
    $respuesta = 'Recurso <i>'.$recurso->nombre.' ('.$recurso->grupo.')</i> <b>deshabilitado</b> con éxito....';
    return $respuesta;
  }
  
  public function habilitar(){
 
    $id = Input::get('idDisableRecurso','');

    
    if (empty($id)) return 'Identificador vacio: No se ha realizado ninguna acción....';

    $recurso = Recurso::where('id','=',$id)->update(array('disabled' => false,'motivoDisabled' => ''));
    
    //Enviar mail a usuarios con reserva futuras
    $sgrMail = new sgrMail();
    $sgrMail->notificaHabilitaRecurso($id); 

    $recurso = Recurso::findOrFail($id);
    $respuesta = 'Recurso <i>'.$recurso->nombre.' ('.$recurso->grupo.')</i> <b>habilitado</b> con éxito....';
    return $respuesta;
    
  } 
  

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

  /**
  * Devuelve los usuarios con relación de supervisor // tecnico // validador
  * @param Input::get('idrecurso') int identificador de recurso
  */
  public function usersWithRelation(){

    //Input 
    $idrecurso = Input::get('idrecurso','');

    //Output
    $result = array('error'         => false,
                    'supervisores'  => array(),
                    'validadores'   => array(),
                    'tecnicos'      => array(),
                    'msg'           => '');
    //check $idrecurso
    if (empty($idrecurso)){
      $result['error'] = true;
      $result['mgs']  = Config::get('msg.idnotfound');
      return $result;
    }
    else{
      $recurso = Recurso::findOrFail($idrecurso);
      if ($recurso->supervisores->count() > 0) $result['supervisores'] = $recurso->supervisores->toArray();
      if ($recurso->validadores->count() > 0) $result['validadores'] = $recurso->validadores->toArray();
      if ($recurso->tecnicos->count() > 0) $result['tecnicos'] = $recurso->tecnicos->toArray();  
    }
      
    return $result;  
    
   // return View::make('admin.supervisores')->with(compact('recurso','supervisores','sortby','order','offset','search'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menu','admin.menuSupervisores',['idRecurso' => $recurso->id, 'recurso' => $recurso->nombre])->nest('recurseModalAddUserWithRol','admin.recurseModalAddUserWithRol',['recurso' => $recurso])->nest('recurseModalRemoveUserWithRol','admin.recurseModalRemoveUserWithRol');
  }

  //añade relación usuario recurso
  public function addUserWithRol(){
    
    //input
    $idRecurso = Input::get('idRecurso','');
    $username  = Input::get('username','');
    $rol       = Input::get('rol','');      
    //output
    $respuesta = array( 'error'     => false,
                        'msg'       => '',
                        'user'      => array(),
                        'recurso'   => array(),
                        'relacion'  => '',
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
    $user = User::where('username','=',$username)->first();
    $respuesta['user']= $user->toArray();
    $respuesta['recurso'] = $recurso->toArray();
    $idUser = $user->id;
    switch ($rol) {
      //tecnicos
      case '1':
        $tecnicos = Recurso::find($idRecurso)->tecnicos;
        if ($tecnicos->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este recurso.';
          
          return $respuesta;
        }
        $recurso->tecnicos()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>técnico</b></i> con éxito.';
        $respuesta['relacion'] = 'tecnico';
        break;
      //Supervisor
      case '2':
        $supervisores = Recurso::find($idRecurso)->supervisores;
        if ($supervisores->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>supervisor</b></i> de este recurso.';
         
          return $respuesta;
        }
        $recurso->supervisores()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>supervisor</b></i> con éxito.';
         $respuesta['relacion'] = 'supervisor';
        break;
      //Validador
      case '3':
        $validadores = Recurso::find($idRecurso)->validadores;
        if ($validadores->contains($idUser)){
          $respuesta['error'] = true;
          $respuesta['msg'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este recurso.';
          
          return $respuesta;
        }
        $recurso->validadores()->attach($idUser);
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
        $respuesta['relacion'] = 'validador';
        break;
      
      default:
        $respuesta['error'] = false;
        $respuesta['msg'] = 'Identificador de rol no esperado: ' . $rol;
        break;
    }

    

    return $respuesta;
  }
  //elimina la relación 
  public function removeUsersWithRol(){
    
    //input
    $idRecurso                = Input::get('idrecurso','');
    $detachSupervisores       = Input::get('supervisores','');
    $detachValidadores        = Input::get('validadores','');
    $detachTecnicos           = Input::get('tecnicos','');
    
    //output
    $respuesta = array( 'error' => false,
                        'msg' => '',
                        'supervisores' => '',
                        'validadores' => '',
                        'tecnicos' => '',
                        );

    if (empty($idRecurso)){
      $respuesta['error'] = true;
      $respuesta['msg'] = Config::get('msg.idnotfound');
      return $respuesta;
    }

    $recurso = Recurso::findOrFail($idRecurso);
    if (!empty($detachSupervisores))
      foreach ($detachSupervisores as $idSupervisor) {
        $recurso->supervisores()->detach($idSupervisor);
        
      }
    if (!empty($detachValidadores))  
      foreach ($detachValidadores as $idValidador) {
        $recurso->validadores()->detach($idValidador);
        
      }
    if (!empty($detachTecnicos))
      foreach ($detachTecnicos as $idTecnico) {
        $recurso->tecnicos()->detach($idTecnico);
        
      }
    
    $respuesta['msg'] = Config::get('msg.success');
    $respuesta['supervisores'] = $recurso->supervisores->toArray();
    $respuesta['validadores'] = $recurso->validadores->toArray();
    $respuesta['tecnicos'] = $recurso->tecnicos->toArray();
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