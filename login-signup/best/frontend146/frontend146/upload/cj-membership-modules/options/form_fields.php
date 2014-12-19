<?php
global $cjfm_item_options;
$cjfm_item_options['form_fields'] = array(
	array(
		'type' => 'hidden',
		'id' => 'html_fields_hidden',
		'label' => 'Label',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('This is text that goes here..', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'heading',
		'id' => 'html_fields_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('HTML Form Fields', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'ff_info_full',
		'label' => __('Info Full Label', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Info Full default text', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'ff_info',
		'label' => __('Info Label', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Info default text', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'ff_text',
		'label' => __('Textbox', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'Textbox',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'number',
		'id' => 'ff_number',
		'label' => __('Number', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'Textbox',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'ff_textarea',
		'label' => __('Textarea', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'Textarea',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'checkbox',
		'id' => 'ff_checkbox',
		'label' => __('Checkbox', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => array('may-be'),
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'checkbox-inline',
		'id' => 'ff_checkbox_inline',
		'label' => __('Checkbox Inline', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => array('no', 'may-be'),
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio',
		'id' => 'ff_radio',
		'label' => __('Radio', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'may-be',
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'ff_radio_inline',
		'label' => __('Radio Inline', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'ff_dropdown',
		'label' => __('Dropdown', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multiselect',
		'id' => 'ff_multiselect',
		'label' => __('MultiSelect', 'cjfm'),
		'info' => 'This is some info about this field.',
		'suffix' => '',
		'prefix' => '',
		'default' => array('no', 'may-be'),
		'options' => array('yes' => 'Yes', 'no' => 'No', 'may-be' => 'May Be'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'wp_fields_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('WordPress Fields', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'wp_fields_page',
		'label' => 'Page',
		'info' => 'Select a page',
		'suffix' => '',
		'prefix' => '',
		'default' => '10',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'pages',
		'id' => 'wp_fields_pages',
		'label' => 'Pages',
		'info' => 'Select a page',
		'suffix' => '',
		'prefix' => '',
		'default' => array('4', '10'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'post',
		'id' => 'wp_fields_post',
		'label' => 'Post',
		'info' => 'Select a post',
		'suffix' => '',
		'prefix' => '',
		'default' => '7',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'posts',
		'id' => 'wp_fields_posts',
		'label' => 'Posts',
		'info' => 'Select a post',
		'suffix' => '',
		'prefix' => '',
		'default' => array('7', '13'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'category',
		'id' => 'wp_fields_category',
		'label' => 'Category',
		'info' => 'Select a category',
		'suffix' => '',
		'prefix' => '',
		'default' => '3',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'categories',
		'id' => 'wp_fields_categories',
		'label' => 'Categories',
		'info' => 'Select categories',
		'suffix' => '',
		'prefix' => '',
		'default' => array(1,2),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'tag',
		'id' => 'wp_fields_tag',
		'label' => 'Tag',
		'info' => 'Select a tag',
		'suffix' => '',
		'prefix' => '',
		'default' => 'tag3',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'tags',
		'id' => 'wp_fields_tags',
		'label' => 'Tags',
		'info' => 'Select tags',
		'suffix' => '',
		'prefix' => '',
		'default' => array('tag3', 'tag6', 'tag4'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'role',
		'id' => 'wp_fields_role',
		'label' => 'Role',
		'info' => 'Select a role',
		'suffix' => '',
		'prefix' => '',
		'default' => 'editor',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'roles',
		'id' => 'wp_fields_roles',
		'label' => 'Roles',
		'info' => 'Select a roles',
		'suffix' => '',
		'prefix' => '',
		'default' => array('editor', 'author'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'helper_fields_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Helper Form Fields', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'date',
		'id' => 'helper_fields_date',
		'label' => 'Date',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'helper_fields_color',
		'label' => 'Color',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#ddd',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'file',
		'id' => 'helper_fields_file',
		'label' => 'Upload File',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'files',
		'id' => 'helper_fields_files',
		'label' => 'Upload Files',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'helper_fields_wysiwyg',
		'label' => 'WYSIWYG',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'This is some text in <b>bold</b> letters.',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-css',
		'id' => 'cssCode',
		'label' => 'CSS Code',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'New text goes here.',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-js',
		'id' => 'jsCode',
		'label' => 'JS Code',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'New text goes here.',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjfm'),
		'info' => 'Settings saved successfully.',
		'suffix' => '',
		'prefix' => '',
		'default' => 'This is some text in <b>bold</b> letters.',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);