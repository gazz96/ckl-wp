<?php
/**
 * My Account Payment History Page Content
 *
 * Displays payment transactions list with summary cards
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 *
 * @var array $orders Array of WC_Order objects
 * @var float $total_spent_all Total spent all time
 * @var float $total_spent_month Total spent this month
 * @var float $pending_payments Pending payments
 */

defined('ABSPATH') || exit;

if (!isset($orders)) {
    $orders = array();
}
if (!isset($total_spent_all)) {
    $total_spent_all = 0;
}
if (!isset($total_spent_month)) {
    $total_spent_month = 0;
}
if (!isset($pending_payments)) {
    $pending_payments = 0;
}
?>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <!-- This Month -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('This Month', 'ckl-car-rental'); ?>
                </p>
                <p class="text-2xl font-bold text-[#cc2e28]">
                    <?php echo wc_price($total_spent_month); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#cc2e28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- All Time -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('All Time', 'ckl-car-rental'); ?>
                </p>
                <p class="text-2xl font-bold text-green-600">
                    <?php echo wc_price($total_spent_all); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">
                    <?php esc_html_e('Pending', 'ckl-car-rental'); ?>
                </p>
                <p class="text-2xl font-bold text-yellow-600">
                    <?php echo wc_price($pending_payments); ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">
            <?php esc_html_e('Transactions', 'ckl-car-rental'); ?>
        </h2>
    </div>

    <?php if (!empty($orders)) : ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Order', 'ckl-car-rental'); ?>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Date', 'ckl-car-rental'); ?>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Amount', 'ckl-car-rental'); ?>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Payment Method', 'ckl-car-rental'); ?>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Status', 'ckl-car-rental'); ?>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?php esc_html_e('Actions', 'ckl-car-rental'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($orders as $order) :
                        $order_id = $order->get_id();
                        $order_number = $order->get_order_number();
                        $order_date = $order->get_date_created()->date_i18n(get_option('date_format'));
                        $order_total = $order->get_total();
                        $payment_method = $order->get_payment_method_title();
                        $order_status = $order->get_status();
                        $order_status_label = wc_get_order_status_name($order_status);

                        // Determine status color
                        $status_class = 'bg-gray-100 text-gray-800';
                        if (in_array($order_status, array('completed', 'processing'))) {
                            $status_class = 'bg-green-100 text-green-800';
                        } elseif (in_array($order_status, array('pending', 'on-hold'))) {
                            $status_class = 'bg-yellow-100 text-yellow-800';
                        } elseif (in_array($order_status, array('cancelled', 'refunded', 'failed'))) {
                            $status_class = 'bg-red-100 text-red-800';
                        }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="text-[#cc2e28] hover:text-[#a8241f] font-medium">
                                    #<?php echo esc_html($order_number); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo esc_html($order_date); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                <?php echo wc_price($order_total); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo esc_html($payment_method); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo esc_attr($status_class); ?>">
                                    <?php echo esc_html($order_status_label); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="text-[#cc2e28] hover:text-[#a8241f] font-medium">
                                    <?php esc_html_e('View', 'ckl-car-rental'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <?php esc_html_e('No transactions yet', 'ckl-car-rental'); ?>
            </h3>
            <p class="text-gray-600">
                <?php esc_html_e('You haven\'t made any payments yet.', 'ckl-car-rental'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>
