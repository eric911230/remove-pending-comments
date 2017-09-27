<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load translations
 *
 */
function remove_pending_load_translation_file() {
	$plugin_path = plugin_basename( dirname( __FILE__ ) .'/../languages' );
	
	load_plugin_textdomain( 'remove-pending-comments', '', $plugin_path );
}

/**
 * Load admin CSS style
 *
 */
function remove_pending_css_admin() { 
	?>

	<link rel="stylesheet" href="<?php echo get_bloginfo( 'home' ) . '/' . PLUGINDIR . '/remove-pending-comments/css/admin.css' ?>" type="text/css" media="all" /> 

	<?php
}

/**
 * Add admin page and CSS
 */
function remove_pending_add_pages() {
	$page = add_submenu_page( 
		'edit-comments.php', 
		__( 'Remove Pending Comments', 'remove-pending-comments' ), 
		__( 'Remove Pending Comments', 'remove-pending-comments' ), 
		10, 
		'remove-pending-comments', 
		'remove_pending_options_page' 
	);

	add_action( 'admin_head-' . $page, 'remove_pending_css_admin' );
}

/**
 * The admin page
 */
function remove_pending_options_page() {
	$magic_string = __("I am sure I want to remove all pending comments", 'remove-pending-comments' );

	if ( current_user_can( 'manage_options' ) ) { 
		?>

		<div class="wrap"> 

			<?php
			if ( isset( $_POST['removepending'] ) && ! empty( $_POST['removepending'] ) ) {
				
				$nonce = $_REQUEST['_wpnonce'];
				if ( ! wp_verify_nonce( $nonce, 'remove-pending-comments' ) ) {
					die( 'You are not authorized.' );
				}

				$comments = get_comments( 'status=hold&number=1' );

				if ( $comments ) {
					if ( stripslashes( $_POST['removepending'] ) == $magic_string ) {

						global $wpdb;

						$wpdb->query( 
							$wpdb->prepare( "DELETE FROM $wpdb->comments WHERE comment_approved = 0" )
						);

						echo '<div class="updated">';
							_e( 'Just removed all pending comments. Please check!', 'remove-pending-comments' );
						echo '</div>';
					} else {
						echo '<div class="error">';
							_e( 'Please try again. Did you type the text properly?', 'remove-pending-comments' );
						echo '</div>';
					}
				} else {
					echo '<div class="error">';
						_e( 'It looks like there aren\'t any pending comments!', 'remove-pending-comments' );
					echo '</div>';
				}
			} 
			?>

			<h2>
				<?php _e( 'Remove Pending Comments', 'remove-pending-comments' ) ?>
			</h2> 

			<p>
				<?php _e( 'You have to type the following text into the form to remove all pending comments:', 'remove-pending-comments' ); ?>
			</p>

			<blockquote>
				<?php echo $magic_string ?>
			</blockquote>
		
			<form action="" method="post">
				<?php function_exists( 'wp_nonce_field' ) ? wp_nonce_field( 'remove-pending-comments' ) : null; ?>

				<input name="removepending" type="text" size="80" >

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Remove Pending Comments', 'remove-pending-comments' ) ?>">
				</p>
			</form>
		</div>

		<?php
	}
}