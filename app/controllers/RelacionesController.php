<?php
/* marca branch master2 */
class RelacionesController extends BaseController {

	/**
    * //Establece la relación users-grupoRecursos || users-recursos(pendiente) (supervisor-validador-tecnico)
    *
    * @param Input::get('id')    int
    * @param $tipo string en Config::get('options.objectWithRelation')
    * @param Input::get('username')   string
    * @param Input::get('rol')        string
    *
    * @return $result array
    * 
  */
  public function ajaxAddRelacion(){ // :)
    
    //input
    $id 		   = Input::get('id','');
    $username  = Input::get('username','');
    $rol       = Input::get('rol','');
    $tipo      = Input::get('tipo','');       
    
    
    //Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
    //Validate
    $rules = array(
        'id'    			=> 'required',//|exists:grupoRecursos,id', //exists:table,column
        'username'   	=> 'required|exists:users,username',
        'rol'        	=> 'required|in:1,2,3',
				'tipo'				=> 'required|in:'.Config::get('options.objectWithRelation'),

        );

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'tipo.in'							=> 'Tipo de objeto no reconocido',
          'username.exists'     => 'No existe usuario en la BD.',
          'rol.in'              => 'El campo <strong>:attribute</strong> no coincide con ninguno de los valores aceptados.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
      $user = User::where('username','=',$username)->first();
    	$idUser = $user->id;
    	if ($tipo == 'grupo')
    		$result = $this->addRelacionConGrupo($id,$idUser,$username,$rol);
    	elseif ($tipo == 'recurso')
    		$result = $this->addRelacionConRecurso($id,$idUser,$username,$rol);

    }//fin else

    $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    return $result;
  }


  public function addRelacionConRecurso($idrecurso,$iduser,$username,$rol){
  	//Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
  	$recurso = Recurso::findOrFail($idrecurso);
    
    switch ($rol) {
    	//gestores
      case '1':
        $gestores = $recurso->gestores;
        if ($gestores->contains($iduser)){
          $result['error'] = true;
          $result['errors']['gestor'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este recuso.';
          return $result;
        }
        $sgrRecurso = Factoria::getRecursoInstance($recurso);
        $sgrRecurso->attach_gestor($iduser);
        break;
        
      //Supervisor
      case '2':
      	$administradores = $recurso->administradores;
        if ($administradores->contains($iduser)){
          $result['error'] = true;
          $result['errors']['administrador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>administrador</b></i> de este Recurso.';
          return $result;
        }
        $sgrRecurso = Factoria::getRecursoInstance($recurso);
        $sgrRecurso->attach_administrador($iduser);
        break;
      
      //Validador
      case '3':
        $validadores = $recurso->validadores;
        if ($validadores->contains($iduser)){
          $result['error'] = true;
          $result['errors']['validador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este recurso.';
          return $result;
        }
        $sgrRecurso = Factoria::getRecursoInstance($recurso);
        $sgrRecurso->attach_validador($iduser);
        $result['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
       	break;
      
      default:
        $result['error'] = false;
        $result['msg'] = 'Identificador de rol no esperado: ' . $rol;
      break;
    }//fin case
    return $result;
  }


  public function addRelacionConGrupo($idgrupo,$iduser,$username,$rol){
  	//Output 
    $result = array( 'errors'    => array(),
                      'msg'   => '',    
                      'error'   => false,
                    );
  	$grupo = grupoRecurso::find($idgrupo);
    
    switch ($rol) {
    	//gestores
      case '1':
          $gestores = $grupo->gestores;
          if ($gestores->contains($iduser)){
            $result['error'] = true;
            $result['errors']['gestor'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este Grupo.';
            return $result;
          }
          $grupo->gestores()->attach($iduser);
          $recursos = $grupo->recursos->each(function($recurso) use ($iduser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_gestor($iduser);
          });
          break;
        
        //Supervisor
        case '2':
          $administradores = $grupo->administradores;
          if ($administradores->contains($iduser)){
            $result['error'] = true;
            $result['errors']['administrador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>administrador</b></i> de este Grupo.';
            return $result;
          }
          $grupo->administradores()->attach($iduser);
          $recursos = $grupo->recursos->each(function($recurso) use ($iduser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_administrador($iduser);
          });
          break;
      
        //Validador
        case '3':
          $validadores = $grupo->validadores;
          if ($validadores->contains($iduser)){
            $result['error'] = true;
            $result['errors']['validador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este Grupo.';
            return $result;
          }
          $grupo->validadores()->attach($iduser);
          $recursos = $grupo->recursos->each(function($recurso) use ($iduser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_validador($iduser);
          });
          $result['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
          break;
      
        default:
          $result['error'] = false;
          $result['msg'] = 'Identificador de rol no esperado: ' . $rol;
        break;
      }//fin case
      return $result;
  }

  /**
  	*
  	* //devuelve array objetc User que son gestores de grupo || recurso con identificador $id
  	* @param $id int identificador de grupo || recurso
  	* @param $tipo string en Config::get('options.objectWithRelation')
  	* @return array()
  **/
  public function ajaxGetGestores(){//:/
  	//input
    $id   = Input::get('id','');
    $tipo 	= Input::get('tipo','');
    
    //Output 
    $result = array( 'errors'   	 => array(),
                     'gestores'   => '',    
                     'error'   	=> false,
                    );
    //Validate
    $rules = array( 'id'    => 'required',//|exists:grupoRecursos,id', //exists:table,column
    								'tipo'	=> 'required|in:'.Config::get('options.objectWithRelation'),
        						);

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'in'				=> 'Tipo de objeto no reconocido',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	if ($tipo == 'grupo')
    		$result['gestores'] = grupoRecurso::find($id)->gestores->toArray();
    	elseif ($tipo == 'recurso')
    		$result['gestores'] = Recurso::find($id)->gestores->toArray();
    }
    return $result;	
  }	

  /**
  	*
  	* //devuelve array objetc User que son administradores del grupo || recurso con identificador $id
  	* @param $id int identificador de grupo || recurso
  	* @param $tipo string en Config::get('options.objectWithRelation')
  	* @return array()
  **/
  public function ajaxGetAdministradores(){ // :)
  	//input
    $id   = Input::get('id','');
    $tipo = Input::get('tipo','');
    
    //Output 
    $result = array( 'errors'   	 				=> array(),
                     'administradores'   	=> '',    
                     'error'   						=> false,                    
                    );
    //Validate
    $rules = array( 'id'    => 'required',//|exists:grupoRecursos,id', //exists:table,column
        						'tipo'	=> 'required|in:'.Config::get('options.objectWithRelation'),
        					);

    $messages = array(	'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          							'in'				=> 'Tipo de objeto no reconocido',
          					);

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	if ($tipo == 'grupo')
    		$result['administradores'] = grupoRecurso::find($id)->administradores->toArray();
    	elseif ($tipo == 'recurso')
    		$result['administradores'] = Recurso::find($id)->administradores->toArray();
    }
    
    return $result;	
  }	

  /**
  	*
  	* //devuelve array objetc User que son validadores del grupo || recurso con identificador $id
  	* @param $idgrupo int identificador de grupo || recurso
  	* @param $tipo string en Config::get('options.objectWithRelation')
  	* @return array()
  **/
  public function ajaxGetValidadores(){//:)
  	//input
    $id 	= Input::get('id','');
    $tipo = Input::get('tipo','');
    
    //Output 
    $result = array( 'errors'   		 => array(),
                     'validadores'   => '',    
                     'error'   			=> false,
                    );
    //Validate
    $rules = array( 'id'    => 'required',//|exists:grupoRecursos,id', //exists:table,column
        						'tipo'	=> 'required|in:'.Config::get('options.objectWithRelation'),
        					);

    $messages = array(	'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          							'in'				=> 'Tipo de objeto no reconocido',
          					);

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	if ($tipo == 'grupo')
    		$result['validadores'] = grupoRecurso::find($id)->validadores->toArray();
    	elseif ($tipo == 'recurso')
    		$result['validadores'] = Recurso::find($id)->validadores->toArray();
    }
    return $result;	
  }	
  
  /**
    * //elimina la relación grupoRecursos-persona || recurso-persona
    *
    * @param Input::get('id') int identificador de grupo || recurso
    * @param Input::get('administrador_id) array
    * @param Input::get('validadores_id') array
    * @param Input::get('tecnicos_id') array
    *
    * @return $result array    
    *
  */
  public function ajaxRemoveRelacion(){ // :)
    
    //input
    $id      				 = Input::get('id','');
    $tipo						 = Input::get('tipo','');
    $administradores = Input::get('administradores_id',array());
    $validadores     = Input::get('validadores_id',array());
    $gestores        = Input::get('gestores_id',array());
    
    //Output 
    $result = array( 	'errors'    	=> array(),
                      'msg'   		=> '',    
                      'error'   	=> false,
                      'test'			=> $id,
                    );

    //Validate
    $rules = array( 'id'    => 'required',//|exists:grupoRecursos,id', //exists:table,column
        						'tipo'	=> 'required|in:'.Config::get('options.objectWithRelation'),
        					);

    $messages = array(	'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          							'in'				=> 'Tipo de objeto no reconocido',
          					);

    $validator = Validator::make(Input::all(), $rules, $messages);

    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
      return $result;
    }
    else {

    	if ($tipo == 'grupo')
    		$this->removeRelacionConGrupo($id,$administradores,$validadores,$gestores);//$result['validadores'] = grupoRecurso::find($id)->validadores->toArray();
    	elseif ($tipo == 'recurso')
    		$this->removeRelacionConRecurso($id,$administradores,$validadores,$gestores);//$result['validadores'] = Recurso::find($id)->validadores->toArray();

      
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    }

    return $result;
  }

  public function removeRelacionConGrupo($id,$administradores,$validadores,$gestores){
  	$grupo = grupoRecurso::findOrFail($id);
    
    foreach ($administradores as $administrador) {
      $grupo->administradores()->detach($administrador);
      $recursos = $grupo->recursos->each(function($recurso) use ($administrador) {
       	$sgrRecurso = Factoria::getRecursoInstance($recurso);
       	$sgrRecurso->detach_administrador($administrador);
      });
    }
    foreach ($validadores as $validador) {
      $grupo->validadores()->detach($validador);
      $recursos = $grupo->recursos->each(function($recurso) use ($validador) {
    	 	$sgrRecurso = Factoria::getRecursoInstance($recurso);
       	$sgrRecurso->detach_validador($validador);
      });
    }
    foreach ($gestores as $gestor) {
      $grupo->gestores()->detach($gestor);
      $recursos = $grupo->recursos->each(function($recurso) use ($gestor) {
       	$sgrRecurso = Factoria::getRecursoInstance($recurso);
       	$sgrRecurso->detach_gestor($gestor);
      });
   	}
   	return true;
  }

	public function removeRelacionConRecurso($id,$administradores,$validadores,$gestores){
  	$recurso = recurso::findOrFail($id);
    $sgrRecurso = Factoria::getRecursoInstance($recurso);
    
    foreach ($administradores as $administrador) {
    	$sgrRecurso->detach_administrador($administrador);
    }
    foreach ($validadores as $validador) {
      $sgrRecurso->detach_validador($validador);
    }
    foreach ($gestores as $gestor) {
     	$sgrRecurso->detach_gestor($gestor);
    }
   	return true;
  }


}