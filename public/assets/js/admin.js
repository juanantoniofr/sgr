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
                    showMsg($respuesta.msg);
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

    //Ajax: Disabled recurso
    $('#fm_disabledrecurso_save').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        showGifEspera();
        $.ajax({
            type:"POST",
            url:"disabled",
            data: $('#fm_disabledrecurso').serialize(),
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
        //activelinkpersonas();
        activelinkaddpersonas();
        activelinkremovepersonas();
    }


    function activelinkdisabled(){
        
        $(".disabled").on('click',function(e){
            e.preventDefault();
            $('#m_disabled_nombre').html($(this).data('nombrerecurso'));
            $('form#fm_disabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
            hideMsg();
            $('#m_disabledrecurso').modal('show');
        });
    }
   
    function activelinkenabled(){
        
        $(".enabled").on('click',function(e){
            e.preventDefault();
            $('#m_enabled_nombre').html($(this).data('nombrerecurso'));
            $('form#fm_enabledrecurso input[name="idrecurso"]').val($(this).data('idrecurso'));
            hideMsg();
            $('#m_enabledrecurso').modal('show');
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

    //Muestra ventana modal Addrecurso
    $("#btnNuevoRecurso").on('click',function(e){
        e.preventDefault();
        hideMsg();
        showGifEspera();
        $.ajax({
            type:"GET",
            url:"htmlOptionGrupos",
            data:{},
            success:function($html){
                $('#fm_addrecurso_optionsGrupos').html($html);
                hideGifEspera();
                $('#m_addrecurso').modal('show');        
            },
            error:function(xhr, ajaxOptions, thrownError){
                hideGifEspera();
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
            }
        });
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

    
    function updateChkeditorInstances(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }    
    
    function showGifEspera(){
        $('#espera').css('display','inline').css('z-index','10000');
    }

    function hideGifEspera(){
        $('#espera').css('display','none').css('z-index','-10000');
    }

});