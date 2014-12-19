<?php 
function cjfm_custom_fields_helper($field_type, $edit_field = null){
	global $wpdb;
	$cjfm_custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$yes_no_array = array(
		'yes' => __('Yes', 'cjfm'),
		'no' => __('No', 'cjfm'),
	);

	$default_fields = array('user_login', 'user_pass', 'user_pass_conf', 'user_email');
	$default_fields_avatar = array('user_avatar');
	$show_options = array('select', 'multiselect', 'radio', 'checkbox');
	$wordpress_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber');
	$address_fields = array('cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode', 'cjfm_country');
	$special_fields_heading = array('heading');
	$special_fields_paragraph = array('paragraph');
	$special_fields_custom_html = array('custom_html');
	$social_fields = array('facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');
	
	$description_info = '';
	
	if(in_array($field_type, $show_options)){
		$unique_id_type = 'text';
		$unique_id_default = '';
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Field Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$options_type = 'textarea';
	}elseif(in_array($field_type, $wordpress_fields)){
		$unique_id_type = 'hidden';
		$unique_id_default = $field_type;
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Field Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$options_type = 'hidden';
	}elseif(in_array($field_type, $address_fields)){
		$unique_id_type = 'hidden';
		$unique_id_default = $field_type;
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Field Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$options_type = 'hidden';
	}elseif(in_array($field_type, $special_fields_heading)){
		$unique_id_type = 'hidden';
		$unique_id_default = cjfm_unique_string();
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Heading Text <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'hidden';
		$description_label = __('Description', 'cjfm');
		$options_type = 'hidden';
	}elseif(in_array($field_type, $special_fields_paragraph)){
		$unique_id_type = 'hidden';
		$unique_id_default = cjfm_unique_string();
		$label_type = 'hidden';
		$label_default = '--';
		$label_label = __('Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Paragraph Text <i class="block red">(required)</i>', 'cjfm');
		$options_type = 'hidden';
	}elseif(in_array($field_type, $special_fields_custom_html)){
		$unique_id_type = 'hidden';
		$unique_id_default = cjfm_unique_string();
		$label_type = 'hidden';
		$label_default = '--';
		$label_label = __('Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('HTML Code <i class="block red">(required)</i>', 'cjfm');
		$options_type = 'hidden';
	}elseif(in_array($field_type, $default_fields)){
		$unique_id_type = 'hidden';
		$unique_id_default = $field_type;
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$options_type = 'hidden';
		echo '<style type="text/css">#required, #register, #invitation, #enabled{display:none;}</style>';
	}elseif(in_array($field_type, $default_fields_avatar)){
		$unique_id_type = 'hidden';
		$unique_id_default = $field_type;
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'hidden';
		$description_label = __('Allowed File Types?', 'cjfm');
		$description_info = 'e.g. jpg,gif,png';
		$options_type = 'hidden';
		
	}elseif(in_array($field_type, $social_fields)){
		$unique_id_type = 'hidden';
		$unique_id_default = $field_type;
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$description_info = '';
		$options_type = 'hidden';
		
	}else{
		$unique_id_type = 'text';
		$unique_id_default = '';
		$label_type = 'text';
		$label_default = '';
		$label_label = __('Field Label <i class="block red">(required)</i>', 'cjfm');
		$description_type = 'textarea';
		$description_label = __('Description', 'cjfm');
		$options_type = 'hidden';
	}



	$button_suffix = '<a class="button-secondary margin-10-left" href="'.cjfm_callback_url('cjfm_customize_fields').'">'.__('Back', 'cjfm').'</a>';
	$sort_order_query = $wpdb->get_row("SELECT COUNT(id) AS maxorder FROM $cjfm_custom_fields_table");
	$sort_order_default = $sort_order_query->maxorder + 1;

	if(is_null($edit_field)){

		$form_options = array(
			array(
			    'type' => 'sub-heading',
			    'id' => '',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => __('Add new field', 'cjfm'),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'text-readonly',
			    'id' => 'field_type',
			    'label' => __('Field Type', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => $field_type,
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $unique_id_type,
			    'id' => 'unique_id',
			    'label' => __('Unique Name <i class="red block">(required)</i>', 'cjfm'),
			    'info' => __('<p><i class="red">Must be unique. No spaces or special characters.</i></p><p>Only Underscore (_) is allowed.</p>', 'cjfm'),
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('unique_id', $unique_id_default),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $label_type,
			    'id' => 'label',
			    'label' => $label_label,
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('label', $label_default),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $description_type,
			    'id' => 'description',
			    'label' => $description_label,
			    'info' => $description_info,
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('description', ''),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $options_type,
			    'id' => 'options',
			    'label' => __('Field Options <i class="red">(required)</i> <span class="block">(Radio Buttons, Checkboxes and Dropdowns)</span>', 'cjfm'),
			    'info' => __('<p>Each option per line <i class="red">(Avoid special characters and spaces)</i></p>', 'cjfm'),
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('options', 'NA'),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'number',
			    'id' => 'sort_order',
			    'label' => __('Sort Order?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('sort_order', $sort_order_default),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'required',
			    'label' => __('Required Field?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('required', 'yes'),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'profile',
			    'label' => __('Show on profile page?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('profile', 'yes'),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'register',
			    'label' => __('Show on register page?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('register', 'yes'),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'invitation',
			    'label' => __('Show on invitation form?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('invitation', 'no'),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'enabled',
			    'label' => __('Enabled?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('enabled', 'yes'),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'submit',
			    'id' => 'add_new_field',
			    'label' => __('Add New Field', 'cjfm'),
			    'info' => '',
			    'suffix' => $button_suffix,
			    'prefix' => '',
			    'default' => '',
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
		);
	}else{


		$form_options = array(
			array(
			    'type' => 'sub-heading',
			    'id' => '',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => sprintf(__('Edit %s Settings', 'cjfm'), $edit_field->label),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'text-readonly',
			    'id' => 'field_type',
			    'label' => __('Field Type', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => $edit_field->field_type,
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $unique_id_type,
			    'id' => 'unique_id',
			    'label' => __('Unique Name <i class="red block">(required)</i>', 'cjfm'),
			    'info' => __('<p><i class="red">Must be unique. No spaces or special characters.</i></p><p>Only Underscore (_) is allowed.</p>', 'cjfm'),
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('unique_id', $edit_field->unique_id),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $label_type,
			    'id' => 'label',
			    'label' => $label_label,
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('label', $edit_field->label),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $description_type,
			    'id' => 'description',
			    'label' => $description_label,
			    'info' => $description_info,
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('description', $edit_field->description),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => $options_type,
			    'id' => 'options',
			    'label' => __('Field Options <i class="red">(required)</i> <span class="block">(Radio Buttons, Checkboxes and Dropdowns)</span>', 'cjfm'),
			    'info' => __('<p>Each option per line <i class="red">(Avoid special characters and spaces)</i></p>', 'cjfm'),
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('options', $edit_field->options),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'number',
			    'id' => 'sort_order',
			    'label' => __('Sort Order?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('sort_order', $edit_field->sort_order),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'required',
			    'label' => __('Required Field?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('required', $edit_field->required),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'profile',
			    'label' => __('Show on profile page?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('profile', $edit_field->profile),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'register',
			    'label' => __('Show on register page?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('register', $edit_field->register),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'invitation',
			    'label' => __('Show on invitation form?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('invitation', $edit_field->invitation),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'radio-inline',
			    'id' => 'enabled',
			    'label' => __('Enabled?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => cjfm_post_default('enabled', $edit_field->enabled),
			    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'submit',
			    'id' => 'update_field',
			    'label' => __('Update Field', 'cjfm'),
			    'info' => '',
			    'suffix' => $button_suffix,
			    'prefix' => '',
			    'default' => '',
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
		);

	}

	return $form_options;
}