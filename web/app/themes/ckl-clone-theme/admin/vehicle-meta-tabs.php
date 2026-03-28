<?php
/**
 * Vehicle Meta Box Tabs
 *
 * Replaces standard meta boxes with a tabbed interface
 * Organizes all vehicle-related fields into logical tabs
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin scripts and styles for vehicle tabs
 */
function ckl_enqueue_vehicle_tabs_scripts($hook) {
    global $post;

    if (($hook === 'post.php' || $hook === 'post-new.php') && isset($post->post_type) && $post->post_type === 'vehicle') {
        // Enqueue styles
        wp_enqueue_style(
            'ckl-vehicle-tabs',
            get_template_directory_uri() . '/admin/assets/vehicle-tabs.css',
            array(),
            '1.0.0'
        );

        // Enqueue scripts
        wp_enqueue_script(
            'ckl-vehicle-tabs',
            get_template_directory_uri() . '/admin/assets/vehicle-tabs.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script
        wp_localize_script('ckl-vehicle-tabs', 'cklVehicleAdmin', array(
            'nonce' => wp_create_nonce('ckl_vehicle_admin'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'ckl_enqueue_vehicle_tabs_scripts');

/**
 * Remove default meta boxes
 */
function ckl_remove_default_vehicle_meta_boxes() {
    remove_meta_box('vehicle_special_pricing', 'vehicle', 'normal');
    remove_meta_box('vehicle_pricing_details', 'vehicle', 'side');
}
add_action('add_meta_boxes_vehicle', 'ckl_remove_default_vehicle_meta_boxes', 99);

/**
 * Add main tabbed meta box
 */
function ckl_add_tabbed_vehicle_meta_box() {
    add_meta_box(
        'vehicle_tabbed_meta',
        __('Vehicle Details', 'ckl-car-rental'),
        'ckl_render_tabbed_vehicle_meta_box',
        'vehicle',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_vehicle', 'ckl_add_tabbed_vehicle_meta_box');

/**
 * Render tabbed meta box
 */
function ckl_render_tabbed_vehicle_meta_box($post) {
    wp_nonce_field('ckl_vehicle_tabs', 'ckl_vehicle_tabs_nonce');

    // Get all vehicle meta
    $meta = ckl_get_vehicle_meta($post->ID);
    $special_pricing = ckl_get_vehicle_special_pricing($post->ID);
    $availability = get_post_meta($post->ID, '_vehicle_availability', true);

    // Get vehicle categories
    $categories = get_terms(array(
        'taxonomy' => 'vehicle_category',
        'hide_empty' => false,
        'parent' => 0
    ));

    // Get selected vehicle category
    $selected_categories = wp_get_object_terms($post->ID, 'vehicle_category', array('fields' => 'ids'));
    $selected_category = !empty($selected_categories) ? $selected_categories[0] : '';

    ?>
    <div class="ckl-tabs-container">
        <!-- Tab Navigation -->
        <div class="ckl-tabs-nav">
            <button type="button" class="ckl-tab-button active" data-tab="tab-basic-info">
                <?php _e('Basic Info', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="ckl-tab-button" data-tab="tab-pricing">
                <?php _e('Pricing', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="ckl-tab-button" data-tab="tab-inventory">
                <?php _e('Inventory', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="ckl-tab-button" data-tab="tab-services">
                <?php _e('Services', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="ckl-tab-button" data-tab="tab-gallery">
                <?php _e('Gallery', 'ckl-car-rental'); ?>
            </button>
            <button type="button" class="ckl-tab-button" data-tab="tab-availability">
                <?php _e('Availability', 'ckl-car-rental'); ?>
            </button>
        </div>

        <!-- Tab 1: Basic Info -->
        <div id="tab-basic-info" class="ckl-tab-content active">
            <table class="form-table">
                <tr>
                    <th><label for="vehicle_category"><?php _e('Vehicle Category', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <?php if (!empty($categories)) : ?>
                            <div class="ckl-vehicle-type-selector">
                                <?php foreach ($categories as $parent_category) : ?>
                                    <div class="ckl-vehicle-type-group">
                                        <div class="ckl-vehicle-type-group-header">
                                            <?php echo esc_html($parent_category->name); ?>
                                        </div>
                                        <div class="ckl-vehicle-type-options">
                                            <?php
                                            $child_categories = get_terms(array(
                                                'taxonomy' => 'vehicle_category',
                                                'hide_empty' => false,
                                                'parent' => $parent_category->term_id
                                            ));

                                            foreach ($child_categories as $child) :
                                                ?>
                                                <label class="ckl-vehicle-type-option">
                                                    <input type="radio" name="vehicle_category" value="<?php echo $child->term_id; ?>" <?php checked($selected_category, $child->term_id); ?>>
                                                    <?php echo esc_html($child->name); ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="description"><?php _e('No vehicle categories found. Please create them first.', 'ckl-car-rental'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_passenger_capacity"><?php _e('Passenger Capacity', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_passenger_capacity" id="vehicle_passenger_capacity"
                               value="<?php echo esc_attr($meta['passenger_capacity']); ?>" min="1" max="20">
                        <p class="description"><?php _e('Number of passengers', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_doors"><?php _e('Doors', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_doors" id="vehicle_doors"
                               value="<?php echo esc_attr($meta['doors']); ?>" min="2" max="6">
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_luggage"><?php _e('Luggage', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_luggage" id="vehicle_luggage"
                               value="<?php echo esc_attr($meta['luggage']); ?>" min="0" max="10">
                        <p class="description"><?php _e('Number of bags', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_transmission"><?php _e('Transmission', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <select name="vehicle_transmission" id="vehicle_transmission">
                            <option value="automatic" <?php selected($meta['transmission'], 'automatic'); ?>><?php _e('Automatic', 'ckl-car-rental'); ?></option>
                            <option value="manual" <?php selected($meta['transmission'], 'manual'); ?>><?php _e('Manual', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_fuel_type"><?php _e('Fuel Type', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <select name="vehicle_fuel_type" id="vehicle_fuel_type">
                            <option value="petrol" <?php selected($meta['fuel_type'], 'petrol'); ?>><?php _e('Petrol', 'ckl-car-rental'); ?></option>
                            <option value="diesel" <?php selected($meta['fuel_type'], 'diesel'); ?>><?php _e('Diesel', 'ckl-car-rental'); ?></option>
                            <option value="electric" <?php selected($meta['fuel_type'], 'electric'); ?>><?php _e('Electric', 'ckl-car-rental'); ?></option>
                            <option value="hybrid" <?php selected($meta['fuel_type'], 'hybrid'); ?>><?php _e('Hybrid', 'ckl-car-rental'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_plate_number"><?php _e('Plate Number', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="text" name="vehicle_plate_number" id="vehicle_plate_number"
                               value="<?php echo esc_attr($meta['plate_number']); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tab 2: Pricing -->
        <div id="tab-pricing" class="ckl-tab-content">
            <table class="form-table">
                <tr>
                    <th><label for="vehicle_price_per_day"><?php _e('Daily Rate (RM)', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_price_per_day" id="vehicle_price_per_day"
                               value="<?php echo esc_attr($meta['price_per_day']); ?>" step="0.01" min="0" data-required>
                        <p class="description"><?php _e('Base daily rental rate', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_price_per_hour"><?php _e('Hourly Rate (RM)', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_price_per_hour" id="vehicle_price_per_hour"
                               value="<?php echo esc_attr($meta['price_per_hour']); ?>" step="0.01" min="0">
                        <p class="description"><?php _e('Rate for partial days (optional)', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_minimum_booking_days"><?php _e('Minimum Booking Days', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_minimum_booking_days" id="vehicle_minimum_booking_days"
                               value="<?php echo esc_attr($meta['minimum_booking_days']); ?>" min="1" max="30">
                    </td>
                </tr>
                <tr>
                    <th><label for="vehicle_late_fee_per_hour"><?php _e('Late Fee (RM/hour)', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_late_fee_per_hour" id="vehicle_late_fee_per_hour"
                               value="<?php echo esc_attr($meta['late_fee_per_hour']); ?>" step="0.01" min="0">
                        <p class="description"><?php _e('Optional late return fee', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
            </table>

            <h3><?php _e('Promotional Pricing', 'ckl-car-rental'); ?></h3>
            <p class="description"><?php _e('Set special discounted prices for specific date ranges (e.g., early bird offers, last-minute deals).', 'ckl-car-rental'); ?></p>

            <div class="ckl-repeater-container" id="special-pricing-container">
                <?php foreach ($special_pricing as $i => $pricing) : ?>
                    <div class="ckl-repeater-item">
                        <div class="ckl-repeater-header">
                            <span class="ckl-repeater-title"><?php echo esc_html($pricing['name']); ?></span>
                            <button type="button" class="button ckl-remove-repeater" data-confirm="<?php _e('Remove this pricing offer?', 'ckl-car-rental'); ?>">
                                <?php _e('Remove', 'ckl-car-rental'); ?>
                            </button>
                        </div>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Offer Name', 'ckl-car-rental'); ?></th>
                                <td><input type="text" name="special_pricing[<?php echo $i; ?>][name]" value="<?php echo esc_attr($pricing['name']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><?php _e('Start Date', 'ckl-car-rental'); ?></th>
                                <td><input type="date" name="special_pricing[<?php echo $i; ?>][start_date]" value="<?php echo esc_attr($pricing['start_date']); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php _e('End Date', 'ckl-car-rental'); ?></th>
                                <td><input type="date" name="special_pricing[<?php echo $i; ?>][end_date]" value="<?php echo esc_attr($pricing['end_date']); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php _e('Special Price (RM/day)', 'ckl-car-rental'); ?></th>
                                <td><input type="number" name="special_pricing[<?php echo $i; ?>][price]" value="<?php echo esc_attr($pricing['price']); ?>" step="0.01" min="0"></td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button ckl-add-repeater" data-template="<?php echo esc_attr(json_encode(array(
                'name' => '',
                'start_date' => '',
                'end_date' => '',
                'price' => ''
            ))); ?>">
                <?php _e('+ Add Promotional Offer', 'ckl-car-rental'); ?>
            </button>

            <h3><?php _e('Peak Period Pricing', 'ckl-car-rental'); ?></h3>
            <p class="description"><?php _e('Set specific pricing for global peak periods. The price you set here will override the base daily rate for these dates.', 'ckl-car-rental'); ?></p>

            <?php
            $global_periods = get_option('ckl_global_peak_prices', array());
            $vehicle_peak_pricing = get_post_meta($post->ID, '_peak_pricing', true);
            if (!is_array($vehicle_peak_pricing)) {
                $vehicle_peak_pricing = array();
            }

            // Build a lookup for existing pricing by global_period_id
            $pricing_by_period = array();
            foreach ($vehicle_peak_pricing as $pricing) {
                if (isset($pricing['global_period_id']) && $pricing['global_period_id']) {
                    $pricing_by_period[$pricing['global_period_id']] = $pricing;
                }
            }
            ?>

            <?php if (!empty($global_periods)): ?>
                <h4><?php _e('Global Peak Periods', 'ckl-car-rental'); ?></h4>
                <table class="form-table">
                    <?php foreach ($global_periods as $period): ?>
                        <?php
                        $period_id = $period['id'];
                        $has_pricing = isset($pricing_by_period[$period_id]);
                        $pricing = $has_pricing ? $pricing_by_period[$period_id] : array();
                        $enabled = isset($pricing['enabled']) ? $pricing['enabled'] : ($period['active'] ? 1 : 0);
                        $peak_price = isset($pricing['peak_price']) ? $pricing['peak_price'] : '';
                        ?>
                        <tr>
                            <th>
                                <label>
                                    <input type="checkbox" name="peak_pricing[<?php echo $period_id; ?>][enabled]" value="1" <?php checked($enabled); ?> class="ckl-peak-enable">
                                    <?php echo esc_html($period['name']); ?>
                                </label>
                                <br>
                                <small class="description">
                                    <?php printf(__('%s to %s', 'ckl-car-rental'), esc_html($period['start_date']), esc_html($period['end_date'])); ?>
                                    <?php if ($period['recurring'] !== 'none'): ?>
                                        <br><?php echo esc_html(ucfirst($period['recurring'])); ?>
                                    <?php endif; ?>
                                </small>
                            </th>
                            <td>
                                <input type="hidden" name="peak_pricing[<?php echo $period_id; ?>][global_period_id]" value="<?php echo $period_id; ?>">
                                <div class="ckl-peak-pricing-fields" style="<?php echo !$enabled ? 'display: none;' : ''; ?>">
                                    <label>
                                        <?php _e('Peak Price (RM/day):', 'ckl-car-rental'); ?>
                                        <input type="number" name="peak_pricing[<?php echo $period_id; ?>][peak_price]" value="<?php echo esc_attr($peak_price); ?>" step="0.01" min="0" class="small-text">
                                        <span class="description"><?php _e('Leave empty to use base daily rate', 'ckl-car-rental'); ?></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="description">
                    <?php _e('No global peak periods defined. Go to CKLANGKAWI > Peak Periods Calendar to create peak periods.', 'ckl-car-rental'); ?>
                </p>
            <?php endif; ?>

            <h4><?php _e('Vehicle-Only Peak Periods', 'ckl-car-rental'); ?></h4>
            <p class="description"><?php _e('Add custom peak periods that only apply to this vehicle.', 'ckl-car-rental'); ?></p>

            <div class="ckl-repeater-container" id="custom-peak-pricing-container">
                <?php foreach ($custom_periods as $i => $pricing): ?>
                    <div class="ckl-repeater-item">
                        <div class="ckl-repeater-header">
                            <span class="ckl-repeater-title"><?php echo esc_html($pricing['name']); ?></span>
                            <button type="button" class="button ckl-remove-repeater" data-confirm="<?php _e('Remove this peak pricing?', 'ckl-car-rental'); ?>">
                                <?php _e('Remove', 'ckl-car-rental'); ?>
                            </button>
                        </div>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Period Name', 'ckl-car-rental'); ?></th>
                                <td><input type="text" name="custom_peak_pricing[<?php echo $i; ?>][name]" value="<?php echo esc_attr($pricing['name']); ?>" class="regular-text" placeholder="<?php _e('e.g., Special Event', 'ckl-car-rental'); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php _e('Start Date', 'ckl-car-rental'); ?></th>
                                <td><input type="date" name="custom_peak_pricing[<?php echo $i; ?>][start_date]" value="<?php echo esc_attr($pricing['start_date']); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php _e('End Date', 'ckl-car-rental'); ?></th>
                                <td><input type="date" name="custom_peak_pricing[<?php echo $i; ?>][end_date]" value="<?php echo esc_attr($pricing['end_date']); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php _e('Adjustment Type', 'ckl-car-rental'); ?></th>
                                <td>
                                    <select name="custom_peak_pricing[<?php echo $i; ?>][adjustment_type]">
                                        <option value="percentage" <?php selected($pricing['adjustment_type'], 'percentage'); ?>><?php _e('Percentage', 'ckl-car-rental'); ?></option>
                                        <option value="fixed" <?php selected($pricing['adjustment_type'], 'fixed'); ?>><?php _e('Fixed Amount (RM)', 'ckl-car-rental'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Amount', 'ckl-car-rental'); ?></th>
                                <td>
                                    <input type="number" name="custom_peak_pricing[<?php echo $i; ?>][amount]" value="<?php echo esc_attr($pricing['amount']); ?>" step="0.01" min="0">
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button ckl-add-repeater" data-template="<?php echo esc_attr(json_encode(array(
                'name' => '',
                'start_date' => '',
                'end_date' => '',
                'adjustment_type' => 'percentage',
                'amount' => ''
            ))); ?>" data-target="custom-peak-pricing-container">
                <?php _e('+ Add Custom Peak Period', 'ckl-car-rental'); ?>
            </button>

            <script>
            jQuery(document).ready(function($) {
                $('.ckl-peak-enable').on('change', function() {
                    var $fields = $(this).closest('td').find('.ckl-peak-pricing-fields');
                    if ($(this).is(':checked')) {
                        $fields.slideDown();
                    } else {
                        $fields.slideUp();
                    }
                });
            });
            </script>
        </div>

        <!-- Tab 3: Inventory -->
        <div id="tab-inventory" class="ckl-tab-content">
            <table class="form-table">
                <tr>
                    <th><label for="vehicle_units_available"><?php _e('Units Available', 'ckl-car-rental'); ?></label></th>
                    <td>
                        <input type="number" name="vehicle_units_available" id="vehicle_units_available"
                               value="<?php echo esc_attr($meta['units_available']); ?>" min="0" max="100">
                        <p class="description"><?php _e('Total number of units in fleet', 'ckl-car-rental'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('WooCommerce Product', 'ckl-car-rental'); ?></th>
                    <td>
                        <?php
                        $wc_product_id = $meta['woocommerce_product_id'];
                        if ($wc_product_id) :
                            $wc_product = wc_get_product($wc_product_id);
                            if ($wc_product) :
                                ?>
                                <p>
                                    <?php printf(__('Linked to: %s', 'ckl-car-rental'), '<a href="' . get_edit_post_link($wc_product_id) . '">' . esc_html($wc_product->get_name()) . '</a>'); ?>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="sync_wc_product" value="1" checked>
                                        <?php _e('Auto-sync with WooCommerce', 'ckl-car-rental'); ?>
                                    </label>
                                </p>
                            <?php else : ?>
                                <p class="description"><?php _e('No WooCommerce product linked. It will be created automatically when you publish.', 'ckl-car-rental'); ?></p>
                            <?php endif; ?>
                        <?php else : ?>
                            <p class="description"><?php _e('WooCommerce product will be created automatically when published.', 'ckl-car-rental'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tab 4: Services -->
        <div id="tab-services" class="ckl-tab-content">
            <?php
            $all_services = ckl_get_vehicle_services();
            $vehicle_services = get_post_meta($post->ID, '_vehicle_services', true);
            if (!is_array($vehicle_services)) {
                $vehicle_services = array();
            }
            ?>

            <?php if (!empty($all_services)) : ?>
                <table class="form-table">
                    <?php foreach ($all_services as $service) :
                        $is_enabled = isset($vehicle_services[$service['id']]['enabled']) ? $vehicle_services[$service['id']]['enabled'] : true;
                        $override_price = isset($vehicle_services[$service['id']]['override_price']) ? $vehicle_services[$service['id']]['override_price'] : false;
                        $service_price = isset($vehicle_services[$service['id']]['price_per_day']) ? $vehicle_services[$service['id']]['price_per_day'] : $service['price_per_day'];
                        ?>
                        <tr>
                            <th>
                                <label>
                                    <input type="checkbox" name="vehicle_services[<?php echo $service['id']; ?>][enabled]" value="1" <?php checked($is_enabled); ?>>
                                    <?php echo esc_html($service['title']); ?>
                                </label>
                                <?php if ($service['description']) : ?>
                                    <p class="description"><?php echo esc_html($service['description']); ?></p>
                                <?php endif; ?>
                            </th>
                            <td>
                                <span class="dashicons <?php echo esc_attr($service['icon']); ?> text-large"></span>
                                <?php if ($service['pricing_type'] === 'daily' && $service['price_per_day']) : ?>
                                    <span class="description">
                                        <?php printf(__('Default: RM %s/day', 'ckl-car-rental'), number_format($service['price_per_day'], 2)); ?>
                                    </span>
                                <?php elseif ($service['pricing_type'] === 'one_time' && $service['price_one_time']) : ?>
                                    <span class="description">
                                        <?php printf(__('Default: RM %s', 'ckl-car-rental'), number_format($service['price_one_time'], 2)); ?>
                                    </span>
                                <?php endif; ?>

                                <?php
                                // Show category indicator
                                $service_categories = isset($service['categories']) ? $service['categories'] : array();
                                if (empty($service_categories)) :
                                ?>
                                    <span class="ckl-service-badge ckl-service-global" title="<?php _e('Available to all vehicles', 'ckl-car-rental'); ?>">
                                        <span class="dashicons dashicons-admin-site"></span>
                                        <?php _e('Global', 'ckl-car-rental'); ?>
                                    </span>
                                <?php else : ?>
                                    <span class="ckl-service-badge ckl-service-category" title="<?php _e('Available for specific categories', 'ckl-car-rental'); ?>">
                                        <span class="dashicons dashicons-category"></span>
                                        <?php _e('Category-Specific', 'ckl-car-rental'); ?>
                                    </span>
                                <?php endif; ?>

                                <div style="margin-top: 10px;">
                                    <label>
                                        <input type="checkbox" name="vehicle_services[<?php echo $service['id']; ?>][override_price]" value="1" <?php checked($override_price); ?>>
                                        <?php _e('Override price for this vehicle:', 'ckl-car-rental'); ?>
                                    </label>
                                    <?php if ($service['pricing_type'] === 'daily') : ?>
                                        <input type="number" name="vehicle_services[<?php echo $service['id']; ?>][price_per_day]" value="<?php echo esc_attr($service_price); ?>" step="0.01" min="0" class="small-text" <?php echo !$override_price ? 'disabled' : ''; ?>>
                                        <?php _e('RM/day', 'ckl-car-rental'); ?>
                                    <?php elseif ($service['pricing_type'] === 'one_time') : ?>
                                        <input type="number" name="vehicle_services[<?php echo $service['id']; ?>][price_one_time]" value="<?php echo esc_attr($service_price); ?>" step="0.01" min="0" class="small-text" <?php echo !$override_price ? 'disabled' : ''; ?>>
                                        <?php _e('RM', 'ckl-car-rental'); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p class="description"><?php _e('Enable services that customers can add to their booking for this vehicle.', 'ckl-car-rental'); ?></p>
            <?php else : ?>
                <p class="description">
                    <?php _e('No services configured. Go to Vehicles > Services to add services.', 'ckl-car-rental'); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Tab 6: Gallery -->
        <div id="tab-gallery" class="ckl-tab-content">
            <?php
            $gallery_images = get_post_meta($post->ID, '_vehicle_gallery', true);
            if (!is_array($gallery_images)) {
                $gallery_images = array();
            }
            $featured_id = get_post_thumbnail_id($post->ID);
            ?>

            <div class="ckl-gallery-container">
                <!-- Featured Image -->
                <div class="ckl-gallery-featured">
                    <h4><?php _e('Featured Image', 'ckl-car-rental'); ?></h4>
                    <div class="ckl-featured-image-wrapper">
                        <?php if ($featured_id) : ?>
                            <?php echo wp_get_attachment_image($featured_id, 'medium', false, array('class' => 'ckl-featured-preview')); ?>
                        <?php else : ?>
                            <div class="ckl-placeholder">
                                <?php _e('No featured image set', 'ckl-car-rental'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button" id="ckl-set-featured">
                        <?php $featured_id ? _e('Change Featured Image', 'ckl-car-rental') : _e('Set Featured Image', 'ckl-car-rental'); ?>
                    </button>
                    <input type="hidden" id="ckl-featured-image-id" value="<?php echo esc_attr($featured_id); ?>">
                </div>

                <!-- Gallery Images -->
                <div class="ckl-gallery-images">
                    <h4><?php _e('Gallery Images', 'ckl-car-rental'); ?></h4>
                    <p class="description"><?php _e('Add multiple images to showcase the vehicle. Drag to reorder.', 'ckl-car-rental'); ?></p>

                    <div class="ckl-gallery-grid" id="ckl-gallery-grid">
                        <?php foreach ($gallery_images as $attachment_id) :
                            $image_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                            if ($image_url) :
                        ?>
                                <div class="ckl-gallery-item" data-attachment-id="<?php echo esc_attr($attachment_id); ?>">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                                    <div class="ckl-gallery-item-actions">
                                        <button type="button" class="button ckl-set-featured-from-gallery" title="<?php _e('Set as Featured', 'ckl-car-rental'); ?>">
                                            <span class="dashicons dashicons-star-filled"></span>
                                        </button>
                                        <button type="button" class="button ckl-remove-gallery-image" title="<?php _e('Remove', 'ckl-car-rental'); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </div>
                                </div>
                        <?php
                            endif;
                        endforeach; ?>
                    </div>

                    <button type="button" class="button" id="ckl-add-gallery-images">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Add Images', 'ckl-car-rental'); ?>
                    </button>

                    <input type="hidden" id="ckl-gallery-ids" name="vehicle_gallery_ids" value="<?php echo esc_attr(implode(',', $gallery_images)); ?>">
                </div>
            </div>

            <style>
                .ckl-gallery-container {
                    display: grid;
                    grid-template-columns: 1fr 2fr;
                    gap: 30px;
                }
                @media (max-width: 1200px) {
                    .ckl-gallery-container {
                        grid-template-columns: 1fr;
                    }
                }
                .ckl-gallery-featured,
                .ckl-gallery-images {
                    padding: 15px;
                    background: #f9f9f9;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }
                .ckl-featured-image-wrapper {
                    margin: 15px 0;
                }
                .ckl-featured-preview {
                    max-width: 100%;
                    height: auto;
                    border: 2px solid #ddd;
                    border-radius: 4px;
                }
                .ckl-placeholder {
                    width: 100%;
                    height: 200px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #fff;
                    border: 2px dashed #ddd;
                    border-radius: 4px;
                    color: #999;
                }
                .ckl-gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                    gap: 10px;
                    margin: 15px 0;
                }
                .ckl-gallery-item {
                    position: relative;
                    border: 2px solid #ddd;
                    border-radius: 4px;
                    overflow: hidden;
                    cursor: move;
                    background: #fff;
                }
                .ckl-gallery-item img {
                    width: 100%;
                    height: 120px;
                    object-fit: cover;
                    display: block;
                }
                .ckl-gallery-item.ui-sortable-helper {
                    opacity: 0.8;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                }
                .ckl-gallery-item-actions {
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    display: flex;
                    gap: 5px;
                }
                .ckl-gallery-item-actions .button {
                    padding: 5px 8px;
                    font-size: 12px;
                    line-height: 1;
                }
                .ckl-gallery-item-actions .dashicons {
                    font-size: 16px;
                    width: 16px;
                    height: 16px;
                }
                .ckl-service-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    padding: 3px 8px;
                    margin-left: 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: 500;
                    text-transform: uppercase;
                }
                .ckl-service-badge .dashicons {
                    font-size: 14px;
                    width: 14px;
                    height: 14px;
                }
                .ckl-service-global {
                    background: #e7f3ed;
                    color: #0073aa;
                }
                .ckl-service-category {
                    background: #fff3cd;
                    color: #856404;
                }
            </style>

            <script>
            jQuery(document).ready(function($) {
                var galleryFrame;

                // Set featured image
                $('#ckl-set-featured').on('click', function(e) {
                    e.preventDefault();

                    if (galleryFrame) {
                        galleryFrame.open();
                        return;
                    }

                    galleryFrame = wp.media({
                        title: '<?php _e('Select Featured Image', 'ckl-car-rental'); ?>',
                        button: {
                            text: '<?php _e('Set Featured Image', 'ckl-car-rental'); ?>'
                        },
                        multiple: false
                    });

                    galleryFrame.on('select', function() {
                        var attachment = galleryFrame.state().get('selection').first().toJSON();

                        // Update featured image
                        $('#ckl-featured-image-id').val(attachment.id);
                        $('.ckl-featured-preview').remove();
                        $('.ckl-featured-image-wrapper').append('<img src="' + attachment.url + '" class="ckl-featured-preview" alt="">');
                        $('#ckl-set-featured').text('<?php _e('Change Featured Image', 'ckl-car-rental'); ?>');

                        // Set as WordPress featured image
                        WPSetThumbnailHTML(attachment.id);
                    });

                    galleryFrame.open();
                });

                // Add gallery images
                $('#ckl-add-gallery-images').on('click', function(e) {
                    e.preventDefault();

                    if (galleryFrame) {
                        galleryFrame.open();
                        return;
                    }

                    galleryFrame = wp.media({
                        title: '<?php _e('Add to Gallery', 'ckl-car-rental'); ?>',
                        button: {
                            text: '<?php _e('Add to Gallery', 'ckl-car-rental'); ?>'
                        },
                        multiple: 'add'
                    });

                    galleryFrame.on('select', function() {
                        var attachments = galleryFrame.state().get('selection').toJSON();

                        attachments.forEach(function(attachment) {
                            var $item = $('<div class="ckl-gallery-item" data-attachment-id="' + attachment.id + '">' +
                                '<img src="' + attachment.sizes.thumbnail.url + '" alt="">' +
                                '<div class="ckl-gallery-item-actions">' +
                                    '<button type="button" class="button ckl-set-featured-from-gallery" title="<?php _e('Set as Featured', 'ckl-car-rental'); ?>"><span class="dashicons dashicons-star-filled"></span></button>' +
                                    '<button type="button" class="button ckl-remove-gallery-image" title="<?php _e('Remove', 'ckl-car-rental'); ?>"><span class="dashicons dashicons-trash"></span></button>' +
                                '</div>' +
                            '</div>');

                            $('#ckl-gallery-grid').append($item);
                        });

                        updateGalleryIds();
                    });

                    galleryFrame.open();
                });

                // Remove gallery image
                $(document).on('click', '.ckl-remove-gallery-image', function(e) {
                    e.preventDefault();
                    $(this).closest('.ckl-gallery-item').remove();
                    updateGalleryIds();
                });

                // Set featured from gallery
                $(document).on('click', '.ckl-set-featured-from-gallery', function(e) {
                    e.preventDefault();
                    var $item = $(this).closest('.ckl-gallery-item');
                    var attachmentId = $item.data('attachment-id');

                    // Update featured image display
                    var attachment = wp.media.attachment(attachmentId);
                    attachment.fetch();

                    attachment.done(function() {
                        $('#ckl-featured-image-id').val(attachmentId);
                        $('.ckl-featured-preview').remove();
                        $('.ckl-featured-image-wrapper').append('<img src="' + attachment.attributes.url + '" class="ckl-featured-preview" alt="">');
                        $('#ckl-set-featured').text('<?php _e('Change Featured Image', 'ckl-car-rental'); ?>');

                        // Set as WordPress featured image
                        WPSetThumbnailHTML(attachmentId);
                    });
                });

                // Make gallery sortable
                $('#ckl-gallery-grid').sortable({
                    items: '.ckl-gallery-item',
                    cursor: 'move',
                    placeholder: 'ckl-gallery-placeholder',
                    update: function() {
                        updateGalleryIds();
                    }
                });

                // Update hidden input with gallery IDs
                function updateGalleryIds() {
                    var ids = [];
                    $('.ckl-gallery-item').each(function() {
                        ids.push($(this).data('attachment-id'));
                    });
                    $('#ckl-gallery-ids').val(ids.join(','));
                }
            });
            </script>
        </div>

        <!-- Tab 7: Availability -->
        <div id="tab-availability" class="ckl-tab-content">
            <div class="ckl-calendar-preview" data-vehicle-id="<?php echo $post->ID; ?>">
                <div class="ckl-calendar-header">
                    <button type="button" class="button ckl-calendar-prev">&larr;</button>
                    <span class="ckl-calendar-month-year"></span>
                    <button type="button" class="button ckl-calendar-next">&rarr;</button>
                </div>
                <div class="ckl-calendar-grid">
                    <!-- Calendar will be populated via JavaScript -->
                </div>
            </div>

            <h3><?php _e('Bulk Update Availability', 'ckl-car-rental'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><?php _e('Date Range', 'ckl-car-rental'); ?></th>
                    <td>
                        <input type="date" name="bulk_availability_start" id="bulk_availability_start">
                        <?php _e('to', 'ckl-car-rental'); ?>
                        <input type="date" name="bulk_availability_end" id="bulk_availability_end">
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Status', 'ckl-car-rental'); ?></th>
                    <td>
                        <label>
                            <input type="radio" name="bulk_availability_status" value="available" checked>
                            <?php _e('Available', 'ckl-car-rental'); ?>
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="radio" name="bulk_availability_status" value="full">
                            <?php _e('Fully Booked', 'ckl-car-rental'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button type="button" class="button" id="bulk-update-availability">
                            <?php _e('Update Availability', 'ckl-car-rental'); ?>
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Save tabbed meta box data
 */
function ckl_save_tabbed_vehicle_meta($post_id) {
    // Don't save on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check nonce
    if (!isset($_POST['ckl_vehicle_tabs_nonce']) || !wp_verify_nonce($_POST['ckl_vehicle_tabs_nonce'], 'ckl_vehicle_tabs')) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) return;

    // Save vehicle category
    if (isset($_POST['vehicle_category'])) {
        $category_id = intval($_POST['vehicle_category']);
        wp_set_object_terms($post_id, $category_id, 'vehicle_category', false);
    }

    // Save basic info
    $basic_fields = array(
        'vehicle_passenger_capacity',
        'vehicle_doors',
        'vehicle_luggage',
        'vehicle_transmission',
        'vehicle_fuel_type',
        'vehicle_plate_number'
    );

    foreach ($basic_fields as $field) {
        if (isset($_POST[$field])) {
            $meta_key = '_' . str_replace('vehicle_', '', $field);
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }

    // Save pricing
    $pricing_fields = array(
        'vehicle_price_per_day',
        'vehicle_price_per_hour',
        'vehicle_minimum_booking_days',
        'vehicle_late_fee_per_hour'
    );

    foreach ($pricing_fields as $field) {
        if (isset($_POST[$field])) {
            $meta_key = '_' . str_replace('vehicle_', '', $field);
            update_post_meta($post_id, $meta_key, floatval($_POST[$field]));
        }
    }

    // Save special pricing
    if (isset($_POST['special_pricing']) && is_array($_POST['special_pricing'])) {
        $sanitized = array();
        foreach ($_POST['special_pricing'] as $pricing) {
            if (!empty($pricing['name']) && !empty($pricing['start_date']) && !empty($pricing['end_date']) && isset($pricing['price'])) {
                $sanitized[] = array(
                    'name' => sanitize_text_field($pricing['name']),
                    'start_date' => sanitize_text_field($pricing['start_date']),
                    'end_date' => sanitize_text_field($pricing['end_date']),
                    'price' => floatval($pricing['price']),
                );
            }
        }
        update_post_meta($post_id, '_special_pricing', $sanitized);
    }

    // Save peak pricing (global period pricing and custom periods)
    $peak_pricing = array();

    // Save global period pricing
    if (isset($_POST['peak_pricing']) && is_array($_POST['peak_pricing'])) {
        foreach ($_POST['peak_pricing'] as $period_id => $pricing_data) {
            // Only save if enabled
            if (isset($pricing_data['enabled']) && $pricing_data['enabled']) {
                $period_pricing = array(
                    'global_period_id' => intval($period_id),
                    'enabled' => true,
                );

                // Save peak price if set
                if (isset($pricing_data['peak_price']) && !empty($pricing_data['peak_price'])) {
                    $period_pricing['peak_price'] = floatval($pricing_data['peak_price']);
                }

                $peak_pricing[] = $period_pricing;
            }
        }
    }

    // Save custom vehicle-only peak periods
    if (isset($_POST['custom_peak_pricing']) && is_array($_POST['custom_peak_pricing'])) {
        foreach ($_POST['custom_peak_pricing'] as $pricing) {
            if (!empty($pricing['name']) && !empty($pricing['start_date']) && !empty($pricing['end_date']) && isset($pricing['amount'])) {
                $peak_pricing[] = array(
                    'name' => sanitize_text_field($pricing['name']),
                    'start_date' => sanitize_text_field($pricing['start_date']),
                    'end_date' => sanitize_text_field($pricing['end_date']),
                    'adjustment_type' => sanitize_text_field($pricing['adjustment_type']),
                    'amount' => floatval($pricing['amount']),
                );
            }
        }
    }

    update_post_meta($post_id, '_peak_pricing', $peak_pricing);

    // Save inventory
    if (isset($_POST['vehicle_units_available'])) {
        update_post_meta($post_id, '_vehicle_units_available', intval($_POST['vehicle_units_available']));
    }

    // Save services
    if (isset($_POST['vehicle_services']) && is_array($_POST['vehicle_services'])) {
        $services = array();
        foreach ($_POST['vehicle_services'] as $service_id => $service_data) {
            $services[$service_id] = array(
                'enabled' => isset($service_data['enabled']),
                'override_price' => isset($service_data['override_price']),
            );

            if (isset($service_data['override_price']) && isset($service_data['price_per_day'])) {
                $services[$service_id]['price_per_day'] = floatval($service_data['price_per_day']);
            }
            if (isset($service_data['override_price']) && isset($service_data['price_one_time'])) {
                $services[$service_id]['price_one_time'] = floatval($service_data['price_one_time']);
            }
        }
        update_post_meta($post_id, '_vehicle_services', $services);
    } else {
        update_post_meta($post_id, '_vehicle_services', array());
    }

    // Save gallery images
    if (isset($_POST['vehicle_gallery_ids'])) {
        $gallery_ids = sanitize_text_field($_POST['vehicle_gallery_ids']);
        if (!empty($gallery_ids)) {
            $ids_array = array_map('intval', explode(',', $gallery_ids));
            update_post_meta($post_id, '_vehicle_gallery', $ids_array);
        } else {
            delete_post_meta($post_id, '_vehicle_gallery');
        }
    }
}
add_action('save_post_vehicle', 'ckl_save_tabbed_vehicle_meta', 20, 1);

/**
 * AJAX: Get calendar availability for admin
 */
function ckl_get_admin_calendar_availability_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_vehicle_admin')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $month = sanitize_text_field($_POST['month'] ?? date('Y-m'));

    if (!$vehicle_id) {
        wp_send_json_error(array('message' => __('Invalid vehicle ID', 'ckl-car-rental')));
    }

    // Get availability data
    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    if (!is_array($availability)) {
        $availability = array();
    }

    // Get all days in the month
    $timestamp = strtotime($month . '-01');
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m', $timestamp), date('Y', $timestamp));
    $today = date('Y-m-d');

    $calendar_data = array();
    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = date('Y-m-d', strtotime(date('Y-m-', $timestamp) . sprintf('%02d', $day)));
        $is_past = strtotime($date) < strtotime($today);

        if ($is_past) {
            $status = 'past';
        } elseif (isset($availability[$date])) {
            $status = $availability[$date]['status'];
        } else {
            $status = 'available';
        }

        $calendar_data[$day] = array(
            'date' => $date,
            'status' => $status
        );
    }

    wp_send_json_success(array(
        'month_name' => date('F Y', $timestamp),
        'days' => $calendar_data,
        'first_day' => date('w', $timestamp)
    ));
}
add_action('wp_ajax_ckl_get_admin_calendar_availability', 'ckl_get_admin_calendar_availability_ajax');

/**
 * AJAX: Bulk update availability
 */
function ckl_bulk_update_availability_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ckl_vehicle_admin')) {
        wp_send_json_error(array('message' => __('Invalid security token', 'ckl-car-rental')));
    }

    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $start_date = sanitize_text_field($_POST['start_date'] ?? '');
    $end_date = sanitize_text_field($_POST['end_date'] ?? '');
    $status = sanitize_text_field($_POST['status'] ?? 'available');

    if (!$vehicle_id || empty($start_date) || empty($end_date)) {
        wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
    }

    // Get existing availability
    $availability = get_post_meta($vehicle_id, '_vehicle_availability', true);
    if (!is_array($availability)) {
        $availability = array();
    }

    // Update availability for date range
    $current = strtotime($start_date);
    $end = strtotime($end_date);

    while ($current <= $end) {
        $date = date('Y-m-d', $current);
        $availability[$date] = array(
            'status' => $status,
            'updated' => current_time('mysql')
        );
        $current = strtotime('+1 day', $current);
    }

    update_post_meta($vehicle_id, '_vehicle_availability', $availability);

    wp_send_json_success(array(
        'message' => __('Availability updated successfully', 'ckl-car-rental')
    ));
}
add_action('wp_ajax_ckl_bulk_update_availability', 'ckl_bulk_update_availability_ajax');
