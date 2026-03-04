<?php
/**
 * CKL Booking Manager
 *
 * Handles booking management and processing
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Booking_Manager {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('woocommerce_bookings_created_booking', array(__CLASS__, 'on_booking_created'), 10, 2);
        add_filter('woocommerce_bookings_is_valid', array(__CLASS__, 'validate_booking_availability'), 10, 4);
        add_action('add_meta_boxes', array(__CLASS__, 'add_booking_meta_boxes'));
    }

    /**
     * Handle booking creation
     */
    public static function on_booking_created($booking_id, $booking) {
        // Store additional booking information
        $order_id = $booking->get_order_id();
        $product_id = $booking->get_product_id();
        $start_date = $booking->get_start_date();
        $end_date = $booking->get_end_date();

        // Get vehicle ID from product
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

        if ($vehicle_id) {
            // Store vehicle ID with booking
            update_post_meta($booking_id, '_booking_vehicle_id', $vehicle_id);

            // Store pickup/return locations if available
            if (isset($_POST['_pickup_location'])) {
                update_post_meta($booking_id, '_pickup_location', sanitize_text_field($_POST['_pickup_location']));
            }
            if (isset($_POST['_return_location'])) {
                update_post_meta($booking_id, '_return_location', sanitize_text_field($_POST['_return_location']));
            }

            // Calculate rental duration
            $duration = self::calculate_rental_duration($start_date, $end_date);
            update_post_meta($booking_id, '_rental_duration_days', $duration['days']);
            update_post_meta($booking_id, '_rental_duration_hours', $duration['hours']);

            // Log booking for analytics
            self::log_booking_analytics($booking_id, $vehicle_id);
        }
    }

    /**
     * Calculate rental duration
     */
    public static function calculate_rental_duration($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);

        return array(
            'days' => $interval->days,
            'hours' => $interval->h,
            'total_hours' => ($interval->days * 24) + $interval->h + ($interval->i / 60),
        );
    }

    /**
     * Validate booking availability against blocked dates and other rules
     */
    public static function validate_booking_availability($is_valid, $product_id, $start_date, $end_date) {
        // Check if dates are blocked
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

        if ($vehicle_id) {
            // Check for blocked dates
            $blocked_dates = self::get_blocked_dates_for_vehicle($vehicle_id);

            foreach ($blocked_dates as $blocked) {
                $blocked_start = get_post_meta($blocked->ID, '_blocked_start_date', true);
                $blocked_end = get_post_meta($blocked->ID, '_blocked_end_date', true);

                if (self::date_ranges_overlap($start_date, $end_date, $blocked_start, $blocked_end)) {
                    return new WP_Error('blocked_date', __('This vehicle is not available on the selected dates.', 'ckl-car-rental'));
                }
            }
        }

        return $is_valid;
    }

    /**
     * Check if two date ranges overlap
     */
    public static function date_ranges_overlap($start1, $end1, $start2, $end2) {
        $start_ts = strtotime($start1);
        $end_ts = strtotime($end1);
        $blocked_start_ts = strtotime($start2);
        $blocked_end_ts = strtotime($end2);

        return ($start_ts <= $blocked_end_ts) && ($end_ts >= $blocked_start_ts);
    }

    /**
     * Get blocked dates for vehicle
     */
    public static function get_blocked_dates_for_vehicle($vehicle_id) {
        return get_posts(array(
            'post_type' => 'blocked_date',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_blocked_vehicle_id',
                    'value' => $vehicle_id,
                ),
            ),
        ));
    }

    /**
     * Log booking analytics
     */
    public static function log_booking_analytics($booking_id, $vehicle_id) {
        $booking = get_wc_booking($booking_id);
        $order = wc_get_order($booking->get_order_id());

        $analytics_data = array(
            'booking_id' => $booking_id,
            'vehicle_id' => $vehicle_id,
            'order_total' => $order ? $order->get_total() : 0,
            'start_date' => $booking->get_start_date(),
            'end_date' => $booking->get_end_date(),
            'created_at' => current_time('mysql'),
        );

        // Store in analytics log (could use separate table)
        $analytics_log = get_option('ckl_analytics_log', array());
        $analytics_log[] = $analytics_data;
        update_option('ckl_analytics_log', $analytics_log);
    }

    /**
     * Add booking meta boxes
     */
    public static function add_booking_meta_boxes() {
        add_meta_box(
            'ckl_booking_details',
            __('CKL Booking Details', 'ckl-car-rental'),
            array(__CLASS__, 'booking_details_meta_box_html'),
            'wc_booking',
            'side',
            'default'
        );
    }

    /**
     * Render booking details meta box
     */
    public static function booking_details_meta_box_html($post) {
        $vehicle_id = get_post_meta($post->ID, '_booking_vehicle_id', true);
        $duration_days = get_post_meta($post->ID, '_rental_duration_days', true);
        $pickup_location = get_post_meta($post->ID, '_pickup_location', true);
        $return_location = get_post_meta($post->ID, '_return_location', true);

        ?>
        <div class="ckl-booking-details">
            <?php if ($vehicle_id): ?>
                <p><strong><?php _e('Vehicle:', 'ckl-car-rental'); ?></strong>
                    <a href="<?php echo get_edit_post_link($vehicle_id); ?>"><?php echo get_the_title($vehicle_id); ?></a>
                </p>
            <?php endif; ?>

            <?php if ($duration_days): ?>
                <p><strong><?php _e('Duration:', 'ckl-car-rental'); ?></strong> <?php echo $duration_days; ?> <?php _e('days', 'ckl-car-rental'); ?></p>
            <?php endif; ?>

            <?php if ($pickup_location): ?>
                <p><strong><?php _e('Pickup Location:', 'ckl-car-rental'); ?></strong><br><?php echo esc_html($pickup_location); ?></p>
            <?php endif; ?>

            <?php if ($return_location): ?>
                <p><strong><?php _e('Return Location:', 'ckl-car-rental'); ?></strong><br><?php echo esc_html($return_location); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
