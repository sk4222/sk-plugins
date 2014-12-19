<?php 
global $wpdb;
$options_table = cjfm_item_info('options_table');
$settings = $wpdb->get_results("SELECT * FROM $options_table");
$export_settings = urlencode(serialize($settings));


if(isset($_POST['do_import_settings'])){
	$import_settings = unserialize(urldecode($_POST['import_settings']));
	if(is_array($import_settings)){
		$wpdb->query("TRUNCATE TABLE {$options_table}");
		foreach ($import_settings as $key => $value) {
			$insert_data = array(
				'option_name' => $value->option_name,
				'option_value' => $value->option_value,
			);
			cjfm_insert($options_table, $insert_data);
		}
		$location = cjfm_callback_url();
		wp_redirect( $location, $status = 302 );
		exit;
	}
}



$cjfm_form_options['export_settings'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'export_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Export %s Settings', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea-readonly',
		'id' => 'export_settings',
		'label' => __('Export Settings', 'cjfm'),
		'info' => __('Copy the contents and save it in a text file.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_post_default('export_settings', $export_settings),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
		'params' => ' onclick="this.focus();this.select()" ',
	),
);


cjfm_admin_form_raw($cjfm_form_options['export_settings']);



$cjfm_form_options['import_settings'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'import_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Import %s Settings', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'import_settings',
		'label' => __('Import Settings', 'cjfm'),
		'info' => __('Paste the previously copied settings and click Import Settings.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => 'do_import_settings',
		'label' => __('Import Settings', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

echo '<form class="margin-30-top" action="'.admin_url('admin.php?page='.@$_GET['page'].'&callback='.@$_GET['callback'].'').'" method="post" enctype="multipart/form-data">';
cjfm_admin_form_raw($cjfm_form_options['import_settings']);
echo '</form>';