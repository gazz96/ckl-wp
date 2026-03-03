<?php
/**
 * My Account Documents
 *
 * Documents and requirements page for CKL Car Rental
 *
 * @package CKL_Car_Rental
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get uploaded documents
$license_document_id = get_user_meta($customer_id, '_document_license', true);
$id_document_id = get_user_meta($customer_id, '_document_id', true);

// Get document URLs
$license_url = $license_document_id ? wp_get_attachment_url($license_document_id) : '';
$id_url = $id_document_id ? wp_get_attachment_url($id_document_id) : '';

do_action('woocommerce_account_documents_before', $customer_id);
?>

<!-- Page Header -->
<div class="ckl-page-header mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        <?php esc_html_e('Documents & Requirements', 'ckl-car-rental'); ?>
    </h1>
    <p class="text-gray-600">
        <?php esc_html_e('Access important rental information and manage your documents.', 'ckl-car-rental'); ?>
    </p>
</div>

<?php do_action('woocommerce_account_documents_content_before', $customer_id); ?>

<?php
/**
 * Documents content section
 */
wc_get_template_part('template-parts/myaccount/documents-section', '', array(
    'license_url' => $license_url,
    'license_document_id' => $license_document_id,
    'id_url' => $id_url,
    'id_document_id' => $id_document_id,
));
?>

<?php do_action('woocommerce_account_documents_content_after', $customer_id); ?>

<?php do_action('woocommerce_account_documents_after', $customer_id); ?>
