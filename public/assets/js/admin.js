$(function(e){

    //Muestra modal confirmación baja supervisor
    $('.bajasupervisor').on('click',function(e){
        e.preventDefault();

        $('#usernameSupervisor').html($(this).data('username'));
        $('#nombreRecurso').html($(this).data('nombrerecurso'));
        $('#btnquitaSupervisor').data('idrecurso',$(this).data('idrecurso'));
        $('#btnquitaSupervisor').data('iduser',$(this).data('iduser'));
        $('#avisoQuitarSupervisor').fadeOut();

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
               if ($respuesta['error'] === true) $('#avisoQuitarSupervisor').fadeOut('slow').html($respuesta['msg']).removeClass('alert-success').addClass('alert-danger').fadeIn('slow');
               else {
                    $('#avisoQuitarSupervisor').fadeOut('slow').html($respuesta['msg']).removeClass('alert-danger').addClass('alert-success').fadeIn('slow');
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
    $("#addsupervisor").on('click',function(e){
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
        
        if ($('#username').val() == '') $('#ModalUsuerAviso').removeClass('alert-success').addClass('alert-danger').html('Uvus no puede quedar vacio...').fadeOut('slow').fadeIn('slow');
        else{
            
        $.ajax({
            type: "POST",
            url:  "addsupervisor",
            data: {username:$('#username').val(),idRecurso:$('#ModalUsuerIdRecurso').val(),rol:$('#selectrol').val()},
            success: function($respuesta){
               console.log($respuesta);
               if ($respuesta['error'] === true) $('#ModalUsuerAviso').fadeOut('slow').html($respuesta['msg']).removeClass('alert-success').addClass('alert-danger').fadeIn('slow');
               else {
                    $('#ModalUsuerAviso').fadeOut('slow').html($respuesta['msg']).removeClass('alert-danger').addClass('alert-success').fadeIn('slow');
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
                //console.log($('#modalEditRecurso input#editdescripcion'));
                //$('#modalEditRecurso input#editdescripcion').val();
                CKEDITOR.instances['editdescripcion'].setData($atributos['descripcion']);
                CKEDITOR.instances['editdescripcion'].updateElement();
                //console.log($atributos['descripcion']);
                $.each($atributos,function(key,value){ 
                                            $('#modalEditRecurso input#'+key).val(value);
                                            $('#modalEditRecurso #select_'+key).val(value);
                                        });
                //updateChkeditorInstances();
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
        //Cargar valores del recurso a editar en #modalEditRecurso
        //console.log($(this).data('idrecurso'));
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
               //console.log($respuesta);
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
                        $('#modalAddRecurso #'+key+'_error').fadeIn("slow");
                        $('#modalAddRecurso #'+key+'_error').fadeIn("slow");

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

    $(".deshabilitarRecurso").on('click',function(e){
        e.preventDefault();
        $('#nombrerecursoDeshabilitar').html($(this).data('nombrerecurso'));
        $('a#btnDeshabilitar').data('idrecurso',$(this).data('idrecurso'));
        $('a#btnDeshabilitar').attr('href', 'deshabilitarRecurso.html' + '?'+'id='+$(this).data('idrecurso'));
        $('#modaldisabledRecurso').modal('show');
    });  

    $(".habilitarRecurso").on('click',function(e){
        e.preventDefault();
        $('#nombrerecursoHabilitar').html($(this).data('nombrerecurso'));
        $('a#btnHabilitar').data('idrecurso',$(this).data('idrecurso'));
        $('a#btnHabilitar').attr('href', 'habilitarRecurso.html' + '?'+'id='+$(this).data('idrecurso'));
        $('#modalenabledRecurso').modal('show');
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

});