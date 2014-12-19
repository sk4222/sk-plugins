<?php 
function cjfm_form_reset_password( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		'redirect' => cjfm_generate_url('page_login'),
		'user_login_label' => __('Username or Email address', 'cjfm'),
		'required_text' => __('(required)', 'cjfm'),
		'button_text' => __('Continue', 'cjfm'),
		'button_class' => null,
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$type_array = array(
		'send-password' => __('Send login info via email. (Default)', 'cjfm'),
		'create-new-password' => __('Send email with link to set new password.', 'cjfm'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display recover password form.', 'cjfm'),
		'user_login_label' => array(__('Username Label', 'cjfm'), 'text', null, __('This will replace the default username field label. (Default: Username or Email address:)', 'cjfm')),
		'required_text' => array(__('Required Field Text', 'cjfm'), 'text', null, __('This text will be added to the username label. (Default: required)', 'cjfm')),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
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


	$from_name_saved = cjfm_get_option('from_name');
	$from_email_saved = cjfm_get_option('from_email');

	$from_name = (!empty($form_name_saved)) ? $form_name_saved : get_bloginfo( 'name' );
	$from_email = (!empty($from_email_saved)) ? $from_email_saved : get_option( 'admin_email' );

	// Send confirmatin link for reset password process

	if(isset($_POST['do_send_confirmation_link'])){

	$errors = null;
	$errors = cjfm_spam_protection_process($_POST, 'reset_password');

	$check_user_info = cjfm_user_info($_POST['recover_password_user_login']);

	if($_POST['recover_password_user_login'] == ''){
		$errors[] = __('Missing required fields.', 'cjfm');
	}elseif(empty($check_user_info)){
		$errors[] = __('No account found with this information.', 'cjfm');
	}

		if(!is_null($errors)){
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}else{
			$user_info = cjfm_user_info($_POST['recover_password_user_login']);
			$email_message = cjfm_parse_email('send_password_link_message', $user_info);
			
			$user_info = cjfm_user_info($_POST['recover_password_user_login']);

			if(!empty($user_info)){
				$uk = get_user_meta( $user_info['ID'], 'cjfm_reset_password_key', true );
				if($uk == ''){
					$activation_key = sha1(time().'-'.cjfm_unique_string());
					update_user_meta($user_info['ID'], 'cjfm_reset_password_key', $activation_key);
				}

				$updated_user_info = cjfm_user_info($_POST['recover_password_user_login']);

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
					'subject' => sprintf(__('Reset your %s password', 'cjfm'), get_bloginfo( 'name' )),
					'message' => $msg,
				);

				cjfm_email($email_data);
				$display[] = cjfm_show_message('success', __('We\'ve sent a confirmation link to your email address.<br />If you don\'t receive instructions within a few minutes, check your email\'s spam and junk filters.', 'cjfm'));
			}
		}

	}


	$form_fields['recover_password_form'] = array(
		array(
			'type' => 'text',
			'id' => 'recover_password_user_login',
			'label' => $user_login_label,
			'info' => '',
			'suffix' => '',
			'prefix' => '',
			'default' => cjfm_post_default('recover_password_user_login', ''),
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		cjfm_spam_protection_field('reset_password'),
		array(
			'type' => 'submit',
			'id' => 'do_send_confirmation_link',
			'label' => $button_text,
			'info' => '',
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'class' => $button_class,
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	$display[] = '<div class="cjfm-recover-password-form '.$class.'">';
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'rp' && $_GET['key'] != ''){

		$key = $_GET['key'];
		$user = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'cjfm_reset_password_key' AND meta_value = '{$key}'");



		if(!empty($user)){

			// Process new password form

			if(isset($_POST['do_set_password'])){

				$user_id = $_POST['user_id'];
				$new_password = $_POST['new_password'];
				$confirm_password = $_POST['confirm_password'];

				$user_info = cjfm_user_info($user_id);

				if($new_password == ''){
					$display[] = cjfm_show_message('error', __('New password field is required.', 'cjfm'));
				}elseif($new_password != $confirm_password){
					$display[] = cjfm_show_message('error', __('Password fields does not match.', 'cjfm'));
				}else{

					wp_set_password( $new_password, $user_id );
					update_user_meta($user_id, 'cjfm_user_salt', base64_encode($new_password));

					$new_activation_key = sha1($user_info['user_login'].'-'.cjfm_unique_string());
					update_user_meta($user_id, 'cjfm_reset_password_key', $new_activation_key);

					wp_redirect( $redirect );
					exit;
				}
			}

			// Set New Password
			$form_fields['new_password_form'] = array(
				array(
					'type' => 'hidden',
					'id' => 'user_id',
					'label' => '',
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => $user->user_id,
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'password',
					'id' => 'new_password',
					'label' => __('New Password', 'cjfm'),
					'info' => '',
					'class' => 'cjfm-pw',
					'suffix' => '<span class="cjfm-pw-strength"></span>',
					'prefix' => '',
					'default' => '',
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'password',
					'id' => 'confirm_password',
					'label' => __('Confirm Password', 'cjfm'),
					'info' => '',
					'class' => 'cjfm-pw',
					'suffix' => '<span class="cjfm-pw-strength"></span>',
					'prefix' => '',
					'default' => '',
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'submit',
					'id' => 'do_set_password',
					'label' => __('Change Password', 'cjfm'),
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => '',
					'class' => $button_class,
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
			);

			$display[] = '<form action="" method="post" class="cjfm-form"  autocomplete="off">';
			$display[] = cjfm_display_form($form_fields['new_password_form']);
			$display[] = '</form>';
		}else{
			$display[] = cjfm_show_message('error', __('Confirmation key invalid or expired, please try again.', 'cjfm'));
			$display[] = '<div class="cjfm-ajax-recover-password-form">';
			$display[] = '<form action="" method="post" class="cjfm-form"  autocomplete="off">';
			$display[] = cjfm_display_form($form_fields['recover_password_form']);
			$display[] = '</form>';
			$display[] = '</div>';
		}

	}else{
		$display[] = '<div class="cjfm-ajax-recover-password-form">';
		$display[] = '<form action="" method="post" class="cjfm-form"  autocomplete="off">';
		$display[] = cjfm_display_form($form_fields['recover_password_form']);
		$display[] = '</form>';
		$display[] = '</div>';
	}
	
	$display[] = '</div>';

	if(!is_user_logged_in()){
		$output = $display;
	}else{
		$reset_password_page = cjfm_get_option('page_reset_password');
		$current_page = ($reset_password_page == $post->ID) ? 'yes' : 'no';
		if($current_page != 'yes'){
			$location = cjfm_generate_url('page_profile');
			wp_redirect( $location, 302 );
		}else{
			$edit_profile_url = cjfm_generate_url('page_profile');
			$output[] = sprintf(__('<p>You are already loggedin to your account. <a href="%s">Click here</a> to update your profile.</p>', 'cjfm'), $edit_profile_url);
		}
		
	}

	if($return == null){
	    return implode('', $output);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_form_reset_password', 'cjfm_form_reset_password' );