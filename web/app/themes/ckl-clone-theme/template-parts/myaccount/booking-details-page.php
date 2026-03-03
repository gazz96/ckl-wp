<?php
/**
 * My Account Booking Details Page Content
 *
 * Detailed view of single booking
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var array $booking_data Booking details array
 * @var WC_Booking $booking Booking object
 * @var WC_Order $order Order object
 */

defined('ABSPATH') || exit;

if (!isset($booking_data)) {
    return;
}

$booking_id = $booking_data['booking_id'];
$vehicle_id = $booking_data['vehicle_id'];
$order = isset($order) ? $order : null;

// Get vehicle features
$vehicle_features = array();
if ($vehicle_id) {
    $feature_terms = wp_get_object_terms($vehicle_id, 'vehicle_feature');
    foreach ($feature_terms as $feature) {
        $vehicle_features[] = $feature->name;
    }
}

// Get pricing breakdown
$order_items = $order ? $order->get_items() : array();
$subtotal = $order ? $order->get_subtotal() : 0;
$tax = $order ? $order->get_total_tax() : 0;
$total = $booking_data['total'];

// Get late fees
$late_fee_hours = get_post_meta($booking_id, '_late_fee_hours', true);
$late_fee_amount = get_post_meta($booking_id, '_late_fee_amount', true);
$actual_return_time = get_post_meta($booking_id, '_actual_return_time', true);

// Check if cancellable
$status = $booking_data['status'];
$can_cancel = in_array($status, array('pending-confirmation', 'confirmed', 'paid'));
?>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Vehicle Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
                <?php esc_html_e('Vehicle Information', 'ckl-car-rental'); ?>
            </h2>

            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Vehicle Image -->
                <div class="sm:w-48 flex-shrink-0">
                    <?php if ($booking_data['vehicle_image']) : ?>
                        <img src="<?php echo esc_url($booking_data['vehicle_image']); ?>" alt="<?php echo esc_attr($booking_data['vehicle_name']); ?>" class="w-full h-auto rounded-lg">
                    <?php else : ?>
                        <div class="w-full aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Vehicle Details -->
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        <?php echo esc_html($booking_data['vehicle_name']); ?>
                    </h3>

                    <?php if (!empty($vehicle_features)) : ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($vehicle_features as $feature) : ?>
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    <?php echo esc_html($feature); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo esc_url(get_permalink($vehicle_id)); ?>" class="inline-flex items-center gap-1 text-[#cc2e28] hover:text-[#a8241f] font-medium text-sm">
                        <?php esc_html_e('View Vehicle', 'ckl-car-rental'); ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Rental Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <?php esc_html_e('Rental Details', 'ckl-car-rental'); ?>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pickup -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1"><?php esc_html_e('Pickup', 'ckl-car-rental'); ?></p>
                        <p class="font-semibold text-gray-900"><?php echo esc_html($booking_data['start_date_formatted']); ?></p>
                        <?php if ($booking_data['pickup_location']) : ?>
                            <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($booking_data['pickup_location']); ?></p>
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
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1"><?php esc_html_e('Return', 'ckl-car-rental'); ?></p>
                        <p class="font-semibold text-gray-900"><?php echo esc_html($booking_data['end_date_formatted']); ?></p>
                        <?php if ($booking_data['return_location']) : ?>
                            <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($booking_data['return_location']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Duration -->
                <?php if ($booking_data['duration_days'] || $booking_data['duration_hours']) : ?>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1"><?php esc_html_e('Duration', 'ckl-car-rental'); ?></p>
                        <p class="font-semibold text-gray-900">
                            <?php
                            if ($booking_data['duration_days']) {
                                printf(esc_html__('%s days', 'ckl-car-rental'), esc_html($booking_data['duration_days']));
                            }
                            if ($booking_data['duration_hours']) {
                                echo ' ' . sprintf(esc_html__('%s hours', 'ckl-car-rental'), esc_html($booking_data['duration_hours']));
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Late Fee Info -->
                <?php if ($late_fee_hours && $late_fee_amount) : ?>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1"><?php esc_html_e('Late Return', 'ckl-car-rental'); ?></p>
                        <p class="font-semibold text-yellow-600">
                            <?php printf(esc_html__('%s hours late', 'ckl-car-rental'), esc_html($late_fee_hours)); ?>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php esc_html_e('Additional fee:', 'ckl-car-rental'); ?> <?php echo wc_price($late_fee_amount); ?>
                        </p>
                        <?php if ($actual_return_time) : ?>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php esc_html_e('Actual return:', 'ckl-car-rental'); ?> <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($actual_return_time))); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Booking Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <?php
            /**
             * Booking timeline showing progression of events
             */
            wc_get_template_part('template-parts/myaccount/booking-timeline', null, array(
                'booking_id' => $booking_id,
            ));
            ?>
        </div>

    </div>

    <!-- Right Column - Pricing & Actions -->
    <div class="space-y-6">

        <!-- Pricing Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <?php esc_html_e('Pricing', 'ckl-car-rental'); ?>
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600"><?php esc_html_e('Subtotal', 'ckl-car-rental'); ?></span>
                    <span class="text-gray-900"><?php echo wc_price($subtotal); ?></span>
                </div>

                <?php if ($tax > 0) : ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600"><?php esc_html_e('Tax', 'ckl-car-rental'); ?></span>
                    <span class="text-gray-900"><?php echo wc_price($tax); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($late_fee_amount > 0) : ?>
                <div class="flex justify-between text-sm">
                    <span class="text-yellow-600"><?php esc_html_e('Late Fee', 'ckl-car-rental'); ?></span>
                    <span class="text-yellow-600"><?php echo wc_price($late_fee_amount); ?></span>
                </div>
                <?php endif; ?>

                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-900"><?php esc_html_e('Total', 'ckl-car-rental'); ?></span>
                        <span class="text-xl font-bold text-[#cc2e28]"><?php echo wc_price($total); ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <?php if ($order) : ?>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600"><?php esc_html_e('Payment', 'ckl-car-rental'); ?></span>
                    <?php
                    $payment_status_html = wc_get_order_status_name($payment_status);
                    $payment_class = 'text-green-600';
                    if (in_array($payment_status, array('pending', 'on-hold'))) {
                        $payment_class = 'text-yellow-600';
                    } elseif (in_array($payment_status, array('cancelled', 'refunded', 'failed'))) {
                        $payment_class = 'text-red-600';
                    }
                    ?>
                    <span class="text-sm font-semibold <?php echo esc_attr($payment_class); ?>">
                        <?php echo esc_html($payment_status_html); ?>
                    </span>
                </div>
                <?php if ($payment_method) : ?>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-sm text-gray-600"><?php esc_html_e('Method', 'ckl-car-rental'); ?></span>
                    <span class="text-sm text-gray-900"><?php echo esc_html($payment_method); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php
        /**
         * Display QR Code after pricing summary
         *
         * Hook allows QR code display on booking details page.
         *
         * @param array $booking_data Booking details array
         */
        do_action('ckl_booking_details_after_pricing', $booking_data);
        ?>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4"><?php esc_html_e('Actions', 'ckl-car-rental'); ?></h2>

            <div class="space-y-3">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('bookings')); ?>" class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    <?php esc_html_e('Back to Bookings', 'ckl-car-rental'); ?>
                </a>

                <?php if ($order) : ?>
                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    <?php esc_html_e('View Order', 'ckl-car-rental'); ?>
                </a>
                <?php endif; ?>

                <?php if ($can_cancel) : ?>
                <button type="button"
                        class="ckl-cancel-booking-btn w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors"
                        data-booking-id="<?php echo esc_attr($booking_id); ?>"
                        data-nonce="<?php echo esc_attr(wp_create_nonce('ckl_cancel_booking_' . $booking_id)); ?>">
                    <?php esc_html_e('Cancel Booking', 'ckl-car-rental'); ?>
                </button>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
