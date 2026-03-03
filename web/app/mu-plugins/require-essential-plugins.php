<?php
/**
 * Ensure Essential Plugins
 *
 * This mu-plugin ensures that critical plugins are always active.
 * Mu-plugins (must-use plugins) cannot be disabled from the admin panel.
 *
 * @package CKL_Car_Rental
 */

/**
 * Ensure Post SMTP is always active
 *
 * Post SMTP (Postman) is essential for reliable email delivery via Brevo.
 * Without it, WordPress default mail() function is used which often
 * results in emails going to spam or failing to deliver entirely.
 *
 * @return void
 */
function ckl_require_essential_plugins() {
	$required_plugins = array(
		'post-smtp/postman-smtp.php',
	);

	foreach ( $required_plugins as $plugin ) {
		if ( ! is_plugin_active( $plugin ) ) {
			activate_plugin( $plugin );
		}
	}
}
add_action( 'admin_init', 'ckl_require_essential_plugins' );

/**
 * Prevent deactivation of essential plugins
 *
 * Removes the "Deactivate" link from the plugins list for essential plugins.
 *
 * @param array  $actions     An array of plugin action links.
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @return array Modified actions array.
 */
function ckl_prevent_essential_plugin_deactivation( $actions, $plugin_file ) {
	$essential_plugins = array(
		'post-smtp/postman-smtp.php',
	);

	if ( in_array( $plugin_file, $essential_plugins, true ) ) {
		unset( $actions['deactivate'] );
	}

	return $actions;
}
add_filter( 'plugin_action_links', 'ckl_prevent_essential_plugin_deactivation', 10, 2 );

/**
 * Add admin notice if Post SMTP is not configured
 *
 * Checks if SMTP settings are properly configured.
 * Displays a warning if the plugin is active but not configured.
 *
 * @return void
 */
function ckl_check_smtp_configuration() {
	// Only show on Post SMTP settings page
	if ( ! isset( $_GET['page'] ) || 'postman-smtp' !== $_GET['page'] ) {
		return;
	}

	$_options = get_option( 'postman_options', array() );
	$hostname = isset( $options['hostname'] ) ? $options['hostname'] : '';
	$sender_email = isset( $options['sender_email'] ) ? $options['sender_email'] : '';

	if ( empty( $hostname ) || empty( $sender_email ) ) {
		echo '<div class="notice notice-warning"><p>';
		echo '<strong>CKL Car Rental:</strong> Please configure SMTP settings. ';
		echo 'Go to <a href="' . esc_url( admin_url( 'admin.php?page=postman-smtp' ) ) . '">Post SMTP Settings</a> ';
		echo 'and configure the SMTP connection with your Brevo API key.';
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'ckl_check_smtp_configuration' );
