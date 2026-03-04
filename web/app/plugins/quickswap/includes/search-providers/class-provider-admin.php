<?php
/**
 * Admin Navigation Provider
 *
 * Searches WordPress admin pages and menu items
 *
 * @package QuickSwap
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class QuickSwap_Provider_Admin extends QuickSwap_Abstract_Provider {

    /**
     * Get provider ID
     */
    public function get_id() {
        return 'admin';
    }

    /**
     * Get provider label
     */
    public function get_label() {
        return __('Admin Pages', 'quickswap');
    }

    /**
     * Get provider icon
     */
    public function get_icon() {
        return '⚙️';
    }

    /**
     * Required capability
     */
    protected $capability = 'quickswap_admin_pages';

    /**
     * Priority (higher = shown first)
     */
    protected $priority = 20;

    /**
     * Admin pages cache
     */
    private static $admin_pages = null;

    /**
     * Initialize provider
     */
    public static function init() {
        add_action('quickswap_register_providers', function() {
            QuickSwap_Search::register_provider('admin', new self());
        });
    }

    /**
     * Search admin pages
     */
    public function search($query, $args = array()) {
        $limit = isset($args['limit']) ? intval($args['limit']) : 10;

        // Get admin pages
        $pages = $this->get_admin_pages();

        // Filter and score
        $items = array();
        $query_lower = strtolower($query);

        foreach ($pages as $page) {
            $score = $this->calculate_admin_score($page, $query_lower);

            if ($score > 0) {
                $items[] = $this->format_admin_item($page, $score);
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
        );
    }

    /**
     * Get all admin pages
     */
    private function get_admin_pages() {
        if (self::$admin_pages !== null) {
            return self::$admin_pages;
        }

        global $menu, $submenu, $pagenow;

        self::$admin_pages = array();

        // Get top-level menu items
        foreach ($menu as $menu_item) {
            if (empty($menu_item[0])) {
                continue;
            }

            // Check if user can access this menu
            if (!current_user_can($menu_item[1])) {
                continue;
            }

            // Parse menu item
            $title = $this->strip_menu_tags($menu_item[0]);
            $url = $menu_item[2];

            // Skip separators
            if ($title === '') {
                continue;
            }

            // Add top-level page
            self::$admin_pages[] = array(
                'title' => $title,
                'url' => $url,
                'type' => 'menu',
                'parent' => '',
                'capability' => $menu_item[1],
            );
        }

        // Get submenu items
        foreach ($submenu as $parent => $items) {
            foreach ($items as $submenu_item) {
                if (empty($submenu_item[0])) {
                    continue;
                }

                // Check if user can access
                if (!current_user_can($submenu_item[1])) {
                    continue;
                }

                $title = $this->strip_menu_tags($submenu_item[0]);
                $url = $submenu_item[2];

                // Skip separators
                if ($title === '') {
                    continue;
                }

                // Find parent title
                $parent_title = '';
                foreach ($menu as $menu_item) {
                    if ($menu_item[2] === $parent) {
                        $parent_title = $this->strip_menu_tags($menu_item[0]);
                        break;
                    }
                }

                self::$admin_pages[] = array(
                    'title' => $title,
                    'url' => $url,
                    'type' => 'submenu',
                    'parent' => $parent_title,
                    'capability' => $submenu_item[1],
                );
            }
        }

        // Add common admin pages
        self::$admin_pages = array_merge(self::$admin_pages, $this->get_common_admin_pages());

        // Add custom post type pages
        self::$admin_pages = array_merge(self::$admin_pages, $this->get_custom_post_type_pages());

        return self::$admin_pages;
    }

    /**
     * Strip HTML tags and count badges from menu titles
     */
    private function strip_menu_tags($title) {
        // Remove HTML tags
        $title = strip_tags($title);
        // Remove count badges like " (12)"
        $title = preg_replace('/\s*\(\d+\)/', '', $title);
        // Remove HTML entities
        $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');

        return trim($title);
    }

    /**
     * Get commonly accessed admin pages
     */
    private function get_common_admin_pages() {
        $pages = array();

        // Common admin pages
        $common_pages = array(
            'Dashboard' => 'index.php',
            'Home' => 'index.php',
            'Updates' => 'update-core.php',
            'All Posts' => 'edit.php',
            'Add New Post' => 'post-new.php',
            'All Pages' => 'edit.php?post_type=page',
            'Add New Page' => 'post-new.php?post_type=page',
            'Media Library' => 'upload.php',
            'Comments' => 'edit-comments.php',
            'Appearance' => 'themes.php',
            'Customize' => 'customize.php',
            'Widgets' => 'widgets.php',
            'Menus' => 'nav-menus.php',
            'Plugins' => 'plugins.php',
            'Add New Plugin' => 'plugin-install.php',
            'Users' => 'users.php',
            'Add New User' => 'user-new.php',
            'Your Profile' => 'profile.php',
            'Settings' => 'options-general.php',
            'General Settings' => 'options-general.php',
            'Writing Settings' => 'options-writing.php',
            'Reading Settings' => 'options-reading.php',
            'Discussion Settings' => 'options-discussion.php',
            'Media Settings' => 'options-media.php',
            'Permalink Settings' => 'options-permalink.php',
        );

        foreach ($common_pages as $title => $url) {
            $pages[] = array(
                'title' => $title,
                'url' => $url,
                'type' => 'common',
                'parent' => '',
            );
        }

        // Add WooCommerce pages if active
        if (class_exists('WooCommerce')) {
            $wc_pages = array(
                'WooCommerce Orders' => 'edit.php?post_type=shop_order',
                'WooCommerce Products' => 'edit.php?post_type=product',
                'WooCommerce Coupons' => 'edit.php?post_type=shop_coupon',
                'WooCommerce Customers' => 'admin.php?page=wc-admin&path=/customers',
                'WooCommerce Reports' => 'admin.php?page=wc-admin&path=/analytics/revenue',
                'WooCommerce Settings' => 'admin.php?page=wc-settings',
            );

            foreach ($wc_pages as $title => $url) {
                $pages[] = array(
                    'title' => $title,
                    'url' => $url,
                    'type' => 'woocommerce',
                    'parent' => 'WooCommerce',
                );
            }
        }

        return $pages;
    }

    /**
     * Get custom post type admin pages
     */
    private function get_custom_post_type_pages() {
        $pages = array();

        // Get all post types with UI
        $post_types = get_post_types(array('show_ui' => true), 'objects');

        // Exclude built-in post types and WooCommerce (handled elsewhere)
        $exclude_types = array('post', 'page', 'attachment', 'revision', 'nav_menu_item',
                              'product', 'shop_order', 'shop_coupon');

        foreach ($post_types as $post_type) {
            // Skip excluded types
            if (in_array($post_type->name, $exclude_types)) {
                continue;
            }

            // Check user capabilities
            if (!current_user_can($post_type->cap->edit_posts)) {
                continue;
            }

            // Get post type labels with fallbacks
            $singular_name = !empty($post_type->labels->singular_name)
                ? $post_type->labels->singular_name
                : $post_type->name;
            $plural_name = !empty($post_type->labels->name)
                ? $post_type->labels->name
                : $post_type->name;
            $menu_name = !empty($post_type->labels->menu_name)
                ? $post_type->labels->menu_name
                : $plural_name;

            // Generate "All [CPT]" page
            $all_title = sprintf(__('All %s', 'quickswap'), $menu_name);
            $pages[] = array(
                'title' => $all_title,
                'url' => 'edit.php?post_type=' . $post_type->name,
                'type' => 'cpt',
                'parent' => '',
                'capability' => $post_type->cap->edit_posts,
                'post_type' => $post_type->name,
                'searchable_fields' => array(
                    $post_type->name,
                    $singular_name,
                    $plural_name,
                    $menu_name,
                    $all_title,
                ),
            );

            // Generate "Add New [CPT]" page
            $add_new_title = sprintf(__('Add New %s', 'quickswap'), $singular_name);
            $pages[] = array(
                'title' => $add_new_title,
                'url' => 'post-new.php?post_type=' . $post_type->name,
                'type' => 'cpt',
                'parent' => '',
                'capability' => $post_type->cap->create_posts,
                'post_type' => $post_type->name,
                'searchable_fields' => array(
                    $post_type->name,
                    $singular_name,
                    $plural_name,
                    $menu_name,
                    $add_new_title,
                    __('Add New', 'quickswap'),
                ),
            );
        }

        return $pages;
    }

    /**
     * Calculate score for admin page
     */
    private function calculate_admin_score($page, $query_lower) {
        $title_lower = strtolower($page['title']);

        // Exact match
        if ($title_lower === $query_lower) {
            return 100;
        }

        // Starts with query
        if (strpos($title_lower, $query_lower) === 0) {
            return 80;
        }

        // Contains query
        if (strpos($title_lower, $query_lower) !== false) {
            return 60;
        }

        // Search in CPT searchable fields for custom post types
        if (isset($page['searchable_fields']) && is_array($page['searchable_fields'])) {
            foreach ($page['searchable_fields'] as $field) {
                $field_lower = strtolower($field);

                // Exact match in searchable field
                if ($field_lower === $query_lower) {
                    return 90; // High score for exact CPT match
                }

                // Starts with query
                if (strpos($field_lower, $query_lower) === 0) {
                    return 70;
                }

                // Contains query
                if (strpos($field_lower, $query_lower) !== false) {
                    return 50;
                }
            }
        }

        // Fuzzy match
        $settings = get_option('quickswap_settings', array());
        if (!empty($settings['enable_fuzzy'])) {
            $similarity = QuickSwap_Fuzzy::similarity($query_lower, $title_lower);
            if ($similarity >= 70) {
                return $similarity;
            }

            // Also check searchable fields for fuzzy match
            if (isset($page['searchable_fields']) && is_array($page['searchable_fields'])) {
                foreach ($page['searchable_fields'] as $field) {
                    $field_similarity = QuickSwap_Fuzzy::similarity($query_lower, strtolower($field));
                    if ($field_similarity >= 70 && $field_similarity > $similarity) {
                        return $field_similarity;
                    }
                }
            }
        }

        // Search in parent title too
        if (!empty($page['parent'])) {
            $parent_lower = strtolower($page['parent']);
            if (strpos($parent_lower, $query_lower) !== false) {
                return 40;
            }
        }

        return 0;
    }

    /**
     * Format admin page item
     */
    private function format_admin_item($page, $score) {
        // Build full URL
        if (strpos($page['url'], 'http') !== 0) {
            $url = admin_url($page['url']);
        } else {
            $url = $page['url'];
        }

        $subtitle = '';
        if (!empty($page['parent'])) {
            $subtitle = sprintf(__('Parent: %s', 'quickswap'), $page['parent']);
        } elseif ($page['type'] === 'menu') {
            $subtitle = __('Top level menu', 'quickswap');
        }

        return $this->format_item(array(
            'id' => md5($url),
            'title' => $page['title'],
            'subtitle' => $subtitle,
            'url' => $url,
            'score' => $score,
            'meta' => array(
                'type' => $page['type'],
                'capability' => $page['capability'] ?? '',
            ),
        ));
    }

    /**
     * Get item URL
     */
    public function get_item_url($item) {
        return $item['url'] ?? '';
    }

    /**
     * Execute action (open page)
     */
    public function execute_action($action_id, $item) {
        if ($action_id === 'open') {
            return array(
                'success' => true,
                'url' => $item['url'],
            );
        }

        return parent::execute_action($action_id, $item);
    }
}
