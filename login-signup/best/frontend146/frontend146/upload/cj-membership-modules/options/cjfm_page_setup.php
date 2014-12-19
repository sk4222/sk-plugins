<?php 
global $cjfm_item_options;
$cjfm_item_options['cjfm_page_setup'] = array(
	array(
		'type' => 'heading',
		'id' => 'page_setup_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Page Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'page_setup_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Shortcodes have a lot more options, setting up the shortcodes via Shortcode Generator while editing or creating the pages is highly recommended.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'auto_page_setup',
		'label' => __('Page Setup (Auto)', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<p><a href="'.cjfm_string(cjfm_callback_url('cjfm_page_setup')).'cjfm_do_action=create_pages" class="btn btn-success btn-small">'.__('Create Pages', 'cjfm').'</a></p><p>'.__('<b>Note:</b> If you have already used the auto page setup, this will remove the previously created pages and create new pages with default setup.', 'cjfm').'</p>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_login',
		'label' => __('Login Page', 'cjfm'),
		'info' => __('<p>Create or edit a page and use the following shortcode:</p>', 'cjfm').'<p><code>[cjfm_form_login redirect_url="" user_login_label="" user_pass_label="" required_text="" button_text="" button_class="" class=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_register',
		'label' => __('Register Page', 'cjfm'),
		'info' => __('<p>Create or edit a page and use the following shortcode:</p>', 'cjfm').'<p><code>[cjfm_form_register redirect_url="" button_text="" button_class="" class=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_logout',
		'label' => __('Logout Page', 'cjfm'),
		'info' => __('<p>Create or edit a page and use the following shortcode:</p>', 'cjfm').'<p><code>[cjfm_logout redirect="" type="direct-logout" button_text="" button_class="" class=""]Content to display before logout.[/cjfm_logout]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_reset_password',
		'label' => __('Reset Password Page', 'cjfm'),
		'info' => __('<p>Create or edit a page and use the following shortcode:</p>', 'cjfm').'<p><code>[cjfm_form_reset_password user_login_label="" required_text="" button_text="" button_class="" class=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_profile',
		'label' => __('Profile Page', 'cjfm'),
		'info' => __('<p>Create or edit a page and use the following shortcode:</p>', 'cjfm').'<p><code>[cjfm_user_profile button_text="" button_class="" class=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
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

