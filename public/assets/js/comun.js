
activelinks();
/* marca branch master2 */
function activelinks(){
    //grupos :) (add en grupos.js )
		activelinkeditgrupo(); 									//:) 	edit
    activelinkdelgrupo();										//:)	del
    activeLinkaddrecursoexistentetogrupo();	//:)	add recursos existente (espacio|tipoequipos)
    
		//recursos
    activelinkaddnuevorecurso(); 			// :) add
	  activeLinkeditrecurso(); 					// :)	edit
    activelinkeliminarrecurso();			// :) del
    activeLinkaddItemSinContenedor();	// :)	add recurso existente (puestos|equipos)
		activelinkenabled();							// :/ enabled
    activelinkdisabled();							// :/ disabled
    
    //Relaciones
    activelinkviewrelacion();			//:)	view & del relacion grupos & recursos
    activelinkaddrelacion();			//:)	add relacion
    toggles();
}

function toggles(){ // :)
    $('.toggleOpcionesGrupo').hover(function (event) {
          event.preventDefault();
          $(this).css('text-decoration','none');
          var target = $(this).attr('href');

          $(target).fadeIn('fast');
    });
    $('.toggleOpcionesRecurso').click(function (event) {event.preventDefault();})
    $('.toggleOpcionesRecurso').hover(function (event) {
          
          event.preventDefault();
          $('.opcionesGrupo').fadeOut('fast');
          $(this).css('text-decoration','none');
          var target = $(this).attr('href');
          $(target).fadeIn('fast');
    });
    $('.toggleOpcionesItem').click(function (event) {event.preventDefault();});
    
    $('.toggleOpcionesItem').hover(function (event) {
          event.preventDefault();
          $('.opcionesRecurso').fadeOut('fast');
          $(this).css('text-decoration','none');
          var target = $(this).attr('href');
          $(target).fadeIn('fast');
    });
     
    $('.listitemgrupo').hover(function (event) {
          event.preventDefault();
          $('.opcionesGrupo').fadeOut('fast');
    });
    $('.listitemrecurso').hover(function (event) {
          event.preventDefault();
          $('.opcionesRecurso').fadeOut('fast');
    });
    $('.listitem').hover(function (event) {
          event.preventDefault();
          $('.opcionesItem').fadeOut('fast');
    });
      
    $('.listarRecursos').click(function (event) { //toggle lista (ul) de recursos en un grupo
          event.preventDefault();
          
          $(this).css('text-decoration','none');
          var target = $(this).data('ulrecursosdelgrupo');
          $('.i_'+$(this).data('grupoid')).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
          $(target).fadeToggle('fast');
    });
    
    $('.listarItems').click(function (event) {
          event.preventDefault();
          $(this).css('text-decoration','none');
          
          var target = $(this).data('ulitemsid');
          $('.i_'+$(this).data('recursoid')).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
          
          $(target).fadeToggle('fast');
    });
}

//recursos

	//Ajax: Disabled recurso
	function activelinkdisabled(){ // :)
	  $(".disabled").on('click',function(e){
	    e.preventDefault();
	    $('#m_disabled_nombre').html($(this).data('nombre'));
	    $('form#fm_disabledrecurso input[name="idrecurso"]').val($(this).data('id'));
	    $('form#fm_disabledrecurso input[name="grupoid"]').val($(this).data('grupoid'));
	    CKEDITOR.instances['fm_disabledrecurso_motivo'].setData('');
		  CKEDITOR.instances['fm_disabledrecurso_motivo'].updateElement();
	    m_hideMsg();
	    $('#m_disabledrecurso').modal('show');
	  });
	}
	//Enabled recurso
	function activelinkenabled(){ // :/
		$(".enabled").on('click',function(e){
	    e.preventDefault();
	    $('#m_enabled_nombre').html($(this).data('nombre'));
	    $('form#fm_enabledrecurso input[name="idrecurso"]').val($(this).data('id'));
	    $('form#fm_enabledrecurso input[name="grupoid"]').val($(this).data('grupoid'));
	    m_hideMsg();
	    $('#m_enabledrecurso').modal('show');
	  });
	}
	function activeLinkeditrecurso(){ // :)
	  //Muestra ventana modal editRecurso
	  $(".linkEditRecurso").on('click',function(e){
	    e.preventDefault();
	    showGifEspera();
	    $.ajax({
	      type: "GET",
	      url:  "ajaxGetDatosRecurso",
	      data: {id:$(this).data('id')},
	      success: function($respuesta){
	      	if ($respuesta.error === true){
	      		hideGifEspera();
	      		showErrores($respuesta.errors); //Muestra error en página actual (no modal)
	      	}
	      	else{
		      	hideGifEspera();
		        $recurso = $respuesta.recurso;
		        $('#m_editrecurso_title_nombre').html($recurso.nombre);
		        CKEDITOR.instances['fm_editrecurso_inputdescripcion'].setData($recurso.descripcion);
		        CKEDITOR.instances['fm_editrecurso_inputdescripcion'].updateElement();
		        $('#fm_editrecurso input[name="id"]').val($recurso.id);
		        $('#fm_editrecurso input[name="nombre"]').val($recurso.nombre);
		        $('#fm_editrecurso input[name="id_lugar"]').val($recurso.id_lugar);
		        $('#fm_editrecurso select[name="tiposelect"]').val($recurso.tipo);
		        $('#fm_editrecurso input[name="tipo"]').val($recurso.tipo);
		        //$('#fm_editrecurso_optionstipo').val($recurso.tipo).text($(this).data('texttipo'));
		        $('#fm_editrecurso_optionsPadre').html($respuesta.listadocontenedores);
		        
		        if($recurso.grupo_id != 0)
		        	$('#fm_editrecurso select[name="padre_id"]').val($recurso.grupo_id);
		        else if ($recurso.contenedor_id != 0)
		        	$('#fm_editrecurso select[name="padre_id"]').val($recurso.contenedor_id);
		        
		        
		        
		        $('#fm_editrecurso select[name="modo"]').val($.parseJSON($recurso.acl).m);    
		        $arrayRoles = $.parseJSON($recurso.acl).r.split(',');
		            
		        $('#fm_editrecurso input[type="checkbox"]').prop( "checked", false );
		        $.each($arrayRoles,function(index,value){
		          $('#fm_editrecurso input#fm_editrecurso_roles'+value).prop( "checked", true );
		        });
		        m_hideMsg();
		        $('#m_editrecurso').modal('show');	
	      	}
	      },
	      error: function(xhr, ajaxOptions, thrownError){
	        hideGifEspera();
	        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
	      }
	    });//<!-- ajax -->
	  });
	}

	function activelinkaddnuevorecurso(){ // :)
		$(".addnuevorecursotogrupo, .addnuevoitemtorecurso").on('click',function(e){
			e.preventDefault();
	 		m_hideMsg();
	 		$('#m_addrecurso_title_tiporecursoAañadir').html($(this).data('texttipo'));
	 		$('#m_addrecurso_title_nombre').html($(this).data('nombre'));
	 		$('#fm_addrecurso_optionsContenedor_id').val($(this).data('id')).text($(this).data('nombre'));
	 		$('#fm_addrecurso_labelContenedor_id').text('Añadir en:');
	 		$('#fm_addrecurso_optionstipo').val($(this).data('tipo')).text($(this).data('texttipo'));
	 		$('form#fm_addrecurso input[name="tipopadre"]').val($(this).data('tipoelemento'));
	  	$('#m_addrecurso').modal('show');
		});
	}

	function updateListadoRecursos($iditemlista){ // :)
	  $.ajax({
	    type:"GET",
	    url:"ajaxGetViewRecursos",
	    data:{'orderby':'','order':''},
	    success:function($html){
	      $('#tableRecursos').html($html);
	      $($iditemlista).fadeIn();
	      $($iditemlista).parents().fadeIn();
	      activelinks();
	    },
	    error:function(xhr, ajaxOptions, thrownError){
	      hideGifEspera();
	      alert(xhr.responseText + ' (codeError: ' + xhr.status);
	    }
	  });//<!--./ajax-->
	}

	//Puestos//equipos
	function activeLinkaddItemSinContenedor(){// :)
		$(".addItemSinContenedor").on('click',function(e){
	  	e.preventDefault();
	  	
	    $tipoitem = $(this).data('tipo');
	    $idmodal 	= 'm_add'+$tipoitem+'Existente';
	  	$idform 	= 'fm_add'+$tipoitem+'Existente';
	    $('#m_add'+$tipoitem+'_title_nombre').html($(this).data('nombre'));
	    $('form#'+$idform+' input[name="contenedor_id"]').val($(this).data('contenedorid'));
	    $('#fm_add'+$tipoitem+'Existente_save').data('idmodal',$idmodal);
	    $('#fm_add'+$tipoitem+'Existente_save').data('idform',$idform);
	    m_hideMsg();
	   	$('#'+$idmodal).modal('show');
		});
	}

	//Delete recurso 
	function activelinkeliminarrecurso(){ // :)
	  $(".linkEliminaRecurso").on('click',function(e){
	    e.preventDefault();
	    if($(this).data('numeroelementos') > 0){
	      $('#malert_text').html('No se pueden eliminar un recurso con elementos asignados.');
	      $('#m_alert').modal('show');
	    }
	    else if($(this).data('numeroeventos') > 0){
	      $('#malert_text').html('No se pueden eliminar un recurso con reservas programadas.');
	      $('#m_alert').modal('show');
	    }
	    else {
	      $idrecurso = $(this).data('id');
	      $nombre = $(this).data('nombre');
	      $('#mdrecurso_nombre').html($nombre);
	      $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
	      $('form#fm_delrecurso input[name="grupoid"]').val($(this).data('grupoid')); 
	      m_hideMsg();
	      $('#m_delrecurso').modal('show');
	    }
	  });
	}
//<!-- ./recursos -->

//Grupos
	//Muestra ventana modal para eliminar grupo 
	function activelinkdelgrupo(){ // :)
	  $(".linkdelgrupo").on('click',function(e){
	    e.preventDefault();
	    if($(this).data('numeroelementos') > 0){
	      $('#malert_text').html('No se pueden eliminar un grupo con recursos asignados.');
	      $('#m_alert').modal('show');
	    }
	    else {
	      $idgrupo = $(this).data('idgrupo');
	      $nombre = $(this).data('nombre');
	      $('#mdgrupo_nombre').html($nombre);
	      $('form#fm_delgrupo input[name="grupo_id"]').val($idgrupo);
	      m_hideMsg();
	      $('#m_delgrupo').modal('show');
	    }
	  });
	}

	//Muestra ventana modal para editar grupo de recursos
	function activelinkeditgrupo(){ // :)
	  $(".linkEditGrupo").on('click',function(e){
	    e.preventDefault();
	    $idgrupo = $(this).data('idgrupo');
	    $descripcion = $(this).data('descripcion');
	    $nombre = $(this).data('nombre');
	    //$tipo = $(this).data('tipogrupo');
	    $('form#fm_editgrupo input[name="nombre"]').val($nombre);
	    $('form#fm_editgrupo input[name="descripcion"]').val($descripcion);
	    $('form#fm_editgrupo input[name="grupo_id"]').val($idgrupo);
	    

	    $('#fm_editgrupo_optionstipo').val($(this).data('tipogrupo')).text($(this).data('texttipo'));
	    //$('form#fm_editgrupo select[name="tipo"]').val($tipo);
	    CKEDITOR.instances['fm_editgrupo_inputdescripcion'].setData($descripcion);
	    CKEDITOR.instances['fm_editgrupo_inputdescripcion'].updateElement();
	    m_hideMsg();
	    $('#m_editgrupo').modal('show');
	  });
	}

	//Espacios//tipoequipos
	function activeLinkaddrecursoexistentetogrupo(){ // :)
	  //Muestra ventana modal addrecursotogrupo
	  $(".addrecursoexistentetogrupo").on('click',function(e){
	    e.preventDefault();
	    showGifEspera();
	    
	     $.ajax({
	      type:"GET",
	      url:"ajaxGetRecursoContenedoresSinGrupo",
	      data:{idgrupo : $(this).data('idgrupo')},
	      success: function($respuesta){
	      	
	      	hideGifEspera();
	      	$('#m_addrecursotogrupo_recursos').html($respuesta.html);
	      },
	      error: function(xhr, ajaxOptions, thrownError){
	      	hideGifEspera();
	      alert(xhr.responseText + ' (codeError: ' + xhr.status);
	      }
	    });
	    ///****************
	    $('#m_addrecursotogrupo_nombre').html($(this).data('nombre'));
	    $('form#fm_addrecursotogrupo input[name="grupo_id"]').val($(this).data('idgrupo'));
	    m_hideMsg();
			$('#m_addrecursotogrupo').modal('show');
	  });
	}
//<!-- ./grupos -->  

//relaciones
	//Muestra ventana modal para establecer relación persona-grupo || persona-recurso
	function activelinkaddrelacion(){ // :)
	  $('.addRelacion').on('click',function(e){
	    e.preventDefault();
	    $('#m_addRelacion_title_nombre').html($(this).data('nombre'));
	    $('form#fm_addRelacion input[name="id"]').val($(this).data('id'));
	    $('form#fm_addRelacion input[name="tipo"]').val($(this).data('tipo'));
	    $('form#fm_addRelacion input[name="grupoid"]').val($(this).data('grupoid'));
	    m_hideMsg();
	    $('#m_addRelacion').modal('show');
	  });
	}

	//Muestra ventana modal para establecer relación persona-grupo || persona-recurso
	function activelinkviewrelacion(){ // :)
	  $('.delRelacion, .verRelacion').on('click',function(e){
		  e.preventDefault();
		  $('#m_removeRelacion_title_nombre').html($(this).data('nombre'));
		  $('form#fm_removeRelacion input[name="id"]').val($(this).data('id'));
	 	  $('form#fm_removeRelacion input[name="tipo"]').val($(this).data('tipo'));
	 	  $('form#fm_removeRelacion input[name="grupoid"]').val($(this).data('grupoid'));
		  $id 	= $(this).data('id');
		  $tipo = $(this).data('tipo');
		  setPersonasGestores($id,$tipo);
		  setPersonasAdministradores($id,$tipo);
		  setPersonasValidadores($id,$tipo);

		  m_hideMsg();
		  $('#m_removeRelacion').modal('show');
		 });
	}

	//Obtiene gestores para $id identificador de grupo || recurso
	function setPersonasGestores($id,$tipo){// :)
		$('#fm_removeRelacion-checkboxes-gestores').html('');
	  $.ajax({
	      type:"GET",
	      url:"ajaxGetGestores",
	      data:{id : $id,tipo: $tipo},
	      success: function($respuesta){
	      	$stringhtml = '';
	      	if ($respuesta.error === true) {
	      		//$('#fm_removeRelacion-checkboxes').append('<p class="text-danger">Errores al recuperar usuarios gestores del grupo</p>');
	      		$stringhtml = '<p class="text-danger">Errores al recuperar usuarios técnicos</p>';
	      	}
	      	else{
	      		$gestores = $respuesta.gestores;
		        if ($gestores.length > 0){
			    		//$stringhtml = $stringhtml + '';	
			    		$.each($gestores,function(index,gestor){
			    			$stringhtml = $stringhtml + '<label>';
				 				$stringhtml = $stringhtml + '<input class="" type="checkbox" name="gestores_id[]" value=' + gestor.id +'">';
				 				$stringhtml = $stringhtml + gestor.nombre + ' ' + gestor.apellidos + ' (' + gestor.username + ')';
								$stringhtml = $stringhtml + '</label><br />';
				 			});
				 		//$stringhtml = $stringhtml + '';
						}
						else $stringhtml = $stringhtml + '<p class="text-warning">No hay gestores</p>';    
		        $('#fm_removeRelacion-checkboxes-gestores').append($stringhtml);
	        }
	      },
	      error: function(xhr, ajaxOptions, thrownError){
	      	$('#fm_removeRelacion-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
	      }
	    });
	}

	//Obtiene administradores para $id identificador de grupo || recurso
	function setPersonasAdministradores($id,$tipo){// :)
		$('#fm_removeRelacion-checkboxes-administradores').html('');
	  $.ajax({
	      type:"GET",
	      url:"ajaxGetAdministradores",
	      data:{id : $id, tipo:$tipo},
	      success: function($respuesta){
	      	$stringhtml = '';
	      	if ($respuesta.error === true) {
	      		//$('#fm_removeRelacion-checkboxes').append('Errores al recuperar usuarios administradores');
	      		$stringhtml = '<p class="text-danger">Errores al recuperar usuarios administradores</p>';
	      	}
	      	else{
	      		$administradores = $respuesta.administradores;
		    		if ($administradores.length > 0){
					    //$stringhtml = $stringhtml + '<div class="checkbox" id="fm_removeRelacion_inputadministradores_id">';	
					    $.each($administradores,function(index,administrador){
					    	$stringhtml = $stringhtml + '<label>';
						 		$stringhtml = $stringhtml + '<input type="checkbox" name="administradores_id[]" value=' + administrador.id +'">';
						 		$stringhtml = $stringhtml + administrador.nombre + ' ' + administrador.apellidos + ' (' + administrador.username + ')';
								$stringhtml = $stringhtml + '</label><br />';
						 	});
						 	//$stringhtml = $stringhtml + '</div>';
						}
				    else $stringhtml = $stringhtml + '<p class="text-warning">No hay administradores</p>';
				    $('#fm_removeRelacion-checkboxes-administradores').append($stringhtml);
				  }
	    
	      },
	      error: function(xhr, ajaxOptions, thrownError){
	      	$('#fm_removeRelacion-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
	      }
	    });
	}

	//Obtiene validadores para $id identificador de grupo || recurso
	function setPersonasValidadores($id,$tipo){// :)
		$('#fm_removeRelacion-checkboxes-validadores').html('');
		$.ajax({
	      type:"GET",
	      url:"ajaxGetValidadores",
	      data:{id:$id, tipo:$tipo},
	      success: function($respuesta){
	      	$stringhtml = '';
	      	if ($respuesta.error === true) {
	      		//$('#fm_rremoveRelacion-checkboxes').append('Errores al recuperar usuarios validadores');
	      		$stringhtml = '<p class="text-danger">Errores al recuperar usuarios validadores</p>';
	      	}
	      	else{
				    $validadores = $respuesta.validadores;
				    if ($validadores.length > 0){
					    //$stringhtml = $stringhtml + '<div class="checkbox" id="fm_removeRelacion_inputvalidadores_id">';	
					    $.each($validadores,function(index,validador){
					    	$stringhtml = $stringhtml + '<label>';
						 		$stringhtml = $stringhtml + '<input type="checkbox" name="validadores_id[]" value=' + validador.id +'">';
						 		$stringhtml = $stringhtml + validador.nombre + ' ' + validador.apellidos + ' (' + validador.username + ')';
								$stringhtml = $stringhtml + '</label><br />';
						 	});
						 	//$stringhtml = $stringhtml + '</div>';
					 	}
					 	else $stringhtml = $stringhtml + '<p class="text-warning">No hay validadores</p>';
				    $('#fm_removeRelacion-checkboxes-validadores').append($stringhtml);
				  }
			
	      },
	      error: function(xhr, ajaxOptions, thrownError){
	      	$('#fm_removeRelacion-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
	      }
	    });
	}
//<!-- ./relaciones -->  