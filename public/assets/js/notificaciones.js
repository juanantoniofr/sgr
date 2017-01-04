$(function(e){
/* marca branch master2 */
	$('.list-group').on('click',function(e){// :)
		$('form#activeUser input[name="username"]').val($(this).data('username'));
		$('form#activeUser input[name="idnotificacion"]').val($(this).data('idnotificacion'));
		$('form#activeUser input[name="id"]').val($(this).data('iduser'));
		m_hideMsg();
		$('#modalUser').modal('show');
	});

	$('#activar').on('click',function(e){// :)
			e.preventDefault();
			$activar = 1;
			$iditemlista = $('form#activeUser input[name="idnotificacion"]').val();
			updateCuentaDeUsuario($activar,$iditemlista);
	});

	$('#desactivar').on('click',function(e){// :)
			e.preventDefault();
			$activar = 0;
			$iditemlista = $('form#activeUser input[name="idnotificacion"]').val();
			updateCuentaDeUsuario($activar,$iditemlista);
	});
	
	function updateCuentaDeUsuario($activar,$iditemlista){// :)
		m_hideMsg();
		$data = 'activar=' + $activar + '&username=' + $('input:text[name=uvus]').val()+ '&' +$('form#activeUser').serialize();
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

	$('#borrar').on('click',function(e){ // :/
		e.preventDefault();
		$data = $('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxBorraUser",
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
          $('#msg').html($respuesta.msg).fadeIn();
          $iditemlista = $('form#activeUser input[name="idnotificacion"]').val();	
          $('#' + $iditemlista).fadeOut().remove();
       	}
			},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
		});
		$("#modalUser").modal('hide');
	});	

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
	
});