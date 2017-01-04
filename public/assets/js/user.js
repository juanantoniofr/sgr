$(function(e){
/* marca branch master2 */
  activalinks();

  function activalinks() { // :)
    activaLinkEliminaUsusario();
    activaLinkEditaUsuario();
  }

  $("#addUser").on('click',function(e){ // :)
    e.preventDefault();
    $("#addUserDatePicker" ).datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showAnim: 'slideDown',
            dateFormat: 'd-m-yy',
            showButtonPanel: true,
            firstDay: 1,
            monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
            dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
    });
    m_hideMsg();
    $('#modalAddUser').modal('show');
  }); //:)
   
  //Add user
  $('#btnSalvarUser').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    $data = $('form#nuevoUsuario').serialize();
   
    m_hideMsg();
    $.ajax({
      type: "POST",
      url: "ajaxAddUsuario",
      data: $data,
      success: function($respuesta){
        if ($respuesta.error === false){
          $('#modalAddUser').modal('hide');
          hideGifEspera();
          getUsuarios();
          showMsg($respuesta.msg);
        }
        //Hay errores de validación del formulario
        else {
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
            $('#m_addusuario_input'+index).addClass('has-error');//resalta el campo de formulario con error
            $('#m_addusuario_textError_'+index).html(' &nbsp; ' + value);//añade texto de error a span alert-danger en ventana modal
              
          });
          $('#m_addusuario_msgError').fadeIn('8000');
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  }); 

  function getUsuarios(){ // :)
    $.ajax({
      type: "GET",
      url: "ajaxGetUsuarios",
      data: $('form#filtrarUsuarios').serialize()+'&pagina='+$('span#numpagina').data('numpagina'),
      success: function($respuesta){
          $('div#tableusuarios').fadeOut().html($respuesta).fadeIn('slow');
          activalinks();
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }

    });

  $("#addUser").on('click',function(e){
		e.preventDefault();
		$('#modalAddUser').modal('show');
  });
   
  //Lanza ajax function para salvar nuevo usuario
  $('#btnSalvarRecurso').on('click',function(e){
    e.preventDefault();
    $data = $('form#nuevoUsuario').serialize();
    $.ajax({
      type: "POST",
      url: "salvarNuevoUsuario",
      data: $data,
      success: function($respuesta){
        if ($respuesta['error'] == false){
          $('#modalAddUser').modal('hide');
          location.reload();
        }
        //Hay errores de validación del formulario
        else {
          //console.log($respuesta['errors']);
          //reset
          $('.has-error').removeClass('has-error');//borrar errores anteriores
          $('.spanerror').each(function(){$(this).slideUp();});
          //new errors
          $.each($respuesta['errors'],function(key,value){
            $('#fg'+key).addClass('has-error');
            $('#'+key+'_error > span#text_error').html(value);
            $('#'+key+'_error').fadeIn("slow");
            $('#'+key+'_error').fadeIn("slow");
            $('#aviso').slideDown("slow");
          });     
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  }); 

  function showGifEspera(){
		$('#espera').css('z-index','1000');
	}

	function hideGifEspera(){
		$('#espera').css('z-index','-1000');
	}

});