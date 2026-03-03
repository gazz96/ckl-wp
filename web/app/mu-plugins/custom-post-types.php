<?php
/**
 * Plugin Name: Custom Post Types
 * Description: Registers custom post types for the website.
 */

function register_custom_post_types() {
    // Register Vehicle Post Type
    $vehicle_labels = [
        'name'                  => _x('Vehicles', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Vehicle', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Vehicles', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Vehicle', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Vehicle', 'textdomain'),
        'new_item'              => __('New Vehicle', 'textdomain'),
        'edit_item'             => __('Edit Vehicle', 'textdomain'),
        'view_item'             => __('View Vehicle', 'textdomain'),
        'all_items'             => __('All Vehicles', 'textdomain'),
        'search_items'          => __('Search Vehicles', 'textdomain'),
        'parent_item_colon'     => __('Parent Vehicles:', 'textdomain'),
        'not_found'             => __('No vehicles found.', 'textdomain'),
        'not_found_in_trash'    => __('No vehicles found in Trash.', 'textdomain'),
        'featured_image'        => _x('Vehicle Image', 'Overrides the “Featured Image” phrase for this post type.', 'textdomain'),
        'set_featured_image'    => _x('Set vehicle image', 'Overrides the “Set featured image” phrase for this post type.', 'textdomain'),
        'remove_featured_image' => _x('Remove vehicle image', 'Overrides the “Remove featured image” phrase for this post type.', 'textdomain'),
        'use_featured_image'    => _x('Use as vehicle image', 'Overrides the “Use as featured image” phrase for this post type.', 'textdomain'),
        'archives'              => _x('Vehicle archives', 'The post type archive label used in nav menus.', 'textdomain'),
        'insert_into_item'      => _x('Insert into vehicle', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post).', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this vehicle', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post).', 'textdomain'),
        'filter_items_list'     => _x('Filter vehicles list', 'Screen reader text for the filter links heading on the post type listing screen.', 'textdomain'),
        'items_list_navigation' => _x('Vehicles list navigation', 'Screen reader text for the pagination heading on the post type listing screen.', 'textdomain'),
        'items_list'            => _x('Vehicles list', 'Screen reader text for the items list heading on the post type listing screen.', 'textdomain'),
    ];

    $vehicle_args = [
        'labels'             => $vehicle_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'vehicle'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => ['title', 'editor', 'thumbnail'],
        'menu_icon'          => 'dashicons-car',
    ];

    register_post_type('vehicle', $vehicle_args);
}

add_action('init', 'register_custom_post_types');

/**
 * Adds a meta box for vehicle details.
 */
function vehicle_details_meta_box() {
    add_meta_box(
        'vehicle_details',
        'Vehicle Details',
        'vehicle_details_meta_box_html',
        'vehicle'
    );
}
add_action('add_meta_boxes', 'vehicle_details_meta_box');

/**
 * Renders the HTML for the vehicle details meta box.
 *
 * @param WP_Post $post The post object.
 */
function vehicle_details_meta_box_html($post) {
    wp_nonce_field('vehicle_details_save', 'vehicle_details_nonce');

    // Get existing values
    $vehicle_type = get_post_meta($post->ID, '_vehicle_type', true);
    $passenger_capacity = get_post_meta($post->ID, '_vehicle_passenger_capacity', true);
    $doors = get_post_meta($post->ID, '_vehicle_doors', true);
    $luggage = get_post_meta($post->ID, '_vehicle_luggage', true);
    $has_air_conditioning = get_post_meta($post->ID, '_vehicle_has_air_conditioning', true);
    $transmission = get_post_meta($post->ID, '_vehicle_transmission', true);
    $fuel_type = get_post_meta($post->ID, '_vehicle_fuel_type', true);
    $plate_number = get_post_meta($post->ID, '_vehicle_plate_number', true);
    $units_available = get_post_meta($post->ID, '_vehicle_units_available', true);
    $price_per_day = get_post_meta($post->ID, '_vehicle_price_per_day', true);
    $late_fee_per_hour = get_post_meta($post->ID, '_vehicle_late_fee_per_hour', true);
    $grace_period_minutes = get_post_meta($post->ID, '_vehicle_grace_period_minutes', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="vehicle_type">Vehicle Type</label></th>
            <td>
                <select id="vehicle_type" name="vehicle_type" class="widefat" required>
                    <option value="">Select Type</option>
                    <option value="sedan" <?php selected($vehicle_type, 'sedan'); ?>>Sedan</option>
                    <option value="compact" <?php selected($vehicle_type, 'compact'); ?>>Compact</option>
                    <option value="mpv" <?php selected($vehicle_type, 'mpv'); ?>>MPV</option>
                    <option value="luxury_mpv" <?php selected($vehicle_type, 'luxury_mpv'); ?>>Luxury MPV</option>
                    <option value="suv" <?php selected($vehicle_type, 'suv'); ?>>SUV</option>
                    <option value="4x4" <?php selected($vehicle_type, '4x4'); ?>>4x4</option>
                    <option value="motorcycle" <?php selected($vehicle_type, 'motorcycle'); ?>>Motorcycle</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_passenger_capacity">Passenger Capacity</label></th>
            <td>
                <input type="number" id="vehicle_passenger_capacity" name="vehicle_passenger_capacity" value="<?php echo esc_attr($passenger_capacity); ?>" class="widefat" min="1" max="50" required>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_doors">Doors</label></th>
            <td>
                <input type="number" id="vehicle_doors" name="vehicle_doors" value="<?php echo esc_attr($doors); ?>" class="widefat" min="1" max="10">
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_luggage">Luggage Capacity</label></th>
            <td>
                <input type="number" id="vehicle_luggage" name="vehicle_luggage" value="<?php echo esc_attr($luggage); ?>" class="widefat" min="0" max="20">
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_has_air_conditioning">Air Conditioning</label></th>
            <td>
                <input type="checkbox" id="vehicle_has_air_conditioning" name="vehicle_has_air_conditioning" value="1" <?php checked($has_air_conditioning, '1'); ?>>
                <label for="vehicle_has_air_conditioning">Yes, this vehicle has air conditioning</label>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_transmission">Transmission</label></th>
            <td>
                <select id="vehicle_transmission" name="vehicle_transmission" class="widefat">
                    <option value="automatic" <?php selected($transmission, 'automatic'); ?>>Automatic</option>
                    <option value="manual" <?php selected($transmission, 'manual'); ?>>Manual</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_fuel_type">Fuel Type</label></th>
            <td>
                <input type="text" id="vehicle_fuel_type" name="vehicle_fuel_type" value="<?php echo esc_attr($fuel_type); ?>" class="widefat" placeholder="e.g., Petrol, Diesel, Hybrid">
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_plate_number">Plate Number</label></th>
            <td>
                <input type="text" id="vehicle_plate_number" name="vehicle_plate_number" value="<?php echo esc_attr($plate_number); ?>" class="widefat" required>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_units_available">Units Available</label></th>
            <td>
                <input type="number" id="vehicle_units_available" name="vehicle_units_available" value="<?php echo esc_attr($units_available); ?>" class="widefat" min="1" required>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_price_per_day">Price Per Day (RM)</label></th>
            <td>
                <input type="number" step="0.01" id="vehicle_price_per_day" name="vehicle_price_per_day" value="<?php echo esc_attr($price_per_day); ?>" class="widefat" min="0" required>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_late_fee_per_hour">Late Fee Per Hour (RM)</label></th>
            <td>
                <input type="number" step="0.01" id="vehicle_late_fee_per_hour" name="vehicle_late_fee_per_hour" value="<?php echo esc_attr($late_fee_per_hour); ?>" class="widefat" min="0" placeholder="Optional">
                <p class="description">Optional: Charge per hour for late returns. Leave empty to use system default.</p>
            </td>
        </tr>
        <tr>
            <th><label for="vehicle_grace_period_minutes">Grace Period (Minutes)</label></th>
            <td>
                <input type="number" id="vehicle_grace_period_minutes" name="vehicle_grace_period_minutes" value="<?php echo esc_attr($grace_period_minutes); ?>" class="widefat" min="0" step="15">
                <p class="description">Grace period before late fees are applied. Default: 0 minutes.</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Saves the vehicle details meta box data.
 *
 * @param int $post_id The post ID.
 */
function save_vehicle_details_meta($post_id) {
    if (!isset($_POST['vehicle_details_nonce']) || !wp_verify_nonce($_POST['vehicle_details_nonce'], 'vehicle_details_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (get_post_type($post_id) !== 'vehicle' || !current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save all vehicle meta fields
    $fields = array(
        'vehicle_type',
        'vehicle_passenger_capacity',
        'vehicle_doors',
        'vehicle_luggage',
        'vehicle_transmission',
        'vehicle_fuel_type',
        'vehicle_plate_number',
        'vehicle_units_available',
        'vehicle_price_per_day',
        'vehicle_late_fee_per_hour',
        'vehicle_grace_period_minutes',
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save checkbox
    $has_air_conditioning = isset($_POST['vehicle_has_air_conditioning']) ? '1' : '0';
    update_post_meta($post_id, '_vehicle_has_air_conditioning', $has_air_conditioning);

    // Sync to WooCommerce if plugin is active
    if (class_exists('CKL_Booking_Manager')) {
        sync_vehicle_to_woocommerce_product($post_id);
    }
}
add_action('save_post_vehicle', 'save_vehicle_details_meta', 20);

/**
 * Sync Vehicle CPT to WooCommerce Bookable Product
 */
function sync_vehicle_to_woocommerce_product($post_id) {
    // Check if this is a vehicle
    if (get_post_type($post_id) !== 'vehicle') {
        return;
    }

    // Don't sync on revisions or autosaves
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    // Get or create WooCommerce product
    $product_id = get_post_meta($post_id, '_vehicle_woocommerce_product_id', true);

    if (!$product_id) {
        // Create new bookable product
        $product = new WC_Product_Booking();
        $product->set_name(get_the_title($post_id));
        $product->set_status('publish');

        // Set booking duration to 1 day
        $product->set_duration(1);
        $product->set_duration_unit('day');
        $product->set_min_duration(1);
        $product->set_max_duration(30);

        // Set base price
        $price = get_post_meta($post_id, '_vehicle_price_per_day', true);
        if ($price) {
            $product->set_regular_price($price);
        }

        $product_id = $product->save();
        update_post_meta($post_id, '_vehicle_woocommerce_product_id', $product_id);
        update_post_meta($product_id, '_vehicle_id', $post_id);

        // Update sync status
        update_post_meta($post_id, '_vehicle_sync_status', 'success');
        update_post_meta($post_id, '_vehicle_last_synced', current_time('mysql'));
    } else {
        // Update existing product
        $product = wc_get_product($product_id);

        if ($product) {
            $product->set_name(get_the_title($post_id));

            // Update price
            $price = get_post_meta($post_id, '_vehicle_price_per_day', true);
            if ($price) {
                $product->set_regular_price($price);
            }

            // Update stock/availability
            $units = get_post_meta($post_id, '_vehicle_units_available', true);
            if ($units) {
                $product->set_stock_quantity($units);
            }

            $product->save();

            update_post_meta($post_id, '_vehicle_sync_status', 'success');
            update_post_meta($post_id, '_vehicle_last_synced', current_time('mysql'));
        }
    }
}
