<?php
/**
 * Plugins Search Provider
 *
 * Searches and manages WordPress plugins
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Provider_Plugins extends QuickSwap_Abstract_Provider {

    /**
     * Get provider ID
     */
    public function get_id() {
        return 'plugins';
    }

    /**
     * Get provider label
     */
    public function get_label() {
        return __('Plugins', 'quickswap');
    }

    /**
     * Get provider icon
     */
    public function get_icon() {
        return '🔌';
    }

    /**
     * Required capability
     */
    protected $capability = 'quickswap_manage_plugins';

    /**
     * Plugins cache
     */
    private static $plugins_cache = null;

    /**
     * Initialize provider
     */
    public static function init() {
        add_action('quickswap_register_providers', function() {
            QuickSwap_Search::register_provider('plugins', new self());
        });

        // Clear cache when plugins change
        add_action('activated_plugin', array(__CLASS__, 'clear_cache'));
        add_action('deactivated_plugin', array(__CLASS__, 'clear_cache'));
    }

    /**
     * Clear plugins cache
     */
    public static function clear_cache() {
        self::$plugins_cache = null;
    }

    /**
     * Search plugins
     */
    public function search($query, $args = array()) {
        $limit = isset($args['limit']) ? intval($args['limit']) : 10;

        // Get all plugins
        $plugins = $this->get_all_plugins();

        // Filter and score
        $items = array();
        $query_lower = strtolower($query);

        foreach ($plugins as $plugin_file => $plugin_data) {
            $score = $this->calculate_plugin_score($plugin_data, $query_lower);

            if ($score > 0) {
                $items[] = $this->format_plugin_item($plugin_file, $plugin_data, $score);
            }
        }

        // Sort by score
        usort($items, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Limit results
        $items = array_slice($items, 0, $limit);

        return array(
            'label' => $this->get_label(),
            'items' => $items,
            'more_url' => admin_url('plugins.php'),
        );
    }

    /**
     * Get all plugins
     */
    private function get_all_plugins() {
        if (self::$plugins_cache !== null) {
            return self::$plugins_cache;
        }

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        self::$plugins_cache = get_plugins();

        // Add active status
        $active_plugins = get_option('active_plugins', array());
        $active_sitewide_plugins = is_multisite() ? get_site_option('active_sitewide_plugins', array()) : array();

        foreach (self::$plugins_cache as $plugin_file => &$plugin_data) {
            $is_active = in_array($plugin_file, $active_plugins) || isset($active_sitewide_plugins[$plugin_file]);
            $plugin_data['is_active'] = $is_active;
            $plugin_data['plugin_file'] = $plugin_file;
        }

        return self::$plugins_cache;
    }

    /**
     * Calculate score for plugin
     */
    private function calculate_plugin_score($plugin_data, $query_lower) {
        $name_lower = strtolower($plugin_data['Name']);
        $desc_lower = strtolower($plugin_data['Description'] ?? '');

        // Exact name match
        if ($name_lower === $query_lower) {
            return 100;
        }

        // Starts with name
        if (strpos($name_lower, $query_lower) === 0) {
            return 80;
        }

        // Contains in name
        if (strpos($name_lower, $query_lower) !== false) {
            return 60;
        }

        // Contains in description
        if (strpos($desc_lower, $query_lower) !== false) {
            return 40;
        }

        // Fuzzy match
        $settings = get_option('quickswap_settings', array());
        if (!empty($settings['enable_fuzzy'])) {
            $similarity = QuickSwap_Fuzzy::similarity($query_lower, $name_lower);
            if ($similarity >= 70) {
                return $similarity;
            }
        }

        return 0;
    }

    /**
     * Format plugin item
     */
    private function format_plugin_item($plugin_file, $plugin_data, $score) {
        $is_active = $plugin_data['is_active'];

        $status_badge = '';
        if ($is_active) {
            $status_badge = '<span class="quickswap-badge quickswap-badge-active">' . __('Active', 'quickswap') . '</span>';
        } else {
            $status_badge = '<span class="quickswap-badge quickswap-badge-inactive">' . __('Inactive', 'quickswap') . '</span>';
        }

        $subtitle = sprintf(
            '%s %s',
            $status_badge,
            $this->truncate_text($plugin_data['Description'] ?? '', 80)
        );

        return $this->format_item(array(
            'id' => $plugin_file,
            'title' => $plugin_data['Name'],
            'subtitle' => $subtitle,
            'url' => $is_active ? admin_url('plugins.php') : admin_url('plugins.php'),
            'score' => $score,
            'meta' => array(
                'version' => $plugin_data['Version'] ?? '',
                'author' => $plugin_data['Author'] ?? '',
                'is_active' => $is_active,
                'plugin_file' => $plugin_file,
            ),
            'actions' => $this->get_plugin_actions($plugin_file, $is_active),
        ));
    }

    /**
     * Get plugin actions
     */
    private function get_plugin_actions($plugin_file, $is_active) {
        $actions = array();

        // Settings action (if available)
        $settings_url = $this->get_plugin_settings_url($plugin_file);
        if ($settings_url && $is_active) {
            $actions[] = array(
                'id' => 'settings',
                'label' => __('Settings', 'quickswap'),
                'icon' => '⚙️',
                'url' => $settings_url,
            );
        }

        // Activate/Deactivate action
        if ($is_active) {
            if (current_user_can('deactivate_plugin', $plugin_file)) {
                $actions[] = array(
                    'id' => 'deactivate',
                    'label' => __('Deactivate', 'quickswap'),
                    'icon' => '⏸️',
                    'action' => 'deactivate',
                );
            }
        } else {
            if (current_user_can('activate_plugin', $plugin_file)) {
                $actions[] = array(
                    'id' => 'activate',
                    'label' => __('Activate', 'quickswap'),
                    'icon' => '⚡',
                    'action' => 'activate',
                );
            }
        }

        return $actions;
    }

    /**
     * Get plugin settings URL
     */
    private function get_plugin_settings_url($plugin_file) {
        // Get settings link from plugin
        $settings_links = array_filter(get_plugin_action_links($plugin_file), function($link) {
            return strpos($link, 'settings') !== false || strpos($link, 'options-general.php') !== false;
        });

        if (!empty($settings_links)) {
            preg_match('/href="([^"]+)"/', reset($settings_links), $matches);
            return $matches[1] ?? '';
        }

        return '';
    }

    /**
     * Truncate text
     */
    private function truncate_text($text, $length) {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }

    /**
     * Get item URL
     */
    public function get_item_url($item) {
        return admin_url('plugins.php?s=' . urlencode($item['title']));
    }

    /**
     * Execute plugin action
     */
    public function execute_action($action_id, $item) {
        $plugin_file = $item['id'];

        if ($action_id === 'activate') {
            if (!current_user_can('activate_plugin', $plugin_file)) {
                return new WP_Error('no_permission', __('You do not have permission to activate this plugin.', 'quickswap'));
            }

            $result = activate_plugin($plugin_file);

            if (is_wp_error($result)) {
                return $result;
            }

            self::clear_cache();

            return array(
                'success' => true,
                'message' => __('Plugin activated.', 'quickswap'),
            );
        }

        if ($action_id === 'deactivate') {
            if (!current_user_can('deactivate_plugin', $plugin_file)) {
                return new WP_Error('no_permission', __('You do not have permission to deactivate this plugin.', 'quickswap'));
            }

            deactivate_plugins($plugin_file);

            self::clear_cache();

            return array(
                'success' => true,
                'message' => __('Plugin deactivated.', 'quickswap'),
            );
        }

        return parent::execute_action($action_id, $item);
    }

    /**
     * Get items for caching
     */
    public static function get_items_for_cache() {
        $cache = array();
        $plugins = (new self())->get_all_plugins();

        foreach ($plugins as $plugin_file => $plugin_data) {
            $cache[] = array(
                'id' => $plugin_file,
                'name' => $plugin_data['Name'],
                'is_active' => $plugin_data['is_active'],
            );
        }

        return $cache;
    }
}
