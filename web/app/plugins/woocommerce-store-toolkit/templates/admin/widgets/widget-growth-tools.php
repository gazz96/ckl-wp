<?php
/**
 * Growth Tools Widget
 *
 * @package     Store Toolkit
 * @subpackage  Admin/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="growth-tools-widget">
    <div class="widget-title">
        <img src="<?php echo esc_url( WOO_ST_URL ); ?>/images/overview-icons/growth-tools.png" alt="Growth Tools" />
        <h3>Growth Tools</h3>
    </div>
    <p>View our growing collection of free recommended growth tools to enhance your store in multiple ways. From adding advanced coupon features, wholesale, adding product feeds, and even making your store multi-vendor.</p>
    <a href="<?php echo admin_url( 'admin.php?page=store-toolkit&tab=growth-tools' ); ?>" class="button button-primary">Get Free Growth Tools</a>
</div>

<style>
    .growth-tools-widget {
        padding: 20px;
        text-align: left;
        font-size: 16px;
    }

    .growth-tools-widget .widget-title {
        font-weight: bold;
        font-size: 22px;
        display: block;
        vertical-align: middle;
        text-align: left;
        margin-bottom: 20px;
    }

    .growth-tools-widget .widget-title img {
        margin-right: 10px;
        display: inline;
        width: 40px; 
        height: 40px;
        vertical-align: middle;
    }
    
    .growth-tools-widget .widget-title h3 {
        display: inline;
        vertical-align: middle;
        padding: 0;
    }

    .growth-tools-widget p {
        margin-bottom: 20px;
        font-size: 16px;
    }

    .growth-tools-widget .button {
        background: #6C7BFF;
        color: #FFFFFF;
        padding: 6px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
    }

    .growth-tools-widget .button:hover, .growth-tools-widget .button:focus, .growth-tools-widget .button:active {
        background: #6C7BFF;
    }

</style>
