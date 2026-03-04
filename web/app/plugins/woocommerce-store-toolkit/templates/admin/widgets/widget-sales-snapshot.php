<?php
/**
 * Sales Snapshot Widget
 *
 * @package     Store Toolkit
 * @subpackage  Admin/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="sales-snapshot-widget">
    <div class="widget-title">
        <img src="<?php echo esc_url( WOO_ST_URL ); ?>/images/overview-icons/sales-snapshot.png" alt="Sales Snapshot" />
        <h3>Sales Snapshot</h3>
    </div>
    <div class="sales-snapshot-grid">
        <div class="grid-item">
            <span class="grid-item-title">Today</span>
            <span><?php echo isset( $sales_revenue_today ) ? $sales_revenue_today : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Yesterday</span>
            <span><?php echo isset( $sales_revenue_yesterday ) ? $sales_revenue_yesterday : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">This Week</span>
            <span><?php echo isset( $sales_revenue_this_week ) ? $sales_revenue_this_week : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Last Week</span>
            <span><?php echo isset( $sales_revenue_last_week ) ? $sales_revenue_last_week : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">This Month</span>
            <span><?php echo isset( $sales_revenue_this_month ) ? $sales_revenue_this_month : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Last Month</span>
            <span><?php echo isset( $sales_revenue_last_month ) ? $sales_revenue_last_month : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">This Year</span>
            <span><?php echo isset( $sales_revenue_this_year ) ? $sales_revenue_this_year : 0; // phpcs:ignore ?></span>
        </div>
        <div class="grid-item">
            <span class="grid-item-title">Last Year</span>
            <span><?php echo isset( $sales_revenue_last_year ) ? $sales_revenue_last_year : 0; // phpcs:ignore ?></span>
        </div>
    </div>
</div>

<style>
    .sales-snapshot-widget {
        background-color: #fff;
        padding: 20px;
        font-size: 16px;
    }

    .sales-snapshot-widget .widget-title {
        font-weight: bold;
        font-size: 22px;
        display: block;
        vertical-align: middle;
        text-align: left;
        margin-bottom: 20px;
    }

    .sales-snapshot-widget .widget-title img {
        margin-right: 10px;
        display: inline;
        width: 40px; 
        height: 40px;
        vertical-align: middle;
    }
    
    .sales-snapshot-widget .widget-title h3 {
        display: inline;
        vertical-align: middle;
        padding: 0;
    }

    .sales-snapshot-widget .sales-snapshot-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 20px;
    }

    .sales-snapshot-widget .grid-item {
        padding: 0;
        text-align: left;
    }
    
    .sales-snapshot-widget .grid-item .grid-item-title {
        font-weight: bold;
        display: block;
    }
</style>
