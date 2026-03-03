<?php
/**
 * My Account Booking Details
 *
 * Single booking details page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var int $booking_id Booking ID
 */

defined('ABSPATH') || exit;

if (!isset($booking_id) || !$booking_id) {
    echo '<div class="woocommerce-error">' . esc_html__('Booking not found.', 'ckl-car-rental') . '</div>';
    return;
}

// Get booking details
$booking_data = ckl_get_booking_details($booking_id);

if (!$booking_data) {
    echo '<div class="woocommerce-error">' . esc_html__('Booking not found.', 'ckl-car-rental') . '</div>';
    return;
}

// Verify ownership
if (!ckl_verify_booking_ownership($booking_id, get_current_user_id())) {
    echo '<div class="woocommerce-error">' . esc_html__('You do not have permission to view this booking.', 'ckl-car-rental') . '</div>';
    return;
}

$booking = get_wc_booking($booking_id);
$status = $booking_data['status'];
$status_label = ckl_get_booking_status_label($status);
$status_color = ckl_get_booking_status_color($status);

$vehicle_post = $booking_data['vehicle_id'] ? get_post($booking_data['vehicle_id']) : null;
$vehicle_gallery = $booking_data['vehicle_id'] ? get_post_meta($booking_data['vehicle_id'], '_vehicle_image_gallery', true) : '';
$gallery_ids = $vehicle_gallery ? explode(',', $vehicle_gallery) : array();

// Get additional booking meta
$late_fee_hours = get_post_meta($booking_id, '_late_fee_hours', true);
$late_fee_amount = get_post_meta($booking_id, '_late_fee_amount', true);
$actual_return_time = get_post_meta($booking_id, '_actual_return_time', true);

// Get order details
$order = $booking_data['order'];
$payment_method = $order ? $order->get_payment_method_title() : '';
$payment_status = $order ? $order->get_status() : '';

do_action('woocommerce_account_booking-details_before', $booking_id);
?>

<!-- Breadcrumb -->
<nav class="ckl-breadcrumb flex mb-6 text-sm">
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('bookings')); ?>" class="text-gray-500 hover:text-[#cc2e28]">
        <?php esc_html_e('Bookings', 'ckl-car-rental'); ?>
    </a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">
        <?php esc_html_e('Booking Details', 'ckl-car-rental'); ?>
    </span>
</nav>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                <?php echo esc_html($booking_data['vehicle_name']); ?>
            </h1>
            <p class="text-gray-600">
                <?php esc_html_e('Booking', 'ckl-car-rental'); ?> #<?php echo esc_html($booking_id); ?>
            </p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold <?php echo esc_attr($status_color); ?>">
            <?php echo esc_html($status_label); ?>
        </span>
    </div>
</div>

<?php do_action('woocommerce_account_booking-details_content_before', $booking_id); ?>

<?php
/**
 * Booking details content
 */
wc_get_template_part('template-parts/myaccount/booking-details', 'page', array(
    'booking_data' => $booking_data,
    'booking' => $booking,
    'order' => $order,
));
?>

<?php do_action('woocommerce_account_booking-details_content_after', $booking_id); ?>

<?php do_action('woocommerce_account_booking-details_after', $booking_id); ?>
