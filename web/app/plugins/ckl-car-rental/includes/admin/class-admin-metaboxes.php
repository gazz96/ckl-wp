<?php
/**
 * CKL Admin Meta Boxes
 *
 * Handles custom admin meta boxes for vehicles and bookings
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Admin_Meta_Boxes {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'add_vehicle_metaboxes'));
        add_action('save_post_vehicle', array(__CLASS__, 'save_vehicle_meta'), 20, 2);
        add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
    }

    /**
     * Add meta boxes for vehicles
     */
    public static function add_vehicle_metaboxes() {
        // Vehicle details
        add_meta_box(
            'vehicle_details',
            __('Vehicle Details', 'ckl-car-rental'),
            array(__CLASS__, 'vehicle_details_meta_box_html'),
            'vehicle',
            'normal',
            'high'
        );

        // Vehicle pricing
        add_meta_box(
            'vehicle_pricing',
            __('Vehicle Pricing', 'ckl-car-rental'),
            array(__CLASS__, 'vehicle_pricing_meta_box_html'),
            'vehicle',
            'normal',
            'high'
        );

        // Vehicle WooCommerce sync
        add_meta_box(
            'vehicle_woocommerce_sync',
            __('WooCommerce Sync', 'ckl-car-rental'),
            array(__CLASS__, 'vehicle_woocommerce_sync_meta_box_html'),
            'vehicle',
            'side',
            'default'
        );
    }

    /**
     * Render vehicle details meta box
     */
    public static function vehicle_details_meta_box_html($post) {
        wp_nonce_field('ckl_save_vehicle_details', 'ckl_vehicle_details_nonce');

        $vehicle_type = get_post_meta($post->ID, '_vehicle_type', true);
        $passenger_capacity = get_post_meta($post->ID, '_vehicle_passenger_capacity', true);
        $doors = get_post_meta($post->ID, '_vehicle_doors', true);
        $luggage = get_post_meta($post->ID, '_vehicle_luggage', true);
        $has_air_conditioning = get_post_meta($post->ID, '_vehicle_has_air_conditioning', true);
        $transmission = get_post_meta($post->ID, '_vehicle_transmission', true);
        $fuel_type = get_post_meta($post->ID, '_vehicle_fuel_type', true);
        $plate_number = get_post_meta($post->ID, '_vehicle_plate_number', true);
        $units_available = get_post_meta($post->ID, '_vehicle_units_available', true);

        ?>
        <div class="ckl-vehicle-details">
            <table class="form-table">
                <tr>
                    <th>
                        <label for="vehicle_type"><?php _e('Vehicle Type', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select name="vehicle_type" id="vehicle_type" required>
                            <option value=""><?php _e('Select Type', 'ckl-car-rental'); ?></option>
                            <option value="sedan" <?php selected($vehicle_type, 'sedan'); ?>><?php _e('Sedan', 'ckl-car-rental'); ?></option>
                            <option value="compact" <?php selected($vehicle_type, 'compact'); ?>><?php _e('Compact', 'ckl-car-rental'); ?></option>
                            <option value="mpv" <?php selected($vehicle_type, 'mpv'); ?>><?php _e('MPV', 'ckl-car-rental'); ?></option>
                            <option value="luxury_mpv" <?php selected($vehicle_type, 'luxury_mpv'); ?>><?php _e('Luxury MPV', 'ckl-car-rental'); ?></option>
                            <option value="suv" <?php selected($vehicle_type, 'suv'); ?>><?php _e('SUV', 'ckl-car-rental'); ?></option>
                            <option value="4x4" <?php selected($vehicle_type, '4x4'); ?>><?php _e('4x4', 'ckl-car-rental'); ?></option>
                            <option value="motorcycle" <?php selected($vehicle_type, 'motorcycle'); ?>><?php _e('Motorcycle', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_passenger_capacity"><?php _e('Passenger Capacity', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_passenger_capacity"
                               id="vehicle_passenger_capacity"
                               value="<?php echo esc_attr($passenger_capacity); ?>"
                               min="1"
                               max="50"
                               required>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_doors"><?php _e('Doors', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_doors"
                               id="vehicle_doors"
                               value="<?php echo esc_attr($doors); ?>"
                               min="1"
                               max="10">
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_luggage"><?php _e('Luggage Capacity', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_luggage"
                               id="vehicle_luggage"
                               value="<?php echo esc_attr($luggage); ?>"
                               min="0"
                               max="20">
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_has_air_conditioning"><?php _e('Air Conditioning', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox"
                               name="vehicle_has_air_conditioning"
                               id="vehicle_has_air_conditioning"
                               value="1"
                               <?php checked($has_air_conditioning, '1'); ?>>
                        <label for="vehicle_has_air_conditioning"><?php _e('Yes, this vehicle has air conditioning', 'ckl-car-rental'); ?></label>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_transmission"><?php _e('Transmission', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select name="vehicle_transmission" id="vehicle_transmission">
                            <option value="automatic" <?php selected($transmission, 'automatic'); ?>><?php _e('Automatic', 'ckl-car-rental'); ?></option>
                            <option value="manual" <?php selected($transmission, 'manual'); ?>><?php _e('Manual', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_fuel_type"><?php _e('Fuel Type', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="text"
                               name="vehicle_fuel_type"
                               id="vehicle_fuel_type"
                               value="<?php echo esc_attr($fuel_type); ?>"
                               placeholder="<?php _e('e.g., Petrol, Diesel, Hybrid', 'ckl-car-rental'); ?>">
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_plate_number"><?php _e('Plate Number', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="text"
                               name="vehicle_plate_number"
                               id="vehicle_plate_number"
                               value="<?php echo esc_attr($plate_number); ?>"
                               required>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_units_available"><?php _e('Units Available', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_units_available"
                               id="vehicle_units_available"
                               value="<?php echo esc_attr($units_available); ?>"
                               min="1"
                               required>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    /**
     * Render vehicle pricing meta box
     */
    public static function vehicle_pricing_meta_box_html($post) {
        wp_nonce_field('ckl_save_vehicle_pricing', 'ckl_vehicle_pricing_nonce');

        $price_per_day = get_post_meta($post->ID, '_vehicle_price_per_day', true);
        $late_fee_per_hour = get_post_meta($post->ID, '_vehicle_late_fee_per_hour', true);
        $grace_period_minutes = get_post_meta($post->ID, '_vehicle_grace_period_minutes', true);

        ?>
        <div class="ckl-vehicle-pricing">
            <table class="form-table">
                <tr>
                    <th>
                        <label for="vehicle_price_per_day"><?php _e('Price Per Day (RM)', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_price_per_day"
                               id="vehicle_price_per_day"
                               value="<?php echo esc_attr($price_per_day); ?>"
                               step="0.01"
                               min="0"
                               required>
                        <p class="description"><?php _e('Base rental rate per day', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_late_fee_per_hour"><?php _e('Late Fee Per Hour (RM)', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_late_fee_per_hour"
                               id="vehicle_late_fee_per_hour"
                               value="<?php echo esc_attr($late_fee_per_hour); ?>"
                               step="0.01"
                               min="0">
                        <p class="description"><?php _e('Optional: Charge per hour for late returns. Leave empty to use system default.', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="vehicle_grace_period_minutes"><?php _e('Grace Period (Minutes)', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="vehicle_grace_period_minutes"
                               id="vehicle_grace_period_minutes"
                               value="<?php echo esc_attr($grace_period_minutes); ?>"
                               min="0"
                               step="15">
                        <p class="description"><?php _e('Grace period before late fees are applied. Default: 0 minutes.', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    /**
     * Render WooCommerce sync meta box
     */
    public static function vehicle_woocommerce_sync_meta_box_html($post) {
        $product_id = get_post_meta($post->ID, '_vehicle_woocommerce_product_id', true);

        $last_synced = get_post_meta($post->ID, '_vehicle_last_synced', true);
        $sync_status = get_post_meta($post->ID, '_vehicle_sync_status', true);

        ?>
        <div class="ckl-woocommerce-sync">
            <?php if ($product_id): ?>
                <p>
                    <strong><?php _e('Linked Product:', 'ckl-car-rental'); ?></strong><br>
                    <a href="<?php echo get_edit_post_link($product_id); ?>">
                        #<?php echo $product_id; ?> - <?php echo get_the_title($product_id); ?>
                    </a>
                </p>

                <?php if ($last_synced): ?>
                    <p>
                        <strong><?php _e('Last Synced:', 'ckl-car-rental'); ?></strong><br>
                        <?php echo $last_synced; ?>
                    </p>
                <?php endif; ?>

                <?php if ($sync_status): ?>
                    <p>
                        <strong><?php _e('Status:', 'ckl-car-rental'); ?></strong><br>
                        <span class="<?php echo $sync_status === 'success' ? 'success' : 'error'; ?>">
                            <?php echo $sync_status === 'success' ? '✓ ' . __('Synced', 'ckl-car-rental') : '✗ ' . __('Error', 'ckl-car-rental'); ?>
                        </span>
                    </p>
                <?php endif; ?>

                <p>
                    <button type="button" class="button" id="sync-vehicle-now">
                        <?php _e('Sync Now', 'ckl-car-rental'); ?>
                    </button>
                </p>
            <?php else: ?>
                <p>
                    <?php _e('This vehicle is not yet linked to a WooCommerce product.', 'ckl-car-rental'); ?>
                </p>
                <p>
                    <button type="button" class="button button-primary" id="create-woocommerce-product">
                        <?php _e('Create WooCommerce Product', 'ckl-car-rental'); ?>
                    </button>
                </p>
            <?php endif; ?>

            <script>
            jQuery(document).ready(function($) {
                $('#create-woocommerce-product').on('click', function() {
                    if (!confirm('<?php _e('This will create a new WooCommerce bookable product for this vehicle. Continue?', 'ckl-car-rental'); ?>')) {
                        return;
                    }

                    var button = $(this);
                    button.prop('disabled', true).text('<?php _e('Creating...', 'ckl-car-rental'); ?>');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'ckl_create_woocommerce_product',
                            vehicle_id: <?php echo $post->ID; ?>,
                            nonce: '<?php echo wp_create_nonce('ckl-create-product'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert(response.data.message || '<?php _e('Error creating product', 'ckl-car-rental'); ?>');
                                button.prop('disabled', false).text('<?php _e('Create WooCommerce Product', 'ckl-car-rental'); ?>');
                            }
                        },
                        error: function() {
                            alert('<?php _e('Server error', 'ckl-car-rental'); ?>');
                            button.prop('disabled', false).text('<?php _e('Create WooCommerce Product', 'ckl-car-rental'); ?>');
                        }
                    });
                });

                $('#sync-vehicle-now').on('click', function() {
                    var button = $(this);
                    button.prop('disabled', true).text('<?php _e('Syncing...', 'ckl-car-rental'); ?>');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'ckl_sync_woocommerce_product',
                            vehicle_id: <?php echo $post->ID; ?>,
                            nonce: '<?php echo wp_create_nonce('ckl-sync-product'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert(response.data.message || '<?php _e('Error syncing', 'ckl-car-rental'); ?>');
                                button.prop('disabled', false).text('<?php _e('Sync Now', 'ckl-car-rental'); ?>');
                            }
                        },
                        error: function() {
                            alert('<?php _e('Server error', 'ckl-car-rental'); ?>');
                            button.prop('disabled', false).text('<?php _e('Sync Now', 'ckl-car-rental'); ?>');
                        }
                    });
                });
            });
            </script>
        </div>
        <?php
    }

    /**
     * Save vehicle meta
     */
    public static function save_vehicle_meta($post_id, $post) {
        // Verify nonces
        if (!isset($_POST['ckl_vehicle_details_nonce']) || !wp_verify_nonce($_POST['ckl_vehicle_details_nonce'], 'ckl_save_vehicle_details')) {
            return;
        }

        if (!isset($_POST['ckl_vehicle_pricing_nonce']) || !wp_verify_nonce($_POST['ckl_vehicle_pricing_nonce'], 'ckl_save_vehicle_pricing')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save vehicle details
        $fields = array(
            'vehicle_type',
            'vehicle_passenger_capacity',
            'vehicle_doors',
            'vehicle_luggage',
            'vehicle_transmission',
            'vehicle_fuel_type',
            'vehicle_plate_number',
            'vehicle_units_available',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Save checkbox
        $has_air_conditioning = isset($_POST['vehicle_has_air_conditioning']) ? '1' : '0';
        update_post_meta($post_id, '_vehicle_has_air_conditioning', $has_air_conditioning);

        // Save pricing
        $pricing_fields = array(
            'vehicle_price_per_day',
            'vehicle_late_fee_per_hour',
            'vehicle_grace_period_minutes',
        );

        foreach ($pricing_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Sync to WooCommerce
        self::sync_vehicle_to_woocommerce($post_id);
    }

    /**
     * Sync vehicle to WooCommerce product
     */
    private static function sync_vehicle_to_woocommerce($vehicle_id) {
        $product_id = get_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', true);

        // Create product if it doesn't exist
        if (!$product_id) {
            $product = new WC_Product_Booking();
            $product->set_name(get_the_title($vehicle_id));
            $product->set_status('publish');

            // Set booking duration to 1 day
            $product->set_duration(1);
            $product->set_duration_unit('day');

            // Set base price
            $price = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
            $product->set_regular_price($price);

            $product_id = $product->save();
            update_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', $product_id);
            update_post_meta($product_id, '_vehicle_id', $vehicle_id);

            update_post_meta($vehicle_id, '_vehicle_sync_status', 'success');
            update_post_meta($vehicle_id, '_vehicle_last_synced', current_time('mysql'));
        } else {
            // Update existing product
            $product = wc_get_product($product_id);

            if ($product) {
                $product->set_name(get_the_title($vehicle_id));

                // Update price
                $price = get_post_meta($vehicle_id, '_vehicle_price_per_day', true);
                $product->set_regular_price($price);

                // Update stock/availability
                $units = get_post_meta($vehicle_id, '_vehicle_units_available', true);
                $product->set_stock_quantity($units);

                $product->save();

                update_post_meta($vehicle_id, '_vehicle_sync_status', 'success');
                update_post_meta($vehicle_id, '_vehicle_last_synced', current_time('mysql'));
            }
        }
    }

    /**
     * Add settings page
     */
    public static function add_settings_page() {
        add_submenu_page(
            'woocommerce',
            __('CKL Settings', 'ckl-car-rental'),
            __('CKL Settings', 'ckl-car-rental'),
            'manage_options',
            'ckl-settings',
            array(__CLASS__, 'render_settings_page')
        );
    }

    /**
     * Render settings page
     */
    public static function render_settings_page() {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap">
            <h1><?php _e('CKL Car Rental Settings', 'ckl-car-rental'); ?></h1>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo admin_url('admin.php?page=ckl-settings&tab=general'); ?>"
                   class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('General', 'ckl-car-rental'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=ckl-settings&tab=calendar'); ?>"
                   class="nav-tab <?php echo $active_tab === 'calendar' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Calendar Sync', 'ckl-car-rental'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=ckl-settings&tab=bulk-block'); ?>"
                   class="nav-tab <?php echo $active_tab === 'bulk-block' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Bulk Block Dates', 'ckl-car-rental'); ?>
                </a>
            </h2>

            <div class="ckl-settings-content">
                <?php
                switch ($active_tab) {
                    case 'calendar':
                        CKL_Calendar_Sync::render_calendar_settings();
                        break;
                    case 'bulk-block':
                        self::render_bulk_block_dates_form();
                        break;
                    default:
                        self::render_general_settings();
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render general settings
     */
    private static function render_general_settings() {
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('ckl_general_settings'); ?>

            <table class="form-table">
                <tr>
                    <th>
                        <label for="ckl_default_late_fee_per_hour"><?php _e('Default Late Fee (RM/hour)', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="number"
                               name="ckl_default_late_fee_per_hour"
                               id="ckl_default_late_fee_per_hour"
                               value="<?php echo get_option('ckl_default_late_fee_per_hour', 10); ?>"
                               step="0.01"
                               min="0">
                        <p class="description"><?php _e('Applied if vehicle-specific late fee is not set', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
        <?php
    }

    /**
     * Render bulk block dates form
     */
    private static function render_bulk_block_dates_form() {
        $vehicles = get_posts(array(
            'post_type' => 'vehicle',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));

        ?>
        <div class="ckl-bulk-block-dates">
            <h2><?php _e('Bulk Block Dates', 'ckl-car-rental'); ?></h2>
            <p><?php _e('Block dates for multiple vehicles at once (e.g., for holidays or maintenance).', 'ckl-car-rental'); ?></p>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ckl_bulk_block_dates">
                <?php wp_nonce_field('ckl_bulk_block_dates', 'ckl_bulk_block_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th>
                            <label><?php _e('Select Vehicles', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select name="vehicles[]" multiple size="10" style="width: 400px; height: 200px;" required>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?php echo $vehicle->ID; ?>">
                                        <?php echo esc_html($vehicle->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple vehicles', 'ckl-car-rental'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="bulk_start_date"><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date"
                                   name="start_date"
                                   id="bulk_start_date"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="bulk_end_date"><?php _e('End Date', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <input type="date"
                                   name="end_date"
                                   id="bulk_end_date"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="bulk_reason"><?php _e('Reason', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <select name="reason" id="bulk_reason">
                                <option value="maintenance"><?php _e('Maintenance', 'ckl-car-rental'); ?></option>
                                <option value="holiday"><?php _e('Holiday', 'ckl-car-rental'); ?></option>
                                <option value="personal"><?php _e('Personal Use', 'ckl-car-rental'); ?></option>
                                <option value="reserved"><?php _e('Reserved for VIP', 'ckl-car-rental'); ?></option>
                                <option value="other"><?php _e('Other', 'ckl-car-rental'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="bulk_notes"><?php _e('Notes', 'ckl-car-rental'); ?></label>
                        </th>
                        <td>
                            <textarea name="notes"
                                      id="bulk_notes"
                                      rows="4"
                                      class="large-text"></textarea>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Block Dates', 'ckl-car-rental')); ?>
            </form>
        </div>
        <?php
    }
}

// Initialize admin meta boxes
CKL_Admin_Meta_Boxes::init();
