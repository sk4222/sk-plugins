<?php 
/**
 * Ajax functions
 * 
 * Setup plugin functionality, scripts and other functions.
 *
 * @author      Mohit Aneja - CSSJockey.com
 * @category    Framework
 * @package     CSSJockey/Framework
 * @version     1.0
 * @since       1.0
 */

global $wpdb, $current_user;

add_action( 'wp_ajax_nopriv_cjfm_ajax_validation_login_form', 'cjfm_ajax_validation_login_form_callback' );
function cjfm_ajax_validation_login_form_callback() {
	global $wpdb;

	$form_data = parse_str($_REQUEST['formdata'], $pdata);

	$user_info = cjfm_user_info($pdata['login_form_user_login']);

	$user_login = $user_info['user_login'];
	$user_pass = $pdata['login_form_user_pass'];
	$spam_protection_errors = cjfm_spam_protection_process($pdata, 'login');

	if($user_login == '' && $user_pass == ''){
		echo cjfm_show_message('error', __('Missing required fields.', 'cjfm'));
	}elseif(!username_exists($user_login)){
		echo cjfm_show_message('error', __('Username does not exist.', 'cjfm'));
	}elseif(!is_null($spam_protection_errors)){
		echo cjfm_show_message('error', implode('<br>', $spam_protection_errors));
	}else{
		$user_auth = wp_authenticate($user_login, $user_pass);
		if(is_wp_error($user_auth)){
			echo cjfm_show_message('error', __('Username and password does not match with our records.', 'cjfm'));
		}else{
			$creds = array();
			$creds['user_login'] = $user_login;
			$creds['user_password'] = $user_pass;
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( !is_wp_error($user) ){
				update_user_meta($user->ID, 'cjfm_last_login', time());
				update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
				$user_info = cjfm_user_info($user->ID);
				do_action('cjfm_login_done', $user_info);
				echo 0;
			}
		}
	}
	die();
}


add_action( 'wp_ajax_nopriv_cjfm_ajax_validation_reset_password_form', 'cjfm_ajax_validation_reset_password_form_callback' );

function cjfm_ajax_validation_reset_password_form_callback() {
	global $wpdb;

	$form_data = parse_str($_REQUEST['formdata'], $pdata);

	$spam_errors = cjfm_spam_protection_process($pdata, 'reset_password');

	$user_info = cjfm_user_info($pdata['recover_password_user_login']);

	if($pdata['recover_password_user_login'] == ''){
		echo cjfm_show_message('error', __('Missing required fields.', 'cjfm'));
	}elseif(!is_null($spam_errors)){
		echo cjfm_show_message('error', implode('<br>', $spam_errors));
	}elseif(empty($user_info)){
		echo cjfm_show_message('error', __('Username does not exist.', 'cjfm'));
	}else{
		
		$user_info = cjfm_user_info($pdata['recover_password_user_login']);

		if(!empty($user_info)){
			$uk = get_user_meta( $user_info['ID'], 'cjfm_reset_password_key', true );
			if($uk == ''){
				$activation_key = sha1(time().'-'.cjfm_unique_string());
				update_user_meta($user_info['ID'], 'cjfm_reset_password_key', $activation_key);
			}

			$updated_user_info = cjfm_user_info($pdata['recover_password_user_login']);

			$reset_password_key = $updated_user_info['cjfm_reset_password_key'];
			$confirmation_link = cjfm_string(get_permalink(cjfm_get_option('page_reset_password'))).'cjfm_action=rp&key='.$reset_password_key;
			
			$from_name_saved = cjfm_get_option('from_name');
			$from_email_saved = cjfm_get_option('from_email');

			$from_name = (!empty($form_name_saved)) ? $form_name_saved : get_bloginfo( 'name' );
			$from_email = (!empty($from_email_saved)) ? $from_email_saved : get_option( 'admin_email' );

			$email_message = cjfm_parse_email('send_password_link_message', $updated_user_info);

			if(is_null($email_message)){
				$msg = sprintf(__('We received a reset password request for your %s account (%s).', 'cjfm'), get_bloginfo( 'name' ), $user_info['user_login']);
				$msg .= __('<p>Please follow the link below to reset your password:</p>', 'cjfm');
				$msg .= sprintf('<a href="%s">%s</a>', $confirmation_link, $confirmation_link);
				$msg .= __('<p>If you have not sent this request, please ignore this email and nothing will change on your account.</p>', 'cjfm');
			}else{
				$msg = $email_message;
			}
			$email_data = array(
				'to' => $user_info['user_email'],
				'from_name' => $from_name,
				'from_email' => $from_email,
				'subject' => sprintf(__('Reset your %s password.', 'cjfm'), get_bloginfo( 'name' )),
				'message' => $msg,
			);

			// echo '<pre>'; print_r($email_data); echo '</pre>';

			cjfm_email($email_data);
			echo cjfm_show_message('success', __('We\'ve sent a confirmation link to your email address.<br />If you don\'t receive instructions within a few minutes, check your email\'s spam and junk filters.', 'cjfm'));
			die();
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_cjfm_ajax_validation_register_form', 'cjfm_ajax_validation_register_form_callback' );
function cjfm_ajax_validation_register_form_callback() {
	global $wpdb;

	$form_data = parse_str($_REQUEST['formdata'], $pdata);

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';
	$must_verify_email = cjfm_get_option('verify_email_address');

	$errors = null;
	
	foreach ($pdata as $key => $value) {
		$$key = $value;
	}

	// Spam Protection
	$errors = cjfm_spam_protection_process($pdata, 'register');

	// Required fields
	$required = '';
	$required_fields = $wpdb->get_results("SELECT unique_id FROM $fields_table WHERE required = 'yes' AND field_type NOT IN('heading', 'paragraph', 'custom_html', 'user_avatar') ORDER BY sort_order ASC");
	foreach ($required_fields as $key => $value) {
		if(empty($pdata[$value->unique_id])){
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

	// Invitation Process
	$invite_email = null;
	if(isset($_GET['invitation_token'])){
		$invite_token = $_GET['invitation_token'];
		$invitation_info = $wpdb->get_row("SELECT * FROM $invitations_table WHERE invitation_key = '{$invite_token}'");
		if(!empty($invitation_info)){
			$invite_email = $invitation_info->user_email;
		}
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

			unset($pdata['do_create_account']);
			unset($pdata['cjfm_do_register_nonce']);

			$user_signup_temp_data = array(
				'user_email' => $pdata['user_email'],
				'activation_key' => $verify_email_activation_key,
				'user_data' => serialize($pdata),
				'dated' => date('Y-m-d H:i:s'),
			);

			cjfm_insert($user_signup_temp_table, $user_signup_temp_data);

			$verify_email_link = cjfm_string($register_page_url).'cjfm_verify='.$pdata['user_email'].'&key='.$verify_email_activation_key;
			$verify_email_message = cjfm_parse_email('verify_email_address_message', null, null, $verify_email_link);
			$verify_email_data = array(
				'to' => $pdata['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => cjfm_get_option('verify_email_subject'),
				'message' => $verify_email_message,
			);
			cjfm_email($verify_email_data);


			echo cjfm_show_message('success', __('We\'ve sent an email with a confirmation link to your email address.<br>Please check your email (also spam folder) in order to activate your account.', 'cjfm'));
		}else{
			$new_user_id = wp_insert_user( $user_data );
			cjfm_send_new_user_email_to_admin($new_user_id);

			foreach ($pdata as $key => $value) {
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

			if(!is_null($invite_email) && isset($invite_email)){
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
				
				echo 0;
			}

		}

	}else{
		echo cjfm_show_message('error', implode('<br>', $errors));
	}

	die();
}


function cjfm_ajax_url(){
	echo '<span id="cjfm-ajax-url" style="display:none;">'.admin_url( 'admin-ajax.php' ).'</span>';
}
add_action('wp_footer', 'cjfm_ajax_url');