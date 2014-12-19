<?php 
global $cjfm_item_vars, $cjfm_file_opts, $wpdb;

$cjfm_options_table = cjfm_item_info('options_table');

foreach ($cjfm_item_vars['db_tables'] as $key => $sql) {
	$query = $wpdb->query($sql);
	if(!$query){
		$wpdb->print_error(); 
		die();
	}
	if(is_wp_error( $query )){
		echo $query->get_error_message();
		die();
	}
}

$cjfm_item_options = cjfm_item_options();

foreach ($cjfm_item_options as $key => $value) {
	foreach ($value as $okey => $ovalue) {
		$check = $wpdb->get_row("SELECT * FROM $cjfm_options_table WHERE option_name = '{$ovalue['id']}'");
		if(empty($check)){

			if(is_array($ovalue['default'])){
				$save_value = serialize($ovalue['default']);
			}else{
				$save_value = $ovalue['default'];
			}

			$wpdb->query("INSERT INTO $cjfm_options_table (option_name, option_value) VALUES ('{$ovalue['id']}', '{$save_value}')");
		}
		$cjfm_file_opts[] = $ovalue['id'];
	}
}

$cjfm_options_sync = $wpdb->get_results("SELECT * FROM $cjfm_options_table ORDER BY option_id");

foreach ($cjfm_options_sync as $key => $result) {
	$cjfm_table_opts[] = $result->option_name;
}

$cjfm_opts_diff = array_diff($cjfm_table_opts, $cjfm_file_opts);

if(!empty($cjfm_opts_diff)){
	foreach ($cjfm_opts_diff as $key => $diff_opt) {
		$wpdb->query("DELETE FROM $cjfm_options_table WHERE option_name = '{$diff_opt}'");
	}
}


function cjfm_duplicate_options()
{
	global $cjfm_file_opts;
	$duplicates = implode('<br />', array_unique( array_diff_assoc( $cjfm_file_opts, array_unique( $cjfm_file_opts ) ) ));

	if(!empty($duplicates)){
		$display[] = '<div class="error">';
		$display[] = sprintf(__('<p><strong>ERROR</strong>: Duplicate options found!  <br /><b>%s <br />(%s)</b></p>', 'cjfm'), cjfm_item_info('item_name'), cjfm_item_path('item_dir'));
		$display[] = '<p>'.$duplicates.'</p>';
		$display[] = '</div>';

		echo implode('', $display);
	}
}

add_action('admin_notices', 'cjfm_duplicate_options');

do_action('cjfm_db_setup_hook');

