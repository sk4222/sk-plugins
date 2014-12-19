<?php 
global $wpdb, $cjfm_item_options, $cjfm_email_message;

require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir'))); 

$cjfm_item_options['cjfm_customize_emails_password'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjfm_customize_emails_password_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Reset Password Email Messages', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'reset_password_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Below email message will be sent to users when they try to reset their password, depending upon the setup type used in the shortcode. <br /> You can use dynamic variables in your messages to personalize the email and populate different values.', 'cjfm').' '.sprintf(__('<a target="_blank" href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), cjfm_callback_url('cjfm_customize_emails_variables')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'send_password_link_subject',
		'label' => __('Subject', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Your %s login details', 'cjfm'), get_bloginfo( 'name' )),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'send_password_link_message',
		'label' => __('Message', 'cjfm'),
		'info' => sprintf(__('<a target="_blank" href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), cjfm_callback_url('cjfm_customize_emails_variables')),
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['send-password-link'],
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