<?php 
function cjfm_delete_account( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array( 
		'return' => null,
		'button_text' => 'Delete Account',
		'button_class' => 'btn btn-danger',
		'redirect' => site_url(),
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display a button for a user to delete their account.', 'cjfm'),
		'button_text' => array(__('Button Text', 'cjfm'), 'text', null, __('Specify button text, defaults to Delete Account.', 'cjfm')),
		'button_class' => array(__('Button CSS Class', 'cjfm'), 'text', null, __('Specify button css class.', 'cjfm')),
		'redirect' => array(__('Redirect Url', 'cjfm'), 'text', null, __('Specify redirect Url if you wish to send the user to a specific page otherwise user will be redirected back to this page.', 'cjfm')),
	);
	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}


	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_delete_account_nonce'],'cjfm_delete_account') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}


	if(isset($_POST['delete_account']) && $process_form == 'true'){
		require_once(ABSPATH.'wp-admin/includes/user.php' );
		wp_delete_user( $current_user->ID);
		$wpdb->query("DELETE FROM $wpdb->users WHERE ID = '{$current_user->ID}'");
		$wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id = '{$current_user->ID}'");
		$wpdb->query("DELETE FROM $wpdb->posts WHERE post_author = '{$current_user->ID}'");
		$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_author = '{$current_user->ID}'");
		$wpdb->query("DELETE FROM $wpdb->links WHERE link_owner = '{$current_user->ID}'");
		wp_logout();
		wp_redirect($redirect);
		exit;
	}

	$confirm_msg = __("Are you sure?\nThis cannot be undone and will remove all your data.", 'cjfm');
	$confirm_msg_admin = __("Admin accounts cannot be deleted by this method.", 'cjfm');


	if(is_user_logged_in()){
		if(!current_user_can('manage_options')){
			$display[] = '<form action="'.cjfm_current_url().'" method="post">';
			$display[] = wp_nonce_field('cjfm_delete_account','cjfm_delete_account_nonce', '', false);
			$display[] = '<button type="submit" name="delete_account" data-confirm="'.$confirm_msg.'" class="cj-confirm '.$button_class.'">'.$button_text.'</button>';
			$display[] = '</form>';
		}else{
			$display[] = '<a href="#" data-confirm="'.$confirm_msg_admin.'" class="cj-confirm btn btn-danger">'.$button_text.'</a>';
		}
	}else{
		$display[] = null;
	}
	

	if($return == null){
	    return implode(null, $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_delete_account', 'cjfm_delete_account' );