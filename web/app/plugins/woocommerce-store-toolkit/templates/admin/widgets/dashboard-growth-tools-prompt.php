<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="woo-st-growth-tools-prompt">
    <p>
        <?php esc_html_e( 'Unlock powerful features that will help you grow your store.', 'woocommerce-store-toolkit' ); ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=store-toolkit&tab=growth-tools' ) ); ?>"><?php esc_html_e( 'View Growth Tools', 'woocommerce-store-toolkit' ); ?> &rarr;</a>
    </p>
</div>
