<?php 
global $post;
function cjfm_user_content( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		'message' => cjfm_get_option('restrict_login_msg'),
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$yes_no_array = array(
		'yes' => 'yes',
		'no' => 'no',
	);

	$type_array = array(
		'direct-logout' => __('Logout directly without displaying any message', 'cjfm'),
		'message' => __('Display a custom message with logout button.', 'cjfm'),
	);

	$options = array(
		'stype' => 'closed', // single or closed
		'description' => __('Text within this shortcode will be displayed only to logged in users.', 'cjfm'),
		'default_content' => 'User only content goes here..', // single or closed
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm'))
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$msg = '<div class="cjfm-login-required">';
	$msg .= $message;
	$msg .= '</div>';

	$display[] = (is_user_logged_in()) ? do_shortcode( $content ) : $msg;

	


	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_content', 'cjfm_user_content' );



function cjfm_visitor_content( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$yes_no_array = array(
		'yes' => 'yes',
		'no' => 'no',
	);

	$type_array = array(
		'direct-logout' => __('Logout directly without displaying any message', 'cjfm'),
		'message' => __('Display a custom message with logout button.', 'cjfm'),
	);

	$options = array(
		'stype' => 'closed', // single or closed
		'description' => __('Text within this shortcode will be displayed only to visitors who are <b>not logged in</b>  to your website.', 'cjfm'),
		'default_content' => 'Visitors only content goes here..', // single or closed
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm'))
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$msg = '<div class="cjfm-login-not-required">';
	$msg .= do_shortcode( $content );
	$msg .= '</div>';

	$display[] = (!is_user_logged_in()) ? $msg : '';

	


	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_visitor_content', 'cjfm_visitor_content' );