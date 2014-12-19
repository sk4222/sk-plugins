<?php 
global $wpdb;

$url = 'http://cssjockey.com/api/check-upgrades.php';

$response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => '',
	'body' => array('item_id' => cjfm_item_info('item_id')),
	'cookies' => array()
    )
);

if ( is_wp_error( $response ) ) {
   	$error_message = $response->get_error_message();
   	echo cjfm_show_message('error', __('Could not get latest version.', 'cjfm'));
} else {
	if(is_null(json_decode($response['body']))){
		update_option('cjfm_latest_version', 'NA');
	}else{
		update_option('cjfm_latest_version', json_decode($response['body']));
	}
  	
}

$download_link = '';
$test = null;
$installed_version = cjfm_item_info('item_version');
$latest_version = get_option('cjfm_latest_version');

if($latest_version != 'NA' && $installed_version < $latest_version){
	$download_link = '<a class="button-primary" href="http://codecanyon.net/downloads" target="_blank">'.__('Download Latest Version', 'cjfm').'</a>';
	$test = array(
			'type' => 'info',
			'id' => 'cjfm_latest_version',
			'label' => '',
			'info' => '',
			'suffix' => '',
			'prefix' => '',
			'default' => $download_link,
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		);
}

if($latest_version != 'NA' && $installed_version > $latest_version){
	$download_link = __('<span class="red">Development Version</span>', 'cjfm');
	$test = array(
			'type' => 'info',
			'id' => 'cjfm_latest_version',
			'label' => '',
			'info' => '',
			'suffix' => '',
			'prefix' => '',
			'default' => $download_link,
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		);
}

if($latest_version != 'NA' && $installed_version == $latest_version){
	$download_link = __('<span class="green">You have latest version installed on your website.</span>', 'cjfm');
	$test = array(
			'type' => 'info',
			'id' => 'cjfm_latest_version',
			'label' => '',
			'info' => '',
			'suffix' => '',
			'prefix' => '',
			'default' => $download_link,
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		);
}

$cjfm_form_options['envato_upgrades'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'envato_upgrades_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Check for Upgrades', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'cjfm_current_version',
		'label' => __('Installed Version', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $installed_version,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'cjfm_latest_version',
		'label' => __('Latest Version', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $latest_version,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	$test
	
);


cjfm_admin_form_raw($cjfm_form_options['envato_upgrades']);