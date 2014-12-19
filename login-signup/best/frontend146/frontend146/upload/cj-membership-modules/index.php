<?php 
/*
Plugin Name: CSSJockey Membership Modules
Plugin URI: http://cssjockey.com/frontend-membership-wordpress-plugin/
Description: This plugin provide various shortcodes and PHP functions to bypass default WordPress screens and enable Login, Registration, Reset Password, Edit Profile and Logout features directly on the front-end of your website or blog. It allows you to restrict specified Pages, Categories or content with any page or post only to logged in members. Normal users will be redirected to Login page to view the restricted content on your website.
Author: Mohit Aneja (CSSJockey)
Version: 1.4.6
Author URI: http://CSSJockey.com/
*/
ob_start();
function cjfm_load_textdomain() {
	$lang_path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	load_plugin_textdomain( 'cjfm', false, $lang_path );
}
add_action( 'init', 'cjfm_load_textdomain');

require_once('item_setup.php');
require_once(sprintf('%s/framework/framework.php', dirname(__FILE__)));

do_action('cjfm_functions');
//set_site_transient('update_themes', null);