$(function(e){  
   
  $('#fm_addpuestoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $idform = $('#fm_addpuestoExistente_save').data('idform');
    $idmodal = $('#fm_addpuestoExistente_save').data('idmodal');
    ajaxAddItemExistente($idform,$idmodal);    
  });
  $('#fm_addequipoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $idform = $(this).data('idform');
    $idmodal = $(this).data('idmodal');
    ajaxAddItemExistente($idform,$idmodal);    
  });
  //Ajax: Añadir Recurso (puesto/equipo) a recurso contenedor
  function ajaxAddItemExistente($idform,$idmodal){// :)
    $.ajax({
      type: "POST",
      url:  "ajaxAddItemExistente",
      data: $('form#'+$idform).serialize(),
      success: function($respuesta){
        if($respuesta.error === true){ 
          hideGifEspera(); 
          $('#'+$idmodal).modal('hide');   
          showMsg($respuesta.errors['contenedor_id']);
        }
        else {
          hideGifEspera();
          $('#'+$idmodal).modal('hide');   
          //Elimina checkBox seleccionados
          $('form#'+$idform+' [name="idrecursos[]"]').each(function(key,item){
            if ($(item).is(':checked')) { 
              $('#divcheckboxid_'+$(item).val()).remove(); }
            }
          );
          showMsg($respuesta['msg']);
          $id_padre = $('form#'+$idform+' input[name="contenedor_id"]').val();
          updateListadoRecursos('.ul_'+ $id_padre);
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  }

  //Ajax: Añadir nuevo recurso 
  $('#fm_addrecurso_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    CKEDITOR.instances['fm_addrecurso_inputdescripcion'].updateElement();
    $data = $('form#fm_addrecurso').serialize() + '&descripcion=' + $('#fm_addrecurso_inputdescripcion').html();
    $.ajax({
        type: "POST",
        url: "AjaxAddNuevoRecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error === true){
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_addrecurso');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_addrecurso').modal('hide');   
            showMsg($respuesta.msg);
            $id_padre = $('form#fm_addrecurso select[name="contenedor_id"] option:selected').val();
            updateListadoRecursos('.ul_'+ $id_padre);
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
  }); 
  

  //Ajax: Salvar edición recurso
  $('#fm_editrecurso_save').on('click',function(e){ // :)
    e.preventDefault();
    CKEDITOR.instances['fm_editrecurso_inputdescripcion'].updateElement();
    $data = $('form#fm_editrecurso').serialize();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "ajaxEditRecurso",
        data: $data,
        success: function($respuesta){
          console.log($respuesta);
          if($respuesta.error == true){ 
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_editrecurso');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_editrecurso').modal('hide');   
            showMsg($respuesta['msg']);
            $id_padre = $('form#fm_editrecurso select[name="padre_id"] option:selected').val();
            console.log($id_padre);
            updateListadoRecursos('.ul_'+ $id_padre);
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  }); 

  //Ajax: Eliminar recurso
  $('#fm_delrecurso_save').on('click',function(e){ // :/
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "ajaxDelRecurso",
      data: $('form#fm_delrecurso').serialize(),
        success: function($respuesta){
          if($respuesta.error === true){  
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_delrecurso');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_delrecurso').modal('hide');   
            showMsg($respuesta.msg);
            console.log($('#fm_delrecurso_save').data('idrecursopadre'));
            updateListadoRecursos('.ul_'+ $('#fm_delrecurso_save').data('idrecursopadre'));
            $('#fm_delrecurso_save').data('idrecursopadre',""); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  });

});