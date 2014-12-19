<?php 
global $cjfm_item_options;
$yes_no_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));
$enable_disable_array = array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm'));

$cjfm_item_options['cjfm_modalbox_forms'] = array(
	array(
		'type' => 'heading',
		'id' => 'modalbox_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Modal Box Forms', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'modalbox_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Here you can manage login and registration forms that are displayed in a Modal box.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'modalbox_forms',
		'label' => __('Enable modalbox forms?', 'cjfm'),
		'info' => __('<p>Once enabled, you can use css class names to display forms in a modalbox on click event.</p>
					  <p> <code>.cjfm-show-login-form</code> Add this class to any link or button to display login form in a modalbox.</p>
					  <p> <code>.cjfm-show-register-form</code> Add this class to any link or button to display registration form in a modalbox.</p>
					  <p> <strong>Note: </strong>Modal links will not work on login and registration pages.</p>
					  ', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'modalbox_login_form_sub_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Login Form', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'modalbox_login_form_heading',
		'label' => __('Login form heading', 'cjfm'),
		'info' => __('Specify a heading for login form.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'Login to your account',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'modalbox_login_form_content',
		'label' => __('Login form content & Shortcode', 'cjfm'),
		'info' => __('Specify content and shortcode for the login form to be displayed in the modal box.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '[cjfm_form_login redirect_url="" user_login_label="" user_pass_label="" required_text="" button_text="" button_class="" class=""]',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'modalbox_register_form_sub_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Register Form', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'modalbox_register_form_heading',
		'label' => __('Register form heading', 'cjfm'),
		'info' => __('Specify a heading for register form.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'Create an account',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'modalbox_register_form_content',
		'label' => __('Register form content & Shortcode', 'cjfm'),
		'info' => __('Specify content and shortcode for the register form to be displayed in the modal box.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '[cjfm_form_register redirect_url="" button_text="" button_class="" class=""]',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);
?>