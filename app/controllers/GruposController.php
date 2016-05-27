<?php

class GruposController extends BaseController {

  /**
    * @param Input::get('sortby') string
    * @param Input::get('order')  string
    *
    * @return View::make('admin.recursos.list')  
  */
  public function listar(){
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');
           
    //Todos los grupos
    $grupos = GrupoRecurso::all();


    return View::make('admin.recursos.list')->nest('table','admin.recursos.table',compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('modalAddGrupo','admin.modalgrupos.add')->nest('modalEditGrupo','admin.modalgrupos.edit')->nest('modalDelGrupo','admin.modalgrupos.del')->nest('modalAddRecurso','admin.modalrecursos.add',compact('grupos'))->nest('modalEditRecurso','admin.modalrecursos.edit',compact('grupos'))->nest('modalAddRecursosToGrupo','admin.modalgrupos.addRecurso')->nest('modalDelRecurso','admin.modalrecursos.del')->nest('modalEnabledRecurso','admin.modalrecursos.enabled')->nest('modalDisabledRecurso','admin.modalrecursos.disabled')->nest('modalAddPersona','admin.modalgrupos.addPersona')->nest('modalRemovePersona','admin.modalgrupos.removePersona')->nest('modalAddPuesto','admin.modalrecursos.addPuesto')->nest('modalEditPuesto','admin.modalrecursos.editPuesto')->nest('modalAddEquipo','admin.modalrecursos.addEquipo')->nest('modalAddPuestoExistente','admin.modalrecursos.addPuestoExistente')->nest('modalAddEquipoExistente','admin.modalrecursos.addEquipoExistente')->nest('modalEditEquipo','admin.modalrecursos.editEquipo');
  }


  /**
    * @param Input::get('idgrupo') int indetificador de grupo
    * @param Input::get('tipogrupo') string tipo de recursos para el grupo 
    * 
    * @return View::make string
  */
  public function recursosSinGrupo(){
    //Input
    $idgrupo    = Input::get('idgrupo','');
    $tipogrupo  = Input::get('tipogrupo','');
    //Output 
    $result = array('errors' => array(),
                    'html'   => '',    
                    'error'  => false,);

    //Validate
    $rules = array( 'idgrupo'   => 'required|exists:grupoRecursos,id',
                    'tipogrupo' => 'required|in:'.implode(',',Config::get('options.tipoGrupos')),);

    $messages = array('required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'exists'        => Config::get('msg.idnotfound'),
                      'tipogrupo.in'  => 'El tipo de grupo no está definido...',);
    
    $validator = Validator::make(Input::all(), $rules, $messages);
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{
      $result['html'] = (string) View::make('admin.html.checkboxesItems')->with('items',Recurso::where('grupo_id','=','0')->where('tipo','=',$tipogrupo)->get());
    }
    return $result;
  }
  
  /**
    * // Añade recursos existentes sin grupo asisagnado a un grupo
    *
    * @param Input::get('grupo_id') int indetificador de grupo
    * @param Input::get('idrecursos') array indentificadores de recursos añadir al grupo
    *
    * @return $result array(boleano,string)
  */
  
  public function addrecursos(){
    //Input
    $id = Input::get('grupo_id','');
    $idrecursos = Input::get('iditems',array());
    //Output 
    $result = array('errors' => array(),
                    'msg'    => '',    
                    'error'  => false,);
    //Validate
    $rules = array( 'grupo_id'  => 'required|exists:grupoRecursos,id',);

    $messages = array('required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
                      'exists'        => Config::get('msg.idnotfound'),);
    $validator = Validator::make(Input::all(), $rules, $messages);
      
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{
      foreach ($idrecursos as $idrecurso) {
        Recurso::find($idrecurso)->update(array('grupo_id'=>$id));
      }
      $result['msg'] = Config::get('msg.success');
    }
    return $result;
  }

  /**
    * //devuelve array con los nombres de los grupos con algún recurso visible para su reserva para el usuario con identificador igual a $id
    * 
    * @param $id int
    *
    * @return $grupos Object GrupoRecursos
    * 
  */
  static public function gruposVisibles(){
  
    $grupos = GrupoRecurso::all()->filter(function($grupo){
      $recursos = $grupo->recursos->filter(function($recurso){
            return $recurso->visible();
        }); 
      if ($recursos->count() > 0 ) return true;
    });
    return $grupos;
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
    $htmloptionsrecursos = '';
        
    if(!empty($id)){
      $grupo = GrupoRecurso::findOrFail($id);
      
      //se filtran para obtener sólo aquellos visibles 
      $recursos = $grupo->recursos->filter(function($recurso){
          $sgrRecurso = RecursoFactory::getRecursoInstance($recurso->tipo);
          $sgrRecurso->setRecurso($recurso);
          return $sgrRecurso->visible(); });
      $htmloptionsrecursos = (string ) View::make('calendario.allViews.optionsRecursos')->with(compact('recursos'));
    }

    return $htmloptionsrecursos;
  }
	
  /**
		*	@param Input::get('grupo_id')		int
		*
		*	@return $result array
	*/
	public function del(){
    //Input
    $id = Input::get('grupo_id','');
    
    //Output 
    $respuesta = array( 'errors' => array(),
    						        'msg'		=> '',		
                      	'error' 	=> false,);
   	//Validate
    $rules = array('grupo_id'	=> 'required|exists:grupoRecursos,id',);

    $messages = array(  'required'	=> 'El campo <strong>:attribute</strong> es obligatorio....',
          	            'exists'		=> 'No existe identificador de grupo...',);
    $validator = Validator::make(Input::all(), $rules, $messages);
    	
    //Save Input or return error
    if ($validator->fails()){
     	$result['errors'] = $validator->errors()->toArray();
     	$result['error'] = true;
    }
    else{
      $grupo = GrupoRecurso::findOrFail($id);
      $supervisores = $grupo->supervisores();
      foreach ($supervisores as $supervisor) {
        $supervisor->detach($supervisor->id);
      }
      $validadores = $grupo->validadores();
      foreach ($validadores as $validador) {
        $validador->detach($validador->id);
      }
      $tecnicos = $grupo->tecnicos();
      foreach ($tecnicos as $tecnico) {
        $tecnico->detach($tecnico->id);
      }

      $grupo->delete();
      $result['msg'] = Config::get('msg.actionSuccess');
    }
    
		return $result;
  }

	/**
		*
		*	@param Input::get('grupo_id')		int
		*	@param Input::get('nombre')			string
		*	@param Input::get('descripcion')	string 
		*
		*	@return $result array
	*/
	public function edit(){
    //Input
    $id 	       = Input::get('grupo_id','');
    $nombre 	   = Input::get('nombre','');
    $tipo        = Input::get('tipo');
    $descripcion = Input::get('descripcion','');
 
    //Output 
    $respuesta = array(	'errors'   	=> array(),
    					          'msg'		=> '',		
                     		'error' 	=> false,
                     		);
   	//Validate
    $rules = array(
    	  'grupo_id'	=> 'required|exists:grupoRecursos,id',
       	'nombre'    => 'required|unique:grupoRecursos,nombre,'.$id,
        'tipo'      => 'required|in:'.implode(',',Config::get('options.tipoGrupos')), 
      );

    $messages = array(
       	'required'	=> 'El campo <strong>:attribute</strong> es obligatorio....',
        'exists'		=> 'No existe identificador de grupo...',	
        'in'        => 'El valor del campo tipo no está definido...',
      );
    $validator = Validator::make(Input::all(), $rules, $messages);
    	
    //Save Input or return error
    if ($validator->fails()){
     	$result['errors'] = $validator->errors()->toArray();
     	$result['error'] = true;
    }
    else{  
     	$grupo = GrupoRecurso::findOrFail($id)->update(array('nombre' => $nombre,'descripcion' => $descripcion, 'tipo' => $tipo));
     	$result['msg'] = Config::get('msg.success');
    }
   	return $result;
  }

	/**
		*
		*	@param Input::get('nombre')			string
		*	@param Input::get('descripcion')	string
		*
		*	@return $result array
	*/
	public function add(){
		//out
		$result = array('errors'  => array(),
						        'msg'		  => '',
						        'error'		=> false,);
		//validate
		$rules = array( 'nombre' => 'required|unique:grupoRecursos',
                    'tipo'   => 'required|in:'.implode(',',Config::get('options.tipoGrupos')),);
   	$messages = array(  'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
        	              'unique'        => 'Existe un <b>grupo</b> con el mismo nombre....',
                        'in'            => 'El valor especificado en tipo no está permitido....',);
    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()){
    	$result['error'] = true;
     	$result['errors'] = $validator->errors()->toArray();
    }
    else{
    	//Salvar el nuevo grupo
    	$grupo = new GrupoRecurso;
		  $grupo->nombre = Input::get('nombre','');
			$grupo->descripcion = Input::get('descripcion','');
      $grupo->tipo = Input::get('tipo');
			$grupo->save();

      //El propio usuario que crea el grupo es supervisor del mismo
      $grupo->supervisores()->attach(Auth::user()->id); 
      $result['msg'] = Config::get('msg.success') . Input::get('tipo');	
    }

		return $result;
	}

  /**
  	* Ajax function: devuelve la lista de grupos en forma de tabla
  	*
  	* @param Input::get('sortby')	string
  	* @param Input::get('order')	string
  	*
  	* @return View::make('admin.recursos.table)  
  */
	public function getTable(){
    	
    	//Input      
	    $sortby = Input::get('sortby','nombre');
	    $order = Input::get('order','asc');

	    //Todos los grupos
	    $grupos = GrupoRecurso::all();
	    return View::make('admin.recursos.table',compact('grupos','sortby','order'));
  	}
 
  /**
  * //Establece la relación persona-grupoRecursos (supervisor-validador-tecnico)
  *
  * @param Input::get('idgrupo')    int
  * @param Input::get('username')   string
  * @param Input::get('rol')        string
  *
  * @return $result array
  * 
  */
  public function addPersona(){
    
    //input
    $idgrupo   = Input::get('idgrupo','');
    $username  = Input::get('username','');
    $rol       = Input::get('rol','');      
    
    
    //Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
    //Validate
    $rules = array(
        'idgrupo'    => 'required|exists:grupoRecursos,id', //exists:table,column
        'username'   => 'required|exists:users,username',
        'rol'        => 'required|in:1,2,3'
        );

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idgrupo.exists'      => 'No existe identificador de grupo en BD.',
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
      $grupo = grupoRecurso::find($idgrupo);
      $user = User::where('username','=',$username)->first();
      $idUser = $user->id;
      
      switch ($rol) {
        //tecnicos
        case '1':
          $tecnicos = $grupo->tecnicos;
          if ($tecnicos->contains($idUser)){
            $result['error'] = true;
            $result['errors']['tecnico'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este recurso.';
            return $result;
          }
          $grupo->tecnicos()->attach($idUser);
          break;
        
        //Supervisor
        case '2':
          $supervisores = $grupo->supervisores;
          if ($supervisores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['supervisor'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>supervisor</b></i> de este recurso.';
            return $result;
          }
          $grupo->supervisores()->attach($idUser);
          break;
      
        //Validador
        case '3':
          $validadores = $grupo->validadores;
          if ($validadores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['validador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este recurso.';
            return $respuesta;
          }
          $grupo->validadores()->attach($idUser);
          $result['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
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
    * //elimina la relación grupoRecursos-persona
    *
    * @param Input::get('idgrupo') int
    * @param Input::get('supervisores_id) array
    * @param Input::get('validadores_id') array
    * @param Input::get('tecnicos_id') array
    *
    * @return $result array    
    *
  */
  public function removePersonas(){
    
    //input
    $idgrupo                  = Input::get('idgrupo','');
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
        'idgrupo'  => 'required|exists:grupoRecursos,id', //exists:table,column
        );

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idgrupo.exists'      => 'No existe identificador de recurso en BD.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
      return $result;
    }
    else {
      $grupo = grupoRecurso::findOrFail($idgrupo);
      foreach ($detachSupervisores as $idSupervisor) {
        $grupo->supervisores()->detach($idSupervisor);
      }
      foreach ($detachValidadores as $idValidador) {
        $grupo->validadores()->detach($idValidador);
      }
      foreach ($detachTecnicos as $idTecnico) {
        $grupo->tecnicos()->detach($idTecnico);
      }
      $result['msg'] = Config::get('msg.success');
    }

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
    $id = Input::get('idgrupo','');

    //Output
    $result = array( 'errors'                 => array(),
                     'error'                 => false,
                     'htmlCheckboxPersonas'  => '',
                    );
    
    //Validate
    $rules = array(
        'idgrupo'  => 'required|exists:grupoRecursos,id', //exists:table,column
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
      $grupo = grupoRecurso::findOrFail($id);
      $result['htmlCheckboxPersonas'] = (string) View::make('admin.html.checkboxPersonas')->with(compact('grupo'));
    }
      
    return $result;  
  }

  /**
    * //Devuelve todos los grupos
    * @param void
    * @return View::make('admin.html.optionGrupos')
  */
  public function htmlOptionGrupos(){

    return View::make('admin.html.optionGrupos')->with('grupos',grupoRecurso::all());
  }

}