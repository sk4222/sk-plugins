<?php
global $wpdb;

/**
 * Runs custom hooks and actions on various events.
 *
 * This file is called before the framework's hooks.php and load module functions
 * feel free to run the code within these functions to extend this plugin as per needs
 * 
 * @var user_info (Array)
 * Returns the user data from users and usermeta table in an array
 *
 **/


# Login ############################################################################################
function cjfm_login_done_functions($user_info){
	global $user_info, $wpdb;
	/*
		this code runs once login is done.
	*/
}




# Register #########################################################################################
function cjfm_registeration_done_functions($user_info){
	global $user_info, $wpdb;
	/*
		this code runs once registertration is complete and user is logged in.
	*/
}




# Edit Profile #####################################################################################
function cjfm_profile_updated_functions($user_info){
	global $user_info, $wpdb;
	/*
		this code runs once user profile is updated.
	*/
}




# Logout ###########################################################################################
function cjfm_before_logout_functions($user_info){
	global $user_info, $wpdb;
	/*
		this code runs before user logs out via plugin's logout link or button.
	*/
}





add_action('cjfm_login_done', 'cjfm_login_done_functions', 10, 1);
add_action('cjfm_registeration_done', 'cjfm_registeration_done_functions', 10, 1);
add_action('cjfm_profile_updated', 'cjfm_profile_updated_functions', 10, 1);
add_action('cjfm_before_logout', 'cjfm_before_logout_functions', 10, 1);