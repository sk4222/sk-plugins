jQuery(document).ready(function($) {

    function height_fix(element, parent) {
        var woh = $(parent).height();
        var eoh = $(element).height();
        $(window).resize(function() {
            var eh = $(element).height();
            var wh = $(parent).height();
            var wdiff = wh / woh;
            var nh = (eoh * wdiff);
            $(element).height(nh);
        })
    }


    // Quick search
    $('input#settings-search-box').quicksearch('table.enable-search tr.searchable');


    // Code mirror
    $(".cj-code-css, .code-css").each(function() {
        var id = $(this).attr('id');
        var csseditor = CodeMirror.fromTextArea(document.getElementById(id), {
            lineNumbers: true,
        });
        csseditor.setOption("theme", 'ambiance');
    })

    $(".cj-code-js, .code-js").each(function() {
        var id = $(this).attr('id');
        var jseditor = CodeMirror.fromTextArea(document.getElementById(id), {
            lineNumbers: true,
        });
        jseditor.setOption("theme", 'ambiance');
    })

    $(".cj-code-html, .code-html").each(function() {
        var id = $(this).attr('id');
        var jseditor = CodeMirror.fromTextArea(document.getElementById(id), {
            lineNumbers: true,
        });
        jseditor.setOption("theme", 'ambiance');
    })

    // $ UI Date
    $(function() {
        $(".date").datetimepicker({
            timeFormat: "hh:mm tt"
        });
    });

    // Screenshots
    $('.cj-screenshot').click(function() {
        $('.cj-screenshot').removeClass('checked');
        $(this).addClass('checked');
    })

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

    // Confirm message
    $('.cj-alert-msg').click(function() {
        var msg = $(this).attr('data-alert');
        if (msg != 'undefined') {
            alert(msg);
        }
        return false;
    })

    // Color
    $(".color-picker").spectrum({
        color: $(this).attr('data-color'),
        showInput: true,
        className: "full-spectrum",
        showInitial: true,
        showPalette: false,
        showSelectionPalette: true,
        maxPaletteSize: 10,
        preferredFormat: "hex",
        localStorageKey: "spectrum.demo",
        move: function(color) {

        },
        show: function() {

        },
        beforeShow: function() {

        },
        hide: function() {

        },
        change: function() {

        },
        palette: [
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"
            ],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"
            ],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"
            ]
        ]
    });

    // Delete Uploaded Files
    $(".cj-remove-file").click(function() {
        $(this).parent().remove();
        return false;
    });


    // Documentation
    $('.cj-answer').hide(0);
    $(".cj-question").click(function() {
        var element = $(this).parent().find('.cj-answer');
        $(element).slideToggle(250);
        return false;
    });


    // Uploading files
    var file_frame;

    $('.cj-upload-file').live('click', function(event) {

        var el = $(this);
        var form_id = $(el).attr('data-form-id');

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            multiple: false // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            var container = $(el).parent().find('.uploaded-file');
            var option_id = $(container).attr('data-id');
            var shtml = '';
            var shtml = '<input type="hidden" name="' + option_id + '[]" value="' + attachment.url + '" />';
            $(container).html(shtml);
            $(el).parent().parent().find('.cj-info').html('<p class="red">Files uploaded, please save settings.</p>');

            $("#" + form_id).trigger('click');

            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });


    $('.cj-upload-files').live('click', function(event) {

        var el = $(this);
        var form_id = $(el).attr('data-form-id');

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            multiple: true // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {

            var selection = file_frame.state().get('selection');

            // -----------------------------------
            var container = $(el).parent().find('.uploaded-files');
            var option_id = $(container).attr('data-id');

            var shtml = '';

            // -----------------------------------

            selection.map(function(attachment) {

                attachment = attachment.toJSON();

                shtml = shtml + '<input type="hidden" name="' + option_id + '[]" value="' + attachment.url + '" />'

                $(el).parent().parent().find('.cj-info').html('<p class="red">Files uploaded, please save settings.</p>');


                // Do something with attachment.id and/or attachment.url here
            });

            $(container).append(shtml);
            $("#" + form_id).trigger('click');

        });


        // Finally, open the modal
        file_frame.open();
    });


    // Responsive main navigation ##################################################################

    // Create the dropdown base
    $("<select />").appendTo("nav.cjfm-dropdown");

    // Create default option "Go to..."
    $("<option />", {
        "selected": "selected",
        "value": "",
        "text": "Go to..."
    }).appendTo("nav select");

    // Populate dropdown with menu items
    $("nav ul li a").each(function() {
        var el = $(this);
        if (el.attr("href") != '#') {
            $("<option />", {
                "value": el.attr("href"),
                "text": el.text()
            }).appendTo("nav select");
        }
    });

    $("nav.cjfm-dropdown select").change(function() {
        window.location = $(this).find("option:selected").val();
    });


})


jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) {
        jQuery("#settings-search-box").val('');
    }
});