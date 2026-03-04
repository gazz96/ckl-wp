<?php
/**
 * QuickSwap AJAX Actions Handler
 *
 * Handles quick actions (activate, deactivate, switch, etc.)
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_AJAX_Actions {

    /**
     * Initialize AJAX handlers
     */
    public static function init() {
        add_action('wp_ajax_quickswap_action', array(__CLASS__, 'handle_action'));
    }

    /**
     * Handle action request
     */
    public static function handle_action() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'quickswap_search_nonce')) {
            wp_send_json_error(array(
                'message' => __('Invalid security token.', 'quickswap'),
            ));
        }

        // Get action parameters
        $action_id = sanitize_key($_POST['action_id'] ?? '');
        $provider_id = sanitize_key($_POST['provider'] ?? '');
        $item_id = sanitize_text_field($_POST['item_id'] ?? '');
        $item_data = isset($_POST['item_data']) ? json_decode(stripslashes($_POST['item_data']), true) : array();

        if (empty($action_id) || empty($provider_id) || empty($item_id)) {
            wp_send_json_error(array(
                'message' => __('Missing required parameters.', 'quickswap'),
            ));
        }

        // Get provider
        $provider = QuickSwap_Search::get_provider($provider_id);

        if (!$provider) {
            wp_send_json_error(array(
                'message' => __('Invalid provider.', 'quickswap'),
            ));
        }

        // Check access
        if (!$provider->can_access()) {
            wp_send_json_error(array(
                'message' => __('You do not have permission to perform this action.', 'quickswap'),
            ));
        }

        // Execute action
        $result = $provider->execute_action($action_id, array(
            'id' => $item_id,
            'url' => $item_data['url'] ?? '',
            'meta' => $item_data['meta'] ?? array(),
        ));

        // Handle result
        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message(),
            ));
        }

        wp_send_json_success($result);
    }

    /**
     * Handle navigation request
     */
    public static function handle_navigation() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'quickswap_search_nonce')) {
            wp_send_json_error(array(
                'message' => __('Invalid security token.', 'quickswap'),
            ));
        }

        // Get navigation target
        $target = sanitize_text_field($_POST['target'] ?? '');

        if (empty($target)) {
            wp_send_json_error(array(
                'message' => __('Missing navigation target.', 'quickswap'),
            ));
        }

        // Validate URL
        if (!wp_http_validate_url($target)) {
            wp_send_json_error(array(
                'message' => __('Invalid URL.', 'quickswap'),
            ));
        }

        wp_send_json_success(array(
            'url' => $target,
        ));
    }
}
