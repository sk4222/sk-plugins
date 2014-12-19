<?php
global $cjfm_item_options;

$social_services_array = array(
	'Facebook' => __('Facebook', 'cjfm'),
	'Twitter' => __('Twitter', 'cjfm'),
);

$cjfm_item_options['plugin_addon_options'] = array(
	array(
		'type' => 'textarea',
		'id' => 'cjfm_social_services',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $social_services_array,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);