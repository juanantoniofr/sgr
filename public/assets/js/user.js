$(function(e){

  activalinks();

  function activalinks() { // :)
    activaLinkEliminaUsusario();
    activaLinkEditaUsuario();
  }

  function clearMsgErrorsModal(){ // :)
    $('.modal_MsgError').fadeOut();
    $('.modal_divinput').removeClass('has-error');
    $('.modal_spantexterror').html('');
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
    clearMsgErrorsModal();
    $('#modalAddUser').modal('show');
  }); //:)
   
  //Add user
  $('#btnSalvarUser').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    $data = $('form#nuevoUsuario').serialize();
   
    clearMsgErrorsModal();
    $.ajax({
      type: "POST",
      url: "ajaxAddUsuario",
      data: $data,
      success: function($respuesta){
        if ($respuesta.error === false){
          $('#modalAddUser').modal('hide');
          hideGifEspera();
          getUsuarios();
          $('#msg').fadeOut('slow').html($respuesta.msg).fadeIn('slow');
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
  }

  function activaLinkEliminaUsusario(){// :)
    $(".eliminarUsuario").on('click',function(e){
      e.preventDefault();
      $('#infoUsuario').html($(this).data('infousuario'));
      $('#modal_deleteUser_tienereservas').fadeOut('fast');
      if ($(this).data('numreservas') > 0) {
        $('#modal_deleteUser_numreservas').html(' ' + $(this).data('numreservas') + ' ');
        $('#modal_deleteUser_tienereservas').fadeIn('fast');
      }
      $('form#fm_eliminausuario input[name="id"]').val($(this).data('id')); 
      $('#modalEliminaUsuario').modal('show');
    });
  }
  //Delete user
  $('#fm_eliminausuario_save').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    $data = $('form#fm_eliminausuario').serialize();
    clearMsgErrorsModal();
    $.ajax({
      type: "POST",
      url: "ajaxEliminausuario",
      data: $data,
      success: function($respuesta){
        if ($respuesta.error === false){
          $('#modalEliminaUsuario').modal('hide');
          hideGifEspera();
          getUsuarios();
          $('#msg').fadeOut('slow').html($respuesta.msg).fadeIn('slow');
        }
        //Hay errores de validación del formulario
        else {
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
              $('#m_eliminausuario_textError_'+index).html(' &nbsp; ' + value);
          });
          $('#m_eliminausuario_msgError').fadeIn('8000');
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

  function activaLinkEditaUsuario(){ // :)
    $(".editUser").on('click',function(e){
      e.preventDefault();
      $('#fm_editUser_username').html($(this).data('username'));
      $('form#formEditUser input[name="nombre"]').val($(this).data('nombre'));
      $('form#formEditUser input[name="apellidos"]').val($(this).data('apellidos'));
      $('form#formEditUser input[name="email"]').val($(this).data('email'));
     
      $('form#formEditUser textarea[name="observaciones"]').val($(this).data('observaciones'));
      
      $('form#formEditUser select[name="capacidad"]').val( $(this).data('capacidad') );
      $('form#formEditUser select[name="colectivo"]').val( $(this).data('colectivo') );
      $('form#formEditUser select[name="estado"]').val($(this).data('estado'));
     
      $("#datepickerUserEdit" ).datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        showAnim: 'slideDown',
        dateFormat: 'd-m-yy',
        showButtonPanel: true,
        firstDay: 1,
        monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
        dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
      });
      $('#datepickerUserEdit').val( $(this).data('caducidad') ); 
      
      $('form#formEditUser input[name="id"]').val($(this).data('id')); 
      clearMsgErrorsModal();
      $('#modalEditUser').modal('show');
    });  
  }
  
  //Ajax edit user
  $('#btnEditUser').on('click',function(e){ // :)

    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "GET",
      url: "ajaxEditUsuario",
      data: $('form#formEditUser').serialize(),
      success: function($respuesta){
        if ($respuesta.error === false){ //no hay errores
          $('#modalEditUser').modal('hide');
          hideGifEspera();
          getUsuarios();
          $('#msg').fadeOut('slow').html($respuesta.msg).fadeIn('slow');
        }
        //Hay errores de validación del formulario
        else {
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
            $('#m_editusuario_input'+index).addClass('has-error');//resalta el campo de formulario con error
            alert(index + value);
            $('#m_editusuario_textError_'+index).html(' &nbsp; ' + value);//añade texto de error a span alert-danger en ventana modal

              
          });
          $('#m_editusuario_msgError').fadeIn('8000');
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  }); 
  

 

  

  /*
  function parseDate(strFecha,$delimiter,$locale) {
    
    $delimiter  = typeof $delimiter !== 'undefined' ? $delimiter : '-';
      $locale   = typeof $locale    !== 'undefined' ? $locale : 'es-ES';

    var sfecha = $.trim(strFecha);
    var aFecha = sfecha.split($delimiter);
    
    if ($locale == 'es-ES'){
      var day = $.trim(aFecha[0]);                  
      var month = $.trim(aFecha[1]);
      var year = $.trim(aFecha[2]);
    }
    else if ($locale = 'en-EN'){
      var day = $.trim(aFecha[2]);                  
      var month = $.trim(aFecha[1]);
      var year = $.trim(aFecha[0]); 
    }
  
    var aDate = [day,month,year];

    return aDate;
  }
*/
  $('form#formEditUser .datepicker').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        showAnim: 'slideDown',
        dateFormat: 'dd-mm-yy',
        showButtonPanel: true,
        firstDay: 1,
        monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
        dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
      });


  function showGifEspera(){
    $('#espera').css('display','inline').css('z-index','1000');
  }

  function hideGifEspera(){
    $('#espera').css('display','inline').css('z-index','-1000');
  }

});