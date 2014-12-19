<?php 

$help_support_value[] = '<a href="'.cjfm_item_info('quick_start_guide_url').'" target="_blank">'.__('Quick Start Guide', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('documentation_url').'" target="_blank">'.__('Documentation', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('support_forum_url').'" target="_blank">'.__('Help & Support', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('feature_request_url').'" target="_blank">'.__('Feature Requests', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('report_bugs_url').'" target="_blank">'.__('Report Bugs', 'cjfm').'</a>';

$cjfm_form_options['welcome'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'welcome_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s Information', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_type',
		'label' => __('Product Type', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('WordPress %s', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_name',
		'label' => __('Product Name', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_name'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_id',
		'label' => __('Product ID', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_id'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'version',
		'label' => __('Installed Version', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_version'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'help_support',
		'label' => __('Useful Links', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode(' &nbsp; &bull; &nbsp; ', $help_support_value),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'license',
		'label' => __('License & Terms of use', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<a target="_blank" href="%s">Click here</a> to view license and terms of use.', 'cjfm'), cjfm_item_info('license_url')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);


cjfm_admin_form_raw($cjfm_form_options['welcome']);
