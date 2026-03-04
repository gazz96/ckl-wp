<?php
/**
 * Hero Search Locations
 *
 * Defines free and custom locations for Langkawi with fee calculation
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * CKL Hero Search Locations Class
 */
class CKL_Hero_Search_Locations {

    /**
     * Free pickup/return locations (no additional fee)
     *
     * @var array
     */
    private static $free_locations = array(
        'langkawi-airport' => array(
            'name' => 'Langkawi Airport',
            'fee' => 0
        ),
        'kuah-town' => array(
            'name' => 'Kuah Town',
            'fee' => 0
        ),
        'padang-matsirat' => array(
            'name' => 'Padang Matsirat',
            'fee' => 0
        ),
        'cenang-beach' => array(
            'name' => 'Cenang Beach',
            'fee' => 0
        )
    );

    /**
     * Custom locations (with additional fee)
     *
     * @var array
     */
    private static $custom_locations = array(
        'tanjang-rhu' => array(
            'name' => 'Tanjung Rhu',
            'fee' => 50  // RM50 custom location fee
        ),
        'datai-bay' => array(
            'name' => 'Datai Bay',
            'fee' => 80
        ),
        'temoyong' => array(
            'name' => 'Temoyong',
            'fee' => 60
        )
    );

    /**
     * Get all locations organized by type
     *
     * @return array
     */
    public static function get_all_locations() {
        return array_merge(
            array('free' => self::$free_locations),
            array('custom' => self::$custom_locations)
        );
    }

    /**
     * Get location fee by slug
     *
     * @param string $location_slug Location slug
     * @return int Fee amount
     */
    public static function get_location_fee($location_slug) {
        // Check free locations
        if (isset(self::$free_locations[$location_slug])) {
            return 0;
        }
        // Check custom locations
        if (isset(self::$custom_locations[$location_slug])) {
            return self::$custom_locations[$location_slug]['fee'];
        }
        return 0;
    }

    /**
     * Calculate drop-off fee based on pickup and return locations
     *
     * @param string $pickup_slug Pickup location slug
     * @param string $return_slug Return location slug
     * @return int Drop-off fee amount
     */
    public static function calculate_dropoff_fee($pickup_slug, $return_slug) {
        // No fee if same location
        if ($pickup_slug === $return_slug) {
            return 0;
        }

        // Add both location fees (if either is custom)
        $pickup_fee = self::get_location_fee($pickup_slug);
        $return_fee = self::get_location_fee($return_slug);

        // Drop-off fee: higher of pickup or return fee
        return max($pickup_fee, $return_fee);
    }

    /**
     * Initialize the class
     *
     * @return void
     */
    public static function init() {
        // AJAX handler for drop-off fee calculation
        add_action('wp_ajax_ckl_calculate_dropoff_fee', array(__CLASS__, 'ajax_calculate_dropoff_fee'));
        add_action('wp_ajax_nopriv_ckl_calculate_dropoff_fee', array(__CLASS__, 'ajax_calculate_dropoff_fee'));
    }

    /**
     * AJAX handler to calculate drop-off fee
     *
     * @return void
     */
    public static function ajax_calculate_dropoff_fee() {
        check_ajax_referer('ckl_dropoff_fee', 'nonce');

        $pickup = isset($_POST['pickup']) ? sanitize_text_field($_POST['pickup']) : '';
        $return = isset($_POST['return']) ? sanitize_text_field($_POST['return']) : '';

        $fee = self::calculate_dropoff_fee($pickup, $return);

        wp_send_json_success(array('fee' => $fee));
    }
}
