<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $cjfm_item_vars, $wpdb;

/*** Do not change anything in this file unless you know what you are doing ***/

# Item info
####################################################################################################
$cjfm_item_vars['item_info'] = array(
	'item_type' => 'plugin', // plugin or theme
	'item_id' => '6F9TIXR506', // Unique ID of the item
	'item_name' => 'Frontend Membership Modules',
	'item_version' => '1.4.6',
	'text_domain' => 'cjfm', 
	'options_table' => $wpdb->prefix.'cjfm_options', 
	'addon_tables' => 'cjfm_custom_fields,cjfm_invitations,cjfm_temp_users',
	'page_title' => 'Membership Modules', 
	'menu_title' => 'Membership Modules', 
	'page_slug' => 'cjfm',
	
	'license_url' => 'http://cssjockey.com/terms-of-use',
	'recover_password_url' => 'http://cssjockey.com/dashboard/forgot-password/',
	'item_url' => 'http://cssjockey.com/shop/wordpress-plugin/front-end-membership-modules/',
	'premium_membership_url' => 'http://cssjockey.com/premium-membership/',
	'quick_start_guide_url' => 'http://docs.cssjockey.com/cjfm/quick-start-guide/',
	'documentation_url' => 'http://docs.cssjockey.com/cjfm',
	'support_forum_url' => 'http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/',
	'feature_request_url' => 'http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/new-feature-requests/',
	'report_bugs_url' => 'http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/bugs-issues/',
);


$options_table = $cjfm_item_vars['item_info']['options_table'];

$table_check = $wpdb->get_row("DESCRIBE $options_table");

if(!empty($table_check)){
	$registration_type = $wpdb->get_row("SELECT * FROM $options_table WHERE option_name = 'register_type'");

	if(!empty($registration_type)){
		if($registration_type->option_value == 'approvals'){
			$approvals = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'cjfm_account_approved' AND meta_value = '0'");
			if(!empty($approvals)){
				$approval_menu['cjfm_approve_accounts'] = '<span class="orange">'.sprintf(__('Approve Accounts <span class="badge badge-warning">%d</span>', 'cjfm'), count($approvals)).'</span>';
			}else{
				$approval_menu['cjfm_approve_accounts'] = '<span class="orange">'.sprintf(__('Approve Accounts <span class="badge badge-default">%d</span>', 'cjfm'), count($approvals)).'</span>';
			}
		}
	}	
}

# Dropdown items
####################################################################################################
$cjfm_item_vars['dropdown'] = array(
	'Configuration' => array(
		'cjfm_maintenance_settings' => __('Maintenance Mode', 'cjfm'),
		'cjfm_configuration' => __('Basic Configuration', 'cjfm'),
		'cjfm_page_setup' => __('Page Setup', 'cjfm'),
		'cjfm_restrict_content' => __('Restricted Content', 'cjfm'),
		'cjfm_spam_protection' => __('Spam Protection', 'cjfm'),
		'cjfm_social_login' => __('Social Login Setup', 'cjfm'),
		'cjfm_modalbox_forms' => __('Modalbox Forms', 'cjfm'),
		'cjfm_customize' => __('Custom CSS or Javascript', 'cjfm'),
	),
	'cjfm_customize_fields' => __('Customize Forms', 'cjfm'),
	'customize_email_messages' => array(
		'cjfm_customize_emails_variables' => __('Dynamic Variables', 'cjfm'),
		'cjfm_customize_emails_config' => __('Outgoing Email Settings', 'cjfm'),
		'cjfm_customize_emails_registration' => __('Registration Emails', 'cjfm'),
		'cjfm_customize_emails_password' => __('Reset Password Emails', 'cjfm'),
	),
	'cjfm_csv_import' => __('Import/Export Users', 'cjfm'),
);
if(!empty($registration_type)){
	if($registration_type->option_value == 'approvals'){
		$cjfm_item_vars['dropdown'] = @array_merge($cjfm_item_vars['dropdown'], $approval_menu);
	}
	if($registration_type->option_value == 'invitations'){
		$invitations_table = $wpdb->prefix.'cjfm_invitations';
		$invitation_requests = $wpdb->get_results("SELECT * FROM $invitations_table WHERE invited = '0'");
		$invitations_menu['Invitations_('.count($invitation_requests).')'] = array(
			'cjfm_invitations_setup' => __('Basic Configuration', 'cjfm'),
			'cjfm_invitations' => sprintf(__('Invitation Requests (%d)', 'cjfm'), count($invitation_requests)) ,
		);
		$cjfm_item_vars['dropdown'] = array_merge($cjfm_item_vars['dropdown'], $invitations_menu);
	}
}

# Option Files
####################################################################################################
$cjfm_item_vars['option_files'] = array(
	'plugin_addon_options',
	'cjfm_configuration',
	'cjfm_maintenance_settings',
	'cjfm_page_setup',
	'cjfm_customize',
	'cjfm_spam_protection',
	'cjfm_invitations_setup',
	'cjfm_restrict_content',
	'cjfm_social_login',
	'cjfm_modalbox_forms',
	'cjfm_customize_emails_config',
	'cjfm_customize_emails_registration',
	'cjfm_customize_emails_password',
	'cjfm_customize_emails_variables',
);

# Load Modules
####################################################################################################
$cjfm_item_vars['modules'] = array(
	'functions/global',
	'shortcodes/global',
	'widgets/global',

	'functions/item-assistant',
	
	'shortcodes/cjfm_login_form',
	'shortcodes/cjfm_register_form',
	'shortcodes/cjfm_logout',
	'shortcodes/cjfm_reset_password_form',
	'shortcodes/cjfm_user_profile',
	'shortcodes/cjfm_page_links',
	'shortcodes/cjfm_user_content',
	'shortcodes/cjfm_user_meta',
	'shortcodes/cjfm_social_login',
	'shortcodes/cjfm_delete_account',

	'widgets/cjfm_custom_message',

	'functions/woocommerce',
	'functions/ajax',
);


# Load Extras
####################################################################################################
$cjfm_item_vars['load_extras'] = array();


# Sidebar Vars
####################################################################################################
$cjfm_item_vars['sidebar_vars'] = array(
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="title">',
	'after_title' => '</h3>',
);




# Theme Nav Menus
####################################################################################################
//$cjfm_item_vars['nav_menus'] = array();
$cjfm_item_vars['nav_menus'] = array(
	'cjfm_visitors_menu' => 'Visitors Only Menu (Membership Modules) <a href="http://docs.cssjockey.com/cjfm/configuring-nav-menus/" target="_blank">Documentation</a>',
	'cjfm_users_menu' => 'Users Only Menu (Membership Modules) <a href="http://docs.cssjockey.com/cjfm/configuring-nav-menus/" target="_blank">Documentation</a>',
);


# Database Tables
####################################################################################################
$options_table = $cjfm_item_vars['item_info']['options_table'];
$cjfm_item_vars['db_tables']['sql'] = "
	CREATE TABLE IF NOT EXISTS `{$options_table}` (
        `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `option_name` varchar(64) NOT NULL DEFAULT '',
        `option_value` longtext NOT NULL,
        PRIMARY KEY (`option_id`),
        UNIQUE KEY `option_name` (`option_name`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";



$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
$cjfm_item_vars['db_tables']['custom_fields'] = "
	CREATE TABLE IF NOT EXISTS `{$custom_fields_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `field_type` varchar(200) NOT NULL DEFAULT '',
        `unique_id` varchar(200) NOT NULL DEFAULT '',
        `label` varchar(100) NOT NULL DEFAULT '',
        `description` text NOT NULL DEFAULT '',
        `required` varchar(10) NOT NULL DEFAULT '',
        `profile` varchar(10) NOT NULL DEFAULT '',
        `register` varchar(10) NOT NULL DEFAULT '',
        `invitation` varchar(10) NOT NULL DEFAULT '',
        `enabled` varchar(10) NOT NULL DEFAULT '',
        `options` text NOT NULL DEFAULT '',
        `sort_order` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_id` (`unique_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";

$invitations_table = $wpdb->prefix.'cjfm_invitations';
$cjfm_item_vars['db_tables']['invitations'] = "
	CREATE TABLE IF NOT EXISTS `{$invitations_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_email` varchar(255) NOT NULL,
        `invitation_key` text NOT NULL,
        `user_data` longtext NOT NULL,
        `dated` datetime NOT NULL,
        `invited` INT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_email` (`user_email`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";

$temp_users_table = $wpdb->prefix.'cjfm_temp_users';
$cjfm_item_vars['db_tables']['temp_users'] = "
	CREATE TABLE IF NOT EXISTS `{$temp_users_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_email` varchar(255) NOT NULL,
        `activation_key` text NOT NULL,
        `user_data` longtext NOT NULL,
        `dated` datetime NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_email` (`user_email`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";


# Recommended or Required Plugins
####################################################################################################
$cjfm_item_vars['install_plugins'] = array();
/*
$cjfm_item_vars['install_plugins'] = array(
	
	// This is an example of how to include a plugin pre-packaged with a theme
	array(
		'name'     				=> 'TGM Example Plugin', // The plugin name
		'slug'     				=> 'tgm-example-plugin', // The plugin slug (typically the folder name)
		'source'   				=> get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source
		'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
		'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
		'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
		'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
		'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
	),
	

	// This is an example of how to include a plugin from the WordPress Plugin Repository
	array(
		'name' 		=> 'WordPress SEO by Yoast',
		'slug' 		=> 'http://wordpress.org/plugins/wordpress-seo/',
		'required' 	=> false,
	),
);
*/

# Custom Post Types
####################################################################################################
$cjfm_item_vars['custom_post_types'] = array();
/*
$cjfm_item_vars['custom_post_types']['listings'] = array(
	'labels' => array(
		'name' => __('Listings', 'cjfm'),
		'singular_name' => __('Listing', 'cjfm'),
		'add_new' => _x('Add New', 'Listings'),
		'add_new_item' => __('Add New Listing', 'cjfm'),
		'edit_item' => __('Edit Listing', 'cjfm'),
		'new_item' => __('New Listing', 'cjfm'),
		'view_item' => __('View Listing', 'cjfm'),
		'search_items' => __('Search Listings', 'cjfm'),
		'not_found' => __('No Listings found', 'cjfm'),
		'not_found_in_trash' => __('No Listings found in Trash', 'cjfm'),
		'parent_item_colon' => ''
	),
	'args' => array(
		'exclude_from_search' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'has_archive' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'listings', 'with_front' => false, 'hierarchical' => true ),
		'capability_type' => 'post',
		'taxonomies' => array('shop'),
		'hierarchical' => false,
		'menu_position' => 20,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
	)
);
*/

# Custom Taxonomies
####################################################################################################
$cjfm_item_vars['custom_taxonomies'] = array();
/*$cjfm_item_vars['custom_taxonomies']['shop'] = array(
	'name' => __('Shop Categories', 'cjfm'),
    'singular_name' => __('Shop Category', 'cjfm'),
    'search_items' => __('Search Shop Category', 'cjfm'),
    'all_items' => __('All Shop Categories', 'cjfm'),
    'parent_item' => __('Parent Shop Category', 'cjfm'),
    'parent_item_colon' => __('Parent Shop Category:', 'cjfm'),
    'edit_item' => __('Edit Shop Category', 'cjfm'),
    'update_item' => __('Update Shop Category', 'cjfm'),
    'add_new_item' => __('Add New Shop Category', 'cjfm'),
    'new_item_name' => __('New Shop Category', 'cjfm'),
    'post_types' => array('Listings'),
);*/