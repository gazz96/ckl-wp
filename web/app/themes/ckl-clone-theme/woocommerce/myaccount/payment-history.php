<?php
/**
 * My Account Payment History
 *
 * Payment history page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get customer orders
$args = array(
    'customer_id' => $customer_id,
    'limit' => -1,
);

$orders = wc_get_orders($args);

// Calculate statistics
$total_spent_all = 0;
$total_spent_month = 0;
$current_month = date('Y-m');
$pending_payments = 0;

foreach ($orders as $order) {
    $order_date = $order->get_date_created()->date('Y-m');
    $order_total = $order->get_total();

    $total_spent_all += $order_total;

    if ($order_date === $current_month) {
        $total_spent_month += $order_total;
    }

    if ($order->has_status('pending')) {
        $pending_payments += $order_total;
    }
}

do_action('woocommerce_account_payment-history_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        <?php esc_html_e('Payment History', 'ckl-car-rental'); ?>
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('View all your payment transactions.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php do_action('woocommerce_account_payment-history_content_before', $customer_id); ?>

<?php
/**
 * Payment history content with summary cards and transactions list
 */
wc_get_template_part('template-parts/myaccount/payment-history', 'page', array(
    'orders' => $orders,
    'total_spent_all' => $total_spent_all,
    'total_spent_month' => $total_spent_month,
    'pending_payments' => $pending_payments,
));
?>

<?php do_action('woocommerce_account_payment-history_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_payment-history_after', $customer_id); ?>
