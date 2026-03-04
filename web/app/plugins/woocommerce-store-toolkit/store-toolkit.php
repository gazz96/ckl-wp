<?php
/**
 * Plugin Name: Store Toolkit for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/woocommerce-store-toolkit/
 * Description: Store Toolkit includes a growing set of commonly-used WooCommerce administration tools aimed at web developers and store maintainers.
 * Version: 2.4.4
 * Author: Visser Labs
 * Author URI: https://visser.com.au/
 * License: GPL2
 *
 * Text Domain: woocommerce-store-toolkit
 * Domain Path: /languages/
 *
 * Tested up to: 6.8.3
 * WC requires at least: 2.3
 * WC tested up to: 10.3.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'WOO_ST_DIRNAME', basename( __DIR__ ) );
define( 'WOO_ST_RELPATH', basename( __DIR__ ) . '/' . basename( __FILE__ ) );
define( 'WOO_ST_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_ST_URL', plugin_dir_url( __FILE__ ) );
define( 'WOO_ST_TEMPLATE_PATH', WOO_ST_PATH . 'templates/' );
define( 'WOO_ST_TEMPLATE_URL', WOO_ST_URL . 'templates/' );
define( 'WOO_ST_PREFIX', 'woo_st' );
define( 'WOO_ST_VERSION', '2.4.4' );

// Include required files.
require_once WOO_ST_PATH . 'common/common.php';
require_once WOO_ST_PATH . 'includes/functions.php';
require_once WOO_ST_PATH . 'includes/formatting.php';
require_once WOO_ST_PATH . 'includes/class-woo-st-unit-pricing.php';
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once WOO_ST_PATH . 'includes/wp-cli.php';
}

/**
 * For developers: Store Toolkit debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 */
if ( ! defined( 'WOO_ST_DEBUG' ) ) {
    define( 'WOO_ST_DEBUG', false );
}

/**
 * Initialize Store Toolkit core functionality.
 */
function woo_st_init() {
    // Initialize required classes that need to be available everywhere.
    new WOO_ST_Unit_Pricing();
}
add_action( 'init', 'woo_st_init' );

/**
 * Load plugin text domain.
 */
function woo_st_i18n() {
    load_plugin_textdomain( 'woocommerce-store-toolkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'woo_st_i18n' );

/**
 * Declare compatibility with WooCommerce HPOS.
 *
 * @since 2.3.10
 */
function woo_st_declare_hpos_compatibility() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
}
add_action( 'before_woocommerce_init', 'woo_st_declare_hpos_compatibility' );

if ( is_admin() ) {
    /* Start of: WordPress Administration */

    // Register our install script for first time install.
    include_once WOO_ST_PATH . 'includes/install.php';
    register_activation_hook( __FILE__, 'woo_st_install' );

    /**
     * Initialize Store Toolkit admin functionality.
     */
    function woo_st_admin_init() {
        // Admin-specific initialization here.
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        $action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );

        switch ( $action ) {

            case 'nuke':
                // Make sure we play nice with other WooCommerce and WordPress nukes.
                if ( ! isset( $_POST['woo_st_nuke'] ) ) {
                    $url = add_query_arg(
                        array(
                            'action'  => null,
                            'message' => __(
                                'A required $_POST element was not detected so the requested nuke will not proceed',
                                'woocommerce-store-toolkit'
                            ),
                        )
                    );
                    wp_safe_redirect( $url );
                    exit();
                }

                // We need to verify the nonce.
                check_admin_referer( 'nuke', 'woo_st_nuke' );

                if ( ! ini_get( 'safe_mode' ) ) {
                    set_time_limit( 0 );
                }

                // List of supported datasets.
                $datasets = woo_st_get_dataset_types();
                // Check if the re-commence nuke notice has been enabled.
                $in_progress = woo_st_get_option( 'in_progress', false );
                if ( isset( $_GET['dataset'] ) && ! empty( $in_progress ) ) {
                    $dataset = strtolower( sanitize_text_field( $_GET['dataset'] ) );
                    if ( in_array( $dataset, $datasets, true ) ) {
                        $response = woo_st_clear_dataset( $dataset );
                    }
                    return;
                }

                // WooCommerce.
                if ( isset( $_POST['woo_st_products'] ) ) {
                    $product_status = ( isset( $_POST['woo_st_products_status'] ) ? array_map( 'sanitize_text_field', $_POST['woo_st_products_status'] ) : false );
                    $args           = array();
                    if ( ! empty( $product_status ) ) {
                        $args['product_status'] = array_values( $product_status );
                    }
                    $response = woo_st_clear_dataset( 'product', $args );
                }
                if ( isset( $_POST['woo_st_products_category'] ) ) {
                    $categories = array_map( 'sanitize_text_field', $_POST['woo_st_products_category'] );
                    $response   = woo_st_clear_dataset( 'product_category', $categories );
                } elseif ( isset( $_POST['woo_st_product_categories'] ) ) {
                    $response = woo_st_clear_dataset( 'product_category' );
                }
                if ( isset( $_POST['woo_st_product_tags'] ) ) {
                    $response = woo_st_clear_dataset( 'product_tag' );
                }
                if ( isset( $_POST['woo_st_product_brands'] ) ) {
                    $response = woo_st_clear_dataset( 'product_brand' );
                }
                if ( isset( $_POST['woo_st_product_vendors'] ) ) {
                    $response = woo_st_clear_dataset( 'product_vendor' );
                }
                if ( isset( $_POST['woo_st_product_images'] ) ) {
                    $response = woo_st_clear_dataset( 'product_image' );
                }
                if ( isset( $_POST['woo_st_coupons'] ) ) {
                    $response = woo_st_clear_dataset( 'coupon' );
                }
                if ( isset( $_POST['woo_st_shipping_classes'] ) ) {
                    $response = woo_st_clear_dataset( 'shipping_class' );
                }
                if ( isset( $_POST['woo_st_woocommerce_logs'] ) ) {
                    $response = woo_st_clear_dataset( 'woocommerce_log' );
                }
                if ( isset( $_POST['woo_st_attributes'] ) ) {
                    $response = woo_st_clear_dataset( 'attribute' );
                }
                if ( isset( $_POST['woo_st_orders'] ) ) {
                    $args = array();
                    if ( isset( $_POST['woo_st_orders_status'] ) ) {
                        $args['status'] = array_map( 'absint', $_POST['woo_st_orders_status'] );
                    }
                    if ( isset( $_POST['woo_st_orders_date'] ) ) {
                        $args['date'] = sanitize_text_field( $_POST['woo_st_orders_date'] );
                        if ( 'manual' === $args['date'] ) {
                            $args['date_from'] = ( isset( $_POST['woo_st_orders_date_from'] ) ? sanitize_text_field( $_POST['woo_st_orders_date_from'] ) : false );
                            $args['date_to']   = ( isset( $_POST['woo_st_orders_date_to'] ) ? sanitize_text_field( $_POST['woo_st_orders_date_to'] ) : false );
                        }
                    }
                    $response = woo_st_clear_dataset( 'order', $args );
                }
                if ( isset( $_POST['woo_st_tax_rates'] ) ) {
                    $response = woo_st_clear_dataset( 'tax_rate' );
                }
                if ( isset( $_POST['woo_st_download_permissions'] ) ) {
                    $response = woo_st_clear_dataset( 'download_permission' );
                }

                // 3rd Party.
                if ( isset( $_POST['woo_st_creditcards'] ) ) {
                    $response = woo_st_clear_dataset( 'credit_card' );
                }
                if ( isset( $_POST['woo_st_storeexportscsv'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_csv' );
                }
                if ( isset( $_POST['woo_st_storeexportstsv'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_tsv' );
                }
                if ( isset( $_POST['woo_st_storeexportsxls'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_xls' );
                }
                if ( isset( $_POST['woo_st_storeexportsxlsx'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_xlsx' );
                }
                if ( isset( $_POST['woo_st_storeexportsxml'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_xml' );
                }
                if ( isset( $_POST['woo_st_storeexportsrss'] ) ) {
                    $response = woo_st_clear_dataset( 'store_export_rss' );
                }
                if ( isset( $_POST['woo_st_google_product_feed'] ) ) {
                    $response = woo_st_clear_dataset( 'google_product_feed' );
                }

                // WordPress.
                if ( isset( $_POST['woo_st_posts'] ) ) {
                    $response = woo_st_clear_dataset( 'post' );
                }
                if ( isset( $_POST['woo_st_post_categories'] ) ) {
                    $response = woo_st_clear_dataset( 'post_category' );
                }
                if ( isset( $_POST['woo_st_post_tags'] ) ) {
                    $response = woo_st_clear_dataset( 'post_tag' );
                }
                if ( isset( $_POST['woo_st_links'] ) ) {
                    $response = woo_st_clear_dataset( 'link' );
                }
                if ( isset( $_POST['woo_st_comments'] ) ) {
                    $response = woo_st_clear_dataset( 'comment' );
                }
                if ( isset( $_POST['woo_st_media_images'] ) ) {
                    $response = woo_st_clear_dataset( 'media_image' );
                }
                break;

            case 'relink-rogue-simple-type':
                // We need to verify the nonce.
                if ( ! empty( $_GET ) && check_admin_referer( 'woo_st_relink_rogue_simple_type' ) ) {
                    woo_st_relink_rogue_simple_type();
                    $url = add_query_arg(
                        array(
                            'action'       => null,
                            '_wpnonce'     => null,
                            'message'      => isset( $message ) ? $message : __( 'No rogue Products were detected.', 'woocommerce-store-toolkit' ),
                            'message_type' => 'success',
                        )
                    );
                    wp_safe_redirect( $url );
                    exit();
                }
                break;

            case 'delete-corrupt-variations':
                // We need to verify the nonce.
                if ( ! empty( $_GET ) && check_admin_referer( 'woo_st_delete_corrupt_variations' ) ) {
                    woo_st_delete_corrupt_variations();
                    $url = add_query_arg(
                        array(
                            'action'       => null,
                            '_wpnonce'     => null,
                            'message'      => __( 'Corrupt Product Variations have been deleted.', 'woocommerce-store-toolkit' ),
                            'message_type' => 'success',
                        )
                    );
                    wp_safe_redirect( $url );
                    exit();
                }
                break;

            case 'refresh-product-transients':
                // We need to verify the nonce.
                if ( ! empty( $_GET ) && check_admin_referer( 'woo_st_refresh_product_transients' ) ) {
                    woo_st_refresh_product_transients();
                    $url = add_query_arg(
                        array(
                            'action'       => null,
                            '_wpnonce'     => null,
                            'message'      => __( 'Product transients have been refreshed.', 'woocommerce-store-toolkit' ),
                            'message_type' => 'success',
                        )
                    );
                    wp_safe_redirect( $url );
                    exit();
                }
                break;

            case 'recalculate-all-subscriptions':
                // We need to verify the nonce.
                if ( ! empty( $_GET ) && check_admin_referer( 'woo_st_recalculate_all_subscriptions' ) ) {
                    woo_st_recalculate_all_subscriptions();
                    $url = add_query_arg(
                        array(
                            'action'       => null,
                            '_wpnonce'     => null,
                            'message'      => __( 'All Subscriptions have been recalculated.', 'woocommerce-store-toolkit' ),
                            'message_type' => 'success',
                        )
                    );
                    wp_safe_redirect( $url );
                    exit();
                }
                break;

            case 'woo_st-generate_products':
                // We need to verify the nonce.
                if ( ! empty( $_POST ) && check_admin_referer( 'generate_products', 'woo_st-generate_products' ) ) {

                    $args     = array(
                        'limit'             => ( isset( $_POST['limit'] ) ? sanitize_text_field( $_POST['limit'] ) : false ),
                        'product_name'      => ( isset( $_POST['product_name'] ) ? sanitize_text_field( $_POST['product_name'] ) : false ),
                        'short_description' => ( isset( $_POST['short_description'] ) ? sanitize_text_field( $_POST['short_description'] ) : false ),
                        'description'       => ( isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : false ),
                        'sku'               => ( isset( $_POST['sku'] ) ? sanitize_text_field( $_POST['sku'] ) : false ),
                    );
                    $response = woo_st_generate_sample_products( $args );
                    if ( $response ) {
                        $message = __( 'Sample Products have been generated.', 'woocommerce-store-toolkit' );
                        woo_st_admin_notice( $message );
                    }
                }
                break;

            case 'woo_st-generate_orders':
                // We need to verify the nonce.
                if ( ! empty( $_POST ) && check_admin_referer( 'generate_orders', 'woo_st-generate_orders' ) ) {

                    $args     = array(
                        'limit' => ( isset( $_POST['limit'] ) ? sanitize_text_field( $_POST['limit'] ) : false ),
                    );
                    $response = woo_st_generate_sample_orders( $args );
                    if ( $response ) {
                        $message = __( 'Sample Orders have been generated.', 'woocommerce-store-toolkit' );
                        woo_st_admin_notice( $message );
                    }
                }
                break;

            // Save changes on Settings screen.
            case 'save-settings':
                // We need to verify the nonce.
                if ( ! empty( $_POST ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo_st_save_settings' ) ) {
                    if ( check_admin_referer( 'woo_st_save_settings' ) ) {
                        woo_st_settings_save();
                    }
                }
                break;

            case 'heartbeat':
                return;

            default:
                // Category.
                $term_taxonomy = 'product_cat';
                add_action( $term_taxonomy . '_edit_form_fields', 'woo_st_category_data_meta_box', 11 );
                // Tag.
                $term_taxonomy = 'product_tag';
                add_action( $term_taxonomy . '_edit_form_fields', 'woo_st_tag_data_meta_box', 11 );
                // Brand.
                $term_taxonomy = 'product_brand';
                add_action( $term_taxonomy . '_edit_form_fields', 'woo_st_brand_data_meta_box', 11 );
                // Product Vendor.
                $term_taxonomy = 'yith_shop_vendor';
                add_action( $term_taxonomy . '_edit_form_fields', 'woo_st_product_vendor_data_meta_box', 11 );
                // User.
                add_action( 'show_user_profile', 'woo_st_user_orders', 9 );
                add_action( 'edit_user_profile', 'woo_st_user_orders', 9 );
                add_action( 'show_user_profile', 'woo_st_user_data_meta_box', 11 );
                add_action( 'edit_user_profile', 'woo_st_user_data_meta_box', 11 );
                if ( function_exists( 'woo_st_add_data_meta_boxes' ) ) {
                    add_action( 'add_meta_boxes', 'woo_st_add_data_meta_boxes', 10, 2 );
                }
                add_filter( 'manage_users_columns', 'woo_st_add_user_column', 11 );
                add_filter( 'manage_users_custom_column', 'woo_st_user_column_values', 11, 3 );
                add_filter( 'admin_footer_text', 'woo_st_admin_footer_text' );

                // Add a User column to the Orders screen.
                add_filter( 'manage_edit-shop_order_columns', 'woo_st_admin_order_column_headers', 20 );
                add_action( 'manage_shop_order_posts_custom_column', 'woo_st_admin_order_column_content', 10, 2 );

                add_filter( 'woocommerce_shop_order_list_table_columns', 'woo_st_admin_order_column_headers', 20 ); // WooCommerce orders tables.
                add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'woo_st_admin_order_column_content', 10, 2 ); // WooCommerce orders tables.
                break;

        }

        // Check for messages like success or error when we return to the page.
        if ( isset( $_GET['message'] ) ) {
            $message = sanitize_text_field( wp_kses_post( $_GET['message'] ) );
            // Get message type, must be 'success', 'error' or 'info'.
            $message_type = ( isset( $_GET['message_type'] ) && in_array( sanitize_text_field( $_GET['message_type'] ), array( 'success', 'error', 'info' ), true )
            ? sanitize_text_field( $_GET['message_type'] ) : 'info' );

            // Display the message.
            woo_st_admin_notice( $message, $message_type );
        }
    }
    add_action( 'admin_init', 'woo_st_admin_init' );

    /**
     * Display backend HTML page for the Store Toolkit.
     */
    function woo_st_default_html_page() {

        global $wpdb;

        $tab = false;
        if ( isset( $_GET['tab'] ) ) { // phpcs:ignore
            $tab = sanitize_text_field( $_GET['tab'] ); // phpcs:ignore
        }

        include_once WOO_ST_PATH . 'templates/admin/tabs.php';
    }

    /**
     * Display backend HTML page for the Store Toolkit.
     */
    function woo_st_html_page() {

        global $wpdb;

        woo_st_template_header();
        $action = woo_get_action();
        switch ( $action ) {

            case 'nuke':
                $message = __( 'The selected WooCommerce and/or WordPress details from the previous screen have been permanently erased from your store. <strong>Ta da!</strong>', 'woocommerce-store-toolkit' );
                woo_st_admin_notice_html( $message );

                woo_st_default_html_page();
                break;

            default:
                woo_st_default_html_page();
                break;

        }
        woo_st_template_footer();
    }

    /**
     * Display the Store Toolkit About page.
     *
     * @since 2.4.0
     */
    function woo_st_html_page_about() {
        woo_st_template_header();
        include_once WOO_ST_PATH . 'templates/admin/about.php';
        woo_st_template_footer();
    }

    /**
     * Display the Store Toolkit Help page.
     *
     * @since 2.4.0
     */
    function woo_st_html_page_help() {
        woo_st_template_header();
        include_once WOO_ST_PATH . 'templates/admin/help.php';
        woo_st_template_footer();
    }

    /**
     * Save quick enhancement settings via AJAX.
     */
    function woo_st_ajax_save_quick_enhancement() {
        // We need to verify the nonce using wp_verify_nonce.
        if ( ! empty( $_POST ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo_st_quick_enhancements' ) ) {
            $setting_name  = ( isset( $_POST['setting_name'] ) ? sanitize_text_field( $_POST['setting_name'] ) : false );
            $setting_value = ( isset( $_POST['setting_value'] ) ? sanitize_text_field( $_POST['setting_value'] ) : false );
            $extra_data    = ( isset( $_POST['extra_data'] ) ? $_POST['extra_data'] : false );

            // Sanitize extra data array one element at a time using array_map.
            if ( is_array( $extra_data ) ) {
                $extra_data = array_map( 'sanitize_text_field', $extra_data );
            }

            if ( woo_st_quick_enhancement_save( $setting_name, $setting_value, $extra_data ) ) {
                $message = __( 'Quick Enhancement setting saved.', 'woocommerce-store-toolkit' );
                woo_st_admin_notice( $message );
            } else {
                $message = __( 'Quick Enhancement setting could not be saved.', 'woocommerce-store-toolkit' );
                woo_st_admin_notice( $message, 'error' );
            }
        }
    }
    add_action( 'wp_ajax_woo_st_save_quick_enhancement', 'woo_st_ajax_save_quick_enhancement' );

    /* End of: WordPress Administration */

} else {

    /* Start of: Storefront */

    /**
     * Handle Store Toolkit CRON operations.
     *
     * Validates CRON access and executes scheduled tasks if authorized.
     *
     * @since 2.4.0
     * @return void
     */
    function woo_st_cron() {

        $action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );
        // This is where the CRON magic happens.
        if ( 'woo_st-cron' !== $action ) {
            return;
        }

        // Check that Store Toolkit is installed and activated or jump out.
        if ( ! function_exists( 'woo_st_get_option' ) ) {
            return;
        }

        // Return silent response and record to error log if CRON support is disabled, bad secret key provided or IP whitelist is in effect.
        if ( 0 === woo_st_get_option( 'enable_cron', 0 ) ) {
            woo_st_error_log( sprintf( 'Error: %s', __( 'Failed CRON access, CRON is disabled', 'woocommerce-store-toolkit' ) ) );
            return;
        }

        $key = ( isset( $_GET['key'] ) ? sanitize_text_field( $_GET['key'] ) : '' ); // phpcs:ignore
        if ( woo_st_get_option( 'secret_key', '' ) !== $key ) {
            $ip_address = woo_st_get_visitor_ip_address();
            // translators: %s: IP address of the failed CRON attempt.
            woo_st_error_log( sprintf( 'Error: %s', sprintf( __( 'Failed CRON attempt from %s, incorrect secret key', 'woocommerce-store-toolkit' ), $ip_address ) ) );
            return;
        }

        $ip_whitelist = apply_filters( 'woo_st_cron_ip_whitelist', false );
        if ( $ip_whitelist ) {
            $ip_address = woo_st_get_visitor_ip_address();
            if ( ! in_array( $ip_address, $ip_whitelist, true ) ) {
                // translators: %s: IP address of the failed CRON attempt.
                woo_st_error_log( sprintf( 'Error: %s', sprintf( __( 'Failed CRON attempt from %s, did not match IP whitelist', 'woocommerce-store-toolkit' ), $ip_address ) ) );
                return;
            }
        }

        // Return simple binary response.
        echo absint( woo_st_cron_nuke() );

        exit();
    }
    add_action( 'init', 'woo_st_cron' );

    /**
     * Initialize Store Toolkit frontend functionality.
     */
    function woo_st_frontend_init() {
        // Frontend-specific initialization.
        $autocomplete_order = get_option( WOO_ST_PREFIX . '_autocomplete_order', 0 );
        if ( $autocomplete_order ) {
            add_action( 'woocommerce_checkout_order_processed', 'woo_st_autocomplete_order_status', 10, 2 );
        }
    }
    add_action( 'init', 'woo_st_frontend_init' );

    /* End of: Storefront */

}
