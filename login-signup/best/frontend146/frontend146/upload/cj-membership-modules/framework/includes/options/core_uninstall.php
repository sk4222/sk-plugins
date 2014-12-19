<?php 
global $wpdb;
$installed_table_names = cjfm_item_info('options_table').'<br>';

if(!is_null(cjfm_item_info('addon_tables'))){
	$installed_addon_tables = explode(',', cjfm_item_info('addon_tables'));
	foreach ($installed_addon_tables as $key => $value) {
		$installed_table_names .= $wpdb->prefix.$value.'<br />';
	}
}

$cjfm_form_options['ncuninstall'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'ncuninstall_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Uninstall %s', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'nctrial_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<p>To uninstall this plugin, you can deactivate this plugin and then remove the following tables from your databse.</p>', 'cjfm').
					$installed_table_names,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	
);


cjfm_admin_form_raw($cjfm_form_options['ncuninstall']);