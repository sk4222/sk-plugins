<?php 
global $wpdb, $cjfm_item_options, $cjfm_email_message;

require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir'))); 

$variables_inc = array(
	"signature" => __('Returns email signature', 'cjfm'),
	"site_name" => __('Returns website name', 'cjfm'),
	"site_url" => __('Returns website url', 'cjfm'),
	"invitation_link" => __('Returns invitation link if registration type is set to invitation only.', 'cjfm'),
	"login_url" => __('Returns login page url specified under page setup', 'cjfm'),
	"register_url" => __('Returns register page url specified under page setup', 'cjfm'),
	"reset_password_url" => __('Returns reset password page url specified under page setup', 'cjfm'),
	"profile_url" => __('Returns profile page url specified under page setup', 'cjfm'),
	"ID" => __('Returns user database ID', 'cjfm'), 
	"user_login" => __('Returns username', 'cjfm'), 
	"user_email" => __('Returns email address', 'cjfm'), 
	"user_url" => __('Retunrs user url', 'cjfm'), 
	"user_registered" => __('Returns user registration date and time', 'cjfm'), 
	"display_name" => __('Returns user display name (fallback user_login)', 'cjfm'), 
	"first_name" => __('Returns user first name (fallback display_name)', 'cjfm'), 
	"last_name" => __('Retruns user last name (fallback blank)', 'cjfm'), 
	"description" => __('Returns user description', 'cjfm'), 
	"aim" => __('Returns user AOL ID', 'cjfm'), 
	"yim" => __('Returns user Yahoo ID', 'cjfm'), 
	"jabber" => __('Returns user Jabber ID', 'cjfm'), 
	"cjfm_rp" => __('Replaced with newly created  user password and removed once password is sent.', 'cjfm'),
	"cjfm_last_login" => __('Returns user last login date and time', 'cjfm'), 
	"cjfm_login_ip" => __('Returns user last login IP address', 'cjfm'),
	"reset_password_confirmation_link" => __('Returns reset password confirmation link URL', 'cjfm'),
);
$dynamic_variables = '';

// Custom fields
$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
$custom_fields = $wpdb->get_results("SELECT * FROM $custom_fields_table ORDER BY sort_order ASC");
$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar');
foreach ($custom_fields as $ckey => $cvalue) {
	if(!in_array($cvalue->unique_id, $exclude_fields)){
		$dynamic_variables[$cvalue->unique_id] = "<code>%%{$cvalue->unique_id}%%</code>".' : '.sprintf(__('Returns %s from usermeta table.', 'cjfm'), $cvalue->unique_id);
	}
}

foreach ($variables_inc as $key => $value) {
	$dynamic_variables[$key] = "<code>%%{$key}%%</code>".' : '.$value;
}

$cjfm_item_options['cjfm_customize_emails'] = array(
	array(
		'type' => 'heading',
		'id' => 'email_settings_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Outgoing Email Settings', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'registration_email_heading',
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
					<br />You can use dynamic variables in your messages to personalize the email and populate different values. <a href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), '#dynamic_variables'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-highlight',
		'id' => 'registration_welcome_email_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<b>'.__('Welcome email message', 'cjfm').'</b>',
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
		'type' => 'info-highlight',
		'id' => 'registration_approval_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<b>'.__('Account awaiting approval', 'cjfm').'</b>',
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
		'type' => 'sub-heading',
		'id' => 'reset_password_heading',
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
		'default' => __('Below email message will be sent to users when they try to reset their password, depending upon the setup type used in the shortcode. <br /> You can use dynamic variables in your messages to personalize the email and populate different values.', 'cjfm').' '.sprintf(__('<a href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), '#dynamic_variables'),
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
		'info' => sprintf(__('<a href="%s">Click here</a> to view dynamic variable list.', 'cjfm'), '#dynamic_variables'),
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
	array(
		'type' => 'sub-heading',
		'id' => 'dynamic_variables',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Dynamic Variables (to be used in email messages above)', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'dynamic_variables_list',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode("<br /><br />", $dynamic_variables),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	
);
?>