<?php 
$hooks_file = sprintf('%s/hooks.php', cjfm_item_path('item_dir'));
if(file_exists($hooks_file)){
	require_once($hooks_file);
}