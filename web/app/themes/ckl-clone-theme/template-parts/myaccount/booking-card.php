<?php
/**
 * My Account Booking Card
 *
 * Single booking card component
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var WC_Booking $booking Booking object
 */

defined('ABSPATH') || exit;

if (!isset($booking) || !$booking instanceof WC_Booking) {
    return;
}

$booking_id = $booking->get_id();
$vehicle_id = get_post_meta($booking_id, '_booking_vehicle_id', true);
$vehicle_post = $vehicle_id ? get_post($vehicle_id) : null;
$vehicle_name = $vehicle_post ? $vehicle_post->post_title : __('Vehicle', 'ckl-car-rental');
$vehicle_image = $vehicle_id ? get_the_post_thumbnail_url($vehicle_id, 'medium') : '';
$vehicle_url = $vehicle_id ? get_permalink($vehicle_id) : '';

$start_date = $booking->get_start_date();
$end_date = $booking->get_end_date();
$status = $booking->get_status();

$order = wc_get_order($booking->get_order_id());
$total = $order ? $order->get_total() : 0;

$pickup_location = get_post_meta($booking_id, '_pickup_location', true);
$return_location = get_post_meta($booking_id, '_return_location', true);

// Format dates
$start_formatted = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($start_date));
$end_formatted = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($end_date));

// Get status label and color
$status_label = ckl_get_booking_status_label($status);
$status_color = ckl_get_booking_status_color($status);

// Check if booking can be cancelled
$can_cancel = $booking && in_array($status, array('pending-confirmation', 'confirmed', 'paid'));
$can_modify = $booking && in_array($status, array('confirmed', 'paid'));
?>

<div class="ckl-booking-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
    <div class="flex flex-col lg:flex-row">
        <!-- Vehicle Image -->
        <div class="lg:w-72 lg:h-48 flex-shrink-0">
            <?php if ($vehicle_image) : ?>
                <a href="<?php echo esc_url($vehicle_url); ?>" class="block w-full h-full">
                    <img src="<?php echo esc_url($vehicle_image); ?>" alt="<?php echo esc_attr($vehicle_name); ?>" class="w-full h-full object-cover">
                </a>
            <?php else : ?>
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <!-- Booking Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                <div class="min-w-0 flex-1">
                    <a href="<?php echo esc_url(add_query_arg('booking_id', $booking_id, wc_get_account_endpoint_url('booking-details'))); ?>" class="block">
                        <h3 class="text-xl font-bold text-gray-900 hover:text-[#cc2e28] transition-colors mb-1">
                            <?php echo esc_html($vehicle_name); ?>
                        </h3>
                    </a>
                    <p class="text-sm text-gray-500">
                        <?php esc_html_e('Booking', 'ckl-car-rental'); ?> #<?php echo esc_html($booking_id); ?>
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold <?php echo esc_attr($status_color); ?>">
                    <?php echo esc_html($status_label); ?>
                </span>
            </div>

            <!-- Booking Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Pickup -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 mb-1"><?php esc_html_e('Pickup', 'ckl-car-rental'); ?></p>
                        <p class="font-medium text-gray-900"><?php echo esc_html($start_formatted); ?></p>
                        <?php if ($pickup_location) : ?>
                            <p class="text-sm text-gray-600 truncate"><?php echo esc_html($pickup_location); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Return -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 mb-1"><?php esc_html_e('Return', 'ckl-car-rental'); ?></p>
                        <p class="font-medium text-gray-900"><?php echo esc_html($end_formatted); ?></p>
                        <?php if ($return_location) : ?>
                            <p class="text-sm text-gray-600 truncate"><?php echo esc_html($return_location); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <!-- Total -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500"><?php esc_html_e('Total', 'ckl-car-rental'); ?>:</span>
                    <span class="text-2xl font-bold text-[#cc2e28]"><?php echo wc_price($total); ?></span>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="<?php echo esc_url(add_query_arg('booking_id', $booking_id, wc_get_account_endpoint_url('booking-details'))); ?>"
                       class="inline-flex items-center justify-center px-4 py-2 bg-[#cc2e28] text-white rounded-lg text-sm font-medium hover:bg-[#a8241f] transition-colors">
                        <?php esc_html_e('View Details', 'ckl-car-rental'); ?>
                    </a>

                    <?php if ($can_cancel) : ?>
                        <button type="button"
                                class="ckl-cancel-booking-btn inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors"
                                data-booking-id="<?php echo esc_attr($booking_id); ?>"
                                data-nonce="<?php echo esc_attr(wp_create_nonce('ckl_cancel_booking_' . $booking_id)); ?>">
                            <?php esc_html_e('Cancel', 'ckl-car-rental'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
