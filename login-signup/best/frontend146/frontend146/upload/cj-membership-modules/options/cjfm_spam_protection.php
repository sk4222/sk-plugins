<?php 
global $cjfm_item_options;

$reCaptcha_theme_array =  array(
	'clean' => __('Clean', 'cjfm'),
	'red' => __('Red', 'cjfm'),
	'white' => __('White', 'cjfm'),
	'blackglass' => __('Black', 'cjfm'),
);

$spam_protection_pages_array = array(
	'none' => __('None', 'cjfm'),
	'login' => __('Login Form', 'cjfm'),
	'register' => __('Registration Form', 'cjfm'),
	'reset_password' => __('Reset Password Form', 'cjfm'),
	'invitation' => __('Invitation Form', 'cjfm'),
);

$cjfm_item_options['cjfm_spam_protection'] = array(
	array(
		'type' => 'heading',
		'id' => 'spam_protection_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Spam Protection', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'checkbox',
		'id' => 'spam_protection_pages',
		'label' => __('Enable Spam Protection on', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('none'),
		'options' => $spam_protection_pages_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'spam_protection_type',
		'label' => __('Spam Protection Type?', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'none',
		'options' => array('none' => __('None', 'cjfm'), 'qa' => __('Question and Answer', 'cjfm'), 'recaptcha' => 'reCaptcha'), // array in case of dropdown, checkbox and radio buttons
	),


	array(
		'type' => 'sub-heading',
		'id' => 'qa_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Question &amp; Answer Settings', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'spam_question',
		'label' => __('Spam Question?', 'cjfm'),
		'info' => __('You can type any question and specify an answer to the same below.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => __('What color is snow?', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'spam_answer',
		'label' => __('Spam Answer?', 'cjfm'),
		'info' => __('This answer will be matched with the user input before processing the form.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => __('white', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'spam_answer_placeholder',
		'label' => __('Spam Answer Placeholder?', 'cjfm'),
		'info' => __('e.g. black or white', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => __('black or white', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),




	array(
		'type' => 'sub-heading',
		'id' => 'recaptcha_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('reCaptcha Settings', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'recaptcha_key_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<a href="%s" target="_blank">Generate</a> reCAPTCHA API keys for this website or <a href="%s" target="_blank">Click here</a> to find out more about reCAPTCHA.', 'cjfm'), 'https://www.google.com/recaptcha/admin/create', 'http://code.google.com/apis/recaptcha/'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'recaptcha_public_key',
		'label' => __('reCaptcha Public Key', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'recaptcha_private_key',
		'label' => __('reCaptcha Private Key', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'recaptcha_theme',
		'label' => __('reCaptcha Theme', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'clean',
		'options' => $reCaptcha_theme_array, // array in case of dropdown, checkbox and radio buttons
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