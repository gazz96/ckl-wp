<?php
/**
 * Guest Booking Lookup Shortcode
 *
 * Shortcode for guest users to look up their bookings
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Guest Booking Lookup Shortcode
 *
 * Usage: [ckl_guest_booking_lookup]
 */
function ckl_guest_booking_lookup_shortcode() {
    // If user is logged in, redirect to my account bookings
    if (is_user_logged_in()) {
        $bookings_url = wc_get_page_permalink('myaccount');
        if ($bookings_url) {
            echo '<p>' . sprintf(__('You are already logged in. <a href="%s">View your bookings here</a>.', 'ckl-car-rental'), esc_url($bookings_url)) . '</p>';
            return;
        }
    }

    // Handle form submission
    $message = '';
    $message_type = '';
    $bookings = array();

    if (isset($_POST['ckl_lookup_booking']) && isset($_POST['lookup_email'])) {
        $nonce = isset($_POST['lookup_nonce']) ? sanitize_text_field($_POST['lookup_nonce']) : '';
        $email = isset($_POST['lookup_email']) ? sanitize_email($_POST['lookup_email']) : '';
        $phone = isset($_POST['lookup_phone']) ? sanitize_text_field($_POST['lookup_phone']) : '';

        if (!wp_verify_nonce($nonce, 'ckl_lookup_booking')) {
            $message = __('Security verification failed. Please try again.', 'ckl-car-rental');
            $message_type = 'error';
        } elseif (empty($email)) {
            $message = __('Please enter your email address.', 'ckl-car-rental');
            $message_type = 'error';
        } elseif (!is_email($email)) {
            $message = __('Please enter a valid email address.', 'ckl-car-rental');
            $message_type = 'error';
        } else {
            // Look up bookings
            $bookings = ckl_get_guest_bookings($email, $phone, array('limit' => -1));

            if (empty($bookings)) {
                $message = __('No bookings found for this email address. Please check and try again.', 'ckl-car-rental');
                $message_type = 'info';
            } else {
                $message = sprintf(__('Found %d booking(s) for this email address.', 'ckl-car-rental'), count($bookings));
                $message_type = 'success';
            }
        }
    }

    ob_start();
    ?>

    <div class="ckl-guest-booking-lookup">
        <div class="ckl-lookup-form-container">
            <h2><?php _e('Find Your Booking', 'ckl-car-rental'); ?></h2>
            <p class="description"><?php _e('Enter your booking details to view your reservation.', 'ckl-car-rental'); ?></p>

            <?php if (!empty($message)): ?>
                <div class="woocommerce-message <?php echo $message_type === 'error' ? 'woocommerce-error' : ($message_type === 'info' ? 'woocommerce-info' : 'woocommerce-message'); ?>">
                    <?php echo esc_html($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" class="ckl-lookup-form">
                <?php wp_nonce_field('ckl_lookup_booking', 'lookup_nonce'); ?>
                <input type="hidden" name="ckl_lookup_booking" value="1">

                <div class="form-row">
                    <label for="lookup_email"><?php _e('Email Address', 'ckl-car-rental'); ?> <span class="required">*</span></label>
                    <input type="email" name="lookup_email" id="lookup_email" required
                           class="input-text"
                           placeholder="<?php _e('Enter your email address', 'ckl-car-rental'); ?>"
                           value="<?php echo isset($_POST['lookup_email']) ? esc_attr($_POST['lookup_email']) : ''; ?>">
                </div>

                <div class="form-row">
                    <label for="lookup_phone"><?php _e('Phone Number', 'ckl-car-rental'); ?></label>
                    <input type="tel" name="lookup_phone" id="lookup_phone"
                           class="input-text"
                           placeholder="<?php _e('Enter your phone number (optional)', 'ckl-car-rental'); ?>"
                           value="<?php echo isset($_POST['lookup_phone']) ? esc_attr($_POST['lookup_phone']) : ''; ?>">
                    <small class="description"><?php _e('Optional: Adding your phone number helps us find your booking more accurately.', 'ckl-car-rental'); ?></small>
                </div>

                <button type="submit" class="button alt">
                    <?php _e('Find My Booking', 'ckl-car-rental'); ?>
                </button>
            </form>
        </div>

        <?php if (!empty($bookings)): ?>
            <div class="ckl-guest-bookings-list">
                <h3><?php _e('Your Bookings', 'ckl-car-rental'); ?></h3>

                <?php foreach ($bookings as $booking): ?>
                    <?php
                    $booking_details = ckl_get_booking_details($booking->get_id());
                    if (!$booking_details) {
                        continue;
                    }
                    ?>

                    <div class="ckl-booking-card">
                        <div class="ckl-booking-card-header">
                            <h4>
                                <?php echo esc_html($booking_details['vehicle_name']); ?>
                            </h4>
                            <span class="ckl-booking-status <?php echo esc_attr(ckl_get_booking_status_color($booking->get_status())); ?>">
                                <?php echo esc_html(ckl_get_booking_status_label($booking->get_status())); ?>
                            </span>
                        </div>

                        <div class="ckl-booking-card-body">
                            <div class="ckl-booking-info">
                                <div class="ckl-booking-info-item">
                                    <span class="label"><?php _e('Booking ID:', 'ckl-car-rental'); ?></span>
                                    <span class="value">#<?php echo esc_html($booking->get_id()); ?></span>
                                </div>
                                <div class="ckl-booking-info-item">
                                    <span class="label"><?php _e('Pick-up:', 'ckl-car-rental'); ?></span>
                                    <span class="value">
                                        <?php
                                        echo esc_html(date_i18n(get_option('date_format'), strtotime($booking_details['pickup_date'])));
                                        echo ' at ' . esc_html($booking_details['pickup_time']);
                                        ?>
                                    </span>
                                </div>
                                <div class="ckl-booking-info-item">
                                    <span class="label"><?php _e('Return:', 'ckl-car-rental'); ?></span>
                                    <span class="value">
                                        <?php
                                        echo esc_html(date_i18n(get_option('date_format'), strtotime($booking_details['return_date'])));
                                        echo ' at ' . esc_html($booking_details['return_time']);
                                        ?>
                                    </span>
                                </div>
                                <div class="ckl-booking-info-item">
                                    <span class="label"><?php _e('Total:', 'ckl-car-rental'); ?></span>
                                    <span class="value">
                                        <?php echo wc_price($booking_details['total_price']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .ckl-guest-booking-lookup {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .ckl-lookup-form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .ckl-lookup-form h2 {
            margin: 0 0 10px;
            font-size: 24px;
        }
        .ckl-lookup-form .description {
            color: #666;
            margin-bottom: 20px;
        }
        .ckl-lookup-form .form-row {
            margin-bottom: 15px;
        }
        .ckl-lookup-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .ckl-lookup-form input[type="email"],
        .ckl-lookup-form input[type="tel"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .ckl-lookup-form small {
            display: block;
            margin-top: 5px;
            color: #888;
            font-size: 13px;
        }
        .ckl-lookup-form button {
            background: #0073aa;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .ckl-lookup-form button:hover {
            background: #005177;
        }
        .ckl-guest-bookings-list h3 {
            margin-bottom: 20px;
        }
        .ckl-booking-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .ckl-booking-card-header {
            padding: 15px 20px;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ckl-booking-card-header h4 {
            margin: 0;
            font-size: 18px;
        }
        .ckl-booking-status {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .ckl-booking-card-body {
            padding: 20px;
        }
        .ckl-booking-info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .ckl-booking-info-item:last-child {
            border-bottom: none;
        }
        .ckl-booking-info-item .label {
            font-weight: 600;
            color: #555;
        }
        .ckl-booking-info-item .value {
            color: #333;
        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('ckl_guest_booking_lookup', 'ckl_guest_booking_lookup_shortcode');
