<?php 
/**
 * Plugin Settings
 * 
 * Setup plugin functionality, scripts and other functions.
 *
 * @author      Mohit Aneja - CSSJockey.com
 * @category    Framework
 * @package     CSSJockey/Framework
 * @version     1.0
 * @since       1.0
 */

global $wpdb, $current_user;

# Plugin Styles & Scripts
####################################################################################################
add_action('wp_enqueue_scripts', 'cjfm_plugin_scripts');
function cjfm_plugin_scripts(){
	if(!is_admin()){
		$plugin_css = cjfm_get_option('plugin_css');
		if($plugin_css == 'yes'){
			wp_enqueue_style('cjfm', cjfm_item_path('item_url').'/assets/css/cjfm.css', null, cjfm_item_info('item_version'), false);
		}
		wp_enqueue_script('cjfm_js', cjfm_item_path('item_url').'/assets/js/cjfm.js', array('jquery'), cjfm_item_info('item_version'), true);
		if(cjfm_get_option('plugin_ajax') == 'yes'){
			wp_enqueue_script('cjfm_ajax_js', cjfm_item_path('item_url').'/assets/js/cjfm-ajax.js', array('jquery'), cjfm_item_info('item_version'), true);
		}
		wp_enqueue_script('cjfm_custom_js', cjfm_item_path('item_url').'/cjfm-custom.js', array('jquery'), cjfm_item_info('item_version'), true);
		wp_enqueue_style('cjfm_custom_css', cjfm_item_path('item_url').'/cjfm-custom.css', null, cjfm_item_info('item_version'), false);
		wp_enqueue_style('cjfm_icons_css', cjfm_item_path('item_url').'/assets/cjfm-icons/style.css', null, cjfm_item_info('item_version'), false);
	}
}

// Use shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

add_action('wp_footer', 'cjfm_custom_scripts');
function cjfm_custom_scripts(){
	$display[] = cjfm_get_option('custom_css');
	$display[] = cjfm_get_option('custom_js');
	echo implode("\n", $display);
}

# Admin Notices
####################################################################################################
add_action( 'admin_notices', 'cjfm_admin_notices');
function cjfm_admin_notices(){

	// Page Setup Admin Notice
	$required_pages = array(
		'page_login',
		'page_logout',
		'page_register',
		'page_reset_password',
		'page_profile',
		
	);
	$count = 0;
	$page_setup[] = 0;
	foreach ($required_pages as $key => $value) {
		$count++;
		if(cjfm_get_option($value) != 0){
			$page_setup[] = 1;
		}
	}

	if($count != array_sum($page_setup)){
		$page_setup_url = cjfm_callback_url('cjfm_page_setup');
		echo '<div class="error" style="margin-top:10px;">
		      <p>'.sprintf(__('<b>%s %s</b> requires a few pages to be setup. <a href="%s">Click here</a> to setup required pages.', 'cjfm'), cjfm_item_info('item_name'), ucwords(cjfm_item_info('item_type')), $page_setup_url).'
		      </p></div>';	
	}

}

// Automatic page setup
add_action('init', 'cjfm_auto_page_setup');
function cjfm_auto_page_setup(){
	global $current_user, $wpdb;
	if(isset($_GET['cjfm_do_action']) && $_GET['cjfm_do_action'] == 'create_pages'){
		$reset_pages = get_option( 'cjfm_auto_page_setup' );
		if(is_array($reset_pages)){
			foreach ($reset_pages as $key => $value) {
				wp_delete_post( $value, true );
				cjfm_update_option($key, '');
			}	
		}
		delete_option('cjfm_auto_page_setup');
		$pages['page_login'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Login', 'cjfm'),
			'post_content'   => '[cjfm_form_login redirect_url="'.site_url().'" user_login_label="" user_pass_label="" required_text="" button_text="" button_class="" class=""]',
			'post_name'      => 'login',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_register'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Register', 'cjfm'),
			'post_content'   => '[cjfm_form_register redirect_url="'.site_url().'" button_text="" button_class="" class=""]',
			'post_name'      => 'register',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_logout'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Logout', 'cjfm'),
			'post_content'   => '[cjfm_logout redirect="'.site_url().'" type="direct-logout" button_text="" button_class="" class=""]This content will be displayed to the user if type is set to message.[/cjfm_logout]',
			'post_name'      => 'logout',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',	
		);
		$pages['page_reset_password'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Recover Password', 'cjfm'),
			'post_content'   => '[cjfm_form_reset_password user_login_label="" required_text="" button_text="" button_class="" class=""]',
			'post_name'      => 'recover-password',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_profile'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Edit Profile', 'cjfm'),
			'post_content'   => '[cjfm_user_profile button_text="" button_class="" class=""]',
			'post_name'      => 'profile',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);

		
		foreach ($pages as $key => $value) {
			//echo '<pre>'; print_r($value); echo '</pre>';
			if(get_option( 'cjfm_auto_page_setup') != 1){
				$post_id = wp_insert_post( $value );
				cjfm_update_option($key, $post_id);
				$post_ids[$key] = $post_id;
			}
		}
		update_option('cjfm_auto_page_setup', $post_ids);
		$location = cjfm_callback_url('cjfm_page_setup');
		wp_redirect( $location );
		exit;
	}
}


# Email Messages
####################################################################################################
function cjfm_parse_email($option_name_or_email, $user_info = null, $raw = null, $verify_email_link = null){
	global $wpdb;

	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';

	if(is_null($raw)){
		$message = cjfm_get_option($option_name_or_email);	
	}else{
		$message = $option_name_or_email;	
	}
	
	$date_time_string = get_option( 'date_format' ).' '.get_option( 'time_format' );

	$user_info = cjfm_user_info($user_info['ID']);

	$display_name_fallback = (isset($user_info['user_login'])) ? $user_info['user_login'] : __('User', 'cjfm');

	if(is_null($verify_email_link) && is_null($user_info)){
		$dynamic_variables = array(
			"site_name" => get_bloginfo( 'name' ),
			"site_url" => site_url(),
			"signature" => cjfm_get_option('signature'),
			"login_url" => cjfm_generate_url('page_login'),
			"register_url" => cjfm_generate_url('page_register'),
			"reset_password_url" => cjfm_generate_url('page_reset_password'),
			"profile_url" => cjfm_generate_url('page_profile'),
			"verify_email_link" => $verify_email_link,
		);
	}else{
		$dynamic_variables = array(
			"site_name" => get_bloginfo( 'name' ),
			"site_url" => site_url(),
			"signature" => cjfm_get_option('signature'),
			"login_url" => cjfm_generate_url('page_login'),
			"register_url" => cjfm_generate_url('page_register'),
			"reset_password_url" => cjfm_generate_url('page_reset_password'),
			"profile_url" => cjfm_generate_url('page_profile'),
			"ID" => $user_info['ID'],
			"user_login" => $user_info['user_login'],
			"user_email" => $user_info['user_email'],
			"user_url" => $user_info['user_url'],
			"user_registered" => date($date_time_string, strtotime($user_info['user_registered'])),
			"display_name" => (!empty($user_info['display_name'])) ? $user_info['display_name'] : $display_name_fallback,
			"first_name" => (!empty($user_info['first_name'])) ? $user_info['first_name'] : $user_info['user_login'],
			"last_name" => (!empty($user_info['last_name'])) ? $user_info['last_name'] : '',
			"description" => @$user_info['description'],
			"aim" => @$user_info['aim'],
			"yim" => @$user_info['yim'],
			"jabber" => @$user_info['jabber'],
			"cjfm_rp" => @base64_decode($user_info['cjfm_user_salt']),
			"cjfm_last_login" => date($date_time_string, strtotime($user_info['cjfm_last_login'])),
			"cjfm_login_ip" => @$user_info['cjfm_login_ip'],
			"reset_password_confirmation_link" => cjfm_string(get_permalink(cjfm_get_option('page_reset_password'))).'cjfm_action=rp&key='.@$user_info['cjfm_reset_password_key'],
			"verify_email_link" => $verify_email_link,
		);
	}

	// Custom fields
	$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $custom_fields_table ORDER BY sort_order ASC");
	$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar');
	foreach ($custom_fields as $ckey => $cvalue) {
		if(!in_array($cvalue->unique_id, $exclude_fields)){
			$dynamic_variables[$cvalue->unique_id] = cjfm_user_info($user_info['ID'], $cvalue->unique_id);
		}
	}

	foreach ($dynamic_variables as $key => $value) {
		$user_info = cjfm_user_info($user_info['ID']);
		$message = str_replace("%%{$key}%%", $value, $message);
	}

	return $message;
}


# Default Fields DB Setup
####################################################################################################
add_action('init', 'cjfm_default_fields_setup');
function cjfm_default_fields_setup(){
	global $wpdb;
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$default_fields['user_login'] = array(
		'field_type' => 'user_login',
		'unique_id' => 'user_login',
		'label' => __('Choose Username', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '0',
	);

	$default_fields['user_email'] = array(
		'field_type' => 'user_email',
		'unique_id' => 'user_email',
		'label' => __('Your email address', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'yes',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '1',
	);

	$default_fields['user_pass'] = array(
		'field_type' => 'user_pass',
		'unique_id' => 'user_pass',
		'label' => __('Choose a password', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '2',
	);

	$default_fields['user_pass_conf'] = array(
		'field_type' => 'user_pass_conf',
		'unique_id' => 'user_pass_conf',
		'label' => __('Type password again', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '3',
	);

	$default_fields['user_avatar'] = array(
		'field_type' => 'user_avatar',
		'unique_id' => 'user_avatar',
		'label' => __('Profile Picture', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'no',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '4',
	);

	foreach ($default_fields as $key => $df) {
		$query = $wpdb->get_results("SELECT * FROM $fields_table WHERE unique_id = '{$key}'");
		if(empty($query)){
			cjfm_insert($fields_table, $df);
		}
	}

}

# Generate spam protection field
####################################################################################################
function cjfm_spam_protection_field($page = null){
	$type = cjfm_get_option('spam_protection_type');
	switch ($type) {
		case 'none':
			$return = null;
		break;
		case 'qa':
			$show = 0;
			$return = null;
			if(in_array($page, cjfm_get_option('spam_protection_pages'))){ $show = 1; }
			if($show == 1){
				$return = array(
				    'type' => 'text',
				    'id' => 'spam_protection',
				    'label' => cjfm_get_option('spam_question'),
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'params' => array('placeholder' => cjfm_get_option('spam_answer_placeholder')),
				    'default' => '',
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);
			}
		break;
		case 'recaptcha':

			$show = 0;
			$return = null;

			if(in_array($page, cjfm_get_option('spam_protection_pages'))){ $show = 1; }

			if($show == 1){

			$return = '<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.cjfm_get_option('recaptcha_public_key').'"></script>
<noscript>
 <iframe src="http://www.google.com/recaptcha/api/noscript?k='.cjfm_get_option('recaptcha_public_key').'" height="300" width="500" frameborder="0"></iframe>
 <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
 <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
</noscript>';
			$return = array(
			    'type' => 'custom_html',
			    'id' => 'spam_protection',
			    'label' => __('Are you human?', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => $return,
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
			}
		break;
	}
	return $return;	
}

# Process spam protection
####################################################################################################
function cjfm_spam_protection_process($post,  $page){

	$type = cjfm_get_option('spam_protection_type');

	$errors = null;

	switch ($type) {
		case 'none':
			$return = null;
		break;
		case 'qa':
			if(strtolower(@$post['spam_protection']) != strtolower(cjfm_get_option('spam_answer'))){
				$errors[] = __('Invalid answer, please try again.', 'cjfm');
			}
		break;
		case 'recaptcha':

			if(isset($post['recaptcha_challenge_field'])){
			
				//require_once(sprintf('%s/shortcodes/recaptcha/recaptchalib.php', cjfm_item_path('modules_dir')));
				$publickey = cjfm_get_option('recaptcha_public_key');
				$privatekey = cjfm_get_option('recaptcha_private_key');

				$recaptcha_post_fields = array(
					'privatekey' => $privatekey,
					'remoteip' => cjfm_current_ip_address(),
					'challenge' => @$post['recaptcha_challenge_field'],
					'response' => @$post['recaptcha_response_field'],
				);

				$response = wp_remote_post( 'http://www.google.com/recaptcha/api/verify', array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'headers' => array(),
					'body' => $recaptcha_post_fields,
					'cookies' => array()
				    )
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   $errors[] = __('Invalid security key, please try again', 'cjfm');
				} else {
				   
					$res = explode("\n", $response['body']);
					if($res[0] != 'true' && $res[1] != 'success'){
						$errors[] = __('Invalid security key, please try again', 'cjfm');
					}

				}
				
			}
			
		break;
	}
	
	$spam_protection_pages_array = cjfm_get_option('spam_protection_pages');
	if(@in_array($page, $spam_protection_pages_array)){ 
		return $errors; 
	}else{
		return null;
	}

}

# Render reCaptcha theme script
####################################################################################################
add_action('wp_head', 'cjfm_recaptcha_theme_script');
function cjfm_recaptcha_theme_script(){
	$type = cjfm_get_option('spam_protection_type');
	if($type == 'recaptcha'){
		echo '<script type="text/javascript">var RecaptchaOptions = {theme : "'.cjfm_get_option('recaptcha_theme').'"};</script>'."\n";
	}
}

# Show Custom Profile Fields with WordPress Profile
####################################################################################################
function cjfm_sync_profile_fields($user){
	global $wpdb;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$fields = $wpdb->get_results("SELECT * FROM $fields_table ORDER BY sort_order ASC");

	$exclude_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber', 'user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf', 'heading', 'custom_html', 'paragraph');
	$text_fields = array('text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode', 'user_avatar', 'facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');

	$display[] = (cjfm_get_option('profile_sync_heading') != '') ? '<h3>'.cjfm_get_option('profile_sync_heading').'</h3>' : '';

	$display[] = '<table id="custom_user_field_table" class="form-table">';

	foreach ($fields as $key => $field) {
		if(!in_array($field->field_type, $exclude_fields)){
			$display[] = '<tr id="'.$field->unique_id.'_field">';

			if($field->field_type == 'heading'){
				$display[] = '<th><h3>'.$field->label.'</h3></th>';
			}else{
				$display[] = '<th><label for="'.$field->unique_id.'">'.$field->label.'</label></th>';
			}
			
			$display[] = '<td>';

			if($field->field_type == 'text' || in_array($field->field_type, $text_fields) && $field->unique_id != 'user_avatar'){
				$display[] = '<input type="text" name="'.$field->unique_id.'" id="'.$field->unique_id.'" value="'.get_user_meta($user->ID, $field->unique_id, true).'" class="regular-text" />';	
			}

			if($field->field_type == 'user_avatar' || in_array($field->field_type, $text_fields) && $field->unique_id == 'user_avatar'){
				if(cjfm_get_option('user_avatar_type') == 'custom'){
					$user_avatar = get_user_meta( $user->ID, 'user_avatar', true );
				}else{
					$user_avatar = cjfm_gravatar_url($user->ID, 256);
				}
				$display[] = '<input type="text" name="'.$field->unique_id.'" id="'.$field->unique_id.'" value="'.$user_avatar.'" class="regular-text" />';
				$display[] = '<p><img src="'.$user_avatar.'" style="width:100px; background:#f9f9f9; border:1px solid #ddd; padding:5px;" /></p>';
			}

			

			if($field->field_type == 'textarea'){
				$display[] = '<textarea rows="5" cols="30" name="'.$field->unique_id.'" id="'.$field->unique_id.'">'.get_user_meta($user->ID, $field->unique_id, true).'</textarea>';	
			}

			if($field->field_type == 'dropdown' || $field->field_type == 'select'){
				$display[] = '<select name="'.$field->unique_id.'" id="'.$field->unique_id.'">';
				$opts = '';
				$opts = explode("\n", $field->options);
				foreach ($opts as $okey => $ovalue) {
					if(trim(get_user_meta($user->ID, $field->unique_id, true)) == strip_tags(trim($ovalue))){
						$display[] = '<option selected value="'.strip_tags($ovalue).'">'.$ovalue.'</option>';	
					}else{
						$display[] = '<option value="'.strip_tags($ovalue).'">'.$ovalue.'</option>';
					}
				}
				$display[] = '</select>';
			}

			if($field->field_type == 'radio'){				
				$opts = '';
				$opts = explode("\n", $field->options);
				foreach ($opts as $okey => $ovalue) {
					if(trim(get_user_meta($user->ID, $field->unique_id, true)) == strip_tags(trim($ovalue))){
						$display[] = '<label><input checked type="radio" name="'.$field->unique_id.'" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';	
					}else{
						$display[] = '<label><input type="radio" name="'.$field->unique_id.'" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';	
					}
				}
			}

			if($field->field_type == 'checkbox'){
				$opts = '';
				$opts = explode("\n", $field->options);
				foreach ($opts as $okey => $ovalue) {
					$saved_values = (is_serialized(get_user_meta($user->ID, $field->unique_id, true))) ? unserialize(get_user_meta($user->ID, $field->unique_id, true)) : get_user_meta($user->ID, $field->unique_id, true);
					if(@in_array(strip_tags(trim($ovalue)), $saved_values)){
						$display[] = '<label><input checked type="checkbox" name="'.$field->unique_id.'[]" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
					}else{
						$display[] = '<label><input type="checkbox" name="'.$field->unique_id.'[]" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
					}
				}
			}

			if($field->field_type == 'multidropdown' || $field->field_type == 'multiselect'){
				$display[] = '<select multiple name="'.$field->unique_id.'[]" id="'.$field->unique_id.'">';
				$opts = '';
				$opts = explode("\n", $field->options);
				foreach ($opts as $okey => $ovalue) {
					$saved_values = (is_serialized(get_user_meta($user->ID, $field->unique_id, true))) ? unserialize(get_user_meta($user->ID, $field->unique_id, true)) : get_user_meta($user->ID, $field->unique_id, true);
					if(@in_array(strip_tags(trim($ovalue)), $saved_values)){
						$display[] = '<option selected value="'.strip_tags(trim($ovalue)).'">'.trim($ovalue).'</option>';	
					}else{
						$display[] = '<option value="'.strip_tags(trim($ovalue)).'">'.trim($ovalue).'</option>';	
					}
				}
				$display[] = '</select>';
			}

			if($field->field_type == 'cjfm_country'){
				$display[] = '<select name="'.$field->unique_id.'" id="'.$field->unique_id.'">';
				foreach (cjfm_countries_array() as $okey => $ovalue) {
					if(get_user_meta($user->ID, $field->unique_id, true) == $okey){
						$display[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';	
					}else{
						$display[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
					}
					
				}
				$display[] = '</select>';
			}

			$display[] = ($field->description != '') ? '<br /><span class="description">'.$field->description.'</span>' : '';
			$display[] = '</td>';
			$display[] = '</tr>';		
		}
	}

	$display[] = '</table>';
	echo implode('', $display);
}


function cjfm_save_profile_fields($user_id){
	global $wpdb;

	//echo '<pre>'; print_r($_POST); echo '</pre>';
	//die();

	if ( !current_user_can( 'edit_user', $user_id ) )
	return FALSE;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$fields = $wpdb->get_results("SELECT * FROM $fields_table ORDER BY sort_order ASC");

	$exclude_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber', 'user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf', 'custom_html');
	$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode', 'user_avatar');

	foreach ($fields as $key => $field) {
		if(!in_array($field->field_type, $exclude_fields)){

			if(is_array($_POST[$field->unique_id])){
				$post_value = serialize($_POST[$field->unique_id]);
			}else{
				$post_value = $_POST[$field->unique_id];
			}

			update_user_meta($user_id, $field->unique_id, $post_value);
		}
	}

}

if(cjfm_get_option('profile_sync') == 'yes'){
	add_action( 'show_user_profile', 'cjfm_sync_profile_fields' );
	add_action( 'edit_user_profile', 'cjfm_sync_profile_fields' );
	add_action( 'personal_options_update', 'cjfm_save_profile_fields' );
	add_action( 'edit_user_profile_update', 'cjfm_save_profile_fields' );
}


# Disable WordPress Admin Bar
####################################################################################################
if(cjfm_get_option('wp_admin_bar') == 'disable'){
	add_filter('show_admin_bar', '__return_false');
}

# WordPress Admin Dashboard Access
####################################################################################################
function cjfm_dashboard_access(){
	global $wpdb, $current_user;
	if(is_user_logged_in() && is_admin() && !current_user_can( 'manage_options' )){
		$dashboard_access = cjfm_get_option('wp_dashboard_access');
		$user_role = cjfm_user_role($current_user->ID);
		if(!in_array($user_role, $dashboard_access)){
			wp_redirect( site_url(), $status = 302 );
			exit;
		}
	}
}
add_action('admin_head', 'cjfm_dashboard_access');

# WordPress Default Page Redirect
####################################################################################################
function cjfm_default_page_redirect(){
	global $wpdb, $current_user;
	$cjfm_current_url = cjfm_current_url();
	$login_url = cjfm_generate_url('page_login');
	$register_url = cjfm_generate_url('page_register');
	$reset_password_url = cjfm_generate_url('page_reset_password');

	if(cjfm_get_option('page_login') != '' && !current_user_can('manage_options')){

		if(strpos($cjfm_current_url, 'wp-signup') > 0){
			wp_redirect( $register_url, $status = 302 );
			exit;
		}
		if(strpos($cjfm_current_url, 'wp-login.php') > 0){
			if(!isset($_GET['action'])){
				wp_redirect( $login_url, $status = 302 );
				exit;
			}else{
				if($_GET['action'] == 'register'){
					wp_redirect( $register_url, $status = 302 );
					exit;	
				}
				if($_GET['action'] == 'lostpassword'){
					wp_redirect( $reset_password_url, $status = 302 );
					exit;	
				}
			}
		}

	}

}
if(cjfm_get_option('wp_default_page_redirect') == 'yes'){
	add_action('init', 'cjfm_default_page_redirect');
}


# Awaiting approval users handle
####################################################################################################
add_action('init', 'cjfm_awaiting_approvals_handle');
function cjfm_awaiting_approvals_handle(){
	global $wpdb, $current_user;
	if(is_user_logged_in()){
		if(get_user_meta($current_user->ID, 'cjfm_account_approved', true) == '0'){
			wp_logout();
			$location = cjfm_string(site_url()).'cjfm_action=awm';
			wp_redirect( $location, $status = 302 );
			exit;
		}
	}
}

add_action('wp_footer', 'cjfm_awating_approvals_msg');
function cjfm_awating_approvals_msg(){
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'awm'){
		$msg = __("Your account is being reviewed. Please check back later.", 'cjfm');
		echo '<script type="text/javascript">alert("'.$msg.'");</script>';
	}
}


# RESTRICT ACCESS
####################################################################################################
function cjfm_restrict_access(){
	global $cjfm, $wpdb, $post, $wp_query;

	if(!is_user_logged_in()):

		$restrict_site = cjfm_get_option('restrict_site');

		if($restrict_site == 'yes'){
			$login_page = cjfm_get_option('page_login');
			$register_page = cjfm_get_option('page_register');
			$reset_password_page = cjfm_get_option('page_reset_password');
			$logout_page = cjfm_get_option('page_logout');
			if(!is_page($login_page) && !is_page($register_page) && !is_page($reset_password_page) && !is_page($logout_page)){
				$redirect = cjfm_string(get_permalink($login_page)).'redirect_url='.cjfm_current_url();
				wp_redirect($redirect);
				exit;
			}
		}



		$login_page_url = cjfm_string(cjfm_generate_url('page_login')).'redirect='.urlencode(cjfm_current_url('only-url')).'&cjfm_action=stop';
		$r_cats = cjfm_get_option('restrict_categories');
		$r_pages = cjfm_get_option('restrict_pages');
		$r_tags = cjfm_get_option('restrict_tags');
		$r_tax = cjfm_get_option('restrict_taxonomies');


		if(!empty($r_cats) && array_sum($r_cats) > 0 && is_category($r_cats) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_cats) && array_sum($r_cats) > 0 && in_category($r_cats) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_pages) && array_sum($r_pages) > 0 && is_page($r_pages) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_tags) && count($r_tags) > 0 && is_tag($r_tags) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_tags) && is_single() && has_tag($r_tags) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);	
			//exit;
		}

		if(!empty($r_tax) && count($r_tax) > 0){
			foreach ($r_tax as $key => $val) {
				$exp = explode('~~~~', $val);
				if(has_term( @$exp[0], @$exp[1], $post )){
					wp_redirect($login_page_url);	
					exit;
				}
			}
		}


		add_filter('the_content', 'cjfm_hide_restricted_post_content');

	endif;


	if(cjfm_get_option('password_strength_meter') == 'no'){
		echo '<style type="text/css">';
		echo '.cjfm-pw-strength{display:none}';
		echo '</style>';
	}


}
add_action('wp_head', 'cjfm_restrict_access');


function cjfm_hide_restricted_post_content($content){
	global $cjfm, $wpdb, $post;

	$login_page_url = cjfm_generate_url('page_login');

	$r_cats = cjfm_get_option('restrict_categories');
	$r_pages = cjfm_get_option('restrict_pages');
	$r_tags = cjfm_get_option('restrict_tags');
	$r_tax = cjfm_get_option('restrict_taxonomies');	

	if(!empty($r_tax) && count($r_tax) > 0){
		foreach ($r_tax as $key => $val) {
			$exp = explode('~~~~', $val);
			$rterms[] = @$exp[0];
			$rtaxonomies[] = @$exp[1];
		}
	}

	$login_link = '<a href="'.cjfm_string(cjfm_generate_url('page_login')).'redirect='.urlencode(cjfm_current_url('only-url')).'&cjfm_action=stop">Login</a>';
	$register_link = '<a href="'.cjfm_string(cjfm_generate_url('page_register')).'redirect='.urlencode(cjfm_current_url('only-url')).'&cjfm_action=stop">Register</a>';

	$message = cjfm_get_option('restricted_login_message');
	$message = str_replace('%%login_link%%', $login_link, $message);
	$message = str_replace('%%register_link%%', $register_link, $message);

	if(!is_null($post)){
		if(!empty($r_cats) && array_sum($r_cats) > 0 && in_category($r_cats, $post->ID)){
			$output = $message;
		}elseif(!empty($r_tags) && count($r_tags) > 0 && has_tag($r_tags, $post->ID)){
			$output = $message;
		}elseif(!empty($r_tax) && count($r_tax) > 0 && @has_term( $rterms, $rtaxonomies, $post->ID )){
			$output = $message;
		}elseif(!empty($r_pages) && array_sum($r_pages) > 0 && is_page($r_pages, $post->ID)){
			$output = $message;
		}else{
			$output = $content;	
		}
		return $output;
	}
}


function cjfm_countries_array($country = null){
	$countries = array( "Andorra" => "Andorra", "United Arab Emirates" => "United Arab Emirates", "Afghanistan" => "Afghanistan", "Antigua And Barbuda" => "Antigua And Barbuda", "Anguilla" => "Anguilla", "Albania" => "Albania", "Armenia" => "Armenia", "Netherlands Antilles" => "Netherlands Antilles", "Angola" => "Angola", "Antarctica" => "Antarctica", "Argentina" => "Argentina", "American Samoa" => "American Samoa", "Austria" => "Austria", "Australia" => "Australia", "Aruba" => "Aruba", "Azerbaijan" => "Azerbaijan", "Bosnia And Herzegowina" => "Bosnia And Herzegowina", "Barbados" => "Barbados", "Bangladesh" => "Bangladesh", "Belgium" => "Belgium", "Burkina Faso" => "Burkina Faso", "Bulgaria" => "Bulgaria", "Bahrain" => "Bahrain", "Burundi" => "Burundi", "Benin" => "Benin", "Bermuda" => "Bermuda", "Brunei Darussalam" => "Brunei Darussalam", "Bolivia" => "Bolivia", "Brazil" => "Brazil", "Bahamas" => "Bahamas", "Bhutan" => "Bhutan", "Bouvet Island" => "Bouvet Island", "Botswana" => "Botswana", "Belarus" => "Belarus", "Belize" => "Belize", "Canada" => "Canada", "Cocos (Keeling) Islands" => "Cocos (Keeling) Islands", "Congo, The Drc" => "Congo, The Drc", "Central African Republic" => "Central African Republic", "Congo" => "Congo", "Switzerland" => "Switzerland", "Cote D'ivoire" => "Cote D'ivoire", "Cook Islands" => "Cook Islands", "Chile" => "Chile", "Cameroon" => "Cameroon", "China" => "China", "Colombia" => "Colombia", "Costa Rica" => "Costa Rica", "Cuba" => "Cuba", "Cape Verde" => "Cape Verde", "Christmas Island" => "Christmas Island", "Cyprus" => "Cyprus", "Czech Republic" => "Czech Republic", "Germany" => "Germany", "Djibouti" => "Djibouti", "Denmark" => "Denmark", "Dominica" => "Dominica", "Dominican Republic" => "Dominican Republic", "Algeria" => "Algeria", "Ecuador" => "Ecuador", "Estonia" => "Estonia", "Egypt" => "Egypt", "Western Sahara" => "Western Sahara", "Eritrea" => "Eritrea", "Spain" => "Spain", "Ethiopia" => "Ethiopia", "Finland" => "Finland", "Fiji" => "Fiji", "Falkland Islands (Malvinas)" => "Falkland Islands (Malvinas)", "Micronesia, Federated States Of" => "Micronesia, Federated States Of", "Faroe Islands" => "Faroe Islands", "France" => "France", "France, Metropolitan" => "France, Metropolitan", "Gabon" => "Gabon", "United Kingdom" => "United Kingdom", "Grenada" => "Grenada", "Georgia" => "Georgia", "French Guiana" => "French Guiana", "Ghana" => "Ghana", "Gibraltar" => "Gibraltar", "Greenland" => "Greenland", "Gambia" => "Gambia", "Guinea" => "Guinea", "Guadeloupe" => "Guadeloupe", "Equatorial Guinea" => "Equatorial Guinea", "Greece" => "Greece", "South Georgia And South S.s." => "South Georgia And South S.s.", "Guatemala" => "Guatemala", "Guam" => "Guam", "Guinea-bissau" => "Guinea-bissau", "Guyana" => "Guyana", "Hong Kong" => "Hong Kong", "Heard And Mc Donald Islands" => "Heard And Mc Donald Islands", "Honduras" => "Honduras", "Croatia (Local Name: Hrvatska)" => "Croatia (Local Name: Hrvatska)", "Haiti" => "Haiti", "Hungary" => "Hungary", "Indonesia" => "Indonesia", "Ireland" => "Ireland", "Israel" => "Israel", "India" => "India", "British Indian Ocean Territory" => "British Indian Ocean Territory", "Iraq" => "Iraq", "Iran (Islamic Republic Of)" => "Iran (Islamic Republic Of)", "Iceland" => "Iceland", "Italy" => "Italy", "Jamaica" => "Jamaica", "Jordan" => "Jordan", "Japan" => "Japan", "Kenya" => "Kenya", "Kyrgyzstan" => "Kyrgyzstan", "Cambodia" => "Cambodia", "Kiribati" => "Kiribati", "Comoros" => "Comoros", "Saint Kitts And Nevis" => "Saint Kitts And Nevis", "Korea, D.p.r.o." => "Korea, D.p.r.o.", "Korea, Republic Of" => "Korea, Republic Of", "Kuwait" => "Kuwait", "Cayman Islands" => "Cayman Islands", "Kazakhstan" => "Kazakhstan", "Laos" => "Laos", "Lebanon" => "Lebanon", "Saint Lucia" => "Saint Lucia", "Liechtenstein" => "Liechtenstein", "Sri Lanka" => "Sri Lanka", "Liberia" => "Liberia", "Lesotho" => "Lesotho", "Lithuania" => "Lithuania", "Luxembourg" => "Luxembourg", "Latvia" => "Latvia", "Libyan Arab Jamahiriya" => "Libyan Arab Jamahiriya", "Morocco" => "Morocco", "Monaco" => "Monaco", "Moldova, Republic Of" => "Moldova, Republic Of", "Montenegro" => "Montenegro", "Madagascar" => "Madagascar", "Marshall Islands" => "Marshall Islands", "Macedonia" => "Macedonia", "Mali" => "Mali", "Myanmar (Burma)" => "Myanmar (Burma)", "Mongolia" => "Mongolia", "Macau" => "Macau", "Northern Mariana Islands" => "Northern Mariana Islands", "Martinique" => "Martinique", "Mauritania" => "Mauritania", "Montserrat" => "Montserrat", "Malta" => "Malta", "Mauritius" => "Mauritius", "Maldives" => "Maldives", "Malawi" => "Malawi", "Mexico" => "Mexico", "Malaysia" => "Malaysia", "Mozambique" => "Mozambique", "Namibia" => "Namibia", "New Caledonia" => "New Caledonia", "Niger" => "Niger", "Norfolk Island" => "Norfolk Island", "Nigeria" => "Nigeria", "Nicaragua" => "Nicaragua", "Netherlands" => "Netherlands", "Norway" => "Norway", "Nepal" => "Nepal", "Nauru" => "Nauru", "Niue" => "Niue", "New Zealand" => "New Zealand", "Oman" => "Oman", "Panama" => "Panama", "Peru" => "Peru", "French Polynesia" => "French Polynesia", "Papua New Guinea" => "Papua New Guinea", "Philippines" => "Philippines", "Pakistan" => "Pakistan", "Poland" => "Poland", "St. Pierre And Miquelon" => "St. Pierre And Miquelon", "Pitcairn" => "Pitcairn", "Puerto Rico" => "Puerto Rico", "Portugal" => "Portugal", "Palau" => "Palau", "Paraguay" => "Paraguay", "Qatar" => "Qatar", "Reunion" => "Reunion", "Romania" => "Romania", "Serbia" => "Serbia", "Russian Federation" => "Russian Federation", "Rwanda" => "Rwanda", "Saudi Arabia" => "Saudi Arabia", "Solomon Islands" => "Solomon Islands", "Seychelles" => "Seychelles", "Sudan" => "Sudan", "Sweden" => "Sweden", "Singapore" => "Singapore", "St. Helena" => "St. Helena", "Slovenia" => "Slovenia", "Svalbard And Jan Mayen Islands" => "Svalbard And Jan Mayen Islands", "Slovakia (Slovak Republic)" => "Slovakia (Slovak Republic)", "Sierra Leone" => "Sierra Leone", "San Marino" => "San Marino", "Senegal" => "Senegal", "Somalia" => "Somalia", "Suriname" => "Suriname", "South Sudan" => "South Sudan", "Sao Tome And Principe" => "Sao Tome And Principe", "El Salvador" => "El Salvador", "Syrian Arab Republic" => "Syrian Arab Republic", "Swaziland" => "Swaziland", "Turks And Caicos Islands" => "Turks And Caicos Islands", "Chad" => "Chad", "French Southern Territories" => "French Southern Territories", "Togo" => "Togo", "Thailand" => "Thailand", "Tajikistan" => "Tajikistan", "Tokelau" => "Tokelau", "Turkmenistan" => "Turkmenistan", "Tunisia" => "Tunisia", "Tonga" => "Tonga", "East Timor" => "East Timor", "Turkey" => "Turkey", "Trinidad And Tobago" => "Trinidad And Tobago", "Tuvalu" => "Tuvalu", "Taiwan, Province Of China" => "Taiwan, Province Of China", "Tanzania, United Republic Of" => "Tanzania, United Republic Of", "Ukraine" => "Ukraine", "Uganda" => "Uganda", "U.s. Minor Islands" => "U.s. Minor Islands", "United States" => "United States", "Uruguay" => "Uruguay", "Uzbekistan" => "Uzbekistan", "Holy See (Vatican City State)" => "Holy See (Vatican City State)", "Saint Vincent And The Grenadines" => "Saint Vincent And The Grenadines", "Venezuela" => "Venezuela", "Virgin Islands (British)" => "Virgin Islands (British)", "Virgin Islands (U.S.)" => "Virgin Islands (U.S.)", "Viet Nam" => "Viet Nam", "Vanuatu" => "Vanuatu", "Wallis And Futuna Islands" => "Wallis And Futuna Islands", "Samoa" => "Samoa", "Yemen" => "Yemen", "Mayotte" => "Mayotte", "South Africa" => "South Africa", "Zambia" => "Zambia", "Zimbabwe" => "Zimbabwe");
	if(is_null($country)){
		return $countries;
	}else{
		return $countries[$country];
	}
}


function cjfm_modalbox_forms(){

	global $wpdb, $post, $current_user;

	$login_page = cjfm_get_option('page_login');
	$register_page = cjfm_get_option('page_register');

	if(!is_page($login_page) && !is_page($register_page)){

		$login_form_class = isset($_POST['do_login']) ? 'show' : '';
		$register_form_class = isset($_POST['do_create_account']) ? 'show' : '';

		echo '<div style="display:none;" class="cjfm-modalbox '.$login_form_class.' '.$register_form_class.'"></div>';

		echo '<div id="cjfm-modalbox-login-form" class="'.$login_form_class.'" style="display:none;">';
		if(cjfm_get_option('modalbox_login_form_heading') != ''){
			echo '<h3>'.cjfm_get_option('modalbox_login_form_heading').'</h3>';
		}
		echo '<div class="cjfm-modalbox-login-content">';
		echo do_shortcode(cjfm_get_option('modalbox_login_form_content'));
		echo '</div>';
		echo '<a href="#close" class="cjfm-close-modalbox">x</a>';
		echo '</div>';

		echo '<div id="cjfm-modalbox-register-form" class="'.$register_form_class.'" style="display:none;">';
		if(cjfm_get_option('modalbox_register_form_heading') != ''){
			echo '<h3>'.cjfm_get_option('modalbox_register_form_heading').'</h3>';
		}
		echo '<div class="cjfm-modalbox-register-content">';
		echo do_shortcode(cjfm_get_option('modalbox_register_form_content'));
		echo '</div>';
		echo '<a href="#close" class="cjfm-close-modalbox">x</a>';
		echo '</div>';

	}

}
if(cjfm_get_option('modalbox_forms') == 'yes'){
	add_action('wp_footer', 'cjfm_modalbox_forms');
}


function cjfm_register_nav_menus(){
	$nav_menus = cjfm_item_vars('nav_menus');
	register_nav_menus($nav_menus);
}
add_action('init', 'cjfm_register_nav_menus');


function cjfm_navigation_menu(){
	global $current_user;
	if(is_user_logged_in()){
		return 'cjfm_users_menu';
	}else{
		return 'cjfm_visitors_menu';
	}
}


function cjfm_extend_maintenance_mode_head(){
	$cjfm_css_url = cjfm_item_path('item_url').'/assets/css/cjfm.css';
	echo '<link href="'.$cjfm_css_url.'" rel="stylesheet" />';
}
add_action('cjfm_maintenance_mode_head', 'cjfm_extend_maintenance_mode_head');



# Parse .csv data
####################################################################################################
function cjfm_parse_csv($file_url, $return = 'data'){
	$class = cjfm_item_path('item_dir').'/options/inc';
	require_once(sprintf('%s/parsecsv.lib.php', $class));

	# create new parseCSV object.
	$csv = new parseCSV();

	/*$csv->delimiter = "{$delimiter}";   # tab delimited
	$csv->parse('_books.csv');*/

	# Parse '_books.csv' using automatic delimiter detection...
	$csv->auto($file_url);
	
	if($return == 'data'){
		return $csv->data;	
	}else{
		return $csv;
	}
}


function cjfm_create_csv($filename = null, $headings_array = null, $row_array = null){
	$output_file = (is_null($filename)) ? date('Y-m-d-H-i-s') : $filename;

	if(is_null($row_array)) { return; }

	// output headers so that the file is downloaded rather than displayed
	header ("Content-type: Application/CSV"); 
	header('Content-Disposition: attachment; filename='.$output_file.'.csv');

	// create a file pointer connected to the output stream
	$output = @fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, $headings_array);

	// loop over the rows, outputting them
	foreach ($row_array as $key => $value) {
		fputcsv($output, $value);
	}
	

	fclose($output);
	exit;
}



// Replace WordPress Gravatar with user uploaded photo
function cjfm_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	$user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
        } elseif ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id' , $id );
            }
    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }

    if ( $user && is_object( $user ) ){
    	$custom_avatar = cjfm_user_info($user->data->ID, 'user_avatar');
    	$custom_avatar = cjfm_resize_image($custom_avatar, $size, $size, true, true);
    	if ($custom_avatar) 
    		$return = '<img src="'.$custom_avatar.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" class="avatar avatar-'.$size.' photo" />';
    	elseif ($avatar) 
    		$return = $avatar;
    	else 
    		$return = '<img src="'.$default.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" class="avatar avatar-'.$size.' photo" />';
    	return $return;
    }else{
    	return $avatar;
    }
}
if(cjfm_get_option('user_avatar_type') == 'custom'){
	add_filter('get_avatar', 'cjfm_gravatar_filter', 10, 5);
}