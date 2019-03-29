<?php
/*
Plugin Name: EC Miscellanea
Description: A collection of miscellaneous items used by Exchange Church. Current custom post types: Leadership
Version: 1.1
Author: Doug Penny
*/

$plugin_path = dirname( __FILE__ );
require_once $plugin_path . '/leadership-custom-post-type.php';

if ( file_exists( $plugin_path . '/CMB2/init.php' ) ) {
	require_once $plugin_path .  '/CMB2/init.php';
}

/*
Change the post title placeholder text
*/
function ec_change_title_text( $title ){
	$screen = get_current_screen();
	if  ( 'ec-leadership' == $screen->post_type ) {
		 $title = 'First and last name';
	}
 
	return $title;
}
add_filter( 'enter_title_here', 'ec_change_title_text' );