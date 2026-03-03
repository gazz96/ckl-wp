<?php
/**
 * Booking Timeline Component
 *
 * Displays a vertical timeline of booking events showing the progression
 * from booking creation through completion, including any late fees.
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}

$booking_id = $args['booking_id'] ?? get_the_ID();
$booking = get_wc_booking($booking_id);

if (!$booking) {
    return;
}

// Get booking dates
$created_date = $booking->get_date_created();
$start_date = $booking->get_start_date();
$end_date = $booking->get_end_date();

// Get custom meta
$actual_return_time = get_post_meta($booking_id, '_actual_return_time', true);
$late_fee_hours = get_post_meta($booking_id, '_late_fee_hours', true);
$late_fee_amount = get_post_meta($booking_id, '_late_fee_amount', true);

// Get booking status
$booking_status = $booking->get_status();

// Determine timeline events based on booking status
$timeline_events = array();

// Event 1: Booking Created
$timeline_events[] = array(
    'id' => 'created',
    'title' => __('Booking Created', 'ckl-car-rental'),
    'description' => __('Booking request submitted', 'ckl-car-rental'),
    'icon' => 'calendar-plus',
    'status' => 'completed', // Always completed
    'datetime' => $created_date,
    'datetime_formatted' => $created_date->date_i18n(get_option('date_format') . ' ' . get_option('time_format')),
);

// Event 2: Payment Status
$order = $booking->get_order();
$payment_status = 'pending';
if ($order) {
    $payment_status = $order->get_status();
}

$payment_completed = in_array($payment_status, array('completed', 'processing'));
$timeline_events[] = array(
    'id' => 'payment',
    'title' => __('Payment Confirmed', 'ckl-car-rental'),
    'description' => $payment_completed
        ? __('Payment received successfully', 'ckl-car-rental')
        : __('Waiting for payment', 'ckl-car-rental'),
    'icon' => 'credit-card',
    'status' => $payment_completed ? 'completed' : 'pending',
    'datetime' => $payment_completed ? $order->get_date_paid() : null,
    'datetime_formatted' => $payment_completed && $order->get_date_paid()
        ? $order->get_date_paid()->date_i18n(get_option('date_format') . ' ' . get_option('time_format'))
        : '',
);

// Event 3: Booking Confirmed
$confirmed_statuses = array('confirmed', 'paid', 'complete', 'in-progress');
$is_confirmed = in_array($booking_status, $confirmed_statuses);

$timeline_events[] = array(
    'id' => 'confirmed',
    'title' => __('Booking Confirmed', 'ckl-car-rental'),
    'description' => $is_confirmed
        ? __('Your booking has been confirmed', 'ckl-car-rental')
        : __('Awaiting confirmation', 'ckl-car-rental'),
    'icon' => 'check-circle',
    'status' => $is_confirmed ? 'completed' : 'pending',
    'datetime' => null, // WooCommerce Bookings doesn't track confirmation date
    'datetime_formatted' => $is_confirmed ? __('Confirmed', 'ckl-car-rental') : '',
);

// Event 4: Vehicle Picked Up
$is_in_progress = in_array($booking_status, array('in-progress', 'complete'));
$timeline_events[] = array(
    'id' => 'picked-up',
    'title' => __('Vehicle Picked Up', 'ckl-car-rental'),
    'description' => $is_in_progress
        ? __('Vehicle collected from pickup location', 'ckl-car-rental')
        : __('Scheduled pickup', 'ckl-car-rental'),
    'icon' => 'car',
    'status' => $is_in_progress ? 'completed' : 'pending',
    'datetime' => $is_in_progress ? $start_date : null,
    'datetime_formatted' => $is_in_progress
        ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($start_date))
        : __('Scheduled: ', 'ckl-car-rental') . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($start_date)),
);

// Event 5: Vehicle Returned
$is_completed = $booking_status === 'complete';
$timeline_events[] = array(
    'id' => 'returned',
    'title' => __('Vehicle Returned', 'ckl-car-rental'),
    'description' => $is_completed
        ? __('Vehicle returned to location', 'ckl-car-rental')
        : __('Expected return', 'ckl-car-rental'),
    'icon' => 'flag-checkered',
    'status' => $is_completed ? 'completed' : 'pending',
    'datetime' => $is_completed && $actual_return_time ? $actual_return_time : ($is_completed ? $end_date : null),
    'datetime_formatted' => $is_completed
        ? ($actual_return_time
            ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($actual_return_time))
            : date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($end_date)))
        : __('Expected: ', 'ckl-car-rental') . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($end_date)),
);

// Event 6: Late Fee (if applicable)
if ($late_fee_hours && $late_fee_hours > 0 && floatval($late_fee_amount) > 0) {
    $timeline_events[] = array(
        'id' => 'late-fee',
        'title' => __('Late Fee Applied', 'ckl-car-rental'),
        'description' => sprintf(
            __('Vehicle returned %d hours late. Additional charges applied.', 'ckl-car-rental'),
            $late_fee_hours
        ),
        'icon' => 'clock',
        'status' => 'warning',
        'datetime' => $actual_return_time,
        'datetime_formatted' => $actual_return_time
            ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($actual_return_time))
            : '',
        'extra_info' => array(
            'hours_late' => $late_fee_hours,
            'fee_amount' => wc_price($late_fee_amount),
        ),
    );
}
?>

<div class="ckl-booking-timeline">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <?php esc_html_e('Booking Timeline', 'ckl-car-rental'); ?>
    </h3>

    <div class="relative">
        <!-- Vertical Line -->
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

        <!-- Timeline Events -->
        <div class="space-y-6">
            <?php foreach ($timeline_events as $index => $event) :
                $is_last = $index === count($timeline_events) - 1;

                // Determine status styling
                $status_classes = array(
                    'completed' => 'bg-green-100 text-green-600 border-green-200',
                    'pending' => 'bg-gray-100 text-gray-400 border-gray-200',
                    'warning' => 'bg-red-100 text-red-600 border-red-200',
                    'active' => 'bg-blue-100 text-blue-600 border-blue-200',
                );

                $status_class = $status_classes[$event['status']] ?? $status_classes['pending'];

                // Icon SVG mapping
                $icons = array(
                    'calendar-plus' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                    'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'car' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
                    'flag-checkered' => 'M3 21v-8a2 2 0 012-2h10a2 2 0 012 2v3 M3 21h18 M5 11l7-7 7 7M5 11l7-7 7 7',
                    'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                );

                $icon_path = $icons[$event['icon']] ?? $icons['check-circle'];
            ?>

                <div class="relative flex items-start pl-12">
                    <!-- Icon -->
                    <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full border-2 <?php echo esc_attr($status_class); ?> bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr($icon_path); ?>"></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 pb-6 <?php echo $is_last ? '' : 'border-b border-gray-100'; ?>">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">
                                    <?php echo esc_html($event['title']); ?>
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    <?php echo esc_html($event['description']); ?>
                                </p>

                                <?php if (!empty($event['datetime_formatted'])) : ?>
                                    <div class="mt-2 flex items-center text-xs text-gray-400">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo esc_html($event['datetime_formatted']); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($event['extra_info'])) : ?>
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <?php if (!empty($event['extra_info']['hours_late'])) : ?>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">
                                                    <?php esc_html_e('Hours Late:', 'ckl-car-rental'); ?>
                                                </span>
                                                <span class="font-medium text-red-600">
                                                    <?php echo esc_html($event['extra_info']['hours_late']); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($event['extra_info']['fee_amount'])) : ?>
                                            <div class="flex items-center justify-between text-sm mt-2">
                                                <span class="text-gray-600">
                                                    <?php esc_html_e('Late Fee:', 'ckl-car-rental'); ?>
                                                </span>
                                                <span class="font-bold text-red-600">
                                                    <?php echo wp_kses_post($event['extra_info']['fee_amount']); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Status Badge -->
                            <?php if ($event['status'] === 'completed') : ?>
                                <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php esc_html_e('Completed', 'ckl-car-rental'); ?>
                                </span>
                            <?php elseif ($event['status'] === 'warning') : ?>
                                <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <?php esc_html_e('Late', 'ckl-car-rental'); ?>
                                </span>
                            <?php elseif ($event['status'] === 'active') : ?>
                                <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php esc_html_e('In Progress', 'ckl-car-rental'); ?>
                                </span>
                            <?php else : ?>
                                <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php esc_html_e('Pending', 'ckl-car-rental'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .ckl-booking-timeline {
        /* Additional custom styles if needed */
    }
</style>
