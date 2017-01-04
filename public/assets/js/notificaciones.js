$(function(e){

/* marca branch master2 */
	$('.list-group').on('click',function(e){// :)
		$('form#activeUser input[name="username"]').val($(this).data('username'));
		$('form#activeUser input[name="idnotificacion"]').val($(this).data('idnotificacion'));
		$('form#activeUser input[name="id"]').val($(this).data('iduser'));
		m_hideMsg();
		$('#modalUser').modal('show');
	});

	$('#activar').on('click',function(e){
		e.preventDefault();
		$data = 'username=' + $('input:text[name=uvus]').val()+ '&' +$('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxActiveUser",
			data: $data,
			success: function(respuesta){
				hideGifEspera();
				$('#msgerror').fadeOut();
				$('#textmsgsuccess').html('Usuario <b>'+$('input:text[name=uvus]').val()+'</b> activado con éxito');
				$('#msgsuccess').fadeIn('slow');
				//console.log($item);
				$('form#activeUser').data('item').remove();
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
		$data = 'username=' + $('input:text[name=uvus]').val()+ '&' +$('form#activeUser').serialize();
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"ajaxDesactiveUser",
			data: $data,
			success: function(respuesta){
				hideGifEspera();
				$('#msgerror').fadeOut();
				$('#textmsgsuccess').html('Usuario <b>'+$('input:text[name=uvus]').val()+'</b> desactivado con éxito');
				$('#msgsuccess').fadeIn('slow');
				$('form#activeUser').data('item').remove();
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
			},
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
			}
		});
		$("#modalUser").modal('hide');
	});	

	var $currentDate = new Date();
	var $defaultDate = new Date($currentDate.getFullYear() + 1, 9 , 30); //Date(year,month,day)
	$("#datepickerCaducidad").datepicker({
		defaultDate: $defaultDate,
		showOtherMonths: true,
	 	selectOtherMonths: true,
		showAnim: 'slideDown',
		dateFormat: 'd-m-yy',
		showButtonPanel: true,
		firstDay: 1,
		monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
		dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	});
	
	$("#datepickerCaducidad").val($defaultDate.getDate() + '-' + $defaultDate.getMonth() + '-' + $defaultDate.getFullYear());

	function showGifEspera(){
		$('#espera').css('display','inline').css('z-index','1000');
	}

	function hideGifEspera(){
		$('#espera').css('display','none').css('z-index','-1000');
	}

});