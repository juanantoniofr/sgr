$(function(e){

    //Ajax: Salvar nuevo recurso
    $('#fm_addrecurso_save').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $data = $('form#fm_addrecurso').serialize();
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
                    showMsg($respuesta['msg']);
                    getListado(); 
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
            }
        });
    }); 
    
    //Ajax: Salvar edición recurso
    $('#fm_editrecurso_save').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $.ajax({
            type: "POST",
            url:  "updaterecurso",
            data: $('form#fm_editrecurso').serialize(),
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
                    getListado(); 
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                    hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');}
            });
    });

    //Ajax: Añadir Recurso a Grupo
    $('#fm_addrecursotogrupo_save').on('click',function(e){
        e.preventDefault();
        showGifEspera();
        $.ajax({
            type: "POST",
            url:  "addrecursotogrupo",
            data: $('form#fm_addrecursotogrupo').serialize(),
            success: function($respuesta){
                console.log($respuesta);
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
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');}
            });
    });
    
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
            });
    });

    //Ajax: Delete recurso
    $('#fm_delrecurso_save').on('click',function(e){
        e.preventDefault();
        showGifEspera();
        $.ajax({
            type: "POST",
            url:  "delrecurso",
            data: $('form#fm_delrecurso').serialize(),
            success: function($respuesta){
                console.log($respuesta);
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
                    getListado(); 
                }
                
            },
            error: function(xhr, ajaxOptions, thrownError){
                    hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                    }
            });
    });

    //Ajax: Salvar edición de grupo
    $('#fm_editgrupo_save').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $.ajax({
            type: "POST",
            url:  "editgrupo",
            data: $('form#fm_editgrupo').serialize(),
            success: function($respuesta){
                if($respuesta.error === true){  
                    hideGifEspera();
                    $.each($respuesta.errors,function(index,value){
                        $('.divmodal_msgError').html('').fadeOut();
                        $('#fm_editgrupo_input'+index).addClass('has-error');//resalta el campo de formulario con error
                        $('#fm_editgrupo_textError').append(value + '<br />');//añade texto de error a div alert-danger en ventana modal
                    });
                    $('#fm_editgrupo_textError').fadeIn('8000');
                    //updateChkeditorInstances();
                }
                else {
                    hideGifEspera();
                    $('#m_editgrupo').modal('hide');   
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

    //Ajax: Salvar nuevo grupo
    $('#fm_addgrupo_save').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $.ajax({
            type:"POST",
            url:"addgrupo",
            data: $('#fm_addgrupo').serialize(),
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
                    showMsg($respuesta['msg']);
                    getListado(); 
                }
                
            }
            ,
            error:function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }
        });//<--./ajax-->
    });

    activelinks();
    
    function activelinks(){
        activelinkeditgrupo();
        activelinkdelgrupo();
        activeLinkeditrecurso();
        activeLinkaddrecursotogrupo();
        activelinkeliminarrecurso();
        activelinkenabled();
    }

    function activelinkenabled(){
        
        $(".enabled").on('click',function(e){
            e.preventDefault();
            $('#m_enabled_nombre').html($(this).data('nombrerecurso'));
            $('form#fm_enabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
            //if ($(this).data('switch') == 'On'){
            hideMsg();
            $('#m_enabledrecurso').modal('show');
            //}
            //else { 
            //if ($(this).data('switch') == 'Off') $('#modaldisabledRecurso').modal('show');
            //}
        });
    }

    function activelinkeliminarrecurso(){
        $(".linkEliminaRecurso").on('click',function(e){
            e.preventDefault();
            $idrecurso = $(this).data('idrecurso');
            $nombre = $(this).data('nombre');
            $('#mdrecurso_nombre').html($nombre);
            $('form#fm_delrecurso input[name="idrecurso"]').val($idrecurso);
            hideMsg();
            $('#m_delrecurso').modal('show');
        });
    }

    function activeLinkaddrecursotogrupo(){
        //Muestra ventana modal addrecursotogrupo
        $(".addrecursotogrupo").on('click',function(e){
            e.preventDefault();
            showGifEspera();
            //console.log($(this).data('idgrupo'));
            $('#m_addrecursotogrupo_nombre').html($(this).data('nombre'));
            $('form#fm_addrecursotogrupo input[name="grupo_id"]').val($(this).data('idgrupo'));
            $.ajax({
                type: "GET",
                url:  "recursosSinGrupo",
                data: {id:$(this).data('idgrupo')},
                success: function($html){
                    hideGifEspera();
                    $('#m_addrecursotogrupo span#recursosSinGrupo').html($html);
                    hideMsg();
                    $('#m_addrecursotogrupo').modal('show');
                },
                error: function(xhr, ajaxOptions, thrownError){
                    hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                }
            });
        });
    }

    function activeLinkeditrecurso(){
        //Muestra ventana modal editRecurso
        $(".linkEditRecurso").on('click',function(e){
            e.preventDefault();
            showGifEspera();
            $.ajax({
                type: "GET",
                url:  "getrecurso",
                data: {idrecurso:$(this).data('idrecurso')},
                success: function($recurso){
                    hideGifEspera();
                    console.log($recurso.descripcion);
                    CKEDITOR.instances['fm_editrecurso_inputdescripcion'].setData($recurso.descripcion);
                    CKEDITOR.instances['fm_editrecurso_inputdescripcion'].updateElement();
                    $('#fm_editrecurso input[name="id"]').val($recurso.id);
                    $('#fm_editrecurso input[name="nombre"]').val($recurso.nombre);
                    $('#fm_editrecurso input[name="id_lugar"]').val($recurso.id_lugar);
                    $('#fm_editrecurso select[name="grupo_id"]').val($recurso.grupo_id);
                    $('#fm_editrecurso select[name="tipo"]').val($recurso.tipo);
                    $('#fm_editrecurso select[name="modo"]').val($.parseJSON($recurso.acl).m);    
                    
                    $arrayRoles = $.parseJSON($recurso.acl).r.split(',');
                    
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
            });
        });
    }

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
    
    //Muestra ventana modal para editar grupo de recursos
    function activelinkeditgrupo(){
        $(".linkEditGrupo").on('click',function(e){
            e.preventDefault();
            $idgrupo = $(this).data('idgrupo');
            $descripcion = $(this).data('descripcion');
            $nombre = $(this).data('nombre');
            $('form#fm_editgrupo input[name="nombre"]').val($nombre);
            $('form#fm_editgrupo input[name="descripcion"]').val($descripcion);
            $('form#fm_editgrupo input[name="grupo_id"]').val($idgrupo);
            CKEDITOR.instances['fm_editgrupo_inputdescripcion'].setData($descripcion);
            CKEDITOR.instances['fm_editgrupo_inputdescripcion'].updateElement();
            hideMsg();
            $('#m_editgrupo').modal('show');
        });
    }

    //Muestra ventana modal para crear nuevo grupo de recursos
    $('#btnNuevoGrupo').on('click',function(e){
        e.preventDefault();
        hideMsg();
        $('#m_addgrupo').modal('show');
    });

    //Muestra ventana modal Addrecurso
    $("#btnNuevoRecurso").on('click',function(e){
        e.preventDefault();
        hideMsg();
        $('#m_addrecurso').modal('show');
    });

    function getListado(){
        $.ajax({
            type:"GET",
            url:"getTableGrupos",
            data:{'orderby':'','order':''},
            success:function($html){
                $('#tableRecursos').html($html);
                activelinks();
            },
            error:function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

        });//<!--./ajax-->
    }

    function showMsg($msg){
        $('#success_recurselist_msg').removeClass().addClass('alert text-center alert-success').fadeOut('4000').html($msg).fadeIn('8000');
    }

    function hideMsg(){
        $('#success_recurselist_msg').fadeOut('4000').html('');//Resetea a vacio y oculta mensajes en la vista activa
        $('.divmodal_msgError').html('').css('display','none');//Resetea a vacio y oculta cualquier mensaje en cualquier ventana modal
        $('.form-group').removeClass('has-error');//Elimina errores en campos de formulario de cualquier ventana modal
    }

    //**************

    //Muestra modal confirmación baja supervisor // validador y/o técnico
    $('.removeUserWithRol').on('click',function(e){
        e.preventDefault();

        $('form#removeUserWithRol input[name="idrecurso"]').val($(this).data('idrecurso'));
        //obtiene supervisores//validadores//técnico del un recurso
        $.ajax({
            type:"GET",
            url:"usersWithRelation.html",
            data:{idrecurso : $(this).data('idrecurso')},
            success: function($respuesta){
                //console.log($respuesta);
                //Añade input formualrio en ventana modal
                $('form#removeUserWithRol div#supervisores').html('');
                $('form#removeUserWithRol div#validadores').html('');
                $('form#removeUserWithRol div#tecnicos').html('');
                $.each($respuesta['supervisores'],function(index,value){
                    
                    $('form#removeUserWithRol div#supervisores').append('<label><input type="checkbox" value = "'+value.id+'" name="supervisores[]" >'+ value.username +', '+value.nombre + ' '+ value.apellidos+' </label><br />');
                });
                
                $.each($respuesta['validadores'],function(index,value){
                    
                    $('form#removeUserWithRol div#validadores').append('<label><input type="checkbox" value = "'+value.id+'" name="validadores[]" >'+ value.username +', '+value.nombre + ' '+ value.apellidos+' </label><br />');
                });

                $.each($respuesta['tecnicos'],function(index,value){
                    //console.log('tecnicos '+value.pivot.recurso_id);
                    $('form#removeUserWithRol div#tecnicos').append('<label><input type="checkbox" value = "'+value.id+'" name="tecnicos[]" >'+ value.username +', '+value.nombre + ' '+ value.apellidos+' </label><br />');
                });

                $('#modalRemoveUserWithRol').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }
        });
    });

    //Ajax: baja supervisor, validor y/o técnico
    $('#btnremoveUserWithRol').on('click',function(e){
        e.preventDefault();
        showGifEspera();
        $.ajax({
            type: "POST",
            url:  "removeUsersWithRol",
            data: $('form#removeUserWithRol').serialize(),
            success: function($respuesta){
               //console.log($respuesta);
               hideGifEspera();
               if ($respuesta['error'] === true) {
                    $('#msg_modalRemoveUserWithRol').fadeOut('8000').html($respuesta['msg']).fadeIn('16000');
                    
                }
               else {
                    $('#modalRemoveUserWithRol').modal('hide');
                    $('#success_recurselist_msg').html($respuesta['msg']).fadeOut('8000').fadeIn('16000');

                    //actualiza td
                    $idrecurso = $('form#removeUserWithRol input[name="idrecurso"]').val();
                    $('#supervisores_'+$idrecurso).html('');
                    $('#validadores_'+$idrecurso).html('');
                    $('#tecnicos_'+$idrecurso).html('');
                    //console.log($respuesta['supervisores']);
                    $.each($respuesta['supervisores'],function(index,value){
                        $('#supervisores_'+value.pivot.recurso_id).append(value.nombre + ' ' + value.apellidos +' ('+ value.username +').<br />');
                    });
                    $.each($respuesta['validadores'],function(index,value){
                        $('#validadores_'+value.pivot.recurso_id).append(value.nombre + ' ' + value.apellidos +' ('+ value.username +').<br />');
                    });
                    $.each($respuesta['tecnicos'],function(index,value){
                        $('#tecnicos_'+value.pivot.recurso_id).append(value.nombre + ' ' + value.apellidos +' ('+ value.username +').<br />');
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

            });
    });

    //show modal add user with relation supervisor // técnico // validador
    $(".addUserWithRol").on('click',function(e){
        e.preventDefault();

        $('#ModalUsuerAviso').fadeOut();
        $('#ModalUsuerNombreRecurso').html($(this).data('nombrerecurso'));
        $('#ModalUserNombreGrupo').html($(this).data('nombregrupo'));
        $('#ModalUsuerIdRecurso').val($(this).data('idrecurso'));
        
        $('#modalAddUserWithRol').modal('show');
    });

    //Save user with relation supervisor // técnico // validador
    $('#btnAddUserWithRol').on('click',function(e){
        e.preventDefault();
        showGifEspera();
          
        $.ajax({
            type: "POST",
            url:  "addUserWithRol",
            data: $('form#addUserWithRol').serialize(),
            success: function($respuesta){
               //console.log($respuesta);
               if ($respuesta['error'] === true) {
                    $('#msg_modalAddUserWithRol').fadeOut('8000').html($respuesta['msg']).removeClass('alert-success').addClass('alert-danger').fadeIn('16000');
                    hideGifEspera();
                }
               else {
                    $idrecurso = $('form#addUserWithRol input[name="idRecurso"]').val();
                    //console.log($idrecurso);
                    if($respuesta['relacion'] == 'supervisor'){
                        $('#supervisores_'+$idrecurso).append($respuesta["user"].nombre + ' ' + $respuesta["user"].apellidos +'<br />');
                    }
                    if($respuesta['relacion'] == 'validador'){
                        
                        $('#validadores_'+$idrecurso).append($respuesta["user"].nombre + ' ' + $respuesta["user"].apellidos +'<br />');
                    }
                    if($respuesta['relacion'] == 'tecnico'){
                        $('#tecnicos_'+$idrecurso).append($respuesta["user"].nombre + ' ' + $respuesta["user"].apellidos +'<br />');
                    }
                    
                    
                    
                    $('#modalAddUserWithRol').modal('hide');//oculta modal
                    hideGifEspera();//oculta gif
                    $('#success_recurselist_msg').html($respuesta['msg']).fadeOut('8000').fadeIn('16000');//muestra mensage
                    $('tr#tr_'+$respuesta["recurso"].id+' td').fadeOut('8000').fadeIn('8000');//toggle tr
                    }
            },
            error: function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

            });
    });
    
    

    $('#btnDeshabilitar').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $.ajax({
            type:"POST",
            url:'deshabilitarRecurso.html',
            data:$('form#deshabilitarecurso').serialize(),
            success:function($respuesta){
                $('#modaldisabledRecurso').modal('hide');
                hideGifEspera();
                $idrecurso = $('#modaldisable_idrecurso').val();

                $('#link_'+ $idrecurso +' i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                $('#link_'+ $idrecurso).data('switch','On');
                $('#tr_'+ $idrecurso +' td').each(function(){
                    $(this).fadeOut('8000').fadeIn('16000');
                });
                
                $('#success_recurselist_msg').html($respuesta['msg']).fadeOut('8000').fadeIn('16000');
                
                },
            error:function(xhr, ajaxOptions, thrownError){
                        hideGifEspera();
                        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                    }
        });
    });   
   
    $('#btnHabilitar').on('click',function(e){
        e.preventDefault();
        showGifEspera();
        $.ajax({
            type:"POST",
            url:'habilitarRecurso.html',
            data:$('form#deshabilitarecurso').serialize(),
            success:function($respuesta){
                $('#modalenabledRecurso').modal('hide');
                hideGifEspera();
                
                $idrecurso = $('#modaldisable_idrecurso').val();
                $('#link_'+ $idrecurso +' i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                $('#link_'+ $idrecurso).data('switch','Off');
                $('#tr_'+ $idrecurso +' td').each(function(){
                    $(this).fadeOut('slow').fadeIn('4000');
                });
                
                $('#success_recurselist_msg p').html($respuesta);
                $('#success_recurselist_msg').fadeIn('4000');
                },
            error:function(xhr, ajaxOptions, thrownError){
                        hideGifEspera();
                        alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                    }
        });
    });
    
    function updateChkeditorInstances(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }    
    
    $("#caducidad").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showAnim: 'slideDown',
            dateFormat: 'd-m-yy',
            showButtonPanel: true,
            firstDay: 1,
            monthNames: ['Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio','Julio', 'Agosto','Septiembre', 'Octubre','Noviembre', 'Diciembre'],
            dayNamesMin: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']
        });


    $('#grupoNuevo').on('click',function(e){
        e.preventDefault();
        $('#nuevoGrupo').toggle('slow');
       
    });
    
    $('#grupoNuevo_edit').on('click',function(e){
        e.preventDefault();
        $('#nuevoGrupo_edit').toggle('slow');
    });

    function showGifEspera(){
        $('#espera').css('display','inline').css('z-index','10000');
    }

    function hideGifEspera(){
        $('#espera').css('display','none').css('z-index','-10000');
    }

});