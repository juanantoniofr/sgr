$(function(e){

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
  

  $(".eliminarUsuario").on('click',function(e){
        e.preventDefault();
        $('#infoUsuario').html($(this).data('infousuario'));
        $('a#btnEliminar').data('id',$(this).data('id'));
        $('a#btnEliminar').attr('href', 'eliminaUser.html' + '?'+'id='+$(this).data('id'));
        $('#modalEliminaUsuario').modal('show');
    });

   $("#addUser").on('click',function(e){
		e.preventDefault();
		$('#modalAddUser').modal('show');
   });
   
   //Lanza ajax function para salvar nuevo usuario
    $('#btnSalvarUser').on('click',function(e){
        e.preventDefault();
        $data = $('form#nuevoUsuario').serialize();
        $.ajax({
            type: "POST",
            url: "salvarNuevoUsuario",
            data: $data,
            success: function($respuesta){
                if ($respuesta['error'] == false){
                    $('#modalAddUser').modal('hide');
                    //location.reload();
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