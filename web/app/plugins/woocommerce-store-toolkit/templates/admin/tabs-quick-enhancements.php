<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<h2><?php esc_html_e( 'Quick Enhancements', 'woocommerce-store-toolkit' ); ?></h2>
<p>
    <?php esc_html_e( 'Quick Enhancements are small tweaks to the WooCommerce admin area that can help improve your workflow.', 'woocommerce-store-toolkit' ); ?>
    <?php esc_html_e( 'These enhancements are designed to be lightweight and easy to use.', 'woocommerce-store-toolkit' ); ?>
    <?php esc_html_e( 'If you have any suggestions for new enhancements, ', 'woocommerce-store-toolkit' ); ?>
    <a href="https://visser.com.au/suggest-a-quick-enhancement/"><?php esc_html_e( 'please let us know!', 'woocommerce-store-toolkit' ); ?></a>
</p>

<h4><?php esc_html_e( 'Product Enhancements', 'woocommerce-store-toolkit' ); ?></h4>
<div class="quick-enhancements-grid">
    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Change Add To Cart Button Text</h3>
            <label class="switch">
                <input type="checkbox" name="change_add_to_cart" value="1"<?php checked( $change_add_to_cart, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Change the Add to Cart button text on the Edit Product screen.', 'woocommerce-store-toolkit' ); ?></p>
            <input type="text" class="extra-data-field" name="change_add_to_cart_extra_data[0]" value="<?php echo esc_attr( $change_add_to_cart_text ); ?>" placeholder="<?php esc_attr_e( 'Add to Cart', 'woocommerce-store-toolkit' ); ?>">
            <br><small><?php esc_html_e( 'Leave blank to use the default text.', 'woocommerce-store-toolkit' ); ?></small>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Adjust Number Products On Archives</h3>
            <label class="switch">
                <input type="checkbox" name="adjust_number_products_on_archive" value="1"<?php checked( $adjust_number_products_on_archive, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Adjust the number of products displayed on the Shop and Archive pages.', 'woocommerce-store-toolkit' ); ?></p>
            <input type="text" class="extra-data-field" name="adjust_number_products_on_archive_extra_data[0]" value="<?php echo esc_attr( $number_products_on_archive ); ?>" placeholder="<?php echo esc_attr( $default_wc_number_products_on_archive ); ?>">
            <br><small><?php esc_html_e( 'Leave blank to use the default.', 'woocommerce-store-toolkit' ); ?></small>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Delete Images On Product Delete</h3>
            <label class="switch">
                <input type="checkbox" name="delete_images_on_product_delete" value="1"<?php checked( $delete_images_on_product_delete, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Delete product images when a product is deleted.', 'woocommerce-store-toolkit' ); ?></p>
            <p><?php esc_html_e( 'This is useful if you have a lot of products and want to keep your media library clean, but be warned, if your image is attached to multiple images it will be deleted for those products too.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Variation Edit Screen</h3>
            <label class="switch">
                <input type="checkbox" name="unlock_variations" value="1"<?php checked( $unlock_variations, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Allow editing individual product variations with the Edit Product screen.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Enable Unit Pricing</h3>
            <label class="switch">
                <input type="checkbox" name="enable_unit_pricing" value="1"<?php checked( $enable_unit_pricing, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Display unit pricing (e.g., $1.30 per 100mL) on product pages.', 'woocommerce-store-toolkit' ); ?></p>
            <p><?php esc_html_e( 'You can set the unit pricing in the product metabox on the product page.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>
</div>

<h4><?php esc_html_e( 'Order Enhancements', 'woocommerce-store-toolkit' ); ?></h4>
<div class="quick-enhancements-grid">
    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Auto Complete Zero Total Orders</h3>
            <label class="switch">
                <input type="checkbox" name="autocomplete_order" value="1"<?php checked( $autocomplete_order, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'If an order total is zero mark it completed automatically.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>
    
    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Show Related Orders</h3>
            <label class="switch">
                <input type="checkbox" name="unlock_related_orders" value="1"<?php checked( $unlock_related_orders, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'View a related orders meta box within the Edit Order screen.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Show Used Coupons On Order Preview Popup</h3>
            <label class="switch">
                <input type="checkbox" name="show_used_coupons" value="1"<?php checked( $show_used_coupons, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Display used coupons on the order preview popup.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>
</div>

<h4><?php esc_html_e( 'Cart & Checkout Enhancements', 'woocommerce-store-toolkit' ); ?></h4>
<div class="quick-enhancements-grid">
    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Add Empty Cart Button</h3>
            <label class="switch">
                <input type="checkbox" name="add_empty_cart_button" value="1"<?php checked( $add_empty_cart_button, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Add an Empty Cart button to the cart page. Compatible with both legacy cart & the cart block.', 'woocommerce-store-toolkit' ); ?></p>
        </div>
    </div>

    <div class="quick-enhancements-grid-item">
        <div class="box">
            <h3>Change Place Order Button Text</h3>
            <label class="switch">
                <input type="checkbox" name="place_order_button" value="1"<?php checked( $place_order_button, 1 ); ?>>
                <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Change the Place Order button text on the Checkout page. Compatible with both legacy checkout & the checkout block.', 'woocommerce-store-toolkit' ); ?></p>
            <input type="text" class="extra-data-field" name="place_order_button_extra_data[0]" value="<?php echo esc_attr( $place_order_button_text ); ?>" placeholder="<?php esc_attr_e( 'Place Order', 'woocommerce-store-toolkit' ); ?>">
        </div>
    </div>
</div>

<?php wp_nonce_field( 'woo_st_quick_enhancements', 'woo_st_quick_enhancements_nonce' ); ?>

<style>
    .quick-enhancements-grid {
        max-width: 1200px;
        margin: 20px 0 30px 0;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 10px;
        text-align: left;
        position: relative;
    }
    
    .quick-enhancements-grid-item {
        padding: 10px;
        text-align: left;
        box-shadow: 1px 2px 5px rgba(0,0,0,0.15);
        border-radius: 6px;
        position: relative;
    }

    .quick-enhancements-grid-item h3 {
        display: inline-block;
        max-width: 200px;
        margin-top: 13px;
    }

    .quick-enhancements-grid-item p {
        margin-top: 0;
    }

    .quick-enhancements-grid-item label {
        display: block;
    }
    
    /* The switch container */
    .quick-enhancements-grid-item .switch {
        position: relative;
        float: right;
        width: 40px;
        height: 24px;
        margin-top: 10px;
        margin-right: 5px;
    }

    /* Hide the default checkbox */
    .quick-enhancements-grid-item .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .quick-enhancements-grid-item .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 34px;
    }

    /* The slider before it's checked */
    .quick-enhancements-grid-item .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    /* When the checkbox is checked */
    .quick-enhancements-grid-item input:checked + .slider {
        background-color: #6C7BFF;
    }

    /* Move the slider when the checkbox is checked */
    input:checked + .slider:before {
        transform: translateX(16px);
    }

    .unit-price {
        font-size: 0.9em;
        color: #666;
        margin-top: 5px;
    }
</style>
