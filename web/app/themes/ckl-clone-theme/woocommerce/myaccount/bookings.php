<?php
/**
 * My Account Bookings
 *
 * Custom bookings management page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get current filter from URL
$current_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';

// Build query args based on filter
$query_args = array('limit' => -1);

$status_map = array(
    'active' => array('pending-confirmation', 'confirmed', 'paid', 'in-progress'),
    'completed' => array('complete'),
    'cancelled' => array('cancelled'),
);

if ($current_filter !== 'all' && isset($status_map[$current_filter])) {
    $query_args['status'] = $status_map[$current_filter];
}

// Get bookings
$all_bookings = ckl_get_customer_bookings($customer_id, array('limit' => -1));
$filtered_bookings = ckl_get_customer_bookings($customer_id, $query_args);

// Count by status
$status_counts = array(
    'all' => count($all_bookings),
    'active' => 0,
    'completed' => 0,
    'cancelled' => 0,
);

foreach ($all_bookings as $booking) {
    if ($booking instanceof WC_Booking) {
        $status = $booking->get_status();
        if (in_array($status, array('pending-confirmation', 'confirmed', 'paid', 'in-progress'))) {
            $status_counts['active']++;
        } elseif ($status === 'complete') {
            $status_counts['completed']++;
        } elseif ($status === 'cancelled') {
            $status_counts['cancelled']++;
        }
    }
}

do_action('woocommerce_account_bookings_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        <?php esc_html_e('My Bookings', 'ckl-car-rental'); ?>
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('View and manage all your vehicle rentals.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php do_action('woocommerce_account_bookings_content_before', $customer_id); ?>

<?php
/**
 * Bookings list with tabs and filters
 */
wc_get_template_part('template-parts/myaccount/bookings-list', '', array(
    'filtered_bookings' => $filtered_bookings,
    'current_filter' => $current_filter,
    'status_counts' => $status_counts,
));
?>

<?php do_action('woocommerce_account_bookings_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_bookings_after', $customer_id); ?>
