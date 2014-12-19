<?php
function cjfm_item_assistant(){
	global $cjfm_assistant_steps;
	require_once(sprintf('%s/functions/assistant/messages.php', cjfm_item_path('modules_dir')));
	
	$step_key = null;
	$cjfm_assistant_step = get_option('cjfm_assistant_step');
	if($cjfm_assistant_step == ''){
		$step_key = 0;
		$step_msg = $cjfm_assistant_steps[0]['text'];
		$step_callback = $cjfm_assistant_steps[0]['callback'];
		$button_text = $cjfm_assistant_steps[0]['button_text'];
		$cancel_text = $cjfm_assistant_steps[0]['cancel_text'];
		$cancel_link = $cjfm_assistant_steps[0]['cancel_link'];
	}else{
		$step = $cjfm_assistant_step;
		if($step < count($cjfm_assistant_steps)){
			$step_key = $step;
			$step_msg = $cjfm_assistant_steps[$step]['text'];
			$step_callback = $cjfm_assistant_steps[$step]['callback'];
			$button_text = $cjfm_assistant_steps[$step]['button_text'];
			$cancel_text = $cjfm_assistant_steps[$step]['cancel_text'];
			$cancel_link = $cjfm_assistant_steps[$step]['cancel_link'];
			$step_back = $step_key - 2; 
			$step_back_callback = @$cjfm_assistant_steps[$step_back]['callback'];
			$back_link = cjfm_assistant_url($step_back, $step_back_callback);
		}
	}

	if(!is_null($step_key)){
		$display[] = '<div id="cjfm-item-assistant" class="assistant-panel">';
		$display[] = '<span class="header">Setup Assistant ~ '.cjfm_item_info('item_name').'</span>';
		$display[] = '<span class="logo"><img src="'.cjfm_item_path('framework_url').'/assets/admin/img/logo.png" width="48" /></span>';
		$display[] = $step_msg;
		$display[] = '<p class="buttons">';
		$display[] = '<a href="'.cjfm_assistant_url($step_key, $step_callback).'" class="button-primary">'.$button_text.'</a>';
		if($step_key >= 2){
			$display[] = '<a href="'.$back_link.'" class="button-secondary">Go back</a>';
		}
		
		$display[] = '<a href="'.$cancel_link.'" class="button-secondary">'.$cancel_text.'</a>';
		$display[] = '</p>';
		$display[] = '</div>';
		echo implode('', $display);
	}
}

add_action('cj_assistant_hook', 'cjfm_item_assistant');

// Add assistant menu item in drop down.
function cjfm_assistant_menu_item(){
	echo '<li class="assistant-menu-item"><a href="#cjfm-item-assistant" class="toggle-id">Help & Support</a>';
	echo '<ul>';
	echo '<li class="assistant-menu-item"><a href="'.cjfm_assistant_url('start-over', cjfm_callback_url('core_welcome')).'" class="toggle-id">Setup Assistant</a></li>';
	echo '<li><a target="_blank" href="'.cjfm_item_info('quick_start_guide_url').'">Quick Start Guide</a></li>';
	echo '<li><a target="_blank" href="'.cjfm_item_info('documentation_url').'">Documentation</a></li>';
	echo '<li><a target="_blank" href="'.cjfm_item_info('support_forum_url').'">Support Forum</a></li>';
	echo '<li><a target="_blank" href="'.cjfm_item_info('feature_request_url').'">Reques new features</a></li>';
	echo '<li><a target="_blank" href="'.cjfm_item_info('report_bugs_url').'">Report Bugs & Issues</a></li>';
	echo '</ul>';
	echo '</li>';
}
add_action('cjfm_dropdown_hook', 'cjfm_assistant_menu_item');


function cjfm_assistant_url($key, $callback){
	return cjfm_string(cjfm_current_url()).'cjfm_complete_step='.$key.'&cjfm_step_redirect='.urlencode($callback);
}


function cjfm_assistant_process_steps(){
	if(isset($_REQUEST['cjfm_complete_step']) && $_REQUEST['cjfm_complete_step'] != ''){
		if($_REQUEST['cjfm_complete_step'] == 'start-over'){
			delete_option('cjfm_assistant_step');
			$location = urldecode($_REQUEST['cjfm_step_redirect']);
			wp_redirect( $location );
			exit;
		}elseif($_REQUEST['cjfm_complete_step'] == 'end-tour'){
			update_option('cjfm_assistant_step', 100000);
			$location = urldecode($_REQUEST['cjfm_step_redirect']);
			wp_redirect( $location );
			exit;
		}else{
			update_option('cjfm_assistant_step', $_REQUEST['cjfm_complete_step'] + 1);
			$location = urldecode($_REQUEST['cjfm_step_redirect']);
			wp_redirect( $location );
			exit;
		}
	}
}
add_action('init', 'cjfm_assistant_process_steps');




// Custom Assistant messages


function cjfm_assistant_messages(){
	global $cjfm_assistant_messages;
	require_once(sprintf('%s/functions/assistant/messages.php', cjfm_item_path('modules_dir')));

	$case = basename($_SERVER['SCRIPT_NAME']);
	switch ($case) {
		case 'nav-menus.php':
			//echo @cjfm_assistant_msg($cjfm_assistant_messages['nav-menu.php']);
			break;
		
		default:
			# code...
			break;
	}
}

add_action('admin_footer', 'cjfm_assistant_messages');