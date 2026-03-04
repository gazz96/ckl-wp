<?php
/**
 * QuickSwap Cache Class
 *
 * Handles caching for search results
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Cache {

    /**
     * Cache group name
     */
    const CACHE_GROUP = 'quickswap';

    /**
     * Default cache expiration (seconds)
     */
    const DEFAULT_EXPIRATION = 300; // 5 minutes

    /**
     * Initialize cache
     */
    public static function init() {
        // Clear cache on post save
        add_action('save_post', array(__CLASS__, 'clear_post_cache'));
        add_action('delete_post', array(__CLASS__, 'clear_post_cache'));

        // Clear cache on user changes
        add_action('user_register', array(__CLASS__, 'clear_user_cache'));
        add_action('profile_update', array(__CLASS__, 'clear_user_cache'));
        add_action('delete_user', array(__CLASS__, 'clear_user_cache'));

        // Clear cache on plugin/theme changes
        add_action('activated_plugin', array(__CLASS__, 'clear_plugin_cache'));
        add_action('deactivated_plugin', array(__CLASS__, 'clear_plugin_cache'));
        add_action('switch_theme', array(__CLASS__, 'clear_theme_cache'));
    }

    /**
     * Get cached value
     *
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     * @return mixed Cached value or default
     */
    public static function get($key, $default = false) {
        return wp_cache_get($key, self::CACHE_GROUP);
    }

    /**
     * Set cached value
     *
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $expiration Expiration time in seconds
     * @return bool Success
     */
    public static function set($key, $value, $expiration = null) {
        if ($expiration === null) {
            $expiration = self::DEFAULT_EXPIRATION;
        }

        return wp_cache_set($key, $value, self::CACHE_GROUP, $expiration);
    }

    /**
     * Delete cached value
     *
     * @param string $key Cache key
     * @return bool Success
     */
    public static function delete($key) {
        return wp_cache_delete($key, self::CACHE_GROUP);
    }

    /**
     * Clear all QuickSwap cache
     *
     * @return bool Success
     */
    public static function clear_all() {
        wp_cache_flush_group(self::CACHE_GROUP);
        return true;
    }

    /**
     * Clear post-related cache
     */
    public static function clear_post_cache($post_id) {
        self::delete("post_{$post_id}");
        self::delete('posts_all');
        do_action('quickswap_cache_cleared', 'posts', $post_id);
    }

    /**
     * Clear user-related cache
     */
    public static function clear_user_cache($user_id) {
        self::delete("user_{$user_id}");
        self::delete('users_all');
        do_action('quickswap_cache_cleared', 'users', $user_id);
    }

    /**
     * Clear plugin-related cache
     */
    public static function clear_plugin_cache($plugin) {
        self::delete('plugins_all');
        self::delete('plugins_active');
        self::delete('plugins_inactive');
        do_action('quickswap_cache_cleared', 'plugins', $plugin);
    }

    /**
     * Clear theme-related cache
     */
    public static function clear_theme_cache($theme_name) {
        self::delete('themes_all');
        self::delete('theme_current');
        do_action('quickswap_cache_cleared', 'themes', $theme_name);
    }

    /**
     * Get or set cached value with callback
     *
     * @param string $key Cache key
     * @param callable $callback Callback to generate value if not cached
     * @param int $expiration Expiration time in seconds
     * @return mixed Cached or generated value
     */
    public static function remember($key, $callback, $expiration = null) {
        $value = self::get($key);

        if ($value !== false) {
            return $value;
        }

        $value = call_user_func($callback);
        self::set($key, $value, $expiration);

        return $value;
    }

    /**
     * Generate cache key from arguments
     *
     * @param string $prefix Key prefix
     * @param array $args Arguments to include in key
     * @return string Generated cache key
     */
    public static function generate_key($prefix, $args = array()) {
        $key_parts = array(self::CACHE_GROUP, $prefix);

        foreach ($args as $arg_key => $arg_value) {
            if (is_array($arg_value) || is_object($arg_value)) {
                $arg_value = md5(serialize($arg_value));
            }
            $key_parts[] = $arg_key . '_' . $arg_value;
        }

        return implode('_', $key_parts);
    }

    /**
     * Get cache statistics
     *
     * @return array Cache stats
     */
    public static function get_stats() {
        global $wp_object_cache;

        $stats = array(
            'enabled' => wp_using_ext_object_cache(),
            'group' => self::CACHE_GROUP,
            'expiration' => self::DEFAULT_EXPIRATION,
        );

        if (method_exists($wp_object_cache, 'get_stats')) {
            $stats['object_cache'] = $wp_object_cache->get_stats();
        }

        return $stats;
    }

    /**
     * Warm up cache with common searches
     */
    public static function warm_up() {
        // Cache common items
        self::set('posts_all', QuickSwap_Provider_Posts::get_items_for_cache(), self::DEFAULT_EXPIRATION);
        self::set('plugins_all', QuickSwap_Provider_Plugins::get_items_for_cache(), self::DEFAULT_EXPIRATION);
        self::set('themes_all', QuickSwap_Provider_Themes::get_items_for_cache(), self::DEFAULT_EXPIRATION);
    }
}
