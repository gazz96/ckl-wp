<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="content">
    <div class="nav-tab-wrapper">
        <a data-tab-id="overview" class="nav-tab<?php echo esc_attr( woo_st_admin_active_tab( 'overview' ) ); ?>" href="
                                                            <?php
                                                            echo esc_url(
                                                                add_query_arg(
                                                                    array(
                                                                        'page' => 'store-toolkit',
                                                                        'tab'  => 'overview',
                                                                    ),
                                                                    'admin.php'
                                                                )
                                                            );
                                                            ?>
        "><?php esc_html_e( 'Overview', 'woocommerce-store-toolkit' ); ?></a>

        <a data-tab-id="tools" class="nav-tab<?php echo esc_attr( woo_st_admin_active_tab( 'quick-enhancements' ) ); ?>" href="
                                                                <?php
                                                                echo esc_url(
                                                                    add_query_arg(
                                                                        array(
                                                                            'page' => 'store-toolkit',
                                                                            'tab'  => 'quick-enhancements',
                                                                        ),
                                                                        'admin.php'
                                                                    )
                                                                );
                                                                ?>
        "><?php esc_html_e( 'Quick Enhancements', 'woocommerce-store-toolkit' ); ?></a>

        <a data-tab-id="tools" class="nav-tab<?php echo esc_attr( woo_st_admin_active_tab( 'tools' ) ); ?>" href="
                                                        <?php
                                                        echo esc_url(
                                                            add_query_arg(
                                                                array(
                                                                    'page' => 'store-toolkit',
                                                                    'tab'  => 'tools',
                                                                ),
                                                                'admin.php'
                                                            )
                                                        );
                                                                ?>
        "><?php esc_html_e( 'Handy Tools', 'woocommerce-store-toolkit' ); ?></a>

        <a data-tab-id="growth-tools" class="nav-tab<?php echo esc_attr( woo_st_admin_active_tab( 'growth-tools' ) ); ?>" href="
                                                                <?php
                                                                echo esc_url(
                                                                    add_query_arg(
                                                                        array(
                                                                            'page' => 'store-toolkit',
                                                                            'tab'  => 'growth-tools',
                                                                        ),
                                                                        'admin.php'
                                                                    )
                                                                );
                                                                ?>
        "><?php esc_html_e( 'Growth Tools', 'woocommerce-store-toolkit' ); ?></a>

        <a data-tab-id="settings" class="nav-tab<?php echo esc_attr( woo_st_admin_active_tab( 'settings' ) ); ?>" href="
                                                                    <?php
                                                                    echo esc_url(
                                                                        add_query_arg(
                                                                            array(
                                                                                'page' => 'store-toolkit',
                                                                                'tab'  => 'settings',
                                                                            ),
                                                                            'admin.php'
                                                                        )
                                                                    );
                                                                    ?>
        "><?php esc_html_e( 'Settings', 'woocommerce-store-toolkit' ); ?></a>

    </div>
    <?php woo_st_tab_template( $tab ); ?>

</div>
<!-- #content -->

<div id="progress" style="display:none;">
    <p><?php esc_html_e( 'Chosen WooCommerce details are being nuked, this process can take awhile. Time for a beer?', 'woocommerce-store-toolkit' ); ?></p>
    <img src="<?php echo esc_url( WOO_ST_URL . '/images/progress.gif' ); ?>" alt="" />
    <hr />
    <h2><?php esc_html_e( 'Just to clarify...', 'woocommerce-store-toolkit' ); ?></h2>
    <p><?php esc_html_e( 'Just to clarify what\'s going on behind the progress bar in case the dredded \'white screen\' appears or a a 500 Internal Server Error is returned:', 'woocommerce-store-toolkit' ); ?></p>
    <ol class="ol-disc">
        <li><?php esc_html_e( 'First we enter a loop that checks if any records for the selected dataset exist', 'woocommerce-store-toolkit' ); ?></li>
        <li><?php esc_html_e( 'Then we ask WordPress (via WP_Query) for a list of 100 ID\'s from the selected dataset (e.g. 100 Product ID\'s/Product Category ID\'s/Order ID\'s, etc.)', 'woocommerce-store-toolkit' ); ?></li>
        <li><?php esc_html_e( 'We enter a second loop that permanently deletes each ID that WordPress gave us', 'woocommerce-store-toolkit' ); ?></li>
        <li><?php esc_html_e( 'When that first 100 records are no more we ask for the next 100, rinse and repeat, rinse and repeat, ...', 'woocommerce-store-toolkit' ); ?></li>
        <li><?php esc_html_e( 'Once we\'ve nuked every record for the selected datasets we can finally show the success screen notice <strong>:)</strong>', 'woocommerce-store-toolkit' ); ?></li>
    </ol>
    <p><?php esc_html_e( 'Where things can go wrong during this process is:', 'woocommerce-store-toolkit' ); ?></p>
    <ul class="ul-disc">
        <li><?php esc_html_e( 'We hit the 30 second server timeout configured on some hosting server\'s that kills the active process (ours), or', 'woocommerce-store-toolkit' ); ?></li>
        <li><?php esc_html_e( 'WordPress maxes out its memory allocation looping through each batch of 100 ID\'s', 'woocommerce-store-toolkit' ); ?></li>
    </ul>
    <p><?php esc_html_e( 'Re-opening Store Toolkit from the WordPress Administration and hitting continue will resolve most issues. Happy nuking! <strong>:)</strong>', 'woocommerce-store-toolkit' ); ?></p>
</div>
<!-- #progress -->
