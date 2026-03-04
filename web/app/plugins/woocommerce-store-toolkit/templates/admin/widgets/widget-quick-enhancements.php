<?php
/**
 * Quick Enhancements Widget
 *
 * @package     Store Toolkit
 * @subpackage  Admin/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="quick-enhancements-widget">
    <div class="widget-title">
        <img src="<?php echo esc_url( WOO_ST_URL ); ?>/images/overview-icons/quick-enhancements.png" alt="Quick Enhancements" />
        <h3>Quick Enhancements</h3>
    </div>
    <p>Toggle on/off quick enhancements to make your store better. Store Toolkit is all about giving you the little things that WooCommerce is missing out of the box.</p>
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=store-toolkit&tab=quick-enhancements' ) ); ?>" class="button button-primary">Quick Enhancements</a>
</div>

<style>
    .quick-enhancements-widget {
        background-color: #fff;
        padding: 20px;
        text-align: left;
        font-size: 16px;
    }

    .quick-enhancements-widget .widget-title {
        font-weight: bold;
        font-size: 22px;
        display: block;
        vertical-align: middle;
        text-align: left;
        margin-bottom: 20px;
    }

    .quick-enhancements-widget .widget-title img {
        margin-right: 10px;
        display: inline;
        width: 40px; 
        height: 40px;
        vertical-align: middle;
    }

    .quick-enhancements-widget p {
        margin-bottom: 20px;
        font-size: 16px;
    }
    
    .quick-enhancements-widget .widget-title h3 {
        display: inline;
        vertical-align: middle;
        padding: 0;
    }

    .quick-enhancements-widget .button {
        background: #7CB342;
        color: #FFFFFF;
        padding: 6px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
    }

    .quick-enhancements-widget .button:hover, .quick-enhancements-widget .button:focus, .quick-enhancements-widget .button:active {
        background: #7CB342;
    }
</style>
