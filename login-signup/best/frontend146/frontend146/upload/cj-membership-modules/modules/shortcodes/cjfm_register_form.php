<?php 
function cjfm_form_register( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		"button_text" => "Create new account",
		'button_class' => null,
		"class" => "",
		'redirect_url' => site_url(),
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );


	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';
	$logout_url = cjfm_generate_url('page_logout');

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display Registration Form with custom fields specified under Configuration >> Custom Form Fields.', 'cjfm'),
		'redirect_url' => array(__('Redirect URL (default: homepage)', 'cjfm'), 'text', null, __('User will be redirected to this Url after successful registration.', 'cjfm')),
		"button_text" => array(__('Button Text (default: Create new account)', 'cjfm'), 'text', null, __('Default Button text will be replaced by this value.', 'cjfm')),
		"button_class" => array(__('Button CSS Class', 'cjfm'), 'text', null, __('Specify custom CSS classes for the button', 'cjfm')),
		"class" => array(__('Container CSS Class', 'cjfm'), 'text', null, __('Specify custom CSS class for the form container.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(isset($_GET['redirect_url'])){
		$redirect = $_GET['redirect_url'];
	}elseif(isset($_GET['redirect'])){
		$redirect = $_GET['redirect'];
	}elseif(isset($_GET['redirect_to'])){
		$redirect = $_GET['redirect_to'];
	}elseif(!isset($_GET['redirect']) && !isset($_GET['redirect_url'])){
		$redirect = $redirect_url;
	}elseif(is_null($redirect)){
		$redirect = site_url();
	}

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));
	$current_page = ($register_page == $post->ID) ? 'yes' : 'no';
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';
	$must_verify_email = cjfm_get_option('verify_email_address');


	/*if(is_user_logged_in()){
		if(!isset($_GET['loggedin']) && $_GET['loggedin'] != 1){
			$location = $redirect;
			wp_redirect( $location, $status = 302 );
			exit;
		}
	}*/

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'btn btn-'.$btn_color.' btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$display[] = '';

	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_do_register_nonce'],'cjfm_do_register') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}

	// Invitation Process
	$invite_email = null;
	if(isset($_GET['invitation_token'])){
		$invite_token = $_GET['invitation_token'];
		$invitation_info = $wpdb->get_row("SELECT * FROM $invitations_table WHERE invitation_key = '{$invite_token}'");
		if(!empty($invitation_info)){
			$invite_email = $invitation_info->user_email;
		}else{
			wp_redirect( cjfm_current_url('only-url'), $status = 302 );
			exit;
		}
	}

	// Verify email registration process
	if(isset($_GET['cjfm_verify']) && isset($_GET['key'])){

		$check_user_email =  $_GET['cjfm_verify'];
		$check_user_key =  $_GET['key'];

		$check_key = $wpdb->get_row("SELECT * FROM $user_signup_temp_table WHERE user_email = '{$check_user_email}' AND activation_key = '{$check_user_key}'");
		
		if(!empty($check_key)){

			$wordpress_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber');
			$user_data_fields = array('user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf');

			$user_data = unserialize($check_key->user_data);

			if(!isset($user_data['user_pass'])){
				$password = wp_generate_password(10, false, false);
			}else{
				$password = $user_data['user_pass'];
			}

			$display_name = (isset($user_data['display_name'])) ? $user_data['display_name'] : $user_data['user_login'];

			$new_user_data = array(
				'user_login' => $user_data['user_login'],
				'user_email' => $user_data['user_email'],
				'user_pass' => $password,
				'user_url' => @$user_data['user_url'],
				'display_name' => $display_name,
				'user_nicename' => $display_name,
			);

			if(!username_exists($user_data['user_login'])){
				$new_user_id = wp_insert_user($new_user_data);
				cjfm_send_new_user_email_to_admin($new_user_id);
			}else{
				$new_user_id = cjfm_user_info($user_data['user_login'], 'ID');
			}


			foreach ($user_data as $key => $value) {
				if(in_array($key, $user_data_fields)){
					unset($user_data[$key]);
				}
			}

			if(!empty($user_data)){
				foreach ($user_data as $key => $value) {
					update_user_meta($new_user_id, $key, $value);
				}
			}

			update_user_meta($new_user_id, 'cjfm_user_salt', base64_encode($password));
			update_user_meta($new_user_id, 'cjfm_rp', base64_encode($password));
			update_user_meta($new_user_id, 'cjfm_last_login', time());
			update_user_meta($new_user_id, 'cjfm_login_ip', cjfm_current_ip_address());

			$new_user_info = cjfm_user_info($new_user_id);

			$email_subject = cjfm_get_option('welcome_email_subject');
			$email_message = cjfm_parse_email('welcome_email_message', $new_user_info);

			$email_data = array(
				'to' => $new_user_info['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $email_subject,
				'message' => $email_message,
			);
			cjfm_email($email_data);

			delete_user_meta($new_user_id, 'cjfm_rp');

			$wpdb->query("DELETE FROM $user_signup_temp_table WHERE user_email = '{$check_user_email}' AND activation_key = '{$check_user_key}'");

			// Login new user.
			$creds = array();
			$creds['user_login'] = $new_user_data['user_login'];
			$creds['user_password'] = $password;
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( !is_wp_error($user) ){
				update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($password));
				update_user_meta($user->ID, 'cjfm_last_login', time());
				update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());

				$user_info = cjfm_user_info($user->ID);
				do_action('cjfm_registeration_done', $user_info);
				
				wp_redirect( $redirect, $status = 302 );
				exit;
			}

		}else{
			$temp_email_address = @$_GET['cjfm_verify'];
			$wpdb->query("DELETE FROM $user_signup_temp_table WHERE user_email = '{$temp_email_address}'");
			return cjfm_show_message('error', sprintf(__('Invalid activation key. <a href="%s">Please try again.</a>', 'cjfm'), $register_page_url));
		}
	}
	

	if(isset($_POST['do_create_account']) && $process_form == 'true'){
		
		$errors = null;
		//unset($_POST['do_create_account']);
		
		foreach ($_POST as $key => $value) {
			$$key = $value;
		}

		// Spam Protection

		$errors = cjfm_spam_protection_process($_POST, 'register');

		// Required fields
		$required = '';
		$required_fields = $wpdb->get_results("SELECT unique_id FROM $fields_table WHERE required = 'yes' AND field_type NOT IN('heading', 'paragraph', 'custom_html', 'user_avatar') ORDER BY sort_order ASC");
		foreach ($required_fields as $key => $value) {
			if(empty($_POST[$value->unique_id])){
				$errors['missing'] = __('Missing required fields.', 'cjfm');
			}
		}

		// Username Checks
		if(!validate_username( $user_login )){
			$errors[] = __('Username field is invalid.', 'cjfm');
		}elseif(username_exists( $user_login )){
			$errors[] = __('Username already registered, try another one.', 'cjfm');
		}

		// Email Checks
		if(!is_email($user_email)){
			$errors[] = __('Email address invalid, please check and try again.', 'cjfm');
		}elseif(email_exists( $user_email )){
			$errors[] = __('Email address already registered.', 'cjfm');	
		}

		// Password Checks
		if(strlen($user_pass) < cjfm_get_option('password_length')){
			$errors[] = sprintf(__('Password must be %d characters long.', 'cjfm'), cjfm_get_option('password_length'));
		}elseif($user_pass != $user_pass_conf){
			$errors[] = __('Password and Confirm password field does not match.', 'cjfm');
		}




		if(is_null($errors)){


			$user_data['user_login'] = @$user_login;
			$user_data['user_email'] = @$user_email;
			$user_data['user_pass'] = @$user_pass;

			$wordpress_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber');
			$user_data_fields = array('user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf');
			foreach ($wordpress_fields as $wkey => $wvalue) {
				$user_data[$wvalue] = @$$wvalue;
			}
			$user_data['nickname'] = @$display_name;

			$usermeta['cjfm_user_salt'] = base64_encode($user_pass);

			// Must verify email address process

			if($must_verify_email == 'enable' && cjfm_get_option('register_type') == 'normal'){

				$verify_email_activation_key = sha1(cjfm_unique_string());

				unset($_POST['do_create_account']);
				unset($_POST['cjfm_do_register_nonce']);

				$user_signup_temp_data = array(
					'user_email' => $_POST['user_email'],
					'activation_key' => $verify_email_activation_key,
					'user_data' => serialize($_POST),
					'dated' => date('Y-m-d H:i:s'),
				);
				cjfm_insert($user_signup_temp_table, $user_signup_temp_data);

				$verify_email_link = cjfm_string($register_page_url).'cjfm_verify='.$_POST['user_email'].'&key='.$verify_email_activation_key;
				$verify_email_message = cjfm_parse_email('verify_email_address_message', null, null, $verify_email_link);
				$verify_email_data = array(
					'to' => $_POST['user_email'],
					'from_name' => cjfm_get_option('from_name'),
					'from_email' => cjfm_get_option('from_email'),
					'subject' => cjfm_get_option('verify_email_subject'),
					'message' => $verify_email_message,
				);
				cjfm_email($verify_email_data);
				return cjfm_show_message('success', cjfm_get_option('verify_email_onscreen_message'));
			}else{
				$new_user_id = wp_insert_user( $user_data );
				cjfm_send_new_user_email_to_admin($new_user_id);
			}

			foreach ($_POST as $key => $value) {
				if($key != 'cjfm_do_register_nonce' && $key != 'do_create_account' && !in_array($key, $wordpress_fields) && !in_array($key, $user_data_fields)){
					$usermeta[$key] = $value;
				}
			}

			foreach ($usermeta as $key => $value) {
				update_user_meta($new_user_id, $key, $value);
			}

			update_user_meta($new_user_id, 'cjfm_last_login', time());
			update_user_meta($new_user_id, 'cjfm_login_ip', cjfm_current_ip_address());

			$new_user_info = cjfm_user_info($new_user_id);

			$new_activation_key = sha1($new_user_info['user_login'].'-'.cjfm_unique_string());
			update_user_meta( $new_user_id, 'cjfm_reset_password_key', $new_activation_key);

			if(cjfm_get_option('register_type') == 'approvals'){
				update_user_meta($new_user_id, 'cjfm_account_approved', 0);
			}

			// Send registration email.
			$new_user_info = cjfm_user_info($new_user_id);
			if(cjfm_get_option('register_type') == 'approvals'){

				// Admin Email
				$admin_email_data = array(
					'to' => get_option( 'admin_email' ),
					'from_name' => get_bloginfo( 'name' ),
					'from_email' => get_option( 'admin_email' ),
					'subject' => cjfm_get_option('awaiting_approval_subject_admin'),
					'message' => cjfm_parse_email('awaiting_approval_message_admin', $new_user_info),
				);
				cjfm_email($admin_email_data);

				$email_subject = cjfm_get_option('awaiting_approval_subject');
				$email_message = cjfm_parse_email('awaiting_approval_message', $new_user_info);

			}else{
				$email_subject = cjfm_get_option('welcome_email_subject');
				$email_message = cjfm_parse_email('welcome_email_message', $new_user_info);
			}

			$email_data = array(
				'to' => $user_email,
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $email_subject,
				'message' => $email_message,
			);
			cjfm_email($email_data);

			if(!is_null($invite_email)){
				$wpdb->query("DELETE FROM $invitations_table WHERE user_email = '{$invite_email}'");
			}

			// Login new user.
			$creds = array();
			$creds['user_login'] = $user_login;
			$creds['user_password'] = $user_pass;
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( !is_wp_error($user) ){
				update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
				update_user_meta($user->ID, 'cjfm_last_login', time());
				update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());

				$user_info = cjfm_user_info($user->ID);
				do_action('cjfm_registeration_done', $user_info);
				
				wp_redirect( $redirect, $status = 302 );
				exit;
			}

		}else{
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}

	}

	// Social Login Message
	if(isset($_GET['cjfm_social_connect']) && $_GET['cjfm_social_connect'] != '' && $_GET['uid'] != ''){
		$display[] = cjfm_show_message('success', sprintf(__('<span class="cjfm-social-connect-register"><b>You are connected via %s.<br>Please complete the registeration.</b> <a href="%s">Cancel</a></span>', 'cjfm'), $_GET['cjfm_social_connect'], cjfm_string(site_url()).'cjfm_action=cancel_social_connect'));
	}

	$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table ORDER BY sort_order ASC");

	if(!empty($form_fields_query)){

		if(cjfm_get_option('register_password_type') == 'disable'){
			$default_password = strtoupper(cjfm_unique_string());
		}else{
			$default_password = '';
		}

		foreach ($form_fields_query as $key => $field) {

			$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode');
			$password_fields = array('user_pass', 'user_pass_conf');
			$file_fields = array('user_avatar');
			$textarea_fields = array('textarea', 'description');
			$country_fields = array('cjfm_country');

			if(in_array($field->field_type, $text_fields)){
				$field_type = 'text';
			}elseif(in_array($field->field_type, $password_fields)){
				$field_type = 'password';
			}elseif(in_array($field->field_type, $textarea_fields)){
				$field_type = 'textarea';
			}elseif(in_array($field->field_type, $country_fields)){
				$field_type = 'select';
			}elseif(in_array($field->field_type, $file_fields)){
				$field_type = 'upload';
			}else{
				$field_type = $field->field_type;	
			}

			if(!is_null($invite_email) && $field->unique_id == 'user_email'){
				$field_type = 'text-readonly';
			}

			if($field->options != 'NA'){
				$fopts = explode("\n", $field->options);
				$field_options = null;
				foreach ($fopts as $okey => $ovalue) {
					$field_options[trim($ovalue)] = trim($ovalue);
				}
			}elseif($field->field_type == 'cjfm_country'){
				$field_options = cjfm_countries_array();
			}else{
				$field_options = '';
			}

			if($field->field_type == 'heading'){
				$default_value = $field->label;
			}elseif($field->field_type == 'paragraph'){
				$default_value = $field->description;
			}elseif($field->field_type == 'custom_html'){
				$default_value = stripcslashes($field->description);
			}elseif(!is_null($invite_email) && $field->field_type == 'user_email'){
				$default_value = $invite_email;
			}else{
				$default_value = @$_POST[$field->unique_id];
			}

			if($field->required == 'yes'){
				$required = '<span class="required">'.__('(required)', 'cjfm').'</span>';
			}else{
				$required = '';
			}

			if($field->enabled == 'yes' && $field->register == 'yes'){
				if(cjfm_get_option('register_password_type') == 'disable' && $field->field_type == 'user_pass'){
					// Password Type Config
					$form_fields[$key] = array(
					    'type' => 'hidden',
					    'id' => $field->unique_id,
					    'label' => '',
					    'info' => '',
					    'suffix' => '',
					    'prefix' => '',
					    'default' => $default_password,
					    'options' => '', // array in case of dropdown, checkbox and radio buttons
					);	
				}elseif(cjfm_get_option('register_password_type') == 'disable' && $field->field_type == 'user_pass_conf'){
					// Password Type Config
					$form_fields[$key] = array(
					    'type' => 'hidden',
					    'id' => $field->unique_id,
					    'label' => '',
					    'info' => '',
					    'suffix' => '',
					    'prefix' => '',
					    'default' => $default_password,
					    'options' => '', // array in case of dropdown, checkbox and radio buttons
					);	
				}else{
					if($field->unique_id == 'user_pass'){
						$form_fields[$key] = array(
						    'type' => $field_type,
						    'id' => $field->unique_id,
						    'label' => $field->label.' '.$required,
						    'info' => $field->description,
						    'class' => 'cjfm-pw',
						    'suffix' => '<span class="cjfm-pw-strength"></span>',
						    'prefix' => '',
						    'default' => cjfm_post_default($field->unique_id, ''),
						    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
						);	
					}
					if($field->unique_id == 'user_pass_conf'){
						$form_fields[$key] = array(
						    'type' => $field_type,
						    'id' => $field->unique_id,
						    'label' => $field->label.' '.$required,
						    'info' => $field->description,
						    'class' => 'cjfm-pw',
						    'suffix' => '<span class="cjfm-pw-strength"></span>',
						    'prefix' => '',
						    'default' => cjfm_post_default($field->unique_id, ''),
						    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
						);	
					}
					if($field->unique_id != 'user_pass' && $field->unique_id != 'user_pass_conf'){
						$form_fields[$key] = array(
						    'type' => $field_type,
						    'id' => $field->unique_id,
						    'label' => $field->label.' '.$required,
						    'info' => $field->description,
						    'suffix' => '',
						    'prefix' => '',
						    'default' => cjfm_post_default($field->unique_id, $default_value),
						    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
						);	
					}
				}
			}
		}

		if(isset($_GET['cjfm_social_connect']) && $_GET['cjfm_social_connect'] != '' && $_GET['uid'] != ''){
			$form_fields['social_login_id'] = array(
			    'type' => 'hidden',
			    'id' => 'cjfm_suid',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => $_GET['uid'],
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
		}
		if(isset($_GET['cjfm_social_connect']) && $_GET['cjfm_social_connect'] != '' && $_GET['uid'] != ''){
			$form_fields['social_login_provider'] = array(
			    'type' => 'hidden',
			    'id' => 'cjfm_sprovider',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => $_GET['cjfm_social_connect'],
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
		}

		$form_fields['spam_protection'] = cjfm_spam_protection_field('register');

		$form_fields['submit'] = array(
		    'type' => 'submit',
		    'id' => 'do_create_account',
		    'label' => $button_text,
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => $button_class,
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);

	}
	
	if(cjfm_get_option('register_type') == 'invitations' && !isset($_GET['invitation_token'])){
		$display[] = cjfm_get_option('invititations_register_form_message');
		$display[] = (cjfm_get_option('request_invitation_form') == 'yes') ? cjfm_show_request_invitation_form($class, $button_class, $redirect) : '';
	}else{
		if(!is_user_logged_in()){
			$display[] = '<div class="cjfm-ajax-register-form '.$class.'">';
			$display[] = '<form action="" method="post" data-redirect="'.$redirect.'" enctype="multipart/form-data" class="cjfm-form">';
			$display[] = cjfm_social_login_errors();
			$display[] = wp_nonce_field('cjfm_do_register','cjfm_do_register_nonce', '', false);
			$display[] = cjfm_display_form($form_fields);
			$display[] = '</form>';	
			$display[] = '</div>';
		}else{
			$display[] = '<div class="'.$class.'">';
			$display[] = sprintf(__('You are already logged in as %s. <a href="%s">Logout</a>', 'cjfm'), $current_user->user_login, $logout_url);
			$display[] = '</div>';
		}
	}

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_form_register', 'cjfm_form_register' );



function cjfm_show_request_invitation_form($class = null, $button_class = null, $redirect){
	global $wpdb;
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));

	$fields = $wpdb->get_results("SELECT * FROM $fields_table WHERE invitation = 'yes' AND enabled = 'yes'");

	if(isset($_POST['do_request_invitation'])){

		$errors = null;
		//unset($_POST['do_create_account']);
		
		foreach ($_POST as $key => $value) {
			$$key = $value;
		}

		// Spam Protection

		$errors = cjfm_spam_protection_process($_POST, 'register');

		// Required fields
		$required = '';
		$required_fields = $wpdb->get_results("SELECT unique_id FROM $fields_table WHERE required = 'yes' AND invitation = 'yes' ORDER BY sort_order ASC");
		foreach ($required_fields as $key => $value) {
			if(isset($_POST[$value->unique_id]) && empty($_POST[$value->unique_id])){
				$errors['missing'] = __('Missing required fields.', 'cjfm');
			}
		}

		// Email Checks
		if(!is_email($user_email)){
			$errors[] = __('Email address invalid, please check and try again.', 'cjfm');
		}elseif(email_exists( $user_email )){
			$errors[] = __('Email address already registered.', 'cjfm');
		}

		if(is_null($errors)){

			$invitation_key = base64_encode(serialize(array($_POST['user_email'], cjfm_unique_string())));

			$invitation_data = array(
				'user_email' => $_POST['user_email'],
				'invitation_key' => $invitation_key,
				'user_data' => serialize($_POST),
				'dated' => date('Y-m-d H:i:s'),
			);
			cjfm_insert($invitations_table, $invitation_data);

			// New invitation notification to admin

			$admin_invitiation_notification_message = __('<p>Dear Admin,</p>', 'cjfm');
			$admin_invitiation_notification_message .= sprintf(__('<p>New invitation request on your website %s</p>', 'cjfm'), get_bloginfo('name'));
			$admin_invitiation_notification_message .= sprintf(__('<a href="%s">View Invitation</a>', 'cjfm'), cjfm_callback_url('cjfm_invitations'));
			$admin_invitation_email_data = array(
				'to' => get_option('admin_email'),
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => sprintf(__('[%s] New invitation request', 'cjfm'), get_bloginfo('name')),
				'message' => $admin_invitiation_notification_message,
			);
			cjfm_email($admin_invitation_email_data);


			$location = cjfm_string(cjfm_current_url('only-url')).'cjfm_msg=success';
			wp_redirect( $location , $status = 302 );
			exit;

		}else{
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}

	}

	if(isset($_GET['cjfm_msg']) && $_GET['cjfm_msg'] == 'success'){
		$display[] = cjfm_show_message('success', __('Thank You! Your request has been received.', 'cjfm'));
	}



	if(!empty($fields)){
		foreach ($fields as $key => $field) {

			$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode');
			$password_fields = array('user_pass', 'user_pass_conf');
			$file_fields = array('user_avatar');
			$textarea_fields = array('textarea', 'description');
			$country_fields = array('cjfm_country');

			if(in_array($field->field_type, $text_fields)){
				$field_type = 'text';
			}elseif(in_array($field->field_type, $password_fields)){
				$field_type = 'password';
			}elseif(in_array($field->field_type, $textarea_fields)){
				$field_type = 'textarea';
			}elseif(in_array($field->field_type, $country_fields)){
				$field_type = 'select';
			}elseif(in_array($field->field_type, $file_fields)){
				$field_type = 'upload';
			}else{
				$field_type = $field->field_type;	
			}

			if($field->options != 'NA'){
				$fopts = explode("\n", $field->options);
				$field_options = null;
				foreach ($fopts as $okey => $ovalue) {
					$field_options[trim($ovalue)] = trim($ovalue);
				}
			}elseif($field->field_type == 'cjfm_country'){
				$field_options = cjfm_countries_array();
			}else{
				$field_options = '';
			}

			if($field->required == 'yes'){
				$required = '<span class="required">'.__('(required)', 'cjfm').'</span>';
			}else{
				$required = '';
			}

			$form_fields[$key] = array(
			    'type' => $field_type,
			    'id' => $field->unique_id,
			    'label' => $field->label.' '.$required,
			    'info' => $field->description,
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default($field->unique_id, ''),
			    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
			);	
		}

		$form_fields['spam_protection'] = cjfm_spam_protection_field('invitation');

		$form_fields['submit'] = array(
		    'type' => 'submit',
		    'id' => 'do_request_invitation',
		    'label' => __('Request an invite', 'cjfm'),
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => $button_class,
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);


		$display[] = '<div class="'.$class.'">';
		$display[] = '<form action="'.$register_page_url.'" method="post" data-redirect="'.$redirect.'" enctype="multipart/form-data" class="cjfm-form">';
		$display[] = cjfm_display_form($form_fields);
		$display[] = '</form>';	
		$display[] = '</div>';	

		return implode('', $display);
	}

}


function cjfm_social_login_errors(){
	$error_messages = null;
	if(isset($_GET['social_login_error'])){
		$error_messages[] = __('Could not connect via selected social network.', 'cjfm');
	}
	if(is_array($error_messages)){
		return cjfm_show_message('error', implode('<br>', $error_messages));
	}else{
		return null;
	}
}

function cjfm_send_new_user_email_to_admin($new_user_id){
	global $wpdb, $current_user;

	$cjfm_custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $cjfm_custom_fields_table");

	$new_user_info = cjfm_user_info($new_user_id);

	$email_message = __('<p>Dear Admin,</p>', 'cjfm');
	$email_message .= sprintf(__('<p>New user registration on your website %s</p>', 'cjfm'), get_bloginfo('name'));
	$email_message .= sprintf(__('Username: %s<br>Email Address: %s<br>', 'cjfm'), $new_user_info['user_login'], $new_user_info['user_email']);
	
	$unset_fields = array(
		'user_login',
		'user_email',
		'user_pass',
		'user_pass_conf',
		'user_avatar',
	);

	$additional_fields = null;
	foreach ($custom_fields as $key => $value) {
		if(!in_array($value->field_type, $unset_fields)){
			if(isset($new_user_info[$value->field_type])){
				$additional_fields .= $value->label.': '.$new_user_info[$value->field_type].'<br>';
			}
		}
	}
	if(!is_null($additional_fields)){
		$email_message .= __('<p><strong>Additional registration form fields:</strong></p>', 'cjfm');
		$email_message .= $additional_fields;
	}
	
	$email_data = array(
		'to' => get_option('admin_email'),
		'from_name' => cjfm_get_option('from_name'),
		'from_email' => cjfm_get_option('from_email'),
		'subject' => sprintf(__('[%s] New User Registration', 'cjfm'), get_bloginfo('name')),
		'message' => $email_message,
	);

	cjfm_email($email_data);	
}






