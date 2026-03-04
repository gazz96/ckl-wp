<?php
/**
 * Plugin Name: QuickSwap
 * Plugin URI: https://cklangkawi.com
 * Description: Mac Spotlight-inspired search plugin for WordPress admin. Press Cmd/Ctrl+K to instantly search posts, pages, users, plugins, themes, and admin pages.
 * Version: 1.0.0
 * Author: CK Langkawi
 * Author URI: https://cklangkawi.com
 * Text Domain: quickswap
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('QUICKSWAP_VERSION', '1.0.0');
define('QUICKSWAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('QUICKSWAP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QUICKSWAP_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('QUICKSWAP_MIN_PHP_VERSION', '7.4');
define('QUICKSWAP_MIN_WP_VERSION', '6.0');

/**
 * Main QuickSwap Class
 */
class QuickSwap {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->check_requirements();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Check plugin requirements
     */
    private function check_requirements() {
        // Check PHP version
        if (version_compare(PHP_VERSION, QUICKSWAP_MIN_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'php_version_notice'));
            return;
        }

        // Check WordPress version
        if (version_compare($GLOBALS['wp_version'], QUICKSWAP_MIN_WP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'wp_version_notice'));
            return;
        }
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core classes
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/class-quickswap-core.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/class-quickswap-search.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/class-quickswap-fuzzy.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/class-quickswap-cache.php';

        // Search providers
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/search-providers/class-abstract-provider.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/search-providers/class-provider-posts.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/search-providers/class-provider-admin.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/search-providers/class-provider-plugins.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/search-providers/class-provider-themes.php';

        // AJAX handlers
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/ajax-handlers/class-ajax-search.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'includes/ajax-handlers/class-ajax-actions.php';

        // Admin functions
        if (is_admin()) {
            require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-settings.php';
            require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-branding.php';
            require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-branding-settings.php';
            // Dashboard feature removed - keeping files for potential future use
            // require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-dashboard.php';
            // require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-dashboard-settings.php';
            require_once QUICKSWAP_PLUGIN_DIR . 'includes/admin/class-admin-ui-components.php';
        }

        // Block Manager module
        if (file_exists(QUICKSWAP_PLUGIN_DIR . 'block-manager/block-manager.php')) {
            require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/block-manager.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        add_action('init', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize core classes
        QuickSwap_Core::init();
        QuickSwap_Search::init();
        QuickSwap_Fuzzy::init();
        QuickSwap_Cache::init();

        // Initialize search providers
        QuickSwap_Provider_Posts::init();
        QuickSwap_Provider_Admin::init();
        QuickSwap_Provider_Plugins::init();
        QuickSwap_Provider_Themes::init();

        // Initialize AJAX handlers
        QuickSwap_AJAX_Search::init();
        QuickSwap_AJAX_Actions::init();

        // Initialize admin
        if (is_admin()) {
            QuickSwap_Admin_Settings::init();
            QuickSwap_Admin_Branding_Settings::init();
            // Dashboard feature removed - keeping files for potential future use
            // QuickSwap_Admin_Dashboard::init();
            // QuickSwap_Admin_Dashboard_Settings::init();

            // Branding init must run on admin_init to check settings at the right time
            add_action('admin_init', array('QuickSwap_Admin_Branding', 'init'));
        }

        // Initialize Block Manager module
        if (class_exists('QuickSwap_Block_Manager')) {
            QuickSwap_Block_Manager::init();
        }
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('quickswap', false, dirname(QUICKSWAP_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'keyboard_shortcut' => 'cmd+k',
            'enable_frontend' => true,
            'max_results' => 10,
            'enable_fuzzy' => true,
            'fuzzy_threshold' => 70,
        );

        add_option('quickswap_settings', $default_options);

        // Set default branding options
        $branding_defaults = array(
            'enable_branding' => false,
            'custom_colors_enabled' => false,
            'admin_primary_color' => '#2271b1',
            'admin_secondary_color' => '#135e96',
            'admin_bg_color' => '#f0f0f1',
            'login_bg_color' => '#ffffff',
            'login_logo_width' => 80,
            'login_logo_height' => 80,
        );

        add_option('quickswap_branding_settings', $branding_defaults);

        // Dashboard feature removed - default dashboard options no longer set
        // The quickswap_dashboard_settings option may still exist in the database from previous installs
        // To clean up, use: wp option delete quickswap_dashboard_settings

        // Create database tables
        $this->create_tables();

        // Set capabilities for administrator
        $this->set_capabilities();
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Search index table
        $table_name = $wpdb->prefix . 'quickswap_search_index';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            content_type VARCHAR(50) NOT NULL,
            content_id BIGINT(20) UNSIGNED NOT NULL,
            title TEXT NOT NULL,
            content LONGTEXT,
            meta_data LONGTEXT,
            author_id BIGINT(20) UNSIGNED,
            date_created DATETIME NOT NULL,
            date_modified DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY content_unique (content_type, content_id),
            KEY content_type (content_type)
        ) $charset_collate;";

        // Search log table
        $log_table = $wpdb->prefix . 'quickswap_search_log';
        $sql_log = "CREATE TABLE IF NOT EXISTS $log_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED,
            search_query VARCHAR(255) NOT NULL,
            results_count INT(11),
            selected_result_id BIGINT(20) UNSIGNED,
            search_time DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY search_query (search_query)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbDelta($sql_log);
    }

    /**
     * Set default capabilities
     */
    private function set_capabilities() {
        $role = get_role('administrator');

        if ($role) {
            $role->add_cap('quickswap_use_search');
            $role->add_cap('quickswap_admin_pages');
            $role->add_cap('quickswap_manage_plugins');
            $role->add_cap('quickswap_manage_themes');
            $role->add_cap('quickswap_manage_users');
            $role->add_cap('quickswap_woocommerce');
        }

        // Editor capabilities
        $editor_role = get_role('editor');
        if ($editor_role) {
            $editor_role->add_cap('quickswap_use_search');
        }

        // Author capabilities
        $author_role = get_role('author');
        if ($author_role) {
            $author_role->add_cap('quickswap_use_search');
        }
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clear scheduled hooks
        wp_clear_scheduled_hook('quickswap_rebuild_index');
    }

    /**
     * PHP version notice
     */
    public function php_version_notice() {
        ?>
        <div class="error">
            <p>
                <?php
                printf(
                    /* translators: 1: Required PHP version, 2: Current PHP version */
                    esc_html__('QuickSwap requires PHP version %1$s or higher. You are running version %2$s.', 'quickswap'),
                    esc_html(QUICKSWAP_MIN_PHP_VERSION),
                    esc_html(PHP_VERSION)
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * WordPress version notice
     */
    public function wp_version_notice() {
        ?>
        <div class="error">
            <p>
                <?php
                printf(
                    /* translators: 1: Required WP version, 2: Current WP version */
                    esc_html__('QuickSwap requires WordPress version %1$s or higher. You are running version %2$s.', 'quickswap'),
                    esc_html(QUICKSWAP_MIN_WP_VERSION),
                    esc_html($GLOBALS['wp_version'])
                );
                ?>
            </p>
        </div>
        <?php
    }
}

/**
 * Initialize the plugin
 */
function quickswap() {
    return QuickSwap::get_instance();
}

// Start the plugin
quickswap();
