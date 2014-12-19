<?php 
global $wpdb;

	require_once('cjfm_customize_fields_helper.php');

	$cjfm_custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$yes_no_array = array(
		'yes' => __('Yes', 'cjfm'),
		'no' => __('No', 'cjfm'),
	);

	$field_type_array['standard_fields'] = array(
		'text' => __('Textbox (Single Line)', 'cjfm'),
		'textarea' => __('Textarea (Multiple Lines)', 'cjfm'),
		'select' => __('Dropdown List', 'cjfm'),
		'multiselect' => __('Dropdown List (multiple)', 'cjfm'),
		'radio' => __('Radio Buttons', 'cjfm'),
		'checkbox' => __('Checkboxes', 'cjfm'),
	);
	$field_type_array['WordPress_fields'] = array(
		'first_name' => __('First Name', 'cjfm'),
		'last_name' => __('Last Name', 'cjfm'),
		'display_name' => __('Display Name', 'cjfm'),
		'user_url' => __('Website URL', 'cjfm'),
		'aim' => __('AIM', 'cjfm'),
		'yim' => __('Yahoo IM', 'cjfm'),
		'jabber' => __('Jabber / Google talk', 'cjfm'),
		'description' => __('Biographical Info', 'cjfm'),
	);
	$field_type_array['address_fields'] = array(
		'cjfm_address1' => __('Address Line 1', 'cjfm'),
		'cjfm_address2' => __('Address Line 2', 'cjfm'),
		'cjfm_city' => __('City', 'cjfm'),
		'cjfm_state' => __('State', 'cjfm'),
		'cjfm_zipcode' => __('Zip code', 'cjfm'),
		'cjfm_country' => __('Country', 'cjfm'),
	);
	$field_type_array['social_media_fields'] = array(
		'facebook_url' => __('Facebook Profile URL', 'cjfm'),
		'twitter_url' => __('Twitter Profile URL', 'cjfm'),
		'google_plus_url' => __('Google+ Profile URL', 'cjfm'),
		'youtube_url' => __('Youtube URL', 'cjfm'),
		'vimeo_url' => __('Vimeo URL', 'cjfm'),
	);
	$field_type_array['content_fields'] = array(
		'heading' => __('Heading', 'cjfm'),
		'paragraph' => __('Paragraph Text', 'cjfm'),
		'custom_html' => __('Custom HTML', 'cjfm'),
	);


	// Remove Field
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'remove_field' && $_GET['id'] != ''){
		$wpdb->query("DELETE FROM $cjfm_custom_fields_table WHERE id = '{$_GET['id']}'");
		wp_redirect( cjfm_callback_url('cjfm_customize_fields'), $status = 302 );
		exit;
	}

?>

<?php if(!isset($_GET['cjfm_action'])): ?>

<?php
	if(isset($_POST['update_sort_order'])){
		unset($_POST['update_sort_order']);
		foreach ($_POST as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$wpdb->query("UPDATE $cjfm_custom_fields_table SET sort_order = '{$value1}' WHERE unique_id = '{$key1}'");
			}
		}
		echo cjfm_show_message('success', __('Sort order updated successfully.', 'cjfm'));
	}
?>

<form action="" method="post">
<table class="enable-search alternate" cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr class="searchable">
			<th colspan="12">
				<h2 class="sub-heading">
					<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=imexfields'; ?>" class="btn btn-small btn-info margin-2-top margin-10-left alignright"><?php _e('Import Export', 'cjfm') ?></a>
					<input name="update_sort_order" type="submit" class="btn btn-small alignright margin-2-top margin-10-left" value="<?php _e('Update Sort Order', 'cjfm') ?>" />
					<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=add_new_field'; ?>" class="btn btn-small btn-success margin-2-top alignright"><?php _e('Add New Field', 'cjfm') ?></a>
					<?php _e('Registration &amp; Profile Fields', 'cjfm') ?>
				</h2>
			</th>
		</tr>

		<?php
			$custom_fields = $wpdb->get_results("SELECT * FROM $cjfm_custom_fields_table ORDER BY sort_order ASC");

			$display[] = '<tr class="searchable">';
			$display[] = '<th>'.__('Field Type', 'cjfm').'</th>';
			$display[] = '<th>'.__('Unique ID', 'cjfm').'</th>';
			$display[] = '<th>'.__('Label', 'cjfm').'</th>';
			$display[] = '<th width="25%">'.__('Description', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Required', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Profile', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Register', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Invitation', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Enabled', 'cjfm').'</th>';
			$display[] = '<th class="textcenter">'.__('Order', 'cjfm').'</th>';
			$display[] = '<th width="15%">'.__('Options', 'cjfm').'</th>';
			$display[] = '<th class="textcenter" width="10%">'.__('Actions', 'cjfm').'</th>';
			$display[] = '</tr>';

			if(!empty($custom_fields)){
				foreach ($custom_fields as $key => $field) {

					$edit_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=edit_field&id='.$field->id;
					$remove_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=remove_field&id='.$field->id;

					$default_fields = array('user_login', 'user_pass', 'user_pass_conf', 'user_email', 'user_avatar');
				

					if($field->field_type == 'custom_html'){
						$field_description = '--';
					}elseif($field->field_type == 'heading'){
						$field_description = '--';
					}elseif($field->field_type == 'paragraph'){
						$field_description = $field->description;
					}else{
						$field_description = $field->description;
					}

					$display[] = '<tr class="searchable">';
					$display[] = '<td>'.$field->field_type.'</td>';
					$display[] = '<td>'.$field->unique_id.'</td>';
					$display[] = '<td>'.$field->label.'</td>';
					$display[] = '<td width="25%">'.$field_description.'</td>';
					$display[] = '<td class="textcenter capitalize">'.$field->required.'</td>';
					$display[] = '<td class="textcenter capitalize">'.$field->profile.'</td>';
					$display[] = '<td class="textcenter capitalize">'.$field->register.'</td>';
					$display[] = '<td class="textcenter capitalize">'.$field->invitation.'</td>';
					$display[] = '<td class="textcenter capitalize">'.$field->enabled.'</td>';
					$display[] = '<td class="textcenter capitalize">
									<input name="sort_order['.$field->unique_id.']" style="width:50px; text-align:center;" type="text" value="'.$field->sort_order.'" />
								  </td>';
					$display[] = '<td width="10%">'.nl2br($field->options).'</td>';

					if(in_array($field->unique_id, $default_fields)){
						$action_links = '<td class="textcenter" width="10%">
										<a tabindex="-1" href="'.$edit_link.'">'.__('Update', 'cjfm').'</a>
										 &nbsp;|&nbsp; 
										<a tabindex="-1" href="#" class="cj-alert disabled" data-alert="'.__("This field is required.", 'cjfm').'">'.__('Remove', 'cjfm').'</a>
									  </td>';
					}else{
						$action_links = '<td class="textcenter" width="10%">
										<a tabindex="-1" href="'.$edit_link.'">'.__('Update', 'cjfm').'</a>
										 &nbsp;|&nbsp; 
										<a tabindex="-1" href="'.$remove_link.'" class="cj-confirm red" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'">'.__('Remove', 'cjfm').'</a>
									  </td>';
					}
					$display[] = $action_links;
					$display[] = '</tr>';
				}
			}else{
				$display[] = '<tr class="searchable">';
				$display[] = '<td colspan="11" class="red">'.__('No custom fields found.', 'cjfm').'</td>';
				$display[] = '</tr>';
			}

			echo implode('', $display);
		?>

	</tbody>
</table>
</form>
<?php endif; ?>
<?php
	// Import Export
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'imexfields'):

	$custom_fields_raw_query = $wpdb->get_results("SELECT * FROM $cjfm_custom_fields_table");
	$custom_fields_raw_data = urlencode(serialize($custom_fields_raw_query));

	if(isset($_POST['do_import_fields'])){

		$import_data_raw = $_POST['import_fields_data'];
		$import_data = unserialize(urldecode($import_data_raw));

		if(!empty($import_data)){
			$wpdb->query("TRUNCATE TABLE $cjfm_custom_fields_table");

			foreach ($import_data as $key => $value) {
				$custom_field_data = array(
					"id" => $value->id,
					"field_type" => $value->field_type,
					"unique_id" => $value->unique_id,
					"label" => $value->label,
					"description" => $value->description,
					"required" => $value->required,
					"profile" => $value->profile,
					"register" => $value->register,
					"invitation" => $value->invitation,
					"enabled" => $value->enabled,
					"options" => $value->options,
					"sort_order" => $value->sort_order,
				);
				cjfm_insert($cjfm_custom_fields_table, $custom_field_data);
			}

			$location = cjfm_callback_url('cjfm_customize_fields');
			wp_redirect( $location, $status = 302 );
			exit;
		}
		
		
		

	}

	$form_options['import_export_fields'] = array(
		array(
		    'type' => 'sub-heading',
		    'id' => '',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => __('Import/Export Fields Data', 'cjfm'),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'export_fields_data',
		    'label' => __('Export Fields Data', 'cjfm'),
		    'info' => __('Copy this content and save it as is in a text file.<br>Make sure you do not make any changes to this string otherwise the import will fail.' , 'cjfm'),
		    'suffix' => '',
		    'prefix' => '',
		    'default' => $custom_fields_raw_data,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'import_fields_data',
		    'label' => __('Import Fields Data', 'cjfm'),
		    'info' => __('Paste previous saved custom fields data and hit Import Data button.', 'cjfm'),
		    'suffix' => '',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'submit',
		    'id' => 'do_import_fields',
		    'label' => __('Import Data', 'cjfm'),
		    'info' => '',
		    'suffix' => '<a href="'.cjfm_callback_url('cjfm_customize_fields').'" class="button-secondary margin-10-left">'.__('Go Back', 'cjfm').'</a>',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	echo '<form action="" method="post" enctype="multipart/form-data">';
	cjfm_admin_form_raw($form_options['import_export_fields']);
	echo '</form>';

	endif;
?>


<?php if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'add_new_field'): ?>

	<?php
		if(isset($_POST['select_field_type'])){
			$field_type = $_POST['field_type'];

			if($field_type != 'Select Field Type'){
				$location = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=add_new_field&field_type='.$field_type;
				wp_redirect( $location, $status = 302 );
				exit;
			}else{
				echo cjfm_show_message('error', __('Please select a field type', 'cjfm'));
			}
		}

		$form_options['add_new_form_fields'] = array(
			array(
			    'type' => 'sub-heading',
			    'id' => '',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => __('Add New Field', 'cjfm'),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'groupselect',
			    'id' => 'field_type',
			    'label' => __('Field Type', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => @$_GET['field_type'],
			    'options' => $field_type_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'submit',
			    'id' => 'select_field_type',
			    'label' => __('Continue', 'cjfm'),
			    'info' => '',
			    'suffix' => '<a href="'.cjfm_callback_url('cjfm_customize_fields').'" class="button-secondary margin-10-left">'.__('Cancel', 'cjfm').'</a>',
			    'prefix' => '',
			    'default' => '',
			    'options' => $field_type_array, // array in case of dropdown, checkbox and radio buttons
			),
		);

		if(!isset($_GET['field_type'])){
			echo '<form action="" method="post" enctype="multipart/form-data">';
			cjfm_admin_form_raw($form_options['add_new_form_fields']);
			echo '</form>';

		}
	?>


	<?php
		if(isset($_GET['field_type'])):
			$field_type = $_GET['field_type'];

			foreach ($field_type_array as $key => $value) {
				foreach ($value as $key1 => $value1) {
					if($field_type == $key1){
						$field_type_heading = $value1;
					}
				}	
			}

			if(isset($_POST['add_new_field'])){
				$errors = null;

				$unique_id_check = $wpdb->get_row("SELECT * FROM $cjfm_custom_fields_table WHERE unique_id = '{$_POST['unique_id']}'");

				if($_POST['unique_id'] == '' || $_POST['label'] == ''){
					$errors[] = __('Missing required fields', 'cjfm');
				}

				if(!empty($unique_id_check)){
					$errors[] = __('Unique name already exists', 'cjfm');
				}

				if(!preg_match('/^[a-zA-Z0-9_]*$/', $_POST['unique_id'])){
					$errors[] = __('Invalid unique name', 'cjfm');
				}

				if(!preg_match('/^[0-9]*$/', $_POST['sort_order'])){
					$errors[] = __('Invalid sort order, must be numeric', 'cjfm');
				}

				$options_field = array('radio', 'checkbox', 'select', 'multiselect');
				if(in_array($_POST['field_type'], $options_field) && $_POST['options'] == 'NA' || $_POST['options'] == ''){
					$errors[] = __('Specify options, each option per line.', 'cjfm');	
				}

				if(!is_null($errors)){
					echo '<div class="margin-30-top">'.cjfm_show_message('error', implode('<br />', $errors)).'</div>';
				}else{
					$field_data = array(
						'unique_id' => $_POST['unique_id'],
						'field_type' => $_POST['field_type'],
						'label' => stripcslashes($_POST['label']),
						'description' => stripcslashes($_POST['description']),
						'required' => $_POST['required'],
						'profile' => $_POST['profile'],
						'register' => $_POST['register'],
						'invitation' => $_POST['invitation'],
						'enabled' => $_POST['enabled'],
						'sort_order' => $_POST['sort_order'],
						'options' => $_POST['options'],
					);
					cjfm_insert($cjfm_custom_fields_table, $field_data);

					wp_redirect( cjfm_callback_url('cjfm_customize_fields'), $status = 302 );
					exit;

				}
			}


			$fields = cjfm_custom_fields_helper($field_type, $edit_field = null);

			echo '<form action="" method="post" enctype="multipart/form-data">';
			cjfm_admin_form_raw($fields);
			echo '</form>';

		endif;

	?>

<?php endif; ?>









<?php if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'edit_field'): ?>

	<?php

		$edit_field = $wpdb->get_row("SELECT * FROM $cjfm_custom_fields_table WHERE id = '{$_GET['id']}'");

		$remove_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=remove_field&id='.$edit_field->id;
		
		$module_url = cjfm_callback_url('cjfm_customize_fields');


		if(isset($_POST['update_field'])){
			$errors = null;

			$unique_id_check = $wpdb->get_row("SELECT * FROM $cjfm_custom_fields_table WHERE unique_id = '{$_POST['unique_id']}'");

			if($_POST['unique_id'] == '' || $_POST['label'] == ''){
				$errors[] = __('Missing required fields', 'cjfm');
			}

			if(!empty($unique_id_check) && $_POST['unique_id'] != $unique_id_check->unique_id){
				$errors[] = __('Unique name already exists', 'cjfm');
			}

			if(!preg_match('/^[a-zA-Z0-9_]*$/', $_POST['unique_id'])){
				$errors[] = __('Invalid unique name', 'cjfm');
			}

			if(!preg_match('/^[0-9]*$/', $_POST['sort_order'])){
				$errors[] = __('Invalid sort order, must be numeric', 'cjfm');
			}

			$options_field = array('radio', 'checkbox', 'select', 'multiselect');
			if(in_array($_POST['field_type'], $options_field) && $_POST['options'] == 'NA' || $_POST['options'] == ''){
				$errors[] = __('Specify options, each option per line.', 'cjfm');	
			}

			if(!is_null($errors)){
				echo '<div class="margin-30-top">'.cjfm_show_message('error', implode('<br />', $errors)).'</div>';
			}else{
				$field_data = array(
					'unique_id' => $_POST['unique_id'],
					'field_type' => $_POST['field_type'],
					'label' => stripcslashes($_POST['label']),
					'description' => stripcslashes($_POST['description']),
					'required' => $_POST['required'],
					'profile' => $_POST['profile'],
					'register' => $_POST['register'],
					'invitation' => $_POST['invitation'],
					'enabled' => $_POST['enabled'],
					'sort_order' => $_POST['sort_order'],
					'options' => $_POST['options'],
				);
				cjfm_update($cjfm_custom_fields_table, $field_data, 'id', $_GET['id']);
				$location = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=edit_field&id='.$_GET['id'].'&cjfm_msg=field-saved';
				wp_redirect( $location, $status = 302 );
				exit;

			}
		}

		if(isset($_GET['cjfm_msg']) && $_GET['cjfm_msg'] == 'field-saved'){
			echo cjfm_show_message('success', __('Field updated successfully.', 'cjfm'));
		}

		$edit_field = $wpdb->get_row("SELECT * FROM $cjfm_custom_fields_table WHERE id = '{$_GET['id']}'");

		$fields = cjfm_custom_fields_helper($edit_field->field_type, $edit_field);

		echo '<form action="" method="post" enctype="multipart/form-data">';
		cjfm_admin_form_raw($fields);
		echo '</form>';

	?>

<?php endif; ?>