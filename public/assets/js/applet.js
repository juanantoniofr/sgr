$(function(e){

	
	function getNombre(){
		var $json = lector.getJsonObject().toString();
		var $obj = $.parseJSON($json);
		return $obj.nombreApellidos;
	}

	function getUvus(){
		return $('#dni').html();
	}

	$('#dni').on('change',function(){
		
		$('input[name|="reservarParaUvus"]').val($('#dni').html());//Actualiza campo formulario add reserva
		
		if (!$('#modalAdd').is(':visible')){
			if ($('#dni').html() != '') {
				getReservas();
				$('#modalAtenderReserva').modal('show');
				$
			}			
			else $('#modalAtenderReserva').modal('hide');
		}
	});

	function getReservas(){
		$.ajax({
				type: "GET",
				url: "tecnico/getUserEvents",
				data: {username:getUvus()},

				success: function(respuesta){
				
					if (respuesta === '-1'){
						
						$('#errorgetEvents span').html('Usuario no leido....');
						$('#errorgetEvents').fadeIn('slow');	
					} 
					else if (respuesta === '1') {
						
						$('#errorgetEvents span').html('No existe cuenta de usuario....');
						$('#errorgetEvents').fadeIn('slow');
					}
					else {
						$('#resultsearch').html(respuesta).fadeIn('slow');
						$('#msgModalAtender').fadeOut();
						$eventSelectId = $('input[name|="idevento"]:first').val();
						$('#nombreUsuario').html($('#evento_'+$eventSelectId).data('nombre') + ' (' + $('#evento_'+$eventSelectId).data('uvus') + ')');
						
						
						$('input[name|="idevento"]').change(function(){
							
							$('textarea[name|="observaciones"]').val($(this).data('observaciones'));	
						});
						$('input[name|="idevento"]:first').prop( "checked", true ).change();
						}
		    },
				error: function(xhr, ajaxOptions, thrownError){
						alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
			}
	  	});
	}

	$('#atender').on('click',function(e){
		e.preventDefault();
		$('#msgModalAtender').fadeOut('slow');
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"tecnico/saveAtencion",
			data: $('form#atenderEvento').serialize(),
			success: function($respuesta){
				hideGifEspera();
				if ($respuesta == "success") {
					$eventSelectId = $('input[name|="idevento"]:checked').val();
					$('#infoEvento_'+$eventSelectId).removeClass('text-info').addClass('text-success');
					$('#infoEvento_'+$eventSelectId+' i').removeClass('fa-calendar-o').addClass('fa-calendar-check-o');
					$('#msgModalAtender').fadeIn('slow');
				}
				
				
			},
			error: function(xhr, ajaxOptions, thrownError){
					hideGifEspera();
					alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
				}
		});
	});

	function showGifEspera(){
		$('#espera').css('display','inline').css('z-index','10000');
	}

	function hideGifEspera(){
		$('#espera').css('display','none').css('z-index','-10000');
	}
});
