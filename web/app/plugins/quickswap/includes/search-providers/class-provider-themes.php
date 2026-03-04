<?php
/**
 * Themes Search Provider
 *
 * Searches and manages WordPress themes
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Provider_Themes extends QuickSwap_Abstract_Provider {

    /**
     * Get provider ID
     */
    public function get_id() {
        return 'themes';
    }

    /**
     * Get provider label
     */
    public function get_label() {
        return __('Themes', 'quickswap');
    }

    /**
     * Get provider icon
     */
    public function get_icon() {
        return '🎨';
    }

    /**
     * Required capability
     */
    protected $capability = 'quickswap_manage_themes';

    /**
     * Themes cache
     */
    private static $themes_cache = null;

    /**
     * Initialize provider
     */
    public static function init() {
        add_action('quickswap_register_providers', function() {
            QuickSwap_Search::register_provider('themes', new self());
        });

        // Clear cache when themes change
        add_action('switch_theme', array(__CLASS__, 'clear_cache'));
    }

    /**
     * Clear themes cache
     */
    public static function clear_cache() {
        self::$themes_cache = null;
    }

    /**
     * Search themes
     */
    public function search($query, $args = array()) {
        $limit = isset($args['limit']) ? intval($args['limit']) : 10;

        // Get all themes
        $themes = $this->get_all_themes();

        // Filter and score
        $items = array();
        $query_lower = strtolower($query);

        foreach ($themes as $theme_slug => $theme) {
            $score = $this->calculate_theme_score($theme, $query_lower);

            if ($score > 0) {
                $items[] = $this->format_theme_item($theme_slug, $theme, $score);
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
            'more_url' => admin_url('themes.php'),
        );
    }

    /**
     * Get all themes
     */
    private function get_all_themes() {
        if (self::$themes_cache !== null) {
            return self::$themes_cache;
        }

        if (!function_exists('wp_get_themes')) {
            require_once ABSPATH . 'wp-admin/includes/theme.php';
        }

        self::$themes_cache = wp_get_themes();

        return self::$themes_cache;
    }

    /**
     * Calculate score for theme
     */
    private function calculate_theme_score($theme, $query_lower) {
        $name_lower = strtolower($theme->get('Name'));
        $desc_lower = strtolower($theme->get('Description'));
        $author_lower = strtolower($theme->get('Author'));

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

        // Contains in author
        if (strpos($author_lower, $query_lower) !== false) {
            return 45;
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
     * Format theme item
     */
    private function format_theme_item($theme_slug, $theme, $score) {
        $current_theme = get_stylesheet();
        $is_current = ($theme_slug === $current_theme);

        $status_badge = '';
        if ($is_current) {
            $status_badge = '<span class="quickswap-badge quickswap-badge-active">' . __('Active', 'quickswap') . '</span>';
        } elseif ($theme->is_allowed('network') && is_multisite()) {
            $status_badge = '<span class="quickswap-badge quickswap-badge-network">' . __('Network Active', 'quickswap') . '</span>';
        }

        $subtitle = sprintf(
            '%s %s • %s',
            $status_badge,
            $theme->get('Author'),
            $this->truncate_text($theme->get('Description'), 60)
        );

        return $this->format_item(array(
            'id' => $theme_slug,
            'title' => $theme->get('Name'),
            'subtitle' => $subtitle,
            'url' => admin_url('themes.php'),
            'score' => $score,
            'meta' => array(
                'version' => $theme->get('Version'),
                'author' => $theme->get('Author'),
                'is_current' => $is_current,
                'screenshot' => $theme->get_screenshot(),
            ),
            'actions' => $this->get_theme_actions($theme_slug, $is_current),
        ));
    }

    /**
     * Get theme actions
     */
    private function get_theme_actions($theme_slug, $is_current) {
        $actions = array();

        if ($is_current) {
            // Customize action for current theme
            if (current_user_can('edit_theme_options')) {
                $actions[] = array(
                    'id' => 'customize',
                    'label' => __('Customize', 'quickswap'),
                    'icon' => '🎨',
                    'url' => wp_customize_url($theme_slug),
                );
            }

            // Theme editor
            if (current_user_can('edit_themes')) {
                $actions[] = array(
                    'id' => 'editor',
                    'label' => __('Theme Editor', 'quickswap'),
                    'icon' => '📝',
                    'url' => admin_url('theme-editor.php?theme=' . $theme_slug),
                );
            }
        } else {
            // Switch theme action
            if (current_user_can('switch_themes')) {
                $actions[] = array(
                    'id' => 'activate',
                    'label' => __('Activate', 'quickswap'),
                    'icon' => '⚡',
                    'action' => 'activate',
                    'confirm' => __('Are you sure you want to switch to this theme?', 'quickswap'),
                );
            }

            // Live preview
            if (current_user_can('edit_theme_options')) {
                $actions[] = array(
                    'id' => 'preview',
                    'label' => __('Live Preview', 'quickswap'),
                    'icon' => '👁️',
                    'url' => admin_url('customize.php?theme=' . $theme_slug),
                );
            }
        }

        // Theme info
        $actions[] = array(
            'id' => 'details',
            'label' => __('Details', 'quickswap'),
            'icon' => 'ℹ️',
            'url' => admin_url('themes.php?theme=' . $theme_slug),
        );

        return $actions;
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
        if ($item['meta']['is_current']) {
            return wp_customize_url($item['id']);
        }
        return admin_url('themes.php?theme=' . $item['id']);
    }

    /**
     * Execute theme action
     */
    public function execute_action($action_id, $item) {
        $theme_slug = $item['id'];

        if ($action_id === 'activate') {
            if (!current_user_can('switch_themes')) {
                return new WP_Error('no_permission', __('You do not have permission to switch themes.', 'quickswap'));
            }

            $result = switch_theme($theme_slug);

            if (is_wp_error($result)) {
                return $result;
            }

            self::clear_cache();

            return array(
                'success' => true,
                'message' => __('Theme activated. Redirecting...', 'quickswap'),
                'redirect' => admin_url('themes.php'),
            );
        }

        return parent::execute_action($action_id, $item);
    }

    /**
     * Get items for caching
     */
    public static function get_items_for_cache() {
        $cache = array();
        $themes = (new self())->get_all_themes();

        foreach ($themes as $theme_slug => $theme) {
            $cache[] = array(
                'id' => $theme_slug,
                'name' => $theme->get('Name'),
            );
        }

        return $cache;
    }
}
