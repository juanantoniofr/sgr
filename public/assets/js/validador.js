$(function(e){
	

	$('.event').click(function(e){

		var $this = $(this);
		var $idEvent = $this.data('idevent');
		$.ajax({
    	   	type: "GET",
			url: "ajaxDataEvent",
			data: {'id':$idEvent},
        	success: function($respuesta){
				
				var $solapamientos = false;
				if($respuesta['solapamientos']) $solapamientos = true;
        		$.each($respuesta,function(key,value){
        			
                    if ($respuesta['aprobada']){
                        $('#'+key).removeClass('text-info');
                        $('#'+key).removeClass('text-danger');
                        $('#'+key).addClass('text-success');
                    }
                    else{
            			if($solapamientos)	{
            				$('#'+key).removeClass('text-info');
                            $('#'+key).removeClass('text-success');
            				$('#'+key).addClass('text-danger');
            			}
            			else {
            				$('#'+key).removeClass('text-danger');
                            $('#'+key).removeClass('text-success');
            				$('#'+key).addClass('text-info');
            			}
                    }

        			$('#'+key).html(value);

        		});

        		

        		$.urlParam = function(name){
    				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    				if (results==null){
      					 return null;
   					 }
   					 else{
       					return results[1] || 0;
    				}
				}
        		var $filterByRecurso=$('#selectRecurso option:selected').val();
                var $filterUser=$('#selectUser option:selected').val();
                var $veraprobadas = 0;
                if ($('#veraprobadas').is(':checked')) $veraprobadas = 1;
                 var $verdenegadas = 0;
                if ($('#verdenegadas').is(':checked')) $verdenegadas = 1; 
                 var $verpendientes = 0;
                if ($('#verpendientes').is(':checked')) $verpendientes = 1;  
                
                if ($respuesta['aprobada']) {$('a#aprobar').addClass('disabled');}
                else {$('a#aprobar').removeClass('disabled');}
        		
                $('a#aprobar').attr('href', 'valida.html' + '?'+'veraprobadas='+$veraprobadas+'&verdenegadas='+$verdenegadas+'&verpendientes='+$verpendientes+'&id_recurso='+$filterByRecurso+'&id_user='+$filterUser+'&evento_id=' + $respuesta['evento_id']+'&action=' + 'aprobar'+'&idRecurso=' + $respuesta['id_recurso']);
        		
        		$('a#denegar').attr('href', 'valida.html' + '?'+'veraprobadas='+$veraprobadas+'&verdenegadas='+$verdenegadas+'&verpendientes='+$verpendientes+'&id_recurso='+$filterByRecurso+'&id_user='+$filterUser+'&evento_id=' + $respuesta['evento_id']+'&action=' + 'denegar'+'&idRecurso=' + $respuesta['id_recurso']);
        	},
        	error: function(xhr, ajaxOptions, thrownError){
				hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                }
        	});
			$('#modalValidacion').modal('show');
	});


	

    $("#caducidad").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showAnim: 'slideDown',
            dateFormat: 'd-m-yy',
            showButtonPanel: true,
            firstDay: 1,
            monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
            dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
        });

    function showGifEspera(){
        $('#espera').css('display','inline').css('z-index','100');
    }

    function hideGifEspera(){
        $('#espera').css('display','none').css('z-index','-100');
    }
});