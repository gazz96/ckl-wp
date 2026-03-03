<?php
/**
 * My Account Dashboard
 *
 * Custom dashboard overview for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get customer's first name or username
$first_name = $current_user->first_name;
$display_name = $first_name ? $first_name : ($current_user->display_name ?: $current_user->user_login);

// Get greeting based on time
$hour = date('H');
if ($hour < 12) {
    $greeting = __('Good morning', 'ckl-car-rental');
} elseif ($hour < 18) {
    $greeting = __('Good afternoon', 'ckl-car-rental');
} else {
    $greeting = __('Good evening', 'ckl-car-rental');
}

// Get booking statistics
$booking_stats = ckl_get_customer_booking_stats($customer_id);

// Get recent bookings
$recent_bookings = ckl_get_customer_bookings($customer_id, array('limit' => 3));

do_action('woocommerce_account_dashboard_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <?php echo esc_html($greeting); ?>, <?php echo esc_html($display_name); ?>!
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('Welcome to your CKL Car Rental dashboard. Manage your bookings and profile from here.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php do_action('woocommerce_account_dashboard_content_before', $customer_id); ?>

<!-- Statistics Cards -->
<div class="ckl-stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Active Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Active Bookings', 'ckl-car-rental'); ?>
                </p>
                <p class="text-3xl font-bold text-[#cc2e28]">
                    <?php echo esc_html($booking_stats['active']); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Completed Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Completed Rentals', 'ckl-car-rental'); ?>
                </p>
                <p class="text-3xl font-bold text-green-600">
                    <?php echo esc_html($booking_stats['completed']); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Spent -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Total Spent', 'ckl-car-rental'); ?>
                </p>
                <p class="text-3xl font-bold text-blue-600">
                    <?php echo wc_price($booking_stats['total_spent']); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Upcoming Pickup -->
    <?php if ($booking_stats['next_pickup']) : ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Next Pickup', 'ckl-car-rental'); ?>
                </p>
                <p class="text-lg font-bold text-purple-600">
                    <?php echo esc_html($booking_stats['next_pickup']); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Next Pickup', 'ckl-car-rental'); ?>
                </p>
                <p class="text-lg font-bold text-gray-400">
                    <?php esc_html_e('No upcoming', 'ckl-car-rental'); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="ckl-quick-actions mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-4">
        <?php esc_html_e('Quick Actions', 'ckl-car-rental'); ?>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-[#cc2e28] transition-all duration-200">
            <div class="w-12 h-12 bg-[#cc2e28] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900"><?php esc_html_e('Book a Vehicle', 'ckl-car-rental'); ?></h3>
                <p class="text-sm text-gray-500"><?php esc_html_e('Start a new booking', 'ckl-car-rental'); ?></p>
            </div>
        </a>

        <a href="<?php echo esc_url(wc_get_account_endpoint_url('bookings')); ?>" class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-[#cc2e28] transition-all duration-200">
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900"><?php esc_html_e('View All Bookings', 'ckl-car-rental'); ?></h3>
                <p class="text-sm text-gray-500"><?php esc_html_e('Manage your rentals', 'ckl-car-rental'); ?></p>
            </div>
        </a>

        <a href="<?php echo esc_url(wc_get_account_endpoint_url('support')); ?>" class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-[#cc2e28] transition-all duration-200">
            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900"><?php esc_html_e('Get Support', 'ckl-car-rental'); ?></h3>
                <p class="text-sm text-gray-500"><?php esc_html_e('Contact our team', 'ckl-car-rental'); ?></p>
            </div>
        </a>
    </div>
</div>

<?php
/**
 * Overview section with recent bookings
 */
wc_get_template_part('template-parts/myaccount/overview-section', '', array('recent_bookings' => $recent_bookings));
?>

<?php do_action('woocommerce_account_dashboard_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_dashboard_after', $customer_id); ?>
