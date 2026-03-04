<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="woo_st-about-page" class="woo_st-page wrap nosubsub">
    <div class="about-container">
        <div class="about-card no-shadow">
            <div class="card-title">
                <h3><?php esc_html_e( 'About The Makers - Rymera Web Co', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
            <p><?php esc_html_e( 'Over the years, we\'ve worked with thousands of smart store owners who were frustrated by the complexities of managing their online stores.', 'woocommerce-store-toolkit' ); ?></p>
            <p><?php esc_html_e( 'That\'s where Store Toolkit comes in - a collection of quick enhancements and handy tools that we feels should have been in WooCommerce all along.', 'woocommerce-store-toolkit' ); ?></p>
            <p><?php esc_html_e( 'This plugin is brought to you by the same dedicated team that has been at the forefront of WooCommerce solutions for over a decade. We\'re passionate about helping you achieve the best results with our tools. We\'re thrilled you\'re using our tool and invite you to try our other plugins as well!', 'woocommerce-store-toolkit' ); ?></p>
            </div>
        </div> 
        <div class="about-card no-shadow">
            <div class="card-body xs-text-center">
                <img src="<?php echo esc_url( WOO_ST_URL . '/images/rymera-team.jpg' ); ?>" alt="Team Rymera" class="team" />
            </div>
        </div>
    </div>

    <h3><?php esc_html_e( 'Other Tools By Rymera', 'woocommerce-store-toolkit' ); ?></h3>
    <div class="about-container">
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/advanced-coupons-for-woocommerce-free/assets/icon-128x128.png' ); ?>" alt="<?php esc_attr_e( 'Advanced Coupons', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'Advanced Coupons for WooCommerce (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Extends your coupon features so you can market your store better. Adds cart conditions (coupon rules), buy one get one (BOGO) deals, url coupons, coupon categories and loads more.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'woocommerce-store-toolkit' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=advanced-coupons-for-woocommerce-free', 'install-plugin_advanced-coupons-for-woocommerce-free' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/wc-vendors/assets/icon.svg' ); ?>" alt="<?php esc_attr_e( 'WC Vendors', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'WC Vendors (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Easiest way to create your multivendor marketplace and earn commission from every sale. Create a WooCommerce marketplace with multi-seller, product vendor & multi vendor commissions.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'wc-vendors' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'wc-vendors/class-wc-vendors.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'wc-vendors/class-wc-vendors.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=wc-vendors', 'install-plugin_wc-vendors' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/woocommerce-wholesale-prices/assets/icon-128x128.jpg' ); ?>" alt="<?php esc_attr_e( 'WooCommerce Wholesale Prices', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'WooCommerce Wholesale Prices (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'The #1 WooCommerce wholesale plugin for adding wholesale prices & managing B2B customers. Trusted by over 25k store owners for managing wholesale orders, pricing, visibility, user roles, and more.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'woocommerce-store-toolkit' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'woocommerce-wholesale-prices/woocommerce-wholesale-prices.plugin.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'woocommerce-wholesale-prices/woocommerce-wholesale-prices.plugin.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=woocommerce-wholesale-prices', 'install-plugin_woocommerce-wholesale-prices' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/invoice-gateway-for-woocommerce/assets/icon-128x128.jpg' ); ?>" alt="<?php esc_attr_e( 'Invoice Gateway for WooCommerce', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'Invoice Gateway for WooCommerce (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Accept orders via a special invoice payment gateway method which lets your customer enter their order without upfront payment. Then just issue an invoice from your accounting system and paste in the number.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'woocommerce-store-toolkit' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'invoice-gateway-for-woocommerce/invoice-gateway-for-woocommerce.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'invoice-gateway-for-woocommerce/invoice-gateway-for-woocommerce.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=invoice-gateway-for-woocommerce', 'install-plugin_invoice-gateway-for-woocommerce' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/woocommerce-exporter/assets/icon-128x128.png' ); ?>" alt="<?php esc_attr_e( 'Store Exporter for WooCommerce', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'Store Exporter for WooCommerce (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Easily export Orders, Subscriptions, Coupons, Products, Categories, Tags to a variety of formats. The deluxe version also adds scheduled exporting for easy reporting and syncing with other systems.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'woocommerce-store-toolkit' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'woocommerce-store-exporter/exporter.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'woocommerce-store-exporter/exporter.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=woocommerce-store-exporter', 'install-plugin_woocommerce-store-exporter' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
        <div class="about-card">
            <div class="card-title">
                <img src="<?php echo esc_url( 'https://ps.w.org/woo-product-feed-pro/assets/icon-128x128.png' ); ?>" alt="<?php esc_attr_e( 'Product Feed Pro by AdTribes', 'woocommerce-store-toolkit' ); ?>" />
                <h3><?php esc_html_e( 'Product Feed Pro (Free Plugin)', 'woocommerce-store-toolkit' ); ?></h3>
            </div>
            <div class="card-body xs-text-center">
                <p class="mt-0"><?php esc_html_e( 'Helps you generate and manage product feeds for various marketing channels, such as Google Shopping, Facebook, and more, to optimize your eCommerce store\'s visibility and sales.', 'woocommerce-store-toolkit' ); ?></p>
            </div>
            <div class="card-footer">
                <div class="install-status">
                    <p class="m-0">
                        <strong><?php esc_html_e( 'Status:', 'woocommerce-store-toolkit' ); ?></strong>
                        <?php echo woo_st_is_plugin_installed( 'woo-product-feed-pro/woocommerce-sea.php' ) ? esc_html_e( 'Installed', 'woocommerce-store-toolkit' ) : esc_html_e( 'Not installed', 'woocommerce-store-toolkit' ); ?>
                    </p>
                    <?php if ( ! woo_st_is_plugin_installed( 'woo-product-feed-pro/woocommerce-sea.php' ) ) : ?>
                    <a href="<?php echo esc_url( wp_nonce_url( 'update.php?action=install-plugin&plugin=woo-product-feed-pro', 'install-plugin_woo-product-feed-pro' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugin', 'woocommerce-store-toolkit' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .about-container {
        max-width: 1200px;
        margin: 20px 0 30px 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 10px;
    }

    .about-card {
        background-color: white;
        box-shadow: 1px 2px 5px rgba(0,0,0,0.15);
        padding: 20px;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
    }

    .about-card.no-shadow {
        box-shadow: none;
        padding: 0;
    }
    
    .about-card img {
        max-width: 36px;
        width: 100%;
        margin-right: 8px;
        display: flex;
    }

    .about-card img.team {
        max-width: 100%;
        width: 100%;
        display: flex;
        /* margin: -20px; */
    }

    .about-card .card-title {
        display: flex;
        align-items: center;
    }

    .about-card .card-body {
        flex-grow: 1;
    }

    .about-card .card-footer {
        margin: 10px -20px -20px -20px;
        background: #f1f1f1;
        padding: 5px 20px 5px 20px;
    }

    .about-card .card-footer .install-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
