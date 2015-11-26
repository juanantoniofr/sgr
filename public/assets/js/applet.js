$(function(e){

	$('.updateList').on('click',function(e){
		$('#resultsearch').fadeOut('slow').fadeIn('slow');
		if (getUvus() != $('#uvus').html()) $('#uvus').fadeOut().html(getUvus()).fadeIn('slow');
		$('#uvusBtn').html(getUvus());
		e.preventDefault();
		getReservas();
		//$('#update').close('show');
	});
	
	
	$('#dni').on('change',function(){
		
		resetmsg();
		if ($('#dni').html() != ""){
			getReservas();
		}
		
		if (getUvus() != $('#uvus').html()) $('#uvus').html(getUvus()).fadeIn('slow');
		$('#uvusBtn').html(getUvus());
	});

	function resetmsg(){
		$('#errorgetEvents').fadeOut('slow');
		$('#error').fadeOut('slow');
		$('#success').fadeOut('slow');
		$('#nohayreservas').fadeOut('slow');
		$('#divSearch').fadeOut('slow');
		$('#resultsearch').html('');
	}

	function getReservas(){
		
		$.ajax({
				type: "GET",
				url: "search",
				data: {username:getUvus()},

				success: function(respuesta){
					//console.log(respuesta);
					
					
					if (respuesta === '-1'){
						
						$('#errorgetEvents span').html('Usuario no leido....');
						$('#errorgetEvents').fadeIn('slow');	
					} 
					else if (respuesta === '1') {
						
						$('#errorgetEvents span').html('No existe cuenta de usuario....');
						$('#errorgetEvents').fadeIn('slow');
						//$('#btnNuevaReserva').addClass('disabled');	
					}
					else {
						$('#btnNuevaReserva').removeClass('disabled');	
						$('#resultsearch').html(respuesta).fadeIn('slow');//.fadeIn('slow');
						$('#divSearch').fadeIn('slow');
						$('.reserva').on('click',function(e){
													e.preventDefault();
													if ($(this).hasClass('disabled')) {
														$('#update').modal('show');
														//$('#btnUpdateList').fadeOut('slow').fadeIn('slow');
													}
													else launchDataModal($(this).data('idevento'),$(this).data('idserie'),$(this).data('fechaevento'),$(this).data('uvus'),$(this).data('recurso'),$(this).data('observaciones'));													
												});
					}
					 
		    },
				error: function(xhr, ajaxOptions, thrownError){
						//hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
				}
	  });
	}

	function launchDataModal($idEvento,$idSerie,$fechaEvento,$reservadoPorUvus,$recursoReservado,$observaciones){
		$('#success').fadeOut('slow');
		$('#error').fadeOut('slow');
		$('#errorgetEvents').fadeOut('slow');
		$.ajax({
			type: "GET",
			url: "getDataEvent",
			data: {idEvento:$idEvento,idSerie:$idSerie,fechaEvento:$fechaEvento},
			success: function($respuesta){
				//console.log($respuesta);
				
				//titulo
				$('input[name|="titulo"]').val($respuesta.titulo);
				//recurso
				$('input[name|="recurso"]').val($recursoReservado);
				//Actividad
				$('select[name|="actividad"] option').each(function(){
					if ($(this).val() == $respuesta.actividad) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});
				//Fecha inicio: campo día
								//hora inicio
				$('select[name|="hInicio"] option').each(function(){
					if (compareTime($(this).val(),$respuesta.horaInicio) == 0) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});
				//hora fin
				$('select[name|="hFin"] option').each(function(){
					if (compareTime($(this).val(),$respuesta.horaFin) == 0) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});
				

				//repetir
				$('select[name|="repetir"]').val('CS');
				if ($respuesta.repeticion == '1'){
					$('select[name|="repetir"]').val('CS');
					$('#datepickerFinicio').val(dateToformatES($respuesta.fechaEvento));
					$('#datepickerFinicio').prop('disabled',true);
					$('#datepickerFevento').val(dateToformatES($respuesta.fechaInicio));
					$('#datepickerFfin').val(dateToformatES($respuesta.fechaFin));
					$aDias = eval($respuesta.diasRepeticion);
					$("input:checkbox").each(function(index,value){
						$(this).prop('checked',false);
						if ($.inArray($(this).val(),$aDias) != -1){	$(this).prop('checked',true);}  
						
					});
					$('#inputRepeticion').show();
					}
				else{
					$('select[name|="repetir"]').val('SR');
					$('#datepickerFinicio').val(dateToformatES($respuesta.fechaEvento));
					$('#datepickerFevento').val(dateToformatES($respuesta.fechaInicio));
					$('#datepickerFfin').val(dateToformatES($respuesta.fechaFin));
					$("input:checkbox").each(function(index,value){
						$(this).prop('checked',false);
						if ( $(this).val() == $respuesta.dia )  $(this).prop('checked',true);
					});

					$('#inputRepeticion').hide();
					}
				
				//observaciones
				$('textarea[name|="observaciones"]').val($observaciones);
				
				//Uvus ->reservadoPor
				$('input[name|="reservadoPor"]').val($reservadoPorUvus);
				$('#uvusModal').html($reservadoPorUvus);
				setResumen();
				
				//id evento (hidden)
				$('input[name|="eventoid"]').val($idEvento);

				
				
				$('#modalAdd').modal('show');		
			},
			error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
				}
			});
	} //./launchDataModal

	function setResumen(){
		
		//var options = {weekday: "long", year: "numeric", month: "long", day: "numeric"};
		var options = {year: "numeric", month: "long", day: "numeric"};
		var options_i = {weekday: "long"};
		var $horaInicio = $('#newReservaHinicio option:selected').val();
		var $horaFin = $('#newReservaHfin option:selected').val();
		//var $str = '<span style="font-style:strong;">Resumen:</span> ';
		var $str = '';
		var $strf = '';
		var $diasSemana = {'0':'domingo','1':'lunes','2':'martes','3':'miércoles','4':'jueves','5':'viernes','6':'sábado'};
		var $dias = '';

		// Fecha inicio
		var $fi = $('#datepickerFinicio').val();
		
		var $stri = parseDate($fi);
		var $di = new Date(parseInt($stri[2]),parseInt($stri[1])-1,parseInt($stri[0]));		
		
		// Repetición 
		if ($('#newReservaRepetir').val() == 'CS'){ 
			$str += ' cada semana desde el ';
			// Fecha fin
			var $ff = $('#datepickerFfin').val();
			var $strf = parseDate($ff);
			var $df = new Date(parseInt($strf[2]),parseInt($strf[1])-1,parseInt($strf[0]));
			$strf = ', hasta el ' +  $df.toLocaleString("es-ES", options);
		
		// Dias semana
		$("input:checkbox").each(function(){
			if ($(this).is(':checked')) {
				$numWeek = $(this).val();
				$dias += $diasSemana[$numWeek] + ', ';
			}
		});
		if ($dias != '') $dias = ' todos los ' + $dias;
		}
		else{
			$str += ' ' + $di.toLocaleString("es-ES",options_i) + ', ';
		}
		
		
		$('#resumen').html('<p>'+$str+ $di.toLocaleString("es-ES", options) + $dias +' de '+$horaInicio+' a '+ $horaFin +  $strf +'</p>');
		//$('#resumen').slideUp('slow');
	}

	function compareTime($h1,$h2){
		//devuelve -1 si $h1 < $h2, 0 si $h1 = $h2 y 1 si $h1 > $h2
		$ah1 = $h1.split(':');
		$date1 = new Date();
		$date1.setHours($ah1[0]);
		$date1.setMinutes($ah1[1]);

		$ah2 = $h2.split(':');
		$date2 = new Date();
		$date2.setHours($ah2[0]);
		$date2.setMinutes($ah2[1]);

		$result = 0;
		//if ($date1 == $date2) $result = 0;
		if ($date1 < $date2) $result = -1;
		else if  ($date1 > $date2) $result = 1;
		return $result;
	}

	function dateToformatES($strFecha){
		var $af = parseDate($strFecha,'-','en-EN');
		$strDate = $af[0] + '-' + $af[1] + '-' + $af[2];
		return $strDate;
	}

	function parseDate(strFecha,$delimiter,$locale) {
		
		$delimiter	= typeof $delimiter !== 'undefined' ? $delimiter : '-';
   		$locale 	= typeof $locale    !== 'undefined' ? $locale : 'es-ES';

		var sfecha = $.trim(strFecha);
		var aFecha = sfecha.split($delimiter);
		
		if ($locale == 'es-ES'){
			var day = $.trim(aFecha[0]);									
			var month = $.trim(aFecha[1]);
			var year = $.trim(aFecha[2]);
		}
		else if ($locale = 'en-EN'){
			var day = $.trim(aFecha[2]);									
			var month = $.trim(aFecha[1]);
			var year = $.trim(aFecha[0]);	
		}
	
		var aDate = [day,month,year];

		return aDate;
	}

	$('#btnNuevaReserva').on('click',function(e){
		
		e.preventDefault();
		$('#resultsearch a').addClass('disabled');
		
		$miventana = window.open('../calendarios.html?uvus='+getUvus(),"ReservaDiaria","directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=yes,resizable=no");
        $miventana.focus();
	});


	$('#saveformAtiendeEvento').on('click',function(e){
		e.preventDefault();
		$('#modalAdd').modal('hide');
		showGifEspera();
		$.ajax({
			type:"POST",
			url:"saveAtencion",
			data: $('form#formAtiendeEvento').serialize(),
			success: function($respuesta){
				//$('#success').fadeOut('slow');
				//$('#error').fadeOut('slow');
				if ($respuesta == "success") {
					$('#success span').html('Datos salvados con éxito....');
					$('#success').fadeIn('slow');
				}
				if ($respuesta == "error"){
					$('#error span').html('Error de ejecución. Contacte con la Unidad Tic....');
					//$('#error').fadeIn('slow');	
				} 
				hideGifEspera();
				getReservas();
			},
			error: function(xhr, ajaxOptions, thrownError){
					hideGifEspera();
					alert(xhr.responseText + ' (codeError: ' + xhr.status  +')');
				}
		});
	});

	//obj tipo: Object { numeroSerie: "00221684", uvus: "joacalrod", nombreApellidos: "JOAQUIN CALONGE RODRIGUEZ", tipoUsuario: "ESTUDIANTE", dni: "77848620" }
	function getUvus(){
		return $('#dni').html();
	}

	function getNombre(){
		var $json = lector.getJsonObject().toString();
		var $obj = $.parseJSON($json);
		return $obj.nombreApellidos;
	}
	

	function showGifEspera(){
		$('#espera').css('display','inline').css('z-index','100');
	}

	function hideGifEspera(){
		$('#espera').css('display','none').css('z-index','-100');
	}
});
