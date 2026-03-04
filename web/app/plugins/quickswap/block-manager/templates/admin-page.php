<?php
/**
 * Block Manager Admin Page Template
 *
 * @package QuickSwap\Block_Manager
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap quickswap-block-collections">
    <h1><?php esc_html_e('Block Collections', 'quickswap'); ?></h1>

    <div id="quickswap-block-collections-app" class="quickswap-block-collections__app">
        <!-- React app will be mounted here -->
        <div class="quickswap-block-collections__loading">
            <?php esc_html_e('Loading...', 'quickswap'); ?>
        </div>
    </div>
</div>

<style>
    .quickswap-block-collections__app {
        margin-top: 20px;
        background: #fff;
        border: 1px solid #c3c4c7;
        padding: 20px;
        min-height: 400px;
    }

    .quickswap-block-collections__loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        color: #646970;
    }
</style>
