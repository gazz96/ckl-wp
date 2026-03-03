<?php
/**
 * Booking Confirmed Email Template
 *
 * Custom template for booking confirmation emails.
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php /* translators: %s: Customer username */ ?>
<p><?php printf(__('Hi %s,', 'ckl-car-rental'), esc_html($order->get_billing_first_name())); ?></p>

<p><?php _e('Your booking has been confirmed! We\'re excited to serve you.', 'ckl-car-rental'); ?></p>

<?php
/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
?>

<h2><?php _e('Booking Details', 'ckl-car-rental'); ?></h2>

<?php
// Get booking details
$booking_id = $order->get_meta('_booking_id');
if ($booking_id) {
    $booking = get_wc_booking($booking_id);

    if ($booking) :
        $product = $booking->get_product();
        $start_date = $booking->get_start_date();
        $end_date = $booking->get_end_date();
        $pickup_location = get_post_meta($booking_id, '_pickup_location', true);
        $return_location = get_post_meta($booking_id, '_return_location', true);
?>
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong><?php _e('Vehicle:', 'ckl-car-rental'); ?></strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <?php echo esc_html($product->get_name()); ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong><?php _e('Pickup Date:', 'ckl-car-rental'); ?></strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <?php echo esc_html($start_date); ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong><?php _e('Return Date:', 'ckl-car-rental'); ?></strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <?php echo esc_html($end_date); ?>
                </td>
            </tr>
            <?php if ($pickup_location) : ?>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <strong><?php _e('Pickup Location:', 'ckl-car-rental'); ?></strong>
                    </td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <?php
                        $location = get_term($pickup_location, 'vehicle_location');
                        echo esc_html($location ? $location->name : 'N/A');
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($return_location) : ?>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <strong><?php _e('Return Location:', 'ckl-car-rental'); ?></strong>
                    </td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <?php
                        $location = get_term($return_location, 'vehicle_location');
                        echo esc_html($location ? $location->name : 'N/A');
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
<?php
    endif;
}
?>

<p><?php _e('Please arrive at the pickup location at least 15 minutes before your scheduled pickup time. Don\'t forget to bring:', 'ckl-car-rental'); ?></p>

<ul>
    <li><?php _e('Valid driving license', 'ckl-car-rental'); ?></li>
    <li><?php _e('Identification card (IC) or passport', 'ckl-car-rental'); ?></li>
    <li><?php _e('Booking confirmation (this email or your booking ID)', 'ckl-car-rental'); ?></li>
</ul>

<p><?php _e('If you need to make any changes to your booking, please contact us as soon as possible.', 'ckl-car-rental'); ?></p>

<p><?php _e('We look forward to serving you!', 'ckl-car-rental'); ?></p>

<?php
/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
