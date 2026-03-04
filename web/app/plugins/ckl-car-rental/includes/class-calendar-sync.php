<?php
/**
 * CKL Calendar Sync
 *
 * Handles Google Calendar integration for bookings
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Calendar_Sync {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('woocommerce_bookings_created_booking', array(__CLASS__, 'sync_booking_to_calendar'), 20, 2);
        add_action('woocommerce_bookings_updated_booking', array(__CLASS__, 'update_booking_in_calendar'), 20);
        add_action('woocommerce_bookings_cancelled_booking', array(__CLASS__, 'remove_booking_from_calendar'), 20);
        add_action('admin_post_ckl_calendar_settings', array(__CLASS__, 'save_calendar_settings'));
    }

    /**
     * Sync booking to Google Calendar
     */
    public static function sync_booking_to_calendar($booking_id, $booking) {
        // Check if Google Calendar sync is enabled
        if (!self::is_sync_enabled()) {
            return;
        }

        $calendar_id = self::get_calendar_id();
        if (!$calendar_id) {
            return;
        }

        $event_data = self::prepare_event_data($booking);

        // Send to Google Calendar
        $event_id = self::create_google_calendar_event($calendar_id, $event_data);

        if ($event_id) {
            // Store Google Calendar event ID
            update_post_meta($booking_id, '_google_calendar_event_id', $event_id);
            update_post_meta($booking_id, '_google_calendar_id', $calendar_id);
        }
    }

    /**
     * Update booking in Google Calendar
     */
    public static function update_booking_in_calendar($booking_id) {
        $google_event_id = get_post_meta($booking_id, '_google_calendar_event_id', true);
        $calendar_id = get_post_meta($booking_id, '_google_calendar_id', true);

        if (!$google_event_id || !$calendar_id) {
            return;
        }

        $booking = get_wc_booking($booking_id);
        if (!$booking) {
            return;
        }

        $event_data = self::prepare_event_data($booking);
        self::update_google_calendar_event($calendar_id, $google_event_id, $event_data);
    }

    /**
     * Remove booking from Google Calendar
     */
    public static function remove_booking_from_calendar($booking_id) {
        $google_event_id = get_post_meta($booking_id, '_google_calendar_event_id', true);
        $calendar_id = get_post_meta($booking_id, '_google_calendar_id', true);

        if (!$google_event_id || !$calendar_id) {
            return;
        }

        self::delete_google_calendar_event($calendar_id, $google_event_id);

        // Remove meta data
        delete_post_meta($booking_id, '_google_calendar_event_id');
        delete_post_meta($booking_id, '_google_calendar_id');
    }

    /**
     * Prepare event data for Google Calendar
     */
    private static function prepare_event_data($booking) {
        $order_id = $booking->get_order_id();
        $order = wc_get_order($order_id);

        $product_id = $booking->get_product_id();
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);
        $vehicle_name = $vehicle_id ? get_the_title($vehicle_id) : $booking->get_product_name();

        $customer_name = $order ? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() : __('Guest', 'ckl-car-rental');
        $customer_email = $order ? $order->get_billing_email() : '';
        $customer_phone = $order ? $order->get_billing_phone() : '';

        $pickup_location = get_post_meta($booking->get_id(), '_pickup_location', true);
        $return_location = get_post_meta($booking->get_id(), '_return_location', true);

        $summary = sprintf(
            __('Booking: %s - %s', 'ckl-car-rental'),
            $vehicle_name,
            $customer_name
        );

        $description = self::build_event_description($booking, $vehicle_name, $customer_name, $customer_email, $customer_phone, $pickup_location, $return_location);

        $start_date = $booking->get_start_date();
        $end_date = $booking->get_end_date();

        return array(
            'summary' => $summary,
            'description' => $description,
            'start' => array(
                'dateTime' => date('c', strtotime($start_date)),
                'timeZone' => self::get_timezone(),
            ),
            'end' => array(
                'dateTime' => date('c', strtotime($end_date)),
                'timeZone' => self::get_timezone(),
            ),
            'location' => $pickup_location ? $pickup_location : __('Pickup location TBD', 'ckl-car-rental'),
            'attendees' => array(
                array('email' => $customer_email, 'displayName' => $customer_name),
            ),
            'colorId' => self::get_booking_color($booking->get_status()),
        );
    }

    /**
     * Build event description
     */
    private static function build_event_description($booking, $vehicle_name, $customer_name, $customer_email, $customer_phone, $pickup_location, $return_location) {
        $description = '';

        $description .= sprintf(__('Vehicle: %s', 'ckl-car-rental'), $vehicle_name) . "\n\n";
        $description .= sprintf(__('Customer: %s', 'ckl-car-rental'), $customer_name) . "\n";
        $description .= sprintf(__('Email: %s', 'ckl-car-rental'), $customer_email) . "\n";
        $description .= sprintf(__('Phone: %s', 'ckl-car-rental'), $customer_phone) . "\n\n";

        $description .= __('Booking Details:', 'ckl-car-rental') . "\n";
        $description .= sprintf(__('Pickup: %s - %s', 'ckl-car-rental'), $booking->get_start_date(), $pickup_location) . "\n";
        $description .= sprintf(__('Return: %s - %s', 'ckl-car-rental'), $booking->get_end_date(), $return_location) . "\n";

        $description .= sprintf(__('Status: %s', 'ckl-car-rental'), $booking->get_status()) . "\n";
        $description .= sprintf(__('Booking ID: #%d', 'ckl-car-rental'), $booking->get_id()) . "\n";

        return $description;
    }

    /**
     * Get booking color based on status
     */
    private static function get_booking_color($status) {
        $colors = array(
            'pending' => '10', // Green
            'confirmed' => '5', // Yellow
            'paid' => '2', // Blue
            'complete' => '11', // Red
            'cancelled' => '8', // Gray
        );

        return isset($colors[$status]) ? $colors[$status] : '1';
    }

    /**
     * Create Google Calendar event using WooCommerce Bookings built-in sync
     */
    private static function create_google_calendar_event($calendar_id, $event_data) {
        // WooCommerce Bookings has built-in Google Calendar sync
        // Use that if configured

        if (class_exists('WC_Bookings_Google')) {
            try {
                $google = new WC_Bookings_Google();

                if ($google->is_connected()) {
                    // Use WooCommerce Bookings sync
                    return $google->sync_event($event_data);
                }
            } catch (Exception $e) {
                error_log('CKL Calendar Sync Error: ' . $e->getMessage());
                return false;
            }
        }

        // Fallback: Manual implementation would go here
        // This would require Google API client library
        return false;
    }

    /**
     * Update Google Calendar event
     */
    private static function update_google_calendar_event($calendar_id, $event_id, $event_data) {
        if (class_exists('WC_Bookings_Google')) {
            try {
                $google = new WC_Bookings_Google();

                if ($google->is_connected()) {
                    return $google->update_event($event_id, $event_data);
                }
            } catch (Exception $e) {
                error_log('CKL Calendar Update Error: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }

    /**
     * Delete Google Calendar event
     */
    private static function delete_google_calendar_event($calendar_id, $event_id) {
        if (class_exists('WC_Bookings_Google')) {
            try {
                $google = new WC_Bookings_Google();

                if ($google->is_connected()) {
                    return $google->delete_event($event_id);
                }
            } catch (Exception $e) {
                error_log('CKL Calendar Delete Error: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }

    /**
     * Check if sync is enabled
     */
    private static function is_sync_enabled() {
        return get_option('ckl_calendar_sync_enabled', 'no') === 'yes';
    }

    /**
     * Get calendar ID
     */
    private static function get_calendar_id() {
        return get_option('ckl_google_calendar_id', '');
    }

    /**
     * Get timezone
     */
    private static function get_timezone() {
        return get_option('timezone_string', 'UTC');
    }

    /**
     * Render calendar settings in admin
     */
    public static function render_calendar_settings() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $enabled = self::is_sync_enabled();
        $calendar_id = self::get_calendar_id();

        ?>
        <div class="ckl-calendar-settings">
            <h2><?php _e('Google Calendar Sync Settings', 'ckl-car-rental'); ?></h2>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ckl_calendar_settings">
                <?php wp_nonce_field('ckl_calendar_settings', 'ckl_calendar_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th>
                            <label for="ckl_calendar_sync_enabled"><?php _e('Enable Sync', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox"
                                   name="ckl_calendar_sync_enabled"
                                   id="ckl_calendar_sync_enabled"
                                   value="yes"
                                   <?php checked($enabled, 'yes'); ?>>
                            <p class="description">
                                <?php _e('Automatically sync bookings to Google Calendar', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="ckl_google_calendar_id"><?php _e('Calendar ID', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="text"
                                   name="ckl_google_calendar_id"
                                   id="ckl_google_calendar_id"
                                   value="<?php echo esc_attr($calendar_id); ?>"
                                   class="regular-text"
                                   placeholder="primary">
                            <p class="description">
                                <?php _e('Enter your Google Calendar ID or use "primary" for the default calendar', 'ckl-car-rental'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Save Settings', 'ckl-car-rental')); ?>
            </form>

            <div class="ckl-calendar-connection-status">
                <h3><?php _e('Connection Status', 'ckl-car-rental'); ?></h3>
                <?php if (class_exists('WC_Bookings_Google')): ?>
                    <?php
                    try {
                        $google = new WC_Bookings_Google();
                        if ($google->is_connected()) {
                            echo '<p class="success">✓ ' . __('Connected to Google Calendar', 'ckl-car-rental') . '</p>';
                        } else {
                            echo '<p class="warning">⚠ ' . __('Not connected. Please configure WooCommerce Bookings Google Calendar integration.', 'ckl-car-rental') . '</p>';
                            echo '<p><a href="' . admin_url('admin.php?page=wc_bookings_settings') . '">' . __('Configure Now', 'ckl-car-rental') . '</a></p>';
                        }
                    } catch (Exception $e) {
                        echo '<p class="error">✗ ' . __('Connection error', 'ckl-car-rental') . ': ' . $e->getMessage() . '</p>';
                    }
                    ?>
                <?php else: ?>
                    <p class="warning">⚠ <?php _e('WooCommerce Bookings Google Calendar integration not available.', 'ckl-car-rental'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Save calendar settings
     */
    public static function save_calendar_settings() {
        // Verify nonce
        if (!isset($_POST['ckl_calendar_nonce']) || !wp_verify_nonce($_POST['ckl_calendar_nonce'], 'ckl_calendar_settings')) {
            wp_die(__('Security check failed', 'ckl-car-rental'));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied', 'ckl-car-rental'));
        }

        // Save settings
        $enabled = isset($_POST['ckl_calendar_sync_enabled']) ? 'yes' : 'no';
        update_option('ckl_calendar_sync_enabled', $enabled);

        $calendar_id = isset($_POST['ckl_google_calendar_id']) ? sanitize_text_field($_POST['ckl_google_calendar_id']) : '';
        update_option('ckl_google_calendar_id', $calendar_id);

        // Redirect back to settings
        wp_redirect(admin_url('admin.php?page=ckl-settings&tab=calendar&status=saved'));
        exit;
    }
}
