<?php
/**
 * Filename: common.php
 * Description: common.php loads commonly accessed functions across the Visser Labs suite.
 */

if ( ! function_exists( 'woo_get_action' ) ) {
    /**
     * Get the action from the $_GET or $_POST array.
     *
     * @param bool $switch Whether to check both $_GET and $_POST arrays.
     *
     * @return string|bool The action string or false if not found.
     */
    function woo_get_action( $switch = false ) { // phpcs:ignore
        if ( $switch ) {
            if ( isset( $_GET['action'] ) ) { // phpcs:ignore
                $action = sanitize_text_field( $_GET['action'] ); // phpcs:ignore
            } elseif ( ! isset( $action ) && isset( $_POST['action'] ) ) { // phpcs:ignore
                $action = sanitize_text_field( $_POST['action'] ); // phpcs:ignore
            } else {
                $action = false;
            }
        } elseif ( isset( $_POST['action'] ) ) { // phpcs:ignore

            $action = sanitize_text_field( $_POST['action'] ); // phpcs:ignore
        } elseif ( ! isset( $action ) && isset( $_GET['action'] ) ) { // phpcs:ignore
            $action = sanitize_text_field( $_GET['action'] ); // phpcs:ignore
        } else {
            $action = false;
        }
        return $action;
    }
}
