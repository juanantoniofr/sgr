$(function(e){


    $(".enabled").on('click',function(e){
        e.preventDefault();
        console.log($(this).data('switch'));
        //Init modal
        $('#nombrerecurso_switchenabled').html($(this).data('nombrerecurso'));
        $('input[name|="idDisableRecurso"]').val($(this).data('idrecurso'));
        //select habilitar//deshabilitar recurso
        if ($(this).data('switch') == 'On'){
            $('#modalenabledRecurso').modal('show');
        }
        else { 
            if ($(this).data('switch') == 'Off') $('#modaldisabledRecurso').modal('show');
        }
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
                //location.reload();
                $idrecurso = $('#modaldisable_idrecurso').val();

                $('#link_'+ $idrecurso +' i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                $('#link_'+ $idrecurso).data('switch','On');
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

    //Muestra modal confirmación baja supervisor
    $('.bajasupervisor').on('click',function(e){
        e.preventDefault();

        $('#usernameSupervisor').html($(this).data('username'));
        $('#nombreRecurso').html($(this).data('nombrerecurso'));
        $('#btnquitaSupervisor').data('idrecurso',$(this).data('idrecurso'));
        $('#btnquitaSupervisor').data('iduser',$(this).data('iduser'));
        $('#avisoQuitarSupervisor').fadeOut('4000');

        $('#modalConfirmaBajaSupervisor').modal('show');
    });

    //da de baja un supervisor

    $('#btnquitaSupervisor').on('click',function(e){
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url:  "quitasupervisor",
            data: {idrecurso:$(this).data('idrecurso'),iduser:$(this).data('iduser')},
            success: function($respuesta){
               console.log($respuesta);
               if ($respuesta['error'] === true) $('#avisoQuitarSupervisor').fadeOut('4000').html($respuesta['msg']).removeClass('alert-success').addClass('alert-danger').fadeIn('4000');
               else {
                    $('#avisoQuitarSupervisor').fadeOut('4000').html($respuesta['msg']).removeClass('alert-danger').addClass('alert-success').fadeIn('4000');
                    }
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

            });

    });
    //cuando se cierra ventana modal quitarsupervisor
    $('#modalConfirmaBajaSupervisor').on('hidden.bs.modal', function (e) {location.reload();});
    
    //Muestra ventana modal addsupervisor
    $(".addsupervisor").on('click',function(e){
        e.preventDefault();

        $('#ModalUsuerAviso').fadeOut();
        $('#ModalUsuerNombreRecurso').html($(this).data('nombrerecurso'));
        $('#ModalUserNombreGrupo').html($(this).data('nombregrupo'));
        $('#ModalUsuerIdRecurso').val($(this).data('idrecurso'));
        
        
        $('#modalAddSupervisor').modal('show');
    });
    //Salva nuevo supervisor
    $('#btnSalvarSupervisor').on('click',function(e){
        e.preventDefault();
        
        if ($('#username').val() == '') $('#ModalUsuerAviso').removeClass('alert-success').addClass('alert-danger').html('Uvus no puede quedar vacio...').fadeOut('4000').fadeIn('4000');
        else{
            
        $.ajax({
            type: "POST",
            url:  "addsupervisor",
            data: {username:$('#username').val(),idRecurso:$('#ModalUsuerIdRecurso').val(),rol:$('#selectrol').val()},
            success: function($respuesta){
               console.log($respuesta);
               if ($respuesta['error'] === true) $('#ModalUsuerAviso').fadeOut('4000').html($respuesta['msg']).removeClass('alert-success').addClass('alert-danger').fadeIn('4000');
               else {
                    $('#ModalUsuerAviso').fadeOut('slow').html($respuesta['msg']).removeClass('alert-danger').addClass('alert-success').fadeIn('4000');
                    }
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

            });
        }
    });
    
    //cuando se cierra ventana modal addsupervisor
    $('#modalAddSupervisor').on('hidden.bs.modal', function (e) {location.reload();});
    
   //Muestra ventana modal editRecurso
    $(".linkEditrecurso").on('click',function(e){
        e.preventDefault();
        //Cargar valores del recurso a editar en #modalEditRecurso
        $.ajax({
            type: "GET",
            url:  "getrecurso",
            data: {id:$(this).data('idrecurso')},
            success: function($respuesta){
                $atributos = $respuesta['atributos'];
                CKEDITOR.instances['editdescripcion'].setData($atributos['descripcion']);
                CKEDITOR.instances['editdescripcion'].updateElement();
                //console.log($atributos['descripcion']);
                $.each($atributos,function(key,value){ 
                                            $('#modalEditRecurso input#'+key).val(value);
                                            $('#modalEditRecurso #select_'+key).val(value);
                                        });
                $('#modalEditRecurso #select_modo').val($.parseJSON($atributos['acl']).m);
                $('#modalEditRecurso .check_colectivos').val($respuesta['visibilidad']);
                $('#modalEditRecurso .text-danger').slideDown();
                $('#modalEditRecurso').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError){
                    //hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                    }

            });
    });
    
    //Muestra ventana modal para editar grupo
    $(".linkEditgrupo").on('click',function(e){
        e.preventDefault();
        $idRecurso = $(this).data('idrecurso');
        $descripciongrupo = $(this).data('descripciongrupo');
        $nombregrupo = $(this).data('nombregrupo');
        $('#titlenombregrupo').html($nombregrupo);
        $('input#grupo').val($nombregrupo);
        $('input#updatedescripciongrupo').val($descripciongrupo);
        $('input#modaldescripcionid').val($idRecurso);
        CKEDITOR.instances['updatedescripciongrupo'].setData($descripciongrupo);
        CKEDITOR.instances['updatedescripciongrupo'].updateElement();
        $('#modalEditarGrupo').modal('show');
    });

    $('#saveChangeDescriptionGroup').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        $.ajax({
            type: "POST",
            url:  "salvarDesecripcion.html",
            data: $('form#formeditargrupo').serialize(),
            success: function($respuesta){
                if($respuesta.hasError == true){  
                    $.each($respuesta.errores,function(key,value){
                        $('b#text_error_'+key).html(value);
                        $('div#error_'+key).removeClass('hidden');
                    });
                    updateChkeditorInstances();
                }
                else {
                    
                    location.reload();
                }
                
            },
            error: function(xhr, ajaxOptions, thrownError){
                    //hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
                    }
            });
            
    });

    $('#btnEditarRecurso').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        $.ajax({
            type: "POST",
            url:  "updateRecurso.html",
            data: $('form#editRecurso').serialize(),
            success: function($respuesta){
                if($respuesta.hasError == true){  
                    $.each($respuesta.errores,function(key,value){
                        $('b#text_error_'+key).html(value);
                        $('div#error_'+key).removeClass('hidden');
                    });
                    updateChkeditorInstances();
                }
                else location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError){
                    //hideGifEspera();
                    alert(xhr.responseText + ' (codeError: ' + xhr.status +')');}
            });
    });
    
    function updateChkeditorInstances(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }    

    //Muestra ventana modal Addrecurso
    $("#btnNuevoRecurso").on('click',function(e){
        e.preventDefault();
        $('#modalAddRecurso').modal('show');
    });

    //Lanza ajax function para salvar nuevo recurso
    $('#btnSalvarRecurso').on('click',function(e){
        e.preventDefault();
        updateChkeditorInstances();
        $data = $('form#nuevoRecurso').serialize();
        $.ajax({
            type: "GET",
            url: "salvarNuevoRecurso",
            data: $data,
            success: function($respuesta){
                if ($respuesta['error'] == false){
                    $('#modalAddRecurso').modal('hide');
                    location.reload();
                }
                //Hay errores de validación del formulario
                else {
                   //console.log($respuesta['errors']);
                   //reset
                   $('#modalAddRecurso .has-error').removeClass('has-error');//borrar errores anteriores
                   $('#modalAddRecurso .spanerror').each(function(){$(this).slideUp();});
                   //new errors
                   $.each($respuesta['errors'],function(key,value){
                        $('#modalAddRecurso #fg'+key).addClass('has-error');
                        $('#modalAddRecurso #'+key+'_error > span#text_error').html(value);
                        $('#modalAddRecurso #'+key+'_error').fadeIn("4000");
                        $('#modalAddRecurso #'+key+'_error').fadeIn("4000");

                        $('#aviso').slideDown("slow");
                        
                    });     
                }
                },
                error: function(xhr, ajaxOptions, thrownError){
                        //hideGifEspera();
                        alert(xhr.responseText + ' (codeError: ' + xhr.status) +')';
                    }
                });
    }); 

    $(".eliminarRecurso").on('click',function(e){
        e.preventDefault();
        $('#nombrerecurso').html($(this).data('nombrerecurso'));
        $('a#btnEliminar').data('idrecurso',$(this).data('idrecurso'));
        $('a#btnEliminar').attr('href', 'eliminarecurso.html' + '?'+'id='+$(this).data('idrecurso'));
        $('#modalborrarRecurso').modal('show');
    });

    
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