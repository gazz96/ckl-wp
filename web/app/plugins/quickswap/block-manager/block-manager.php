<?php
/**
 * Block Manager Module for QuickSwap
 *
 * @package QuickSwap\Block_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Block Manager Class
 */
class QuickSwap_Block_Manager {

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
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/includes/class-block-manager-core.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/includes/class-block-manager-rest.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/includes/class-block-manager-collections.php';
        require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/includes/class-block-manager-settings.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        add_action('admin_enqueue_scripts', array('QuickSwap_Block_Manager_Settings', 'enqueue_admin_assets'));
        add_action('admin_menu', array('QuickSwap_Block_Manager_Settings', 'add_admin_menu'));
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Get assets file for dependencies
        $build_dir = QUICKSWAP_PLUGIN_DIR . 'block-manager/build';
        $asset_file = $build_dir . '/index.asset.php';

        if (file_exists($asset_file)) {
            $asset_data = require $asset_file;

            // Register block editor assets
            wp_register_script(
                'quickswap-block-manager',
                QUICKSWAP_PLUGIN_URL . 'block-manager/build/index.js',
                $asset_data['dependencies'],
                $asset_data['version'],
                true
            );

            wp_register_style(
                'quickswap-block-manager',
                QUICKSWAP_PLUGIN_URL . 'block-manager/build/style-index.css',
                array(),
                $asset_data['version']
            );

            // Localize script
            wp_localize_script('quickswap-block-manager', 'quickswapBlockManager', array(
                'apiUrl' => rest_url('quickswap-block-manager/v1'),
                'nonce' => wp_create_nonce('wp_rest'),
            ));
        }

        // Register admin assets
        $admin_build_dir = QUICKSWAP_PLUGIN_DIR . 'block-manager/build-admin';
        $admin_asset_file = $admin_build_dir . '/index.asset.php';

        if (file_exists($admin_asset_file)) {
            $admin_asset_data = require $admin_asset_file;

            wp_register_script(
                'quickswap-block-manager-admin',
                QUICKSWAP_PLUGIN_URL . 'block-manager/build-admin/index.js',
                $admin_asset_data['dependencies'],
                $admin_asset_data['version'],
                true
            );

            wp_register_style(
                'quickswap-block-manager-admin',
                QUICKSWAP_PLUGIN_URL . 'block-manager/build-admin/index.css',
                array(),
                $admin_asset_data['version']
            );

            // Localize admin script
            wp_localize_script('quickswap-block-manager-admin', 'quickswapBlockManagerAdmin', array(
                'apiUrl' => rest_url('quickswap-block-manager/v1'),
                'nonce' => wp_create_nonce('wp_rest'),
            ));
        }
    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        // Only load in block editor
        if (!function_exists('get_current_screen')) {
            return;
        }

        $screen = get_current_screen();
        if (!$screen || $screen->base !== 'post') {
            return;
        }

        wp_enqueue_script('quickswap-block-manager');
        wp_enqueue_style('quickswap-block-manager');
    }

    /**
     * Initialize the module
     */
    public static function init() {
        $self = self::get_instance();

        // Initialize components
        QuickSwap_Block_Manager_Core::init();
        QuickSwap_Block_Manager_REST::init();
        QuickSwap_Block_Manager_Collections::init();
        QuickSwap_Block_Manager_Settings::init();
    }
}

/**
 * Initialize Block Manager
 */
function quickswap_block_manager() {
    return QuickSwap_Block_Manager::get_instance();
}

// Initialize on plugins_loaded
add_action('plugins_loaded', 'quickswap_block_manager');
