/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {



    // pre-submit callback 
    function preSubmitMainForm(formData, jqForm, options) {
        var url = $('#form_content').data('url');
        $('#form_content').html('<center><img src="' + url + '"></center>');


        // here we could return false to prevent the form from being submitted; 
        // returning anything other than false will allow the form submit to continue 
        return true;
    }

// post-submit callback 
    function postSubmitMainForm(responseText, statusText, xhr, $form) {
        var reloadCaptcha = 'reload_' + jQuery('.captchaimage').attr('id');
        eval(reloadCaptcha + '()');
        initialyze();
        validateMainForm();
    }


    /*Gestion des placeHolders compatible IE */
    $('input, textarea').placeholder();
    /*Validation du numéro de téléphone*/
    jQuery.validator.addMethod("regexphone", function(value, element, regexp)
    {

        if (regexp.constructor != RegExp)
            regexp = new RegExp(regexp);
        else if (regexp.global)
            regexp.lastIndex = 0;
        return this.optional(element) || regexp.test(value);
    }, "");

    /*Validation du formulaire principal*/
    /*On cache le bloc d'affichage des erreurs récupérées par JS*/
    jQuery(".errorsubmit").hide();
    function validateMainForm() {

        jQuery("#main").validate({
            /*Gestion des règles de controle*/
            rules: {
                'contact[datacontact][gender]': {
                    required: true
                },
                'contact[name]': {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                'contact[lastname]': {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                'contact[phone]': {
                    required: true,
                    'regexphone': /^0[1-9][0-9]{8}$/
                },
                'contact[datacontact][landlinePhone]': {
                    'regexphone': /^0[1-9][0-9]{8}$/
                },
                'contact[datacontact][email]': {
                    required: true,
                    'email': true
                },
                'contact[datacontact][old]': {
                    required: true
                },
                'contact[datacontact][city]': {
                    minlength: 2,
                    maxlength: 50
                },
                'contact[datacontact][address]': {
                    minlength: 2,
                    maxlength: 50
                },
                'contact[datacontact][subscriberNumber]': {
                    'digits': true,
                    maxlength: 50
                },
                'contact[datacontact][captcha]': {
                    required: true
                }
                
            },
            /*Gestion des messages d'erreur*/
            messages: {
                'contact[datacontact][gender]': {
                    required: "Veuillez renseigner votre civilité."
                },
                'contact[name]': {
                    required: "Veuillez renseigner votre nom.",
                    minlength: "Votre nom doit faire au moins 2 caractères.",
                    maxlength: "Votre nom doit faire au plus 50 caractères."
                },
                'contact[lastname]': {
                    required: "Veuillez renseigner votre prénom.",
                    minlength: "Votre prénom doit faire au moins 2 caractères.",
                    maxlength: "Votre prénom doit faire au plus 50 caractères."
                },
                'contact[phone]': {
                    required: "Veuillez renseigner votre tél. mobile.",
                    'regexphone': "Le numéro de tel. mobile est incorrect."
                },
                'contact[datacontact][landlinePhone]': {
                    'regexphone': "Le numéro de tel. fixe est incorrect."
                },
                'contact[datacontact][email]': {
                    required: "Veuillez renseigner votre email.",
                    'email': "Veuillez renseigner un email valide."
                },
                'contact[datacontact][old]': {
                    required: "Veuillez renseigner votre âge."
                },
                'contact[datacontact][city]': {
                    minlength: "Votre ville doit faire au moins 2 caractères.",
                    maxlength: "Votre ville doit faire au plus 50 caractères."
                },
                'contact[datacontact][address]': {
                    minlength: "Votre adresse doit faire au moins 2 caractères.",
                    maxlength: "Votre adresse doit faire au plus 50 caractères."
                },
                'contact[datacontact][subscriberNumber]': {
                    'digits': "Le numéro d'abonné est incorrect.",
                    maxlength: "Votre numéro d'abonné doit faire au plus 50 chiffres."
                },
                'contact[datacontact][captcha]': {
                    required: "Veuillez renseigner le code dans le cadre."
                }
                /*,
                 'contact[datacontact][bus][]': {
                 required: "Vous devez sélectionner au moins une ligne de bus."
                 }*/
            },
            /*Gestion de la soumission du formmulaire s'il est valide*/
            submitHandler: function(form) {
                jQuery(".errorsubmit").hide();

                /*-----------------------------------*/
                jQuery(form).ajaxSubmit({
                    target: "#form_content",
                    beforeSubmit: preSubmitMainForm, // pre-submit callback 
                    success: postSubmitMainForm,
                    error: function() {
                        var img505 = $('#form_content').data('505');
                        var redirect = $('#form_content').data('redirect');
                        $('#form_content').html('<center><img src="' + img505 + '"><br/> ERREUR INTERNE.<br/><a href="' + redirect + '"><u>Retour</u></a></center>');
                    }
                });

                /*-------------------------------------*/
                // form.submit();
            },
            /*Gestion de l'emplacement des labels erreurs dans la page HTML*/
            errorPlacement: function(error, element) {
                jQuery('.errorsubmit').append(error);
            },
            /*Gestion de l'affichage du bloc des erreurs */
            invalidHandler: function(event, validator) {

                var errors = validator.numberOfInvalids();
                if (errors)
                {
                    jQuery(".errorsubmit").show();
                }
                else
                {
                    jQuery(".errorsubmit").hide();
                }
            }
            // , ignore: ':hidden:not("#contact_datacontact_bus")'


        });
    }

    function initialyze() {

        /*Date de naissance*/
        var date = new Date();
        var year = date.getFullYear();
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: new Date(1930, 1 - 1, 1),
            yearRange: '1900:' + year,
            maxDate: date

        });
        /*selection des lignes de bus*/
        $("#contact_datacontact_bus").multiselect({
            show: ["bounce", 200],
            hide: ["explode", 1000],
            checkAllText: "Cocher tout",
            uncheckAllText: "Décocher tout",
            minWidth: 420,
            noneSelectedText: 'Bus de la ville de Dole',
            selectedText: '# bus sélectionnés'
        });
    }
    initialyze();
    validateMainForm();


});

