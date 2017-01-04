<?php
/* marca branch master2 */
class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWellcome(){

		if (Cas::isAuthenticated() == false){
			Auth::logout();
			return View::make('wellcome');
		} 

		if (Auth::check() == true){
			$sgrUser = new sgrUser(Auth::user());
			return Redirect::to($sgrUser->home());
		} 

		return View::make('wellcome');

  }

	public function sendmailcontact(){

		$rules = array(	'titulo'	=> 'required',
						'texto'		=> 'required',
						);

		$messages = array(	'titulo.required'		=>	'El campo título es obligatorio...',
							'texto.required'		=>	'El campo texto es obligatorio...',
							);

		$validator = Validator::make(Input::all(), $rules, $messages);

		 if ($validator->fails())
		 {
		 	return Redirect::back()->withErrors($validator->errors());
		 }
		 else{
		 	$data['autor'] = '(' . Auth::user()->username . ') ' . Auth::user()->apellidos . ', ' . Auth::user()->nombre;
			$data['titulo'] = Input::get('titulo','');
			$data['texto'] = Input::get('texto','');
			$data['mail'] = Auth::user()->email;
		 	$sgrMail = new sgrMail();
		 	$sgrMail->notificaContacto($data);
		 	Session::flash('message','Mensaje enviado correctamente...');
          	return Redirect::back();
          }
	}
	
	public function ayuda(){
		
		if (Auth::check()){
			$dropdown = Auth::user()->dropdownMenu();
			return View::make('ayuda')->nest('dropdown',$dropdown);	
		} 
		else return View::make('ayuda'); 
	}

	public function contacto(){
		if (Auth::check()){
			$dropdown = Auth::user()->dropdownMenu();
			return View::make('contacto')->nest('dropdown',$dropdown);	
		} 
		else return View::make('contacto');
	}
}