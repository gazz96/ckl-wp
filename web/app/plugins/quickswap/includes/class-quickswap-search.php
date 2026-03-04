<?php
/**
 * QuickSwap Search Class
 *
 * Orchestrates search across all providers
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Search {

    /**
     * Registered search providers
     */
    private static $providers = array();

    /**
     * Initialize search functionality
     */
    public static function init() {
        // Providers register themselves via add_action
        add_action('quickswap_register_providers', array(__CLASS__, 'register_default_providers'), 10);
    }

    /**
     * Register default providers
     */
    public static function register_default_providers() {
        // Priority providers (enabled by default)
        self::register_provider('posts', new QuickSwap_Provider_Posts());
        self::register_provider('admin', new QuickSwap_Provider_Admin());
        self::register_provider('plugins', new QuickSwap_Provider_Plugins());
        self::register_provider('themes', new QuickSwap_Provider_Themes());
    }

    /**
     * Register a search provider
     */
    public static function register_provider($id, $provider) {
        if ($provider instanceof QuickSwap_Abstract_Provider) {
            self::$providers[$id] = $provider;
        }
    }

    /**
     * Get all registered providers
     */
    public static function get_providers() {
        if (empty(self::$providers)) {
            do_action('quickswap_register_providers');
        }

        return self::$providers;
    }

    /**
     * Get a specific provider
     */
    public static function get_provider($id) {
        $providers = self::get_providers();
        return $providers[$id] ?? null;
    }

    /**
     * Perform search across all providers
     *
     * @param string $query Search query
     * @param array $args Search arguments
     * @return array Search results grouped by provider
     */
    public static function search($query, $args = array()) {
        $defaults = array(
            'limit' => 10,
            'offset' => 0,
            'providers' => array(), // Empty means all providers
            'enable_fuzzy' => true,
            'fuzzy_threshold' => 70,
            'user_id' => get_current_user_id(),
        );

        $args = wp_parse_args($args, $defaults);

        // Check cache first
        $cache_key = self::get_cache_key($query, $args);
        $cached = QuickSwap_Cache::get($cache_key);

        if ($cached !== false) {
            return $cached;
        }

        // Get providers to search
        $providers_to_search = self::get_providers();

        if (!empty($args['providers'])) {
            $providers_to_search = array_intersect_key($providers_to_search, array_flip($args['providers']));
        }

        // Filter providers by user capabilities
        $providers_to_search = array_filter($providers_to_search, function($provider) use ($args) {
            return $provider->can_access($args['user_id']);
        });

        $results = array();
        $total_count = 0;

        foreach ($providers_to_search as $provider_id => $provider) {
            $provider_results = $provider->search($query, $args);

            if (!empty($provider_results['items'])) {
                $results[$provider_id] = $provider_results;
                $total_count += count($provider_results['items']);
            }
        }

        // Sort results by relevance
        $results = self::sort_results($results, $query);

        // Apply limit
        if ($args['limit'] > 0) {
            $results = self::limit_results($results, $args['limit'], $args['offset']);
        }

        // Cache results
        QuickSwap_Cache::set($cache_key, $results, 300); // Cache for 5 minutes

        return $results;
    }

    /**
     * Sort results by relevance score
     */
    private static function sort_results($results, $query) {
        foreach ($results as $provider_id => &$provider_data) {
            if (!empty($provider_data['items'])) {
                usort($provider_data['items'], function($a, $b) use ($query) {
                    $score_a = $a['score'] ?? 0;
                    $score_b = $b['score'] ?? 0;

                    // Sort by score descending
                    if ($score_a === $score_b) {
                        return 0;
                    }

                    return ($score_a > $score_b) ? -1 : 1;
                });
            }
        }

        return $results;
    }

    /**
     * Limit results to specified count
     */
    private static function limit_results($results, $limit, $offset = 0) {
        $flat_results = array();
        $provider_order = array('posts', 'pages', 'admin', 'plugins', 'themes', 'users', 'media', 'comments', 'woocommerce', 'updates');

        // Flatten results in provider priority order
        foreach ($provider_order as $provider_id) {
            if (isset($results[$provider_id])) {
                foreach ($results[$provider_id]['items'] as $item) {
                    $item['provider'] = $provider_id;
                    $flat_results[] = $item;
                }
            }
        }

        // Add any remaining providers not in priority order
        foreach ($results as $provider_id => $provider_data) {
            if (!in_array($provider_id, $provider_order)) {
                foreach ($provider_data['items'] as $item) {
                    $item['provider'] = $provider_id;
                    $flat_results[] = $item;
                }
            }
        }

        // Apply offset and limit
        $flat_results = array_slice($flat_results, $offset, $limit);

        // Re-group by provider
        $grouped_results = array();
        foreach ($flat_results as $item) {
            $provider_id = $item['provider'];
            unset($item['provider']);

            if (!isset($grouped_results[$provider_id])) {
                $grouped_results[$provider_id] = array(
                    'label' => $results[$provider_id]['label'] ?? ucfirst($provider_id),
                    'items' => array(),
                );
            }

            $grouped_results[$provider_id]['items'][] = $item;
        }

        return $grouped_results;
    }

    /**
     * Generate cache key for search
     */
    private static function get_cache_key($query, $args) {
        $key_parts = array(
            'quickswap',
            md5($query),
            $args['limit'],
            $args['offset'],
            implode(',', $args['providers']),
            $args['enable_fuzzy'] ? '1' : '0',
            $args['fuzzy_threshold'],
            $args['user_id'],
        );

        return implode('_', $key_parts);
    }

    /**
     * Parse search query for operators
     *
     * Supports: type:post, user:john, status:draft, after:2025-01-01
     */
    public static function parse_query($query) {
        $parsed = array(
            'query' => $query,
            'operators' => array(),
        );

        // Match operators like type:post, user:john, etc.
        preg_match_all('/(\w+):([^\s]+)/', $query, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $parsed['operators'][$match[1]] = $match[2];
            // Remove operator from main query
            $parsed['query'] = str_replace($match[0], '', $parsed['query']);
        }

        // Clean up query
        $parsed['query'] = trim($parsed['query']);

        return $parsed;
    }

    /**
     * Log search for analytics
     */
    public static function log_search($query, $results_count, $selected_id = null) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'quickswap_search_log';

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'search_query' => substr($query, 0, 255),
                'results_count' => $results_count,
                'selected_result_id' => $selected_id,
                'search_time' => current_time('mysql'),
            ),
            array('%d', '%s', '%d', '%d', '%s')
        );
    }

    /**
     * Get search suggestions based on query
     */
    public static function get_suggestions($query, $limit = 5) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'quickswap_search_log';

        // Get recent searches by current user
        $recent_searches = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT search_query
            FROM $table_name
            WHERE user_id = %d
            AND search_query LIKE %s
            ORDER BY search_time DESC
            LIMIT %d",
            get_current_user_id(),
            '%' . $wpdb->esc_like($query) . '%',
            $limit
        ));

        return $recent_searches;
    }
}
