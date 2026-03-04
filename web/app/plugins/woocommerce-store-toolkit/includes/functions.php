<?php
// phpcs:disable.
/**
 * Store Toolkit Functions
 *
 * General functions available on both the front-end and admin.
 *
 * @package WooCommerce Store Toolkit
 * @version 2.3.11
 */

if ( is_admin() ) {

    /* Start of: WordPress Administration */

    // Includes.
    require_once WOO_ST_PATH . 'includes/admin.php';

    /**
     * Add the Store Toolkit admin menu.
     *
     * @since 2.3.11
     */
    function woo_st_admin_menu() {

        // Add a top level WooCommerce menu called Store Toolkit.
        add_menu_page( __( 'Store Toolkit', 'woocommerce-store-toolkit' ), __( 'Store Toolkit', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit', 'woo_st_html_page', 'dashicons-store', 56 );

        // Add Quick Enhancements submenu.
        add_submenu_page( 'store-toolkit', __( 'Enhancements', 'woocommerce-store-toolkit' ), __( 'Quick Enhancements', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit&tab=quick-enhancements', 'woo_st_html_page' );

        // Add Tools submenu.
        add_submenu_page( 'store-toolkit', __( 'Handy Tools', 'woocommerce-store-toolkit' ), __( 'Handy Tools', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit&tab=tools', 'woo_st_html_page' );

        // Growth Tools submenu. Bolded for emphasis.
        add_submenu_page( 'store-toolkit', __( 'Growth Tools', 'woocommerce-store-toolkit' ), '<strong style="color: #3abb06;">' . __( 'Growth Tools', 'woocommerce-store-toolkit' ) . '</strong>', 'manage_woocommerce', 'store-toolkit&tab=growth-tools', 'woo_st_html_page' );

        // Add Settings submenu.
        add_submenu_page( 'store-toolkit', __( 'Settings', 'woocommerce-store-toolkit' ), __( 'Settings', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit&tab=settings', 'woo_st_html_page' );

        // Add About page submenu.
        add_submenu_page( 'store-toolkit', __( 'About', 'woocommerce-store-toolkit' ), __( 'About', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit-about', 'woo_st_html_page_about' );

        // Add Help page submenu.
        add_submenu_page( 'store-toolkit', __( 'Help', 'woocommerce-store-toolkit' ), __( 'Help', 'woocommerce-store-toolkit' ), 'manage_woocommerce', 'store-toolkit-help', 'woo_st_html_page_help' );
    }
    add_action( 'admin_menu', 'woo_st_admin_menu', 11 );

    /**
     * Header output for the Store Toolkit admin pages.
     *
     * @since 2.3.11
     *
     * @param string $title The title of the page.
     * @param string $icon The icon to display.
     */
    function woo_st_template_header( $title = '', $icon = 'woocommerce' ) {

        if ( $title ) {
            $output = $title;
        } else {
            $output = __( 'Store Toolkit', 'woocommerce-store-toolkit' );
        }

        // Logo image url.
        $logo_url = WOO_ST_URL . '/images/vl-logo.svg';
?>

        <div class="wrap">
            <div class="woo-st-header">
                <a href="https://visser.com.au/" target="_blank"><img src="<?php echo esc_url( $logo_url ); ?>" alt="Visser Labs" class="vl-logo" width="150"></a>

                <?php
                woo_st_header_rate_review();
                ?>
            </div>

            <div id="icon-<?php echo esc_attr( $icon ); ?>" class="icon32 icon32-woocommerce-settings"><br /></div>
            <h1><?php echo esc_html( $output ); ?></h1>
        <?php
    }

    /**
     * Footer output for the Store Toolkit admin pages.
     *
     * @since 2.3.11
     */
    function woo_st_template_footer() {
        ?>
        </div>
        <!-- .wrap -->
<?php
    }

    /**
     * Outputs the rate/review html for the Store Toolkit admin pages.
     *
     * @since 2.3.11
     */
    function woo_st_header_rate_review() {
        $show = true;
        if ( function_exists( 'woo_vl_we_love_your_plugins' ) ) {
            if ( in_array( WOO_ST_DIRNAME, woo_vl_we_love_your_plugins() ) ) {
                $show = false;
            }
        }
        if ( $show ) {
            $rate_url = 'https://wordpress.org/support/view/plugin-reviews/woocommerce-store-toolkit/';
            echo '<div id="support-donate_rate" class="support-donate_rate">';
            echo '<p>';

            echo '<strong>' . esc_html__( 'Like this Plugin?', 'woocommerce-store-toolkit' ) . '</strong> ' .
                '<a href="' . esc_url( add_query_arg( array( 'rate' => '5' ), $rate_url ) ) . '#postform" target="_blank">' .
                esc_html__( 'Rate us 5-stars', 'woocommerce-store-toolkit' ) .
                ' <span style="color: #daa520;">&#9733;&#9733;&#9733;&#9733;&#9733;</span> ' .
                esc_html__( 'on WordPress.org', 'woocommerce-store-toolkit' ) . '</a>';

            echo '</p>';
            echo '</div>';
        }
    }

    /**
     * Relink all Products with the Simple Product Type.
     *
     * @since 2.3.11
     */
    function woo_st_relink_rogue_simple_type() {

        $updated_products = 0;
        $ignored_products = 0;

        // Get the Term ID for the Simple Product Type
        $term_taxonomy = 'product_type';
        $term_id       = term_exists( 'simple', $term_taxonomy );

        // Check if the Simple Product Type exists
        if ( $term_id == null || is_wp_error( $term_id ) ) {
            $message = __( 'The Term ID for the Simple Product Type could not be found within the WordPress Terms table (wp_terms), please de-activate and re-activate WooCommerce to resolve this.', 'woocommerce-store-toolkit' );
            woo_st_admin_notice( $message, 'error' );
            return;
        }
        $term_id = $term_id['term_id'];

        // Get a list of all Product Types
        $args  = array(
            'fields'     => 'ids',
            'hide_empty' => false,
        );
        $terms = get_terms( $term_taxonomy, $args );

        // Filter a list of Products that do not have a Product Type
        $post_type   = 'product';
        $args        = array(
            'post_type'      => $post_type,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => $term_taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $terms,
                    'operator' => 'NOT IN',
                ),
            ),
            'posts_per_page' => -1,
            'post_status'    => 'any',
        );
        $product_ids = new WP_Query( $args );
        if ( $product_ids->posts ) {
            foreach ( $product_ids->posts as $product_id ) {
                // First strip any corrupt Terms assigned to that Product
                wp_set_object_terms( $product_id, null, $term_taxonomy );
                // Assign the new Simple Term to that Product
                $response = wp_set_object_terms( $product_id, (int) $term_id, $term_taxonomy, true );
                if ( $response == null || is_wp_error( $response ) ) {
                    ++$ignored_products;
                    continue;
                }
                ++$updated_products;
            }

            if ( ! empty( $ignored_products ) ) {
                $message = sprintf( __( 'Some Products would not take the Simple Product Type or WordPress returned an error, we managed to update %1$d of %2$d', 'woocommerce-store-toolkit' ), $ignored_products, $updated_products );
            } elseif ( ! empty( $updated_products ) ) {
                $message = sprintf( __( 'We managed to update %d Products with the Simple Product Type, happy days!', 'woocommerce-store-toolkit' ), $updated_products );
            } else {
                $message = __( 'No existing Products had the Simple Product Type assigned to them', 'woocommerce-store-toolkit' );
            }
        } else {
            $message = __( 'No existing Products had the Simple Product Type assigned to them', 'woocommerce-store-toolkit' );
        }
    }

    /**
     * Delete corrupt Product Variations.
     *
     * @since 2.3.11
     */
    function woo_st_delete_corrupt_variations() {

        $post_type   = 'product_variation';
        $args        = array(
            'post_type'      => $post_type,
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        );
        $product_ids = new WP_Query( $args );
        if ( $product_ids->posts ) {
            foreach ( $product_ids->posts as $post_id ) {

                // Check if the Post Title is empty
                $post_title = get_the_title( $post_id );
                if ( ! empty( $post_title ) ) {
                    continue;
                }

                // Check if the Stock Status meta contains an invalid array
                $stock_status = get_post_meta( $post_id, '_stock_status', false );
                if ( count( $stock_status ) == 1 ) {
                    continue;
                }

                wp_delete_post( $post_id, true );
            }
        }
    }

    /**
     * Refresh Product Transients.
     * Clear the Product transients for all WooCommerce Products.
     *
     * @since 2.3.11
     */
    function woo_st_refresh_product_transients() {

        $post_type   = 'product';
        $args        = array(
            'post_type'      => $post_type,
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        );
        $product_ids = new WP_Query( $args );
        if ( $product_ids->posts ) {
            foreach ( $product_ids->posts as $product_id ) {
                wc_delete_product_transients( $product_id );
                delete_post_meta( $product_id, '_wc_average_rating' );
                delete_post_meta( $product_id, '_wc_rating_count' );
                delete_post_meta( $product_id, '_wc_review_count' );
            }
        }
    }

    /**
     * Recalculate all Subscriptions.
     *
     * @since 2.3.11
     */
    function woo_st_recalculate_all_subscriptions() {
        $subscriptions = wcs_get_subscriptions(
            array(
                'status' => array( 'active', 'on-hold', 'pending', 'pending-cancel' ),
                'limit'  => -1,
            )
        );

        if ( ! empty( $subscriptions ) ) {
            foreach ( $subscriptions as $subscription ) {
                $subscription->calculate_totals();
                $subscription->save();
            }
        }
    }

    /**
     * Remove the filename extension from a filename.
     *
     * @since 2.3.11
     *
     * @param string $filename The filename to remove the extension from.
     *
     * @return string $filename The filename without the extension.
     */
    function woo_st_remove_filename_extension( $filename ) {

        $extension = strrchr( $filename, '.' );
        $filename  = substr( $filename, 0, -strlen( $extension ) );

        return $filename;
    }

    /**
     * Get the product statuses.
     *
     * @since 2.3.11
     *
     * @return array $product_statuses The product statuses.
     */
    function woo_st_get_product_statuses() {
        $post_type        = 'product';
        $product_statuses = wp_count_posts( $post_type );
        // Trim off WooCommerce Order Statuses
        if ( ! empty( $product_statuses ) ) {
            $product_statuses = (array) $product_statuses;
            foreach ( $product_statuses as $product_status => $product_count ) {
                if (
                    strstr( $product_status, 'wc-' ) !== false ||
                    strstr( $product_status, 'request-' ) !== false
                ) {
                }
            }
        }
        return $product_statuses;
    }

    /**
     * Get the order statuses.
     *
     * @since 2.3.11
     *
     * @return array $order_statuses The order statuses.
     */
    function woo_st_get_order_statuses() {

        $terms = false;

        // Convert Order Status array into our magic sauce.
        $order_statuses = ( function_exists( 'wc_get_order_statuses' ) ? wc_get_order_statuses() : false );

        if ( ! empty( $order_statuses ) ) {
            $terms = array();
            foreach ( $order_statuses as $key => $order_status ) {
                $terms[] = (object) array(
                    'term_id' => $key,
                    'name'    => $order_status,
                    'slug'    => $key,
                    'count'   => wc_orders_count( $key ),
                );
            }
        }

        return $terms;
    }

    /**
     * Get the total number of Customers.
     *
     * @since 2.3.11
     *
     * @return int $total_customers The total number of Customers.
     */
    function woo_st_get_total_number_of_customers() {
        // Get an array with the user counts by role.
        $user_count = count_users();

        // Extract the number of customers.
        $total_customers = isset( $user_count['avail_roles']['customer'] ) ? $user_count['avail_roles']['customer'] : 0;

        // Return the total number of customers.
        return $total_customers;
    }

    /**
     * Get the total number of Product Reviews.
     *
     * @since 2.3.11
     *
     * @return int $total_reviews The total number of Product Reviews.
     */
    function woo_st_get_total_number_of_product_reviews() {
        // Get the WordPress database object.
        global $wpdb;

        // Query to count approved comments of type 'review'.
        $query = "
            SELECT COUNT(*)
            FROM {$wpdb->comments}
            WHERE comment_approved = 1
            AND comment_type = 'review';
        ";

        // Execute the query and get the result.
        $total_reviews = $wpdb->get_var( $query );

        // Return the total number of product reviews.
        return $total_reviews;
    }

    /**
     * Get the total sales revenue for a period.
     * Valid periods are 'today', 'yesterday', 'week', 'this_week', 'last_week', 'this_month', 'last_month', 'this_year', 'last_year'.
     * Uses the start of the week day defined in WordPress.
     *
     * @since 2.3.11
     *
     * @param string $period The period to get the total sales revenue for.
     *
     * @return float $total_sales The total sales revenue for the period.
     */
    function woo_st_get_total_sales( $period = 'today' ) {
        global $wpdb;

        // Determine the start and end dates based on the period.
        switch ( $period ) {
            case 'today':
                $start_date = new DateTime( 'today' );
                $end_date   = new DateTime( 'today 23:59:59' );
                break;
            case 'yesterday':
                $start_date = new DateTime( 'yesterday' );
                $end_date   = new DateTime( 'yesterday 23:59:59' );
                break;
            case 'week':
                $start_date = new DateTime( '-1 week' );
                $end_date   = new DateTime( 'now' );
                break;
            case 'this_week':
                $start_date = new DateTime( 'monday this week' );
                $end_date   = new DateTime( 'sunday this week 23:59:59' );
                break;
            case 'last_week':
                $start_date = new DateTime( 'monday last week' );
                $end_date   = new DateTime( 'sunday last week 23:59:59' );
                break;
            case 'this_month':
                $start_date = new DateTime( 'first day of this month' );
                $end_date   = new DateTime( 'last day of this month 23:59:59' );
                break;
            case 'last_month':
                $start_date = new DateTime( 'first day of last month' );
                $end_date   = new DateTime( 'last day of last month 23:59:59' );
                break;
            case 'this_year':
                $start_date = new DateTime( 'first day of january this year' );
                $end_date   = new DateTime( 'last day of december this year 23:59:59' );
                break;
            case 'last_year':
                $start_date = new DateTime( 'first day of january last year' );
                $end_date   = new DateTime( 'last day of december last year 23:59:59' );
                break;
            default:
                return 0;
        }

        // Format dates for SQL query.
        $start_date_sql = $start_date->format( 'Y-m-d H:i:s' );
        $end_date_sql   = $end_date->format( 'Y-m-d H:i:s' );

        // Determine if HPOS is enabled.
        $is_hpos_enabled = wc_get_container()->get( \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();

        if ( $is_hpos_enabled ) {
            // HPOS-compatible query.
            $query         = "
                SELECT SUM(o.total_amount)
                FROM {$wpdb->prefix}wc_orders o
                WHERE o.status = 'wc-completed'
                AND o.date_created_gmt BETWEEN %s AND %s;
            ";
            $total_revenue = $wpdb->get_var( $wpdb->prepare( $query, $start_date_sql, $end_date_sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        } else {
            // Legacy orders table query.
            $query         = "
                SELECT SUM(pm.meta_value)
                FROM {$wpdb->prefix}posts p
                INNER JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'shop_order'
                AND p.post_status = 'wc-completed'
                AND pm.meta_key = '_order_total'
                AND p.post_date BETWEEN %s AND %s;
            ";
            $total_revenue = $wpdb->get_var( $wpdb->prepare( $query, $start_date_sql, $end_date_sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        }

        return $total_revenue ? (float) $total_revenue : 0;
    }

    /**
     * Convert a Sale Status to a human-readable format.
     *
     * @param string $sale_status The sale status to convert.
     *
     * @return string $output The human-readable sale status.
     */
    function woo_st_convert_sale_status( $sale_status = '' ) {
        $output = $sale_status;

        if ( $sale_status ) {
            switch ( $sale_status ) {

                case 'cancelled':
                    $output = __( 'Cancelled', 'woocommerce-store-toolkit' );
                    break;

                case 'completed':
                    $output = __( 'Completed', 'woocommerce-store-toolkit' );
                    break;

                case 'on-hold':
                    $output = __( 'On-Hold', 'woocommerce-store-toolkit' );
                    break;

                case 'pending':
                    $output = __( 'Pending', 'woocommerce-store-toolkit' );
                    break;

                case 'processing':
                    $output = __( 'Processing', 'woocommerce-store-toolkit' );
                    break;

                case 'refunded':
                    $output = __( 'Refunded', 'woocommerce-store-toolkit' );
                    break;

                case 'failed':
                    $output = __( 'Failed', 'woocommerce-store-toolkit' );
                    break;
            }
        }

        return $output;
    }

    /**
     * Generate sample products.
     *
     * @param array $args The arguments for generating sample products.
     *
     * @return bool $success True if the sample products were generated successfully, false otherwise.
     */
    function woo_st_generate_sample_products( $args = array() ) {

        $defaults = array(
            'limit'             => 100,
            'product_name'      => 'Sample Product %count%',
            'short_description' => 'Short description for Sample Product %count%',
            'description'       => 'Description for Sample Product %count%',
            'sku'               => 'SAMPLE-%count%',
        );
        $args     = wp_parse_args( $args, $defaults );

        if ( empty( $args['limit'] ) ) {
            return false;
        }

        for ( $i = 0; $i < $args['limit']; $i++ ) {

            // @mod - WC only lets us use create_product via the WC_API

            $avatar_args = array(
                'default' => 'retro',
                'size'    => 512,
            );

            $post_excerpt = '';
            $post_content = '';
            $data         = array(
                'title'             => str_replace( '%count%', $i, $args['product_name'] ),
                'status'            => 'publish',
                'short_description' => str_replace( '%count%', $i, $args['short_description'] ),
                'description'       => str_replace( '%count%', $i, $args['description'] ),
                'menu_order'        => 0,
                'virtual'           => false,
                'featured'          => false,
                'sku'               => str_replace( '%count%', $i, $args['sku'] ),
                'image'             => array( get_avatar_url( sprintf( 'email-%d@example.com', $i ), $args ) ),
            );

            $new_product = array(
                'post_title'   => wc_clean( $data['title'] ),
                'post_status'  => isset( $data['status'] ) ? wc_clean( $data['status'] ) : 'publish',
                'post_type'    => 'product',
                'post_excerpt' => isset( $data['short_description'] ) ? $post_excerpt : '',
                'post_content' => isset( $data['description'] ) ? $post_content : '',
                'post_author'  => get_current_user_id(),
                'menu_order'   => isset( $data['menu_order'] ) ? intval( $data['menu_order'] ) : 0,
            );

            $post_ID = wp_insert_post( $new_product, true );

            if ( is_wp_error( $post_ID ) ) {
                error_log( 'create_sample_product: ' . $post_ID->get_error_message() );
            }

            // Set the Product Type
            $product_type  = null;
            $_product_type = get_the_terms( $post_ID, 'product_type' );
            if ( is_array( $_product_type ) ) {
                $_product_type = current( $_product_type );
                $product_type  = $_product_type->slug;
            }

            // Virtual.
            if ( isset( $data['virtual'] ) ) {
                update_post_meta( $post_ID, '_virtual', ( true === $data['virtual'] ) ? 'yes' : 'no' );
            }

            // Featured Product.
            if ( isset( $data['featured'] ) ) {
                update_post_meta( $post_ID, '_featured', ( true === $data['featured'] ) ? 'yes' : 'no' );
            }

            // SKU.
            if ( isset( $data['sku'] ) ) {
                $unique_sku = wc_product_has_unique_sku( $post_ID, $data['sku'] );
                if ( $unique_sku ) {
                    update_post_meta( $post_ID, '_sku', $data['sku'] );
                }
            }

            // Clear cache/transients.
            wc_delete_product_transients( $post_ID );
        }
        return true;
    }

    /**
     * Generate sample orders.
     *
     * @param array $args The arguments for generating sample orders.
     *
     * @return bool $success True if the sample orders were generated successfully, false otherwise.
     */
    function woo_st_generate_sample_orders( $args = array() ) {

        $defaults = array(
            'limit' => 100,
        );
        $args     = wp_parse_args( $args, $defaults );

        if ( empty( $args['limit'] ) ) {
            return false;
        }

        for ( $i = 0; $i < $args['limit']; $i++ ) {

            $order = new WC_Order();

            // Get a random customer.
            $customer = woo_st_get_random_customer();
            if ( ! $customer instanceof \WC_Customer ) {
                return false;
            }

            // Add random products to the order.
            $products = woo_st_get_random_products( 1, 10 );
            foreach ( $products as $product ) {
                $quantity = rand( 1, 10 );
                $order->add_product( $product, $quantity );
            }

            // Get random order date some time in the last year.
            $order_date = date( 'Y-m-d H:i:s', strtotime( '-' . rand( 1, 365 ) . ' days' ) );

            // Set order details.
            $order->set_customer_id( $customer->get_id() );
            $order->set_created_via( 'store-toolkit' );
            $order->set_date_created( $order_date );
            $order->set_currency( get_woocommerce_currency() );
            $order->set_billing_address_1( $customer->get_billing_address_1() );
            $order->set_billing_address_2( $customer->get_billing_address_2() );
            $order->set_billing_city( $customer->get_billing_city() );
            $order->set_billing_postcode( $customer->get_billing_postcode() );
            $order->set_billing_state( $customer->get_billing_state() );
            $order->set_billing_country( $customer->get_billing_country() );
            $order->set_shipping_address_1( $customer->get_shipping_address_1() );
            $order->set_shipping_address_2( $customer->get_shipping_address_2() );
            $order->set_shipping_city( $customer->get_shipping_city() );
            $order->set_shipping_postcode( $customer->get_shipping_postcode() );
            $order->set_shipping_state( $customer->get_shipping_state() );
            $order->set_shipping_country( $customer->get_shipping_country() );
            $order->set_status( woo_st_get_random_order_status() );
            $order->calculate_totals( true );
            $order->save();
        }

        return true;
    }

    /**
     * Get random products using wc_get_products and given parameters.
     *
     * @param integer $min Minimum number of products to return.
     * @param integer $max Number of products to return.
     * @return array $products Array of WC_Product objects.
     */
    function woo_st_get_random_products( $min = 1, $max = 10 ) {
        $products = array();
        $args     = array(
            'limit' => rand( $min, $max ),
        );

        $products = wc_get_products( $args );

        return $products;
    }

    /**
     * Get a random customer.
     *
     * @return WC_Customer $customer The random customer.
     */
    function woo_st_get_random_customer() {

        $user_id = false;

        $orderbys = array(
            'ID',
            'login',
            'nicename',
            'email',
            'url',
            'registered',
        );
        $orders   = array(
            'ASC',
            'DESC',
        );

        $guest    = (bool) rand( 0, 1 );
        $existing = (bool) rand( 0, 1 );
        $orderby  = rand( 0, ( count( $orderbys ) - 1 ) );
        $order    = rand( 0, 1 );
        $limit    = rand( 1, 100 );

        if ( $existing ) {
            $args  = array(
                'orderby' => $orderbys[ $orderby ],
                'number'  => $limit,
                'fields'  => 'ID',
            );
            $users = get_users( $args );
            if ( ! empty( $users ) ) {
                $user_id = $users[ array_rand( $users ) ];
            }
            if ( ! empty( $user_id ) ) {
                $customer = new WC_Customer( $user_id );
                return $customer;
            }
        }

        $customer = new WC_Customer( get_current_user_id() );
        return $customer;
    }

    /**
     * Get a random order status.
     *
     * @return string $order_status The random order status.
     */
    function woo_st_get_random_order_status() {

        $order_statuses = array(
            'completed',
            'processing',
            'on-hold',
            'failed',
        );
        return $order_statuses[ array_rand( $order_statuses ) ];
    }

    /**
     * Return the percentage difference between two numbers.
     *
     * @param int  $after The number after.
     * @param int  $before The number before.
     * @param bool $display_html Whether to display the percentage as HTML.
     *
     * @return int $output The percentage difference.
     */
    function woo_st_return_percentage( $after = 0, $before = 0, $display_html = true ) {

        $output = 0;
        if (
            absint( $after ) <> 0 &&
            absint( $before ) <> 0
        ) {
            $output = absint( ( ( absint( $after ) / absint( $before ) ) * 100 ) - 100 );
            if ( $display_html && absint( $output ) > 0 ) {
                $output = '+' . $output;
            }
        }
        return $output;
    }

    /**
     * Return the class for the percentage symbol.
     *
     * @param int $after The number after.
     * @param int $before The number before.
     *
     * @return string $output The class for the percentage symbol.
     */
    function woo_st_percentage_symbol_class( $after = 0, $before = 0 ) {

        $output     = '';
        $percentage = woo_st_return_percentage( $after, $before, false );
        if ( $percentage < 0 ) {
            $output = 'down';
        } elseif ( $percentage > 0 ) {
            $output = 'up';
        } else {
            $output = 'line';
        }
        return $output;
    }

    /**
     * Save the Store Toolkit settings.
     */
    function woo_st_settings_save() {

        // Permanently Delete Products bulk action
        woo_st_update_option( 'permanently_delete_products', ( isset( $_POST['permanently_delete_products'] ) ? absint( (int) $_POST['permanently_delete_products'] ) : 1 ) );

        // CRON settings
        $enable_cron = absint( $_POST['enable_cron'] );
        // Display additional notice if Enabled CRON is enabled/disabled
        if ( woo_st_get_option( 'enable_cron', 0 ) <> $enable_cron ) {
            $message = sprintf( __( 'CRON support has been %s.', 'woocommerce-store-toolkit' ), ( ( $enable_cron == 1 ) ? __( 'enabled', 'woocommerce-store-toolkit' ) : __( 'disabled', 'woocommerce-store-toolkit' ) ) );
            woo_st_admin_notice( $message );
        }
        woo_st_update_option( 'enable_cron', $enable_cron );
        woo_st_update_option( 'secret_key', sanitize_text_field( $_POST['secret_key'] ) );

        $message = __( 'Changes have been saved.', 'woocommerce-store-toolkit' );
        woo_st_admin_notice( $message );
    }

    /**
     * Save a single Quick Enhancement setting.
     *
     * @param string $setting_name The name of the setting to save.
     * @param string $setting_value The value of the setting to save.
     * @param array  $extra_data Extra data to save. Gets saved in an option named after the setting name appended with an underscore and the key.
     *
     * @return bool $success True if the setting was saved successfully, false otherwise.
     */
    function woo_st_quick_enhancement_save( $setting_name, $setting_value, $extra_data = array() ) {
        $saved = false;

        // Only allow saving known settings.
        $quick_enhancements_safe_list = array(
            'autocomplete_order',
            'unlock_variations',
            'unlock_related_orders',
            'show_used_coupons',
            'change_add_to_cart',
            'add_empty_cart_button',
            'adjust_number_products_on_archive',
            'delete_images_on_product_delete',
            'place_order_button',
            'enable_unit_pricing',
        );

        if ( in_array( $setting_name, $quick_enhancements_safe_list, true ) ) {
            $saved = woo_st_update_option( $setting_name, $setting_value );
        }

        // Check for extra data.
        if ( ! empty( $extra_data ) ) {
            foreach ( $extra_data as $key => $value ) {
                woo_st_update_option( $setting_name . '_' . $key, $value );
            }
        }

        return $saved;
    }

    /**
     * Maybe add a coupons list to the Order Preview popup.
     *
     * @param array    $data The Order details data.
     * @param WC_Order $order The Order object.
     *
     * @return array $data The Order details data.
     */
    function woo_st_maybe_add_coupons_list_in_order_preview_popup( $data, $order ) {
        // Check if the show_used_coupons Quick Enhancement is enabled.
        if ( ! woo_st_get_option( 'show_used_coupons', 0 ) ) {
            return $data;
        }

        $used_coupons = method_exists( $order, 'get_coupon_codes' ) ? $order->get_coupon_codes() : $order->get_used_coupons();
        $is_edit      = current_user_can('edit_shop_order', $order->get_id()); // phpcs:ignore

        // Check if the Order has any used Coupons.
        if ( empty( $used_coupons ) ) {
            return $data;
        }

        // Create the markup for the used Coupons list.
        $coupons_list = array_reduce(
            $used_coupons,
            function ( $c, $coupon ) use ( $is_edit ) {

                // add comma if there is already coupon present in the loop.
                $c = $c ? $c . ', ' : $c;

                $coupon_id   = wc_get_coupon_id_by_code( $coupon );
                $edit_coupon = $coupon_id && $is_edit ? admin_url( 'post.php?post=' . $coupon_id . '&action=edit' ) : '';
                $coupon_html = $edit_coupon ? sprintf( '<a class="used-coupon" href="%s">%s</a>', $edit_coupon, $coupon ) : sprintf( '<span class="used-coupon">%s</span>', $coupon );

                return $c . $coupon_html;
            },
            ''
        );

        ob_start();
        include WOO_ST_TEMPLATE_PATH . '/admin/orders' . DIRECTORY_SEPARATOR . 'order-preview-popup-coupons-list.php';
        $markup = ob_get_clean();

        $data['item_html'] = $markup . $data['item_html'];

        return $data;
    }
    add_filter( 'woocommerce_admin_order_preview_get_order_details', 'woo_st_maybe_add_coupons_list_in_order_preview_popup', 10, 2 );

    /**
     * Handle AJAX request to empty the cart.
     */
    function woo_st_ajax_empty_cart() {

        // Security check.
        check_ajax_referer( 'empty_cart_nonce', 'security' );

        // Check if the cart is not empty.
        if ( WC()->cart->get_cart_contents_count() > 0 ) {
            WC()->cart->empty_cart();
            wp_send_json_success();
        } else {
            wp_send_json_error( array( 'message' => __( 'Cart is already empty.', 'woocommerce-store-toolkit' ) ) );
        }
    }
    add_action( 'wp_ajax_woo_st_empty_cart', 'woo_st_ajax_empty_cart' );
    add_action( 'wp_ajax_nopriv_woo_st_empty_cart', 'woo_st_ajax_empty_cart' );

    /* End of: WordPress Administration */
} else {

    /* Start of: Front end */

    /**
     * Auto-complete Orders with a total of 0.
     *
     * @param int  $order_id The Order ID.
     * @param bool $posted Whether the Order has been posted.
     *
     * @return bool False.
     */
    function woo_st_autocomplete_order_status( $order_id = 0, $posted = false ) {

        if ( ! empty( $order_id ) ) {
            if ( class_exists( 'WC_Order' ) ) {
                $order = new WC_Order( $order_id );
                if ( $order->get_total() == 0 ) {
                    $order->update_status( 'completed', __( 'Auto-complete Order Status', 'woocommerce-store-toolkit' ) );
                }
            }
        }
        return false;
    }

    /**
     * Change the "Add to Cart" button text.
     *
     * @param string $button_text The button text.
     *
     * @return string The new button text.
     */
    function woo_st_maybe_change_add_to_cart_text( $button_text ) {
        $change_add_to_cart      = woo_st_get_option( 'change_add_to_cart', 0 );
        $change_add_to_cart_text = woo_st_get_option( 'change_add_to_cart_0', '', true );

        if ( $change_add_to_cart && ! empty( $change_add_to_cart_text ) ) {
            return esc_html( $change_add_to_cart_text );
        }

        return $button_text;
    }
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_st_maybe_change_add_to_cart_text', 10, 1 );
    add_filter( 'woocommerce_product_add_to_cart_text', 'woo_st_maybe_change_add_to_cart_text', 10, 1 );

    /**
     * Add Empty Cart Button to WooCommerce Legacy Cart Page.
     */
    function woo_st_add_empty_cart_button_legacy() {
        // Check if the Add Empty Cart Button Quick Enhancement is enabled.
        $add_empty_cart_button = woo_st_get_option( 'add_empty_cart_button', 0 );

        // Enqueue script only on Cart page.
        if ( is_cart() && $add_empty_cart_button ) {
            echo '<a class="button wc-empty-cart" href="' . esc_url( wc_get_cart_url() ) . '?empty-cart=true">' . __( 'Empty Cart', 'woocommerce-store-toolkit' ) . '</a>';
        }
    }
    add_action( 'woocommerce_cart_coupon', 'woo_st_add_empty_cart_button_legacy' );

    /**
     * Handle empty cart action.
     */
    function woo_st_handle_empty_cart_action() {
        if (isset($_GET['empty-cart']) && 'true' === $_GET['empty-cart']) { // phpcs:ignore
            WC()->cart->empty_cart();
        }
    }
    add_action( 'init', 'woo_st_handle_empty_cart_action' );

    /**
     * Filter Place Order Button Quick Enhancement to WooCommerce Legacy Checkout Page.
     *
     * @param string $button_text The button text.
     *
     * @return string The new button text.
     */
    function woo_st_filter_place_order_button_legacy( $button_text ) {
        $place_order_button      = woo_st_get_option( 'place_order_button', 0 );
        $place_order_button_text = woo_st_get_option( 'place_order_button_0', '', true );

        if ( $place_order_button && ! empty( $place_order_button_text ) ) {
            return esc_html( $place_order_button_text );
        } else {
            return $button_text;
        }
    }
    add_filter( 'woocommerce_order_button_text', 'woo_st_filter_place_order_button_legacy' );

    /**
     * Register custom block script and style.
     */
    function woo_st_enqueue_custom_block_assets() {
        wp_register_script(
            'woo-st-empty-cart-block',
            WOO_ST_URL . 'dist/empty-cart-block.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wc-blocks' ),
            filemtime( WOO_ST_PATH . 'dist/empty-cart-block.js' ),
            true
        );

        register_block_type(
            'custom/empty-cart',
            array(
                'editor_script' => 'woo-st-empty-cart-block',
            )
        );
    }
    add_action( 'init', 'woo_st_enqueue_custom_block_assets' );

    /**
     * Enqueue custom JavaScript for Cart & Checkout blocks.
     */
    function woo_st_enqueue_custom_block_scripts() {
        // Check if the Add Empty Cart Button Quick Enhancement is enabled.
        $add_empty_cart_button = woo_st_get_option( 'add_empty_cart_button', 0 );

        // Enqueue script only on Cart page.
        if ( is_cart() && $add_empty_cart_button ) {
            wp_enqueue_script( 'woo-st-empty-cart-frontend', WOO_ST_URL . 'dist/empty-cart-frontend.js', array( 'wp-element', 'wp-i18n', 'wp-data', 'wp-dom-ready' ), '1.0', true );
            wp_localize_script(
                'woo-st-empty-cart-frontend',
                'woo_st_empty_cart_params',
                array(
                    'empty_cart_nonce' => wp_create_nonce( 'empty_cart_nonce' ),
                )
            );
        }

        // Check if the Place Order Button Quick Enhancement is enabled.
        $place_order_button      = woo_st_get_option( 'place_order_button', 0 );
        $place_order_button_text = woo_st_get_option( 'place_order_button_0', '', true );

        // Enqueue script only on Checkout page.
        if ( is_checkout() && $place_order_button && ! empty( $place_order_button_text ) ) {
            wp_enqueue_script( 'woo-st-place-order-button-frontend', WOO_ST_URL . 'dist/place-order-button-frontend.js', array( 'wc-blocks-checkout' ), '1.0', true );
            wp_localize_script(
                'woo-st-place-order-button-frontend',
                'woo_st_place_order_button_params',
                array(
                    'place_order_button_text' => $place_order_button_text,
                )
            );
        }
    }
    add_action( 'wp_enqueue_scripts', 'woo_st_enqueue_custom_block_scripts' );

    /**
     * Adjust the number of products displayed on the Shop page.
     *
     * @param int $products_per_page The number of products per page.
     *
     * @return int $products_per_page The adjusted number of products per page.
     */
    function woo_st_maybe_adjust_products_per_page( $products_per_page ) {
        $adjust_number_products_on_archive = woo_st_get_option( 'adjust_number_products_on_archive', 0 );
        if ( $adjust_number_products_on_archive ) {
            $products_per_page = woo_st_get_option( 'adjust_number_products_on_archive_0', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() );
        }
        return $products_per_page;
    }
    add_filter( 'loop_shop_per_page', 'woo_st_maybe_adjust_products_per_page', 9999 );

    /* End of: Front end */
}

/**
 * Unlocks the Variations screen for WooCommerce 3.0+.
 *
 * @param array $args The arguments for unlocking the Variations screen.
 *
 * @return array $args The arguments for unlocking the Variations screen.
 */
function woo_st_admin_unlock_variations_screen( $args ) {

    $args['show_ui'] = true;
    return $args;
}

/**
 * Returns date of first Order received, any status
 *
 * @param string $date_format The date format to return.
 *
 * @return string $output The date of the first Order received.
 */
function woo_st_get_order_first_date( $date_format = 'd/m/Y' ) {

    $output = date( $date_format, mktime( 0, 0, 0, date( 'n' ), 1 ) );

    $post_type = 'shop_order';
    $args      = array(
        'post_type'   => $post_type,
        'orderby'     => 'post_date',
        'order'       => 'ASC',
        'numberposts' => 1,
        'post_status' => 'any',
    );
    $orders    = get_posts( $args );
    if ( ! empty( $orders ) ) {
        $output = date( $date_format, strtotime( $orders[0]->post_date ) );
    }
    return $output;
}

/**
 * Get the Order Date filter.
 *
 * @param string $filter The filter to apply.
 * @param string $format The format to return.
 *
 * @return string $output The Order Date filter.
 */
function woo_st_get_order_date_filter( $filter = '', $format = '' ) {

    $date_format = 'd-m-Y';
    $output      = false;
    if ( ! empty( $filter ) && ! empty( $format ) ) {
        switch ( $filter ) {

                // Today
            case 'today':
                if ( $format == 'from' ) {
                    $output = date( $date_format, strtotime( 'today' ) );
                } else {
                    $output = date( $date_format, strtotime( 'tomorrow' ) );
                }
                break;

                // This month
            case 'current_month':
                if ( $format == 'from' ) {
                    $output = date( $date_format, mktime( 0, 0, 0, date( 'n' ), 1 ) );
                } else {
                    $output = date( $date_format, mktime( 0, 0, 0, ( date( 'n' ) + 1 ), 0 ) );
                }
                break;
        }
    }
    return $output;
}

/**
 * Clear a dataset.
 *
 * @param string $export_type The export type to clear.
 * @param array  $data        The data to clear.
 *
 * @return bool $output True if the dataset was cleared successfully, false otherwise.
 */
function woo_st_clear_dataset( $export_type = '', $data = false ) {

    global $wpdb;

    if ( empty( $export_type ) ) {
        return false;
    }

    // Commence the drop
    woo_st_update_option( 'in_progress', $export_type );
    switch ( $export_type ) {

            // WooCommerce

        case 'product':
            $post_type = array( 'product', 'product_variation' );
            $args      = array(
                'post_type'   => $post_type,
                'fields'      => 'ids',
                'post_status' => woo_st_post_statuses(),
                'numberposts' => 100,
            );

            // Check for Product Status filter
            if ( ! empty( $data['product_status'] ) ) {
                $args['post_status'] = $data['product_status'];
            }

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_product', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'product' ) ) {
                $products = get_posts( $args );
                if ( ! empty( $products ) ) {
                    foreach ( $products as $product ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_post( $product, true );
                            // Product Category
                            if ( taxonomy_exists( 'product_cat' ) ) {
                                wp_set_object_terms( $product, null, 'product_cat' );
                            }
                            // Product Tag
                            if ( taxonomy_exists( 'product_tag' ) ) {
                                wp_set_object_terms( $product, null, 'product_tag' );
                            }
                            // Product Brand
                            $term_taxonomy = apply_filters( 'woo_st_brand_term_taxonomy', 'product_brand' );
                            if ( taxonomy_exists( $term_taxonomy ) ) {
                                wp_set_object_terms( $product, null, $term_taxonomy );
                            }
                            // Product Vendor
                            if ( taxonomy_exists( 'shop_vendor' ) ) {
                                wp_set_object_terms( $product, null, 'shop_vendor' );
                            }
                            // Attributes
                            $attributes_sql = 'SELECT `attribute_id` as ID, `attribute_name` as name, `attribute_label` as label, `attribute_type` as type FROM `' . $wpdb->prefix . 'woocommerce_attribute_taxonomies`';
                            $attributes     = $wpdb->get_results( $attributes_sql );
                            if ( ! empty( $attributes ) ) {
                                foreach ( $attributes as $attribute ) {
                                    if ( taxonomy_exists( 'pa_' . $attribute->name ) ) {
                                        wp_set_object_terms( $product, null, 'pa_' . $attribute->name );
                                    }
                                }
                            }
                            if ( function_exists( 'wc_delete_product_transients' ) ) {
                                wc_delete_product_transients( $product );
                            }
                            if ( class_exists( 'WC_Comments' ) ) {
                                $comments = new WC_Comments();
                                if ( method_exists( $comments, 'clear_transients' ) ) {
                                    $comments->clear_transients( $product );
                                }
                            }
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Product #%d', $product ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                $post_type = 'product';
                wp_cache_delete( $post_type );
                $post_type = 'product_variation';
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'product' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Products, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                delete_transient( 'wc_featured_products' );

                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'product_category':
            $term_taxonomy = 'product_cat';
            // Let's check if we're doing a filtered nuke...
            if ( ! empty( $data ) ) {
                foreach ( $data as $single_category ) {
                    $post_type = 'product';
                    $args      = array(
                        'post_type'   => $post_type,
                        'fields'      => 'ids',
                        'tax_query'   => array(
                            array(
                                'taxonomy' => $term_taxonomy,
                                'field'    => 'id',
                                'terms'    => $single_category,
                            ),
                        ),
                        'numberposts' => -1,
                    );

                    // Allow Plugin/Theme authors to add support for tactical nukes
                    $args = apply_filters( 'woo_st_clear_dataset_product_per_category', $args );

                    $products = get_posts( $args );
                    if ( $products ) {
                        foreach ( $products as $product ) {
                            if ( ! WOO_ST_DEBUG ) {
                                wp_delete_post( $product, true );
                            } else {
                                error_log( sprintf( '[store-toolkit] Delete Product #%d by Product Category', $product ) );
                            }
                        }
                    }
                }
            } else {
                $args = array(
                    'hide_empty' => false,
                    'number'     => 100,
                );

                // Allow Plugin/Theme authors to add support for tactical nukes
                $args = apply_filters( 'woo_st_clear_dataset_product_category', $args );

                // Loop through every 100 records until 0 is returned, might take awhile
                while ( $count = woo_st_return_count( 'product_category' ) ) {
                    $categories = get_terms( $term_taxonomy, $args );
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            if ( ! WOO_ST_DEBUG ) {
                                wp_delete_term( $category->term_id, $term_taxonomy );
                                $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = %d', $category->term_id ) );
                                $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->term_relationships . '` WHERE `term_taxonomy_id` = %d', $category->term_taxonomy_id ) );
                                // Check if WooCommerce woocommerce_termmeta exists
                                if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "woocommerce_termmeta'" ) ) {
                                    $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->prefix . 'woocommerce_termmeta` WHERE `woocommerce_term_id` = %d', $category->term_id ) );
                                }
                                delete_term_meta( $category->term_id, 'thumbnail_id' );
                            } else {
                                error_log( sprintf( '[store-toolkit] Delete Product Category #%d', $category->term_id ) );
                            }
                        }
                    }

                    // I don't get any pleasure out of doing this bit...
                    wp_cache_delete( $term_taxonomy );

                    // Check if count hasn't budged and we're in a permanent loop
                    if ( $count == woo_st_return_count( 'product_category' ) ) {
                        error_log( '[store-toolkit] Detected permanent loop nuking Product Categories, bugging out...' );
                        $output = false;
                        break;
                    }
                }
                if ( ! WOO_ST_DEBUG ) {
                    $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->term_taxonomy . '` WHERE `taxonomy` = %s', $term_taxonomy ) );
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product_category' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'product_tag':
            $term_taxonomy = 'product_tag';
            $args          = array(
                'fields'     => 'ids',
                'hide_empty' => false,
                'number'     => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_product_tag', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'product_tag' ) ) {
                $tags = get_terms( $term_taxonomy, $args );
                if ( ! empty( $tags ) ) {
                    foreach ( $tags as $tag ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $tag, $term_taxonomy );
                            $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = %d', $tag ) );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Product Tag #%d', $tag ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $term_taxonomy );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'product_tag' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Product Tags, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product_tag' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'product_brand':
            $term_taxonomy = apply_filters( 'woo_st_brand_term_taxonomy', 'product_brand' );
            $args          = array(
                'fields'     => 'ids',
                'hide_empty' => false,
                'number'     => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_product_brand', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'product_brand' ) ) {
                $tags = get_terms( $term_taxonomy, $args );
                if ( $tags ) {
                    foreach ( $tags as $tag ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $tag, $term_taxonomy );
                            $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = %d', $tag ) );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Product Brand #%d', $tag ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $term_taxonomy );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'product_brand' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Product Brands, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product_brand' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'product_vendor':
            $term_taxonomy = 'shop_vendor';
            $args          = array(
                'fields'     => 'ids',
                'hide_empty' => false,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_product_vendor', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'product_vendor' ) ) {
                $tags = get_terms( $term_taxonomy, $args );
                if ( $tags ) {
                    foreach ( $tags as $tag ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $tag, $term_taxonomy );
                            $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = %d', $tag ) );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Product Vendor #%d', $tag ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $term_taxonomy );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'product_vendor' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Product Vendors, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product_vendor' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'product_image':
            $post_type = array( 'product', 'product_variation' );
            $args      = array(
                'post_type'   => $post_type,
                'fields'      => 'ids',
                'post_status' => 'any',
                'numberposts' => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_product_image', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'product_image' ) ) {
                $products = get_posts( $args );
                // Check each Product for images
                if ( ! empty( $products ) ) {
                    $upload_dir = wp_upload_dir();
                    foreach ( $products as $product ) {
                        $args   = array(
                            'post_type'      => 'attachment',
                            'post_parent'    => $product,
                            'post_status'    => 'inherit',
                            'post_mime_type' => 'image',
                            'numberposts'    => -1,
                        );
                        $images = get_children( $args );
                        if ( ! empty( $images ) ) {
                            foreach ( $images as $image ) {
                                if ( ! WOO_ST_DEBUG ) {
                                    wp_delete_attachment( $image->ID, true );
                                } else {
                                    error_log( sprintf( '[store-toolkit] Delete Product Image #%d', $image->ID ) );
                                }
                            }
                        }
                    }
                } else {
                    // Check for WooCommerce-related images
                    $images_sql = 'SELECT `post_id` AS `ID` FROM `' . $wpdb->postmeta . "` WHERE `meta_key` = '_woocommerce_exclude_image' AND `meta_value` = 0";
                    $images     = $wpdb->get_col( $images_sql );
                    if ( ! empty( $images ) ) {
                        foreach ( $images as $image ) {
                            if ( ! WOO_ST_DEBUG ) {
                                wp_delete_attachment( $image, true );
                            } else {
                                error_log( sprintf( '[store-toolkit] Delete Product Image #%d', $image ) );
                            }
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                $post_type = 'product';
                wp_cache_delete( $post_type );
                $post_type = 'product_variation';
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'product_image' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Product Images, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_after_clear_dataset_product_image' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'order':
            $post_type     = 'shop_order';
            $term_taxonomy = 'shop_order_status';

            // Let's check if we're doing a filtered nuke...
            $order_status    = false;
            $order_date      = false;
            $order_date_from = false;
            $order_date_to   = false;
            if ( ! empty( $data ) ) {
                $order_status = ( isset( $data['status'] ) ? $data['status'] : false );
                $order_date   = ( isset( $data['date'] ) ? $data['date'] : false );
            }

            if ( ! empty( $order_status ) ) {
                foreach ( $order_status as $single_order => $value ) {
                    $args = array(
                        'limit'  => -1,
                        'status' => $single_order,
                    );

                    // Allow Plugin/Theme authors to add support for tactical nukes
                    $args = apply_filters( 'woo_st_clear_dataset_order_per_status', $args );

                    $orders_query = new WC_Order_Query( $args );
                    $orders       = $orders_query->get_orders();

                    if ( ! empty( $orders ) ) {
                        foreach ( $orders as $order ) {
                            $order->delete( true );
                        }
                    }
                }
            } else {
                $args = array(
                    'limit' => -1,
                );

                // Date
                if ( ! empty( $order_date ) ) {
                    switch ( $order_date ) {

                            // Today
                        case 'today':
                            $order_date_from = woo_st_get_order_date_filter( 'today', 'from' );
                            $order_date_to   = woo_st_get_order_date_filter( 'today', 'to' );
                            break;

                            // Today
                        case 'current_month':
                            $order_date_from = woo_st_get_order_date_filter( 'current_month', 'from' );
                            $order_date_to   = woo_st_get_order_date_filter( 'current_month', 'to' );
                            break;

                            // Fixed date
                        case 'manual':
                            $order_date_from = ( isset( $data['date_from'] ) ? $data['date_from'] : false );
                            $order_date_to   = ( isset( $data['date_to'] ) ? $data['date_to'] : false );
                            break;
                    }
                    $order_date_from = str_replace( '/', '-', $order_date_from );
                    $order_date_to   = str_replace( '/', '-', $order_date_to );

                    $order_date_from = date( 'd-m-Y', strtotime( $order_date_from, current_time( 'timestamp', 0 ) ) );
                    $order_date_to   = date( 'd-m-Y', strtotime( $order_date_to, current_time( 'timestamp', 0 ) ) );

                    $order_date_from = explode( '-', $order_date_from );
                    $order_date_to   = explode( '-', $order_date_to );

                    $order_date_from = array(
                        'year'   => absint( $order_date_from[2] ),
                        'month'  => absint( $order_date_from[1] ),
                        'day'    => absint( $order_date_from[0] ),
                        'hour'   => 0,
                        'minute' => 0,
                        'second' => 0,
                    );

                    $order_date_to = array(
                        'year'   => absint( $order_date_to[2] ),
                        'month'  => absint( $order_date_to[1] ),
                        'day'    => absint( $order_date_to[0] ),
                        'hour'   => 23,
                        'minute' => 23,
                        'second' => 59,
                    );

                    $args['date_query'] = array(
                        'column'    => 'date_created_gmt',
                        'before'    => $order_date_to,
                        'after'     => $order_date_from,
                        'inclusive' => true,
                    );
                }

                // Allow Plugin/Theme authors to add support for tactical nukes.
                $args = apply_filters( 'woo_st_clear_dataset_order', $args );

                $orders_query = new WC_Order_Query( $args );
                $orders       = $orders_query->get_orders();

                if ( ! empty( $orders ) ) {
                    foreach ( $orders as $order ) {
                        $order->delete( true );
                    }
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_order' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'tax_rate':
            if ( ! WOO_ST_DEBUG ) {
                $wpdb->query( 'TRUNCATE TABLE `' . $wpdb->prefix . 'woocommerce_tax_rates`' );
                $wpdb->query( 'TRUNCATE TABLE `' . $wpdb->prefix . 'woocommerce_tax_rate_locations`' );
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_tax_rate' );
            }
            $output = true;
            break;

        case 'download_permission':
            if ( ! WOO_ST_DEBUG ) {
                $wpdb->query( 'TRUNCATE TABLE `' . $wpdb->prefix . 'woocommerce_downloadable_product_permissions`' );

                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_download_permission' );
            }
            $output = true;
            break;

        case 'coupon':
            $post_type = 'shop_coupon';
            $args      = array(
                'post_type'   => $post_type,
                'fields'      => 'ids',
                'post_status' => woo_st_post_statuses(),
                'numberposts' => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_coupon', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'coupon' ) ) {
                $coupons = get_posts( $args );
                if ( ! empty( $coupons ) ) {
                    foreach ( $coupons as $coupon ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_post( $coupon, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Coupon #%d', $coupon ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'coupon' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Coupons, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_coupon' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'shipping_class':
            $term_taxonomy = 'product_shipping_class';
            $args          = array(
                'fields'     => 'ids',
                'hide_empty' => false,
                'number'     => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_shipping_class', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'shipping_class' ) ) {
                $shipping_classes = get_terms( $term_taxonomy, $args );
                if ( ! empty( $shipping_classes ) ) {
                    foreach ( $shipping_classes as $shipping_class ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $shipping_class, $term_taxonomy );
                            $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = %d', $shipping_class ) );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Shipping Class #%d', $shipping_class ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'shipping_class' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Shipping Classes, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_shipping_class' );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'woocommerce_log':
            if ( class_exists( 'WC_REST_System_Status_Controller' ) ) {
                $system_status = new WC_REST_System_Status_Controller();
                if ( method_exists( $system_status, 'get_environment_info' ) ) {
                    $environment   = $system_status->get_environment_info();
                    $log_directory = $environment['log_directory'];
                    if ( ! empty( $log_directory ) ) {
                        if ( file_exists( $log_directory ) ) {
                            $files = glob( $log_directory . '*.log' );
                            if ( $files !== false ) {
                                foreach ( $files as $file ) {
                                    if ( ! WOO_ST_DEBUG ) {
                                        unlink( $file );
                                    } else {
                                        error_log( sprintf( '[store-toolkit] Delete WooCommerce Log #%d', $file ) );
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                do_action( 'woo_st_clear_dataset_woocommerce_log' );
            }
            break;

        case 'attribute':
            if ( isset( $_POST['woo_st_attributes'] ) ) {
                $attributes_sql = 'SELECT `attribute_id` as ID, `attribute_name` as name, `attribute_label` as label, `attribute_type` as type FROM `' . $wpdb->prefix . 'woocommerce_attribute_taxonomies`';
                $attributes     = $wpdb->get_results( $attributes_sql );
                if ( $attributes ) {
                    foreach ( $attributes as $attribute ) {
                        $terms_sql = $wpdb->prepare( 'SELECT `term_id` FROM `' . $wpdb->prefix . 'term_taxonomy` WHERE `taxonomy` = %s', 'pa_' . $attribute->name );
                        $terms     = $wpdb->get_results( $terms_sql );
                        if ( ! empty( $terms ) ) {
                            foreach ( $terms as $term ) {
                                if ( ! WOO_ST_DEBUG ) {
                                    wp_delete_term( $term->term_id, 'pa_' . $attribute->name );
                                } else {
                                    error_log( sprintf( '[store-toolkit] Delete Attribute #%d', $term->term_id ) );
                                }
                            }
                        }
                        if ( ! WOO_ST_DEBUG ) {
                            $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->term_relationships . '` WHERE `term_taxonomy_id` = %d', $attribute->ID ) );
                            // Check if WooCommerce woocommerce_termmeta exists
                            if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "woocommerce_termmeta'" ) ) {
                                $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->prefix . 'woocommerce_termmeta` WHERE `meta_key` = %s', 'order_pa_' . $attribute->name ) );
                            }
                        }
                    }
                }
                if ( ! WOO_ST_DEBUG ) {
                    $wpdb->query( 'DELETE FROM `' . $wpdb->prefix . 'woocommerce_attribute_taxonomies`' );
                    delete_transient( 'wc_attribute_taxonomies' );

                    // Allow Plugin/Theme authors to perform their own tactical nukes when clearing this dataset
                    do_action( 'woo_st_clear_dataset_attribute' );
                }
            }
            $output = true;
            break;

            // 3rd Party

        case 'credit_card':
            $post_type = 'offline_payment';
            $args      = array(
                'post_type'   => $post_type,
                'fields'      => 'ids',
                'post_status' => woo_st_post_statuses(),
                'numberposts' => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_credit_card', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'credit_card' ) ) {
                $credit_cards = get_posts( $args );
                if ( ! empty( $credit_cards ) ) {
                    foreach ( $credit_cards as $credit_card ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_post( $credit_card, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Credit Card #%d', $credit_card ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'credit_card' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Credit Cards, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_csv':
            $post_type       = 'attachment';
            $post_mime_types = array( 'text/csv' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_csv', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_csv' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (CSV) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_csv' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (CSV), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_tsv':
            $post_type       = 'attachment';
            $post_mime_types = array( 'text/tab-separated-values' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_tsv', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_tsv' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (TSV) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_tsv' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (TSV), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_xls':
            $post_type       = 'attachment';
            $post_mime_types = array( 'application/vnd.ms-excel' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_xls', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_xls' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (XLS) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_xls' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (XLS), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_xlsx':
            $post_type       = 'attachment';
            $post_mime_types = array( 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_xlsx', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_xlsx' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (XLSX) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_xlsx' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (XLSX), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_xml':
            $post_type       = 'attachment';
            $post_mime_types = array( 'application/xml' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_xml', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_xml' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (XML) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_xml' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (XML), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'store_export_rss':
            $post_type       = 'attachment';
            $post_mime_types = array( 'application/rss+xml' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_store_export_rss', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'store_export_rss' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Store Export (RSS) #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'store_export_rss' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Store Export (RSS), bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'google_product_feed':
            if ( ! WOO_ST_DEBUG ) {
                if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "woocommerce_gpf_google_taxonomy'" ) ) {
                    $wpdb->query( 'TRUNCATE TABLE `' . $wpdb->prefix . 'woocommerce_gpf_google_taxonomy`' );
                }
            }
            $output = true;
            break;

            // WordPress

        case 'post':
            $post_type = 'post';
            $args      = array(
                'post_type'   => $post_type,
                'fields'      => 'ids',
                'post_status' => woo_st_post_statuses(),
                'numberposts' => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_post', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'post' ) ) {
                $posts = get_posts( $args );
                if ( $posts ) {
                    foreach ( $posts as $post ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_post( $post, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Post #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'post' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Posts, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'post_category':
            $term_taxonomy = 'category';
            $args          = array(
                'hide_empty' => false,
                'number'     => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_post_category', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'post_category' ) ) {
                $post_categories = get_terms( $term_taxonomy, $args );
                if ( $post_categories ) {
                    foreach ( $post_categories as $post_category ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $post_category->term_id, $term_taxonomy );
                            $wpdb->query( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = ' . $post_category->term_id );
                            $wpdb->query( 'DELETE FROM `' . $wpdb->term_relationships . '` WHERE `term_taxonomy_id` = ' . $post_category->term_taxonomy_id );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Post Category #%d', $post_category->term_id ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $term_taxonomy );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'post_category' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Post Categories, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                $wpdb->query( 'DELETE FROM `' . $wpdb->term_taxonomy . "` WHERE `taxonomy` = '" . $term_taxonomy . "'" );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'post_tag':
            $term_taxonomy = 'post_tag';
            $args          = array(
                'hide_empty' => false,
                'number'     => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_post_tag', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'post_tag' ) ) {
                $post_tags = get_terms( $term_taxonomy, $args );
                if ( $post_tags ) {
                    foreach ( $post_tags as $post_tag ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_term( $post_tag->term_id, $term_taxonomy );
                            $wpdb->query( 'DELETE FROM `' . $wpdb->terms . '` WHERE `term_id` = ' . $post_tag->term_id );
                            $wpdb->query( 'DELETE FROM `' . $wpdb->term_relationships . '` WHERE `term_taxonomy_id` = ' . $post_tag->term_taxonomy_id );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Post Tag #%d', $post_tag->term_id ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $term_taxonomy );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'post_tag' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Post Tags, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! WOO_ST_DEBUG ) {
                $wpdb->query( 'DELETE FROM `' . $wpdb->term_taxonomy . "` WHERE `taxonomy` = '" . $term_taxonomy . "'" );
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'link':
            if ( ! WOO_ST_DEBUG ) {
                $wpdb->query( 'TRUNCATE TABLE `' . $wpdb->prefix . 'links`' );
            }
            $output = true;
            break;

        case 'comment':
            $args = array(
                'number' => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_comment', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'comment' ) ) {
                $comments = get_comments( $args );
                if ( ! empty( $comments ) ) {
                    foreach ( $comments as $comment ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_comment( $comment->comment_ID, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Comment #%d', $comment->comment_ID ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( 'comment' );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'comment' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Comments, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;

        case 'media_image':
            $post_type       = 'attachment';
            $post_mime_types = array( 'image/jpg', 'image/jpeg', 'image/jpe', 'image/gif', 'image/png' );
            $args            = array(
                'post_type'      => $post_type,
                'fields'         => 'ids',
                'post_mime_type' => $post_mime_types,
                'post_status'    => woo_st_post_statuses(),
                'numberposts'    => 100,
            );

            // Allow Plugin/Theme authors to add support for tactical nukes
            $args = apply_filters( 'woo_st_clear_dataset_media_image', $args );

            // Loop through every 100 records until 0 is returned, might take awhile
            while ( $count = woo_st_return_count( 'media_image' ) ) {
                $images = get_posts( $args );
                if ( ! empty( $images ) ) {
                    foreach ( $images as $image ) {
                        if ( ! WOO_ST_DEBUG ) {
                            wp_delete_attachment( $image, true );
                        } else {
                            error_log( sprintf( '[store-toolkit] Delete Image #%d', $image ) );
                        }
                    }
                }

                // I don't get any pleasure out of doing this bit...
                wp_cache_delete( $post_type );

                // Check if count hasn't budged and we're in a permanent loop
                if ( $count == woo_st_return_count( 'media_image' ) ) {
                    error_log( '[store-toolkit] Detected permanent loop nuking Media, bugging out...' );
                    $output = false;
                    break;
                }
            }
            if ( ! isset( $output ) ) {
                $output = true;
            }
            break;
    }
    // Mission accomplished
    delete_option( WOO_ST_PREFIX . '_in_progress' );

    if ( ! isset( $output ) ) {
        $output = false;
    }

    return $output;
}

/**
 * Returns a list of post type statuses.
 *
 * @return array
 */
function woo_st_post_statuses() {

    $output = array(
        'publish',
        'pending',
        'draft',
        'auto-draft',
        'future',
        'private',
        'inherit',
        'trash',
    );
    return $output;
}

/**
 * Returns number of an Export type prior to nuke, used on Store Toolkit screen.
 *
 * @param string $export_type Export type.
 *
 * @return int $count Count of Export type.
 */
function woo_st_return_count( $export_type = '' ) {

    global $wpdb;

    $count_sql = null;
    switch ( $export_type ) {

            // WooCommerce

        case 'product':
            $post_type = array( 'product', 'product_variation' );
            $args      = array(
                'post_type'      => $post_type,
                'posts_per_page' => 1,
            );
            $query     = new WP_Query( $args );
            $count     = $query->found_posts;
            break;

        case 'product_image':
            $meta_key  = '_woocommerce_exclude_image';
            $count_sql = sprintf( 'SELECT COUNT(`post_id`) FROM `' . $wpdb->postmeta . "` WHERE `meta_key` = '%s'", $meta_key );
            break;

        case 'product_category':
            $term_taxonomy = 'product_cat';
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'product_tag':
            $term_taxonomy = 'product_tag';
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'product_brand':
            $term_taxonomy = apply_filters( 'woo_st_brand_term_taxonomy', 'product_brand' );
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'product_vendor':
            $term_taxonomy = 'shop_vendor';
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'order':
            $count = woo_st_get_orders_count();
            break;

        case 'tax_rate':
            $count_sql = 'SELECT COUNT(`tax_rate_id`) FROM `' . $wpdb->prefix . 'woocommerce_tax_rates`';
            break;

        case 'download_permission':
            $count_sql = 'SELECT COUNT(`download_id`) FROM `' . $wpdb->prefix . 'woocommerce_downloadable_product_permissions`';
            break;

        case 'coupon':
            $post_type = 'shop_coupon';
            if ( post_type_exists( $post_type ) ) {
                $count = wp_count_posts( $post_type );
            }
            break;

        case 'shipping_class':
            $term_taxonomy = apply_filters( 'woo_st_shipping_class_term_taxonomy', 'product_shipping_class' );
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'woocommerce_log':
            $count = 0;
            if ( class_exists( 'WC_REST_System_Status_Controller' ) ) {
                $system_status = new WC_REST_System_Status_Controller();
                if ( method_exists( $system_status, 'get_environment_info' ) ) {
                    $environment   = $system_status->get_environment_info();
                    $log_directory = $environment['log_directory'];
                    if ( ! empty( $log_directory ) ) {
                        if ( file_exists( $log_directory ) ) {
                            $files = glob( $log_directory . '*.log' );
                            if ( $files !== false ) {
                                $count = count( $files );
                            }
                        }
                    }
                }
            }
            break;

        case 'attribute':
            $count_sql = 'SELECT COUNT(`attribute_id`) FROM `' . $wpdb->prefix . 'woocommerce_attribute_taxonomies`';
            break;

            // 3rd Party

        case 'credit_card':
            $post_type = 'offline_payment';
            if ( post_type_exists( $post_type ) ) {
                $count = wp_count_posts( $post_type );
            }
            break;

        case 'store_export_csv':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'text/csv'";
            break;

        case 'store_export_tsv':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'text/tab-separated-values'";
            break;

        case 'store_export_xls':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'application/vnd.ms-excel'";
            break;

        case 'store_export_xlsx':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'";
            break;

        case 'store_export_xml':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'application/xml'";
            break;

        case 'store_export_rss':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` = 'application/rss+xml'";
            break;

        case 'google_product_feed':
            if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "woocommerce_gpf_google_taxonomy'" ) ) {
                $count_sql = 'SELECT COUNT(`taxonomy_term`) FROM `' . $wpdb->prefix . 'woocommerce_gpf_google_taxonomy`';
            }
            break;

            // WordPress

        case 'post':
            $post_type = 'post';
            if ( post_type_exists( $post_type ) ) {
                $count = wp_count_posts( $post_type );
            }
            break;

        case 'post_category':
            $term_taxonomy = 'category';
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'post_tag':
            $term_taxonomy = 'post_tag';
            if ( taxonomy_exists( $term_taxonomy ) ) {
                $count = wp_count_terms( $term_taxonomy );
            }
            break;

        case 'link':
            $count_sql = 'SELECT COUNT(`link_id`) FROM `' . $wpdb->prefix . 'links`';
            break;

        case 'comment':
            $count = wp_count_comments();
            break;

        case 'media_image':
            $count_sql = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . "` WHERE `post_mime_type` LIKE 'image%'";
            break;
    }
    if ( isset( $count ) || $count_sql ) {
        if ( isset( $count ) ) {
            if ( is_object( $count ) ) {
                $count_object = $count;
                $count        = 0;
                foreach ( $count_object as $key => $item ) {
                    $count = $item + $count;
                }
            }
            return $count;
        } elseif ( ! empty( $count_sql ) ) {
            $count = $wpdb->get_var( $count_sql );
        } else {
            $count = 0;
        }
        return $count;
    } else {
        return 0;
    }
}

/**
 * Get the visitor's IP address
 * Provided by Pippin Williamson, mentioned on WP Beginner (http://www.wpbeginner.com/wp-tutorials/how-to-display-a-users-ip-address-in-wordpress/)
 *
 * @return string $ip IP Address
 */
function woo_st_get_visitor_ip_address() {

    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        // check ip from share internet
        $ip = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        // to check ip is passed from proxy
        $ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
    } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
    }
    return apply_filters( 'woo_st_get_visitor_ip_address', $ip );
}

/**
 * Cron nuke support.
 *
 * @return bool $output True if successful, false otherwise.
 */
function woo_st_cron_nuke() {

    $output = false;

    // Let's prepare the nuke data
    $datasets = apply_filters( 'woo_st_cron_allowed_dataset_types', array_keys( woo_st_get_dataset_types() ) );
    $type     = ( isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : $type );
    if ( empty( $type ) ) {
        woo_st_error_log( sprintf( 'Error: %s', __( 'No dataset type was provided', 'woocommerce-store-toolkit' ) ) );
        return;
    }
    // Check that the type is in the list of allowed datasets
    if ( ! in_array( $type, $datasets ) ) {
        woo_st_error_log( sprintf( 'Error: %s', __( 'An invalid export type was provided', 'woocommerce-store-toolkit' ) ) );
        return;
    }
    $output = woo_st_clear_dataset( $type );

    return $output;
}

/**
 * Get the list of dataset types.
 *
 * @return array $types List of dataset types.
 */
function woo_st_get_dataset_types() {

    $types = array(
        'product',
        'product_category',
        'product_tag',
        'product_brand',
        'product_vendor',
        'product_image',
        'order',
        'tax_rate',
        'download_permission',
        'coupon',
        'shipping_class',
        'woocommerce_log',
        'attribute',
        'credit_card',
        'store_export_csv',
        'store_export_tsv',
        'store_export_xls',
        'store_export_xlsx',
        'store_export_xml',
        'store_export_rss',
        'google_product_feed',
        'post',
        'post_category',
        'post_tag',
        'link',
        'comment',
        'media_image',
    );
    return $types;
}

/**
 * Print an error message to the WooCommerce log.
 *
 * @param string $message The error message.
 */
function woo_st_error_log( $message = '' ) {

    if ( $message == '' ) {
        return;
    }

    if ( class_exists( 'WC_Logger' ) ) {
        $logger = new WC_Logger();
        $logger->add( WOO_ST_PREFIX, $message );
        return true;
    } else {
        // Fallback where the WooCommerce logging engine is unavailable
        error_log( sprintf( '[store-toolkit] %s', $message ) );
    }
}

/**
 * Get option wrapper.
 *
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @param bool   $allow_empty Allow empty value.
 *
 * @return mixed $output Option value.
 */
function woo_st_get_option( $option = null, $default = false, $allow_empty = false ) {

    $output = false;
    if ( $option !== null ) {
        $separator   = '_';
        $option_name = sanitize_key( WOO_ST_PREFIX . $separator . $option );
        $output      = get_option( $option_name, $default );
        if ( $allow_empty == false && $output != 0 && ( $output == false || $output == '' ) ) {
            $output = $default;
        }
    }
    return $output;
}

/**
 * Update option wrapper.
 *
 * @param string $option Option name.
 * @param mixed  $value Option value.
 *
 * @return bool $output True if successful, false otherwise.
 */
function woo_st_update_option( $option = null, $value = null ) {

    $output = false;
    if ( $option !== null && $value !== null ) {
        $separator   = '_';
        $option_name = sanitize_key( WOO_ST_PREFIX . $separator . $option );
        $output      = update_option( $option_name, $value );
    }
    return $output;
}

/**
 * Get order count.
 *
 * @return int $count Order count.
 */
function woo_st_get_orders_count() {
    $count = 0;
    foreach ( wc_get_order_statuses() as $status_slug => $status_name ) {
        $count += wc_orders_count( $status_slug );
    }
    return $count;
}

/**
 * Check if a plugin is active.
 *
 * @param string $plugin_name The plugin name. Example: 'woocommerce/woocommerce.php'.
 * @return bool True if active, false otherwise.
 */
function woo_st_is_plugin_active( $plugin_name ) {
    $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

    return in_array( $plugin_name, $active_plugins );
}

/**
 * Check if a plugin is installed.
 *
 * @param string $plugin_name The plugin name. Example: 'woocommerce/woocommerce.php'.
 * @return bool True if installed, false otherwise.
 */
function woo_st_is_plugin_installed( $plugin_name ) {
    return file_exists( WP_PLUGIN_DIR . '/' . $plugin_name );
}

/**
 * Delete images when a product is deleted.
 *
 * @param int $post_id The post ID.
 */
function woo_st_maybe_delete_product_images( $post_id ) {
    // Check if the quick enhancement option is turned on.
    if ( ! woo_st_get_option( 'delete_images_on_product_delete', 0 ) ) {
        return;
    }

    $product = wc_get_product( $post_id );
    if ( ! $product ) {
        return;
    }
    $featured_image_id  = $product->get_image_id();
    $image_galleries_id = $product->get_gallery_image_ids();
    if ( ! empty( $featured_image_id ) ) {
        wp_delete_post( $featured_image_id );
    }
    if ( ! empty( $image_galleries_id ) ) {
        foreach ( $image_galleries_id as $single_image_id ) {
            wp_delete_post( $single_image_id );
        }
    }
}
add_action( 'before_delete_post', 'woo_st_maybe_delete_product_images' );
