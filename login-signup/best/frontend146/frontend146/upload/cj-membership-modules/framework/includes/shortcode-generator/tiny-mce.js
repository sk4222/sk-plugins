(function() {
    tinymce.create('tinymce.plugins.cjfm', {
        init: function(ed, url) {
            ed.addButton('cj-tinymce-button', {
                title: 'CSSJockey Shortcodes',
                cmd: 'cj_button',
                image: url + '/cj-icon.png'
            });

            ed.addCommand('cj_button', function() {
                jQuery('.cj-shortcode-generator-lightbox').fadeIn();
                jQuery("#cj-shortcode").val('');
                jQuery("#cj-shortcode-options").html('');
                jQuery(".cj-shortcodes-dropdown").val('0');
                jQuery("#cj-select-product").val('0');
                jQuery("#cj-insert-shortcode").on('click', function() {
                    ed.execCommand('mceInsertContent', 0, jQuery("#cj-shortcode").val());
                    jQuery("#cj-shortcode").val('');
                    jQuery('.cj-shortcode-generator-lightbox').fadeOut();
                    return false;
                });
            });

        },

    });

    // Register plugin
    tinymce.PluginManager.add('cjfm', tinymce.plugins.cjfm);
})();


jQuery(document).ready(function($) {
    $(".cj-shortcode-generator-close").on('click', function() {
        $('.cj-shortcode-generator-lightbox').fadeOut();
        return false;
    });

    $("#cj-select-product").on('change', function() {
        var text_domain = $(this).val();
        $('.cj-shortcodes-dropdown').fadeOut(0);
        $('.' + text_domain + '-shortcodes-dropdown').val('0');
        $('.' + text_domain + '-shortcodes-dropdown').fadeIn();
        return false;
    });

    $(".cj-shortcodes-dropdown").on('change', function() {
        $("#cj-shortcode-options").html('');
        var panel = 'cj-shortcode-' + $(this).val();
        var options_html = $("#" + panel).html();
        $("#cj-shortcode-options").html(options_html);
        $("#cj-shortcode").val('');
        var shortcode_val = $('#cj-shortcode-options .shortcode-container-code').text();
        $("#cj-shortcode").val(shortcode_val);
        return false;
    });



    $("body").on('keyup', '#cj-shortcode-options input[type="text"], #cj-shortcode-options textarea, #cj-shortcode-options input[type="number"]', function(e) {
        e.preventDefault();
        var p_value = $(this).val();
        var p_class = $(this).attr('name');
        $("span." + p_class).text(p_value);
        $("#cj-shortcode").val('');
        var shortcode_val = $(this).closest('form').find('.shortcode-container-code').text();
        $("#cj-shortcode").val(shortcode_val);
        return false;
    });

    $("body").on('change', '#cj-shortcode-options input[type="color"], #cj-shortcode-options select', function(e) {
        e.preventDefault();
        var p_value = $(this).val();
        var p_class = $(this).attr('name');
        $("span." + p_class).text(p_value);
        $("#cj-shortcode").val('');
        var shortcode_val = $(this).closest('form').find('.shortcode-container-code').text();
        $("#cj-shortcode").val(shortcode_val);
        return false;
    });

});