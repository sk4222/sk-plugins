<?php 
$enable_woo_commerce = cjfm_get_option('');
//Adding Registration fields to the form 
add_filter( 'woocommerce_register_form', 'adding_custom_registration_fields' );
add_filter( 'woocommerce_before_checkout_registration_form', 'adding_custom_registration_fields' );
function adding_custom_registration_fields( ) {
	global $wpdb, $current_user, $woocommerce;
	$woo_generate_username = get_option('woocommerce_registration_generate_username');
	$woo_generate_password = get_option('woocommerce_registration_generate_password');
	$form_fields = cjfm_registration_fields();
	if(!is_null($form_fields)){
		echo '<div class="cj-woo-form">';
		echo cjfm_display_form($form_fields);
		echo '</div>';
	}
}

//Validation registration form  after submission using the filter registration_errors
add_filter('woocommerce_registration_errors', 'cjfm_woo_registration_errors_validation', 10, 3);
function cjfm_woo_registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
		global $woocommerce;
		extract($_POST); // extracting $_POST into separate variables

		$errors = null;

		$form_fields = cjfm_registration_fields();
		if(!empty($form_fields)){
			foreach ($form_fields as $key => $value) {
				if($value['required'] == 'yes' && @$_POST[$value['id']] == ''){
					$errors[] = sprintf(__('%s field is required.', 'cjfm'), str_replace('*', '', $value['label']));
				}
			}
		}
	
		if(!is_null($errors)){
			return new WP_Error( 'registration-error', implode('<br>', $errors) );
		}else{
			return $reg_errors;
		}
}

//Updating use meta after registration successful registration
add_action('woocommerce_created_customer','cjfm_woo_adding_extra_reg_fields');

function cjfm_woo_adding_extra_reg_fields($user_id) {
	global $wpdb;
	extract($_POST);
	require_once(ABSPATH.'wp-admin/includes/user.php' );
	$form_fields = cjfm_registration_fields();
	foreach ($form_fields as $key => $value) {
		update_user_meta($user_id, $value['id'], $$value['id']);
	}
}




function cjfm_registration_fields(){

	global $wpdb, $current_user;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table ORDER BY sort_order ASC");

	$form_fields = null;

	if(!empty($form_fields_query)){

		foreach ($form_fields_query as $key => $field) {
			$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode');
			$password_fields = array('user_pass', 'user_pass_conf');
			$file_fields = array('user_avatar');
			$textarea_fields = array('textarea', 'description');
			$country_fields = array('cjfm_country');
			$social_fields = array('facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');


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

			if($field->field_type == 'heading'){
				$default_value = $field->label;
			}elseif($field->field_type == 'paragraph'){
				$default_value = $field->description;
			}elseif($field->field_type == 'custom_html'){
				$default_value = stripcslashes($field->description);
			}else{
				$default_value = '';
			}

			if($field->required == 'yes'){
				$required = '<span class="required">'.__('(required)', 'cjfm').'</span>';
			}else{
				$required = '';
			}
			
			$default_password = wp_generate_password( 10, false, false );

			$exclude_fields = array(
				'user_login',
				'user_email',
				'user_pass',
				'user_pass_conf',
			);

			if($field->enabled == 'yes' && $field->register == 'yes'){
				if(!in_array($field->field_type, $exclude_fields)){
					$field_required = ($field->required == 'yes') ? ' <span class="required">*</span>' : '';
					$form_fields[$key] = array(
					    'type' => $field_type,
					    'id' => $field->unique_id,
					    'label' => $field->label.' '.$field_required,
					    'info' => $field->description,
					    'required' => $field->required,
					    'suffix' => '',
					    'prefix' => '',
					    'default' => cjfm_post_default($field->unique_id, ''),
					    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
					);
				}
			}
		}
	}

	return $form_fields;

}