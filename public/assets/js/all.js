function showGifEspera(){
  
  $('#espera').css('display','inline').css('z-index','10000');
}
/* marca branch master2 */
function hideGifEspera(){
 
  $('#espera').css('display','none').css('z-index','-10000');
}

function showMsg($msg){
  
  $('#msg').fadeOut('slow').html($msg).fadeIn('slow');
}

function showErrores($errors){
	hideMsg();
	$.each($errors,function(index,error){
		$.each(error,function(index,value){
			$('#msg').append(value);});	
	});
	$('#msg').fadeIn('slow');
}

function hideMsg(){
  $('#msg').fadeOut('slow').html();
}

function m_hideMsg(){
	$('.modal_msgError').fadeOut();//Oculta texto "formulario con errores"
	$('.divmodal_msgError').html('').css('display','none');//Resetea a vacio y oculta cualquier mensaje en cualquier ventana modal
  $('.modal_spantexterror').html('').fadeOut();//Resetea a vacio y oculta cualquier mensaje en cualquier ventana modal
  $('.form-group').removeClass('has-error');//Elimina errores en campos de formulario de cualquier ventana modal
}

function m_showMsg($errors,$idmodal){
	$.each($errors,function(index,value){
		$('.modal_msgError').fadeIn();//Muestra texto "formulario con errores".
    $($idmodal + '_input'+index).addClass('has-error');//resalta el campo de formulario con error
    $($idmodal + '_textError_'+index).append(value + '<br />').fadeIn();//añade texto de error a span alert-danger asociado al campo de formulario con error
  });
}