<?php
/**
 * Unit Pricing functionality for WooCommerce Store Toolkit.
 *
 * @package WooCommerce Store Toolkit
 * @version 2.3.11
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class WOO_ST_Unit_Pricing.
 *
 * Handles unit pricing functionality for WooCommerce products.
 * Allows displaying price per unit (e.g. price per mL, L, oz, etc.).
 */
class WOO_ST_Unit_Pricing {
    /**
     * Constructor.
     */
    public function __construct() {
        // Admin hooks - these should be added regardless of is_admin().
        add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'add_product_unit_pricing_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_unit_pricing_fields' ) );
        add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'add_variation_unit_pricing_fields' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_unit_pricing_fields' ), 10, 1 );
        add_action( 'woocommerce_ajax_save_product_variation', array( $this, 'save_variation_unit_pricing_fields' ), 10, 1 );

        // Frontend hooks - only add these when not in admin.
        if ( ! is_admin() ) {
            add_filter( 'woocommerce_get_price_html', array( $this, 'display_unit_pricing' ), 10, 2 );
            add_filter( 'woocommerce_available_variation', array( $this, 'add_total_volume_to_variation' ), 10, 3 );
            add_filter( 'woocommerce_variation_price_html', array( $this, 'filter_variation_price_html' ), 10, 3 );
            add_action( 'wp_head', array( $this, 'add_unit_pricing_styles' ) );
        }
    }

    /**
     * Get available unit options.
     *
     * @return array Unit options.
     */
    private function get_unit_options() {
        return array(
            'mL'    => esc_html__( 'Milliliter (mL)', 'woocommerce-store-toolkit' ),
            'L'     => esc_html__( 'Liter (L)', 'woocommerce-store-toolkit' ),
            'fl oz' => esc_html__( 'Fluid Ounce (fl oz)', 'woocommerce-store-toolkit' ),
            'pt'    => esc_html__( 'Pint (pt)', 'woocommerce-store-toolkit' ),
            'qt'    => esc_html__( 'Quart (qt)', 'woocommerce-store-toolkit' ),
            'gal'   => esc_html__( 'Gallon (gal)', 'woocommerce-store-toolkit' ),
            'g'     => esc_html__( 'Gram (g)', 'woocommerce-store-toolkit' ),
            'kg'    => esc_html__( 'Kilogram (kg)', 'woocommerce-store-toolkit' ),
            'oz'    => esc_html__( 'Ounce (oz)', 'woocommerce-store-toolkit' ),
            'lb'    => esc_html__( 'Pound (lb)', 'woocommerce-store-toolkit' ),
        );
    }

    /**
     * Add custom fields to the product data meta box for unit pricing.
     */
    public function add_product_unit_pricing_fields() {
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_products' ) ) { // phpcs:ignore
            return;
        }

        global $post;
        $product = wc_get_product( $post->ID );
        if ( ! $product ) {
            return;
        }

        echo '<div class="options_group">';

        woocommerce_wp_checkbox(
            array(
                'id'          => '_woo_st_enable_unit_pricing',
                'label'       => esc_html__( 'Display Unit Pricing', 'woocommerce-store-toolkit' ),
                'description' => esc_html__( 'Check this box to display unit pricing for this product.', 'woocommerce-store-toolkit' ),
            )
        );

        woocommerce_wp_select(
            array(
                'id'          => '_woo_st_unit_name',
                'label'       => esc_html__( 'Unit Name', 'woocommerce-store-toolkit' ),
                'desc_tip'    => 'true',
                'description' => esc_html__( 'Select the unit name for the product volume.', 'woocommerce-store-toolkit' ),
                'options'     => $this->get_unit_options(),
            )
        );

        woocommerce_wp_checkbox(
            array(
                'id'          => '_woo_st_show_total_volume',
                'label'       => esc_html__( 'Show Total Volume', 'woocommerce-store-toolkit' ),
                'description' => esc_html__( 'Check this box to show the total volume of the product.', 'woocommerce-store-toolkit' ),
            )
        );

        // Add Total Volume field for simple products only.
        if ( $product->is_type( 'simple' ) ) {
            woocommerce_wp_text_input(
                array(
                    'id'                => '_woo_st_total_volume',
                    'label'             => esc_html__( 'Total Volume', 'woocommerce-store-toolkit' ),
                    'desc_tip'          => 'true',
                    'description'       => esc_html__( 'Enter the total volume of the product.', 'woocommerce-store-toolkit' ),
                    'type'              => 'number',
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min'  => '0',
                    ),
                )
            );
        }

        echo '</div>';
    }

    /**
     * Save the custom fields.
     *
     * @param int $post_id Product ID.
     */
    public function save_product_unit_pricing_fields( $post_id ) {
        // Check user capabilities.
        if ( ! current_user_can( 'edit_products' ) ) { // phpcs:ignore
            return;
        }

        // Verify nonce.
        if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) {
            return;
        }

        // Handle checkbox fields.
        $checkbox_fields = array( '_woo_st_enable_unit_pricing', '_woo_st_show_total_volume' );
        foreach ( $checkbox_fields as $field ) {
            $value = isset( $_POST[ $field ] ) ? 'yes' : 'no';
            update_post_meta( $post_id, $field, $value );
        }

        // Save unit name.
        if ( isset( $_POST['_woo_st_unit_name'] ) ) {
            update_post_meta( $post_id, '_woo_st_unit_name', sanitize_text_field( $_POST['_woo_st_unit_name'] ) );
        }

        // Save total volume for simple products.
        $product = wc_get_product( $post_id );
        if ( $product && $product->is_type( 'simple' ) ) {
            if ( isset( $_POST['_woo_st_total_volume'] ) ) {
                $total_volume = wc_clean( $_POST['_woo_st_total_volume'] );
                update_post_meta( $post_id, '_woo_st_total_volume', $total_volume );
            }
        }
    }

    /**
     * Add custom fields to product variations for unit pricing.
     *
     * @param int     $loop           Position in the loop.
     * @param array   $variation_data Variation data.
     * @param WP_Post $variation      Variation post data.
     */
    public function add_variation_unit_pricing_fields( $loop, $variation_data, $variation ) {
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ) {
            return;
        }

        // Check user capabilities.
        if ( ! current_user_can( 'edit_products' ) ) { // phpcs:ignore
            return;
        }

        // Total Volume field for variations.
        woocommerce_wp_text_input(
            array(
                'id'                => '_woo_st_total_volume[' . $variation->ID . ']',
                'label'             => __( 'Total Volume', 'woocommerce-store-toolkit' ),
                'description'       => __( 'Enter the total volume of this variation.', 'woocommerce-store-toolkit' ),
                'value'             => get_post_meta( $variation->ID, '_woo_st_total_volume', true ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0',
                ),
                'wrapper_class'     => 'form-row form-row-full',
            )
        );
    }

    /**
     * Save custom fields for product variations.
     *
     * @param int $variation_id Variation ID.
     */
    public function save_variation_unit_pricing_fields( $variation_id ) {
        if ( ! current_user_can( 'edit_products' ) ) { // phpcs:ignore
            return;
        }

        // Different nonce check for AJAX.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            if ( ! check_ajax_referer( 'save-variations', 'security', false ) ) {
                return;
            }
        } else { // phpcs:ignore
            // Regular nonce check for non-AJAX.
            if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) {
                return;
            }
        }

        // Save Total Volume for variations.
        if ( isset( $_POST['_woo_st_total_volume'] ) && is_array( $_POST['_woo_st_total_volume'] ) ) {
            $total_volume = isset( $_POST['_woo_st_total_volume'][ $variation_id ] ) ?
                wc_clean( $_POST['_woo_st_total_volume'][ $variation_id ] ) : '';

            if ( '' !== $total_volume ) {
                update_post_meta( $variation_id, '_woo_st_total_volume', $total_volume );
            } else {
                delete_post_meta( $variation_id, '_woo_st_total_volume' );
            }
        }
    }

    /**
     * Display unit pricing on product pages.
     *
     * @param string     $price   The product price HTML.
     * @param WC_Product $product The product object.
     * @return string Modified price HTML with unit pricing.
     */
    public function display_unit_pricing( $price, $product ) {
        // Early returns for invalid conditions.
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ||
            ! $product ||
            ! ( $product instanceof WC_Product ) ) {
            return $price;
        }

        // Check if unit pricing is enabled for this product.
        $enable_unit_pricing = $product->get_meta( '_woo_st_enable_unit_pricing', true );
        if ( 'yes' !== $enable_unit_pricing ) {
            return $price;
        }

        // Get parent product settings.
        $unit_name         = $product->get_meta( '_woo_st_unit_name', true );
        $show_total_volume = $product->get_meta( '_woo_st_show_total_volume', true );

        if ( empty( $unit_name ) ) {
            return $price;
        }

        // Only load variation handling for variable products.
        if ( $product->is_type( 'variable' ) ) {
            return $this->get_variable_product_unit_price( $price, $product, $unit_name, $show_total_volume );
        }

        // Simple products just need the basic unit price calculation.
        return $this->get_simple_product_unit_price( $price, $product, $unit_name, $show_total_volume );
    }

    /**
     * Get unit price HTML for variable products.
     *
     * @param string     $price            Original price HTML.
     * @param WC_Product $product          Product object.
     * @param string     $unit_name        Unit name.
     * @param string     $show_total_volume Whether to show total volume.
     * @return string Modified price HTML.
     */
    private function get_variable_product_unit_price( $price, $product, $unit_name, $show_total_volume ) {
        $variations = $product->get_available_variations();
        if ( empty( $variations ) ) {
            return $price;
        }

        $unit_prices = array();
        foreach ( $variations as $variation ) {
            $variation_obj   = wc_get_product( $variation['variation_id'] );
            $total_volume    = floatval( $variation_obj->get_meta( '_woo_st_total_volume', true ) );
            $variation_price = floatval( $variation_obj->get_price() );

            if ( empty( $total_volume ) || $total_volume <= 0 || $variation_price <= 0 ) {
                continue;
            }

            $unit_prices[] = array(
                'price'        => $variation_price / $total_volume,
                'total_volume' => $total_volume,
            );
        }

        if ( empty( $unit_prices ) ) {
            return $price;
        }

        $min_unit_price = min( array_column( $unit_prices, 'price' ) );
        $max_unit_price = max( array_column( $unit_prices, 'price' ) );

        if ( $min_unit_price === $max_unit_price ) {
            $unit_price_html = wc_price( $min_unit_price ) . ' per ' . $unit_name;
        } else {
            $unit_price_html = wc_price( $min_unit_price ) . ' - ' . wc_price( $max_unit_price ) . ' per ' . $unit_name;
        }

        if ( 'yes' === $show_total_volume ) {
            $unit_price_html .= ' (' . $total_volume . $unit_name . ' total)';
        }

        /**
         * Filter the unit price HTML output.
         *
         * @param string          $unit_price_html The formatted unit price HTML.
         * @param float          $min_unit_price  The minimum unit price.
         * @param float          $max_unit_price  The maximum unit price.
         * @param string         $unit_name       The unit name (e.g., mL, L, oz).
         * @param float          $total_volume    The total volume of the product.
         * @param WC_Product     $product         The product object.
         */
        $unit_price_html = apply_filters(
            'woo_st_unit_price_html',
            $unit_price_html,
            $min_unit_price,
            $max_unit_price,
            $unit_name,
            $total_volume,
            $product
        );

        return $price . '<br><small class="unit-price">' . $unit_price_html . '</small>';
    }

    /**
     * Get unit price HTML for simple products.
     *
     * @param string     $price            Original price HTML.
     * @param WC_Product $product          Product object.
     * @param string     $unit_name        Unit name.
     * @param string     $show_total_volume Whether to show total volume.
     * @return string Modified price HTML.
     */
    private function get_simple_product_unit_price( $price, $product, $unit_name, $show_total_volume ) {
        $total_volume  = floatval( $product->get_meta( '_woo_st_total_volume', true ) );
        $product_price = floatval( $product->get_price() );

        if ( empty( $total_volume ) || $total_volume <= 0 || $product_price <= 0 ) {
            return $price;
        }

        $unit_price      = $product_price / $total_volume;
        $unit_price_html = wc_price( $unit_price ) . ' per ' . $unit_name;

        if ( 'yes' === $show_total_volume ) {
            $unit_price_html .= ' (' . $total_volume . $unit_name . ' total)';
        }

        /**
         * Filter the unit price HTML output.
         *
         * @param string     $unit_price_html The formatted unit price HTML.
         * @param float      $unit_price      The calculated unit price.
         * @param string     $unit_name       The unit name (e.g., mL, L, oz).
         * @param float      $total_volume    The total volume of the product.
         * @param WC_Product $product         The product object.
         */
        $unit_price_html = apply_filters(
            'woo_st_unit_price_html',
            $unit_price_html,
            $unit_price,
            $unit_name,
            $total_volume,
            $product
        );

        return $price . '<br><small class="unit-price">' . $unit_price_html . '</small>';
    }

    /**
     * Add custom CSS for unit pricing display.
     */
    public function add_unit_pricing_styles() {
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ) {
            return;
        }
        ?>
        <style>
            .unit-price {
                display: block;
                font-size: 0.875em;
                color: #666;
                margin-top: 0.5em;
            }
        </style>
        <?php
    }

    /**
     * Add total volume and unit pricing to variation data.
     *
     * @param array                $variation_data Variation data.
     * @param WC_Product_Variable  $product        Variable product.
     * @param WC_Product_Variation $variation      Variation product.
     * @return array Modified variation data.
     */
    public function add_total_volume_to_variation( $variation_data, $product, $variation ) {
        // Check if unit pricing is enabled.
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ) {
            return $variation_data;
        }

        $enable_unit_pricing = $product->get_meta( '_woo_st_enable_unit_pricing', true );
        if ( 'yes' !== $enable_unit_pricing ) {
            return $variation_data;
        }

        // Get settings.
        $unit_name         = $product->get_meta( '_woo_st_unit_name', true );
        $show_total_volume = $product->get_meta( '_woo_st_show_total_volume', true );
        $total_volume      = floatval( $variation->get_meta( '_woo_st_total_volume', true ) );

        // Add total volume to variation data.
        $variation_data['total_volume'] = $total_volume;

        // Calculate and add unit price if we have valid data.
        if ( ! empty( $total_volume ) && $total_volume > 0 && ! empty( $variation_data['display_price'] ) ) {
            $unit_price      = $variation_data['display_price'] / $total_volume;
            $unit_price_html = wc_price( $unit_price ) . ' per ' . $unit_name;

            if ( 'yes' === $show_total_volume ) {
                $unit_price_html .= ' (' . $total_volume . $unit_name . ' total)';
            }

            /**
             * Filter the unit price HTML output.
             *
             * @param string               $unit_price_html The formatted unit price HTML.
             * @param float                $unit_price      The calculated unit price.
             * @param string               $unit_name       The unit name (e.g., mL, L, oz).
             * @param float                $total_volume    The total volume of the product.
             * @param WC_Product_Variation $variation       The variation object.
             * @param WC_Product_Variable  $product         The parent product object.
             */
            $unit_price_html = apply_filters(
                'woo_st_unit_price_html',
                $unit_price_html,
                $unit_price,
                $unit_name,
                $total_volume,
                $variation,
                $product
            );

            // Append unit price to variation's price HTML.
            $variation_data['price_html'] = $variation_data['price_html'] . '<small class="unit-price">' . $unit_price_html . '</small>';
        }

        return $variation_data;
    }

    /**
     * Filter variation price HTML to add unit pricing.
     *
     * @param string               $price_html     The price HTML.
     * @param WC_Product_Variation $variation      Variation product object.
     * @param WC_Product_Variable  $parent_product Parent product object.
     * @return string Modified price HTML.
     */
    public function filter_variation_price_html( $price_html, $variation, $parent_product ) {
        // Check if unit pricing is enabled.
        if ( ! woo_st_get_option( 'enable_unit_pricing', 0 ) ) {
            return $price_html;
        }

        $enable_unit_pricing = $parent_product->get_meta( '_woo_st_enable_unit_pricing', true );
        if ( 'yes' !== $enable_unit_pricing ) {
            return $price_html;
        }

        // Get settings.
        $unit_name         = $parent_product->get_meta( '_woo_st_unit_name', true );
        $show_total_volume = $parent_product->get_meta( '_woo_st_show_total_volume', true );
        $total_volume      = floatval( $variation->get_meta( '_woo_st_total_volume', true ) );
        $variation_price   = floatval( $variation->get_price() );

        if ( empty( $total_volume ) || $total_volume <= 0 || $variation_price <= 0 ) {
            return $price_html;
        }

        // Calculate and format unit price.
        $unit_price      = $variation_price / $total_volume;
        $unit_price_html = wc_price( $unit_price ) . ' per ' . $unit_name;

        if ( 'yes' === $show_total_volume ) {
            $unit_price_html .= ' (' . $total_volume . $unit_name . ' total)';
        }

        /**
         * Filter the unit price HTML output.
         *
         * @param string               $unit_price_html The formatted unit price HTML.
         * @param float                $unit_price      The calculated unit price.
         * @param string               $unit_name       The unit name (e.g., mL, L, oz).
         * @param float                $total_volume    The total volume of the product.
         * @param WC_Product_Variation $variation       The variation object.
         * @param WC_Product_Variable  $parent_product  The parent product object.
         */
        $unit_price_html = apply_filters(
            'woo_st_unit_price_html',
            $unit_price_html,
            $unit_price,
            $unit_name,
            $total_volume,
            $variation,
            $parent_product
        );

        return $price_html . '<br><small class="unit-price">' . $unit_price_html . '</small>';
    }
}
