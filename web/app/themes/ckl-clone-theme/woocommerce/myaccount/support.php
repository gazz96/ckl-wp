<?php
/**
 * My Account Support
 *
 * Support and help page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get support contact info from options or defaults
$phone = get_option('ckl_support_phone', '+60 12-345-6789');
$whatsapp = get_option('ckl_support_whatsapp', '+60123456789');
$email = get_option('ckl_support_email', 'support@cklangkawi.com');

do_action('woocommerce_account_support_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        <?php esc_html_e('Help & Support', 'ckl-car-rental'); ?>
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('We\'re here to help. Get in touch with our support team.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php do_action('woocommerce_account_support_content_before', $customer_id); ?>

<?php
/**
 * Support content with contact options and FAQ
 */
wc_get_template_part('template-parts/myaccount/support-section', '', array(
    'phone' => $phone,
    'whatsapp' => $whatsapp,
    'email' => $email,
));
?>

<?php do_action('woocommerce_account_support_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_support_after', $customer_id); ?>
