<?php 
function cjfm_user_meta( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		'meta_key' => 'user_login',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );


	$user_info = cjfm_user_info($current_user->ID);

	unset($user_info['cjfm_user_salt']);
	unset($user_info['cjfm_rp']);
	unset($user_info['user_pass']);

	if(!empty($user_info)){
		foreach ($user_info as $ukey => $uvalue) {
			$meta_key_array[$ukey] = $ukey;
		}
	}else{
		$meta_key_array['none'] = __('Not Applicable', 'cjfm');
	}

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display the current user data based on the specified key.', 'cjfm'),
		'meta_key' => array(__('User Meta Key', 'cjfm'), 'dropdown', $meta_key_array, __('Select user data key to return its value.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}


	if(is_user_logged_in()){
		$display[] = $user_info[$meta_key];
	}else{
		$display[] = '';
	}

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_meta', 'cjfm_user_meta' );