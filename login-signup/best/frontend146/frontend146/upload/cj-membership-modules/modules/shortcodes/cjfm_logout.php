<?php 
global $post;
function cjfm_logout( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		'redirect' => cjfm_generate_url('page_login'),
		'type' => '', # direct / message with button
		'button_text' => 'Logout', # direct / message with button
		'button_class' => null,
		'class' => 'cjfm-login-form',
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
		'description' => __('This shortcode will logout the user.', 'cjfm'),
		'default_content' => __('This content will be displayed to the user if type is set to message.', 'cjfm'),
		'redirect' => array(__('Redirect URL:', 'cjfm'), 'text', null, __('User will be redirected to this Url after successful logout.<br>(Default: login page specified under plugin settings)', 'cjfm')),
		'type' => array(__('Logout Type <i style="color:red;">(required)</i>:', 'cjfm'), 'dropdown', $type_array, __('Select logout type', 'cjfm')),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
		'button_class' => array(__('Submit Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'btn btn-'.$btn_color.' btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$display[] = '';

	$logout_url = cjfm_string(cjfm_current_url('only-url')).'cjfm_action=logout&redirect='.urlencode($redirect);

	if($type == 'message'  && !isset($_GET['cjfm_action'])){
		
		$logout_button = '<p><a href="'.$logout_url.'" class="'.$button_class.'">'.$button_text.'</a></p>';
		$display[] = '<div class="cjfm-logout '.$class.' ">';
		$display[] = do_shortcode( $content );
		$display[] = $logout_button;
		$display[] = '</div>';
	}

	if($type == 'direct-logout' && !isset($_GET['cjfm_action'])){
		wp_redirect( $logout_url, $status = 302 );
		exit;
	}


	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'logout'){

		$user_info = cjfm_user_info($current_user->ID);
		do_action( 'cjfm_before_logout', $user_info );

		wp_logout();
		wp_redirect( $_GET['redirect'], $status = 302 );
		exit;
		
	}


	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_logout', 'cjfm_logout' );