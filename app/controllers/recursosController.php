<?php

class recursosController extends BaseController{

   /**
     * @param Input::get('nombre')      string
     * @param Input::get('descripcion') string
     * @param Input::get('tipo')        string
     * @param Input::get('id_lugar')    string
     * @param Input::get('grupo_id')    int 
     * @param Input::get('modo')        int (0|1)
     * @param Input::get('roles')       array
     *
     * @return $result                  array    
   */ 
  public function add(){
    
    //Input
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|puesto|equipo
    $grupo_id = Input::get('grupo_id');
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    
    //Validación de formulario   
    $rules = array(
        'nombre'    => 'required|unique:recursos',
        'tipo'      => 'required|in:'.implode(',',Config::get('options.tipoRecursos')),  
        'grupo_id'  => 'required|exists:grupoRecursos,id',
        'modo'      => 'required|in:'.implode(',',Config::get('options.modoGestion')),
        );

     $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
          'unique'    => 'Existe un recurso con el mismo nombre...',
          'tipo.in'   => 'El tipo de recurso no está definido...',
          'modo.in'   => 'Modo de Gestión de solicitudes de reserva no definido....',
          'exists'    => 'No existe identificador de grupo...',
        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      //Si no hay errores en el formulario
      $recurso = new Recurso;
      $recurso->nombre = $nombre;
      $recurso->grupo_id = $grupo_id;
      $recurso->tipo = $tipo;
      $recurso->descripcion = $descripcion;
      $recurso->id_lugar = $id_lugar;
      $recurso->acl = $this->buildJsonAcl($modo,$roles);
          

      $recurso->save();

      $result['msg'] = Config::get('msg.success');
    
      //Establecer relación de supervisor 
      $recurso->supervisores()->attach(Auth::user()->id); //El propio usuario que lo añade si no es administrador
    }

    return $result;
  }

  /**
   * @param Input::get('nombre')      string
   * @param Input::get('descripcion') string
   * @param Input::get('tipo')        string
   * @param Input::get('id_lugar')    string
   * @param Input::get('grupo_id')    int 
   * @param Input::get('modo')        int (0|1)
   * @param Input::get('roles')       array
   *
   * @return $result                  array    
   */ 
  public function update(){
   
    //return Recurso::find(Input::get('id',''));
    //Input
    $id = Input::get('id','');
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|puesto|equipo
    $grupo_id = Input::get('grupo_id');
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    
    //Validación de formulario   
    $rules = array(
        'id'        => 'required|exists:recursos',
        'nombre'    => 'required|unique:recursos,nombre,'.Input::get('id'),
        'tipo'      => 'required|in:'.implode(',',Config::get('options.tipoRecursos')),  
        'grupo_id'  => 'required|exists:grupoRecursos,id',
        'modo'      => 'required|in:'.implode(',',Config::get('options.modoGestion')),
        );

     $messages = array(
          'id.exists' => 'Identificador de recurso no encontrado....',
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
          'unique'    => 'Existe un recurso con el mismo nombre...',
          'tipo.in'   => 'El tipo de recurso no está definido...',
          'modo.in'   => 'Modo de Gestión de solicitudes de reserva no definido....',
          'exists'    => 'No existe identificador de grupo...',
        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      Recurso::find($id)->update(array( 'nombre'      => $nombre,
                                        'tipo'        => $tipo,
                                        'grupo_id'    => $grupo_id,
                                        'modo'        => $modo,
                                        'descripcion' => $descripcion,
                                        'id_lugar'    => $id_lugar,
                                        'acl'         => $this->buildJsonAcl($modo,$roles),)
                                 );

      

      $result['msg'] = Config::get('msg.success');
    }
    return $result;
  }

  /**
    * @param Input::get('idrecurso') int
    *
    * @return $result array(boolean|string) 
  */
  public function del(){
    
    //input
    $id = Input::get('idrecurso','');
    
    //Output 
    $result = array( 'errors'    => array(),
                        'msg'   => '',    
                        'error'   => false,
                      );

    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        );

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
          'exists'    => 'No existe identificador de grupo...', 
          );
    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{

      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaDeleteRecurso($id,$motivo);
      
      //Softdelete recurso y eventos
      Recurso::find($id)->events()->delete();
      Recurso::find($id)->delete();
      $result['msg'] = Config::get('msg.actionSuccess');
    }
    
    return $result;
  }
  
  /**
    * devuelve recurso dado su id (para modal admin.modalrecursos.edit)
    * 
    * @param void
    *
    * @return object Recurso
  */
  public function getrecurso(){
    return Recurso::findOrFail(Input::get('idrecurso'));
  }

  
  /**
    * //Devuelve los recursos de un mismo grupo en forma de html options para select en sidebar
    * @param void
    *
    * @return View::make('calendario.optionsRecursos') string
  */
  public function getRecursos(){
    
    //Input
    $id = Input::get('groupID','');
      
    //Output 
    $addOptionAll = false;
    $tipoRecurso = '';
    $disabledAll = 0;
    
    if(!empty($id)){
      $grupo = GrupoRecurso::findOrFail($id);
      
      //se filtran para obtener sólo aquellos visibles o atendidos para el usuario logeado
      $recursos = $grupo->recursos->filter(function($recurso){
          return $recurso->visible() || $recurso->esAtendidoPor(Auth::user()->id); });
      //tipo de recurso && número de puestos or equipos disabled
      $numerodeitemsdisabled = 0;
      foreach ($recursos as $recurso) {
        $tipoRecurso = $recurso->tipo;
        if($recurso->disabled == '1') $numerodeitemsdisabled++;
      }
      if($numerodeitemsdisabled == $recursos->count()) $disabledAll = 1;
      
      //Añadir opción reservar "todos los puestos o equipos"
      if (!Auth::user()->isUser() && $tipoRecurso != 'espacio' && !$disabledAll) $addOptionAll = true;
      
      return View::make('calendario.optionsRecursos')->with(compact('recursos','tipoRecurso','addOptionAll','disabledAll'));
    }

    return '';
    
  }

  /**
    * //habilita un recursos para su reserva
    *
    * @param Input::get('idrecurso') int
    *
    * @return $result array
  */
  public function enabled(){
 
    //input
    $id = Input::get('idrecurso','');
    
    //Output 
    $result = array( 'errors'    => array(),
                        'msg'   => '',    
                        'error'   => false,
                      );

    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        );

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'exists'    => 'No existe identificador de recurso en BD.', 
          );
    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{

      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaHabilitaRecurso($id);
      
      //Update campo disabled
      $recurso = Recurso::find($id);
      $recurso->disabled =  0;
      $recurso->save();
      $result['msg'] = Config::get('msg.actionSuccess');
    }
    
    return $result;
  } 

  /**
    * //Deshabilita un recursos para su reserva
    *
    * @param Input::get('idrecurso') int
    * @param Input::get('motivo') string
    *
    * @return $result array
  */
  public function disabled(){
 
    //input
    $id = Input::get('idrecurso','');
    $motivo = Input::get('motivo','');

    //Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        );

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'exists'    => 'No existe identificador de recurso en BD.', 
          );
    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{

      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaDeshabilitaRecurso($id,$motivo);
      
      //Update campo disabled
      $recurso = Recurso::find($id);
      $recurso->disabled =  1;
      $recurso->motivoDisabled = $motivo;
      $recurso->save();
      $result['msg'] = Config::get('msg.actionSuccess');
    }
    
    return $result;
  }

  /**
    * //Establece la relación presona-recurso (supervisor-validador-tecnico)
    *
    * @param Input::get('idrecurso')  int
    * @param Input::get('username')   string
    * @param Input::get('rol')        string
    *
    * @return $result array
    * 
  */
  public function addPersona(){
    
    //input
    $idRecurso = Input::get('idrecurso','');
    $username  = Input::get('username','');
    $rol       = Input::get('rol','');      
    
    
    //Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        'username'   => 'required|exists:users,username',
        'rol'        => 'required|in:1,2,3'
        );

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idrecurso.exists'    => 'No existe identificador de recurso en BD.',
          'username.exists'     => 'No existe usuario en la BD.',
          'in'                  => 'El campo <strong>:attribute</strong> no coincide con ninguno de los valores aceptados.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
      $recurso = Recurso::find($idRecurso);
      $user = User::where('username','=',$username)->first();
      //$respuesta['user']= $user->toArray();
      //$respuesta['recurso'] = $recurso->toArray();
      $idUser = $user->id;
      switch ($rol) {
        //tecnicos
        case '1':
          $tecnicos = $recurso->tecnicos;
          if ($tecnicos->contains($idUser)){
            $result['error'] = true;
            $result['errors']['tecnico'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este recurso.';
            return $result;
          }
          $recurso->tecnicos()->attach($idUser);
          break;
        
        //Supervisor
        case '2':
          $supervisores = $recurso->supervisores;
          if ($supervisores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['supervisor'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>supervisor</b></i> de este recurso.';
            return $result;
          }
          $recurso->supervisores()->attach($idUser);
          
          //$respuesta['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>supervisor</b></i> con éxito.';
          //$respuesta['relacion'] = 'supervisor';
          break;
      
        //Validador
        case '3':
          $validadores = $recurso->validadores;
          if ($validadores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['validador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este recurso.';
            return $respuesta;
          }
          $recurso->validadores()->attach($idUser);
          //$respuesta['error'] = false;
          $result['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
          //$respuesta['relacion'] = 'validador';
          break;
      
        default:
          $result['error'] = false;
          $result['msg'] = 'Identificador de rol no esperado: ' . $rol;
        break;
      }//fin case
    }//fin else

    $result['msg'] = Config::get('msg.actionSuccess');
    return $result;
  }

  /**
    * // Devuelve listas de input type checkbox para formulario con las personas que tienen alguna de las relaciones de supervisor//técnico//validador
    * 
    * @param Input::get('idrecurso') int identificador de recurso
    *
    * @return $result array
  */
  public function htmlCheckboxPersonas(){

    //input
    $id = Input::get('idrecurso','');

    //Output
    $result = array( 'errors'                 => array(),
                     'error'                 => false,
                     'htmlCheckboxPersonas'  => '',
                    );
    
    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        );

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'exists'    => 'No existe identificador de recurso en BD.', 
          );
    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //get personas or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{
      $recurso = Recurso::findOrFail($id);
      $result['htmlCheckboxPersonas'] = (string) View::make('admin.modalrecursos.checkboxPersonas')->with(compact('recurso'));
    }
      
    return $result;  
  }


  /**
    * //elimina la relación recurso-persona
    *
    * @param Input::get('idrecurso') int
    * @param Input::get('supervisores_id) array
    * @param Input::get('validadores_id') array
    * @param Input::get('tecnicos_id') array
    *
    * @return $result array    
    *
  */
  public function removePersonas(){
    
    //input
    $idrecurso                = Input::get('idrecurso','');
    $detachSupervisores       = Input::get('supervisores_id',array());
    $detachValidadores        = Input::get('validadores_id',array());
    $detachTecnicos           = Input::get('tecnicos_id',array());
    
    //Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );

    //Validate
    $rules = array(
        'idrecurso'  => 'required|exists:recursos,id', //exists:table,column
        );

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idrecurso.exists'    => 'No existe identificador de recurso en BD.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
      return $result;
    }
    else {
      $recurso = Recurso::findOrFail($idrecurso);
      foreach ($detachSupervisores as $idSupervisor) {
        $recurso->supervisores()->detach($idSupervisor);
      }
      foreach ($detachValidadores as $idValidador) {
        $recurso->validadores()->detach($idValidador);
      }
      foreach ($detachTecnicos as $idTecnico) {
        $recurso->tecnicos()->detach($idTecnico);
      }
      $result['msg'] = Config::get('msg.success');
    }

    return $result;
  }


  /**
    * @param void
    *
    * @return $recursos Array(Recurso)  
  */
  public function recursosSinGrupo(){
    return View::make('admin.modalgrupos.recursosSinGrupo')->with('recursos',Recurso::where('grupo_id','=','0')->get());
  }

  /**
    *
    * // Devuelve en formato json los roles con acceso a un recurso
    *
    * @param $modo int (1|0) gestión de soliticitudes de reserva atendida o desantendida 
    * @param $roles array
    *
    * @return $acl string 
  */
  private function buildJsonAcl($modo,$roles){

    $acl = array('r' => '',
                  'm' => '0',//por defecto gestión Atendida de las solicitudes de uso.
                  );
    $acl['m'] = $modo;
    $roles[] = Config::get('options.idroladministrador'); //Administrador tiene accseso
    $listIdRolesConAcceso = implode(',',$roles);
    $acl['r'] = $listIdRolesConAcceso;

    return json_encode($acl);
  }

  /**
  * ?????
  */
 
  //Devuelve el campo descripción dado un id_recurso ???
  public function getDescripcion(){

    $idRecurso = Input::get('idrecurso','');
    if (empty($idRecurso)) return '-1';

    $descripcion = '';
    $recurso = Recurso::find($idRecurso);
    $descripcion = $recurso->descripcion; //descripción del elemento
    
    if (empty($descripcion)) $descripcion = $recurso->descripcionGrupo; //descripción general de todos los espacios,equipos o puestos del grupo
    
    return $descripcion;
  } 

}