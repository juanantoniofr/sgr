$(function(e){  
  //Ajax: Añadir Recurso (puesto/equipo) a recurso contenedor 
  $('#fm_addpuestoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $idform = $('#fm_addpuestoExistente_save').data('idform');
    $idmodal = $('#fm_addpuestoExistente_save').data('idmodal');
    ajaxadditem($idform,$idmodal);    
  });
  $('#fm_addequipoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $idform = $(this).data('idform');
    $idmodal = $(this).data('idmodal');
    ajaxadditem($idform,$idmodal);    
  });

  function ajaxadditem($idform,$idmodal){
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

          updateListadoRecursos('ulitems_'+$('form#'+$idform+' input[name="contenedor_id"]').val());
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  }

  //Ajax: Añadir nuevo recurso 
  $('#fm_addrecurso_save').on('click',function(e){
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
            if ($('form#fm_addrecurso input[name="tipopadre"]').val() == 'grupo'){
              $grupo_id = $('form#fm_addrecurso select[name="contenedor_id"] option:selected').val();
              updateListadoRecursos('#ulrecursosdelgrupo_'+ $grupo_id);
            }
            else if ($('form#fm_addrecurso input[name="tipopadre"]').val() == 'recurso'){
              $recurso_id = $('form#fm_addrecurso select[name="contenedor_id"] option:selected').val();
              updateListadoRecursos('#ulitems__'+ $recurso_id);
            }
            
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    }); 

});