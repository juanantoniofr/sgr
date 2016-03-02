<?php

class GruposController extends BaseController {

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
  		* @return View::make('admin.grupolist')  
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


	    return View::make('admin.grupolist')->nest('tableRecursos','admin.tableRecursos',compact('grupos','sortby','order'))->nest('dropdown',Auth::user()->dropdownMenu())->nest('menuRecursos','admin.menuRecursos')->nest('modalAddGrupo','admin.modalgrupos.add');
  	}

  	/**
  		* Ajax function: devuelve la lista de grupos en forma de tabla
  		*
  		* @param Input::get('sortby')	string
  		* @param Input::get('order')	string
  		*
  		* @return View::make('admin.tableRecursos)  
  	*/
	public function getTable(){
    	
    	//Input      
	    $sortby = Input::get('sortby','nombre');
	    $order = Input::get('order','asc');

	    //Todos los grupos
	    $grupos = GrupoRecurso::all();
	    return View::make('admin.tableRecursos',compact('grupos','sortby','order'));
  	}

}