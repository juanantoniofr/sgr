$(function(e){

	//Muestra ventana modal para crear nuevo grupo de recursos
  $('#btnNuevoGrupo').on('click',function(e){ // :)
    e.preventDefault();
    m_hideMsg();
    $('#m_addgrupo').modal('show');
  });
  
  //Ajax: add new grupo
  $('#fm_addgrupo_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    CKEDITOR.instances['fm_addgrupo_inputdescripcion'].updateElement();
    $data = $('form#fm_addgrupo').serialize() + '&descripcion=' + $('#fm_addgrupo_inputdescripcion').html();
    $.ajax({
      type:"POST",
      url:"addgrupo",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
            $('.divmodal_msgError').html('').fadeOut();
            $('#fm_addgrupo_input'+index).addClass('has-error');//resalta el campo de formulario con error
            $('#fm_addgrupo_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_addgrupo_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_addgrupo').modal('hide');   
          showMsg($respuesta.msg);
        }
      },
      error:function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<--./ajax-->
  });

	//Ajax: delete grupo
  $('#fm_delgrupo_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "delgrupo",
      data: $('form#fm_delgrupo').serialize(),
      success: function($respuesta){
        if($respuesta.error === true){  
          hideGifEspera(); 
          $.each($respuesta.errors,function(index,value){
            $('.divmodal_msgError').html('').fadeOut();
            $('#fm_editrecurso_input'+index).addClass('has-error');//resalta el campo de formulario con error
            $('#fm_editrecurso_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_editrecurso_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_delgrupo').modal('hide');   
          showMsg($respuesta.msg);
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  });
	
	//Ajax: edit grupo
  $('#fm_editgrupo_save').on('click',function(e){// :)
    e.preventDefault();
    CKEDITOR.instances['fm_editgrupo_inputdescripcion'].updateElement();
    $data = $('form#fm_editgrupo').serialize();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "editgrupo",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){  
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_editgrupo_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_editgrupo_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_editgrupo_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_editgrupo').modal('hide');   
          showMsg($respuesta.msg);
          $grupo_id = $('form#fm_editgrupo input[name="grupo_id"]').val();
          updateListadoRecursos('#ulrecursosdelgrupo_'+ $grupo_id); 
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

	//Ajax: Añadir Recurso existente (espacio/tipoequipo) a Grupo 
  $('#fm_addrecursotogrupo_save').on('click',function(e){// :)
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "ajaxAddrecursoSingrupo",
      data: $('form#fm_addrecursotogrupo').serialize(),
      success: function($respuesta){
        if($respuesta.error === true){ 
          hideGifEspera(); 
          $('#m_addrecursotogrupo').modal('hide');
         	showMsg($respuesta.errors['grupo_id']);
        }
        else {
          hideGifEspera();
          $('#m_addrecursotogrupo').modal('hide');   
          //Elimina checkBox seleccionados
          $('form#fm_addrecursotogrupo [name="idrecursos[]"]').each(function(key,item){
    				if ($(item).is(':checked')) { 
    					$('#divcheckboxid_'+$(item).val()).remove(); }
    				}
    			);
 			 		showMsg($respuesta['msg']);
 			 		$grupo_id = $('form#addrecursotogrupo input[name="grupo_id"]').val();
          updateListadoRecursos('#ulrecursosdelgrupo_'+ $grupo_id);
      	}
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

  //Ajax: añadir relacion(administrador//gestor//validador) persona-grupo 
  $('#fm_addPersonaGrupo_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
        
    $.ajax({
      type: "POST",
      url:  "ajaxAddrelacionUsuarioGrupo",
      data: $('form#fm_addPersonaGrupo').serialize(),
      success: function($respuesta){
            if ($respuesta.error === true) {
              hideGifEspera();
              m_hideMsg();//Oculto mensaje de error en ventana modal.
              hideMsg();//Oculto mensajes en cuerpo página html
              m_showMsg($respuesta.errors,'#m_addPersonaGrupo');//muestro errores en la ventana modal
             }
            else {
              hideGifEspera();
              $('#m_addPersonaGrupo').modal('hide');   
              showMsg($respuesta.msg);
              $grupo_id = $('form#addPersonaGrupo input[name="idgrupo"]').val();
              updateListadoRecursos('#ulrecursosdelgrupo_'+ $grupo_id); 
            }   
      },
      error: function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

	//Ajax: elimina relación (administrador//gestor//validor) persona-grupo
  $('#fm_removePersonaGrupo_save').on('click',function(e){// :)
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "ajaxRemoverelacionUsuarioGrupo",
      data: $('form#fm_removePersonaGrupo').serialize(),
      success: function($respuesta){
          if ($respuesta.error === true) {
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_removePersonaGrupo');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_removePersonaGrupo').modal('hide');   
            showMsg($respuesta.msg);
            updateListadoRecursos(); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  });

});