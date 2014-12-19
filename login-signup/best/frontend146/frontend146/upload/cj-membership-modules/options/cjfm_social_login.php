<?php 
global $cjfm_item_options;
$yes_no_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));
$enable_disable_array = array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm'));

$facebook_info[] = __('1. Create Facebook application at <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>', 'cjfm');
$facebook_info[] = __('2. Make sure to specify App Domains', 'cjfm');
$facebook_info[] = __('3. "Website with Facebook Login" must be checked, but for "Site URL", you can enter any landing URL.', 'cjfm');
$facebook_info[] = __('4. Copy the App ID & App Secret and specify in the boxes below.', 'cjfm');

$twitter_info[] = __('1. Create Twitter application at <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a>', 'cjfm');
$twitter_info[] = __('2. Make sure to enter a Callback URL or callback will be disallowed. You can enter your website Url.', 'cjfm');
$twitter_info[] = __('3. Register your domains at @Anywhere domains. Twitter only allows authentication from authorized domains.', 'cjfm');
$twitter_info[] = __('4. Copy the App Key & App Secret and specify in the boxes below.', 'cjfm');

$google_info[] = __('1. Create a Google APIs project at <a href="https://code.google.com/apis/console/" target="_blank">https://code.google.com/apis/console/</a>', 'cjfm');
$google_info[] = __('2. You do not have to enable any services from the Services tab.', 'cjfm');
$google_info[] = __('3. Make sure to go to <b>API Access</b>  tab and <b>Create an OAuth 2.0 client ID</b>.', 'cjfm');
$google_info[] = __('4. Choose <b>Web application</b>  for <b>Application type</b>.', 'cjfm');
$google_info[] = __('5. Make sure that redirect URI is set to actual OAuth 2.0 callback URL specified above.', 'cjfm');
$google_info[] = __('6. Copy the Client ID & Client Secret and specify in the boxes below.', 'cjfm');

$linkedin_info[] = __('1. Create LinkedIn application at <a href="https://www.linkedin.com/secure/developer" target="_blank">https://www.linkedin.com/secure/developer</a>', 'cjfm');
$linkedin_info[] = __('2. Enter your domain at JavaScript API Domain', 'cjfm');
$linkedin_info[] = __('3. There is no need to enter OAuth Redirect URL', 'cjfm');
$linkedin_info[] = __('4. Copy the App Key & App Secret and specify in the boxes below.', 'cjfm');

$github_info[] = __('1. Register a GitHub application at <a href="https://github.com/settings/applications/new" target="_blank">https://github.com/settings/applications/new</a>', 'cjfm');
$github_info[] = __('2. Enter URL as your application URL', 'cjfm');

$cjfm_item_options['cjfm_social_login'] = array(
	array(
		'type' => 'heading',
		'id' => 'social_login_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Social Login', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'callback_url_info',
		'label' => __('<b>Callback Url</b>', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<p>'.cjfm_item_path('modules_url').'/shortcodes/hybridauth/?hauth.done=Google'.'</p>'.
					__('<p>You must replace <b>Google</b> with the service you are using when using the callback url.</p>', 'cjfm').
					__('<p>This may be required by some services to generate oAuth keys.</p>', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'facebook_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Facebook Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'facebook_info',
		'label' => __('Facebook Application', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode('<br>', $facebook_info),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Facebook_appID',
		'label' => __('AppID', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Facebook_appSecret',
		'label' => __('AppSecret', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'Twitter_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Twitter Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'Twitter_info',
		'label' => __('Twitter Application', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode('<br>', $twitter_info),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Twitter_appID',
		'label' => __('API Key', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Twitter_appSecret',
		'label' => __('API Secret', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'Google_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Google Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'Google_info',
		'label' => __('Google Application', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode('<br>', $google_info),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Google_appID',
		'label' => __('Client ID', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'Google_appSecret',
		'label' => __('Client Secret', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'LinkedIn_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('LinkedIn Setup', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'LinkedIn_info',
		'label' => __('LinkedIn Application', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode('<br>', $linkedin_info),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'LinkedIn_appID',
		'label' => __('API Key', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'LinkedIn_appSecret',
		'label' => __('Secret Key', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
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