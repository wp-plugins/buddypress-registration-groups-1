<?php

/*
Plugin Name: BuddyPress Registration Groups
Plugin URI: http://hardlyneutral.com/wordpress-plugins/
Description: WordPress/WordPress MU and BuddyPress. Allows a new user to select groups to join during the registration process.
Version: 0.5
Tags: buddypress, groups, registration, autojoin
Requires at least: WordPress 2.9.2 or WordPress MU 2.9.2 / BuddyPress 1.2.5.2
Tested up to: WordPress 3.0.1 / BuddyPress 1.2.5.2
License: GNU/GPL 2
Author: Eric Johnson
Author URI: http://hardlyneutral.com/
*/

/* Only load the component if BuddyPress is loaded and initialized. */
function bp_registration_groups_init() {
	require( dirname( __FILE__ ) . '/includes/bp-registration-groups.php' );
}
add_action( 'bp_init', 'bp_registration_groups_init' );

?>