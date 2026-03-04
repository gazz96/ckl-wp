<?php
/**
 * Add WooCommerce store details to WordPress Administration Dashboard
 */
function woo_st_add_dashboard_widgets() {

    // Simple check that WooCommerce is activated
    if ( class_exists( 'WooCommerce' ) ) {

        $user_capability = apply_filters( 'woo_st_dashboard_widgets', 'manage_options' );

        // Check for manage_options User Capability
        if ( current_user_can( $user_capability ) ) {
            if ( apply_filters( 'woo_st_dashboard_store_snapshot', true ) ) {
                wp_add_dashboard_widget( 'woo_st-dashboard_store_snapshot', __( 'Store Snapshot', 'woocommerce-store-toolkit' ), 'woo_st_dashboard_store_snapshot' );
            }
            if ( apply_filters( 'woo_st_dashboard_sales_snapshot', true ) ) {
                if ( function_exists( 'wc_price' ) ) {
                    wp_add_dashboard_widget( 'woo_st-dashboard_sales_snapshot', __( 'Sales Snapshot', 'woocommerce-store-toolkit' ), 'woo_st_dashboard_sales_snapshot' );
                }
            }
        }
    }
}
add_action( 'wp_dashboard_setup', 'woo_st_add_dashboard_widgets' );

/**
 * Store Snapshot Widget
 */
function woo_st_dashboard_store_snapshot() {
    // Required data for Store Snapshot widget.
    $orders_processing = wc_orders_count( 'processing' );
    $orders_on_hold    = wc_orders_count( 'on-hold' );
    $orders_completed  = wc_orders_count( 'completed' );
    $orders_refunded   = wc_orders_count( 'refunded' );
    $customer_count    = woo_st_get_total_number_of_customers();
    $product_count     = woo_st_return_count( 'product' );
    $coupon_count      = woo_st_return_count( 'coupon' );
    $review_count      = woo_st_get_total_number_of_product_reviews();

    // Get the widget template.
    include_once WOO_ST_PATH . 'templates/admin/widgets/widget-store-snapshot.php';

    // Include Growth Tools prompt.
    include WOO_ST_PATH . 'templates/admin/widgets/dashboard-growth-tools-prompt.php';
}

/**
 * Sales Snapshot Widget
 */
function woo_st_dashboard_sales_snapshot() {
    // Required data for Sales Snapshot widget.
    $sales_revenue_today      = wc_price( woo_st_get_total_sales( 'today' ) );
    $sales_revenue_yesterday  = wc_price( woo_st_get_total_sales( 'yesterday' ) );
    $sales_revenue_this_week  = wc_price( woo_st_get_total_sales( 'this_week' ) );
    $sales_revenue_last_week  = wc_price( woo_st_get_total_sales( 'last_week' ) );
    $sales_revenue_this_month = wc_price( woo_st_get_total_sales( 'this_month' ) );
    $sales_revenue_last_month = wc_price( woo_st_get_total_sales( 'last_month' ) );
    $sales_revenue_this_year  = wc_price( woo_st_get_total_sales( 'this_year' ) );
    $sales_revenue_last_year  = wc_price( woo_st_get_total_sales( 'last_year' ) );

    // Get the widget template.
    include_once WOO_ST_PATH . 'templates/admin/widgets/widget-sales-snapshot.php';

    // Include Growth Tools prompt.
    include WOO_ST_PATH . 'templates/admin/widgets/dashboard-growth-tools-prompt.php';
}
