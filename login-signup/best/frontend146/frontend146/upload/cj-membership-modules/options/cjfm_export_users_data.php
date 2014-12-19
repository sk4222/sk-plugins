<?php 
require_once('../../../../wp-load.php');
global $wpdb;


$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
$users_columns = $wpdb->get_row("SELECT * FROM $wpdb->users", ARRAY_A);

$users_table_columns = array('user_login', 'ID', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_status', 'display_name', 'spam', 'deleted');

$columns_array[] = 'Sr. No.';
foreach ($users_columns as $ukey => $uvalue) {
	if(in_array($ukey, $users_table_columns)){
		$columns_array[] = $ukey;
		$new_users_columns_array[] = $ukey;
	}
}

$new_users_columns_array[] = 'user_pass';

$custom_fields = $wpdb->get_results("SELECT * FROM $custom_fields_table");

foreach ($custom_fields as $key => $value) {
	if(!in_array($value->unique_id, $users_table_columns) && $value->unique_id != 'user_pass' && $value->unique_id != 'user_pass_conf'){
		$columns_array[] = $value->unique_id;
		$custom_user_fields[] = $value->unique_id;
		$new_users_columns_array[] = $value->unique_id;
	}
	
}



$users_rows = $wpdb->get_results("SELECT * FROM $wpdb->users", ARRAY_A);

foreach ($users_rows as $ukey => $uvalue) {
	foreach ($uvalue as $key => $value) {
		if(in_array($key, $users_table_columns)){
			$rows_array[$uvalue['ID']]['count'] = $ukey + 1;
			if($key == 'user_registered'){
				$rows_array[$uvalue['ID']][$key] = date('Y-m-d H:i:s', strtotime($value));
			}else{
				$rows_array[$uvalue['ID']][$key] = $value;	
			}
			
		}
	}
	foreach ($custom_user_fields as $ckey => $cvalue) {
		$rows_array[$uvalue['ID']][$cvalue] = get_user_meta($uvalue['ID'], $cvalue, true);
	}
}

//echo '<pre>'; print_r($rows_array); echo '</pre>';

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'export_users'){
	$file_name = sanitize_title( get_bloginfo( 'name' ).'-users-'.date("Y-m-d") );
	cjfm_create_csv($file_name, $columns_array, $rows_array);
}

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format'){
	$file_name = 'cjfm-users-import-format';
	cjfm_create_csv($file_name, $columns_array, array());
}

if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format_new_users'){
	$file_name = 'cjfm-new-users-import-format';
	foreach ($new_users_columns_array as $key => $value) {
		if($value != 'ID' && $value != 'spam' && $value != 'deleted' && $value != 'user_status'){
			$new_users_array[] = $value;
		}
	}

	cjfm_create_csv($file_name, $new_users_array, array());
}












