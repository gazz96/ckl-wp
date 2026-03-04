<h2><?php esc_html_e( 'Handy Tools', 'woocommerce-store-toolkit' ); ?></h2>

<div class="tools-grid">
    <!-- RE-LINK ROGUE PRODUCTS TO SIMPLE PRODUCT TYPE -->
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Re-link rogue Products to the Simple Product Type', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Scan the WooCommerce Products catalogue for Products that do not have any Product Type assigned to them and assign them to the default Simple Product Type.', 'woocommerce-store-toolkit' ); ?></p>
            <a href="
                <?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'action'   => 'relink-rogue-simple-type',
                            '_wpnonce' => wp_create_nonce( 'woo_st_relink_rogue_simple_type' ),
                        )
                    )
                );
                ?>
                " class="button button-primary"><?php esc_html_e( 'Run Tool', 'woocommerce-store-toolkit' ); ?></a>
        </div>
    </div>

    <!-- DELETE CORRUPT PRODUCT VARIATIONS -->
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Delete corrupt Product Variations', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Scan the WooCommerce Products catalogue for Variations that are obviously corrupt. Corrupt Variations are identified as having no Post Title and an invalid array of duplicate values set for the Stock Status detail.', 'woocommerce-store-toolkit' ); ?></p>
            <a href="
                <?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'action'   => 'delete-corrupt-variations',
                            '_wpnonce' => wp_create_nonce( 'woo_st_delete_corrupt_variations' ),
                        )
                    )
                );
                ?>
                " class="button button-primary"><?php esc_html_e( 'Run Tool', 'woocommerce-store-toolkit' ); ?></a>
        </div>
    </div>

    <!-- REFRESH PRODUCT TRANSIENTS -->
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Refresh Product Transients', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Clear the Product transients for all WooCommerce Products.', 'woocommerce-store-toolkit' ); ?></p>
            <a href="
                <?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'action'   => 'refresh-product-transients',
                            '_wpnonce' => wp_create_nonce( 'woo_st_refresh_product_transients' ),
                        )
                    )
                );
                ?>
                " class="button button-primary"><?php esc_html_e( 'Run Tool', 'woocommerce-store-toolkit' ); ?></a>
        </div>
    </div>
    
    <!-- RECALCULATE ALL SUBSCRIPTIONS -->
    <?php
    if ( woo_st_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
    ?>
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Recalculate All Subscriptions', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Goes into all active, on-hold, and pending-cancellation subscriptions and presses the "Recalculate" button.' ); ?></p>
            <a href="
                <?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'action'   => 'recalculate-all-subscriptions',
                            '_wpnonce' => wp_create_nonce( 'woo_st_recalculate_all_subscriptions' ),
                        )
                    )
                );
                ?>
                " class="button button-primary"><?php esc_html_e( 'Run Tool', 'woocommerce-store-toolkit' ); ?></a>
        </div>
    </div>
    <?php
    }
    ?>

    <!-- GENERATE SAMPLE ORDERS -->
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Generate Sample Orders', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Generates a random set of sample Orders for testing purposes. Uses random customers, dates, and products.', 'woocommerce-store-toolkit' ); ?></p>
            <form method="post">
                <p><?php esc_html_e( 'Number of Orders to generate', 'woocommerce-store-toolkit' ); ?>: <input type="text" name="limit" value="100" /></p>
                <p><input type="submit" value="<?php esc_html_e( 'Generate Orders', 'woocommerce-store-toolkit' ); ?>" class="button button-primary" /></p>
                <input type="hidden" name="action" value="woo_st-generate_orders" />
                <?php wp_nonce_field( 'generate_orders', 'woo_st-generate_orders' ); ?>
            </form>
        </div>
    </div>

    <!-- NUKING TOOLS -->
    <div class="tools-grid-item">
        <div class="box">
            <h3><?php esc_html_e( 'Nuking Tools', 'woocommerce-store-toolkit' ); ?></h3>
            <p class="description"><?php esc_html_e( 'Need to delete some data from your WooCommerce install? Use these nuking tools to pinpoint the data you want to delete and remove it in 1-click. Use these tools with caution. They are designed to remove data from your WooCommerce store.', 'woocommerce-store-toolkit' ); ?></p>
            <a href="
                <?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'tab' => 'nuke',
                        )
                    )
                );
                ?>
                " class="button button-primary"><?php esc_html_e( 'View Nuking Tools', 'woocommerce-store-toolkit' ); ?></a>
        </div>

<!-- END GRID -->
</div>

<style>
    .tools-grid {
        max-width: 1200px;
        margin: 20px 0 30px 0;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 10px;
        text-align: left;
        position: relative;
    }
    
    .tools-grid-item {
        padding: 15px;
        text-align: left;
        box-shadow: 1px 2px 5px rgba(0,0,0,0.15);
        border-radius: 6px;
        position: relative;
    }

    .tools-grid-item h3 {
        display: inline-block;
        margin-top: 10px;
    }

    .tools-grid-item p {
        margin-top: 0;
        margin-bottom: 10px;
    }

    .tools-grid-item label {
        display: block;
    }
    
    /* The switch container */
    .tools-grid-item .switch {
        position: relative;
        float: right;
        width: 40px;
        height: 24px;
        margin-top: 10px;
        margin-right: 5px;
    }

    /* Hide the default checkbox */
    .tools-grid-item .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .tools-grid-item .slider {
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
    .tools-grid-item .slider:before {
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
    .tools-grid-item input:checked + .slider {
        background-color: #6C7BFF;
    }

    /* Move the slider when the checkbox is checked */
    input:checked + .slider:before {
        transform: translateX(16px);
    }


</style>
