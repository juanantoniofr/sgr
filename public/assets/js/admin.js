$(function(e){

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
               console.log($respuesta);
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
                    console.log($respuesta['supervisores']);
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
                alert(xhr.responseText + ' (codeError: ' + xhr.status +')');
               }

            });
    });
    
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