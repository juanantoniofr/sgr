<?php
class recursosController extends BaseController{
  /* :) 5-1-2017 recursoscontroller */
  /** 
    * @param Input::get('sortby') string
    * @param Input::get('order')  string
    *
    * @return View::make('admin.recursos.list')  
  */
  public function listar(){ // :)
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
           
    //Todos los grupos
    $grupos = GrupoRecurso::all();
    foreach ($grupos as $grupo) {
      $sgrGrupos[] = new sgrGrupo($grupo);
    }
    
    $sgrUser = new sgrUser(Auth::user());
    $recursosSinGrupo = Recurso::where('grupo_id','=','0')->where('tipo','=','espacio')->orWhere('tipo','=','tipoequipos')->get();
    $itemsParaEspacios = Recurso::where('contenedor_id','=','0')->where('tipo','=','puesto')->get();
    $itemsParaTipoequipos = Recurso::where('contenedor_id','=','0')->where('tipo','=','equipo')->get();
    return View::make('admin.recursos.list')->nest('modalAddPuestoExistente','admin.modalrecursos.addPuestoExistente',compact('itemsParaEspacios'))->nest('modalAddEquipoExistente','admin.modalrecursos.addEquipoExistente',compact('itemsParaTipoequipos'))->nest('table','admin.recursos.recursos',compact('sgrGrupos','sortby','order'))->nest('modalAddRelacion','admin.modalrelaciones.addPersona')->nest( 'dropdown','admin.dropdown',compact('sgrUser') )->nest('modalAddGrupo','admin.modalgrupos.add')->nest('modalEditGrupo','admin.modalgrupos.edit')->nest('modalDelGrupo','admin.modalgrupos.del')->nest('modalAddRecursosToGrupo','admin.modalgrupos.addRecursoExistente',compact('recursosSinGrupo'))->nest('modalEditRecurso','admin.modalrecursos.edit',compact('grupos'))->nest('modalDelRecurso','admin.modalrecursos.del')->nest('modalEnabledRecurso','admin.modalrecursos.enabled')->nest('modalDisabledRecurso','admin.modalrecursos.disabled')->nest('modalRemoveRelacion','admin.modalrelaciones.removePersona')->nest('modalAddRecurso','admin.modalrecursos.add',compact('grupos'))->nest('modalAlert','modalAlert');
  }
  /**
    * // Añade recursos (puesto/equipo) existentes sin asignado recurso contenedor asignado
    *
    * @param Input::get('grupo_id') int indetificador de grupo
    * @param Input::get('idrecursos') array indentificadores de recursos añadir al grupo
    *
    * @return $result array(boleano,string)
  */
  public function ajaxAddItemExistente(){ // :) 
    //Input
    $id = Input::get('contenedor_id','');
    $idrecursos = Input::get('idrecursos',array());
    //Output 
    $result = array('errors' => array(),
                    'msg'    => '',    
                    'error'  => false,);
    //Validate
    $rules = array( 'contenedor_id'  => 'required',);
    
    $messages = array('required'              => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'exists'                => Config::get('msg.idnotfound'),
                      );
    
    $validator = Validator::make(Input::all(), $rules, $messages);
        
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{
    
      foreach ($idrecursos as $idrecurso) {
        //Recurso::find($idrecurso)->update(array('contenedor_id'=>$id));
        $sgrRecurso = Factoria::getRecursoInstance(Recurso::find($idrecurso));
        $data = array('contenedor_id' => $id);
        $sgrRecurso->setdatos($data);
        $sgrRecurso->save();
        $sgrRecurso->attach_administrador(Auth::user()->id);
      }
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    }
    return $result;
  }
  /**
    * //Añade un nuevo recurso a la base de datos de cualquier tipo (puesto,equipo,espacio y tipoequipos)
    * // llamadas desde: admin\modalrecursos\add.blade.php
    * @param Input::get('nombre')         string
    * @param Input::get('descripcion')    string
    * @param Input::get('tipo')           string
    * @param Input::get('id_lugar')       string
    * @param Input::get('contenedor_id')  int  
    * @param Input::get('modo')           int (0|1)
    * @param Input::get('roles')          array
    *
    * @return $result                     array(boolean,string,array)    
  */ 
  public function AjaxAddNuevoRecurso(){ // :)
    
    //out
    $result = array('error' => false,
                    'msg'   => '',
                    'errors' => array());
    
    //Input
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|tipoequipos
    $contenedor_id = Input::get('contenedor_id','0');
    $modo = Input::get('modo'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    $tipopadre = Input::get('tipopadre',''); //El elemento padre o contenedor de un recurso puede ser un grupo u otro recurso
    
    //Validación de formulario   
    $rules = array( 'nombre'            => 'required|unique:recursos,nombre,NULL,id,deleted_at,NULL',
                    'tipo'              => 'required|in:'.implode(',',Config::get('options.recursos')),  
                    'contenedor_id'     => 'required',
                    'modo'              => 'required|in:'.implode(',',Config::get('options.modoGestion')),);
    $messages = array('required'              => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'nombre.unique'         => 'Existe un recurso con el mismo nombre...',
                      'tipo.in'               => 'El tipo de recurso no está definido ..',
                      'modo.in'               => 'Modo de Gestión de solicitudes de reserva no definido....',
                      'contenedor_idexists'   => 'Elemento contenedor no encontrado en BD.',);
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    $validator->sometimes('contenedor_idexists','',function($input){
        if ($input['tipoPadre'] == 'recurso') 
          return Recurso::findOrFail($input['contenedor_id'])->count() > 0;
        if ($input['tipoPadre'] == 'grupo') 
          return GrupoRecurso::findOrFail($input['contenedor_id'])->count() > 0;
      });
    
    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      
      if ($tipopadre == 'recurso'){
        $grupo_id = 0;
        $contenedor_id = $contenedor_id;
      }
      else if ($tipopadre == 'grupo'){
        $grupo_id = $contenedor_id;
        $contenedor_id = 0;
      }
      //Si no hay errores en el formulario
      $data = array(  'nombre'        => $nombre,
                      'tipo'          => $tipo,
                      'grupo_id'      => $grupo_id,
                      'contenedor_id' => $contenedor_id,
                      'descripcion'   => $descripcion,
                      'id_lugar'      => $id_lugar,
                      'acl'           => sgrACL::buildJsonAcl($modo,$roles),);
      
      $sgrRecurso = Factoria::getRecursoInstance(new Recurso);
      $sgrRecurso->setdatos($data);
      $sgrRecurso->save();
      $sgrRecurso->attach_administrador(Auth::user()->id);
      //$sgrRecurso->save();
      
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
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
  public function ajaxGetDatosRecurso(){ // :)
    //input
    $id = Input::get('id','');
    //Output 
    $result = array( 'errors'               => array(),
                     'msg'                  => '',    
                     'error'                => false,
                     'recurso'              => '',
                     'listadocontenedores'  => array(),);
    //Validate
    $rules = array('id'      => 'required|exists:recursos,id',);
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
      
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      
      $itemsContenedores = $sgrRecurso->getContenedores();
      $result['msg'] = $id;
      $result['listadocontenedores'] = (string) View::make('admin.html.optionscontenedores')->with(compact('itemsContenedores'));
      
     } 
    return $result;
  }
  
  /**
    * @param Input::get('nombre')      string
    * @param Input::get('descripcion') string
    * @param Input::get('tipo')        string
    * @param Input::get('id_lugar')    string
    * @param Input::get('padre_id')    int 
    * @param Input::get('modo')        int (0|1)
    * @param Input::get('roles')       array
    *
    * @return $result                  array    
  */ 
  public function ajaxEditRecurso(){ // :)
    //Input
    $id = Input::get('id','');
    $nombre = Input::get('nombre');
    $tipo =  Input::get('tipo'); //espacio|puesto|tipoequipo|equipo
    $padre_id = Input::get('padre_id','');
    $modo = Input::get('modo','1'); //0=gestión con validación, 1=gestión sin validación
    $descripcion = Input::get('descripcion','');
    $id_lugar = Input::get('id_lugar','');
    $roles = Input::get('roles'); //roles con acceso para poder reservar (array())
    //out
    $result = array('error'   => false,
                    'msg'     => '',
                    'errors'  => array());
   
    //Validación de formulario   
    $rules = array( 'id'              => 'required|exists:recursos',
                    'nombre'          => 'required|unique:recursos,nombre,'.Input::get('id').',id,deleted_at,NULL',
                    'tipo'            => 'required|in:'.implode(',',Config::get('options.recursos')),  
                    'padre_id'        => 'required',
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
                        'existsContenedor'        => 'No existe identificador de elemento padre..',
                        );
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    $datos = array('padre_id' => $padre_id,'tipo' => $tipo);
    $validator->sometimes('existsContenedor', '', function( $datos )
      {
        if (in_array($datos['tipo'], Config::get('options.recursosContenedores'))) return GrupoRecurso::where('id','=',$datos['padre_id'])->get() != null;
        if (in_array($datos['tipo'], Config::get('options.recursosItems')))        return Recurso::where('id','=',$datos['padre_id'])->get() != null;
      });
    
    if ($validator->fails()){
      //Si errores en el formulario
      $result['error'] = true;
      $result['errors'] = $validator->errors()->toArray();
    }
    else{  
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      $sgrRecurso->edit(Input::all());
      $sgrRecurso->save();
      
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.editrecursosuccess') ));
    }
    return $result;
  }
  /**
    * @param Input::get('idrecurso') int
    *
    * @return $result array(boolean|string) 
  */
  public function ajaxDelRecurso(){ // :)
    
    //input
    $id = Input::get('idrecurso','');
    //Output 
    $result = array( 'errors'   => array(),
                      'msg'     => '',    
                      'error'   => false,);
    //Validate
    $rules    = array('idrecurso' => 'required|exists:recursos,id',);
    $messages = array('required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'exists'    => 'No existe identificador de grupo...',);
    $validator = Validator::make(Input::all(), $rules, $messages);
    //$validator->sometime --> no tiene eventos futuros
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
    }
    else{
      //Softdelete recurso y eventos
      $recurso = Recurso::findOrFail($id);
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      $sgrRecurso->detach_all();//Elimina relaciones recurso-user (gestor/administrador/validador).
      $sgrRecurso->delEventos();//Elimina todos los eventos (si hay).
      $sgrRecurso->del();//elimina el recurso.
      
      //Enviar mail a usuarios con reserva futuras
      
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.delrecursosuccess')));
    }
    
    return $result;
  }
 /**
    * //Deshabilita un recursos para su reserva (actúa en casacada)
    *
    * @param Input::get('idrecurso') int
    * @param Input::get('motivo') string
    *
    * @return $result array
  */
  public function AjaxDisabled(){ // :)
 
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
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      $sgrRecurso->disabled($motivo);
    
      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaDeshabilitaRecurso($id,$motivo);
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.disabledrecursosuccess')));
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
  public function AjaxEnabled(){ // :/
 
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
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
      $sgrRecurso->enabled();
      //Enviar mail a usuarios con reserva futuras
      $sgrMail = new sgrMail();
      $sgrMail->notificaHabilitaRecurso($id);
     
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.enabledrecursosuccess')));
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
  public function getitems(){// 
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
      $sgrRecurso = Factoria::getRecursoInstance($recurso);
    
      //se filtran para obtener sólo aquellos visibles o atendidos para el usuario logeado
      $items = $sgrRecurso->items();
      $addOptionReservarTodo = false;
      if (count($items) > 0 && !Auth::user()->isUser()) $addOptionReservarTodo = true;
      //$addOptionReservarTodo = $recurso->usuariopuedereservartodoslospuestos(Auth::user()->id);
      
      //número de puestos or equipos disabled
      $numerodeitemsdisabled = 0;
      $disabledAll = 0;
      foreach ($items as $item) {
        if($item->isDisabled() == '1') $numerodeitemsdisabled++;
      }
      if($numerodeitemsdisabled == count($items)) $disabledAll = 1;
      
      $disabledAll = $sgrRecurso->isDisabled();
      //Añadir opción reservar "todos los puestos"
      $result['listoptions'] = (string) View::make('calendario.allViews.optionsItems')->with(compact('items','addOptionReservarTodo','disabledAll','numerodeitemsdisabled'));
      }
    return $result;
  }
  
  
 
  
  /**
    * //Devuelve el campo descripción dado un idrecurso
    * @param Input::get('idrecurso','') int identificador de recurso
    *
    * @return $descripcion string
  */
  public function getDescripcion(){ // precindible ??
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
  public function htmlOptionrecursos(){ // prescindible ???
    $tipoRecurso = Input::get('tipo','');
    $espacios = Recurso::where('tipo','=',$tipoRecurso)->get();
    return View::make('admin.html.optionEspacios')->with(compact('espacios'));
  }
}