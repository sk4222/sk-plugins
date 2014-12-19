jQuery(document).ready(function($) {

    $('#cjfm-modalbox-login-form, #cjfm-modalbox-register-form, .cjfm-modalbox').fadeOut(0);

    // modalbox forms
    $(".cjfm-show-login-form").on('click', function() {
        $('.cjfm-modalbox').fadeIn(250);
        $('#cjfm-modalbox-login-form').fadeIn(250);
        return false;
    });

    $(".cjfm-show-register-form").on('click', function() {
        $('.cjfm-modalbox').fadeIn(250);
        $('#cjfm-modalbox-register-form').fadeIn(250);
        return false;
    });

    $('.cjfm-close-modalbox').on('click', function() {
        $('.cjfm-modalbox').fadeOut(250);
        $('#cjfm-modalbox-login-form').fadeOut(250);
        $('#cjfm-modalbox-register-form').fadeOut(250);
        $('.cjfm-modalbox').removeClass('show');
        $('#cjfm-modalbox-login-form').removeClass('show');
        $('#cjfm-modalbox-register-form').removeClass('show');
        return false;
    });


    // Password Meter
    $('.cjfm-pw input[type="password"], .cjfm-cpw input[type="password"]').on('keyup', function(e) {

        // Must have capital letter, numbers and lowercase letters
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        // Must have either capitals and lowercase letters or lowercase and numbers
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");

        if($(this).val() == ''){
            $(this).parent().find('.cjfm-pw-strength').html('<span class="blank">Blank</span>');
        } else if (strongRegex.test($(this).val())) {
            // If reg ex matches strong password
            $(this).parent().find('.cjfm-pw-strength').html('<span class="strong">Strong</span>');
        } else if (mediumRegex.test($(this).val())) {
            // If medium password matches the reg ex
            $(this).parent().find('.cjfm-pw-strength').html('<span class="medium">Medium</span>');
        } else {
            // If password is ok
            $(this).parent().find('.cjfm-pw-strength').html('<span class="weak">Weak</span>');
        }
        return true;
    });

    // Confirm message
    $('.confirm, .cj-confirm').click(function() {
        var msg = $(this).attr('data-confirm');
        var confmsg = confirm(msg);
        if (confmsg == true) {
            return true;
        } else {
            return false;
        }
    })

});