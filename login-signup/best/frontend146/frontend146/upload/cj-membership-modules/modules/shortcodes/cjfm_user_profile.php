<?php 
function cjfm_user_profile( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		'redirect' => cjfm_current_url('only-url'),
		'button_text' => __('Update Profile', 'cjfm'),
		'button_class' => null,
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$enable_disable_array =  array(
		'enable' => __('Enable', 'cjfm'),
		'disable' => __('Disable', 'cjfm'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display edit profile form and will include custom fields specified to be displayed on edit profile page.', 'cjfm'),
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

	$login_url = cjfm_generate_url('page_login');

	$cjfm_user_profile_page = cjfm_get_option('page_profile');

	# Check if user is logged in
	if(!is_user_logged_in() && is_page( $cjfm_user_profile_page )){
		$login_url_profile = cjfm_string($login_url).'redirect='.urlencode(cjfm_current_url('url-only'));
		wp_redirect( $login_url_profile );
		exit;
	}else{
		$user_info = cjfm_user_info($current_user->ID);
	}

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	# Process Form
	$errors = null;
	if(isset($_POST['do_update_profile']) && is_null($return)){
		
		// Required Fields
		$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar', 'heading', 'custom_html', 'paragraph');
		$required_fields = $wpdb->get_results("SELECT unique_id, field_type FROM $fields_table WHERE required = 'yes' AND profile = 'yes' ORDER BY sort_order ASC");
		foreach ($required_fields as $key => $rf) {
			if(!in_array($rf->unique_id, $exclude_fields) && !in_array($rf->field_type, $exclude_fields)){
				if(@$_POST[$rf->unique_id] == ''){
					$errors['missing'] = __('Missing required fields', 'cjfm');
				}
			}
		}

		// Email Check
		if(!is_email($_POST['user_email'])){
			$errors[] = __('Invalid email address', 'cjfm');
		}elseif(email_exists( $_POST['user_email'] ) && $_POST['user_email'] != $current_user->user_email){
			$errors[] = __('Email address assigned to another account.', 'cjfm');
		}

		// Password Check
		if($_POST['user_pass'] != ''){
			if($_POST['user_pass'] != $_POST['user_pass_conf']){
				$errors[] = __('Password fields does not match', 'cjfm');
			}elseif(strlen($_POST['user_pass']) < cjfm_get_option('password_length')){
				$errors[] = sprintf(__('Password must be %d characters long', 'cjfm'), cjfm_get_option('password_length'));
			}
		}

		if(!is_null($errors)){
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}


		if(is_null($errors)){
			foreach ($_POST as $key => $value) {
				if($key != 'user_pass' && $key != 'user_pass_conf'){
					update_user_meta($current_user->ID, $key, $value);
				}
				if($key == 'user_pass' && $_POST['user_pass'] != '' && $_POST['user_pass_conf'] != '' && $_POST['user_pass'] == $_POST['user_pass_conf']){
					wp_set_password( $_POST['user_pass'], $current_user->ID );
				}
			}

			// User Avatar
			if(cjfm_get_option('user_avatar_type') == 'custom'){
				if(isset($_FILES) && $_FILES['user_avatar']['error'] == ''){
					$user_avatar_url = cjfm_file_upload('user_avatar', null, null, cjfm_get_option('user_avatar_filetypes'), 'guid', cjfm_get_option('user_avatar_filesize'));
					if(!is_array($user_avatar_url)){
						if($user_avatar_url != get_user_meta($current_user->ID, 'user_avatar', true)){
							update_user_meta($current_user->ID, 'user_avatar', $user_avatar_url);
						}	
					}else{
						$errors = $user_avatar_url;
					}
				}
			}else{
				update_user_meta($current_user->ID, 'user_avatar', cjfm_gravatar_url($current_user->ID));
			}

			if(!is_null($errors)){
				$display[] = cjfm_show_message('error', implode('<br>', $errors));
			}else{

				$user_info = cjfm_user_info($current_user->ID);
				do_action('cjfm_profile_updated', $user_info);

				$location = cjfm_string(cjfm_current_url('only-url')).'cjfm_msg=updated';
				wp_redirect( $location, $status = 302 );
				exit;

			}

		}

	}

	$social_services = cjfm_get_option('cjfm_social_services');

	foreach ($social_services as $skey => $svalue) {
		$option_name = 'cjfm_'.$skey.'_id';
		if(get_user_meta($current_user->ID, $option_name, true) != ''){
			$display[] = sprintf(__('<p class="cjfm-social-connect-profile"><b>Your account is connected with %s</b>. <a target="_blank" class="cjfm-confirm" data-confirm="Are you sure? This cannot be undone." href="%s">Disconnect</a></p>', 'cjfm'), $skey, cjfm_string(cjfm_current_url('only-url')).'cjfm_action=disconnect_service');
		}
	}

	if(is_null($errors) && isset($_GET['cjfm_msg']) && $_GET['cjfm_msg'] == 'updated'){
		$display[] = cjfm_show_message('success', __('Profile information updated.', 'cjfm'));
	}

	$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table ORDER BY sort_order ASC");

	foreach ($form_fields_query as $key => $field) {

		$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode');
		$password_fields = array('user_pass', 'user_pass_conf');
		$file_fields = array('user_avatar');
		$textarea_fields = array('textarea', 'description');
		$country_fields = array('cjfm_country');
		$social_fields = array('facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');

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
		}elseif(in_array($field->field_type, $social_fields)){
			$field_type = 'text';
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

		if($field->field_type == 'heading'){
			$default_value = $field->label;
		}elseif($field->field_type == 'paragraph'){
			$default_value = $field->description;
		}elseif($field->field_type == 'custom_html'){
			$default_value = stripcslashes($field->description);
		}else{
			$default_value = (isset($user_info[$field->unique_id])) ? $user_info[$field->unique_id] : '';
		}

		if($field->required == 'yes'){
			$required = '<span class="required">'.__('(required)', 'cjfm').'</span>';
		}else{
			$required = '';
		}

		if($field->enabled == 'yes' && $field->profile == 'yes'){

			if($field->field_type == 'user_pass'){
				// Password Type Config
				$form_fields[$key] = array(
				    'type' => 'password',
				    'id' => $field->unique_id,
				    'label' => $field->label,
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'params' => array('placeholder' => __('Leave empty for no change', 'cjfm')),
				    'default' => '',
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);	
			}elseif($field->field_type == 'user_pass_conf'){
				// Password Type Config
				$form_fields[$key] = array(
				    'type' => 'password',
				    'id' => $field->unique_id,
				    'label' => $field->label,
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'params' => array('placeholder' => __('Leave empty for no change', 'cjfm')),
				    'default' => '',
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);	
			}elseif($field->field_type == 'user_login'){
				// Password Type Config
				$form_fields[$key] = array(
				    'type' => 'text-readonly',
				    'id' => $field->unique_id,
				    'label' => $field->label,
				    'info' => __('Username cannot be changed', 'cjfm'),
				    'suffix' => '',
				    'prefix' => '',
				    'default' => @$default_value,
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);	
			}elseif($field->field_type == 'user_avatar' && cjfm_get_option('user_avatar_type') == 'custom'){
				// User Avatar
				$user_avatar = (get_user_meta($current_user->ID, 'user_avatar', true) != '') ? '<div class="cjfm-user-avatar"><img src="'.get_user_meta($current_user->ID, 'user_avatar', true).'"></div>' : '';
				$form_fields[$key] = array(
				    'type' => 'upload',
				    'id' => $field->unique_id,
				    'label' => $field->label.$user_avatar,
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'default' => @$default_value,
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);	
			}elseif($field->field_type == 'user_avatar' && cjfm_get_option('user_avatar_type') == 'gravatar'){
				// User Avatar
				$user_avatar = cjfm_gravatar_url($current_user->ID, 150);
				$form_fields[$key] = array(
				    'type' => 'custom_html',
				    'id' => $field->unique_id,
				    'label' => '',
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'default' => '<p class="cjfm-user-gravatar"><a href="https://en.gravatar.com/emails/" target="_blank"><img src="'.$user_avatar.'" /></a></p>',
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);	
			}else{
				$form_fields[$key] = array(
				    'type' => $field_type,
				    'id' => $field->unique_id,
				    'label' => $field->label.' '.$required,
				    'info' => $field->description,
				    'suffix' => '',
				    'prefix' => '',
				    'default' => $default_value,
				    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
				);				
			}
		}
	}


	$form_fields['submit'] = array(
	    'type' => 'submit',
	    'id' => 'do_update_profile',
	    'label' => $button_text,
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'class' => $button_class,
	    'default' => '',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	);

	
	$display[] = '<div class="cj-form cj-form-edit-profile '.$class.'">';
	$display[] = '<form action="" method="post" enctype="multipart/form-data" class="cjfm-form" autocomplete="off">';
	$display[] = cjfm_display_form($form_fields);
	$display[] = '</form>';	
	$display[] = '</div>';	

	
	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_profile', 'cjfm_user_profile' );