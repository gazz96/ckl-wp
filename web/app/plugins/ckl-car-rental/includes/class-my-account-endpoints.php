<?php
/**
 * CKL My Account Endpoints
 *
 * Register custom WooCommerce My Account endpoints for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

class CKL_My_Account_Endpoints {

    /**
     * Custom endpoints
     */
    private static $endpoints = array(
        'dashboard' => 'dashboard',
        'bookings' => 'bookings',
        'booking-details' => 'booking-details',
        'profile' => 'profile',
        'payment-history' => 'payment-history',
        'documents' => 'documents',
        'support' => 'support',
    );

    /**
     * Initialize endpoints
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'add_endpoints'));
        add_filter('woocommerce_get_query_vars', array(__CLASS__, 'add_query_vars'), 0);
        add_filter('woocommerce_account_menu_items', array(__CLASS__, 'add_menu_items'));
        add_action('woocommerce_account_dashboard_endpoint', array(__CLASS__, 'dashboard_content'));
        add_action('woocommerce_account_bookings_endpoint', array(__CLASS__, 'bookings_content'));
        add_action('woocommerce_account_booking-details_endpoint', array(__CLASS__, 'booking_details_content'));
        add_action('woocommerce_account_profile_endpoint', array(__CLASS__, 'profile_content'));
        add_action('woocommerce_account_payment-history_endpoint', array(__CLASS__, 'payment_history_content'));
        add_action('woocommerce_account_documents_endpoint', array(__CLASS__, 'documents_content'));
        add_action('woocommerce_account_support_endpoint', array(__CLASS__, 'support_content'));

        // Flush rewrite rules on plugin activation
        register_activation_hook(CKL_CAR_RENTAL_PLUGIN_BASENAME, array(__CLASS__, 'flush_rewrite_rules'));
    }

    /**
     * Add new endpoints
     */
    public static function add_endpoints() {
        foreach (self::$endpoints as $endpoint => $slug) {
            add_rewrite_endpoint($slug, EP_ROOT | EP_PAGES);
        }
    }

    /**
     * Add query vars
     */
    public static function add_query_vars($vars) {
        foreach (self::$endpoints as $key => $var) {
            $vars[] = $var;
        }
        return $vars;
    }

    /**
     * Add menu items
     */
    public static function add_menu_items($items) {
        // Remove default dashboard
        unset($items['dashboard']);

        // Add custom items in desired order
        $new_items = array(
            'dashboard' => __('Dashboard', 'ckl-car-rental'),
            'orders' => __('Orders', 'woocommerce'),
            'bookings' => __('Bookings', 'ckl-car-rental'),
            'addresses' => __('Addresses', 'woocommerce'),
            'profile' => __('Profile', 'ckl-car-rental'),
            'payment-history' => __('Payment History', 'ckl-car-rental'),
            'documents' => __('Documents', 'ckl-car-rental'),
            'support' => __('Support', 'ckl-car-rental'),
            'customer-logout' => __('Logout', 'woocommerce'),
        );

        return $new_items;
    }

    /**
     * Dashboard endpoint content
     */
    public static function dashboard_content() {
        wc_get_template('myaccount/dashboard.php');
    }

    /**
     * Bookings endpoint content
     */
    public static function bookings_content() {
        wc_get_template('myaccount/bookings.php');
    }

    /**
     * Booking details endpoint content
     */
    public static function booking_details_content() {
        $booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

        if (!$booking_id) {
            echo '<div class="woocommerce-error">' . esc_html__('Booking not found.', 'ckl-car-rental') . '</div>';
            return;
        }

        // Verify ownership
        if (!ckl_verify_booking_ownership($booking_id, get_current_user_id())) {
            echo '<div class="woocommerce-error">' . esc_html__('You do not have permission to view this booking.', 'ckl-car-rental') . '</div>';
            return;
        }

        wc_get_template('myaccount/booking-details.php', array('booking_id' => $booking_id));
    }

    /**
     * Profile endpoint content
     */
    public static function profile_content() {
        wc_get_template('myaccount/profile.php');
    }

    /**
     * Payment history endpoint content
     */
    public static function payment_history_content() {
        wc_get_template('myaccount/payment-history.php');
    }

    /**
     * Documents endpoint content
     */
    public static function documents_content() {
        wc_get_template('myaccount/documents.php');
    }

    /**
     * Support endpoint content
     */
    public static function support_content() {
        wc_get_template('myaccount/support.php');
    }

    /**
     * Flush rewrite rules
     */
    public static function flush_rewrite_rules() {
        self::add_endpoints();
        flush_rewrite_rules();
    }
}

/**
 * Verify booking ownership
 */
function ckl_verify_booking_ownership($booking_id, $user_id) {
    if (!class_exists('WC_Booking')) {
        return false;
    }

    $booking = get_wc_booking($booking_id);

    if (!$booking) {
        return false;
    }

    $order_id = $booking->get_order_id();

    if (!$order_id) {
        return false;
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return false;
    }

    return $order->get_user_id() === $user_id;
}
