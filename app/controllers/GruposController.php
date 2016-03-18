<?php

class GruposController extends BaseController {


	/**
		*
		*	@param Input::get('grupo_id')		int
		*
		*	@return $result array
	*/
	public function del(){
    
    	//Input
    	$id 	     = Input::get('grupo_id','');
    	
    	//Output 
    	$respuesta = array(	'errors'   	=> array(),
    						'msg'		=> '',		
                      		'error' 	=> false,
                      		);
   		//Validate
    	$rules = array(
    		'grupo_id'	=> 'required|exists:grupoRecursos,id', //exists:table,column
        );

     	$messages = array(
        	'required'	=> 'El campo <strong>:attribute</strong> es obligatorio....',
          	'exists'		=> 'No existe identificador de grupo...',	
          );
      	$validator = Validator::make(Input::all(), $rules, $messages);
    	
    	//Save Input or return error
    	if ($validator->fails()){
        	$result['errors'] = $validator->errors()->toArray();
        	$result['error'] = true;
    	}
    	else{  
        	$grupo = GrupoRecurso::findOrFail($id)->delete();
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
    	$id 	     = Input::get('grupo_id','');
    	$nombre 	 = Input::get('nombre','');
    	$descripcion = Input::get('descripcion','');
 
    	//Output 
    	$respuesta = array(	'errors'   	=> array(),
    						'msg'		=> '',		
                      		'error' 	=> false,
                      		);
   		//Validate
    	$rules = array(
    		'grupo_id'	=> 'required|exists:grupoRecursos,id', //exists:table,column
        	'nombre'    => 'required|unique:grupoRecursos,nombre,'.$id,
        );

     	$messages = array(
        	'required'	=> 'El campo <strong>:attribute</strong> es obligatorio....',
          	'exists'		=> 'No existe identificador de grupo...',	
          );
      	$validator = Validator::make(Input::all(), $rules, $messages);
    	
    	//Save Input or return error
    	if ($validator->fails()){
        	$result['errors'] = $validator->errors()->toArray();
        	$result['error'] = true;
    	}
    	else{  
        	$grupo = GrupoRecurso::findOrFail($id)->update(array('nombre' => $nombre,'descripcion' => $descripcion));
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
		$result = array('errors'	=> array(),
						'msg'		=> '',
						'error'		=> false,
						);
		

		//validate
		$rules = array(
        	'nombre'      => 'required|unique:grupoRecursos',
        );

     	$messages = array(
        	'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
        	'unique'        => 'Existe un <b>grupo</b> con el mismo nombre....',
        );
    
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
			$grupo->save();

			$result['msg'] = Config::get('msg.success');	
      	}

		return $result;
	}

	/**
  		* @param Input::get('sortby')	string
  		* @param Input::get('order')	string
  		*
  		* @return View::make('admin.recursos.list')  
  	*/
	public function listar(){
    
	    //Input      
	    $sortby = Input::get('sortby','nombre');
	    $order = Input::get('order','asc');
	         
	    //Todos los grupos
	    $grupos = GrupoRecurso::all();/*->filter(function($grupo){
	      
	      $recursos = $grupo->recursos->each(function($recurso){
	        return $recurso->supervisores->contains(Auth::user()->id);  
	      }); 
	      if ($recursos->count() > 0) return true;
	      
	     });*/


	    return View::make('admin.recursos.list')->nest('table','admin.recursos.table',compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAddGrupo','admin.modalgrupos.add')->nest('modalEditGrupo','admin.modalgrupos.edit')->nest('modalDelGrupo','admin.modalgrupos.del')->nest('modalAddRecurso','admin.modalrecursos.add',compact('grupos'))->nest('modalEditRecurso','admin.modalrecursos.edit',compact('grupos'))->nest('modalAddRecursosToGrupo','admin.modalgrupos.addRecurso')->nest('modalDelRecurso','admin.modalrecursos.del')->nest('modalEnabledRecurso','admin.modalrecursos.enabled')->nest('modalDisabledRecurso','admin.modalrecursos.disabled')->nest('modalAddPersona','admin.modalrecursos.addPersona');
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
  		* @param Input::get('grupo_id') int indetificador de grupo
  		* @param Input::get('idrecursos') array indentificadores de recursos añadir al grupo
  		*
  		* @return $result array(boleano,string)
  	*/
  	public function addrecursos(){

  		//input
  		$id = Input::get('grupo_id','');
  		$idrecursos = Input::get('idrecursos',array());
  		//out
		$result = array('error'	=> false,
						'msg'	=> Config::get('msg.success'),
						);

		//Validación input
		if (empty($id) || GrupoRecurso::where('id','=',$id)->count() != 1) {
			$result['msg'] = Config::get('msg.idempty') . ' o ' . Config::get('msg.idnotfound');
			$result['error'] = true;
  		}
  		else {
  			foreach ($idrecursos as $idrecurso) {
  				Recurso::find($idrecurso)->update(array('grupo_id'=>$id));
  			}
  		}
  			
  		return $result;
  	}





}