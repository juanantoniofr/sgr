<?php

class RelacionesController extends BaseController {

	/**
    * //Establece la relación users-grupoRecursos (supervisor-validador-tecnico)
    *
    * @param Input::get('idgrupo')    int
    * @param Input::get('username')   string
    * @param Input::get('rol')        string
    *
    * @return $result array
    * 
  */
  public function ajaxAddrelacionUsuarioGrupo(){ // :)
    
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
          $gestores = $grupo->gestores;
          if ($gestores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['gestor'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>técnico</b></i> de este Grupo.';
            return $result;
          }
          $grupo->gestores()->attach($idUser);
          $recursos = $grupo->recursos->each(function($recurso) use ($idUser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_gestor($idUser);
          });
          break;
        
        //Supervisor
        case '2':
          $administradores = $grupo->administradores;
          if ($administradores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['administrador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>administrador</b></i> de este Grupo.';
            return $result;
          }
          $grupo->administradores()->attach($idUser);
          $recursos = $grupo->recursos->each(function($recurso) use ($idUser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_administrador($idUser);
          });
          break;
      
        //Validador
        case '3':
          $validadores = $grupo->validadores;
          if ($validadores->contains($idUser)){
            $result['error'] = true;
            $result['errors']['validador'] = 'Usuario con UVUS <i>'.$username.'</i> ya es <i><b>validador</b></i> de este Grupo.';
            return $respuesta;
          }
          $grupo->validadores()->attach($idUser);
          $recursos = $grupo->recursos->each(function($recurso) use ($idUser) {
          	$sgrRecurso = Factoria::getRecursoInstance($recurso);
          	$sgrRecurso->attach_validador($idUser);
          });
          $result['msg'] = 'Usuario <i>'.$username.'</i> añadido como <i><b>validador</b></i> con éxito.';
          break;
      
        default:
          $result['error'] = false;
          $result['msg'] = 'Identificador de rol no esperado: ' . $rol;
        break;
      }//fin case
    }//fin else

    $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    return $result;
  }


  /**
  	*
  	* //devuelve array objetc User que son gestores del grupo $idgrupo
  	* @param $idgrupo int identificador de grupo
  	* @return array()
  **/
  public function ajaxGetGestoresGrupo(){//:)
  	//input
    $idgrupo   = Input::get('idgrupo','');
    
    //Output 
    $result = array( 'errors'   	 => array(),
                     'gestores'   => '',    
                     'error'   	=> false,
                    );
    //Validate
    $rules = array( 'idgrupo'    => 'required|exists:grupoRecursos,id', //exists:table,column
        						);

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idgrupo.exists'      => 'No existe identificador de grupo en BD.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	$result['gestores'] = grupoRecurso::find($idgrupo)->gestores->toArray();
    }
    return $result;	
  }	

  /**
  	*
  	* //devuelve array objetc User que son administradores del grupo $idgrupo
  	* @param $idgrupo int identificador de grupo
  	* @return array()
  **/
  public function ajaxGetAdministradoresGrupo(){ // :)
  	//input
    $idgrupo   = Input::get('idgrupo','');
    
    //Output 
    $result = array( 'errors'   	 				=> array(),
                     'administradores'   	=> '',    
                     'error'   						=> false,
                    );
    //Validate
    $rules = array( 'idgrupo'    => 'required|exists:grupoRecursos,id', //exists:table,column
        						);

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idgrupo.exists'      => 'No existe identificador de grupo en BD.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	$result['administradores'] = grupoRecurso::find($idgrupo)->administradores->toArray();
    }
    return $result;	
  }	

  /**
  	*
  	* //devuelve array objetc User que son validadores del grupo $idgrupo
  	* @param $idgrupo int identificador de grupo
  	* @return array()
  **/

  public function ajaxGetValidadoresGrupo(){//:)
  	//input
    $idgrupo   = Input::get('idgrupo','');
    
    //Output 
    $result = array( 'errors'   		 => array(),
                     'validadores'   => '',    
                     'error'   			=> false,
                    );
    //Validate
    $rules = array( 'idgrupo'    => 'required|exists:grupoRecursos,id', //exists:table,column
        						);

    $messages = array(
          'required'            => 'El campo <strong>:attribute</strong> es obligatorio.',
          'idgrupo.exists'      => 'No existe identificador de grupo en BD.',
          );

    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //Save Input or return error
    if ($validator->fails()){
        $result['errors'] = $validator->errors()->toArray();
        $result['error'] = true;
        return $result;
    }
    else{
    	$result['validadores'] = grupoRecurso::find($idgrupo)->validadores->toArray();
    }
    return $result;	
  }	
  
  /**
    * //elimina la relación grupoRecursos-persona
    *
    * @param Input::get('idgrupo') int
    * @param Input::get('administrador_id) array
    * @param Input::get('validadores_id') array
    * @param Input::get('tecnicos_id') array
    *
    * @return $result array    
    *
  */
  public function ajaxRemoverelacionUsuarioGrupo(){ // :)
    
    //input
    $idgrupo            = Input::get('idgrupo','');
    $administradores    = Input::get('administradores_id',array());
    $validadores        = Input::get('validadores_id',array());
    $gestores           = Input::get('gestores_id',array());
    
    //Output 
    $result = array( 	'errors'    	=> array(),
                      'msg'   		=> '',    
                      'error'   	=> false,
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
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    }

    return $result;
  }

}