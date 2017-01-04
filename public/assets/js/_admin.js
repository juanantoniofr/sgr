$(function(e){

 /* marca branch master2 */
  //Añadir nuevo
  //Recursos (Espacio // TipoEquipos)
    

    

    

    //Ajax: Salvar nuevo Puesto 
   /* $('#fm_addpuesto_save').on('click',function(e){
      e.preventDefault();
      showGifEspera();
      CKEDITOR.instances['fm_addpuesto_inputdescripcion'].updateElement();
      $data = $('form#fm_addpuesto').serialize() + '&descripcion=' + $('#fm_editpuesto_inputdescripcion').html();
      
      $.ajax({
        type: "POST",
        url: "addrecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error === true){
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_addpuesto');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_addpuesto').modal('hide');   
            showMsg($respuesta.msg);
            updateListadoRecursos($('#fm_addpuesto input[name="contenedor_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    });*/
    
    
      
    //Ajax: Salvar nuevo equipo
    /*$('#fm_addequipo_save').on('click',function(e){
      e.preventDefault();
      showGifEspera();
      CKEDITOR.instances['fm_addequipo_inputdescripcion'].updateElement();
      $data = $('form#fm_addequipo').serialize() + '&descripcion=' + $('#fm_addequipo_inputdescripcion').html();
      $.ajax({
        type: "POST",
        url: "addrecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error === true){
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_addequipo');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_addequipo').modal('hide');   
            showMsg($respuesta.msg);
            updateListadoRecursos($('#fm_addequipo input[name="tipoequipo_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    });*/
  // <!-- añadir nuevo -->
  

  //Editar
    
    

  
    //Ajax: Salvar edición de Puesto 
  /*$('#fm_editpuesto_save').on('click',function(e){
      e.preventDefault();
      showGifEspera();
      CKEDITOR.instances['fm_editpuesto_inputdescripcion'].updateElement();
      $data = $('form#fm_editpuesto').serialize() + '&descripcion=' + $('#fm_editpuesto_inputdescripcion').html();
      hideGifEspera();
      $.ajax({
        type: "POST",
        url: "updaterecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error === true){
            hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_editpuesto');//muestro errores en la ventana modal
          }
          else {
            hideGifEspera();
            $('#m_editpuesto').modal('hide');   
            showMsg($respuesta.msg);

            updateListadoRecursos($('#fm_editpuesto select[name="contenedor_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
  });*/

  //Edit Equipo *************
  //Ajax: Salvar edición de Equipo 
  /*$('#fm_editequipo_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    CKEDITOR.instances['fm_editequipo_inputdescripcion'].updateElement();
    $data = $('form#fm_editequipo').serialize() + '&descripcion=' + $('#fm_editequipo_inputdescripcion').html();
    $.ajax({
      type: "POST",
      url: "updaterecurso",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
            m_hideMsg();//Oculto mensaje de error en ventana modal.
            hideMsg();//Oculto mensajes en cuerpo página html
            m_showMsg($respuesta.errors,'#m_editequipo');//muestro errores en la ventana modal
        }
        else {
          hideGifEspera();
          $('#m_editequipo').modal('hide');   
          showMsg($respuesta.msg);
          updateListadoRecursos($('form#fm_editequipo select[name="contenedor_id"] option:selected').val()); 
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
      }
    });//<!-- ajax -->
  });*/
  
 

  //Obtine el listado de grupos 
  /*function setGrupos($idSelect,$idInput,$optionSelected){
    $.ajax({
      type:"GET",
      url:"htmlOptionGrupos",
      data:{},
      success:function($html){
            $($idSelect).html($html);
            if ($idInput != '') $($idInput).val($optionSelected);
      },
      error:function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
          }
      }); 
  }*/
 
});