jQuery(document).ready(function($) {

    // Chosen
    var config = {
        '.chzn-select': {},
        '.chzn-select-deselect': {
            allow_single_deselect: true
        },
        '.chzn-select-no-single': {
            disable_search_threshold: 5
        },
        '.chzn-select-no-results': {
            allow_single_deselect: true,
            no_results_text: 'Oops, nothing found!'
        },
        '.chzn-select-width': {
            width: "95%"
        }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    $(".toggle-id").on('click', function() {
        var id = $(this).attr('href');
        $(id).toggle(0);
        return false;
    });

    // Quick search
    //$('input#settings-search-box').quicksearch('table.enable-search tr.searchable');

});