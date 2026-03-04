<?php
/**
 * Store Snapshot Widget
 *
 * @package     Store Toolkit
 * @subpackage  Admin/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="store-snapshot-widget">
    <div class="widget-title">
        <img src="<?php echo esc_url( WOO_ST_URL ); ?>/images/overview-icons/store-snapshot.png" alt="Store Snapshot" />
        <h3>Store Snapshot</h3>
    </div>
    <div class="store-snapshot-grid">
        <div class="grid-item">
            <span class="grid-item-title">Orders Processing</span>
            <span><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-orders&status=wc-processing' ) ); ?>"><?php echo isset( $orders_processing ) ? esc_html( $orders_processing ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Orders On Hold</span>
            <span><a href="
            <?php
            echo esc_url(
                admin_url( 'admin.php?page=wc-orders&status=wc-on-hold' )
            );
                ?>
"><?php echo isset( $orders_on_hold ) ? esc_html( $orders_on_hold ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Orders Completed</span>
            <span><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-orders&status=wc-completed' ) ); ?>"><?php echo isset( $orders_completed ) ? esc_html( $orders_completed ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Orders Refunded</span>
            <span><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-orders&status=wc-refunded' ) ); ?>"><?php echo isset( $orders_refunded ) ? esc_html( $orders_refunded ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Customers</span>
            <span><a href="<?php echo esc_url( admin_url( 'users.php?role=customer' ) ); ?>"><?php echo isset( $customer_count ) ? esc_html( $customer_count ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Products</span>
            <span><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>"><?php echo isset( $product_count ) ? esc_html( $product_count ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Coupons</span>
            <span><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=shop_coupon' ) ); ?>"><?php echo isset( $coupon_count ) ? esc_html( $coupon_count ) : 0; ?></a></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Reviews</span>
            <span><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product&page=product-reviews' ) ); ?>"><?php echo isset( $review_count ) ? esc_html( $review_count ) : 0; ?></a></span>
        </div>
    </div>
</div>

<style>
    .store-snapshot-widget {
        background-color: #fff;
        padding: 20px;
        font-size: 16px;
    }

    .store-snapshot-widget .widget-title {
        font-weight: bold;
        font-size: 22px;
        display: block;
        vertical-align: middle;
        text-align: left;
        margin-bottom: 20px;
    }

    .store-snapshot-widget .widget-title img {
        margin-right: 10px;
        display: inline;
        width: 40px; 
        height: 40px;
        vertical-align: middle;
    }
    
    .store-snapshot-widget .widget-title h3 {
        display: inline;
        vertical-align: middle;
        padding: 0;
    }

    .store-snapshot-widget .store-snapshot-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 20px;
    }

    .store-snapshot-widget .grid-item {
        padding: 0;
        text-align: left;
    }

    .store-snapshot-widget .grid-item span {
        display: block;
    }
    
    .store-snapshot-widget .grid-item .grid-item-title {
        font-weight: bold;
    }
</style>
