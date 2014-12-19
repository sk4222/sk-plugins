<?php 
global $wpdb, $cjfm_item_options, $cjfm_email_message;

require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir'))); 

$cjfm_item_options['cjfm_customize_emails_registration'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjfm_customize_emails_registration_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Registration Email Messages', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'registration_email_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Below email messages will be sent to users when they register on this site, depending upon the registration type selected under Plguin Configuration. 
					<br />You can use dynamic variables in your messages to personalize the email and populate different values. <a target="_blank" href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), cjfm_callback_url('cjfm_customize_emails_variables')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'verify_email_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'Email Verification Messages',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'verify_email_subject',
		'label' => __('Verify email address subject', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Comfirm your email address', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'verify_email_address_message',
		'label' => __('Verify email address message', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['verify-email-message'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'verify_email_onscreen_message',
		'label' => __('Verify email address on-screen message', 'cjfm'),
		'info' => __('This message is displayed to user after they submit the registration form and system sent an email to verify their email address.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'We have sent an email with a confirmation link to your email address.<br>Please check your email (also spam folder) in order to activate your account.',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'registration_welcome_email_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Welcome email message', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'welcome_email_subject',
		'label' => __('Welcome email subject', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Your %s account has been created.', 'cjfm'), get_bloginfo( 'name' )),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'welcome_email_message',
		'label' => __('Welcome email message', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['welcome-email-message'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'registration_approval_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Account awaiting approval', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'awaiting_approval_subject',
		'label' => __('Subject (User Email)', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Your %s account request is being verified.', 'cjfm'), get_bloginfo( 'name' )),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'awaiting_approval_message',
		'label' => __('Message  (User Email)', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['account-awaiting-approval'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'awaiting_approval_subject_admin',
		'label' => __('Subject (Admin Email)', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s user accounts awaiting your approval.', 'cjfm'), get_bloginfo( 'name' )),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'awaiting_approval_message_admin',
		'label' => __('Message (Admin Email)', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['account-awaiting-approval-admin'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'invitation_email_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Invitation Approved Email Messages', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'invitation_email_subject',
		'label' => __('Subject', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s Invite', 'cjfm'), get_bloginfo( 'name' )),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'invitation_email_message',
		'label' => __('Message', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjfm_email_message['invitation-approved-message'],
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