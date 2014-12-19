<?php 
global $wpdb, $cjfm_item_options, $cjfm_email_message;

require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir')));

$cjfm_item_options['cjfm_customize_emails_config'] = array(
	array(
		'type' => 'heading',
		'id' => 'email_config_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Outgoing Email Settings', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'from_name',
		'label' => __('From Name', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => get_bloginfo( 'name' ),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'from_email',
		'label' => __('From Email Address', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => get_option( 'admin_email' ),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'signature',
		'label' => __('Email Signature', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Regards, <br /> <b>%s Team</b>', 'cjfm'), get_bloginfo( 'name' )),
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