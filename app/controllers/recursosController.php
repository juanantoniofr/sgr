<?php
class recursosController extends BaseController{
  
  /**
    * // Obtiene los items (equipos o espacios) de un espacio o tipoequipos
    *
    * @param $idrecurso int
    *
    * @return $result array()
    *
  */ 
  public function getitems(){

    //Input
    $idrecurso = Input::get('idrecurso','');
    //Output
    $result = array('error' => false,
                    'listoptions'   => '',
                    'errors' => array());
    //Validación de formulario   
    $rules = array('idrecurso' => 'required|exists:recursos,id');
    $messages = array('exists'  => 'Identificador de recurso no encontrado....',
                      'required'=> 'El campo <strong>:attribute</strong> es obligatorio....');
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{ 
      $recurso = Recurso::findOrFail($idrecurso);
      $sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      $sgrRecurso->setRecurso($recurso);
      //se filtran para obtener sólo aquellos visibles o atendidos para el usuario logeado
      $items = $sgrRecurso->items();
      
      $addOptionReservarTodo = $recurso->usuariopuedereservartodoslospuestos(Auth::user()->id);
      
      //número de puestos or equipos disabled
      $numerodeitemsdisabled = 0;
      $disabledAll = 0;
      foreach ($items as $item) {
        if($item->disabled == '1') $numerodeitemsdisabled++;
      }
      if($numerodeitemsdisabled == $items->count()) $disabledAll = 1;
      
      //Añadir opción reservar "todos los puestos"
      $result['listoptions'] = (string) View::make('calendario.allViews.optionsItems')->with(compact('items','addOptionReservarTodo','disabledAll'));
    }
    return $result;
  }

  /**
    * //Añade un nuevo recurso a la base de datos de cualquier tipo (puesto,equipo,espacio y tipoequipos)
    * // llamadas desde: admin\modalrecursos\add.blade.php
    * @param Input::get('nombre')      string
    * @param Input::get('descripcion') string
    * @param Input::get('tipo')        string
    * @param Input::get('id_lugar')    string
    * @param Input::get('grupo_id')    int
    * @param Input::get('espacio_id')  int  
    * @param Input::get('modo')        int (0|1)
    * @param Input::get('roles')       array
    *
    * @return $result                  array    
  */ 
  public function add(){
    
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    //Input
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|tipoequipos
    $grupo_id = Input::get('grupo_id',0);
    $espacio_id = Input::get('espacio_id',0); //!=0 si add puesto
    $tipoequipo_id = Input::get('tipoequipo_id',0);// !=0 si add equipo
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    
    //Validación de formulario   
    $rules = array( 'nombre'        => 'required|unique:recursos',
                    'tipo'          => 'required|in:'.implode(',',Config::get('options.recursos')),  
                    //'grupo_id'      => 'required_if:tipo,'.Config::get('options.espacio').'|exists:grupoRecursos,id',
                    'grupo_id'      => 'required_if:tipo,'.Config::get('options.espacio').','.Config::get('options.tipoequipos').'|exists:grupoRecursos,id',
                    'espacio_id'    => 'required_if:tipo,'.Config::get('options.puesto').'|exists:recursos,id',
                    'tipoequipo_id' => 'required_if:tipo,'.Config::get('options.equipo').'|exists:recursos,id',
                    'modo'          => 'required|in:'.implode(',',Config::get('options.modoGestion')),);

    $messages = array('required'                    => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'unique'                      => 'Existe un recurso con el mismo nombre...',
                      'tipo.in'                     => 'El tipo de recurso no está definido ..',
                      'modo.in'                     => 'Modo de Gestión de solicitudes de reserva no definido....',
                      'grupo_id.required_if'        => 'identificador de grupo requerido....',
                      'espacio_id.required_if'      => 'identificador de espacio requerido....',
                      'tipoequipo_id.required_if'   => 'identificador de modelo de equipos requerido....',
                      'grupo_id.exists'             => 'No existe identificador de grupo...',
                      'espacio_id.exists'           => 'No existe identificador de espacio...',
                      'tipoequipo_id.exists'        => 'No existe identificador de modelo de equipos...',);
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      //Si no hay errores en el formulario
      $data = array(  'nombre'        => $nombre,
                      'tipo'          => $tipo,
                      'grupo_id'      => $grupo_id,
                      'espacio_id'    => $espacio_id,
                      'tipoequipo_id' => $tipoequipo_id,
                      'descripcion'   => $descripcion,
                      'id_lugar'      => $id_lugar,
                      'acl'           => sgrACL::buildJsonAcl($modo,$roles),);
      $recurso = new Recurso;
      $sgrRecurso = RecursoFactory::getRecursoInstance($tipo);
      $sgrRecurso->setRecurso($recurso);
      $sgrRecurso->add($data);
      $sgrRecurso->save();
      
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
  public function edit(){
    //Input
    $id = Input::get('id','');
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|puesto|equipo
    $grupo_id = Input::get('grupo_id',0);
    $espacio_id = Input::get('espacio_id',0);
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    
    //Validación de formulario   
    $rules = array( 'id'          => 'required|exists:recursos',
                    'nombre'      => 'required|unique:recursos,nombre,'.Input::get('id'),
                    'tipo'        => 'required|in:'.implode(',',Config::get('options.tipoRecursos')),  
                    'grupo_id'    => 'required_if:tipo,'.Config::get('options.espacio').'|exists:grupoRecursos,id',
                    'espacio_id'  => 'required_if:tipo,'.Config::get('options.puesto').'|exists:recursos,id',
                    'modo'        => 'required|in:'.implode(',',Config::get('options.modoGestion')),);
     $messages = array( 'id.exists'               => 'Identificador de recurso no encontrado....',
                        'required'                => 'El campo <strong>:attribute</strong> es obligatorio....',
                        'unique'                  => 'Existe un recurso con el mismo nombre...',
                        'tipo.in'                 => 'El tipo de recurso no está definido...',
                        'modo.in'                 => 'Modo de Gestión de solicitudes de reserva no definido....',
                        'grupo_id.required_if'    => 'identificador de grupo requerido....',
                        'espacio_id.required_if'  => 'identificador de espacio requerido....',
                        'grupo_id.exists'         => 'No existe identificador de grupo...',
                        'espacio_id.exists'       => 'No existe identificador de espacio...',);
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      $data = array('nombre'      => $nombre,
                    'tipo'        => $tipo,
                    'grupo_id'    => $grupo_id,
                    'espacio_id'  => $espacio_id,
                    'descripcion' => $descripcion,
                    'id_lugar'    => $id_lugar,
                    'acl'         => sgrACL::buildJsonAcl($modo,$roles),);
      //$recurso = Recurso::find($id);
      $sgrRecurso = RecursoFactory::getRecursoInstance($tipo);
      $sgrRecurso->setRecurso(Recurso::find($id));
      $sgrRecurso->update($data);

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
                      'error'   => false,);
    //Validate
    $rules = array('idrecurso'  => 'required|exists:recursos,id',);
    $messages = array(  'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
                        'exists'    => 'No existe identificador de grupo...',);
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
                     'error'     => false,);
    //Validate
    $rules = array('idrecurso'  => 'required|exists:recursos,id',);
    $messages = array('required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
                      'exists'    => 'No existe identificador de recurso en BD.',);
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
                      'error'   => false,);
    //Validate
    $rules = array('idrecurso'  => 'required|exists:recursos,id',);

    $messages = array('required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
                      'exists'    => 'No existe identificador de recurso en BD.',);
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
    * @return string html checboxes 
  */
  public function recursosSinGrupo(){
    return View::make('admin.modalgrupos.recursosSinGrupo')->with('recursos',Recurso::where('grupo_id','=','0')->where('tipo','=',Config::get('options.espacio'))->orwhere('tipo','=',Config::get('options.tipoequipos'))->get());
  }

  /**
    * //checboxes html 
    * @param void
    * @return string html checkboxes
  */
  public function getpuestosSinEspacio(){
    return View::make('admin.html.checkboxesPuestos')->with('puestos',Recurso::where('espacio_id','=','0')->where('tipo','=',Config::get('options.puesto'))->get());
  }

  /**
    * @param Input::get('espacio_id') int indetificador de grupo
    * @param Input::get('idpuestos') array indentificadores de recursos añadir al grupo
    *
    * @return $result array(array,boleano,string)
  */
  //temporal
  public function addpuestoaespacio(){
    //Input
    $id = Input::get('espacio_id','');
    $idpuestos = Input::get('idpuestos',array());
    //Output 
    $result = array('errors' => array(),
                    'msg'    => '',    
                    'error'  => false,);
    //Validate
    $rules = array('espacio_id' => 'required|exists:recursos,id',);

    $messages = array('required' => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'exists'   => Config::get('msg.idnotfound'),);
    $validator = Validator::make(Input::all(), $rules, $messages);
      
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{
      foreach ($idpuestos as $idpuesto) {
        Recurso::find($idpuesto)->update(array('espacio_id'=>$id));
      }
      $result['msg'] = Config::get('msg.success');
    }
    return $result;
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
                     'error'         => false,);
    //Validate
    $rules = array('idrecurso'  => 'required|exists:recursos,id',);
    $messages = array('required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
                      'exists'    => 'No existe identificador de recurso en BD.',);

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

  /**
    * //Devuelve todos los espacios 
    * @param void
    * @return View::make('admin.html.optionEspacios')
  */
  public function htmlOptionEspacios(){
    $espacios = Recurso::where('tipo','=',Config::get('options.espacio'))->get();
    return View::make('admin.html.optionEspacios')->with(compact('espacios'));
  }

}