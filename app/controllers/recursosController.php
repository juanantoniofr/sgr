<?php

class recursosController extends BaseController{

  /**
    * //Añade un nuevo recurso a la base de datos
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
    $tipo =  Input::get('tipo'); //espacio|equipo
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
    }

    return $result;
  }

  /**
     * //Añade un nuevo puesto
     *
     * @param Input::get('idrecurso')   int
     * @param Input::get('nombre')      string
     * @param Input::get('descripcion') string
     * @param Input::get('tipo')        string
     * @param Input::get('id_lugar')    string
     * @param Input::get('modo')        int (0|1)
     * @param Input::get('roles')       array
     *
     * @return $result                  array    
  */ 
  public function addPuesto(){
    
    //Input
    $idrecurso = Input::get('idrecurso');
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //puesto
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
        'idrecurso' =>  'required|exists:recursos,id',
        'nombre'    =>  'required|unique:recursos',
        'tipo'      =>  'required|in:'.implode(',',Config::get('options.tipoRecursos')),  
        'modo'      =>  'required|in:'.implode(',',Config::get('options.modoGestion')),
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
      //Si hay errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      //Si no hay errores en el formulario
      $recurso = new Recurso;
      $recurso->nombre = $nombre;
      $recurso->tipo = $tipo;
      $recurso->descripcion = $descripcion;
      $recurso->id_lugar = $id_lugar;
      $recurso->acl = $this->buildJsonAcl($modo,$roles);
      $recurso->espacio_id = $idrecurso;
      $recurso->save();

      $result['msg'] = Config::get('msg.success');
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

      //Softdelete recurso y eventos
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      $sgrRecurso->setRecurso($recurso);
      $sgrRecurso->delEvents();
      $sgrRecurso->del();
      
      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaDeleteRecurso($id);

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
                     'msg'       => '',    
                     'error'     => false,
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
      //enable
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      $sgrRecurso->setRecurso($recurso);
      $sgrRecurso->enabled();
      $sgrRecurso->save();

      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaHabilitaRecurso($id);
     
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
      //disabled
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      $sgrRecurso->setRecurso($recurso);
      $sgrRecurso->disabled();
      $sgrRecurso->save();

      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaDeshabilitaRecurso($id,$motivo);

      $result['msg'] = Config::get('msg.actionSuccess');
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
    * //Devuelve el campo descripción dado un idrecurso
    * @param Input::get('idrecurso','') int identificador de recurso
    *
    * @return $descripcion string
  */
  public function getDescripcion(){

    //Input
    $idRecurso = Input::get('idrecurso','');
    //Output 
    $result = array( 'errors'         => array(),
                     'descripcion'   => '',    
                     'error'         => false,
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
    
    //Obtener descripción or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{
      $recurso = Recurso::find($idRecurso);
      if (empty($recurso->descripcion)) $result['descripcion'] = $recurso->grupo->descripcion; //descripción general de todos los espacios,equipos o puestos del grupo
      else $result['descripcion'] = $recurso->descripcion; //descripción del elemento
    }
    
    return $result;
  } 

}