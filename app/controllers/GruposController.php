<?php

class GruposController extends BaseController {

	/**
	*
	*	@param Input::get('nombre') string
	*	@param Input::get('descripcion') string
	*
	*	@return $result array
	*/

	public function add(){

		//out
		$result = array('errors' => array(),
						'msg'	=> '',
						);
		//validate
		$rules = array(
        'nombre'      => 'required|unique:grupoRecursos',
        );

     	$messages = array(
          'required'      => 'El campo <strong>:attribute</strong> es obligatorio....',
          'unique'        => 'Existe un grupo con el mismo nombre....',
        );
    
    	$validator = Validator::make(Input::all(), $rules, $messages);

    	if ($validator->fails()){
       		$respuesta['errors'] = $validator->errors()->toArray();
      	}
      	else{
      		$grupo = new Grupo;
			$grupo->nombre = Input::get('nombre','');
			$grupo->descripcion = Input::get('descripcion','');
			$grupo->save();
			$respuesta['msg'] = Config::get('msg.success');	
      	}

		return $result;

	}


}