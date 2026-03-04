<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<div class="woo_st-order-preview-data wc-order-preview-addresses">
<?php do_action( 'woo_st_before_order_preview_popup_summary', $order ); ?>

    <div class="woo_st-order-used-coupons wc-order-preview-address" style="padding-top: 0;">
        <strong><?php esc_html_e( 'Coupons', 'woocommerce-store-toolkit' ); ?></strong>
        <?php if ( $coupons_list ) : ?>
            <?php echo wp_kses_post( $coupons_list ); ?>
        <?php else : ?>
            <span class="no-coupons"><?php esc_html_e( 'No coupons used', 'woocommerce-store-toolkit' ); ?></span>
        <?php endif; ?>
    </div>

    <?php do_action( 'woo_st_after_order_preview_popup_summary', $order ); ?>
</div>
