<?php
/**
 * Plugin Name: CKL Car Rental
 * Plugin URI: https://cklangkawi.com
 * Description: Custom car rental booking system for CK Langkawi. Integrates with WooCommerce Bookings for complete rental management.
 * Version: 1.0.2
 * Author: CK Langkawi
 * Author URI: https://cklangkawi.com
 * Text Domain: ckl-car-rental
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 9.8
 * WC tested up to: 10.5
 * PHP tested up to: 8.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CKL_CAR_RENTAL_VERSION', '1.0.2');
define('CKL_CAR_RENTAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CKL_CAR_RENTAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CKL_CAR_RENTAL_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main CKL Car Rental Class
 */
class CKL_Car_Rental {

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
        // Core classes
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-user-roles.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-booking-manager.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-late-fees.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-reviews.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-calendar-sync.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-analytics.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-dynamic-pricing.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-block-dates.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-location-system.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-hero-search-locations.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-my-account-endpoints.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-customer-dashboard-ajax.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-vehicle-booking-ajax.php';
        require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/class-booking-hooks.php';

        // Admin classes
        if (is_admin()) {
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-admin-metaboxes.php';
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-peak-price-manager.php';
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-pricing-rule-templates.php';
            require_once CKL_CAR_RENTAL_PLUGIN_DIR . 'includes/admin/class-admin-ajax.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        add_action('init', array($this, 'load_textdomain'));
        add_action('before_woocommerce_init', array($this, 'declare_hpos_compatibility'));
    }

    /**
     * Declare compatibility with High-Performance Order Storage.
     *
     * @since 1.0.1
     */
    public function declare_hpos_compatibility() {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                __FILE__,
                true
            );
        }
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        // Check if WooCommerce Bookings is active
        if (!class_exists('WC_Bookings')) {
            add_action('admin_notices', array($this, 'woocommerce_bookings_missing_notice'));
            return;
        }

        // Initialize classes
        CKL_User_Roles::init();
        CKL_Booking_Manager::init();
        CKL_Late_Fees::init();
        CKL_Reviews::init();
        CKL_Calendar_Sync::init();
        CKL_Analytics::init();
        CKL_Dynamic_Pricing::init();
        CKL_Block_Dates::init();
        CKL_Location_System::init();
        CKL_Hero_Search_Locations::init();
        CKL_My_Account_Endpoints::init();
        CKL_Booking_Hooks::init();
        CKL_Vehicle_Booking_AJAX::init();
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('ckl-car-rental', false, dirname(CKL_CAR_RENTAL_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('CKL Car Rental requires WooCommerce to be installed and active.', 'ckl-car-rental'); ?></p>
        </div>
        <?php
    }

    /**
     * WooCommerce Bookings missing notice
     */
    public function woocommerce_bookings_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('CKL Car Rental requires WooCommerce Bookings to be installed and active.', 'ckl-car-rental'); ?></p>
        </div>
        <?php
    }
}

/**
 * Initialize the plugin
 */
function ckl_car_rental() {
    return CKL_Car_Rental::get_instance();
}

// Start the plugin
ckl_car_rental();
