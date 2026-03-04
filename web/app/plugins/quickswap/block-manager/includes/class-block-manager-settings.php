<?php
/**
 * Block Manager Settings Class
 *
 * @package QuickSwap\Block_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings page class
 */
class QuickSwap_Block_Manager_Settings {

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
        // Settings initialization
    }

    /**
     * Initialize the class
     */
    public static function init() {
        $self = self::get_instance();
    }

    /**
     * Add admin menu item
     */
    public static function add_admin_menu() {
        add_submenu_page(
            'quickswap-settings',
            __('Block Collections', 'quickswap'),
            __('Block Collections', 'quickswap'),
            'edit_posts',
            'quickswap-block-collections',
            array(__CLASS__, 'render_admin_page')
        );
    }

    /**
     * Render admin page
     */
    public static function render_admin_page() {
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have permission to access this page.', 'quickswap'));
        }

        // Load template
        require_once QUICKSWAP_PLUGIN_DIR . 'block-manager/templates/admin-page.php';
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook) {
        if ($hook !== 'quickswap_page_quickswap-block-collections') {
            return;
        }

        wp_enqueue_script('quickswap-block-manager-admin');
        wp_enqueue_style('quickswap-block-manager-admin');

        // Localize script
        wp_localize_script('quickswap-block-manager-admin', 'quickswapBlockManagerAdmin', array(
            'apiUrl' => rest_url('quickswap-block-manager/v1'),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
}
