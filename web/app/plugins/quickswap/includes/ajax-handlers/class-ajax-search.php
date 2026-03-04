<?php
/**
 * QuickSwap AJAX Search Handler
 *
 * Handles AJAX search requests
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_AJAX_Search {

    /**
     * Initialize AJAX handlers
     */
    public static function init() {
        // Logged in users
        add_action('wp_ajax_quickswap_search', array(__CLASS__, 'handle_search'));
        add_action('wp_ajax_quickswap_suggestions', array(__CLASS__, 'handle_suggestions'));

        // Non-logged in users (if frontend search is enabled)
        add_action('wp_ajax_nopriv_quickswap_search', array(__CLASS__, 'handle_frontend_search'));
    }

    /**
     * Handle search request
     */
    public static function handle_search() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'quickswap_search_nonce')) {
            wp_send_json_error(array(
                'message' => __('Invalid security token. Please refresh the page and try again.', 'quickswap'),
            ));
        }

        // Check capability
        if (!current_user_can('quickswap_use_search')) {
            wp_send_json_error(array(
                'message' => __('You do not have permission to use this feature.', 'quickswap'),
            ));
        }

        // Get and sanitize query
        $query = sanitize_text_field($_POST['query'] ?? '');
        if (empty($query)) {
            wp_send_json_error(array(
                'message' => __('Please enter a search query.', 'quickswap'),
            ));
        }

        // Minimum query length
        if (strlen($query) < 2) {
            wp_send_json_error(array(
                'message' => __('Please enter at least 2 characters.', 'quickswap'),
            ));
        }

        // Get search args
        $args = array(
            'limit' => isset($_POST['limit']) ? intval($_POST['limit']) : 10,
            'offset' => isset($_POST['offset']) ? intval($_POST['offset']) : 0,
            'providers' => isset($_POST['providers']) ? array_map('sanitize_key', (array) $_POST['providers']) : array(),
            'enable_fuzzy' => isset($_POST['enable_fuzzy']) ? (bool) $_POST['enable_fuzzy'] : true,
            'fuzzy_threshold' => isset($_POST['fuzzy_threshold']) ? intval($_POST['fuzzy_threshold']) : 70,
            'user_id' => get_current_user_id(),
        );

        // Get settings for fuzzy search
        $settings = get_option('quickswap_settings', array());
        if (empty($settings['enable_fuzzy'])) {
            $args['enable_fuzzy'] = false;
        }

        // Perform search with error handling
        try {
            $results = QuickSwap_Search::search($query, $args);
        } catch (Exception $e) {
            error_log('QuickSwap search error for query "' . $query . '": ' . $e->getMessage());

            wp_send_json_error(array(
                'message' => __('Search failed. Please check the error log for details.', 'quickswap'),
                'debug' => WP_DEBUG ? $e->getMessage() : null,
            ));
        }

        // Log search for analytics
        $total_count = 0;
        foreach ($results as $provider_data) {
            $total_count += count($provider_data['items']);
        }
        QuickSwap_Search::log_search($query, $total_count);

        // Format response
        $response = array(
            'query' => $query,
            'results' => array_values($results),
            'count' => $total_count,
        );

        wp_send_json_success($response);
    }

    /**
     * Handle search suggestions
     */
    public static function handle_suggestions() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'quickswap_search_nonce')) {
            wp_send_json_error(array(
                'message' => __('Invalid security token.', 'quickswap'),
            ));
        }

        // Check capability
        if (!current_user_can('quickswap_use_search')) {
            wp_send_json_error(array(
                'message' => __('Permission denied.', 'quickswap'),
            ));
        }

        $query = sanitize_text_field($_POST['query'] ?? '');
        if (strlen($query) < 2) {
            wp_send_json_success(array('suggestions' => array()));
        }

        $suggestions = QuickSwap_Search::get_suggestions($query, 5);

        wp_send_json_success(array(
            'suggestions' => $suggestions,
        ));
    }

    /**
     * Handle frontend search request (for logged out users)
     */
    public static function handle_frontend_search() {
        $settings = get_option('quickswap_settings', array());

        // Check if frontend search is enabled
        if (empty($settings['enable_frontend'])) {
            wp_send_json_error(array(
                'message' => __('Frontend search is not enabled.', 'quickswap'),
            ));
        }

        // For logged out users, only show public content
        $query = sanitize_text_field($_POST['query'] ?? '');

        if (strlen($query) < 2) {
            wp_send_json_error(array(
                'message' => __('Please enter at least 2 characters.', 'quickswap'),
            ));
        }

        // Limited search for public users (only published posts and pages)
        $args = array(
            'limit' => isset($_POST['limit']) ? intval($_POST['limit']) : 10,
            'enable_fuzzy' => true,
            'user_id' => 0, // No user
        );

        // Only search posts provider for public users
        $args['providers'] = array('posts');

        $results = QuickSwap_Search::search($query, $args);

        wp_send_json_success(array(
            'query' => $query,
            'results' => array_values($results),
            'is_frontend' => true,
        ));
    }
}
