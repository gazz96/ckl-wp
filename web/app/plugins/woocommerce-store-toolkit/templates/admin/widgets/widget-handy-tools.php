<?php
/**
 * Handy Tools Widget
 *
 * @package     Store Toolkit
 * @subpackage  Admin/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="handy-tools-widget">
    <div class="widget-title">
        <img src="<?php echo esc_url( WOO_ST_URL ); ?>/images/overview-icons/handy-tools.png" alt="Handy Tools" />
        <h3>Handy Tools</h3>
    </div>
    <p>Loads of handy tools for your store like mass deleting content (nuking), fixing broken variations, clearing transients, generating sample orders, and lots more.</p>
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=store-toolkit&tab=tools' ) ); ?>" class="button button-primary">Handy Tools</a>
</div>

<style>
    .handy-tools-widget {
        background-color: #fff;
        padding: 20px;
        text-align: left;
        font-size: 16px;
    }

    .handy-tools-widget .widget-title {
        font-weight: bold;
        font-size: 22px;
        display: block;
        vertical-align: middle;
        text-align: left;
        margin-bottom: 20px;
    }

    .handy-tools-widget .widget-title img {
        margin-right: 10px;
        display: inline;
        width: 40px; 
        height: 40px;
        vertical-align: middle;
    }

    .handy-tools-widget p {
        margin-bottom: 20px;
        font-size: 16px;
    }
    
    .handy-tools-widget .widget-title h3 {
        display: inline;
        vertical-align: middle;
        padding: 0;
    }

    .handy-tools-widget .button {
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

    .handy-tools-widget .button:hover, .handy-tools-widget .button:focus, .handy-tools-widget .button:active {
        background: #7CB342;
    }
</style>
