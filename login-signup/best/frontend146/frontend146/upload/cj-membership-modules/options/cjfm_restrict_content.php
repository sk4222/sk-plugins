<?php 
global $cjfm_item_options;
$yes_no_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));
$enable_disable_array = array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm'));


$cjfm_item_options['cjfm_restrict_content'] = array(
	array(
		'type' => 'heading',
		'id' => 'restricted_content_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Restricted Content', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'restrict_site',
		'label' => __('Restricted Whole Website', 'cjfm'),
		'info' => __('Choose yes if you wish to restrict all website pages except login, register and reset password page setup under this plugin settings.<p><b>NOTE:</b> If you select yes here, below settings will be overwritten.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'restricted_specific_content_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Restrict Specific Content', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'restricted_content_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Selected content will be available only to logged in users.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'pages',
		'id' => 'restrict_pages',
		'label' => __('Restricted Pages', 'cjfm'),
		'info' => __('Select pages you would like to restrict access only for logged in users.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'categories',
		'id' => 'restrict_categories',
		'label' => __('Restricted Categories', 'cjfm'),
		'info' => __('Select categories you would like to restrict access only for logged in users.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'tags',
		'id' => 'restrict_tags',
		'label' => __('Restricted Tags', 'cjfm'),
		'info' => __('Select tags you would like to restrict access only for logged in users.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'taxonomies',
		'id' => 'restrict_taxonomies',
		'label' => __('Restricted Taxonomies', 'cjfm'),
		'info' => __('Select tags you would like to restrict access only for logged in users.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'restricted_login_message',
		'label' => __('Message to display?', 'cjfm'),
		'info' => __('<p>You can use following variables in this message.</p><p><code>%%login_link%%</code>: This will be replaced by login link.</p><code>%%register_link%%</code>: This will be replaced by register link.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '<p>You must <b>%%login_link%%</b> or <b>%%register_link%%</b> to view this content.</p>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'user_content_shortcode',
		'label' => __('User Only Content', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<p>You can use the following shortcode to display content only to loggedin users.</p>', 'cjfm').'<p><code>[cjfm_user_content]....[/cjfm_user_content]</code></p>'.__('<p>Only logged in users can see the content within this shortcode, otherwise the above message will be displayed.</p>', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'visitor_content_shortcode',
		'label' => __('Visitors Only Content', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<p>You can use the following shortcode to display content only to visitors who are not loggedin to the site.</p>', 'cjfm').'<p><code>[cjfm_visitor_content]....[/cjfm_visitor_content]</code></p>'.__('<p>Only logged in users can see the content within this shortcode, otherwise the above message will be displayed.</p>', 'cjfm'),
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
