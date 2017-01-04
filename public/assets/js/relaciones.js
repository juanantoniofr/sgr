/* marca branch master2 */
$(function(e){ 
  //Ajax: añadir relacion(administrador//gestor//validador) persona-grupo || persona-recurso 
  $('#fm_addRelacion_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
        
    $.ajax({
      type: "POST",
      url:  "ajaxAddRelacion",
      data: $('form#fm_addRelacion').serialize(),
      success: function($respuesta){
            if ($respuesta.error === true) {
              hideGifEspera();
              m_hideMsg();//Oculto mensaje de error en ventana modal.
              hideMsg();//Oculto mensajes en cuerpo página html
              m_showMsg($respuesta.errors,'#m_addRelacion');//muestro errores en la ventana modal
             }
            else {
              hideGifEspera();
              $('#m_addRelacion').modal('hide');   
              showMsg($respuesta.msg);
              $id = $('form#fm_addRelacion input[name="grupoid"]').val();
              updateListadoRecursos('.ul_'+ $id);
            }   
      },
      error: function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

	//Ajax: elimina relación (administrador//gestor//validor) persona-grupo || persona-recurso
  $('#fm_removeRelacion_save').on('click',function(e){// :/
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "ajaxRemoveRelacion",
      data: $('form#fm_removeRelacion').serialize(),
      success: function($respuesta){
          if ($respuesta.error === true) {
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_removeRelacion');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_removeRelacion').modal('hide');   
            showMsg($respuesta.msg);
            $id = $('form#fm_removeRelacion input[name="grupoid"]').val();
            updateListadoRecursos('.ul_'+ $id);
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  });

});