$(function(e){

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
   
  //Lanza ajax function para salvar nuevo usuario
  $('#btnSalvarUser').on('click',function(e){ // :)
    e.preventDefault();
    showGifEspera();
    $data = $('form#nuevoUsuario').serialize();
   
    clearMsgErrorsModal();
    $.ajax({
      type: "POST",
      url: "salvarNuevoUsuario",
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
          console.log($respuesta.errors);
          $.each($respuesta.errors,function(index,value){
              $('#m_addusuario_input'+index).addClass('has-error');//resalta el campo de formulario con error
              console.log(value);
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
console.log($('form#filtrarUsuarios').serialize()+'&pagina='+$('span#numpagina').data('numpagina'));
    $.ajax({
      type: "GET",
      url: "ajaxGetUsuarios",
      data: $('form#filtrarUsuarios').serialize()+'&pagina='+$('span#numpagina').data('numpagina'),
      success: function($respuesta){
          $('div#tableusuarios').fadeOut().html($respuesta).fadeIn('slow');
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  }

  //show modal edit user
  $(".editUser").on('click',function(e){
    e.preventDefault();
    $.ajax({
      type:"GET",
      url:"user.html",
      data: { id : $(this).data('id')},
      success: function($user){
          $('form#formEditUser #username').html($user.username);
          $('form#formEditUser #select_rol').val($user.capacidad);
          $('form#formEditUser #select_colectivo').val($user.colectivo);
          $('form#formEditUser #select_estado').val($user.estado);
          $aDate = parseDate($user.caducidad,'-','en-EN');
          $('#datepickerUserEdit').val($aDate[0]+'-'+$aDate[1]+'-'+$aDate[2]);
          $('form#formEditUser input[name="nombre"]').val($user.nombre);
          $('form#formEditUser input[name="apellidos"]').val($user.apellidos);
          $('form#formEditUser input[name="email"]').val($user.email);
          $('form#formEditUser input[name="id"]').val($user.id);
          $('form#formEditUser textarea[name="observaciones"]').val($user.observaciones);
          $('#modalEditUser').modal('show');
        },
      error: function(xhr, ajaxOptions, thrownError){
         alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

  //Ajax edit user
  $('#modaleditUser').on('click',function(e){

    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "GET",
      url: "editarUsuario.html",
      data: $('form#formEditUser').serialize(),
      success: function($respuesta){
        //console.log($respuesta);
        if ($respuesta['exito'] == true){
            $('#modalEditUser').modal('hide');
            hideGifEspera();
            $('#msg').removeClass('alert-warning alert-danger alert-info').addClass('alert-success').html($respuesta['msg']).fadeOut('4000').fadeIn('4000');
            
            $('td#'+$respuesta["user"].id+'_colectivo').html($respuesta['user'].colectivo);
            $('td#'+$respuesta["user"].id+'_rol').html($respuesta['capacidad']);
            $('td#'+$respuesta["user"].id+'_apellidosnombre').html($respuesta['user'].apellidos + ', ' + $respuesta['user'].nombre);
            $('td#'+$respuesta["user"].id+'_observaciones').html($respuesta['user'].observaciones);
            $('small#'+$respuesta["user"].id+'_updated_at').html($respuesta['user'].updated_at);

            //estado
            $('td#'+$respuesta["user"].id+'_estado i').fadeOut();
            if($respuesta['user'].estado == '1' && !$respuesta['caducada']) {
              $('i#'+$respuesta["user"].id+'_activa').fadeIn();
            }
            if($respuesta['user'].estado == '0') {
              $('i#'+$respuesta["user"].id+'_desactiva').fadeIn();
            }
            if($respuesta['caducada']) {
              $('i#'+$respuesta["user"].id+'_caducada').fadeIn();
            }

            $('tr#'+$respuesta["user"].id+' td').fadeOut('8000').fadeIn('16000');
            //console.log($('tr#'+$respuesta["user"].id+'  > td#colectivo'));
            //console.log($('tr#'+$respuesta["user"].id+' td'));
          }
        //Hay errores de validación del formulario
        else {
            //reset
            $('.form-group').removeClass('has-error');//borrar errores anteriores
            $('.dataerror').each(function(){$(this).fadeOut();});
            //Show errors
            $errors = $respuesta.errors;
            $.each($errors,function(key,value){
              $('#editmodal_'+key).addClass('has-error');
              $('#editmodal_'+key+'_error').html(value).fadeIn('4000');
              });
          }//fin else
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  }); 
  

  $(".eliminarUsuario").on('click',function(e){// :/
    e.preventDefault();
    $('#infoUsuario').html($(this).data('infousuario'));
    $('#modal_deleteUser_tienereservas').fadeOut('fast');
    if ($(this).data('numreservas') > 0) {
      $('#modal_deleteUser_numreservas').html(' ' + $(this).data('numreservas') + ' ');
      $('#modal_deleteUser_tienereservas').fadeIn('fast');
    }
    $('a#btnEliminar').data('id',$(this).data('id')); //? 
    $('a#btnEliminar').attr('href', 'eliminaUser.html' + '?'+'id='+$(this).data('id')); //?
    $('#modalEliminaUsuario').modal('show');
  });
  //falta función ajax para eliminar usuario :/


  


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