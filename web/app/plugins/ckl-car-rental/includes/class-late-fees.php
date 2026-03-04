<?php
/**
 * CKL Late Fees
 *
 * Handles late fee calculation and notifications
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Late_Fees {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('woocommerce_booking_complete', array(__CLASS__, 'calculate_late_fee'));
        add_action('woocommerce_bookings_update_booking_status', array(__CLASS__, 'on_status_change'), 10, 2);
        add_action('add_meta_boxes', array(__CLASS__, 'add_late_fee_meta_box'));
        add_action('wp_ajax_check_late_return', array(__CLASS__, 'ajax_check_late_return'));
    }

    /**
     * Calculate late fee when booking is marked complete
     */
    public static function calculate_late_fee($booking_id) {
        $booking = get_wc_booking($booking_id);
        if (!$booking) {
            return;
        }

        $product_id = $booking->get_product_id();
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

        if (!$vehicle_id) {
            return;
        }

        $scheduled_end = $booking->get_end_date();
        $scheduled_end_ts = strtotime($scheduled_end);

        // Get actual return time (should be set by admin when vehicle is returned)
        $actual_return = get_post_meta($booking_id, '_actual_return_time', true);

        if (!$actual_return) {
            return;
        }

        $actual_return_ts = strtotime($actual_return);

        // Check if return is late
        if ($actual_return_ts > $scheduled_end_ts) {
            $grace_period = get_post_meta($vehicle_id, '_vehicle_grace_period_minutes', true);
            if (empty($grace_period)) {
                $grace_period = 0;
            }

            $grace_period_seconds = $grace_period * 60;
            $late_start_ts = $scheduled_end_ts + $grace_period_seconds;

            // Only apply fee if beyond grace period
            if ($actual_return_ts > $late_start_ts) {
                $late_fee_per_hour = get_post_meta($vehicle_id, '_vehicle_late_fee_per_hour', true);

                if (empty($late_fee_per_hour)) {
                    // Use default late fee if not set on vehicle
                    $late_fee_per_hour = get_option('ckl_default_late_fee_per_hour', 10);
                }

                // Calculate hours late (round up to next hour)
                $hours_late = ceil(($actual_return_ts - $late_start_ts) / 3600);

                // Calculate late fee
                $late_fee = $hours_late * floatval($late_fee_per_hour);

                // Store late fee information
                update_post_meta($booking_id, '_late_fee_hours', $hours_late);
                update_post_meta($booking_id, '_late_fee_amount', $late_fee);
                update_post_meta($booking_id, '_late_fee_calculated', 'yes');

                // Add fee to order
                $order_id = $booking->get_order_id();
                if ($order_id) {
                    $order = wc_get_order($order_id);
                    if ($order) {
                        $fee = new WC_Order_Item_Fee();
                        $fee->set_name(__('Late Return Fee', 'ckl-car-rental'));
                        $fee->set_amount($late_fee);
                        $fee->set_tax_class(0);
                        $fee->set_tax_status('none');
                        $fee->set_total($late_fee);
                        $order->add_item($fee);
                        $order->calculate_totals();
                        $order->save();

                        // Add order note
                        $order->add_order_note(sprintf(
                            __('Late return fee applied: %s hours late. Fee: %s', 'ckl-car-rental'),
                            $hours_late,
                            wc_price($late_fee)
                        ));
                    }
                }

                // Send late return notification
                self::send_late_return_notification($booking_id, $hours_late, $late_fee);

                return $late_fee;
            }
        }

        return 0;
    }

    /**
     * Handle booking status changes
     */
    public static function on_status_change($booking_id, $status) {
        if ($status === 'complete' || $status === 'cancelled') {
            // Check for late return
            self::calculate_late_fee($booking_id);
        }
    }

    /**
     * Send late return notification
     */
    public static function send_late_return_notification($booking_id, $hours_late, $late_fee) {
        $booking = get_wc_booking($booking_id);
        $order_id = $booking->get_order_id();
        $order = wc_get_order($order_id);

        if (!$order) {
            return;
        }

        $customer_email = $order->get_billing_email();
        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

        $subject = sprintf(__('[%s] Late Return Notification', 'ckl-car-rental'), get_bloginfo('name'));

        $message = sprintf(
            __('Dear %s,\n\nThis is to notify you that your vehicle return was %s hours late.\n\nA late fee of %s has been applied to your order.\n\nIf you have any questions, please contact us.\n\nThank you.', 'ckl-car-rental'),
            $customer_name,
            $hours_late,
            wc_price($late_fee)
        );

        // Send email
        wp_mail($customer_email, $subject, $message);

        // Also notify admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('[%s] Late Return Alert', 'ckl-car-rental'), get_bloginfo('name'));
        $admin_message = sprintf(
            __('A booking (ID: %s) has been returned %s hours late.\n\nLate fee applied: %s\n\nCustomer: %s (%s)\n\nPlease review the booking.', 'ckl-car-rental'),
            $booking_id,
            $hours_late,
            wc_price($late_fee),
            $customer_name,
            $customer_email
        );
        wp_mail($admin_email, $admin_subject, $admin_message);
    }

    /**
     * Add late fee meta box to booking
     */
    public static function add_late_fee_meta_box() {
        add_meta_box(
            'ckl_late_fee',
            __('Late Return Fee', 'ckl-car-rental'),
            array(__CLASS__, 'late_fee_meta_box_html'),
            'wc_booking',
            'side',
            'default'
        );
    }

    /**
     * Render late fee meta box
     */
    public static function late_fee_meta_box_html($post) {
        $booking = get_wc_booking($post->ID);
        $scheduled_end = $booking->get_end_date();

        $actual_return = get_post_meta($post->ID, '_actual_return_time', true);
        $late_fee_amount = get_post_meta($post->ID, '_late_fee_amount', true);
        $late_fee_hours = get_post_meta($post->ID, '_late_fee_hours', true);
        $late_fee_calculated = get_post_meta($post->ID, '_late_fee_calculated', true);

        wp_nonce_field('ckl_save_late_fee', 'ckl_late_fee_nonce');

        ?>
        <div class="ckl-late-fee-meta-box">
            <p>
                <strong><?php _e('Scheduled Return:', 'ckl-car-rental'); ?></strong><br>
                <?php echo $scheduled_end; ?>
            </p>

            <p>
                <label for="actual_return_time"><?php _e('Actual Return Time:', 'ckl-car-rental'); ?></label><br>
                <input type="datetime-local"
                       id="actual_return_time"
                       name="actual_return_time"
                       value="<?php echo esc_attr($actual_return); ?>"
                       class="regular-text">
            </p>

            <?php if ($late_fee_calculated === 'yes'): ?>
                <div class="late-fee-applied" style="background: #fff3cd; padding: 10px; margin-top: 10px; border-left: 4px solid #ffc107;">
                    <p><strong><?php _e('Late Fee Applied:', 'ckl-car-rental'); ?></strong></p>
                    <p><?php _e('Hours Late:', 'ckl-car-rental'); ?> <?php echo esc_html($late_fee_hours); ?></p>
                    <p><?php _e('Fee Amount:', 'ckl-car-rental'); ?> <?php echo wc_price($late_fee_amount); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!$actual_return): ?>
                <p class="description"><?php _e('Set the actual return time to calculate late fees.', 'ckl-car-rental'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Save late fee meta
     */
    public static function save_late_fee_meta($post_id) {
        // Check nonce
        if (!isset($_POST['ckl_late_fee_nonce']) || !wp_verify_nonce($_POST['ckl_late_fee_nonce'], 'ckl_save_late_fee')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save actual return time
        if (isset($_POST['actual_return_time']) && !empty($_POST['actual_return_time'])) {
            $actual_return = sanitize_text_field($_POST['actual_return_time']);

            // Format the datetime for storage
            $actual_return_formatted = date('Y-m-d H:i:s', strtotime($actual_return));
            update_post_meta($post_id, '_actual_return_time', $actual_return_formatted);

            // Recalculate late fee
            self::calculate_late_fee($post_id);
        }
    }

    /**
     * AJAX handler for checking late returns
     */
    public static function ajax_check_late_return() {
        check_ajax_referer('ckl-check-late-return', 'nonce');

        $booking_id = intval($_POST['booking_id']);

        if (!$booking_id) {
            wp_send_json_error(array('message' => __('Invalid booking ID', 'ckl-car-rental')));
        }

        $booking = get_wc_booking($booking_id);
        if (!$booking) {
            wp_send_json_error(array('message' => __('Booking not found', 'ckl-car-rental')));
        }

        $scheduled_end = $booking->get_end_date();
        $scheduled_end_ts = strtotime($scheduled_end);
        $now = current_time('timestamp');

        if ($now > $scheduled_end_ts) {
            wp_send_json_success(array(
                'is_late' => true,
                'scheduled_end' => $scheduled_end,
                'current_time' => current_time('mysql'),
            ));
        } else {
            wp_send_json_success(array('is_late' => false));
        }
    }
}

// Hook for saving late fee meta
add_action('save_post_wc_booking', array('CKL_Late_Fees', 'save_late_fee_meta'), 20, 2);
