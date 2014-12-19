<?php
global $cjfm_item_options;

$yes_no_array = array( 'yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));

$cjfm_item_options['cjfm_invitations_setup'] = array(
	array(
		'type' => 'heading',
		'id' => 'invitations_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Invitations Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'request_invitation_form',
		'label' => __('Show Request Invitation Form', 'cjfm'),
		'info' => __('Show request invitation form instead of registration form when registration shortcode is called.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'invititations_register_form_message',
		'label' => __('Registration page message', 'cjfm'),
		'info' => __('This message will be replaced by the registration form in register shortcode.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('You need an invitation to join %s.', 'cjfm'), get_bloginfo( 'name' )),
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