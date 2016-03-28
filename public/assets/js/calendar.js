$(function(e){
	

	var nameMonths = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

	var $daysWeekAbr = ['Dom','Lun','Mar','Mie','Jue','Vie','Sab','Dom'];

	onLoad();
	
	/*functions 
		*****************************************************************************
	*/

	//When load page....
	function onLoad(){

		// 1. --> Programmer events 

		// 1.1 When change recurse selected 
		whenChangeRecurseSelected();
		//1.2 When click buttons pre, next or today
		whenClickButtonNav();

		//1.3 When datapicker change
		$('#datepicker').on('change',function(){printCalendar();});	
		
		//1.4 When Change view active
		$('#btnView .btn').click(function(){
			
			var $this = $(this);
			
			//Add class 'active'
			if ($this.hasClass('active') != true){
				$this.addClass('active');
			}
			//Remove class 'active' for siblings
			$this.siblings('.active').removeClass('active');
			
			var $viewActive = $('#btnView .active').data('calendarView');

			if ($viewActive == 'agenda'){
				$('#btnNuevaReserva').addClass('disabled');
				$('#recurse').addClass('disable');
				$('#selectGroupRecurse').addClass('disable');
				
			}
			else{
				$('#btnNuevaReserva').removeClass('disabled');
				$('#recurse').removeClass('disable');
				$('#selectGroupRecurse').removeClass('disable');
			}

			$('#message').fadeOut("slow");
			setLabelRecurseName();
			printCalendar();
		});
		
		//1.5 When click button "nueva reserva"
		$('#btnNuevaReserva').click(function(e){
			var $id_recurso = (!$('select#recurse option:selected').val()) ? '' : $('select#recurse option:selected').val();
			if ($('#alert_msg').data('nh') > 12){
					$('#alert_msg').fadeOut('slow');
					$('#alert_msg').fadeIn('slow');		
				}
			else if (0 == $id_recurso){
				$('#alert').fadeOut();$('#alert').fadeIn();
			}
			else {
				$('#editOptions').hide();
				resetMsgErrors();
				$('#datepickerFinicio').val(firstDayAviable());
				$('.divEvent a.linkpopover').each(function(index,value){ $(this).popover('hide'); });
				setInitValueForModalAdd('8:30',firstDayAviable());
				$('#modalAdd').modal('show');
			}
		});

		//1.5.1 When clik button "leer carnet"
		/*$('#appletLaunch').click(function(e){
			$('#modalApplet').modal('show');
		});*/

		//1.6 click infoButton
		$('#infoButton').on('click',function(e){
				e.preventDefault();
				$('#modalDescripcion').modal('show');				
			});	
		
		//2. -> Configure datapickers
		//***************************
		configureDataPickers();
		
		//3. -> Progammer event modal add/edit window
		whenChangeInputInModalWindow();
		
		//4. Initial Value for recurse selected
		$('#selectGroupRecurse').val('0');
		

		//5. Initial Value for datepicker
		$("#datepicker").val(firstDayAviable());

		//6. Set initial value some element (also, init function, can to be call when change content the table calendar by ajax)
		init();

		//7. Programmer Events when user click in Calendar Cell
		programerEventClickToCalendarCell();


		//8. Set initial value for Modal delete window (also, init function, can to be call when change content the table calendar by ajax)
		initModalDelete();

		//9. Progremmer event click button save event in Modal Window (add/edit)
		$("button#save").click(function(e){saveEvent();});
	}
	
	//functions: call from function onLoad()
	//********************************************************************************
	//********************************************************************************
	
	function whenChangeRecurseSelected(){
		
		//When change recurse selected
		$('#recurse').on('change',function(){
			$('#message').fadeOut("slow");
			var $str = 'Nueva reserva: ' +  $('select#recurse option:selected').text();
			$('#myModalLabel').html($str);
			setLabelRecurseName();
			//alert($('select#recurse option:selected').val());
			$('input[name$=id_recurso]').val($('select#recurse option:selected').val());
			printCalendar();
		});

		//When select group recurse
		$('#selectGroupRecurse').on('change',function(e){
			showGifEspera();
			$('#message').fadeOut("slow");
			
			$.ajax({
				type:"GET",
				url:"getRecursos",
				data: { groupID:$('select#selectGroupRecurse option:selected').val()},
				success: function(respuesta){
					$('#selectRecurseInGroup').fadeOut('fast',function(){$('select#recurse option').detach();});
					$('#selectRecurseInGroup').fadeIn('fast',function(){
						$('#recurse').html(respuesta);
						$("select#recurse option:first").prop("selected", "selected");
						$('select#recurse').change();
					});
					hideGifEspera();
				},
				error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
				}
			});
		});
	}

	function whenClickButtonNav(){
		
		$('#navprev').click(function(){
			$('#message').fadeOut("slow");
			var aDate = parseDate($('#datepicker').val());
			$day = aDate[0];
			$month = aDate[1];
			$year = aDate[2];
			$viewActive = $('#btnView .active').data('calendarView');
			//prev month
			if ($viewActive == 'month'){
				var $newdate = new Date($year,$month-2,$day);
				$day = $newdate.getDate();
				$month = 1 + parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			}
			//prev week
			if ($viewActive == 'week'){
				var $newdate = new Date($year,$month-1,$day-7);
				
				$day = $newdate.getDate();
				$month = 1 + parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			}

			//prev agenda (-1 día) 
			if ($viewActive == 'agenda'){
				var $newdate = new Date($year,$month,parseInt($day)-1);
				
				$day = $newdate.getDate();
				$month = parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			
			}

			$('#datepickerFinicio').val($day+'-'+$month+'-'+$year);
			$('#datepicker').val($day+'-'+$month+'-'+$year);
			
			printCalendar();
		});

		$('#navnext').click(function(){
			$('#message').fadeOut("slow");
			var aDate = parseDate($('#datepicker').val());
			$day = aDate[0];
			$month = aDate[1];
			$year = aDate[2];
			$viewActive = $('#btnView .active').data('calendarView');
			//next month
			if ($viewActive == 'month'){
				var $newdate = new Date($year,$month,$day);
				$day = $newdate.getDate();
				$month = 1 + parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			}
			//next week
			if ($viewActive == 'week'){
				var $newdate = new Date($year,$month-1,parseInt($day)+7);
				$day = $newdate.getDate();
				$month = 1 + parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			
			}
			//next agenda (+1 día)
			if ($viewActive == 'agenda'){
				var $newdate = new Date($year,$month,parseInt($day)+1);
				$day = $newdate.getDate();
				$month = parseInt($newdate.getMonth());
				$year = $newdate.getFullYear();
			
			}
			
			$('#datepickerFinicio').val($day+'-'+$month+'-'+$year);
			$('#datepicker').val($day+'-'+$month+'-'+$year);
				
			printCalendar();
		});

		$('#navhoy').click(function(){
			$('#message').fadeOut("slow");
			var aDate = parseDate(today());
			$day = aDate[0];
			$month = aDate[1];
			$year = aDate[2];
			$('#datepickerFinicio').val($day+'-'+$month+'-'+$year);
			$('#datepicker').val($day+'-'+$month+'-'+$year);
			
			printCalendar();
		});
	}

	function updatePrintButton(){
		
		var aDate = parseDate($('#datepicker').val());
		$day = aDate[0];
		$month = aDate[1];
		$year = aDate[2];
		$('#btnprint').data('day',$day);
		$('#btnprint').data('month',$month);
		$('#btnprint').data('year',$year);
	}

	function configureDataPickers(){
		$("#datepicker" ).datepicker({
			defaultDate: firstDayAviable(),
	    	showOtherMonths: true,
	      	selectOtherMonths: true,
	      	showAnim: 'slideDown',
	  		dateFormat: 'd-m-yy',
	  		showButtonPanel: true,
	  		firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	  	});

		$("#datepickerFevento" ).datepicker({
	    	showOtherMonths: true,
	      	selectOtherMonths: true,
	      	showAnim: 'slideDown',
	  		dateFormat: 'd-m-yy',
	  		showButtonPanel: true,
	  		firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	  	});

		$( "#datepickerFinicio" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true,
			showAnim: 'slideDown',
			dateFormat: 'd-m-yy',
			showButtonPanel: true,
			firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
			});	
	
		$( "#datepickerFfin" ).datepicker({
	    	showOtherMonths: true,
	      	selectOtherMonths: true,
	      	showAnim: 'slideDown',
	  		dateFormat: 'd-m-yy',
	  		showButtonPanel: true,
	  		firstDay: 1,
			monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
			dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
	  		});
	}

	function whenChangeInputInModalWindow(){

		$( "#datepickerFevento" ).on('change',function(){
			setCheckBoxActive($(this).val());
			$('#datepickerFinicio').val($(this).val());
			setResumen();
		});

		$( "#datepickerFinicio" ).on('change',function(){
			setCheckBoxActive($(this).val());
			$('#datepickerFevento').val($(this).val());
			$('#datepickerFfin').val(nextMonth(dateToformatES($(this).val())));
			setResumen();
		});
		
		$( "#datepickerFfin" ).on('change',function(){
			setResumen(); 
			});
	
		$( "#newReservaHinicio" ).on('change',function(){
			setResumen();
			});

		$( "#newReservaHfin" ).on('change',function(){
			setResumen();
			});

		$( "#newReservaRepetir" ).on('change',function(){
			if ($('#newReservaRepetir').val() == 'CS') {
				$('#inputRepeticion').slideDown('slow');
				$('#datepickerFinicio').prop('disabled', true);
			}
			else {
				$('#inputRepeticion').slideUp('slow');
				$('#datepickerFinicio').prop('disabled', false);
			}
			setResumen();
		});
	
		$('input:checkbox').each(function(){
			$(this).on('change',function(){
				setResumen();
				});
		});
	}
	
	
	/*init function --> call from function onLoad()
		********************************************************************************
		********************************************************************************
	*/
	
	//Init some element
	function init(){
		$('#msg').hide();
		
		//When view = agenda
		var $viewActive = $('#btnView .active').data('calendarView');
		if ($viewActive == 'agenda'){
			$('a.agendaEdit').each(function(){setLinkEditEvent($(this).data('idEvento'));});
		}
		else {
			//When view != agenda	
			$('.divEvent a.linkpopover').each(function(){setPopOver($(this));});
			resaltaLinkOnHover();
			$('a.linkMasEvents').on('click',onMoreEvents);
		}
		//Always
		$('#modalAdd').on('hidden.bs.modal', function (e) {
  			$('button#save').show();
  			$('#inputRepeticion').hide();
  			$('#newReservaRepetir option[value=SR]').prop('selected','selected');
  			$('#datepickerFinicio').prop('disabled',false);
		});

		(!$('select#recurse option:selected').val()) ? $('#btnNuevaReserva').prop('disabled','disabled') : $('#btnNuevaReserva').prop('disabled','');
	}

	function onMoreEvents(e){
		e.preventDefault();
		e.stopPropagation();
				
		$ancho = $(this).prev('.divEvents').css('width');
		$('.divEvent a.linkpopover').each(function(index,value){
				 $(this).popover('hide');});

		$(this).prev('.divEvents').css({'min-width':'20%','width':'auto','height':'auto','background-color':'#abc','overflow':'visible','border':'1px solid black','position':'absolute','z-index':'180'});
				
		$(this).prev('.divEvents').find('.cerrar').show();
				
		$(this).prev('.divEvents').find('.cerrar').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			$('.divEvent a.linkpopover').each(function(index,value){
				 $(this).popover('hide');});
			

			$(this).hide();
		
			$(this).find($('a.linkpopover')).hover(
				function(){
					$hidden = $(this).parents('.divEvents').css('overflow');
					if($hidden == 'hidden'){
						$(this).css({'overflow':'visible','position':'absolute'});
						}
					},
					function(){
						$(this).css({'overflow':'hidden','position':'inherit'});
					});
		
			$(this).parent('.divEvents').css({'width':$ancho,'height':'68px','background-color':'white','overflow':'hidden','border':'0px','position':'inherit','z-index':'0'});

			

		});
	}

	//Init Modal Window for delete event
	function initModalDelete(){
		$('#option1').click(function(){
			$idEvento = $('#option1').data('idEvento');
			$idSerie =	$('#option1').data('idSerie');
			deleteEvents(1,$idEvento,$idSerie);
		});
		$('#option2').click(function(){
			$idEvento = $('#option2').data('idEvento');
			$idSerie =	$('#option2').data('idSerie');
			deleteEvents(2,$idEvento,$idSerie);
		});
		$('#option3').click(function(){
			$idEvento = $('#option1').data('idEvento');
			$idSerie =	$('#option1').data('idSerie');
			deleteEvents(3,$idEvento,$idSerie);
		});
	}

	//Programer event for Click In calendar Cell
	function programerEventClickToCalendarCell(){	
		
		$('.formlaunch').click(function(e){
			if($('select#recurse option:selected').data('disabled')){
				$('#modalMsg').modal('show');
			}	
			else{
				if ($('select#recurse option:selected').val() === undefined) {
					//alert('Espacio o medio a reservar no seleccionado');
					$('#alert').fadeOut('slow');
					$('#alert').fadeIn('slow');
				}
				else if ($('#alert_msg').data('nh') >= 12){
						$('#alert_msg').fadeOut('slow');
						$('#alert_msg').fadeIn('slow');		
					}
				else {
					$elem = $(this).find($('.divEvents'));
					$hidden = $elem.css('overflow');
					if($hidden == 'visible') {
						$elem.css({'width':$ancho,'height':'68px','background-color':'white','overflow':'hidden','border':'0px','position':'inherit','z-index':'0'});
						$(this).find($('.cerrar')).hide();
					}

					setInitValueForModalAdd($(this).data('hora'),$(this).data('fecha'));
					$('.divEvent a.linkpopover').each(function(index,value){ $(this).popover('hide');});
					$('#modalAdd').modal('show');
				}
			}

		});
	}

	function setInitValueForModalAdd($horaInicio,$fechaInicio){
			
		$('#actionType').val('');
		var $str = 'Nueva reserva: ' +  $('select#recurse option:selected').text();
		$('#myModalLabel').html($str);
		
		
		if ($('select#recurse option:selected').data('atendido')) $('#reservarPara').fadeIn();	
		else {
			$('#reservarPara').fadeOut();
			$('form#addEvent input[name=reservarParaUvus]').val('');	
		}
		

		resetMsgErrors();
		$('#errorsModalAdd').slideUp();//Cierra el div con los errores
		$('#editOptions').hide();
		//Título
		$('form#addEvent input#newReservaTitle').val('');
		//Hora inicial
		$('select[name|="hInicio"] option').each(function(){$(this).prop('selected',false);});
		$('select[name|="hInicio"] option[value="'+$horaInicio+'"]').prop('selected',true);
		//calcular hora final
		$hora = $horaInicio;
		$aItem  = $hora.split(':');
		$hf = parseInt($aItem[0]) + 1;
		$strhf = $hf + ':30';
		$('select[name|="hFin"] option').each(function(){$(this).prop('selected',false);});
		$('select[name|="hFin"] option[value="'+$strhf+'"]').prop('selected',true); 
		//Fechas
		var $fecha = $fechaInicio;	
		var $strf = parseDate($fecha);//$fecha
		$('#datepickerFinicio').val($fecha);
		$('#datepickerFinicio').prop('disabled',false);
		$('#datepickerFevento').val($fecha);
		$('#datepickerFfin').val(nextMonth(dateToformatES($fecha)));
		//Periocidad
		$('select[name|="repetir"] option').each(function(){$(this).prop('selected',false);});
		$('#inputRepeticion').hide();
		//Día de la semana
		setCheckBoxActive($fecha);
		//Texto resumen
		setResumen();

		//

	}

	function resetMsgErrors(){
		
		var $labels = new Array('titulo','fInicio','hFin','fFin','dias','fEvento');
		for(var key in $labels){
					$('#'+$labels[key]).removeClass('has-error');
 		       		$('#'+$labels[key]+'_Error').html('').hide();
		}
		$('#errorsModalAdd').slideUp();
	}
	/*
		display new data in calendar (call from functions in onLaod....)
		********************************************************************************
		********************************************************************************
	*/

	
	function printCalendar(){
		
		updatePrintButton();
		var $val = $('#datepicker').val();
		var $aDate = parseDate($val,'-','es-ES');
		var date = new Date($aDate[2],$aDate[1],$aDate[0]);
		var $viewActive = $('#btnView .active').data('calendarView');
		var $id_recurso = (!$('select#recurse option:selected').val()) ? '' : $('select#recurse option:selected').val();
		var $grupo_id = $('select#selectGroupRecurse option:selected').val();

		if ($grupo_id == '0' && $viewActive != 'agenda' ) {$('#alert').fadeOut();$('#alert').fadeIn();}
		else {
			$('#btnprint').removeClass('disabled');
			$('#btnNuevaReserva').removeClass('disabled');
			showGifEspera();
			
			$.ajax({
				type:"GET",
				url:"ajaxCalendar",
				data:{viewActive: $viewActive,day: $aDate[0],month: $aDate[1], year: $aDate[2],id_recurso: $id_recurso,groupID: $grupo_id},
				success: function(respuesta){
					if ($('select#recurse option:selected').val()) {$('#alert').css('display','none');}
					

					$('#tableCaption').fadeIn('slow').html(respuesta['tCaption']);
					
					$('#tableHead').fadeIn('slow').html(respuesta['tHead']);
					$('#tableBody').fadeIn('slow').html(respuesta['tBody']);
										
					init();
					//if (!$('select#recurse option:selected').data('disabled')) programerEventClickToCalendarCell();
					programerEventClickToCalendarCell();
					if ($viewActive == 'agenda') {
						setLinkDeleteEvent();
						}
					
					hideGifEspera();

					if ($('select#recurse option:selected').data('disabled')) {
						
						$('#btnNuevaReserva').addClass('disabled');
						//muestra modal disabled recurso
						$('#modalMsgTitle').html(respuesta['disabled']['title']);
						$('#textMsg').addClass('alert');
						$('#textMsg').addClass('alert-warning');
						$('#textMsg').html(respuesta['disabled']['msg']);
						$('#modalMsg').modal('show');
			
					}
					
					},
					error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(' (codeError: ' + xhr.status +')' + xhr.responseText );
					}
				});
				
		}

	}

	/*Action: 1. save new event
		********************************************************************************
		********************************************************************************
	*/
	
	//Save new event to DB
	function saveEvent(){
		$('#message').fadeOut("slow");
		
		$data = 'grupo_id=' + $('select#selectGroupRecurse option:selected').val() + '&' + $('form#addEvent').serialize();
		$.ajax({
    	   	type: "POST",
			url: "saveajaxevent",
			//data: $('form#addEvent').serialize(),
			data: $data,
        	success: function(respuesta){
        		//alert(respuesta);
        		if (respuesta['error'] == false){
 		       		//addEvent(respuesta['ids']);
					$('#message').html(respuesta['msgSuccess']).fadeIn("slow");
					
 		       		printCalendar();
					$("#modalAdd").modal('hide');
					$('#actionType').val('');
 		       	}
 		       	else {
 		       		$('.has-error').removeClass('has-error');
 		       		$('.is_slide').each(function(){$(this).slideUp();});
 		       		resetMsgErrors();
 		       		//console.log(respuesta['msgErrors']);
 		       		$.each(respuesta['msgErrors'],function(key,value){

 		       			$('#'+key).addClass('has-error');
 		       			$('#'+key+'_Error').html(value).fadeIn("slow");
 		       			$('#errorsModalAdd').slideDown("slow");
 		       			
 		       		});
	        	}
 		        },
				error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
					}
      			});
	}
	
	/*Action: 2. delete event
		********************************************************************************
		********************************************************************************
	*/

	//Delete events to BD (by ajax)
	function deleteEvents($option,$idEvento,$idSerie){
		//Delete event by ajax
		
		$.ajax({
    		type: "POST",
			url: "delajaxevent",
			data: {'id_recurso':$('select#recurse option:selected').val(),'grupo_id':$('select#selectGroupRecurse option:selected').val(),'option':$option,'idEvento':$idEvento,'idSerie':$idSerie},
        	success: function(respuesta){
			       
			        $(respuesta).each(function(index,value){
			        	//Actualiza calendario en el front-end
			        	
			        	$('#alert_msg').data('nh',$('#alert_msg').data('nh')-1);
			        	if ($('#alert_msg').data('nh') < 12){
							$('#alert_msg').fadeOut('slow');}
					
			        	
			        	//deleteEventView(value.id);	
			        });
			        $("#deleteOptionsModal").modal('hide');
					$('#actionType').val('');
			        $('#message').fadeOut('slow');
			        printCalendar();
		        },
			error: function(xhr, ajaxOptions, thrownError){
					hideGifEspera();
					alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
				}
      		});
	}
	
	/*Action: 3. Edit event
		********************************************************************************
		********************************************************************************
	*/

	//edit events (DB & calendar view)
	function editEvents($option,$idEvento,$idSerie){
		
		
		$('#message').fadeOut("slow");

		$.ajax({
		    	type: "POST",
				url: "editajaxevent",
				data: 'grupo_id=' + $('select#selectGroupRecurse option:selected').val() + '&' +'option='+$option+'&'+'idEvento='+$idEvento+'&'+'idSerie='+$idSerie+'&'+$('form#addEvent').serialize(),
		       	success: function(respuesta){
		 		   	if (respuesta['error'] == false){
		 					 		
			 			$('#message').html(respuesta['msgSuccess']).fadeIn("slow");
				 		printCalendar();
				 				 		   					 		       		
						$("#modalAdd").modal('hide');
						$('#actionType').val('');
						
				 	}
			 		else {
				 		$('.has-error').removeClass('has-error');
				 		$('.is_slide').each(function(){$(this).slideUp();});
				 		resetMsgErrors();
				 		$.each(respuesta['msgErrors'],function(key,value){
				 		    $('#'+key).addClass('has-error');
	   		       			$('#'+key+'_Error').html(value).fadeIn("slow");
				 		    $('#errorsModalAdd').slideDown("slow");
				 		});
					}

				},
				error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
				}
	      	});
	}

	function initModalEdit($idEvento,$idSerie){
		//By Ajax obtenmos los datos del evento para rellenar los campos del formulario de edición		
		resetMsgErrors();
		$.ajax({
    	   	type: "GET",
			url: "geteventbyId",
			data: {'id':$idEvento},
        	success: function($respuesta){
        		//console.log($respuesta);
        		$evento = $respuesta['event'];
        		$usernameReservadoPara = $respuesta['usernameReservadoPara'];
        		$usernameReservadoPor = $respuesta['usernameReservadoPor'];
        		$('#contentModalAdd').html($respuesta);
        		
        		//console.log($respuesta + $reservadoPara);
				//titulo
				$('form#addEvent input#newReservaTitle').val($evento.titulo);
				//Actividad
				$('select[name|="actividad"] option').each(function(){
					if ($(this).val() == $evento.actividad) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});

				$('form#addEvent input#reservadoPara').val($usernameReservadoPara);
				$('form#addEvent input#reservadoPor').val($usernameReservadoPor);

				//Fecha inicio: campo día
								//hora inicio
				$('select[name|="hInicio"] option').each(function(){
					if (compareTime($(this).val(),$evento.horaInicio) == 0) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});
				//hora fin
				$('select[name|="hFin"] option').each(function(){
					if (compareTime($(this).val(),$evento.horaFin) == 0) $(this).prop('selected',true);
					else $(this).prop('selected',false);
				});
				

				//repetir
				
				if ($evento.repeticion == '1'){
					$('select[name|="repetir"]').val('CS');
					$('#datepickerFinicio').val(dateToformatES($evento.fechaEvento));
					$('#datepickerFinicio').prop('disabled',true);
					$('#datepickerFevento').val(dateToformatES($evento.fechaInicio));
					$('#datepickerFfin').val(dateToformatES($evento.fechaFin));
					$aDias = eval($evento.diasRepeticion);
					$("input:checkbox").each(function(index,value){
						$(this).prop('checked',false);
						if ($.inArray($(this).val(),$aDias) != -1){	$(this).prop('checked',true);}  
						
					});
					$('#inputRepeticion').show();
					}
				else{
					$('select[name|="repetir"]').val('SR');
					$('#datepickerFinicio').val(dateToformatES($evento.fechaEvento));
					$('#datepickerFevento').val(dateToformatES($evento.fechaInicio));
					$('#datepickerFfin').val(dateToformatES($evento.fechaFin));
					$("input:checkbox").each(function(index,value){
						$(this).prop('checked',false);
						if ( $(this).val() == $evento.dia )  $(this).prop('checked',true);
					});

					$('#inputRepeticion').hide();
					}
				
				setResumen();
				$('button#save').hide();
				$('#editOptions').show();
				$('#modalAdd').modal('show');
 		    },
			error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
				alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
				}
      		});

		$('#actionType').val('edit');
		$('#myModalLabel').html('Editar evento');
		
		$('button#editOption1').off();
		

		$('button#editOption1').click(function(){
			$('#EditOption1').data('idEvento',$idEvento);
			$('#EditOption1').data('idSerie',$idSerie);
			editEvents('1',$idEvento,$idSerie);
		});
	}
	
	//popover 
	function setPopOver($item){
		$item.popover(); 
		$item.click(function(e){
			e.preventDefault();
			e.stopPropagation();
			var $elem = $(this);
		
			$('.divEvent a.linkpopover').each(function(index,value){
				if (!$elem.is($(this))) $(this).popover('hide');});
			
			//$elem.popover('hide');
			$('a.comprobante').click(
				function(e){
					e.stopPropagation();
					$elem.popover('hide');
				});	

			$('a.closePopover').click(function(e){
				e.preventDefault();
				e.stopPropagation();
				$elem.popover('hide');
			});
			
			
			$('div.popover').click(function(e){
				e.preventDefault();
				e.stopPropagation();});
			

			$elem.popover();
			setLinkEditEvent($elem.data('id'));
			setLinkDeleteEvent();
			activarLinkFinalizaReserva($elem.data('id'));
			activarLinkAnulaReserva($elem.data('id'));

		});
	}
	
	//Programa evento onCLick en el link finalizar de la ventana popover 
	function activarLinkFinalizaReserva($id){
		
		$('#finaliza_'+$id).click(function(e){
			e.preventDefault();
			$('span#titulofinaliza').html($(this).data('titulo'));
			$('span#usuariofinaliza').html($(this).data('usuario'));
			$('#buttonModalFinaliza').data('idevento',$id);
			$('#finaliza_'+$id).parents('.divEvent').find('a.linkpopover').popover('hide');
			$("#modalFinalizareserva").modal('show');
		});
	}

	$('#buttonModalFinaliza').on('click',function(e){
		e.preventDefault();
		
		$('#message').fadeOut("slow");
		$.ajax({
    	   	type: "POST",
			url: "finalizaevento",
			data: {'idevento' : $(this).data('idevento'), 'observaciones' : $('#finalizaObservaciones').val() },
        	success: function(respuesta){
        		//alert(respuesta);
        		if (respuesta['error'] == false){
 		       		$('#message').html(respuesta['msgSuccess']).fadeIn("slow");
					printCalendar();
				}
 		       	else {
 		       		$('#alert_msg').fadeOut('slow').html(respuesta['msgError']).fadeIn("slow");
 		       		}
 		        },
				error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
					}
      			});
		$("#modalFinalizareserva").modal('hide');
	});

	//Programa evento onCLick en el link anular de la ventana popover
	function activarLinkAnulaReserva($id){
		
		$('#anula_'+$id).click(function(e){
			e.preventDefault();
			$('span#tituloanula').html($(this).data('titulo'));
			$('span#usuarioanula').html($(this).data('usuario'));
			$('#buttonModalAnula').data('idevento',$id);
			$('#anula_'+$id).parents('.divEvent').find('a.linkpopover').popover('hide');
			$("#modalAnulareserva").modal('show');
		});
	}
	//ajax: anular reserva
	$('#buttonModalAnula').on('click',function(e){
		e.preventDefault();
		
		$('#message').fadeOut("slow");
		$.ajax({
    	   	type: "POST",
			url: "anulaevento",
			data: {'idevento' : $(this).data('idevento'), 'observaciones' : $('#anulaObservaciones').val() },
        	success: function(respuesta){
        		//alert(respuesta);
        		if (respuesta['error'] == false){
 		       		$('#message').html(respuesta['msgSuccess']).fadeIn("slow");
					printCalendar();
				}
 		       	else {
 		       		$('#alert_msg').fadeOut('slow').html(respuesta['msgError']).fadeIn("slow");
 		       		}
 		        },
				error: function(xhr, ajaxOptions, thrownError){
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
					}
      			});
		$("#modalAnulareserva").modal('hide');
	});


	//Programa evento onCLick en el link editar de la ventana popover
	function setLinkEditEvent($id){
		var viewActive = '';
		viewActive = $('#btnView .active').data('calendarView');
	
		$selector = 'a#edit';
		if (viewActive == 'agenda') $selector = 'a#edit_agenda';
		//alert('Selector = ' + $selector+'_'+$id);
		//Programa el envento onClick en el enlace en la ventana popover para eliminar evento.
		$($selector + '_' + $id).click(function(e){
			e.preventDefault();
			e.stopPropagation();
			//alert('lanza evento');
			$this = $(this);
			$idEvento = $this.data('idEvento');
			$idSerie = $this.data('idSerie');
			//console.log($idEvento);
			$('#editOption1').data('idEvento',$idEvento);
			$('#editOption1').data('idSerie',$idSerie);
			//Cargar datos del evento en al ventana Modal para editar el evento
			initModalEdit($idEvento,$idSerie);
			$($selector).parents('.divEvent').find('a.linkpopover').popover('hide');
			
			//$('#modalAdd').modal('show');
		});
	}
	
	//Programa evento onCLick en el link eliminar de la ventana popover
	function setLinkDeleteEvent(){
		var viewActive = '';
		viewActive = $('#btnView .active').data('calendarView');
		$selector = 'a#delete';
		if (viewActive == 'agenda') $selector = 'a.delete_agenda';
		//Programa el envento onClick en el enlace en la ventana popover para eliminar evento.
		$($selector).click(function(e){
			e.preventDefault();
			e.stopPropagation();
			$this = $(this);
			$('#msg').html('').fadeOut();
			$idEvento = $this.data('idEvento');
			$idSerie = $this.data('idSerie');
			$('#option1').data('idEvento',$idEvento);
			$('#option1').data('idSerie',$idSerie);
			
			
			$($selector).parents('.divEvent').find('a.linkpopover').popover('hide');
			$('#deleteOptionsModal').modal('show');

		});
	}
	
	function setCheckBoxActive($fecha){
				
		var fechaSelect = parseDate($fecha);
		var $f = new Date(parseInt(fechaSelect[2]),parseInt(fechaSelect[1])-1,parseInt(fechaSelect[0]));
		var num = $f.getDay(); //0-> domingo, 1-> lunues,....., 6->sábado
		$("input:checkbox").each(function(index,value){
			//if ($(this).is(':checked') && $('#actionType').val() != 'edit') $(this).prop('checked',false);
			if ($(this).is(':checked')) $(this).prop('checked',false);
			if (index === num) {
			 	$(this).prop('checked',true);}
		});
		//$('#datepickerFfin').val(nextDay(dateToformatES($fecha)));
	}

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
	
	function resaltaLinkOnHover(){
		
		$('.linkEvento').hover(
			function(){
				$oldColor = $(this).parents('.divEvent').css('background-color');
				$idSerie = $(this).data('idSerie');
				$hidden = $(this).parents('.divEvents').css('overflow');
				
				$thisWidth = $(this).css('width');
				if($hidden == 'hidden'){
					$(this).parent('divEvent').css({'background-color':'#abc'});
					 $(this).css({'background-color':'transparent','position':'absolute','z-index':'180','border':'1px solid #333'});
					 $width = $(this).parent('.divEvent').css('width');
				}
					
				
				$('.linkEvento').each(function(){
					if ($(this).data('idSerie') == $idSerie) 
						$(this).css({'background-color':'#abc','border':'1px solid #333'});
					});
			}	
			,
			function(){
					$('.linkEvento').each(function(){
					if ($(this).data('idSerie') == $idSerie) 
						$(this).css({'background-color':$oldColor,'border':'1px solid transparent','position':'inherit','z-index':'0'});
				});
			}
			);		
	}

	function newLinkSetOnHover($idSerie){
		$('.'+$idSerie).hover(
			function(){
				//$oldColor = $(this).css('background-color');
				$('.'+$idSerie).each(function(){
					$(this).css('background-color','#aa9');
				});
				},
			function(){
				$('.'+$idSerie).each(function(){
					$(this).css('background-color','#C6ECF5');
				});
			}
			);
	}
	
	function setLabelRecurseName(){
		
		$viewActive = $('#btnView .active').data('calendarView');
		if ($viewActive == 'agenda'){
			$('#infoButton').fadeOut();
			$strCalendar = 'todos lo espacios o medios';
		} 
		else {
			$strCalendar = $('select#recurse option:selected').text();
			$idrecurso = $('select#recurse option:selected').val();
			//Ajax para obtener descripcion recurso y muestra botón si no vacio
			$.ajax({
				type:"GET",
				url:"getDescripcion",
				data: { idrecurso:$idrecurso },
				success: function($respuesta){
					//-1 -> 'Error: identificador de recurso vacio...'
					if('-1' != $respuesta && '' != $respuesta) { 					
						$('#nombrerecurso').html($strCalendar);
						$('#descripcionRecurso').html($respuesta);
						if ($respuesta != '') $('#infoButton').fadeIn();
						}
					else $('#infoButton').fadeOut();
					},
					error: function(xhr, ajaxOptions, thrownError){
						$('#infoButton').fadeOut();
						hideGifEspera();
						alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
					}
				});
			
			

			}
			$('#recurseName').html($strCalendar).fadeIn();
	}

	//Other functions
	//***************************************************************
	//***************************************************************
	function getId($fi,$hi){
		//Valores por defecto en caso de no estar defenidas $hi y $hf
		$hInicio	= typeof $hi !== 'undefined' ? $hi : '';
   		//$hFin		= typeof $hf !== 'undefined' ? $hf : '';
		var $f = parseDate($fi,'-','en-EN');
		var $df = new Date($f[2], parseFloat($f[1])-1, parseFloat($f[0]));
		var $str = $df.getFullYear()+'-'+($df.getMonth()+1)+'-'+$df.getDate();
		var $id = getIdfecha($str,$hInicio);
		return $id;
	}

	function getIdfecha($f,$hi){
		$hInicio	= typeof $hi !== 'undefined' ? $hi : '';
		var $fecha = parseDate($f,'-','en-EN');
		var $day = $fecha[0];
		var $month = $fecha[1];
		//Eliminar el cero inicial en el formato del día del mes
		if ($month.substring(0, 1) == '0') $month = $month.substring(1,$month.length);									
		var $year = $fecha[2];
		$viewActive = $('#btnView .active').data('calendarView');
		if ($viewActive == 'week'){
			//Eliminar el cero inicial en el formato hora
			$aItem  = $hInicio.split(':');
			if ($aItem[0].substring(0,1) == '0') $formathi = $aItem[0].substring(1,$aItem[0].length);
			else $formathi = $aItem[0];//.substring(0,2);		
			var $id = $day + $month + $year + $formathi + $aItem[1];//.substring(3,5);
		}
		else
			var $id = $day + $month + $year + '000'; 
		return $id;
	}

	function getContenido($value){
		var $contenido = '<p style="width=100%;text-align:center">';
		var $aDate = parseDate($value.fechaEvento,'-','en-EN');
		var $df = new Date($aDate[2],$aDate[1]-1,$aDate[0]);
		$contenido += $daysWeekAbr[$df.getDay()] + ', ';
		$contenido += $df.getDate() + ' de ';
		$contenido += nameMonths[$df.getMonth()] + ', ';
		$contenido += $value.horaInicio.substring(0,5) + ' - ' +$value.horaFin.substring(0,5)+'</p>';
        $contenido += '<hr /><a href="#" id="edit_'+$value.id+'" data-id-evento="' + $value.id +'" data-id-serie="' + $value.evento_id + '"  data-periodica="'+ $value.repeticion +'">Editar</a> | <a href="#" id="delete"  data-id-evento="' + $value.id +'" data-id-serie="' + $value.evento_id + '"  data-periodica="'+ $value.repeticion +'" >Eliminar</a>';
        return $contenido;
	}

	function nextHoraInicio($hi,$k){
		$nextHoraInicio = $hi;
		var $milsHi = Date.parse("Thu, 01 Jan 1970 " + $hi + " GMT");
		if ($k > 0){
			var $milsHora = 60 * 60 * 1000;
			var $milsNextHora = $milsHi + ($k * $milsHora);
			var $date = new Date($milsNextHora);	
		}
		else {
			var $date = new Date($milsHi);
		}
		$nextHoraInicio = $date.getUTCHours() + ':' + $date.getUTCMinutes();
		return $nextHoraInicio;
	}

	function getIntervalos($hi,$hf){
		$numIntervalos = 1;
		if ($('#btnView .active').data('calendarView') == 'week'){
			var $date = new Date();
			var $milsgIntervalo = 60 * 60 * 1000;
			$numIntervalos = Math.round( (Date.parse("Thu, 01 Jan 1970 " + $hf + " GMT") - Date.parse("Thu, 01 Jan 1970 " + $hi + " GMT"))/ $milsgIntervalo );
		}
		return $numIntervalos;
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

	function today(){
		$today = '';
		$hoy = new Date();
		$today = $hoy.getDate()+'-'+($hoy.getMonth()+1)+'-'+$hoy.getFullYear();
		return $today;
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

	function nextDay($date){
		
		$aDate = parseDate($date,'-','en-EN');
		var $day = new Date($aDate[2],$aDate[1],$aDate[0]);//date(año,mes,dia)
		$day.setTime($day.getTime() + (24 * 60 * 60 * 1000));

		return $day.getDate()+'-'+$day.getMonth()+'-'+$day.getFullYear();

	}

	function nextMonth($date){
		
		$aDate = parseDate($date,'-','en-EN');
		var $day = new Date($aDate[2],$aDate[1],$aDate[0]);//date(año,mes,dia)
		//$day.setTime($day.getTime() + (24 * 60 * 60 * 1000));

		return $day.getDate()+'-'+($day.getMonth()+1)+'-'+$day.getFullYear();
	}

	function firstDayAviable(){
		
		if ($('.formlaunch').first().data('fecha') != undefined)
			return $('.formlaunch').first().data('fecha'); 
		else return $('#btnNuevaReserva').data('fristday');
	}
	
	function strDateCurrentMonth($date){
		$aDate = parseDate($date,'-','es-ES');	
		var $day = new Date($aDate[2],$aDate[1],$aDate[0]);//date(año,mes,dia)
		//$day.setTime($day.getTime() + (24 * 60 * 60 * 1000));
	
		return '1-'+$day.getMonth()+'-'+$day.getFullYear();		
	}

	function showGifEspera(){
		$('#espera').css('display','inline').css('z-index','100');
	}

	function hideGifEspera(){
		$('#espera').css('display','none').css('z-index','-100');
	}

});