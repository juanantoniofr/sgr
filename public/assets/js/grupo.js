$(function(e){
/* marca branch master2 */
	
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
    $data = $('form#fm_addgrupo').serialize();
    $.ajax({
      type:"POST",
      url:"addgrupo",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
          m_hideMsg();//Oculto mensaje de error en ventana modal.
          hideMsg();//Oculto mensajes en cuerpo página html
          m_showMsg($respuesta.errors,'#m_addgrupo');//muestro errores en la ventana modal
        }
        else {
          hideGifEspera();
          $('#m_addgrupo').modal('hide');
          updateListadoRecursos();    
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
        console.log($respuesta);
        if($respuesta.error === true){  
          hideGifEspera();
          m_hideMsg();//Oculto mensaje de error en ventana modal.
          hideMsg();//Oculto mensajes en cuerpo página html
          m_showMsg($respuesta.errors,'#m_delgrupo');//muestro errores en la ventana modal
        }
        else {
          hideGifEspera();
          $('#m_delgrupo').modal('hide');
          updateListadoRecursos();   
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
          m_hideMsg();//Oculto mensaje de error en ventana modal.
          hideMsg();//Oculto mensajes en cuerpo página html
          m_showMsg($respuesta.errors,'#m_editgrupo');//muestro errores en la ventana modal
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
 			 		$grupo_id = $('form#fm_addrecursotogrupo input[name="grupo_id"]').val();
          updateListadoRecursos('#ulrecursosdelgrupo_'+ $grupo_id);
      	}
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

 

});