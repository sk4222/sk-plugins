<?php

if(!function_exists('cj_tinymce_buttons')){

	if(is_admin()){
		add_action( 'init', 'cj_tinymce_buttons' );
	}

	function cj_tinymce_buttons() {
		add_filter( "mce_external_plugins", "cj_add_buttons" );
		add_filter( 'mce_buttons', 'cj_register_buttons' );
	}

	function cj_add_buttons( $plugin_array ) {
		$plugin_array['cjfm'] = cjfm_item_path('includes_url') . '/shortcode-generator/tiny-mce.js';
		return $plugin_array;
	}
	 
	function cj_register_buttons( $buttons ) {
		array_push( $buttons, 'cj-tinymce-button');
		return $buttons;
	}

}
 
if(!function_exists('cj_shortcode_generator_new')){
	function cj_shortcode_generator_new(){
		$shortcode_generator_path = cjfm_item_path('includes_dir') . '/shortcode-generator';
		$shortcode_generator_url = cjfm_item_path('includes_url') . '/shortcode-generator';
		require_once(sprintf('%s/cj_shortcode_generator_panel.php', $shortcode_generator_path));
		echo '<link rel="stylesheet" href="'.$shortcode_generator_url.'/style.css" />';
	}
	add_action('admin_footer', 'cj_shortcode_generator_new');
}