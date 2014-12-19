<?php 
function cjfm_page_links( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		'page' => 'none',
		'link_text' => null,
		'link_target' => '_self',
		'redirect' => null,
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$page_array = array(
		'page_login' => __('Login Page', 'cjfm'),
		'page_register' => __('Register Page', 'cjfm'),
		'page_reset_password' => __('Reset Password Page', 'cjfm'),
		'page_logout' => __('Logout Page', 'cjfm'),
		'page_profile' => __('User Profile Page', 'cjfm'),
	);

	$target_array = array(
		'_self' => __('Same Window', 'cjfm'),
		'_blank' => __('New Window', 'cjfm'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display a link to the page selected under Page Setup plugin settings page.', 'cjfm'),
		'page' => array(__('Page', 'cjfm'), 'dropdown', $page_array, __('Select a page for which you would like to generate the link.', 'cjfm')),
		'redirect' => array(__('Redirect Url', 'cjfm'), 'text', null, __('Specify redirect Url if you wish to send the user to a specific page otherwise user will be redirected back to this page.', 'cjfm')),
		'link_text' => array(__('Link Text', 'cjfm'), 'text', null, __('Specify link text for the generated link.', 'cjfm')),
		'link_target' => array(__('Open link in', 'cjfm'), 'dropdown', $target_array, __('Select if you would like this link to open in same or new window/tab.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$redirect_url = ($redirect == null) ? cjfm_current_url('only-url') : $redirect;

	$page_url['none'] = '#no-page';
	
	$page_url['login_url'] = cjfm_generate_url('page_login');
	$page_url['register_url'] = cjfm_generate_url('page_register');
	$page_url['logout_url'] = cjfm_generate_url('page_logout');

	$page_url['reset_password_url'] = cjfm_generate_url('page_reset_password');
	$page_url['profile_url'] = cjfm_generate_url('page_profile');

	$link_url = ($page != 'none') ? cjfm_string(cjfm_generate_url($page)).'redirect='.$redirect_url : '#no-page-specified';
	$link_text = (!is_null($link_text)) ? $link_text : get_the_title(cjfm_get_option($page));


	$display[] = '<a href="'.$link_url.'" target="'.$link_target.'">'.$link_text.'</a>';

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_page_links', 'cjfm_page_links' );