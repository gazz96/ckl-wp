<?php
/**
 * Location-Based Booking System
 *
 * Handles location taxonomy, pickup/return locations, and location-based availability.
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Location_System {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_location_taxonomy'));
        add_action('vehicle_details_meta_box_html', array(__CLASS__, 'add_location_fields_to_vehicle'));
        add_action('save_post_vehicle', array(__CLASS__, 'save_vehicle_location_meta'), 30);
        add_filter('woocommerce_bookings_is_valid', array(__CLASS__, 'validate_booking_location'), 10, 4);
        add_action('woocommerce_bookings_after_create_booking', array(__CLASS__, 'save_booking_locations'), 10, 2);
    }

    /**
     * Register Location Taxonomy
     */
    public static function register_location_taxonomy() {
        $labels = array(
            'name'              => __('Locations', 'ckl-car-rental'),
            'singular_name'     => __('Location', 'ckl-car-rental'),
            'search_items'      => __('Search Locations', 'ckl-car-rental'),
            'all_items'         => __('All Locations', 'ckl-car-rental'),
            'parent_item'       => __('Parent Location', 'ckl-car-rental'),
            'parent_item_colon' => __('Parent Location:', 'ckl-car-rental'),
            'edit_item'         => __('Edit Location', 'ckl-car-rental'),
            'update_item'       => __('Update Location', 'ckl-car-rental'),
            'add_new_item'      => __('Add New Location', 'ckl-car-rental'),
            'new_item_name'     => __('New Location Name', 'ckl-car-rental'),
            'menu_name'         => __('Locations', 'ckl-car-rental'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'location'),
            'show_in_rest'      => true,
        );

        register_taxonomy('vehicle_location', array('vehicle'), $args);
    }

    /**
     * Add location fields to vehicle meta box
     */
    public static function add_location_fields_to_vehicle($post) {
        // Get available locations
        $locations = get_terms(array(
            'taxonomy' => 'vehicle_location',
            'hide_empty' => false,
        ));

        $vehicle_locations = wp_get_post_terms($post->ID, 'vehicle_location', array('fields' => 'ids'));
        ?>
        <tr>
            <th><label for="vehicle_locations"><?php _e('Available Locations', 'ckl-car-rental'); ?></label></th>
            <td>
                <?php if (!empty($locations) && !is_wp_error($locations)) : ?>
                    <select id="vehicle_locations" name="vehicle_locations[]" multiple class="widefat" style="height: 150px;">
                        <?php foreach ($locations as $location) : ?>
                            <option value="<?php echo esc_attr($location->term_id); ?>" <?php selected(in_array($location->term_id, $vehicle_locations)); ?>>
                                <?php echo esc_html($location->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description"><?php _e('Select locations where this vehicle is available. Hold Ctrl/Cmd to select multiple.', 'ckl-car-rental'); ?></p>
                <?php else : ?>
                    <p><?php _e('No locations found. Please add locations in the Locations taxonomy.', 'ckl-car-rental'); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Save vehicle location meta
     */
    public static function save_vehicle_location_meta($post_id) {
        if (get_post_type($post_id) !== 'vehicle') {
            return;
        }

        // Save locations
        if (isset($_POST['vehicle_locations'])) {
            $location_ids = array_map('intval', $_POST['vehicle_locations']);
            wp_set_post_terms($post_id, $location_ids, 'vehicle_location');
        }
    }

    /**
     * Validate booking location
     */
    public static function validate_booking_location($is_valid, $product_id, $start_date, $end_date) {
        // Get vehicle ID from product
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

        if (!$vehicle_id) {
            return $is_valid;
        }

        // Check if pickup location is set
        if (!isset($_POST['pickup_location']) || empty($_POST['pickup_location'])) {
            return new WP_Error('no_pickup_location', __('Please select a pickup location.', 'ckl-car-rental'));
        }

        // Check if return location is set
        if (!isset($_POST['return_location']) || empty($_POST['return_location'])) {
            return new WP_Error('no_return_location', __('Please select a return location.', 'ckl-car-rental'));
        }

        $pickup_location = intval($_POST['pickup_location']);
        $return_location = intval($_POST['return_location']);

        // Get vehicle locations
        $vehicle_locations = wp_get_post_terms($vehicle_id, 'vehicle_location', array('fields' => 'ids'));

        // Check if pickup location is available for this vehicle
        if (!in_array($pickup_location, $vehicle_locations)) {
            return new WP_Error('invalid_pickup_location', __('This vehicle is not available at the selected pickup location.', 'ckl-car-rental'));
        }

        // Check if return location is available for this vehicle
        if (!in_array($return_location, $vehicle_locations)) {
            return new WP_Error('invalid_return_location', __('This vehicle cannot be returned to the selected location.', 'ckl-car-rental'));
        }

        return $is_valid;
    }

    /**
     * Save booking locations
     */
    public static function save_booking_locations($booking_id, $booking) {
        // Save pickup location
        if (isset($_POST['pickup_location'])) {
            update_post_meta($booking_id, '_pickup_location', intval($_POST['pickup_location']));
        }

        // Save return location
        if (isset($_POST['return_location'])) {
            update_post_meta($booking_id, '_return_location', intval($_POST['return_location']));
        }

        // Calculate distance fee if locations are different
        $pickup_location = isset($_POST['pickup_location']) ? intval($_POST['pickup_location']) : 0;
        $return_location = isset($_POST['return_location']) ? intval($_POST['return_location']) : 0;

        if ($pickup_location !== $return_location) {
            $drop_off_fee = self::calculate_drop_off_fee($pickup_location, $return_location);
            if ($drop_off_fee > 0) {
                update_post_meta($booking_id, '_drop_off_fee', $drop_off_fee);
            }
        }
    }

    /**
     * Calculate drop-off fee
     */
    private static function calculate_drop_off_fee($pickup_id, $return_id) {
        // Get distance fee option
        $distance_fee_enabled = get_option('ckl_distance_fee_enabled', 'no');

        if ($distance_fee_enabled !== 'yes') {
            return 0;
        }

        $fee_per_km = get_option('ckl_distance_fee_per_km', '0.50');

        // Get location coordinates
        $pickup_coords = get_term_meta($pickup_id, '_location_coordinates', true);
        $return_coords = get_term_meta($return_id, '_location_coordinates', true);

        if (empty($pickup_coords) || empty($return_coords)) {
            // Default flat fee for different locations
            return floatval(get_option('ckl_drop_off_flat_fee', '20.00'));
        }

        // Calculate distance using Haversine formula
        $distance = self::calculate_distance($pickup_coords, $return_coords);

        // Calculate fee
        $fee = $distance * $fee_per_km;

        return floatval($fee);
    }

    /**
     * Calculate distance between two coordinates
     */
    private static function calculate_distance($coords1, $coords2) {
        list($lat1, $lon1) = explode(',', $coords1);
        list($lat2, $lon2) = explode(',', $coords2);

        $lat1 = floatval($lat1);
        $lon1 = floatval($lon1);
        $lat2 = floatval($lat2);
        $lon2 = floatval($lon2);

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = $miles * 1.609344;

        return $km;
    }

    /**
     * Get vehicle locations dropdown
     */
    public static function get_locations_dropdown($name = 'location', $selected = 0, $multiple = false) {
        $locations = get_terms(array(
            'taxonomy' => 'vehicle_location',
            'hide_empty' => false,
        ));

        if (empty($locations) || is_wp_error($locations)) {
            return '';
        }

        $multiple_attr = $multiple ? 'multiple' : '';
        $name_attr = $multiple ? $name . '[]' : $name;

        ob_start();
        ?>
        <select name="<?php echo esc_attr($name_attr); ?>" id="<?php echo esc_attr($name); ?>" <?php echo $multiple_attr; ?> class="widefat">
            <?php if (!$multiple) : ?>
                <option value=""><?php _e('Select Location', 'ckl-car-rental'); ?></option>
            <?php endif; ?>
            <?php foreach ($locations as $location) : ?>
                <option value="<?php echo esc_attr($location->term_id); ?>" <?php selected($selected, $location->term_id); ?>>
                    <?php echo esc_html($location->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
        return ob_get_clean();
    }
}
