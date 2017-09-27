<?php
/*
Plugin Name: Remove Pending Comments
Plugin URI: 
Author: Eric
Description: A quick way to remove all pending comments.
Version: 1.0
Text Domain: remove-pending-comments
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function remove_pending_load() {
	if ( is_admin() ) {
		require_once( 'inc/admin.php' );
		
		add_action( 'init', 'remove_pending_load_translation_file' );
		add_action( 'admin_menu', 'remove_pending_add_pages' );
	}
}
remove_pending_load();
