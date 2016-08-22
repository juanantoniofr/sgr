<?php
class recursosController extends BaseController{
  
   /** 
    * @param Input::get('sortby') string
    * @param Input::get('order')  string
    *
    * @return View::make('admin.recursos.list')  
  */
  /******* EOF *******/
  public function listado(){
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
           
    //Todos los grupos
    $grupos = GrupoRecurso::all();

    return View::make('admin.recursos.recursos',compact('grupos','sortby','order'));
    //return View::make('admin.recursos.list')->nest('table','admin.recursos.table',compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('modalAddGrupo','admin.modalgrupos.add')->nest('modalEditGrupo','admin.modalgrupos.edit')->nest('modalDelGrupo','admin.modalgrupos.del')->nest('modalAddRecurso','admin.modalrecursos.add',compact('grupos'))->nest('modalEditRecurso','admin.modalrecursos.edit',compact('grupos'))->nest('modalAddRecursosToGrupo','admin.modalgrupos.addRecurso')->nest('modalDelRecurso','admin.modalrecursos.del')->nest('modalEnabledRecurso','admin.modalrecursos.enabled')->nest('modalDisabledRecurso','admin.modalrecursos.disabled')->nest('modalAddPersona','admin.modalgrupos.addPersona')->nest('modalRemovePersona','admin.modalgrupos.removePersona')->nest('modalAddPuesto','admin.modalrecursos.addPuesto')->nest('modalEditPuesto','admin.modalrecursos.editPuesto')->nest('modalAddEquipo','admin.modalrecursos.addEquipo')->nest('modalAddPuestoExistente','admin.modalrecursos.addPuestoExistente')->nest('modalAddEquipoExistente','admin.modalrecursos.addEquipoExistente')->nest('modalEditEquipo','admin.modalrecursos.editEquipo');
  }


  /** 
    * @param Input::get('sortby') string
    * @param Input::get('order')  string
    *
    * @return View::make('admin.recursos.list')  
  */
  /******* EOF *******/
  public function listar(){
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
           
    //Todos los grupos
    $grupos = GrupoRecurso::all();


    return View::make('admin.recursos.list')->nest('table','admin.recursos.recursos',compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('modalAddGrupo','admin.modalgrupos.add')->nest('modalEditGrupo','admin.modalgrupos.edit')->nest('modalDelGrupo','admin.modalgrupos.del')->nest('modalAddRecurso','admin.modalrecursos.add',compact('grupos'))->nest('modalEditRecurso','admin.modalrecursos.edit',compact('grupos'))->nest('modalAddRecursosToGrupo','admin.modalgrupos.addRecurso')->nest('modalDelRecurso','admin.modalrecursos.del')->nest('modalEnabledRecurso','admin.modalrecursos.enabled')->nest('modalDisabledRecurso','admin.modalrecursos.disabled')->nest('modalAddPersona','admin.modalgrupos.addPersona')->nest('modalRemovePersona','admin.modalgrupos.removePersona')->nest('modalAddPuesto','admin.modalrecursos.addPuesto')->nest('modalEditPuesto','admin.modalrecursos.editPuesto')->nest('modalAddEquipo','admin.modalrecursos.addEquipo')->nest('modalAddPuestoExistente','admin.modalrecursos.addPuestoExistente')->nest('modalAddEquipoExistente','admin.modalrecursos.addEquipoExistente')->nest('modalEditEquipo','admin.modalrecursos.editEquipo');
  }

  /**
    * //Añade un nuevo recurso a la base de datos de cualquier tipo (puesto,equipo,espacio y tipoequipos)
    * // llamadas desde: admin\modalrecursos\add.blade.php
    * @param Input::get('nombre')         string
    * @param Input::get('descripcion')    string
    * @param Input::get('tipo')           string
    * @param Input::get('id_lugar')       string
    * @param Input::get('grupo_id')       int
    * @param Input::get('contenedor_id')  int  
    * @param Input::get('modo')           int (0|1)
    * @param Input::get('roles')          array
    *
    * @return $result                     array(boolean,string,array)    
  */ 
  /******* EOF *******/
  public function add(){
    
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    //Input
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|tipoequipos
    $grupo_id = Input::get('grupo_id',0);
    $contenedor_id = Input::get('contenedor_id','0');
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    
    //Validación de formulario   
    $rules = array( 'nombre'        => 'required|unique:recursos',
                    'tipo'          => 'required|in:'.implode(',',Config::get('options.recursos')),  
                    'grupo_id'      => 'required_if:tipo,'.Config::get('options.espacio').','.Config::get('options.tipoequipos').'|exists:grupoRecursos,id',
                    'contenedor_id'    => 'required_if:tipo,'.Config::get('options.puesto').','.Config::get('options.equipo'),
                    'modo'          => 'required|in:'.implode(',',Config::get('options.modoGestion')),);

    $messages = array('required'                    => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'unique'                      => 'Existe un recurso con el mismo nombre...',
                      'tipo.in'                     => 'El tipo de recurso no está definido ..',
                      'modo.in'                     => 'Modo de Gestión de solicitudes de reserva no definido....',
                      'grupo_id.required_if'        => 'identificador de grupo requerido....',
                      'contenedor_id.required_if'   => 'identificador de elemento contendor requerido....',
                      'grupo_id.exists'             => 'No existe identificador de grupo...',
                      'exist_contenedor'            => 'No existe identificador de elemento padre..',);
    
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($contenedor_id != 0){
      $validator->sometimes('exist_contenedor','',function($input){
        return Recurso::findOrFail($input['contenedor_id'])->count() > 0;
      });
    }

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
                      'contenedor_id' => $contenedor_id,
                      'descripcion'   => $descripcion,
                      'id_lugar'      => $id_lugar,
                      'acl'           => sgrACL::buildJsonAcl($modo,$roles),);
      //$recurso = new Recurso;
      $sgrRecurso = Factoria::getRecursoInstance(new Recurso);
      $sgrRecurso->setdatos($data);
      $sgrRecurso->save();
      
      $result['msg'] = Config::get('msg.success');
    }

    return $result;
  }

  /**
    * devuelve recurso dado su id (para modal admin.modalrecursos.edit)
    * 
    * @param Input::get('idrecurso') int
    *
    * @return $result object Recurso
  */
  /******* EOF *******/
  public function getrecurso(){
    //input
    $id = Input::get('idrecurso','');
    //Output 
    $result = array( 'errors'               => array(),
                     'msg'                  => '',    
                     'error'                => false,
                     'recurso'              => '',
                     'listadogrupos'        => '',
                     'listadocontenedores'  => '');
    //Validate
    $rules = array('idrecurso'      => 'required|exists:recursos,id',);
    $messages = array(  'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
                        'exists'    => 'No existe identificador de grupo...',);
    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{
      $recurso = Recurso::findOrFail($id);
      $result['recurso'] = $recurso->toArray();
      $grupos = GrupoRecurso::all();
      $result['listadogrupos'] = (string) View::make('admin.html.optionGrupos')->with(compact('grupos'));
      $recursosContenedores = Recurso::where('tipo','=',$recurso->contenedor->tipo)->get();
      $result['listadocontenedores'] = (string) View::make('admin.html.optionscontenedores')->with(compact('recursosContenedores'));
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
    $tipo =  Input::get('tipo'); //espacio|puesto|tipoequipo|equipo
    $grupo_id = Input::get('grupo_id',0);
    $contenedor_id = Input::get('contenedor_id','0');
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    //out
    $result = array('error'   => false,
                    'msg'     => '',
                    'errors'  => array());
    
    //Validación de formulario   
    $rules = array( 'id'              => 'required|exists:recursos',
                    'nombre'          => 'required|unique:recursos,nombre,'.Input::get('id'),
                    'tipo'            => 'required|in:'.implode(',',Config::get('options.recursos')),  
                    'grupo_id'        => 'required_if:tipo,'.Config::get('options.espacio').'|exists:grupoRecursos,id',
                    'contenedor_id'   => 'required_if:tipo,'.Config::get('options.puesto').','.Config::get('options.equipo'),
                    'modo'            => 'required|in:'.implode(',',Config::get('options.modoGestion')),);
     $messages = array( 'id.exists'               => 'Identificador de recurso no encontrado....',
                        'required'                => 'El campo <strong>:attribute</strong> es obligatorio....',
                        'unique'                  => 'Existe un recurso con el mismo nombre...',
                        'tipo.in'                 => 'El tipo de recurso no está definido...',
                        'modo.in'                 => 'Modo de Gestión de solicitudes de reserva no definido....',
                        'grupo_id.required_if'    => 'identificador de grupo requerido....',
                        'espacio_id.required_if'  => 'identificador de espacio requerido....',
                        'grupo_id.exists'         => 'No existe identificador de grupo...',
                        'grupo_id.sametypes'      => 'No coinciden los tipos de grupo y recurso...',
                        'exist_contenedor'        => 'No existe identificador de elemento padre..',
                        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    
    if ($contenedor_id != 0){
      $validator->sometimes('exist_contenedor','',function($input){
        return Recurso::findOrFail($input['contenedor_id'])->count() > 0;
      });
    }

    //En vez de chequear sería mejor cambiar?????
    //Controlar condición: debe coincidir el tipo del grupo con el tipo de recurso (grupos de tipo "espacio" deben agrupar recursos de tipo espacio (igual para grupos de tipo tiopequipos y recursos del tipo tipoequipos))
    if (0 != $grupo_id && 0 == $contenedor_id){
      $tiposachequear = array('tipogrupo' => GrupoRecurso::find($grupo_id)->tipo,'tiporecurso' => $tipo);
      $validator->sometimes(array('grupo_id','tipo'),'sametypes',function() use ($tiposachequear){
          return  $tiposachequear['tipogrupo'] != $tiposachequear['tiporecurso'];
      });
    }
    
    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();

    }
    else{  
      $data = array('nombre'        => $nombre,
                    'tipo'          => $tipo,
                    'grupo_id'      => $grupo_id,
                    'contenedor_id' => $contenedor_id,
                    'descripcion'   => $descripcion,
                    'id_lugar'      => $id_lugar,
                    'acl'           => sgrACL::buildJsonAcl($modo,$roles),
                    );
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      $sgrRecurso->setdatos($data);
      $sgrRecurso->save();
      //$sgrRecurso->updatetipoitems($data['tipo']);
      /*$sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
      $sgrRecurso->setRecurso($recurso);
      $sgrRecurso->update($data);*/

      $result['msg'] = Config::get('msg.success');
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
      $addOptionReservarTodo = false;
      if (count($items) > 0 && !Auth::user()->isUser()) $addOptionReservarTodo = true;
      //$addOptionReservarTodo = $recurso->usuariopuedereservartodoslospuestos(Auth::user()->id);
      
      //número de puestos or equipos disabled
      $numerodeitemsdisabled = 0;
      $disabledAll = 0;
      foreach ($items as $item) {
        if($item->disabled == '1') $numerodeitemsdisabled++;
      }
      if($numerodeitemsdisabled == $items->count()) $disabledAll = 1;
      
      //Añadir opción reservar "todos los puestos"
      $result['listoptions'] = (string) View::make('calendario.allViews.optionsItems')->with(compact('items','addOptionReservarTodo','disabledAll','numerodeitemsdisabled'));
      //$result['listoptions'] = "<pre>".var_dump($items)."</pre>";
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
    $rules    = array('idrecurso' => 'required|exists:recursos,id',);
    $messages = array('required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
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
    * //checboxes html 
    * @param void
    * @return string html checkboxes
  */
  public function getpuestosSinEspacio(){
    return View::make('admin.html.checkboxesItems')->with('items',Recurso::where('espacio_id','=','0')->where('tipo','=',Config::get('options.puesto'))->get());
  }

  /**
    * //checboxes html 
    * @param void
    * @return string html checkboxes
  */
  public function getequiposSinModelo(){
    return View::make('admin.html.checkboxesItems')->with('items',Recurso::where('tipoequipo_id','=','0')->where('tipo','=',Config::get('options.equipo'))->get());
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
    * //Devuelve todos los recurso del tipo Input::get('tipo') formateados como html options 
    * @param Input::get('tipo') string
    * @return View::make('admin.html.optionEspacios')
  */
  public function htmlOptionrecursos(){
    $tipoRecurso = Input::get('tipo','');
    $espacios = Recurso::where('tipo','=',$tipoRecurso)->get();
    return View::make('admin.html.optionEspacios')->with(compact('espacios'));
  }

}