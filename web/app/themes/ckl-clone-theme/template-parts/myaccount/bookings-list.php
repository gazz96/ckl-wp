<?php
/**
 * My Account Bookings List
 *
 * Displays bookings with tabs and filters
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var array $filtered_bookings Filtered bookings
 * @var string $current_filter Current status filter
 * @var array $status_counts Status counts
 */

defined('ABSPATH') || exit;

if (!isset($filtered_bookings)) {
    $filtered_bookings = array();
}
if (!isset($current_filter)) {
    $current_filter = 'all';
}
if (!isset($status_counts)) {
    $status_counts = array('all' => 0, 'active' => 0, 'completed' => 0, 'cancelled' => 0);
}

$base_url = wc_get_account_endpoint_url('bookings');
?>

<!-- Tabs Navigation -->
<div class="ckl-bookings-tabs mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto gap-2" aria-label="Tabs">
            <a href="<?php echo esc_url($base_url); ?>"
               class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors <?php echo $current_filter === 'all' ? 'border-[#cc2e28] text-[#cc2e28]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                <?php esc_html_e('All', 'ckl-car-rental'); ?>
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo esc_html($status_counts['all']); ?></span>
            </a>
            <a href="<?php echo esc_url(add_query_arg('status', 'active', $base_url)); ?>"
               class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors <?php echo $current_filter === 'active' ? 'border-[#cc2e28] text-[#cc2e28]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                <?php esc_html_e('Active', 'ckl-car-rental'); ?>
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo esc_html($status_counts['active']); ?></span>
            </a>
            <a href="<?php echo esc_url(add_query_arg('status', 'completed', $base_url)); ?>"
               class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors <?php echo $current_filter === 'completed' ? 'border-[#cc2e28] text-[#cc2e28]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                <?php esc_html_e('Completed', 'ckl-car-rental'); ?>
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo esc_html($status_counts['completed']); ?></span>
            </a>
            <a href="<?php echo esc_url(add_query_arg('status', 'cancelled', $base_url)); ?>"
               class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors <?php echo $current_filter === 'cancelled' ? 'border-[#cc2e28] text-[#cc2e28]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                <?php esc_html_e('Cancelled', 'ckl-car-rental'); ?>
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo esc_html($status_counts['cancelled']); ?></span>
            </a>
        </nav>
    </div>
</div>

<!-- Bookings Grid -->
<?php if (!empty($filtered_bookings)) : ?>
    <div class="ckl-bookings-grid space-y-4">
        <?php
        foreach ($filtered_bookings as $booking) {
            wc_get_template_part('template-parts/myaccount/booking-card', '', array('booking' => $booking));
        }
        ?>
    </div>
<?php else : ?>
    <!-- Empty State -->
    <div class="ckl-empty-state bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
            <?php esc_html_e('No bookings found', 'ckl-car-rental'); ?>
        </h3>
        <p class="text-gray-600 mb-6">
            <?php
            if ($current_filter === 'all') {
                esc_html_e('You haven\'t made any bookings yet.', 'ckl-car-rental');
            } else {
                printf(esc_html__('You don\'t have any %s bookings.', 'ckl-car-rental'), esc_html($current_filter));
            }
            ?>
        </p>
        <?php if ($current_filter !== 'all') : ?>
            <a href="<?php echo esc_url($base_url); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-[#cc2e28] text-[#cc2e28] rounded-lg font-medium hover:bg-gray-50 transition-colors">
                <?php esc_html_e('View All Bookings', 'ckl-car-rental'); ?>
            </a>
        <?php else : ?>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-[#cc2e28] text-white rounded-lg font-medium hover:bg-[#a8241f] transition-colors">
                <?php esc_html_e('Browse Vehicles', 'ckl-car-rental'); ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>
