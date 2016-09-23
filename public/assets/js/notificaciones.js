$(function(e){

	function resetMsgErros(){// :)
		$('.modal_MsgError').fadeOut();

		$('.modal_spantexterror').fadeOut();//Resetea a vacio y oculta cualquier mensaje en cualquier ventana modal
    $('.form-group').removeClass('has-error');//Elimina errores en campos de formulario de cualquier ventana modal
	}

	$('.list-group').on('click',function(e){// :)
		$('form#activeUser input[name="username"]').val($(this).data('username'));
		$('form#activeUser input[name="idnotificacion"]').val($(this).data('idnotificacion'));
		$('form#activeUser input[name="id"]').val($(this).data('iduser'));
		resetMsgErros();
		$('#modalUser').modal('show');
	});

	$('#activar').on('click',function(e){// :)
			e.preventDefault();
			$activar = true;
			$iditemlista = $('form#activeUser input[name="idnotificacion"]').val();
			updateCuentaDeUsuario($activar,$iditemlista);
	});

	$('#desactivar').on('click',function(e){// :/
			e.preventDefault();
			$activar = false;
			$iditemlista = $('form#activeUser input[name="idnotificacion"]').val();
			updateCuentaDeUsuario($activar,$iditemlista);
	});
	
	function updateCuentaDeUsuario($activar,$iditemlista){
		resetMsgErros();
		$data = 'activar=' + $activar + 'username=' + $('input:text[name=uvus]').val()+ '&' +$('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxUpdateUser",
			data: $data,
			success: function($respuesta){
				if($respuesta.error === true){
         	hideGifEspera();
         	$.each($respuesta.errors,function(index,value){
           	$('.modal_MsgError').fadeOut();
           	$('#m_activaUsuario_input'+index).addClass('has-error');//resalta el campo de formulario con error
           	$('#m_activaUsuario_textError_'+index).html(value + '<br />').fadeIn();//añade texto de error a div alert-danger en ventana modal
         	});
         	$('#m_activaUsuario_msgError').fadeIn('8000');
       	}
        else {
          hideGifEspera();
          $("#modalUser").modal('hide');
          //location.reload();
          $('#msg').html($respuesta.msg).fadeIn();
          $('#' + $iditemlista).fadeOut().remove();
       	}
			},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
			}
		});
	}

	
		
	$('#borrar').on('click',function(e){

			e.preventDefault();

			$data = 'username=' + $('input:text[name=uvus]').val()+ '&' +$('form#activeUser').serialize();
			showGifEspera();
			$.ajax({
					type:"POST",
					url:"ajaxBorraUser",
					data: $data,
					success: function(respuesta){
						hideGifEspera();
						$('#msgerror').fadeOut();
						$('#textmsgsuccess').html('Usuario <b>'+$('input:text[name=uvus]').val()+'</b> borrado con éxito');
						$('#msgsuccess').fadeIn('slow');
						$('form#activeUser').data('item').remove();
						//$item.remove();			
						//console.log($item);
						
					},
					error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
           					
					}
			});
			$("#modalUser").modal('hide');
			
	});	

	//var $currentDate = new Date();
	
	
	//var $defaultDate = new Date($currentDate.getFullYear() + 1, 9 , 30); //Date(year,month,day)
	$("#datepickerCaducidad").datepicker({
			//defaultDate: $defaultDate,
			showOtherMonths: true,
	    selectOtherMonths: true,
	    showAnim: 'slideDown',
	  	ateFormat: 'd-m-yy',
	  	showButtonPanel: true,
	  	firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	});
	
	//$("#datepickerCaducidad").val($defaultDate.getDate() + '-' + $defaultDate.getMonth() + '-' + $defaultDate.getFullYear());

	function showGifEspera(){
		
		$('#espera').css('display','inline').css('z-index','1000');
	}

	function hideGifEspera(){
	
		$('#espera').css('display','none').css('z-index','-1000');
	}

});