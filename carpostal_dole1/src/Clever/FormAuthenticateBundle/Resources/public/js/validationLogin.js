/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {

    // pre-submit callback 
    function preSubmitPasswordForm(formData, jqForm, options) {
        var url = $('#passwordlost').data('url');
        $('#passwordlost').html('<center><img src="' + url + '"></center>');
        return true;
    }

// post-submit callback 
    function postSubmitPasswordForm(responseText, statusText, xhr, $form) {
        if (jQuery('#sendPassword .error').length === 0) {
            jQuery("#sendPassword").hide();
        }
        validatePasswordForm();
    }
    jQuery("#sendPassword").hide();
    function validatePasswordForm() {

        jQuery("#passwordlost").validate({
            /*Gestion des règles de controle*/
            rules: {
                'pwd_lost':
                        {
                            required: true,
                            minlength: 2
                        }
            },
            /*Gestion des messages d'erreur*/
            messages: {
                'pwd_lost':
                        {
                            required: "Veuillez renseigner votre identifiant.",
                            minlength: "Votre Identifiant doit faire au moins 2 caractères."
                        }
            },
            /*Gestion de la soumission du formmulaire s'il est valide*/
            submitHandler: function(form) {

                jQuery("#sendPassword").hide();

                /*-----------------------------------*/
                jQuery(form).ajaxSubmit({
                    target: "#passwordlost",
                    beforeSubmit: preSubmitPasswordForm,
                    success: postSubmitPasswordForm,
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
                jQuery('#sendPassword').append(error);
            },
            /*Gestion de l'affichage du bloc des erreurs */
            invalidHandler: function(event, validator) {

                var errors = validator.numberOfInvalids();
                if (errors)
                {
                    jQuery("#sendPassword").show();
                }
                else
                {
                    jQuery("#sendPassword").hide();
                }
            }

        });
    }
    validatePasswordForm();

    if (jQuery('#sendAuthentication .error').length === 0) {
        jQuery("#sendAuthentication").hide();
    }
    function validateAuthenticateForm() {

        jQuery("#formlogin").validate({
            /*Gestion des règles de controle*/
            rules: {
                '_username':
                        {
                            required: true,
                            minlength: 2
                        },
                '_password':
                        {
                            required: true,
                            minlength: 2
                        }
            },
            /*Gestion des messages d'erreur*/
            messages: {
                '_username':
                        {
                            required: "Veuillez renseigner votre identifiant.",
                            minlength: "Votre Identifiant doit faire au moins 2 caractères."
                        },
                '_password':
                        {
                            required: "Veuillez renseigner votre mot de passe.",
                            minlength: "Votre mot de passe doit faire au moins 2 caractères."
                        }
            },
            /*Gestion de la soumission du formmulaire s'il est valide*/
            submitHandler: function(form) {

                jQuery("#sendAuthentication").hide();
                form.submit();
            },
            /*Gestion de l'emplacement des labels erreurs dans la page HTML*/
            errorPlacement: function(error, element) {
                jQuery('#sendAuthentication').append(error);
            },
            /*Gestion de l'affichage du bloc des erreurs */
            invalidHandler: function(event, validator) {

                var errors = validator.numberOfInvalids();

                if (errors)
                {
                    jQuery("#sendAuthentication").show();
                }
                else
                {
                    jQuery("#sendAuthentication").hide();
                }
            }

        });
    }
    validateAuthenticateForm();

});
