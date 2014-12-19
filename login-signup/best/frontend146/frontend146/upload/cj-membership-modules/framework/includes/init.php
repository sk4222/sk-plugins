<?php 
function cjfm_framework_init(){
	require_once(sprintf('%s/db_setup.php', cjfm_item_path('includes_dir')));
	require_once(sprintf('%s/widget_options_form.php', cjfm_item_path('includes_dir')));
	require_once(sprintf('%s/dashboard-widget.php', cjfm_item_path('includes_dir')));
	require_once(sprintf('%s/bootstrap-walker.php', cjfm_item_path('includes_dir')));
	require_once(sprintf('%s/hooks.php', cjfm_item_path('includes_dir')));
	require_once(sprintf('%s/admin_ajax.php', cjfm_item_path('includes_dir')));
}
add_action( 'init', 'cjfm_framework_init' );

add_action('cjfm_functions', 'cjfm_install_plugins');
add_action('cjfm_functions', 'cjfm_load_modules');
add_action('cjfm_functions', 'cjfm_shortcode_generator');

add_action( 'init', 'cjfm_register_post_types' );
add_action( 'init', 'cjfm_register_taxonomies', 0 );
add_action( 'init', 'cjfm_meta_boxes', 9999 );

$cjfm_item_info = cjfm_item_info();
require_once(sprintf('%s/functions/'.$cjfm_item_info['item_type'].'_setup.php', cjfm_item_path('modules_dir')));

require_once(sprintf('%s/push_notifications.php', cjfm_item_path('includes_dir')));

add_filter('widget_text', 'do_shortcode');