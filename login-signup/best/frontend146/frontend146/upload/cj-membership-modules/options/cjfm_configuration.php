<?php 
global $cjfm_item_options;
$yes_no_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));
$enable_disable_array = array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm'));

$registration_status = (get_option('users_can_register') != 0) ? __('<b class="green">enabled</b>', 'cjfm') : __('<b class="red">disabled</b>', 'cjfm');
if(is_multisite()){
	$registration_status_url = admin_url('network/settings.php?updated=true');
}else{
	$registration_status_url = admin_url('options-general.php');	
}

$register_password_type_array = array(
	'enable' => __('Allow users to set their passwords while registration.', 'cjfm'),
	'disable' => __('Generate password automatically and send via email.', 'cjfm'),
);

$register_type_array = array(
	'normal' => __('Normal', 'cjfm'),
	'approvals' => __('Approvals Required', 'cjfm'),
	'invitations' => __('Invitations Only', 'cjfm'),
);

$cjfm_item_options['cjfm_configuration'] = array(
	array(
		'type' => 'heading',
		'id' => 'config_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Configuration', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'woocommerce_integration',
		'label' => __('Woocommerce Integartion?', 'cjfm'),
		'info' => __('Once enabled, custom registration fields will be added to registration forms displayed on My account and Checkout pages.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'disable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'plugin_ajax',
		'label' => __('Use Ajax for forms?', 'cjfm'),
		'info' => __('You can choose to enable AJAX for login, register and reset password forms.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'password_strength_meter',
		'label' => __('Password strength meter?', 'cjfm'),
		'info' => __('You can choose to enable password strength meter for register and create new password forms.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'registration_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Registration Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'registration_enabled_info',
		'label' => __('Default Setup', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<p>Registrations are currently %s. <a href="%s">Click here</a> to manage the settings.</p>', 'cjfm'), $registration_status, $registration_status_url).
					 sprintf(__('Default role is set to: <b>%s</b>. <a href="%s">Click here</a> to manage the settings.', 'cjfm'), get_option( 'default_role' ), $registration_status_url),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'register_password_type',
		'label' => __('Handle Passwords?', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $register_password_type_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'number',
		'id' => 'password_length',
		'label' => __('Minimum Password Length', 'cjfm'),
		'info' => __('Password must be above the specified character length.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 6,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'register_type',
		'label' => __('Registration Type', 'cjfm'),
		'info' => '<div class="margin-15-top">'.__('<p><code>Normal</code> : Default registration system.</p>', 'cjfm').
				  __('<p><code>Approvals Required</code> : Admin must approve user account when users signup on this website.</p>', 'cjfm').
				  __('<p><code>Invitations Only</code> : Admins and existing users can invite new users. Normal registration not allowed.</p>', 'cjfm').'</div>',
		'suffix' => '',
		'prefix' => '',
		'default' => 'normal',
		'options' => $register_type_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'verify_email_address',
		'label' => __('Email address verification', 'cjfm'),
		'info' => __('If enabled, user email address will be verified before the account is created.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'profile_fields_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom Fields', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'profile_sync',
		'label' => __('Sync Custom Fields', 'cjfm'),
		'info' => __('<p>If <b>"Yes"</b>, additional profile fields will be displayed under WordPress profile page as well.</p>', 'cjfm').
				  sprintf(__('<a href="%s">Click here</a> to manage register and profile fields.', 'cjfm'), cjfm_callback_url('cjfm_customize_fields')),
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'profile_sync_heading',
		'label' => __('Additional Profile Fields Heading', 'cjfm'),
		'info' => __('This will be displayed above the custom fields in WordPress profile page.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => __('Additional Information', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'wp-defaults-heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('WordPress Defaults', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'wp_admin_bar',
		'label' => __('WordPress Admin Bar', 'cjfm'),
		'info' => __('If disabled, WordPress Admin Bar will be hidden for all users on frontend.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'roles',
		'id' => 'wp_dashboard_access',
		'label' => __('WordPress Dashboard Access', 'cjfm'),
		'info' => __('Select user roles to allow WordPress Dashboard access.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'administrator',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'wp_default_page_redirect',
		'label' => __('Disable WordPress Default Pages?', 'cjfm'),
		'info' => __('<p>If <b>yes</b>, Users will be redirected to frontend pages when they try to login or register via WordPress default links.</p><p class="red">Make sure the page setup is complete and you tried to login via the form on frontend login page.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'plugin_css_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Plugin Styles', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'plugin_css',
		'label' => __('Use Plugin CSS?', 'cjfm'),
		'info' => sprintf(__('<p><a class="red bold" href="%s">Click here</a> to insert custom CSS or Javascript.</p>', 'cjfm'), cjfm_callback_url('cjfm_customize')).
				  __('<p>You can also write custom CSS and JS in <code>cjfm-custom.css</code> and <code>cjfm-custom.js</code> files placed under plugin root folder.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'user-avatar-heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('User Avatar (Profile picture)', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'user_avatar_type',
		'label' => __('Avatar Type?', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'gravatar',
		'options' => array('gravatar' => __('Use Gravatar', 'cjfm'), 'custom' => __('Allow custom profile picture upload', 'cjfm')), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'user_avatar_filetypes',
		'label' => __('Allowed File Types?', 'cjfm'),
		'info' => __('Separate filetype extensions by <code>|</code> symbol.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'jpg|gif|png|jpeg',
		'options' => ''
	),
	array(
		'type' => 'text',
		'id' => 'user_avatar_filesize',
		'label' => __('Maxium File Size?', 'cjfm'),
		'info' => sprintf(__('Specify maxium file size for an avatar in kilobytes. <a target="_blank" href="%s">Convert Size</a>', 'cjfm'), 'https://www.google.com/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=500KB+in+MB'),
		'suffix' => __('Kilobytes', 'cjfm'),
		'prefix' => '',
		'default' => '500',
		'options' => ''
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