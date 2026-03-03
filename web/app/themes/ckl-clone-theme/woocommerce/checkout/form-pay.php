<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.2.0
 */

defined('ABSPATH') || exit;

$totals = $order->get_order_item_totals(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
?>

<div class="ckl-checkout-pay-wrapper max-w-4xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php esc_html_e('Pay for Order', 'woocommerce'); ?></h1>
        <p class="text-gray-600"><?php esc_html_e('Complete your payment securely', 'ckl-car-rental'); ?></p>
    </div>

    <form id="order_review" method="post" class="space-y-6">

        <!-- Order Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white"><?php esc_html_e('Order Details', 'woocommerce'); ?></h2>
            </div>

            <!-- Order Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="product-name text-left px-6 py-3 text-sm font-semibold text-gray-700"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                            <th class="product-quantity text-center px-4 py-3 text-sm font-semibold text-gray-700"><?php esc_html_e('Qty', 'woocommerce'); ?></th>
                            <th class="product-total text-right px-6 py-3 text-sm font-semibold text-gray-700"><?php esc_html_e('Totals', 'woocommerce'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($order->get_items()) > 0) : ?>
                            <?php foreach ($order->get_items() as $item_id => $item) : ?>
                                <?php
                                if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                                    continue;
                                }
                                ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="product-name px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            <?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false)); ?>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <?php
                                            do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);
                                            wc_display_item_meta($item);
                                            do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
                                            ?>
                                        </div>
                                    </td>
                                    <td class="product-quantity px-4 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                                            <?php echo esc_html($item->get_quantity()); ?>
                                        </span>
                                    </td>
                                    <td class="product-subtotal px-6 py-4 text-right font-semibold text-gray-900">
                                        <?php echo $order->get_formatted_line_subtotal($item); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <?php if ($totals) : ?>
                            <?php foreach ($totals as $total) : ?>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" colspan="2" class="px-6 py-3 text-left text-sm font-medium text-gray-700">
                                        <?php echo $total['label']; ?>
                                    </th>
                                    <td class="px-6 py-3 text-right font-semibold text-gray-900">
                                        <?php echo $total['value']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php
        /**
         * Triggered from within the checkout/form-pay.php template, immediately before the payment section.
         *
         * @since 8.2.0
         */
        do_action('woocommerce_pay_order_before_payment');
        ?>

        <!-- Payment Methods Card -->
        <div id="payment" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white"><?php esc_html_e('Payment Method', 'woocommerce'); ?></h2>
            </div>

            <div class="p-6">
                <?php if ($order->needs_payment()) : ?>
                    <ul class="wc_payment_methods payment_methods methods space-y-4">
                        <?php
                        if (!empty($available_gateways)) {
                            foreach ($available_gateways as $gateway) {
                                ?>
                                <li class="payment-method_<?php echo esc_attr($gateway->id); ?> border rounded-lg overflow-hidden transition-all hover:border-blue-300 hover:shadow-sm">
                                    <?php wc_get_template('checkout/payment-method.php', array('gateway' => $gateway)); ?>
                                </li>
                                <?php
                            }
                        } else {
                            echo '<li class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">';
                            wc_print_notice(apply_filters('woocommerce_no_available_payment_methods_message', esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')), 'notice'); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
                            echo '</li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>

                <!-- Submit Button -->
                <div class="form-row mt-6">
                    <input type="hidden" name="woocommerce_pay" value="1" />

                    <?php wc_get_template('checkout/terms.php'); ?>

                    <?php do_action('woocommerce_pay_order_before_submit'); ?>

                    <div class="flex justify-center">
                        <?php echo apply_filters('woocommerce_pay_order_button_html', '<button type="submit" class="button alt w-full md:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); ?>
                    </div>

                    <?php do_action('woocommerce_pay_order_after_submit'); ?>

                    <?php wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce'); ?>
                </div>
            </div>
        </div>

        <!-- Security Badge -->
        <div class="flex items-center justify-center space-x-6 text-sm text-gray-500 py-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span><?php esc_html_e('Secure Payment', 'ckl-car-rental'); ?></span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span><?php esc_html_e('SSL Encrypted', 'ckl-car-rental'); ?></span>
            </div>
        </div>
    </form>
</div>

<style>
    /* Payment method selection improvements */
    .wc_payment_methods input[type="radio"]:checked + label {
        background: #eff6ff;
        border-color: #3b82f6;
    }

    .wc_payment_methods input[type="radio"]:focus + label {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .ckl-checkout-pay-wrapper {
            padding: 1rem;
        }

        .ckl-checkout-pay-wrapper table th,
        .ckl-checkout-pay-wrapper table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
