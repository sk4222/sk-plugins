<?php
global $wpdb;

// EXPORT USER DATA

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'export_users'){
	$location = cjfm_string(sprintf('%s/options/cjfm_export_users_data.php', cjfm_item_path('item_url'))).'cjfm_action=export_users';
	wp_redirect( $location, $status = 302 );
	exit;
}

$download_url = cjfm_string(cjfm_callback_url('cjfm_csv_import')).'cjfm_action=export_users';
$form_options['fields'] =  array(
	array(
	    'type' => 'sub-heading',
	    'id' => '',
	    'label' => '',
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => __('Export User Data', 'cjfm'),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'info-full',
	    'id' => 'download_user_data',
	    'label' => __('Download Users Data', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => sprintf(__('<a href="%s" target="_blank" class="btn btn-success btn-small">Download Users Data</a>', 'cjfm'), $download_url),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),

);

echo cjfm_admin_form_raw($form_options['fields']);




// UPDATE EXISTING USERS

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format'){
	$location = cjfm_string(sprintf('%s/options/cjfm_export_users_data.php', cjfm_item_path('item_url'))).'cjfm_action=download_format';
	wp_redirect( $location, $status = 302 );
	exit;
}


$users_table_columns = array('user_nicename', 'user_email', 'user_url', 'user_registered', 'user_status', 'display_name', 'spam', 'deleted');

echo '<form class="margin-30-top" action="'.admin_url('admin.php?page='.cjfm_item_info('page_slug').'&callback='.@$_GET['callback'].'').'" method="post" enctype="multipart/form-data">';

if(isset($_POST['upload_user_data'])){
	$errors = null;
	if(isset($_FILES)){
		if($_FILES['cjfm_import_users']['error'] != 0){
			echo cjfm_show_message('error', __('No file found, please choose a file to upload.', 'cjfm'));
		}elseif($_FILES['cjfm_import_users']['type'] != 'text/csv'){
			echo cjfm_show_message('error', __('Invalid file type, please try again.', 'cjfm'));
		}else{

			$user_data = cjfm_parse_csv($_FILES['cjfm_import_users']['tmp_name'], 'data');

			$required_fields = array('ID', 'user_login', 'user_email');


			if(!empty($user_data)){

				foreach ($user_data as $ckey => $cvalue) {
					$total[$ckey] = 1;
					$ids[@$cvalue['ID']] = @$cvalue['ID'];
					$emails[@$cvalue['user_email']] = @$cvalue['user_email'];
					$logins[@$cvalue['user_login']] = @$cvalue['user_login'];
				}

				if(count($logins) != count($total) || count($ids) != count($total) || count($emails) != count($total)){
					$errors[] = __('<b>Invalid Data</b>, please verify the uploaded data and try again.<br>ID, user_login, user_email fields are required and must be unique.', 'cjfm');
				}

				foreach ($user_data as $key => $value) {
					$users_table_data = null;
					$user_id = $value['ID'];

					$check_id = cjfm_user_info(@$value['ID']);
					$check_user_login = cjfm_user_info(@$value['user_login']);
					$check_user_email = cjfm_user_info(@$value['user_email']);

					if(empty($check_id) && empty($check_user_login) && empty($check_user_email)){
						$excluded_users[] = '<li>'.$value['user_login'].'</li>';
						$errors[] = sprintf(__('Could not find these users in database:<br><ol>%s</ol>', 'cjfm'), implode('', $excluded_users));
					}

					if(is_null($errors)){
						foreach ($value as $ukey => $uvalue) {
							if(in_array($ukey, $users_table_columns)){
								if($ukey == 'user_registered'){
									$users_table_data[$ukey] = date('Y-m-d H:i:s', strtotime($uvalue));
								}else{
									$users_table_data[$ukey] = $uvalue;
								}
							}
						}

						cjfm_update($wpdb->users, $users_table_data, 'ID', $user_id);

						if(!in_array($ukey, $users_table_columns)){
							update_user_meta($value['ID'], $ukey, $value[$ukey]);
						}
					}
				}

				if(is_null($errors)){
					echo cjfm_show_message('success', __('User data uploaded successfully.', 'cjfm'));
				}else{
					echo cjfm_show_message('error', implode('<br>', $errors));
				}


			}else{
				echo cjfm_show_message('error', __('No data found in the uploaded file, please check and try again.', 'cjfm'));
			}

		}
	}
}


$download_format_url = cjfm_string(cjfm_callback_url('cjfm_csv_import')).'cjfm_action=download_format';
$form_options['import_fields'] =  array(
	array(
	    'type' => 'sub-heading',
	    'id' => '',
	    'label' => '',
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => __('Update Exisiting Users', 'cjfm'),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'info',
	    'id' => 'import_step_1',
	    'label' => __('Step 1: Download Format', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => sprintf(__('<a target="_blank" href="%s">Click here</a> to download format for .csv file.', 'cjfm'), $download_format_url),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'info',
	    'id' => 'import_step_2',
	    'label' => __('Step 2: Import File', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => '<input type="file" name="cjfm_import_users" value="" />',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'submit',
	    'id' => 'upload_user_data',
	    'label' => __('Update Existing Users', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => '',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);


echo cjfm_admin_form_raw($form_options['import_fields']);
echo '</form>';





// ADD NEW USERS

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format_new_users'){
	$location = cjfm_string(sprintf('%s/options/cjfm_export_users_data.php', cjfm_item_path('item_url'))).'cjfm_action=download_format_new_users';
	wp_redirect( $location, $status = 302 );
	exit;
}


echo '<form class="margin-30-top" action="'.admin_url('admin.php?page='.cjfm_item_info('page_slug').'&callback='.@$_GET['callback'].'').'" method="post" enctype="multipart/form-data">';


if(isset($_POST['add_new_users'])){
	$errors = null;
	if(isset($_FILES)){
		if($_FILES['cjfm_new_users']['error'] != 0){
			echo cjfm_show_message('error', __('No file found, please choose a file to upload.', 'cjfm'));
		}elseif($_FILES['cjfm_new_users']['type'] != 'text/csv'){
			echo cjfm_show_message('error', __('Invalid file type, please try again.', 'cjfm'));
		}else{

			$user_data = cjfm_parse_csv($_FILES['cjfm_new_users']['tmp_name'], 'data');

			
			if(!empty($user_data)){

				foreach ($user_data as $ckey => $cvalue) {
					$total[$ckey] = 1;
					$emails[@$cvalue['user_email']] = @$cvalue['user_email'];
					$logins[@$cvalue['user_login']] = @$cvalue['user_login'];

					$check_user_login = cjfm_user_info(@$cvalue['user_login']);
					$check_user_email = cjfm_user_info(@$cvalue['user_email']);

					if(!empty($check_user_login)){
						$user_exists[] = '<li>'.@$cvalue['user_login'].'</li>';
						//$errors['user_exists'] = sprintf(__('Following users already registered:<br><ol>%s</ol>', 'cjfm'), implode('', $user_exists));
					}

					if(!empty($check_user_email)){
						$email_exists[] = '<li>'.@$cvalue['user_email'].'</li>';
						//$errors['email_exists'] = sprintf(__('Following email addresses already registered:<br><ol>%s</ol>', 'cjfm'), implode('', $email_exists));
					}

				}

				if(count($logins) != count($total) || count($emails) != count($total)){
					$errors[] = __('<b>Invalid Data</b>, please verify the uploaded data and try again.<br>user_login, user_email fields are required and must be unique.', 'cjfm');
				}



				if(is_null($errors)){

					foreach ($user_data as $ckey => $cvalue){
						
							if(@$cvalue['user_pass'] != ''){
								$password = @$cvalue['user_pass'];
							}else{
								$password = strtolower(cjfm_unique_string());
							}
							if(@$cvalue['user_login'] != '' && @$cvalue['user_email'] != ''){

								if(!username_exists( $cvalue['user_login'] ) && !email_exists( $cvalue['user_email'] )){
								
									$new_user = wp_create_user( $cvalue['user_login'], $password, $cvalue['user_email'] );
									$nuid = $new_user;

								}else{
									$nuid = cjfm_user_info($cvalue['user_login'], 'ID');
								}

								
								foreach ($cvalue as $ckey => $cvalue) {

									if(in_array($ckey, $users_table_columns)){
										$update_user_data[$nuid]['ID'] = $nuid;
										if($ckey == 'user_registered'){
											$update_user_data[$nuid][$ckey] = date('Y-m-d H:i:s', strtotime($cvalue));
										}else{
											$update_user_data[$nuid][$ckey] = $cvalue;
										}
										wp_update_user( $update_user_data[$nuid] );
									}

									

									if(!in_array($ckey, $users_table_columns) && !in_array($ckey, array('user_login', 'user_pass', 'user_email'))){
										update_user_meta($nuid, $ckey, $cvalue);
									}
								}

							}
						
					}


					echo cjfm_show_message('success', __('New user data uploaded successfully.', 'cjfm'));

				}else{
					echo cjfm_show_message('error', implode('<br>', $errors));
				}




			}else{
				echo cjfm_show_message('error', __('No data found in the uploaded file, please check and try again.', 'cjfm'));
			}

		}
	}
}


$download_format_url = cjfm_string(cjfm_callback_url('cjfm_csv_import')).'cjfm_action=download_format_new_users';
$form_options['import_fields'] =  array(
	array(
	    'type' => 'sub-heading',
	    'id' => '',
	    'label' => '',
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => __('Add New Users', 'cjfm'),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'info',
	    'id' => 'import_step_1',
	    'label' => __('Step 1: Download Format', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => sprintf(__('<a target="_blank" href="%s">Click here</a> to download format for .csv file.', 'cjfm'), $download_format_url),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'info',
	    'id' => 'import_step_2',
	    'label' => __('Step 2: Import File', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => '<input type="file" name="cjfm_new_users" value="" />',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'submit',
	    'id' => 'add_new_users',
	    'label' => __('Add New Users', 'cjfm'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => '',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);


echo cjfm_admin_form_raw($form_options['import_fields']);
echo '</form>';
