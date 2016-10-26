
activelinks();

function activelinks(){
    //grupos :)
    activelinkeditgrupo(); 									//:)
    activelinkdelgrupo();										//:)
    activelinkaddrelacionPersonaGrupo();		//:)
    activelinkviewrelacionPersonaGrupo();		//:)
    activeLinkaddrecursoexistentetogrupo();	//:)
    
		//recursos
    activelinkaddnuevorecurso(); 		// :)


    
    activeLinkeditrecurso(); // :/



    activeLinkaddItemSinContenedor();//puestos-equipos



    
    
    activelinkeliminarrecurso();
    activelinkenabled();
    activelinkdisabled();
    
    
    
    
    //activeLinkeditpuesto();
    //activeLinkeditequipo();
    toggles();
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
	        console.log($respuesta);
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

//Muestra ventana modal para establecer relación persona-grupo
function activelinkaddrelacionPersonaGrupo(){ // :)
  $('.addRelacionUsuarioGrupo').on('click',function(e){
    e.preventDefault();
    $('#m_addPersonaGrupo_title_nombre').html($(this).data('nombre'));
    $('form#fm_addPersonaGrupo input[name="idgrupo"]').val($(this).data('id'));
    m_hideMsg();
    $('#m_addPersonaGrupo').modal('show');
  });
}

//Muestra ventana modal para establecer relación persona-grupo
function activelinkviewrelacionPersonaGrupo(){ // :)
  $('.delRelacionUsuarioGrupo, .verUsuarioConRelacionGrupo').on('click',function(e){
    e.preventDefault();
    $('#m_removePersonaGrupo_title_nombre').html($(this).data('nombre'));
    $('form#fm_removePersonaGrupo input[name="idgrupo"]').val($(this).data('id'));
    
    $('#fm_removePersonas-checkboxes').html('');
    $idgrupo = $(this).data('id');
    setPersonasGestoresGrupo($idgrupo);
    setPersonasAdministradoresGrupo($idgrupo);
    setPersonasValidadoresGrupo($idgrupo);

    m_hideMsg();
   	$('#m_removePersonaGrupo').modal('show');
  });
}

//Obtiene gestores para $idgrupo
function setPersonasGestoresGrupo($idgrupo){// :)

  $.ajax({
      type:"GET",
      url:"ajaxGetGestoresGrupo",
      data:{idgrupo : $idgrupo},
      success: function($respuesta){
      	$stringhtml = '<h3>Gestores</h3>';
      	if ($respuesta.error === true) {
      		$('#fm_removePersonas-checkboxes').append('Errores al recuperar usuarios gestores del grupo');
      	}
      	else{
      		$gestores = $respuesta.gestores;
	        if ($gestores.length > 0){
		    		$stringhtml = $stringhtml + '<div class="checkbox" id="fm_removePersona_inputgestores_id">';	
		    		$.each($gestores,function(index,gestor){
		    			$stringhtml = $stringhtml + '<label>';
			 				$stringhtml = $stringhtml + '<input class="" type="checkbox" name="gestores_id[]" value=' + gestor.id +'">';
			 				$stringhtml = $stringhtml + gestor.nombre + ' ' + gestor.apellidos + ' (' + gestor.username + ')';
							$stringhtml = $stringhtml + '</label><br />';
			 			});
			 		$stringhtml = $stringhtml + '</div>';
					}
					else $stringhtml = $stringhtml + '<p class="text-warning">No hay gestores para este grupo</p>';    
	        $('#fm_removePersonas-checkboxes').append($stringhtml);
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
      	$('#fm_removePersonas-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
}

//Obtiene administradores para $idgrupo
function setPersonasAdministradoresGrupo($idgrupo){// :)

  $.ajax({
      type:"GET",
      url:"ajaxGetAdministradoresGrupo",
      data:{idgrupo : $idgrupo},
      success: function($respuesta){
      	$stringhtml = '<h3>Administradores</h3>';
      	if ($respuesta.error === true) {
      		$('#fm_removePersonas-checkboxes').append('Errores al recuperar usuarios administradores del grupo');
      	}
      	else{
      		$administradores = $respuesta.administradores;
	    		if ($administradores.length > 0){
				    $stringhtml = $stringhtml + '<div class="checkbox" id="fm_removePersona_inputadministradores_id">';	
				    $.each($administradores,function(index,administrador){
				    	$stringhtml = $stringhtml + '<label>';
					 		$stringhtml = $stringhtml + '<input type="checkbox" name="administradores_id[]" value=' + administrador.id +'">';
					 		$stringhtml = $stringhtml + administrador.nombre + ' ' + administrador.apellidos + ' (' + administrador.username + ')';
							$stringhtml = $stringhtml + '</label><br />';
					 	});
					 	$stringhtml = $stringhtml + '</div>';
					}
			    else $stringhtml = $stringhtml + '<p class="text-warning">No hay administradores para este grupo</p>';
			    $('#fm_removePersonas-checkboxes').append($stringhtml);
			  }
    
      },
      error: function(xhr, ajaxOptions, thrownError){
      	$('#fm_removePersonas-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
}

//Obtiene validadores para $idgrupo
function setPersonasValidadoresGrupo($idgrupo){// :)

  $.ajax({
      type:"GET",
      url:"ajaxGetValidadoresGrupo",
      data:{idgrupo : $idgrupo},
      success: function($respuesta){
      	$stringhtml = '<h3>Validadores</h3>';
      	if ($respuesta.error === true) {
      		$('#fm_removePersonas-checkboxes').append('Errores al recuperar usuarios validadores del grupo');
      	}
      	else{
			    $validadores = $respuesta.validadores;
			    if ($validadores.length > 0){
				    $stringhtml = $stringhtml + '<div class="checkbox" id="fm_removePersona_inputvalidadores_id">';	
				    $.each($validadores,function(index,validador){
				    	$stringhtml = $stringhtml + '<label>';
					 		$stringhtml = $stringhtml + '<input type="checkbox" name="validadores_id[]" value=' + validador.id +'">';
					 		$stringhtml = $stringhtml + validador.nombre + ' ' + validador.apellidos + ' (' + validador.username + ')';
							$stringhtml = $stringhtml + '</label><br />';
					 	});
					 	$stringhtml = $stringhtml + '</div>';
				 	}
				 	else $stringhtml = $stringhtml + '<p class="text-warning">No hay validadores para este grupo</p>';
			    $('#fm_removePersonas-checkboxes').append($stringhtml);
			  }
		
      },
      error: function(xhr, ajaxOptions, thrownError){
      	$('#fm_removePersonas-checkboxes').append(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
}
  
//Delete recurso 
function activelinkeliminarrecurso(){ // :/
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
      console.log($(this).data('id'));
      $nombre = $(this).data('nombre');
      $('#mdrecurso_nombre').html($nombre);
      $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
      $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
      $('#fm_delrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre')); 
      m_hideMsg();
      $('#m_delrecurso').modal('show');
    }
  });
}
    
    
    
		
    



//Muestra ventana modal para eliminar relación persona-recurso
function activelinkremovepersonas(){ //????
  $('.removeUserWithRol').on('click',function(e){
	  e.preventDefault();
    showGifEspera();
    $('#m_removePersona_title_nombregrupo').html($(this).data('nombregrupo'));
    $('form#fm_removePersona input[name="idgrupo"]').val($(this).data('idgrupo'));
    m_hideMsg();
  	$.ajax({
      type:"GET",
      url:"htmlCheckboxPersonas",
      data:{idgrupo : $(this).data('idgrupo')},
      success: function($respuesta){
        //Añade input formulario en ventana modal
        hideGifEspera();
        $('form#fm_removePersona div#fm_removePersonas-checkboxes').html($respuesta.htmlCheckboxPersonas);
        $('#m_removePersona').modal('show');
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });
}





//Enabled recurso
function activelinkenabled(){
	$(".enabled").on('click',function(e){
    e.preventDefault();
    $('#m_enabled_nombre').html($(this).data('nombrerecurso'));
    $('form#fm_enabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
    $('#fm_enabledrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre'));
    m_hideMsg();
    $('#m_enabledrecurso').modal('show');
  });
}

//Ajax: Disabled recurso
function activelinkdisabled(){
  $(".disabled").on('click',function(e){
    e.preventDefault();
    $('#m_disabled_nombre').html($(this).data('nombrerecurso'));
    $('form#fm_disabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
    $('#fm_disabledrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre'));
    m_hideMsg();
    $('#m_disabledrecurso').modal('show');
  });
}
