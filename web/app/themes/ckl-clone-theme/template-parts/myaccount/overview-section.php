<?php
/**
 * My Account Overview Section
 *
 * Displays recent bookings on dashboard
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var array $recent_bookings Recent bookings array
 */

defined('ABSPATH') || exit;

if (!isset($recent_bookings)) {
    $recent_bookings = array();
}

// Filter only valid bookings
$valid_bookings = array_filter($recent_bookings, function($booking) {
    return $booking instanceof WC_Booking;
});
?>

<!-- Recent Bookings Section -->
<div class="ckl-recent-bookings">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">
            <?php esc_html_e('Recent Bookings', 'ckl-car-rental'); ?>
        </h2>
        <?php if (!empty($valid_bookings)) : ?>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('bookings')); ?>" class="text-[#cc2e28] hover:text-[#a8241f] font-medium text-sm flex items-center gap-1">
                <?php esc_html_e('View All', 'ckl-car-rental'); ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($valid_bookings)) : ?>
        <div class="grid grid-cols-1 gap-4">
            <?php
            foreach ($valid_bookings as $booking) :
                $vehicle_id = get_post_meta($booking->get_id(), '_booking_vehicle_id', true);
                $vehicle_post = $vehicle_id ? get_post($vehicle_id) : null;
                $vehicle_name = $vehicle_post ? $vehicle_post->post_title : __('Vehicle', 'ckl-car-rental');
                $vehicle_image = $vehicle_id ? get_the_post_thumbnail_url($vehicle_id, 'medium') : '';

                $start_date = $booking->get_start_date();
                $end_date = $booking->get_end_date();
                $status = $booking->get_status();
                $order = wc_get_order($booking->get_order_id());
                $total = $order ? $order->get_total() : 0;

                // Format dates
                $start_formatted = date_i18n(get_option('date_format'), strtotime($start_date));
                $end_formatted = date_i18n(get_option('date_format'), strtotime($end_date));

                // Get status label and color
                $status_label = ckl_get_booking_status_label($status);
                $status_color = ckl_get_booking_status_color($status);
                ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Vehicle Image -->
                        <div class="sm:w-32 sm:h-24 flex-shrink-0">
                            <?php if ($vehicle_image) : ?>
                                <img src="<?php echo esc_url($vehicle_image); ?>" alt="<?php echo esc_attr($vehicle_name); ?>" class="w-full h-full object-cover rounded-lg">
                            <?php else : ?>
                                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Booking Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg"><?php echo esc_html($vehicle_name); ?></h3>
                                    <p class="text-sm text-gray-500">
                                        <?php esc_html_e('Booking', 'ckl-car-rental'); ?> #<?php echo esc_html($booking->get_id()); ?>
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo esc_attr($status_color); ?>">
                                    <?php echo esc_html($status_label); ?>
                                </span>
                            </div>

                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><?php echo esc_html($start_formatted); ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span><?php echo esc_html($end_formatted); ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold"><?php echo wc_price($total); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="sm:w-32 flex sm:flex-col gap-2 sm:justify-center">
                            <a href="<?php echo esc_url(add_query_arg('booking_id', $booking->get_id(), wc_get_account_endpoint_url('booking-details'))); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-[#cc2e28] text-white rounded-lg text-sm font-medium hover:bg-[#a8241f] transition-colors">
                                <?php esc_html_e('View', 'ckl-car-rental'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <?php esc_html_e('No bookings yet', 'ckl-car-rental'); ?>
            </h3>
            <p class="text-gray-600 mb-4">
                <?php esc_html_e('You haven\'t made any bookings yet. Start by browsing our available vehicles.', 'ckl-car-rental'); ?>
            </p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-[#cc2e28] text-white rounded-lg font-medium hover:bg-[#a8241f] transition-colors">
                <?php esc_html_e('Browse Vehicles', 'ckl-car-rental'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
