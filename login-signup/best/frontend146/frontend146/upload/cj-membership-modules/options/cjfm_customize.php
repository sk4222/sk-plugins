<?php 
global $cjfm_item_options;

$colors_array = array(
	'danger' => __('red', 'cjfm'),
	'success' => __('green', 'cjfm'),
	'warning' => __('orange', 'cjfm'),
	'info' => __('blue', 'cjfm'),
	'inverse' => __('black', 'cjfm'),
	'none' => __('gray', 'cjfm'),
);

$size_array = array(
	'mini' => __('mini', 'cjfm'),
	'small' => __('small', 'cjfm'),
	'default' => __('medium', 'cjfm'),
	'large' => __('large', 'cjfm'),
);

$cjfm_item_options['cjfm_customize'] = array(
	array(
		'type' => 'heading',
		'id' => 'customize_button_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Customize Form Look and Feel', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'select',
		'id' => 'button_color',
		'label' => __('Submit Button Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'success',
		'options' => $colors_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'select',
		'id' => 'button_size',
		'label' => __('Submit Button Size', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'default',
		'options' => $size_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'customize_code_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom CSS or Javascript', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'customize_code_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom code will be inserted in the footer. Make sure your theme supports wp_footer() function.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-css',
		'id' => 'custom_css',
		'label' => __('Custom CSS Code', 'cjfm'),
		'info' => __('<p>Write your custom css code here.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '<style type="text/css">
	/* add custom css code */
</style>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-js',
		'id' => 'custom_js',
		'label' => __('Custom JavaScript Code', 'cjfm'),
		'info' => __('<p>Write your custom javascript code here.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '<script type="text/javascript">
	/* add custom javascript code */
</script>',
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
?>