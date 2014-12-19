<?php 
function cjfm_social_login( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		'service' => 'Facebook',
		'redirect_url' => site_url(),
		'button_text' => 'Connect via Facebook',
		'button_class' => null,
		'button_icon' => 'yes',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$services_array = array(
		'Facebook' => __('Facebook', 'cjfm'),
		'Twitter' => __('Twitter', 'cjfm'),
		'Google' => __('Google', 'cjfm'),
		'LinkedIn' => __('LinkedIn', 'cjfm'),
	);

	$yes_no_array = array(
		'yes' => __('Yes', 'nctheme'),
		'no' => __('No', 'nctheme'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This will enable social login for your website.', 'cjfm'),
		'service' => array(__('Select Social Service', 'cjfm'), 'dropdown', $services_array, __('Select social web service.', 'cjfm')),
		'redirect_url' => array(__('Redirect Url', 'cjfm'), 'text', null, __('You can specify a custom Url to redirect user after social connect. (default: homepage)', 'cjfm')),
		'button_icon' => array(__('Show Icon?', 'cjfm'), 'dropdown', $yes_no_array, __('You can enable or disable service icon on button.', 'cjfm')),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
		'button_class' => array(__('Submit Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(is_null($button_class)){
		$buton_css_class = 'cjfm-button cjfm-button-'.strtolower($service);
	}else{
		$buton_css_class = $button_class;
	}

	$errors = null;
	$appID = cjfm_get_option($service.'_appID');
	$appSecret = cjfm_get_option($service.'_appSecret');
	$button_icon_html = ($button_icon == 'yes') ? ' <i class="cjfm-icon cjfmicon-'.strtolower($service).'"></i>' : '';
	$display[] = '<a href="'.cjfm_string(site_url()).'social_connect='.$service.'&redirect='.$redirect_url.'" class="'.$buton_css_class.'">'.$button_icon_html.$button_text.'</a>';
	if($appID == '' || $appSecret == '' && current_user_can('manage_options')){
		$errors[] = cjfm_show_message('error', sprintf(__('Missing credentials for %s. Please enter valid credentials in plugin settings.', 'cjfm'), $service));
	}
	if($return == null){
		if(is_null($errors)){
			return implode('', $display);
		}else{
			return implode('', $errors);
		}
	}else{
	    return serialize($options);
	}
}
add_shortcode( 'cjfm_social_login', 'cjfm_social_login' );


function cjfm_social_connect(){
	global $wpdb;
	if(isset($_GET['social_connect'])){
		if(isset($_GET['redirect'])){
			cjfm_set_cookie('cjfm_social_redirect', $_GET['redirect'], 36000);
			cjfm_process_social_connect();
			die();
		}
	}
}
add_action('init', 'cjfm_social_connect');



function cjfm_process_social_connect(){
	global $wpdb, $current_user;
	session_start();

	$service = $_GET['social_connect'];

	$config_params['Facebook'] =  array ( 
		"enabled" => true,
		"keys"    => array ( 
			"id" => cjfm_get_option('Facebook_appID'),
			"secret" => cjfm_get_option('Facebook_appSecret'),
			"scope"   => "email, user_about_me", // optional
			"display" => "popup" // optional
		), 
	);
	$config_params['Twitter'] =  array ( 
		"enabled" => true,
		"keys"    => array ( 
			"key" => cjfm_get_option('Twitter_appID'),
			"secret" => cjfm_get_option('Twitter_appSecret'),
		), 
	);
	$config_params['Google'] =  array ( 
		"enabled" => true,
		"keys"    => array ( 
			"id" => cjfm_get_option('Google_appID'),
			"secret" => cjfm_get_option('Google_appSecret'),
		), 
	);
	$config_params['LinkedIn'] =  array ( 
		"enabled" => true,
		"keys"    => array ( 
			"key" => cjfm_get_option('LinkedIn_appID'),
			"secret" => cjfm_get_option('LinkedIn_appSecret'),
		), 
	);


	$config = array(
		"base_url" => cjfm_item_path('item_url').'/modules/shortcodes/hybridauth/',
		"providers" => $config_params
	);

	require_once( cjfm_item_path('item_dir')."/modules/shortcodes/hybridauth/Hybrid/Auth.php" );

	$user_profile = null;
	try{
		$hybridauth = new Hybrid_Auth( $config );
		$adapter = $hybridauth->authenticate(strtolower($service));
		$user_profile = $adapter->getUserProfile();
		
		$social_connect_info['cjfm_sprovider'] = $service;
		$social_connect_info['cjfm_suid'] = $user_profile->identifier;
		$cjfm_sprovider = $service;
		$cjfm_suid = $user_profile->identifier;
		
		$redirect_cookie = cjfm_get_cookie('cjfm_social_redirect');

		$redirect_url = ($redirect_cookie != '') ? $redirect_cookie : site_url();

		// Update user social service provider
		if(is_user_logged_in()){
			update_user_meta($current_user->ID, 'cjfm_suid', $cjfm_suid);
			update_user_meta($current_user->ID, 'cjfm_sprovider', $cjfm_sprovider);
			update_user_meta($current_user->ID, 'cjfm_social_profile', $user_profile);
			wp_redirect( $redirect_url, $status = 302 );
			exit;
		}


		// Check via email address
		if(isset($user_profile->email)){
			$existing_email = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_email = '{$user_profile->email}'");
			if(!is_null($existing_email)){
				$user_info = cjfm_user_info($existing_email->ID);
				if(!isset($user_info['cjfm_user_salt'])){
					wp_redirect( $redirect_url, $status = 302 );
					exit;
				}
				$user_pass = base64_decode($user_info['cjfm_user_salt']);
				$creds = array();
				$creds['user_login'] = $user_info['user_login'];
				$creds['user_password'] = $user_pass;
				$creds['remember'] = true;
				$user = wp_signon( $creds, false );
				if ( !is_wp_error($user) ){
					update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
					update_user_meta($user->ID, 'cjfm_last_login', time());
					update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
					update_user_meta($user->ID, 'cjfm_suid', $cjfm_suid);
					update_user_meta($user->ID, 'cjfm_sprovider', $cjfm_sprovider);
					update_user_meta($user->ID, 'cjfm_social_profile', $user_profile);
					$user_info = cjfm_user_info($user->ID);
					do_action('cjfm_login_done', $user_info);
					wp_redirect( $redirect_url, $status = 302 );
					exit;
				}	
			}
		}

		$check_existing_uid = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'cjfm_suid' AND meta_value = '{$cjfm_suid}'");

		if(is_null($check_existing_uid)){
			$register_url = cjfm_string(cjfm_generate_url('page_register')).'cjfm_social_connect='.$cjfm_sprovider.'&uid='.$cjfm_suid;
			wp_redirect($register_url);
			exit;
		}else{
			$user_info = cjfm_user_info($check_existing_uid->user_id);
			if(!isset($user_info['cjfm_user_salt'])){
				wp_redirect( $redirect_url, $status = 302 );
				exit;
			}else{

				$user_pass = base64_decode($user_info['cjfm_user_salt']);
				$creds = array();
				$creds['user_login'] = $user_info['user_login'];
				$creds['user_password'] = $user_pass;
				$creds['remember'] = true;
				$user = wp_signon( $creds, false );
				if ( !is_wp_error($user) ){
					update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
					update_user_meta($user->ID, 'cjfm_last_login', time());
					update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
					update_user_meta($user->ID, 'cjfm_suid', $cjfm_suid);
					update_user_meta($user->ID, 'cjfm_sprovider', $cjfm_sprovider);
					update_user_meta($user->ID, 'cjfm_social_profile', $user_profile);
					$user_info = cjfm_user_info($user->ID);
					do_action('cjfm_login_done', $user_info);
					wp_redirect( $redirect_url, $status = 302 );
					exit;
				}else{
					wp_redirect( $redirect_url, $status = 302 );
					exit;
				}

			}
		

		}
		

	}
	catch( Exception $e ){
		$error_url = cjfm_string(cjfm_generate_url('page_register')).'social_login_error=invalid_auth&provider='.$e->getMessage();
		wp_redirect($error_url);
		exit;
	}

}












