<?php 
function cjfm_form_login( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		//'redirect_url' => site_url(),
		'redirect_url' => cjfm_current_url(), // test this and implement
		'user_login_label' => __('Username or Email address:', 'cjfm'),
		'user_pass_label' => __('Password:', 'cjfm'),
		'button_text' => __('Login', 'cjfm'),
		'button_class' => null,
		'required_text' => __('(required)', 'cjfm'),
		'forgot_password_text' => __('Forgot password?', 'cjfm'),
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$yes_no_array = array(
		'yes' => 'yes',
		'no' => 'no',
	);
	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display a login form and redirect the user to specified redirect_url upon successful login.', 'cjfm'),
		'redirect_url' => array(__('Redirect URL: (Default: Homepage)', 'cjfm'), 'text', null, __('Specify a valid Url where you would like users to go after successful login', 'cjfm')),
		'user_login_label' => array(__('Username Label', 'cjfm'), 'text', null, __('This will replace the default username field label. (Default: Username or Email address:)', 'cjfm')),
		'user_pass_label' => array(__('Password Label', 'cjfm'), 'text', null, __('This will replace the default password field label. (Default: Password:)', 'cjfm')),
		'required_text' => array(__('Required Field Text', 'cjfm'), 'text', null, __('This text will be added to the username label. (Default: required)', 'cjfm')),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
		'forgot_password_text' => array(__('Forgot Password Link Text', 'cjfm'), 'text', null, __('This will replace forgot password link text. (Default: Forgot Password?)', 'cjfm')),
		'button_class' => array(__('Submit Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm'))
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'btn btn-'.$btn_color.' btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$redirect = $redirect_url;

	if(isset($_GET['redirect_url'])){
		$redirect = $_GET['redirect_url']; //.'loggedin=1';
	}elseif(isset($_GET['redirect'])){
		$redirect = $_GET['redirect']; //.'loggedin=1';
	}elseif(isset($_GET['redirect_to'])){
		$redirect = $_GET['redirect_to']; //.'loggedin=1';
	}elseif(!isset($_GET['redirect']) && !isset($_GET['redirect_url'])){
		$redirect = $redirect_url; //.'loggedin=1';
	}elseif(is_null($redirect)){
		$redirect = site_url(); //.'loggedin=1';
	}

	/*if(is_user_logged_in()){
		if(!isset($_GET['loggedin']) && $_GET['loggedin'] != 1){
			$location = $redirect;
			wp_redirect( $location, $status = 302 );
			exit;
		}
	}*/

	// PROCESS FORM

	$display[] = '';
	$process_form = 'false';

	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_do_login_nonce'],'cjfm_do_login') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}

	if(isset($_POST['do_login']) && $process_form == 'true'){

		$errors = null;

		$errors = cjfm_spam_protection_process($_POST, 'login');

		$user_info = cjfm_user_info($_POST['login_form_user_login']);

		$user_login = $user_info['user_login'];
		$user_pass = $_POST['login_form_user_pass'];

		if(!is_null($errors)){
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}else{

			if(!is_wp_error( wp_authenticate( $user_login, $user_pass ) )){
				
				$creds = array();
				$creds['user_login'] = $user_info['user_login'];
				$creds['user_password'] = $user_pass;
				$creds['remember'] = true;
				$user = wp_signon( $creds, is_ssl() );
				if ( !is_wp_error($user) ){

					/*wp_set_current_user( $userID, $user_login );
					wp_set_auth_cookie( $userID, true, false );*/
					do_action( 'wp_login', $user_login );

					update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
					update_user_meta($user->ID, 'cjfm_last_login', time());
					update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
					
					$user_info = cjfm_user_info($user->ID);
					do_action('cjfm_login_done', $user_info);
					
					wp_redirect( $redirect, $status = 302 );
					exit;
				}

			}else{
				$display[] = cjfm_show_message('error', __('Invalid username or password.', 'cjfm'));
			}

		}
	}


	$reset_password_url = cjfm_generate_url('page_reset_password');
	$logout_url = cjfm_generate_url('page_logout');
	$args = array(
		'user_login_label' => $user_login_label.' <span class="cjfm-required">'.$required_text.'</span>',
		'user_pass_label' => $user_pass_label.' <span class="cjfm-required">'.$required_text.'</span>',
		'button_text' => $button_text,
		'button_class' => $button_class,
		'button_suffix' => '<a class="button-suffix" href="'.$reset_password_url.'">'.$forgot_password_text.'</a>',
	);

	$form_fields['login_form'] = array(
		array(
		    'type' => 'text',
		    'id' => 'login_form_user_login',
		    'label' => $args['user_login_label'],
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'password',
		    'id' => 'login_form_user_pass',
		    'label' => $args['user_pass_label'],
		    'info' => '',
		    'class' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'hidden',
		    'id' => 'redirect_url',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => $redirect,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		cjfm_spam_protection_field('login'),
		array(
		    'type' => 'submit',
		    'id' => 'do_login',
		    'label' => $args['button_text'],
		    'info' => '',
		    'suffix' => $args['button_suffix'],
		    'prefix' => '',
		    'class' => $args['button_class'],
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	if(!is_user_logged_in()){
		$display[] = '<div class="cjfm-form cjfm-login-form '.$class.' ">';
		$display[] = '<form action="" method="post" class="cjfm-form" data-redirect="'.$redirect.'">';
		$display[] = wp_nonce_field('cjfm_do_login','cjfm_do_login_nonce', '', false);
		$display[] = cjfm_display_form($form_fields['login_form']);
		$display[] = '</form>';
		$display[] = '</div>';
	}else{
		$display[] = '<div class="cjfm-form cjfm-login-form '.$class.' ">';
		$display[] = sprintf(__('You are already logged in as %s. <a href="%s">Logout</a>', 'cjfm'), $current_user->user_login, $logout_url);
		$display[] = '</div>';
	}

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_form_login', 'cjfm_form_login' );