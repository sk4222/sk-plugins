<?php
	global $wpdb, $cjfm_email_message;
	$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	
	require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir'))); 
	require_once(sprintf('%s/wp-admin/includes/user.php', ABSPATH)); 


	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'approve_account' && $_GET['id'] != ''){
		$user_info = cjfm_user_info($_GET['id']);
		if(!empty($user_info)){
			delete_user_meta($user_info['ID'], 'cjfm_account_approved');

			$email_subject = cjfm_get_option('welcome_email_subject');
			$email_message = cjfm_parse_email('welcome_email_message', $user_info);

			$email_data = array(
				'to' => $user_info['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $email_subject,
				'message' => $email_message,
			);
			cjfm_email($email_data);

			$location = cjfm_callback_url('cjfm_approve_accounts');
			wp_redirect( $location, $status = 302 );
			exit;
		}
	}


	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'decline_account' && $_GET['id'] != ''){


		if(isset($_POST['do_decline_account'])){
			
			$user_info = cjfm_user_info($_POST['user_login']);

			wp_delete_user( $user_info['ID'] );

			if(is_multisite()){
				wpmu_delete_user($user_info['ID']);
			}

			$wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id = ");

			$email_subject = $_POST['email_subject'];
			$email_message = cjfm_parse_email($_POST['email_message'], $user_info, 'raw');

			$email_data = array(
				'to' => $user_info['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $email_subject,
				'message' => $email_message,
			);
			cjfm_email($email_data);

			$location = cjfm_callback_url('cjfm_approve_accounts');
			wp_redirect( $location, $status = 302 );
			exit;

		}

		
		$user_info = cjfm_user_info($_GET['id']);
		if(!empty($user_info)){

			$form_options['decline_account'] = array(
				array(
					'type' => 'heading',
					'id' => 'declne_account_heading',
					'label' => '',
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => __('Decline Account Email Message', 'cjfm'),
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'text-readonly',
					'id' => 'user_login',
					'label' => __('User Login', 'cjfm'),
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => cjfm_user_info($_GET['id'], 'user_login'),
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'text',
					'id' => 'email_subject',
					'label' => __('Email Subject', 'cjfm'),
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => sprintf(__('Your %s account is declined.', 'cjfm'), get_bloginfo( 'name' )),
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'wysiwyg',
					'id' => 'email_message',
					'label' => __('Email Message', 'cjfm'),
					//'info' => sprintf(__('<a href="%s" target="_blank">Click here</a> to view dynamic variable list.', 'cjfm'), cjfm_callback_url('cjfm_customize_emails').'#dynamic_variables'),
					'info' => '',
					'suffix' => '',
					'prefix' => '',
					'default' => $cjfm_email_message['account-declined'],
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
				array(
					'type' => 'submit',
					'id' => 'do_decline_account',
					'label' => __('Decline Account & Remove User', 'cjfm'),
					'info' => '',
					'suffix' => '<a href="'.cjfm_callback_url('cjfm_approve_accounts').'" class="button-secondary margin-5-left">'.__('Cancel', 'cjfm').'</a>',
					'prefix' => '',
					'default' => '',
					'options' => '', // array in case of dropdown, checkbox and radio buttons
				),
			);
			echo '<form action="" method="post" class="margin-30-bottom">';
			cjfm_admin_form_raw($form_options['decline_account']);
			echo '</form>';

			
		}
	}

?>
<table class="enable-search alternate" cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr class="searchable">
			<th colspan="3">
				<h2 class="sub-heading"><?php _e('Approve Accounts', 'cjfm') ?></h2>
			</th>
		</tr>

		<tr>
			<th width="15%"><?php _e('User Details', 'cjfm') ?></th>
			<th width=""><?php _e('Custom Fields', 'cjfm') ?></th>
			<th width="10%"><?php _e('Actions', 'cjfm') ?></th>
		</tr>

		<?php 
			$approvals = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'cjfm_account_approved' AND meta_value = '0' ORDER BY user_id ASC"); 
			if(!empty($approvals)){
				$user_custom_fields = $wpdb->get_results("SELECT unique_id FROM $custom_fields_table WHERE register = 'yes' ORDER BY sort_order ASC");
				$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar');
				foreach ($approvals as $key => $ap) {
					$user_info = cjfm_user_info($ap->user_id);
					$user_custom_fields_display = '';
					foreach ($user_custom_fields as $key => $field) {
						if(!in_array($field->unique_id, $exclude_fields)){
							$field_value = (is_serialized($user_info[$field->unique_id])) ? implode(' | ', unserialize($user_info[$field->unique_id])) : $user_info[$field->unique_id];
							$user_custom_fields_display .= '<b>'.$field->unique_id.'</b>'.' &raquo; '.$field_value.'<br />';
						}
					}
					$approve_link = cjfm_string(cjfm_callback_url('cjfm_approve_accounts')).'cjfm_action=approve_account&id='.$ap->user_id;
					$decline_link = cjfm_string(cjfm_callback_url('cjfm_approve_accounts')).'cjfm_action=decline_account&id='.$ap->user_id;
					$action_links = '<a href="'.$approve_link.'">'.__('Approve', 'cjfm').'</a> &nbsp;|&nbsp; <a class="red cj-confirm" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'" href="'.$decline_link.'">Decline</a>';
					echo '<tr class="searchable">';
					echo '<td>'.$user_info['display_name'].'</td>';
					echo '<td>'.$user_custom_fields_display.'</td>';
					echo '<td>'.$action_links.'</td>';
					echo '</tr>';
				}
			}else{
				echo '<tr class="searchable">';
				echo '<td colspan="3" class="red italic">'.__('No accounts found that required approvals.', 'cjfm').'</td>';
				echo '</tr>';
			}
		?>

	</tbody>
</table>
