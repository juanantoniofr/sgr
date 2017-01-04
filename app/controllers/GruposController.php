<?php
/* marca branch master2 */
class GruposController extends BaseController {

  
  /**
    * Ajax function: devuelve html input select con los recursos visibles para Auth:user() en el grupo con id = groupID
    *
    * @param Input::get('groupID') int
    *
    * @return $result['html' => View::make('calendario.optionsRecursos') string, 'error' => booleano]
  */
  public function AjaxGetRecursos(){ // :)
    
  //input
    $id = Input::get('groupID','');

    //Output
    $result = array('error' => false,
                    'html'  => '',);
    
    //Validate
    $rules = array(
        'groupID'  => 'required|exists:grupoRecursos,id', //exists:table,column
        );

    $messages = array(
          'required'  => 'El campo <strong>:attribute</strong> es obligatorio.',
          'exists'    => 'No existe identificador de recurso en BD.', 
          );
    $validator = Validator::make(Input::all(), $rules, $messages);
    
    //get personas or return error
    if ($validator->fails()){
        $messages = $validator->messages();
        $msg = '';
        foreach ($messages->all() as $m){
          $msg .=  $m . '<br />';
        }
        $result['error'] = true;
        $result['html'] = (string) View::make('msg.error')->with(array('msg' => $msg));
        
    }
    else{
      $grupo = GrupoRecurso::findOrFail($id);
      $sgrGrupo = new sgrGrupo($grupo);
      $sgrRecursos = $sgrGrupo->recursosVisibles(Auth::user()->capacidad);
      
      $result['html'] = (string) View::make('calendario.allViews.optionsRecursos')->with(compact('sgrRecursos'));
    }
      
    return $result;    

  }
	
  /**
    * //Devuelve todos los grupos
    * @param void
    * @return View::make('admin.html.optionGrupos')
  */
  public function htmlOptionGrupos(){//Sireve de algo????

    return View::make('admin.html.optionGrupos')->with('grupos',grupoRecurso::all());
  }

  /**
    * Ajax function: devuelve la lista de grupos en forma de tabla
    *
    * @param Input::get('sortby') string
    * @param Input::get('order')  string
    *
    * @return View::make('admin.recursos.table)  
  */
  public function ajaxGetViewRecursos(){ // :)
      
    //Input      
    $sortby = Input::get('sortby','nombre');
    $order = Input::get('order','asc');

    //Todos los grupos
    $grupos = GrupoRecurso::all();
    foreach ($grupos as $grupo) {
      $sgrGrupos[] = new sgrGrupo($grupo);
    }
      
    return View::make('admin.recursos.recursos',compact('sgrGrupos','sortby','order'));
  }


  /**
    * //develve html checkboxes con los recursos contenedores sin grupo
    * @param $idgrupo int identificador de grupo
    * @return View::make string
  */
  public function ajaxGetRecursoContenedoresSinGrupo(){
    //input
    $id = Input::get('idgrupo','');

    //Output
    $result = array('html'  => '',);
    
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
        $messages = $validator->messages();
        $msg = '';
        foreach ($messages->all() as $m){
          $msg .=  $m . '<br />';
        }
        $result['html'] = (string) View::make('msg.error')->with(array('msg' => $msg));
        //$result['html'] = $msg;
    }
    else{
      $grupo = GrupoRecurso::findOrFail($id);
      $recursosSinGrupo = Recurso::where('grupo_id','=','0')->where('tipo','=',$grupo->tipo)->get();
      $result['html'] = (string) View::make('admin.html.optionscontendoressingrupo')->with(compact('recursosSinGrupo'));
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
  public function ajaxAddrecursoSingrupo(){ // :)
    //Input
    $id = Input::get('grupo_id','');
    $idrecursos = Input::get('idrecursos',array());
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
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    }
    return $result;
  }

  /**
    *
    * @param Input::get('grupo_id')   int
    * @param Input::get('nombre')     string
    * @param Input::get('descripcion')  string 
    *
    * @return $result array
  */
  public function edit(){ // :)
    //Input
    $id          = Input::get('grupo_id','');
    $nombre      = Input::get('nombre','');
    $tipo        = Input::get('tipo');
    $descripcion = Input::get('descripcion','');
 
    //Output 
    $respuesta = array( 'errors'    => array(),
                        'msg'   => '',    
                        'error'   => false,
                        );
    //Validate
    $rules = array(
        'grupo_id'  => 'required|exists:grupoRecursos,id',
        'nombre'    => 'required|unique:grupoRecursos,nombre,'.$id,
        'tipo'      => 'required|in:'.implode(',',Config::get('options.tipoGrupos')), 
      );

    $messages = array(
        'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
        'exists'    => 'No existe identificador de grupo...', 
        'in'        => 'El valor del campo tipo no está definido...',
        'unique'      => 'BD error....',
      );
    $validator = Validator::make(Input::all(), $rules, $messages);
      
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
    }
    else{  
      $grupo = GrupoRecurso::findOrFail($id)->update(array('nombre' => $nombre,'descripcion' => $descripcion, 'tipo' => $tipo));
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.editgruposuccess')));
    }
    return $result;
  }

  /**
    *
    * @param Input::get('nombre')     string
    * @param Input::get('descripcion')  string
    *
    * @return $result array
  */
  public function add(){ // :)
    //out
    $result = array('errors'  => array(),
                    'msg'     => '',
                    'error'   => false,);
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

      //El propio usuario que crea el grupo es administrador del mismo
      $grupo->administradores()->attach(Auth::user()->id); 
      $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.success')));
    }

    return $result;
  }

  /**
    * @param Input::get('grupo_id')   int
    *
    * @return $result array
  */
  public function del(){ // :)
    //Input
    $id = Input::get('grupo_id','');
    
    //Output 
    $respuesta = array( 'errors' => array(),
                        'msg'   => '',    
                        'error'   => false,);
    //Validate
    $rules = array('grupo_id' => 'required|exists:grupoRecursos,id',);

    $messages = array(  'required'  => 'El campo <strong>:attribute</strong> es obligatorio....',
                        'exists'    => 'No existe identificador de grupo...',);
    $validator = Validator::make(Input::all(), $rules, $messages);
      
    //Save Input or return error
    if ($validator->fails()){
      $result['errors'] = $validator->errors()->toArray();
      $result['error'] = true;
      return $result;
    }
    
    $grupo = GrupoRecurso::findOrFail($id);
    if ( $grupo->recursos->count() > 0 ){
      $result['errors'] = array('grupo_id' => 'No se puede eliminar un grupo con elementos');
      $result['error'] = true;
      return $result;
    }
    
    $administradores = $grupo->administradores();
    foreach ($administradores as $administrador) {
      $supervisor->detach($administrador->id);
    }
    $validadores = $grupo->validadores();
    foreach ($validadores as $validador) {
      $validador->detach($validador->id);
    }

    $gestores = $grupo->gestores();
      foreach ($gestores as $gestor) {
        $tecnico->detach($gestor->id);
      }

    $grupo->delete();
    $result['msg'] = (string) View::make('msg.success')->with(array('msg' => Config::get('msg.delgruposuccess')));
      
    return $result;
  }

}