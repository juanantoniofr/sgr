$(function(e){

	$('.list-group').on('click',function(e){// :)
		e.preventDefault();
		e.stopPropagation();
		$('form#activeUser input[name="username"]').val($(this).data('uvus'));
		$('form#activeUser input[name="idnotificacion"]').val($(this).data('idnotificacion'));
		var $defaultCaducidad = new Date($(this).data('defaultcaducidad'));
		configuredatepicker($defaultCaducidad);
		m_hideMsg();
		$('#modalUser').modal('show');
	});

	$('#activar').on('click',function(e){
		e.preventDefault();
		$data = 'activar=1&'+$('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxUpdateUser",
			data: $data,
			success: function(respuesta){
				hideGifEspera();
				console.log(respuesta);
				$('#msg').fadeOut().html(respuesta.msg).fadeIn('slow');
				eliminadelalista('.list-group',respuesta.idnotificacion);
			},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
			}
		});
		$("#modalUser").modal('hide');
	});

	$('#desactivar').on('click',function(e){
		e.preventDefault();
		$data = 'activar=0&'+$('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxUpdateUser",
			data: $data,
			success: function(respuesta){
				hideGifEspera();
				$('#msg').fadeOut().html(respuesta.msg).fadeIn('slow');
				eliminadelalista('.list-group',respuesta.idnotificacion);
				},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
			}
		});
		$("#modalUser").modal('hide');
	});
		
	$('#borrar').on('click',function(e){
		e.preventDefault();
		$data = $('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxBorraUser",
			data: $data,
			success: function(respuesta){
				hideGifEspera();
				$('#msgerror').fadeOut();
				$('#msg').html(respuesta.msg);
				$('#msgsuccess').fadeIn('slow');
				
			},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
			}
		});
		$("#modalUser").modal('hide');
	});	

	function configuredatepicker($defaultDate){
		console.log($defaultDate);
		$("#datepickerCaducidad").datepicker({
			showOtherMonths: true,
	   	selectOtherMonths: true,
	   	showAnim: 'slideDown',
			dateFormat: 'd-m-yy',
	 		showButtonPanel: true,
	 		firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	  	});
	
		$("#datepickerCaducidad").val($defaultDate.getDate() + '-' + ($defaultDate.getMonth() + 1) + '-' + $defaultDate.getFullYear());
	}

	function eliminadelalista($classlista,$iditem){
		$($classlista).each(function (){
					console.log($(this));
					console.log($iditem);
					console.log($(this).data('idnotificacion'));
						if ($iditem == $(this).data('idnotificacion')) $(this).fadeOut().remove();
					});
	}
	
});