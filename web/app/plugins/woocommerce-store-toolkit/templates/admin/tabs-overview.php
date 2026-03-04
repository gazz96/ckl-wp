<?php
/**
 * Overview Tab Template
 *
 * @package     Store Toolkit
 * @subpackage  Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="container">
    <div class="grid">
        <div class="box">
            <?php require_once 'widgets/widget-sales-snapshot.php'; ?>
        </div>
        <div class="box">
            <?php require_once 'widgets/widget-store-snapshot.php'; ?>
        </div>
        <div class="box">
            <?php require_once 'widgets/widget-quick-enhancements.php'; ?>
        </div>
        <div class="box">
            <?php require_once 'widgets/widget-handy-tools.php'; ?>
        </div>
    </div>

    <div class="box full-width">
        <?php require_once 'widgets/widget-growth-tools.php'; ?>
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 30px 0;
    }
    
    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 30px;
    }
    
    .box {
        padding: 10px;
        text-align: center;
        box-shadow: 1px 2px 5px rgba(0,0,0,0.15);
        border-radius: 6px;
    }
    
    .full-width {
        width: 100%;
        background: linear-gradient(165deg, rgba(179, 194, 255, 0.3), rgba(225, 249, 199, 0.3));
        padding: 10px 0;
        margin: 30px 0;
        text-align: center;
    }

</style>
