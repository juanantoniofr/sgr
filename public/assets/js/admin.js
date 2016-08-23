$(function(e){

   $('.toggle').hover(function (event) {
        event.preventDefault();
        $(this).css('text-decoration','none');
        var target = $(this).attr('href');
        $(target).fadeIn('fast');
    });
   $('.listitem').hover(function (event) {
        event.preventDefault();
        $('.opcionesGrupo').fadeOut('fast');
    });
    
    $('.listadorecursos').click(function (event) {
        event.preventDefault();
        $(this).css('text-decoration','none');
        var target = $(this).data('divrecursosid');
        console.log(target);
        $(target).fadeIn('fast');
    });
  

  //Añadir nuevo
    //Recursos (Espacio // TipoEquipos)
    //Muestra ventana modal Addrecurso
    $("#btnNuevoRecurso").on('click',function(e){
      e.preventDefault();
      hideMsg();
      showGifEspera();
      setGrupos('#fm_addrecurso_optionsGrupos');
      hideGifEspera();
      $('#m_addrecurso').modal('show');        
    });

    //Ajax: Salvar nuevo recurso 
    $('#fm_addrecurso_save').on('click',function(e){
      e.preventDefault();
      showGifEspera();
      CKEDITOR.instances['fm_addrecurso_inputdescripcion'].updateElement();
      $data = $('form#fm_addrecurso').serialize() + '&descripcion=' + $('#fm_addrecurso_inputdescripcion').html();
     
      $.ajax({
        type: "POST",
        url: "addrecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error === true){
            hideGifEspera();
            $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_addrecurso_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_addrecurso_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
            });
            $('#fm_addrecurso_textError').fadeIn('8000');
          }
          else {
            hideGifEspera();
            $('#m_addrecurso').modal('hide');   
            showMsg($respuesta.msg);
            getListado(); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    }); 

    //Puestos
    function activelinkaddpuesto(){
      $(".linkAddPuesto").on('click',function(e){
        e.preventDefault();
        $('#m_addpuesto_title_nombrerecurso').html($(this).data('nombrerecurso'));
        $('form#fm_addpuesto input[name="contenedor_id"]').val($(this).data('contenedorid'));
        $('form#fm_addpuesto input[name="nombre"]').val('');
        hideMsg();
        $('#m_addpuesto').modal('show');
      });
    }

    //Ajax: Salvar nuevo Puesto 
    $('#fm_addpuesto_save').on('click',function(e){
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
            $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_addpuesto_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_addpuesto_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
            });
            $('#fm_addpuesto_textError').fadeIn('8000');
          }
          else {
            hideGifEspera();
            $('#m_addpuesto').modal('hide');   
            showMsg($respuesta.msg);
            getListado($('#fm_addpuesto input[name="contenedor_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    });
    
    //Equipos
    function activelinkaddequipo(){
      $(".linkAddEquipo").on('click',function(e){
        e.preventDefault();
        $('#m_addequipo_title_nombrerecurso').html($(this).data('nombrerecurso'));
        $('form#fm_addequipo input[name="contenedor_id"]').val($(this).data('contenedorid'));
        $('form#fm_addequipo input[name="nombre"]').val('');
        hideMsg();
        $('#m_addequipo').modal('show');
      });
    }
      
    //Ajax: Salvar nuevo equipo
    $('#fm_addequipo_save').on('click',function(e){
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
            $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_addequipo_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_addequipo_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
            });
            $('#fm_addequipo_textError').fadeIn('8000');
          }
          else {
            hideGifEspera();
            $('#m_addequipo').modal('hide');   
            showMsg($respuesta.msg);
            getListado($('#fm_addequipo input[name="tipoequipo_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
    });
  // <!-- añadir nuevo -->
  

  //Editar
    //Edit recurso ************
    function activeLinkeditrecurso(){
      //Muestra ventana modal editRecurso
      $(".linkEditRecurso").on('click',function(e){
        e.preventDefault();
        showGifEspera();
        $.ajax({
          type: "GET",
          url:  "getrecurso",
          data: {idrecurso:$(this).data('idrecurso')},
          success: function($respuesta){
            hideGifEspera();
            $recurso = $respuesta.recurso;
            CKEDITOR.instances['fm_editrecurso_inputdescripcion'].setData($recurso.descripcion);
            CKEDITOR.instances['fm_editrecurso_inputdescripcion'].updateElement();
            $('#fm_editrecurso input[name="id"]').val($recurso.id);
            $('#fm_editrecurso input[name="nombre"]').val($recurso.nombre);
            $('#fm_editrecurso input[name="id_lugar"]').val($recurso.id_lugar);
            $('#fm_editrecurso_optionsGrupos').html($respuesta.listadogrupos);
            $('#fm_editrecurso select[name="grupo_id"]').val($recurso.grupo_id);
            $('#fm_editrecurso select[name="tipo"]').val($recurso.tipo);
            $('#fm_editrecurso select[name="modo"]').val($.parseJSON($recurso.acl).m);    
            $arrayRoles = $.parseJSON($recurso.acl).r.split(',');
            
            $('#fm_editrecurso input[type="checkbox"]').prop( "checked", false );
            $.each($arrayRoles,function(index,value){
              $('#fm_editrecurso input#fm_editrecurso_roles'+value).prop( "checked", true );
            });
            hideMsg();
            $('#m_editrecurso').modal('show');
          },
          error: function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
          }
        });//<!-- ajax -->
      });
    } 
    //Ajax: Salvar edición recurso
    $('#fm_editrecurso_save').on('click',function(e){
      e.preventDefault();
      CKEDITOR.instances['fm_editrecurso_inputdescripcion'].updateElement();
      $data = $('form#fm_editrecurso').serialize() + '&descripcion=' + $('#fm_editrecurso_inputdescripcion').html();
      showGifEspera();
      $.ajax({
        type: "POST",
        url:  "updaterecurso",
        data: $data,
        success: function($respuesta){
          if($respuesta.error == true){ 
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
            $('#m_editrecurso').modal('hide');   
            showMsg($respuesta['msg']);
            getListado($('#fm_editrecurso input[name="id"]').val());
            
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
    });  

    //Edit Puesto*************
  function activeLinkeditpuesto(){
    //Muestra ventana modal editpuesto
    $(".linkEditPuesto").on('click',function(e){
      e.preventDefault();
      showGifEspera();
      //$('#fm_editpuesto select[name="contenedor_id"] option:selected').text($(this).data('nombrecontenedor'));
      $.ajax({
        type: "GET",
        url:  "getrecurso",
        data: {idrecurso:$(this).data('idrecurso')},
        success: function($respuesta){
          $recurso = $respuesta.recurso;
          hideGifEspera();
          $('#m_editpuesto_title_nombrepuesto').html($recurso.nombre)
          CKEDITOR.instances['fm_editpuesto_inputdescripcion'].setData($recurso.descripcion);
          CKEDITOR.instances['fm_editpuesto_inputdescripcion'].updateElement();
          $('#fm_editpuesto input[name="id"]').val($recurso.id);
          $('#fm_editpuesto input[name="nombre"]').val($recurso.nombre);
          $('#fm_editpuesto input[name="id_lugar"]').val($recurso.id_lugar);
          $('#fm_editpuesto select[name="modo"]').val($.parseJSON($recurso.acl).m); 
          $('#fm_editpuesto select[name="contenedor_id"]').html($respuesta.listadocontenedores);   
          $('#fm_editpuesto select[name="contenedor_id"] option:selected').val($recurso.contenedor_id);

          $arrayRoles = $.parseJSON($recurso.acl).r.split(',');
          
          $('#fm_editpuesto input[type="checkbox"]').prop( "checked", false );
          $.each($arrayRoles,function(index,value){
            $('#fm_editpuesto input#fm_editpuesto_roles'+value).prop( "checked", true );
          });
          hideMsg();
          $('#m_editpuesto').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });//<!-- ajax -->
    });
  }
    //Ajax: Salvar edición de Puesto 
  $('#fm_editpuesto_save').on('click',function(e){
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
            $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_editpuesto_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_editpuesto_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
            });
            $('#fm_editpuesto_textError').fadeIn('8000');
          }
          else {
            hideGifEspera();
            $('#m_editpuesto').modal('hide');   
            showMsg($respuesta.msg);
            getListado($('#fm_editpuesto select[name="contenedor_id"]').val()); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
        }
      });//<!-- ajax -->
  });

  //Edit Equipo *************
  //Ajax: Salvar edición de Equipo 
  $('#fm_editequipo_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    CKEDITOR.instances['fm_editequipo_inputdescripcion'].updateElement();
    $data = $('form#fm_editequipo').serialize() + '&descripcion=' + $('#fm_editequipo_inputdescripcion').html();
    console.log($data);
    $.ajax({
      type: "POST",
      url: "updaterecurso",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
            $('.divmodal_msgError').html('').fadeOut();
            $('#fm_editequipo_input'+index).addClass('has-error');//resalta el campo de formulario con error
            $('#fm_editequipo_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_editequipo_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_editequipo').modal('hide');   
          showMsg($respuesta.msg);
          getListado($('form#fm_editequipo select[name="contenedor_id"] option:selected').val()); 
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
      }
    });//<!-- ajax -->
  });
  
  function activeLinkeditequipo(){
    //Muestra ventana modal editequipo
    $(".linkEditEquipo").on('click',function(e){
      e.preventDefault();
      showGifEspera();
      //$('#fm_editequipo select[name="contenedor_id"] option:selected').text($(this).data('modeloequipo'));
      $.ajax({
        type: "GET",
        url:  "getrecurso",
        data: {idrecurso:$(this).data('idrecurso')},
        success: function($respuesta){
          $recurso = $respuesta.recurso;
          hideGifEspera();
          $('#m_editequipo_title_nombreequipo').html($recurso.nombre)
          CKEDITOR.instances['fm_editequipo_inputdescripcion'].setData($recurso.descripcion);
          CKEDITOR.instances['fm_editequipo_inputdescripcion'].updateElement();
          $('#fm_editequipo input[name="id"]').val($recurso.id);
          $('#fm_editequipo input[name="nombre"]').val($recurso.nombre);
          $('#fm_editequipo input[name="id_lugar"]').val($recurso.id_lugar);
          $('#fm_editequipo select[name="modo"]').val($.parseJSON($recurso.acl).m);
          $('#fm_editequipo select[name="contenedor_id"]').html($respuesta.listadocontenedores);
          $('#fm_editequipo select[name="contenedor_id"] option:selected').val($recurso.contenedor_id);    
          $arrayRoles = $.parseJSON($recurso.acl).r.split(',');
          $('#fm_editequipo input[type="checkbox"]').prop( "checked", false );
          $.each($arrayRoles,function(index,value){
            $('#fm_editequipo input#fm_editequipo_roles'+value).prop( "checked", true );
          });
          hideMsg();
          $('#m_editequipo').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });//<!-- ajax -->
    });
  }

  //Obtine el listado de grupos 
  function setGrupos($idSelect,$idInput,$optionSelected){
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
  }
  
  //Delete recurso **************
  function activelinkeliminarrecurso(){
    $(".linkEliminaRecurso").on('click',function(e){
      e.preventDefault();
      if($(this).data('numeroelementos') > 0){
        $('#malert_text').html('No se pueden eliminar un recurso con elementos asignados.');
        $('#m_alert').modal('show');
      }
      else if($(this).data('numeroeventos') > 0){
        $('#malert_text').html('No se pueden eliminar un recurso con reservas programadas.');
        $('#m_alert').modal('show');
      }
      else {
        $idrecurso = $(this).data('idrecurso');
        $nombre = $(this).data('nombre');
        $('#mdrecurso_nombre').html($nombre);
        $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
        $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
        $('#fm_delrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre')); 
        hideMsg();
        $('#m_delrecurso').modal('show');
      }
    });
  }
  
  //Ajax: Delete recurso
  $('#fm_delrecurso_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "delrecurso",
      data: $('form#fm_delrecurso').serialize(),
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
            $('#m_delrecurso').modal('hide');   
            showMsg($respuesta.msg);
            getListado($('#fm_delrecurso_save').data('idrecursopadre'));
            $('#fm_delrecurso_save').data('idrecursopadre',""); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  });
  
  //Temporal (añade recursos que ya existen a grupos nuevos)
  //Ajax: Añadir Recurso a Grupo Temporal
  $('#fm_addrecursotogrupo_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "addrecursotogrupo",
      data: $('form#fm_addrecursotogrupo').serialize(),
      success: function($respuesta){
          if($respuesta.error == true){ 
            hideGifEspera(); 
            $('.divmodal_msgError').html('').fadeOut();
            $('#fm_addrecursotogrupo_textError').append($respuesta.smg).fadeIn('8000');//añade texto de error a div alert-danger en ventana modal
          }
          else {
            hideGifEspera();
            $('#m_addrecursotogrupo').modal('hide');   
            showMsg($respuesta['msg']);
            getListado(); 
          }
      },
      error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });
    
  function activeLinkaddrecursotogrupo(){
    //Muestra ventana modal addrecursotogrupo
    $(".addrecursotogrupo").on('click',function(e){
      e.preventDefault();
      showGifEspera();
      $('#m_addrecursotogrupo_nombre').html($(this).data('nombre'));
      $('form#fm_addrecursotogrupo input[name="grupo_id"]').val($(this).data('idgrupo'));
      $('form#fm_addrecursotogrupo input[name="tipogrupo"]').val($(this).data('tipogrupo'));
      $.ajax({
        type: "GET",
        url:  "recursosSinGrupo",
        data: {idgrupo:$(this).data('idgrupo'),tipogrupo:$(this).data('tipogrupo')},
        success: function($respuesta){
          hideGifEspera();
          if($respuesta.error == true){ 
            $('.divmodal_msgError').html('').fadeOut();
            $('#fm_fm_addrecursotogrupo_textError').append($respuesta.msg).fadeIn('8000');
          }
          else{
            $('#m_addrecursotogrupo span#recursosSinGrupo').html($respuesta.html);
            hideMsg();
          }
          $('#m_addrecursotogrupo').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
    });
  }
  
  //Añade puestos que ya existen a espacios
  //Ajax: Añadir Puesto a espacio 
  $('#fm_addPuestoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "addpuestoaespacio",
      data: $('form#fm_addPuestoExistente').serialize(),
      success: function($respuesta){
        if($respuesta.error == true){ 
          hideGifEspera(); 
          $('.divmodal_msgError').html('').fadeOut();
          $('#fm_addPuestoExistente_textError').append($respuesta.msg).fadeIn('8000');//añade texto de error a div alert-danger en ventana modal
        }
        else {
          hideGifEspera();
          $('#m_addPuestoExistente').modal('hide');   
          showMsg($respuesta['msg']);
          getListado($('form#fm_addPuestoExistente input[name="espacio_id"]').val());
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  });

  //Temporal (añade equipos que ya existen a tipoequipos)
  //Ajax: Añadir Puesto a espacio 
  $('#fm_addEquipoExistente_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "addequipoamodelo",
      data: $('form#fm_addEquipoExistente').serialize(),
      success: function($respuesta){
        if($respuesta.error == true){ 
          hideGifEspera(); 
          $('.divmodal_msgError').html('').fadeOut();
          $('#fm_addEquipoExistente_textError').append($respuesta.smg).fadeIn('8000');//añade texto de error a div alert-danger en ventana modal
        }
        else {
          hideGifEspera();
          $('#m_addEquipoExistente').modal('hide');   
          showMsg($respuesta['msg']);
          getListado($('form#fm_addEquipoExistente input[name="tipoequipo_id"]').val());
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  });
    
  function activeLinkaddPuestoExistente(){
    //Muestra ventana modal addPuestoExistente
    $(".linkaddPuestoExistente").on('click',function(e){
      e.preventDefault();
      showGifEspera();
      $('#m_addPuestoExistente_nombre').html($(this).data('nombre'));
      $('form#fm_addPuestoExistente input[name="espacio_id"]').val($(this).data('idespacio'));
      $.ajax({
        type: "GET",
        url:  "getpuestosSinEspacio",
        data: {id:$(this).data('idespacio')},
        success: function($html){
          hideGifEspera();
          $('#m_addPuestoExistente span#PuestoSinEspacio').html($html);
          hideMsg();
          $('#m_addPuestoExistente').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });//<!-- ajax -->
    });
  }

  //Equipo Existente
  function activeLinkaddEquipoExistente(){
    //Muestra ventana modal addEquipoExistente
    $(".linkaddEquipoExistente").on('click',function(e){
      e.preventDefault();
      showGifEspera();
      $('#m_addEquipoExistente_nombre').html($(this).data('nombre'));
      $('form#fm_addEquipoExistente input[name="tipoequipo_id"]').val($(this).data('idtipoequipo'));
      $.ajax({
        type: "GET",
        url:  "getequiposSinModelo",
        data: {id:$(this).data('idtipoequipo')},
        success: function($html){
          hideGifEspera();
          $('#m_addEquipoExistente span#EquipoSinModelo').html($html);
          hideMsg();
          $('#m_addEquipoExistente').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });//<!-- ajax -->
    });
  }

  //Delete grupo
  //Ajax: Delete grupo
  $('#fm_delgrupo_save').on('click',function(e){
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
          getListado(); 
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!-- ajax -->
  });
    
  //Muestra ventana modal para eliminar grupo
  function activelinkdelgrupo(){
    $(".linkdelgrupo").on('click',function(e){
      e.preventDefault();
      if($(this).data('numeroelementos') > 0){
        $('#malert_text').html('No se pueden eliminar un grupo con recursos asignados.');
        $('#m_alert').modal('show');
      }
      else {
        $idgrupo = $(this).data('idgrupo');
        $nombre = $(this).data('nombre');
        $('#mdgrupo_nombre').html($nombre);
        $('form#fm_delgrupo input[name="grupo_id"]').val($idgrupo);
        hideMsg();
        $('#m_delgrupo').modal('show');
      }
    });
  }

  //Edit grupo    
  //Ajax: Salvar edición de grupo
  $('#fm_editgrupo_save').on('click',function(e){
    e.preventDefault();
    CKEDITOR.instances['fm_editgrupo_inputdescripcion'].updateElement();
      $data = $('form#fm_editgrupo').serialize() + '&descripcion=' + $('#fm_editgrupo_inputdescripcion').html();
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
          getListado(); 
        }
      },
      error: function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });
  
  //Muestra ventana modal para editar grupo de recursos
  function activelinkeditgrupo(){
    $(".linkEditGrupo").on('click',function(e){
      e.preventDefault();
      $idgrupo = $(this).data('idgrupo');
      $descripcion = $(this).data('descripcion');
      $nombre = $(this).data('nombre');
      $tipo = $(this).data('tipogrupo');
      $('form#fm_editgrupo input[name="nombre"]').val($nombre);
      $('form#fm_editgrupo input[name="descripcion"]').val($descripcion);
      $('form#fm_editgrupo input[name="grupo_id"]').val($idgrupo);
      $('form#fm_editgrupo select[name="tipo"]').val($tipo);
      CKEDITOR.instances['fm_editgrupo_inputdescripcion'].setData($descripcion);
      CKEDITOR.instances['fm_editgrupo_inputdescripcion'].updateElement();
      hideMsg();
      $('#m_editgrupo').modal('show');
    });
  }

  //Ajax: add grupo
  $('#fm_addgrupo_save').on('click',function(e){
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
          getListado(); 
        }
      },
      error:function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<--./ajax-->
  });
  
  //Enabled recurso
  function activelinkenabled(){
    $(".enabled").on('click',function(e){
      e.preventDefault();
      $('#m_enabled_nombre').html($(this).data('nombrerecurso'));
      $('form#fm_enabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
      $('#fm_enabledrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre'));
      hideMsg();
      $('#m_enabledrecurso').modal('show');
    });
  }

  //Ajax: enabled recurso
  $('#fm_enabledrecurso_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type:"POST",
      url:"enabled",
      data: $('#fm_enabledrecurso').serialize(),
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_enabledrecurso_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_enabledrecurso_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_enabledrecurso_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_enabledrecurso').modal('hide');   
          showMsg($respuesta.msg);
          getListado($('#fm_enabledrecurso_save').data('idrecursopadre')); 
          $('#fm_enabledrecurso_save').data('idrecursopadre',"");
        }
      },
      error:function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<--./ajax-->
  });

  //Ajax: Disabled recurso
  function activelinkdisabled(){
    $(".disabled").on('click',function(e){
      e.preventDefault();
      $('#m_disabled_nombre').html($(this).data('nombrerecurso'));
      $('form#fm_disabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
      $('#fm_disabledrecurso_save').data('idrecursopadre',$(this).data('idrecursopadre'));
      hideMsg();
      $('#m_disabledrecurso').modal('show');
    });
  }

  $('#fm_disabledrecurso_save').on('click',function(e){
    e.preventDefault();
    CKEDITOR.instances['fm_disabledrecurso_inputdescripcion'].updateElement();
      $data = $('form#fm_disabledrecurso').serialize() + '&descripcion=' + $('#fm_disabledrecurso_inputdescripcion').html();
    showGifEspera();
    $.ajax({
      type:"POST",
      url:"disabled",
      data: $data,
      success: function($respuesta){
        if($respuesta.error === true){
          hideGifEspera();
          $.each($respuesta.errors,function(index,value){
              $('.divmodal_msgError').html('').fadeOut();
              $('#fm_disabledrecurso_input'+index).addClass('has-error');//resalta el campo de formulario con error
              $('#fm_disabledrecurso_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
          });
          $('#fm_disabledrecurso_textError').fadeIn('8000');
        }
        else {
          hideGifEspera();
          $('#m_disabledrecurso').modal('hide');   
          showMsg($respuesta.msg);
          getListado($('#fm_disabledrecurso_save').data('idrecursopadre'));
          $('#fm_disabledrecurso_save').data('idrecursopadre',"");
        }
      },
      error:function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<--./ajax-->
  });

  //Ajax: Save user with relation supervisor // técnico // validador
  $('#fm_addPersona_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
          
    $.ajax({
      type: "POST",
      url:  "addPersona",
      data: $('form#fm_addPersona').serialize(),
      success: function($respuesta){
            if ($respuesta.error === true) {
              hideGifEspera();
              $.each($respuesta.errors,function(index,value){
                                          $('.divmodal_msgError').html('').fadeOut();
                                          $('#fm_addPersona_input'+index).addClass('has-error');//resalta el campo de formulario con error
                                          $('#fm_addPersona_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
                                      });
              $('#fm_addPersona_textError').fadeIn('8000');
            }
            else {
              hideGifEspera();
              $('#m_addPersona').modal('hide');   
              showMsg($respuesta.msg);
              getListado(); 
            }   
      },
      error: function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });
  });

  //Ajax: baja supervisor, validor y/o técnico
  $('#fm_removePersona_save').on('click',function(e){
    e.preventDefault();
    showGifEspera();
    $.ajax({
      type: "POST",
      url:  "removePersonas",
      data: $('form#fm_removePersona').serialize(),
      success: function($respuesta){
          if ($respuesta.error === true) {
            hideGifEspera();
            $.each($respuesta.errors,function(index,value){
                                          $('.divmodal_msgError').html('').fadeOut();
                                          $('#fm_addPersona_input'+index).addClass('has-error');//resalta el campo de formulario con error
                                          $('#fm_addPersona_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
                                          });
            $('#fm_addPersona_textError').fadeIn('8000');
          }
          else {
            hideGifEspera();
            $('#m_removePersona').modal('hide');   
            showMsg($respuesta.msg);
            getListado(); 
          }
        },
        error: function(xhr, ajaxOptions, thrownError){
          hideGifEspera();
          alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
  });

  activelinks();
    
  function activelinks(){
    activelinkeditgrupo();
    activelinkdelgrupo();
    activeLinkeditrecurso();
    activeLinkaddrecursotogrupo();
    activelinkeliminarrecurso();
    activelinkenabled();
    activelinkdisabled();
    activelinkaddpersonas();
    activelinkremovepersonas();
    activelinkaddpuesto();
    activelinkaddequipo();
    activeLinkeditpuesto();
    activeLinkeditequipo();
    activelinkveritems();
    activeLinkaddPuestoExistente();

    activeLinkaddEquipoExistente();
  }

  function activelinkveritems(){
    $('.linkVerItems').on('click', function(e){
      e.preventDefault();
      verItems($(this).data('idrecurso'));
    });
  }

  function verItems($recursoid){
    $('#items_'+$recursoid).fadeToggle();
  }

  
  //Muestra ventana modal para establecer relación persona-recurso
  function activelinkaddpersonas(){
    $('.addUserWithRol').on('click',function(e){
      e.preventDefault();
      $('#m_addPersona_title_nombregrupo').html($(this).data('nombregrupo'));
      $('form#fm_addPersona input[name="idgrupo"]').val($(this).data('idgrupo'));
      hideMsg();
      $('#m_addPersona').modal('show');
    });
  }

  //Muestra ventana modal para eliminar relación persona-recurso
  function activelinkremovepersonas(){
    $('.removeUserWithRol').on('click',function(e){
      e.preventDefault();
      showGifEspera();
      $('#m_removePersona_title_nombregrupo').html($(this).data('nombregrupo'));
      $('form#fm_removePersona input[name="idgrupo"]').val($(this).data('idgrupo'));
      hideMsg();
      $.ajax({
        type:"GET",
        url:"htmlCheckboxPersonas",
        data:{idgrupo : $(this).data('idgrupo')},
        success: function($respuesta){
            //Añade input formulario en ventana modal
            hideGifEspera();
            $('form#fm_removePersona div#fm_removePersonas-checkboxes').html($respuesta.htmlCheckboxPersonas);
            $('#m_removePersona').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
            hideGifEspera();
            alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
        }
      });
    });
  }
    
  //Muestra ventana modal para crear nuevo grupo de recursos
  $('#btnNuevoGrupo').on('click',function(e){
    e.preventDefault();
    hideMsg();
    $('#m_addgrupo').modal('show');
  });

  function getListado($idrecurso){
    $.ajax({
      type:"GET",
      url:"getTableGrupos",
      data:{'orderby':'','order':''},
      success:function($html){
        $('#tableRecursos').html($html);
        $('#items_'+$idrecurso).fadeToggle();
        activelinks();
      },
      error:function(xhr, ajaxOptions, thrownError){
        hideGifEspera();
        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
      }
    });//<!--./ajax-->
  }

  function showMsg($msg){
    $('#success_recurselist_msg').removeClass().addClass('alert text-center alert-success').fadeOut('4000');
    $('#success_recurselist_textmsg').html($msg);
    $('#success_recurselist_msg').fadeIn('8000');
  }

  function hideMsg(){
      $('#success_recurselist_msg').fadeOut('4000');//oculta mensajes en la vista activa
      $('#success_recurselist_textmsg').html('');//Resetea a vacio y 
      $('.divmodal_msgError').html('').css('display','none');//Resetea a vacio y oculta cualquier mensaje en cualquier ventana modal
      $('.form-group').removeClass('has-error');//Elimina errores en campos de formulario de cualquier ventana modal
  }
    
  function showGifEspera(){
    $('#espera').css('display','inline').css('z-index','10000');
  }

  function hideGifEspera(){
    $('#espera').css('display','none').css('z-index','-10000');
  }

});