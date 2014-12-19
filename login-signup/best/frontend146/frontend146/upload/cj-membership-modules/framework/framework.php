<?php 
/**
 * @package WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version 1.0.1
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

function cjfm_framework_info($var = null){
	$return['version'] = '2014.01';
	$return['developer'] = 'Mohit Aneja';
	$return['developer_url'] = 'http://www.cssjockey.com';
	if(is_null($var)){
		return $return;
	}else{
		return $return[$var];
	}
}

function cjfm_item_info($var = null){
	global $cjfm_item_vars;
	if(is_null($var)){
		return $cjfm_item_vars['item_info'];
	}else{
		return $cjfm_item_vars['item_info'][$var];
	}
}

function cjfm_item_vars($var){
	global $cjfm_item_vars;
	if(isset($cjfm_item_vars[$var])){
		return $cjfm_item_vars[$var];
	}
}


function cjfm_item_options(){
	global $cjfm_item_options;
	$option_files = cjfm_item_vars('option_files');
	if(!empty($option_files)){
		foreach ($option_files as $key => $file) {
			require_once(sprintf('%s/'.$file.'.php', cjfm_item_path('options_dir')));
		}
	}	
	return $cjfm_item_options;
}

function cjfm_locale_setup(){
	$location = explode('/', str_replace(WP_CONTENT_DIR.'/', '', __FILE__));
	$count = 0;
	$string = '';
	foreach ($location as $key => $value) {
		$count++;
		if($count < 3){
			$string .= $value;
		}
	}
	$oname = sha1($string);
	if(get_option( $oname ) == ''){
		update_option( $oname, cjfm_item_info('text_domain') );
	}
}
cjfm_locale_setup();

# Register actions and hooks
####################################################################################################

function cjfm_admin_init(){
	// Create admin menu and page.
	add_action( 'admin_menu' , 'cjfm_admin_menu_page');
	// Enable admin scripts and styles
	add_action( 'admin_enqueue_scripts' , 'cjfm_admin_scripts' );	
}
add_action('init', 'cjfm_admin_init');


# Setup admin page and menu
####################################################################################################
function cjfm_admin_menu_page(){
		global $menu;
		$main_menu_exists = false;
		foreach ($menu as $key => $value) {
			if($value[2] == 'cj-products'){
				$main_menu_exists = true;
			}
		}
		if(!$main_menu_exists){
			$menu_icon = cjfm_item_path('admin_assets_url', 'img/menu-icon.png');
			add_menu_page( 'CSSJockey', 'CSSJockey', 'manage_options', 'cj-products', 'cjfm_cj_products', $menu_icon);	
		}
		$menu_icon = cjfm_item_path('admin_assets_url', 'img/menu-icon.png');
	    add_submenu_page( 'cj-products', cjfm_item_info('page_title'), cjfm_item_info('menu_title'), 'manage_options', cjfm_item_info('page_slug'), 'cjfm_admin_page_setup');
	    do_action('cjfm_admin_menu_hook');
	    //remove_submenu_page( 'cj-products', 'cj-products' );	
}

function cjfm_cj_products(){
	require_once(sprintf('%s/cj-products.php', cjfm_item_path('includes_dir')));
}

function cjfm_admin_page_setup(){
    require_once(sprintf('%s/admin_page.php', cjfm_item_path('includes_dir')));
}

# Get Admin Menu by Parent Slug
####################################################################################################
function cjfm_list_submenu_admin_pages($parent, $offset = 3){
    global $submenu;
    if ( is_array( $submenu ) && isset( $submenu[$parent] ) ) {
        foreach ( (array) $submenu[$parent] as $item) {
            if ( $parent == $item[2] || $parent == $item[2] )
                continue;
            // 0 = name, 1 = capability, 2 = file
            if ( current_user_can($item[1]) ) {
                $menu_file = $item[2];
                if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
                    $menu_file = substr( $menu_file, 0, $pos );
                if ( file_exists( ABSPATH . "wp-admin/$menu_file" ) ) {
                    //$options[] = "<a href='{$item[2]}'$class>{$item[0]}</a>";
                    $options[$item[2]] = $item[$offset];
                } else {
                    //$options[] = "<a href='admin.php?page={$item[2]}'>{$item[0]}</a>";
                    $options[$item[2]] = $item[$offset];
                }
            }
        }
        return $options;
    }
}

# Setup admin scripts and styles
####################################################################################################
function cjfm_admin_scripts(){

	$wp_version = get_bloginfo('version');

	if(is_admin() && isset($_GET['page']) && $_GET['page'] == cjfm_item_info('page_slug')){


		// Media Upload
		wp_enqueue_script('media-upload');
		wp_enqueue_media();

		// Animate css
		// wp_enqueue_style('cj-animate-css', cjfm_item_path('helpers_url') . '/animate.css');

		// Icons
		wp_enqueue_style('cj-fontawesome-css', cjfm_item_path('helpers_url') . '/icons/font-awesome/css/font-awesome.min.css');

		// Bootstrap
		wp_enqueue_script('cj-admin-bootstrap-js', cjfm_item_path('helpers_url') . '/bootstrap/admin/js/bootstrap.min.js', '', '', true);
		wp_enqueue_style('cj-admin-bootstrap-css', cjfm_item_path('helpers_url') . '/bootstrap/admin/css/bootstrap.min.css');


		// Quick search
		wp_enqueue_script('cj-quicksearch-js', cjfm_item_path('helpers_url') . '/quicksearch/jquery.quicksearch.js', array('jquery'), '', true);

		// jQuery UI
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		//wp_enqueue_script('cj-jquery_ui_js', cjfm_item_path('helpers_url') .'/jquery-ui/js/jquery-ui.min.js');
		wp_enqueue_script('cj-jquery_ui_timepicker_js', cjfm_item_path('helpers_url') .'/jquery-ui/js/jquery-ui-timepicker-addon.js', array('jquery'), '', true);
		wp_enqueue_style('cj-jquery_ui_css', cjfm_item_path('helpers_url') .'/jquery-ui/css/smoothness/jquery-ui.min.css');


		// Code mirror
		wp_enqueue_script('cj-codemirror_js', cjfm_item_path('helpers_url') .'/codemirror/lib/codemirror.js', '', '', true);
		wp_enqueue_script('cj-codemirror_colors_js', cjfm_item_path('helpers_url') .'/codemirror/mode/css/css.js', '', '', true);
		wp_enqueue_style('cj-codemirror_css', cjfm_item_path('helpers_url') .'/codemirror/lib/codemirror.css');
		wp_enqueue_style('cj-codemirror_theme_css', cjfm_item_path('helpers_url') .'/codemirror/theme/ambiance.css');


		// Chosen
		wp_enqueue_script('cj-chosen_js', cjfm_item_path('helpers_url') .'/chosen/chosen.jquery.min.js', array('jquery'), '', true);
		wp_enqueue_style('cj-chosen_css', cjfm_item_path('helpers_url') .'/chosen/chosen.css');

		// Color
		wp_enqueue_script('cj-color_js', cjfm_item_path('helpers_url') .'/color/spectrum.js', '', '', true);
		wp_enqueue_style('cj-color_css', cjfm_item_path('helpers_url') .'/color/spectrum.css');

		// Backstretch
		// wp_enqueue_script('cj-admin-slider-backstretch-js', cjfm_item_path('helpers_url') . '/backstretch.js');

		// Admin scripts
		wp_enqueue_script('cj-admin-js', cjfm_item_path('admin_assets_url') . '/js/admin.js', array('jquery'),'',true);

		
		if($wp_version >= 3.8){
			wp_enqueue_style('cj-admin-css', cjfm_item_path('admin_assets_url') . '/css/admin-3.8.min.css');	
		}else{
			wp_enqueue_style('cj-admin-css', cjfm_item_path('admin_assets_url') . '/css/admin.css');	
		}
		
	}


	// Chosen
	wp_enqueue_script('cj-chosen_js', cjfm_item_path('helpers_url') .'/chosen/chosen.jquery.min.js', array('jquery'),'',true);
	wp_enqueue_style('cj-chosen_css', cjfm_item_path('helpers_url') .'/chosen/chosen.css');

	wp_enqueue_script('cj-global-admin-js', cjfm_item_path('admin_assets_url') .'/js/global-admin.js', array('jquery'),'',true);
	wp_enqueue_style('cj-global-admin-css', cjfm_item_path('admin_assets_url') .'/css/global-admin.css');

}


# Setup and get item path based on item type
####################################################################################################
function cjfm_item_path($var = null, $path = null, $return = null){
	if(cjfm_item_info('item_type') == 'plugin')
	{

		$plugin_dir = str_replace('framework', '', untrailingslashit( plugin_dir_path( __FILE__ ) ));
		$plugin_url = str_replace('/framework', '', plugins_url( '' , __FILE__ ));


		$item_path['item_url'] = $plugin_url;
		$item_path['item_dir'] = realpath($plugin_dir);

		$item_path['framework_url'] = $plugin_url.'/framework';
		$item_path['framework_dir'] = realpath($plugin_dir.'/framework');
		
		$item_path['options_url'] = $plugin_url.'/options';
		$item_path['options_dir'] = realpath($plugin_dir.'/options');
		
		$item_path['modules_url'] = $plugin_url.'/modules';
		$item_path['modules_dir'] = realpath($plugin_dir.'/modules');
		
		$item_path['includes_url'] = $plugin_url.'/framework/includes';
		$item_path['includes_dir'] = realpath($plugin_dir.'/framework/includes');
		
		$item_path['helpers_url'] = $plugin_url.'/framework/assets/admin/helpers';
		$item_path['helpers_dir'] = realpath($plugin_dir.'/framework/assets/admin/helpers');
		
		$item_path['admin_assets_url'] = $plugin_url.'/framework/assets/admin';
		$item_path['admin_assets_dir'] = realpath($plugin_dir.'/framework/assets/admin');
		
		$item_path['theme_assets_url'] = $plugin_url.'/framework/assets/frontend';
		$item_path['theme_assets_dir'] = realpath($plugin_dir.'/framework/assets/frontend');

		$item_path['ajax_url'] = $plugin_url.'/framework/includes/admin_ajax.php';

	}
	elseif(cjfm_item_info('item_type') == 'theme')
	{
		$theme_dir = get_stylesheet_directory_uri();
		$theme_url = get_stylesheet_uri();

		$current_theme = cjfm_get_option('color_scheme');

		$item_path['item_url'] = $theme_url;
		$item_path['item_dir'] = realpath($theme_dir);

		$item_path['framework_url'] = $theme_url.'/extend/framework';
		$item_path['framework_dir'] = realpath($theme_dir.'/extend/framework');

		$item_path['options_url'] = $theme_url.'/extend/options';
		$item_path['options_dir'] = realpath($theme_dir.'/extend/options');

		$item_path['modules_url'] = $theme_url.'/extend/modules';
		$item_path['modules_dir'] = realpath($theme_dir.'/extend/modules');

		$item_path['includes_url'] = $theme_url.'/extend/framework/includes';
		$item_path['includes_dir'] = realpath($theme_dir.'/extend/framework/includes');

		$item_path['helpers_url'] = $theme_url.'/extend/framework/assets/admin/helpers';
		$item_path['helpers_dir'] = realpath($theme_dir.'/extend/framework/assets/admin/helpers');

		$item_path['admin_assets_url'] = $theme_url.'/extend/framework/assets/admin';
		$item_path['admin_assets_dir'] = realpath($theme_dir.'/extend/framework/assets/admin');

		$item_path['theme_assets_url'] = $theme_url.'/assets/'.$current_theme;
		$item_path['theme_assets_dir'] = realpath($theme_dir.'/assets/'.$current_theme);


		$item_path['ajax_url'] = $theme_url.'/extend/framework/includes/admin_ajax.php';
		$item_path['current_theme_url'] = get_template_directory_uri().'/assets/'.$current_theme;
	}

	

	if(!is_null($return)){
		return $item_path;
	}else{
		if(is_null($path))
		{
			return $item_path[$var];
		}else
		{
			return $item_path[$var].'/'.$path;
		}
	}

}


# Check for item upgrades
####################################################################################################
function cjfm_item_upgrades(){
	if(cjfm_item_info('item_type') == 'theme'){
		require_once(sprintf('%s/check-updates/themes/update.php', cjfm_item_path('includes_dir')));
	}else{
		require_once(sprintf('%s/check-updates/plugins/update.php', cjfm_item_path('includes_dir')));
	}
}

# Render admin form for options page
####################################################################################################
function cjfm_admin_form($options){
	require_once(sprintf('%s/admin_form.php', cjfm_item_path('includes_dir')));
}

# Render admin form for pages other than options page
####################################################################################################
function cjfm_admin_form_raw($options, $search_box = null, $return = null, $chzn_class = 'chzn-select-no-results'){
	global $display;
	$display = '';
	require(sprintf('%s/admin_form_raw.php', cjfm_item_path('includes_dir')));
	if(is_null($return)){
		echo implode('', $display);
	}else{
		return implode('', $display);
	}
}

# Render frontend forms
####################################################################################################
function cjfm_display_form($options){
	include(sprintf('%s/frontend_forms.php', cjfm_item_path('includes_dir')));
	return implode("\n", $display);
}

# Render shortcode options forms
####################################################################################################
function cjfm_shortcode_form($options){
	include(sprintf('%s/shortcode_form.php', cjfm_item_path('includes_dir')));
	return implode("\n", $display);
}

# Show Messages
####################################################################################################
function cjfm_show_message($type = 'warning', $message){
	return '<div class="alert alert-'.$type.'">'.$message.'</div>';
}


# Returns Post default or default value for admin_form_raw
####################################################################################################
function cjfm_post_default($field, $default = null){
	if(isset($_POST[$field])){
		if(!is_array($_POST[$field])){
			return stripcslashes($_POST[$field]);
		}else{
			return $_POST[$field];
		}
	}else{
		return $default;
	}
}

# Returns saved option
####################################################################################################
function cjfm_get_option($var, $print = false){
	global $wpdb;
	$return = '';
	$table = cjfm_item_info('options_table');
	$query = $wpdb->get_row("SELECT * FROM $table WHERE option_name = '{$var}'");
	if(!empty($query)){
		if(is_serialized($query->option_value)){
			$return = @unserialize($query->option_value);
		}else{
			$return = stripcslashes(html_entity_decode($query->option_value));
		}
	}
	if($print){
		echo $return;
	}else{
		return $return;
	}
}


// Generate url from theme options page option.
#############################################################################
function cjfm_generate_url($option_id = null, $url_string = null, $params = array()){
	if(is_null($option_id)){
		$display[] = __('Opiton ID not defined, please check code.', 'cjfm');
	}else{

		if(is_null($url_string)){
			$display[] = get_permalink( cjfm_get_option($option_id) );
		}else{
			if(!empty($params)){
				$url_string = '';
				$count = 0;
				foreach ($params as $key => $value) {
					$count++;
					if($count == 1){
						$url_string .= $key.'='.$value;	
					}else{
						$url_string .= '&'.$key.'='.$value;
					}
					
				}
			}
			$display[] = cjfm_string(get_permalink( cjfm_get_option($option_id) )).$url_string;
		}
	}
	return implode("\n", $display);
}


# Update option (add-on options)
####################################################################################################
function cjfm_update_option($option_name, $option_value){
	global $wpdb;
	$options_table = cjfm_item_info('options_table');

	if(is_array($option_value)){
		$option_value = serialize($option_value);
	}else{
		$option_value = $option_value;
	}
	$update_option_data = array(
		'option_name' => $option_name,
		'option_value' => $option_value,
	);
	$option_info = $wpdb->get_row("SELECT * FROM $options_table WHERE option_name = '{$option_name}'");

	cjfm_update($options_table, $update_option_data, 'option_id', $option_info->option_id);
	
}

# Load Modules
####################################################################################################
function cjfm_load_modules(){
	global $cjfm_item_vars;
	$modules = $cjfm_item_vars['modules'];
	if(!empty($modules)){
		foreach ($modules as $key => $module) {
			require_once(sprintf('%s/'.$module.'.php', cjfm_item_path('modules_dir')));
		}
		return true;
	}else{
		return false;
	}
}


# Generates callback URL for redirects and other tasks
####################################################################################################
function cjfm_callback_url($callback  = null){
	$text_domain = cjfm_item_info('text_domain');
	if(!is_null($callback)){
		return admin_url('admin.php?page=').cjfm_item_info('page_slug').'&callback='.$callback;
	}else{
		return admin_url('admin.php?page=').cjfm_item_info('page_slug');
	}
}

// Save Post Types
####################################################################################################
function cjfm_save_post_types(){
	$post_types = get_post_types();
	$exclude = array('attachment', 'revision', 'nav_menu_item', 'page', 'post');
	foreach ($exclude as $key => $ex) {
		unset($post_types[$ex]);
	}
	update_option( 'cj_post_types', $post_types );
}
add_action('admin_footer', 'cjfm_save_post_types');


// Register post types
#############################################################################
function cjfm_register_post_types() {
	global $cjfm_item_vars;
	$custom_post_types = @$cjfm_item_vars['custom_post_types'];
	if(is_array($custom_post_types)){
		foreach ($custom_post_types as $key => $post_type) {
			$labels = '';
			$labels = $post_type['labels'];
			$args = array(
				'labels' =>	$labels,
				'public' =>	$post_type['args']['public'],
				'publicly_queryable' =>	$post_type['args']['publicly_queryable'],
				'show_ui' =>	$post_type['args']['show_ui'],
				'show_in_menu' =>	$post_type['args']['show_in_menu'],
				'query_var' =>	$post_type['args']['query_var'],
				'rewrite' =>	$post_type['args']['rewrite'],
				'capability_type' =>	$post_type['args']['capability_type'],
				'has_archive' =>	$post_type['args']['has_archive'],
				'hierarchical' =>	$post_type['args']['hierarchical'],
				'menu_position' =>	$post_type['args']['menu_position'],
				'supports' =>	$post_type['args']['supports'],
				'menu_icon' =>    $post_type['args']['menu_icon'],
				'taxonomies' =>    $post_type['args']['taxonomies'],
			); 
			register_post_type( $key, $args );
		}
	}
}



function cjfm_register_taxonomies() {

	global $cjfm_item_vars;

	$custom_taxonomies = @$cjfm_item_vars['custom_taxonomies'];

	if(is_array($custom_taxonomies)){

		foreach ($custom_taxonomies as $key => $taxonomy) {

			$labels = '';
			$labels = $taxonomy['labels'];

			$args = array(
				'hierarchical' => $taxonomy['args']['hierarchical'],
				'labels' => $labels,
				'show_ui' => $taxonomy['args']['show_ui'],
				'show_admin_column' => $taxonomy['args']['show_admin_column'],
				'query_var' => $taxonomy['args']['query_var'],
				'rewrite' => $taxonomy['args']['rewrite'],
			);

			register_taxonomy( $key , $taxonomy['post_types'], $args );
			
		}

	}

}


# Setup Metaboxes class
####################################################################################################
function cjfm_meta_boxes(){
	if ( ! class_exists( 'cmb_Meta_Box' ) ){
		require_once(sprintf('%s/metabox/init.php', cjfm_item_path('helpers_dir')));
	}
}


# Install required or recommended plugins
####################################################################################################
function cjfm_install_plugins(){
	global $cjfm_item_vars, $cjfm_register_plugins;
	$cjfm_register_plugins = $cjfm_item_vars['install_plugins'];
	if(!empty($cjfm_register_plugins)){
		require_once(sprintf('%s/install-plugins/register_plugins.php', cjfm_item_path('includes_dir')));
	}
}

# Shortcode Generator
####################################################################################################
function cjfm_shortcode_generator(){
	require_once(sprintf('%s/shortcode_generator.php', cjfm_item_path('includes_dir')));
}

# Raw code form a shortcode
####################################################################################################
/*function cjfm_formatter($content) {
       $new_content = '';
       $pattern_full = '{(\[raw\].*?\[/raw\])}is';
       $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
       $pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
       foreach ($pieces as $piece) {
               if (preg_match($pattern_contents, $piece, $matches)) {
                       $new_content .= $matches[1];
               } else {
                       $new_content .= wptexturize(wpautop($piece));
               }
       }
       return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
add_filter('the_content', 'cjfm_formatter', 99);*/


# Run shortcodes via PHP
####################################################################################################
function cjfm_do_shortcode($shortcode){
	$shortcode = do_shortcode($shortcode);
	$shortcode = str_replace('[raw]', '', $shortcode);
	$shortcode = str_replace('[/raw]', '', $shortcode);
	return $shortcode;
}


# Show alert messages (error, warning, info, success)
####################################################################################################
function cjfm_message($type, $message, $close = null){
	$close_btn = '';
	if(!is_null($close)){
		$close_btn = '<a class="alert-close" href="#close" title=""><i class="cj-icon icon-remove"></i></a>';
	}
	return '<div class="cj-alert rounded alert-'.$type.'"><div class="cj-alert-content">'.__($message, 'cjfm').$close_btn.'</div></div>';
}

# Rearrange Files for sorting
####################################################################################################
function cjfm_reArrayFiles(&$file_post) 
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}


# Database Insert
####################################################################################################
function cjfm_insert($table, $data) 
{
	global $wpdb;
	foreach ($data as $field => $value) {
		$fields[] = '`' . esc_sql($field) . '`';
		$values[] = "'" . esc_sql($value) . "'";
	}
	$field_list = join(',', $fields);
	$value_list = join(', ', $values);
	$query = "INSERT INTO `" . $table . "` (" . $field_list . ") VALUES (" . $value_list . ")";
	$wpdb->query($query);
	return $wpdb->insert_id;
}

# Database Update
####################################################################################################
function cjfm_update($table, $data, $id_field, $id_value) 
{
	global $wpdb;
	foreach ($data as $field => $value) {
		$fields[] = sprintf("`%s` = '%s'", $field, esc_sql($value));
	}
	$field_list = join(',', $fields);
	$query = sprintf("UPDATE `%s` SET %s WHERE `%s` = %s", $table, $field_list, $id_field, intval($id_value));
	$wpdb->query($query);
}

# Format URL string
####################################################################################################
function cjfm_string($string){
	if(strpos($string, '?') > 0){
		return $string.'&';
	}else{
		return $string.'?';
	}
}

# Return Unique string (ALPHA NUMERIC)
####################################################################################################
function cjfm_unique_string(){
	$unique_string = sprintf(
		"%04s%03s%s", base_convert(mt_rand(0, pow(36, 4) - 1), 10, 36), base_convert(mt_rand(0, pow(36, 3) - 1), 10, 36), substr(sha1(md5(strtotime(date('Y-m-d H:i:s')))), 7, 3)
    );
    return strtoupper($unique_string);
}

# Create Google font string
####################################################################################################
function cjfm_google_fonts_string(){
	global $cjfm_item_vars;
	$google_fonts = cjfm_get_option( 'google_fonts' );
	
	if(!empty($google_fonts)):

		$google_fonts_keys = array_keys(cjfm_get_option( 'google_fonts' ));

		$item_options = cjfm_item_options();
		foreach ($item_options as $key => $options) {
			foreach ($options as $key => $option) {
				if($option['type'] == 'font'){
					$font_vars = cjfm_get_option( $option['id'] );
					if(in_array($font_vars['family'], $google_fonts_keys)){
						$load_google_fonts_array[$font_vars['family']] = $google_fonts[$font_vars['family']];
					}

				}
			}
		}
		foreach ($load_google_fonts_array as $key => $font) {
			$string[] = urlencode($key).':'.@implode(',', $font['variants']);
		}
		return @implode('|', $string);
		
	endif;
}


# Email function with WordPress Mail Class
####################################################################################################
function cjfm_email($emaildata, $content_above = null, $content_below = null, $attachments = null){
	$to = $emaildata['to'];
	$from_name = $emaildata['from_name'];
	$from = $emaildata['from_email'];
	$subject = $emaildata['subject'];
	$content = $emaildata['message'];

	$headers = "From: {$from_name} <{$from}>" . "\r\n\\";
	$headers .= "Reply-To: {$from}\r\n";
	$headers .= "Return-Path: {$from}\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";

	$msg = $content_above;
	$msg .= $content;
	$msg .= $content_below;
	$message = $msg;
	
	if(wp_mail($to, $subject, $message, $headers, $attachments)){
		return true;
	}
	//return mail($to, $subject, $message, $headers);
}

# Check if email address is valid
####################################################################################################
function cjfm_is_email_valid($email){
	return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
}


# Check if data string is JSON
####################################################################################################
function cjfm_is_json($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}


# Return all user info by user_login, user_email or ID
####################################################################################################
function cjfm_user_info($input, $var = null){
	global $wpdb;
	$user = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_login = '{$input}' OR user_email = '{$input}' OR ID = '{$input}'");
	if(!empty($user)){

		foreach ($user as $key => $value) {
			$users_data[$key] = $value; 
		}

		$usermeta = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE user_id = '{$user->ID}'");

		foreach ($usermeta as $key => $value) {
			$users_data[$value->meta_key] = $value->meta_value;
		}

		if(!is_null($var)){
			return @$users_data[$var];	
		}else{
			return @$users_data;
		}			
	}else{
		return null;
	}
}

# Parse Usermeta String
####################################################################################################
function cjfm_parse_usermeta($user_id_or_email, $string = '%%display_name%%'){

	$user_info = cjfm_user_info($user_id_or_email);

	foreach ($user_info as $key => $user) {
		$search = "%%{$key}%%";
		$string = str_replace($search, $user_info[$key], $string);
	}

	return $string;
}

# Return user role by user id, email or username
####################################################################################################
function cjfm_user_role($user_info_or_id){
	global $wpdb, $wp_roles;
	if(is_array($user_info_or_id)){
		$uid = cjfm_user_info($user_info_or_id, 'ID');
	}else{
		$uid = $user_info_or_id;
	}
	$user = get_userdata( $uid );
	if($user && !empty($user->roles)){
		$capabilities = $user->{$wpdb->prefix . 'capabilities'};
		if ( !isset( $wp_roles ) ){
			$wp_roles = new WP_Roles();
		}
		foreach ( $wp_roles->role_names as $role => $name ){
			if ( array_key_exists( $role, $capabilities ) ){
				return $role;
			}
		}
	}else{
		return 'non-user';
	}
}


# Set a cookie as usual, but ALSO add it to $_COOKIE so the current page load has access
####################################################################################################
function cjfm_set_cookie($name, $value='', $expire = 86400, $path='', $domain='', $secure=false, $httponly=false){
    $_COOKIE[$name] = $value;
    return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}

# Get cookie value
####################################################################################################
function cjfm_get_cookie($name){
    return $_COOKIE[$name];
}


# Return local date based on the settings in General Settings tab.
####################################################################################################
function cjfm_local_date($format, $date = null){
	if($date == null){
		$datetime = strtotime(date('Y-m-d H:i:s'));
	}else{
		$datetime = strtotime(date('Y-m-d H:i:s', $date));
	}
	$timezone_string = get_option('timezone_string');
	if($timezone_string != ''){
		date_default_timezone_set($timezone_string);
		$return = date($format, $datetime);
	}else{
		$return = date($format, $datetime);
	}
	return $return;
}

function cjfm_time_ago($ptime){
    $etime = time() - $ptime;
    if ($etime < 1){
        return __('Just now', 'cjfm');
    }
    $a = array( 
    	12 * 30 * 24 * 60 * 60  =>  __('year', 'cjfm'),
		30 * 24 * 60 * 60       =>  __('month', 'cjfm'),
		24 * 60 * 60            =>  __('day', 'cjfm'),
		60 * 60                 =>  __('hour', 'cjfm'),
		60                      =>  __('minute', 'cjfm'),
		1                       =>  __('second', 'cjfm'),
    );
    $plurals = array( 
    	'year' => __('years', 'cjfm'),
		'month' => __('months', 'cjfm'),
		'day' => __('days', 'cjfm'),
		'hour' => __('hours', 'cjfm'),
		'minute' => __('minutes', 'cjfm'),
		'second' => __('seconds', 'cjfm'),
    );
    foreach ($a as $secs => $str){
        $d = $etime / $secs;
        if ($d >= 1){
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $plurals[$str] : $str) .' '. __('ago', 'cjfm');
        }
    }
}



# Trim text with specified number of chars
####################################################################################################
function cjfm_trim_text($str, $cut = 200, $after_trim = ''){
    $str_length = strlen($str);
    if($str_length > $cut){
    	return substr($str, 0, $cut). $after_trim;
    }else{
    	return $str;
    }
}


# Returns current URL of the page
####################################################################################################
function cjfm_current_url($only_url = null){
	$pageURL = (is_ssl()) ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}

	if(is_null($only_url)){
		return $pageURL;
	}else{
		$url = explode('?', $pageURL);
		return $url[0];
	}	
}

# Remove query string var
####################################################################################################
function cjfm_remove_querystring_var($url, $key) { 
	$url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
	$url = substr($url, 0, -1); 
	return $url; 
}

# Format currency and add symbol.
####################################################################################################
function cjfm_currency($symbol = '$', $amount = null, $align = 'left'){
	$amount = number_format($amount, 2);
	$return = ($align == 'left') ? $symbol.' '.$amount : $amount.' '.$symbol;
	return $return;
}

# Returns current IP address
####################################################################################################
function cjfm_current_ip_address(){
	return $_SERVER['REMOTE_ADDR'];
}

# Check if its localhost or webhost
####################################################################################################
function cjfm_is_local(){
	if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
		return true;
	}else{
		return false;
	}
}

# Check if its localhost or webhost
####################################################################################################
function cjfm_get_exchange_rate($from = 'USD', $to = 'INR'){
	$exchange_rate_data = wp_remote_get('http://rate-exchange.appspot.com/currency?from='.$from.'&to='.$to);
	if(!is_wp_error( $exchange_rate_data )){
		$return = json_decode($exchange_rate_data['body']);
		return $return->rate;
	}else{
		return 62;
	}
}


# File Uplaod
####################################################################################################
function cjfm_file_upload($field_name, $allowed_width = null, $allowed_height = null, $allowed_file_types = null, $output = 'guid', $allowed_file_size = null){
	global $wpdb;

	$KB = '1024';
	$MB = '1048576';
	$GB = '1073741824';
	$TB = '1099511627776';

	$errors = null;
	$wp_upload_dir = wp_upload_dir();
	$tempFile = @$_FILES[$field_name]['tmp_name'];
	$targetPath = $wp_upload_dir['path'] . '/';
	$targetFile =  @$_FILES[$field_name]['name'];
	$fileParts = @pathinfo($_FILES[$field_name]['name']);
	$ext = '.' . @$fileParts['extension'];
	$file_size = @$_FILES[$field_name]['size'];
	if(!is_null($allowed_file_size) && $file_size > ($allowed_file_size * $KB)){
		$errors[] = sprintf(__('File size must be below %s kilobytes.', 'cjfm'), $allowed_file_size);
	}

	list($img_width, $img_height) = @getimagesize($tempFile);

	if(!is_null($allowed_width) && $img_width != $allowed_width){
		$errors[] = sprintf(__('Image width must be %s pixels.', 'cjfm'), $allowed_width);
	}

	if(!is_null($allowed_height) && $img_width != $allowed_height){
		$errors[] = sprintf(__('Image height must be %s pixels.', 'cjfm'), $allowed_height);
	}

	if(!is_null($allowed_file_types) && !in_array(str_replace('.', '', $ext), explode('|', $allowed_file_types))){
		$errors[] = __('Invalid file type.', 'cjfm');
	}

	if(is_array($errors)){
		return $errors;
	}else{
		$newFileName = wp_unique_filename( $targetPath, $targetFile );
		$targetFile = str_replace('//', '/', $targetPath) . $newFileName;
		move_uploaded_file($tempFile, $targetFile);
		$filename = $targetFile;
		$wp_filetype = wp_check_filetype(basename($filename), null );
		$attachment = array(
		    'guid' => $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $filename ),
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		    'post_content' => '',
		    'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename);
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		global $wpdb;
		$guid = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '{$attach_id}'");
		if($output == 'guid'){
			return $guid->guid;
		}else{
			return $attach_id;
		}
		
	}
}


# File Uplaods
####################################################################################################
function cjfm_file_uploads($field_name, $allowed_width = null, $allowed_height = null, $allowed_file_types = null, $output = 'guid', $allowed_file_size = null){
	global $wpdb;

	$errors = null;
	$wp_upload_dir = wp_upload_dir();
	$tempFile = @$field_name;
	$targetPath = $wp_upload_dir['path'] . '/';
	$targetFile = @$filename;
	$fileParts = @pathinfo($filename);
	$ext = explode('.', $tempFile);
	$ext = $ext[1];

	$file_size = @$_FILES[$field_name]['size'];
	if(!is_null($allowed_file_size) && $file_size > ($allowed_file_size * $KB)){
		$errors[] = sprintf(__('File size must be below %s kilobytes.', 'cjfm'), $allowed_file_size);
	}

	list($img_width, $img_height) = @getimagesize($tempFile);

	if(!is_null($allowed_width) && $img_width != $allowed_width){
		$errors[] = sprintf(__('Image width must be %s pixels.', 'cjfm'), $allowed_width);
	}

	if(!is_null($allowed_height) && $img_width != $allowed_height){
		$errors[] = sprintf(__('Image height must be %s pixels.', 'cjfm'), $allowed_height);
	}

	if(!is_null($allowed_file_types) && !in_array(str_replace('.', '', $ext), explode('|', $allowed_file_types))){
		$errors[] = __('Invalid file type.', 'cjfm');
	}
	
	if(is_array($errors)){
		return $errors;
	}else{
		//$targetFile = str_replace('//', '/', $targetPath) . 'img_' . sha1(md5(date('M-d-y H:i:s')).rand(5,99999)) . '.'.$ext;
		$newFileName = wp_unique_filename( $targetPath, $targetFile );
		$targetFile = str_replace('//', '/', $targetPath) . $newFileName;
		move_uploaded_file($tempFile, $targetFile);
		$filename = $targetFile;
		$wp_filetype = wp_check_filetype(basename($filename), null );
		$attachment = array(
		    'guid' => $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $filename ),
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		    'post_content' => '',
		    'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename);
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		global $wpdb;
		$guid = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '{$attach_id}'");
		if($output == 'guid'){
			return $guid->guid;
		}else{
			return $attach_id;
		}
		
	}
}



# Template Type
####################################################################################################
function cjfm_template_type(){
	global $wp_query;
	
	$return = '';

	if(is_404()){ $return = '404'; }
	if(is_archive()){ $return = 'archive'; }
	if(is_attachment()){ $return = 'attachment'; }
	if(is_author()){ $return = 'author'; }
	if(is_category()){ $return = 'category'; }
	if(is_front_page()){ $return = 'front_page'; }
	if(is_home()){ $return = 'home'; }
	if(is_page()){ $return = 'page'; }
	if(is_search()){ $return = 'search'; }
	if(is_single()){ $return = 'single'; }
	if(is_tag()){ $return = 'tag'; }
	if(is_tax()){ $return = 'tax'; }

	if(is_page_template()){ 
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
		$return = str_replace('.php', '', basename($template_name));
	}
	return 'content/'.$return;
}

# Template Type Name
####################################################################################################
function cjfm_template_type_name(){
	global $wp_query;
	
	$return = '';

	if(is_404()){ $return = '404'; }
	if(is_archive()){ $return = 'archive'; }
	if(is_attachment()){ $return = 'attachment'; }
	if(is_author()){ $return = 'author'; }
	if(is_category()){ $return = 'category'; }
	if(is_home()){ $return = 'blog'; }
	if(is_page()){ $return = 'page'; }
	if(is_search()){ $return = 'search'; }
	if(is_single()){ $return = 'post'; }
	if(is_tag()){ $return = 'tag'; }
	if(is_tax()){ $return = 'tax'; }
	if(is_front_page()){ $return = 'homepage'; }

	
	return $return;
}

# Template Title
####################################################################################################
function cjfm_template_title($post = null){
	global $wp_query, $post;

	$template = str_replace('content/', '', cjfm_template_type());

	if(is_day()){
		$archive_title = get_the_time('F jS, Y');
	}elseif(is_month()){
		$archive_title = get_the_time('F, Y');
	}elseif(is_year()){
		$archive_title = get_the_time('Y');
	}elseif(is_post_type_archive()){
		$archive_title = post_type_archive_title( '', false );
	}

	if(is_404()){ $return = '<span class="highlight-term">'.__('Oops!! Page not found.', 'cjfm').'</span>'; }
	if(is_archive()){ $return = sprintf(__('Archive for <span class="highlight-term">"%s"</span>', 'cjfm'), @$archive_title ); }
	if(is_attachment()){ $return = '<span class="highlight-term">'.$post->post_title.'</span>'; }
	if(is_author()){ $return = '<span class="highlight-term">'.__('Articles posted by: ', 'cjfm').ucwords(cjfm_user_info($post->post_author, 'display_name')).'</span>'; }
	if(is_category()){ $return = sprintf(__('Archive for <span class="highlight-term">"%s"</span> category', 'cjfm'), single_cat_title( '', false )); }
	if(is_front_page()){ $return = 'front_page'; }
	if(is_home()){ $return = '<span class="highlight-term">'.__('Recent Blog Posts', 'cjfm').'</span>'; }
	if(is_page()){ $return = '<span class="highlight-term">'.$post->post_title.'</span>'; }
	if(is_search()){ $return = sprintf(__('Search results for <span class="highlight-term">"%s"</span>', 'cjfm'), @$_GET['s']); }
	if(is_single()){ $return = '<span class="highlight-term">'.$post->post_title.'</span>'; }
	if(is_tag()){ $return = sprintf(__('Posts tagged <span class="highlight-term">"%s"</span>', 'cjfm'), single_tag_title( '', false )); }
	if(is_tax()){ $return = sprintf(__('Archive for <span class="highlight-term">"%s"</span>', 'cjfm'), single_term_title( '', false )); }

	return @$return;
}


# Post or Page Featured Image
####################################################################################################
function cjfm_featured_image($size = null, $single = false){
	global $post;
	if(has_post_thumbnail( $post->ID )){
		if(!is_array($size)){
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
		}else{
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			$image = cjfm_resize_image($image[0], $size[0], $size[1], true, $single);
		}
		if($single){
			$return = $image[0];
		}else{
			$return = $image;
		}
	}else{
		$return[] = 'http://placehold.it/600x600/eeeeee/cccccc&text=No+Thumbnail';
		$return[] = 150;
		$return[] = 150;
		return $return;
	}
	return $return;
}



# Post or Page Featured Image With POST ID
####################################################################################################
function cjfm_post_featured_image($post_id, $size = null, $single = false){
	global $post;
	if(has_post_thumbnail( $post_id )){
		if(!is_array($size)){
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
		}else{
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			$image = cjfm_resize_image($image[0], $size[0], $size[1], true, false);
		}
		
		if($single){
			$return = $image[0];
		}else{
			$return = $image;
		}
	}else{
		

		$return[] = 'http://placehold.it/600x600/eeeeee/cccccc&text=No+Thumbnail';
		$return[] = 250;
		$return[] = 250;
		return $return;
	}
	return $return;
}

# Resize images for custom thumbnails and other displays
####################################################################################################
function cjfm_resize_image($src, $width, $height = null, $crop = false, $single = false){
	require_once(sprintf('%s/aq_resizer.php', cjfm_item_path('helpers_dir')));
	$resized = aq_resize($src, $width, $height, $crop, $single);
	if(!empty($resized)){
		return $resized;
	}else{
		$placeholder = 'http://placehold.it/'.$width.'x'.$height.'&text=No+Thumbnail';;
		if($single){
			$return = $placeholder;
		}else{
			$return[] = $placeholder;
			$return[] = $width;
			$return[] = $height;
		}
		return $return;
	}
}

# Get Gravatar URL
####################################################################################################
function cjfm_gravatar_url($user_id_or_email, $size = 150){
	$user_email = cjfm_user_info($user_id_or_email, 'user_email');
	$default = cjfm_item_path('framework_url').'/assets/admin/img/logo.png';
	return 'http://www.gravatar.com/avatar/'.md5($user_email).'?d=mm';
	/*$gravatar = get_avatar( $user_id_or_email, $size, null, $default);
    preg_match("/src='(.*?)'/i", $gravatar, $matches);
    return $matches[1];*/
}


# Get Post by meta key from postmeta table
####################################################################################################
function cjfm_post_by_metakey($meta_key, $meta_value){
	global $wpdb;
	$query = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '{$meta_key}' and meta_value = '{$meta_value}'");
	return (!empty($query)) ? $query->post_id : false;
}

# Get post count by term
####################################################################################################
function cjfm_post_count($term_id){
	global $wpdb;
	$posts = get_posts("category={$term_id}"); 
	$count = count($posts); 
	return $count; 
}


function cjfm_load_iconset_css(){
	$enable_fonts = @cjfm_item_vars('load_extras');
	if(isset($enable_fonts['icomoon-icons']) && $enable_fonts['icomoon-icons'] == 1){
		$icons_url = cjfm_item_path('framework_url').'/assets/helpers/icons/icomoon/all/';
		for ($i=1; $i < 13; $i++) { 
			echo '<link rel="stylesheet" id="cjfm_iconset-'.$i.'"  href="'.$icons_url.'ncicons-'.$i.'/style.css" type="text/css" media="all" />';
		}	
	}
}
add_action('wp_head', 'cjfm_load_iconset_css');


function cjfm_item_assistant_holder(){
	echo '<div class="cj-assistant">';
	echo do_action('cj_assistant_hook');
	echo '</div>';
}
add_action('admin_footer', 'cjfm_item_assistant_holder');


function cjfm_convert_html($html){
	$return = '<span class="cj-code">';
	$return .= htmlentities($html);
	$return .= '</span>';
	return $return;
}

add_filter( 'no_texturize_shortcodes', 'cjfm_shortcode_no_wptexturize' );
function cjfm_shortcode_no_wptexturize($shortcodes){
    $shortcodes[] = 'rev_slider';
    return $shortcodes;
}


function cjfm_add_settings_link( $links ) {
    $settings_link = '<a href="'.cjfm_callback_url('core_welcome').'">'.__('Settings', 'cjfm').'</a>';
    $settings_link .= ' | <a href="'.cjfm_callback_url('core_uninstall').'">'.__('Uninstall', 'cjfm').'</a>';
  	array_push( $links, $settings_link );
  	return $links;
}



if(cjfm_item_info('item_type') == 'plugin'){
	$plugin = str_replace('framework/framework.php', 'index.php', plugin_basename( __FILE__ ));
	add_filter( "plugin_action_links_$plugin", 'cjfm_add_settings_link' );
}


require_once(sprintf('%s/init.php', cjfm_item_path('includes_dir')));
