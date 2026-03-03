<?php
/**
 * Additional Services Management
 *
 * Handles service meta boxes and configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add service meta boxes
 */
function ckl_add_service_meta_boxes() {
    add_meta_box(
        'service_details',
        __('Service Details', 'ckl-car-rental'),
        'ckl_render_service_details_meta_box',
        'vehicle_service',
        'normal',
        'high'
    );

    add_meta_box(
        'service_categories',
        __('Vehicle Categories', 'ckl-car-rental'),
        'ckl_render_service_categories_meta_box',
        'vehicle_service',
        'side',
        'default'
    );

    add_meta_box(
        'service_pricing',
        __('Pricing', 'ckl-car-rental'),
        'ckl_render_service_pricing_meta_box',
        'vehicle_service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_vehicle_service', 'ckl_add_service_meta_boxes');

/**
 * Render service details meta box
 */
function ckl_render_service_details_meta_box($post) {
    wp_nonce_field('ckl_service_details', 'ckl_service_details_nonce');

    $service_type = get_post_meta($post->ID, '_service_type', true) ?: 'checkbox';
    $description = get_post_meta($post->ID, '_service_description', true);
    $icon_class = get_post_meta($post->ID, '_service_icon', true) ?: 'dashicons-cart';

    ?>
    <table class="form-table">
        <tr>
            <th><label for="service_description"><?php _e('Description', 'ckl-car-rental'); ?></label></th>
            <td>
                <textarea name="service_description" id="service_description" rows="3" class="large-text"><?php echo esc_textarea($description); ?></textarea>
                <p class="description"><?php _e('Short description of the service', 'ckl-car-rental'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="service_type"><?php _e('Service Type', 'ckl-car-rental'); ?></label></th>
            <td>
                <select name="service_type" id="service_type">
                    <option value="checkbox" <?php selected($service_type, 'checkbox'); ?>><?php _e('Checkbox (Yes/No)', 'ckl-car-rental'); ?></option>
                    <option value="quantity" <?php selected($service_type, 'quantity'); ?>><?php _e('Quantity (Number)', 'ckl-car-rental'); ?></option>
                    <option value="dropdown" <?php selected($service_type, 'dropdown'); ?>><?php _e('Dropdown (Options)', 'ckl-car-rental'); ?></option>
                </select>
                <p class="description"><?php _e('How customers select this service', 'ckl-car-rental'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="service_icon"><?php _e('Icon Class', 'ckl-car-rental'); ?></label></th>
            <td>
                <input type="text" name="service_icon" id="service_icon" value="<?php echo esc_attr($icon_class); ?>" class="regular-text" placeholder="dashicons-cart">
                <p class="description">
                    <?php _e('Dashicon class name', 'ckl-car-rental'); ?>
                    <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank"><?php _e('Browse icons', 'ckl-car-rental'); ?></a>
                </p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Render service categories meta box
 */
function ckl_render_service_categories_meta_box($post) {
    wp_nonce_field('ckl_service_categories', 'ckl_service_categories_nonce');

    // Get selected categories for this service
    $selected_categories = get_post_meta($post->ID, '_service_categories', true);
    if (!is_array($selected_categories)) {
        $selected_categories = array();
    }

    // Get all vehicle categories grouped by parent
    $parent_categories = get_terms(array(
        'taxonomy' => 'vehicle_category',
        'hide_empty' => false,
        'parent' => 0
    ));

    $is_global = empty($selected_categories);
    ?>

    <div class="ckl-service-categories-wrapper">
        <p class="description">
            <?php _e('Select which vehicle categories this service applies to. Leave empty for global services (available to all vehicles).', 'ckl-car-rental'); ?>
        </p>

        <label class="ckl-global-service-label">
            <input type="checkbox" name="service_global" value="1" <?php checked($is_global); ?>>
            <?php _e('Apply to All Categories (Global)', 'ckl-car-rental'); ?>
        </label>

        <div id="ckl-service-categories-list" class="ckl-service-categories-list" style="<?php echo $is_global ? 'display: none;' : ''; ?>">
            <?php if (!empty($parent_categories)) : ?>
                <?php foreach ($parent_categories as $parent) : ?>
                    <div class="ckl-category-group">
                        <h4><?php echo esc_html($parent->name); ?></h4>
                        <?php
                        $child_categories = get_terms(array(
                            'taxonomy' => 'vehicle_category',
                            'hide_empty' => false,
                            'parent' => $parent->term_id
                        ));

                        if (!empty($child_categories)) :
                        ?>
                            <div class="ckl-category-children">
                                <?php foreach ($child_categories as $child) : ?>
                                    <label class="ckl-category-checkbox">
                                        <input type="checkbox"
                                               name="service_categories[]"
                                               value="<?php echo esc_attr($child->term_id); ?>"
                                               <?php checked(in_array($child->term_id, $selected_categories)); ?>>
                                        <?php echo esc_html($child->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="description"><?php _e('No subcategories found.', 'ckl-car-rental'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="description">
                    <?php _e('No vehicle categories found.', 'ckl-car-rental'); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .ckl-service-categories-wrapper {
            padding: 10px 0;
        }
        .ckl-global-service-label {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-weight: 600;
        }
        .ckl-service-categories-list {
            margin-top: 15px;
        }
        .ckl-category-group {
            margin-bottom: 15px;
            padding: 10px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .ckl-category-group h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #23282d;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }
        .ckl-category-children {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-left: 0;
        }
        .ckl-category-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            cursor: pointer;
        }
        .ckl-category-checkbox input[type="checkbox"] {
            margin: 0;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Toggle category list when global checkbox changes
        $('input[name="service_global"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#ckl-service-categories-list').slideUp();
                $('#ckl-service-categories-list input[type="checkbox"]').prop('checked', false);
            } else {
                $('#ckl-service-categories-list').slideDown();
            }
        });
    });
    </script>
    <?php
}

/**
 * Render service pricing meta box
 */
function ckl_render_service_pricing_meta_box($post) {
    wp_nonce_field('ckl_service_pricing', 'ckl_service_pricing_nonce');

    $price_per_day = get_post_meta($post->ID, '_service_price_per_day', true);
    $price_per_hour = get_post_meta($post->ID, '_service_price_per_hour', true);
    $price_one_time = get_post_meta($post->ID, '_service_price_one_time', true);
    $pricing_type = get_post_meta($post->ID, '_service_pricing_type', true) ?: 'daily';

    ?>
    <table class="form-table">
        <tr>
            <th><label for="service_pricing_type"><?php _e('Pricing Type', 'ckl-car-rental'); ?></label></th>
            <td>
                <select name="service_pricing_type" id="service_pricing_type">
                    <option value="daily" <?php selected($pricing_type, 'daily'); ?>><?php _e('Per Day', 'ckl-car-rental'); ?></option>
                    <option value="hourly" <?php selected($pricing_type, 'hourly'); ?>><?php _e('Per Hour', 'ckl-car-rental'); ?></option>
                    <option value="one_time" <?php selected($pricing_type, 'one_time'); ?>><?php _e('One-Time Fee', 'ckl-car-rental'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="pricing-field pricing-daily">
            <th><label for="service_price_per_day"><?php _e('Price Per Day (RM)', 'ckl-car-rental'); ?></label></th>
            <td>
                <input type="number" name="service_price_per_day" id="service_price_per_day" value="<?php echo esc_attr($price_per_day); ?>" step="0.01" min="0">
            </td>
        </tr>
        <tr class="pricing-field pricing-hourly">
            <th><label for="service_price_per_hour"><?php _e('Price Per Hour (RM)', 'ckl-car-rental'); ?></label></th>
            <td>
                <input type="number" name="service_price_per_hour" id="service_price_per_hour" value="<?php echo esc_attr($price_per_hour); ?>" step="0.01" min="0">
            </td>
        </tr>
        <tr class="pricing-field pricing-one-time">
            <th><label for="service_price_one_time"><?php _e('One-Time Price (RM)', 'ckl-car-rental'); ?></label></th>
            <td>
                <input type="number" name="service_price_one_time" id="service_price_one_time" value="<?php echo esc_attr($price_one_time); ?>" step="0.01" min="0">
            </td>
        </tr>
    </table>

    <script>
    jQuery(document).ready(function($) {
        function togglePricingFields() {
            var pricingType = $('#service_pricing_type').val();
            $('.pricing-field').hide();
            $('.pricing-' + pricingType).show();
        }

        togglePricingFields();
        $('#service_pricing_type').on('change', togglePricingFields);
    });
    </script>
    <style>
        .pricing-field { display: none; }
        .pricing-field.active { display: table-row; }
    </style>
    <?php
}

/**
 * Save service meta
 */
function ckl_save_service_meta($post_id) {
    // Don't save on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) return;

    // Save details
    if (isset($_POST['ckl_service_details_nonce'])) {
        if (!wp_verify_nonce($_POST['ckl_service_details_nonce'], 'ckl_service_details')) return;

        if (isset($_POST['service_description'])) {
            update_post_meta($post_id, '_service_description', sanitize_textarea_field($_POST['service_description']));
        }
        if (isset($_POST['service_type'])) {
            update_post_meta($post_id, '_service_type', sanitize_text_field($_POST['service_type']));
        }
        if (isset($_POST['service_icon'])) {
            update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
        }
    }

    // Save categories
    if (isset($_POST['ckl_service_categories_nonce'])) {
        if (!wp_verify_nonce($_POST['ckl_service_categories_nonce'], 'ckl_service_categories')) return;

        $is_global = isset($_POST['service_global']) && $_POST['service_global'] === '1';
        $categories = isset($_POST['service_categories']) && is_array($_POST['service_categories'])
            ? array_map('intval', $_POST['service_categories'])
            : array();

        // If global or empty, save empty array
        if ($is_global || empty($categories)) {
            update_post_meta($post_id, '_service_categories', array());
        } else {
            update_post_meta($post_id, '_service_categories', $categories);
        }
    }

    // Save pricing
    if (isset($_POST['ckl_service_pricing_nonce'])) {
        if (!wp_verify_nonce($_POST['ckl_service_pricing_nonce'], 'ckl_service_pricing')) return;

        if (isset($_POST['service_pricing_type'])) {
            update_post_meta($post_id, '_service_pricing_type', sanitize_text_field($_POST['service_pricing_type']));
        }
        if (isset($_POST['service_price_per_day'])) {
            update_post_meta($post_id, '_service_price_per_day', floatval($_POST['service_price_per_day']));
        }
        if (isset($_POST['service_price_per_hour'])) {
            update_post_meta($post_id, '_service_price_per_hour', floatval($_POST['service_price_per_hour']));
        }
        if (isset($_POST['service_price_one_time'])) {
            update_post_meta($post_id, '_service_price_one_time', floatval($_POST['service_price_one_time']));
        }
    }
}
add_action('save_post_vehicle_service', 'ckl_save_service_meta');

/**
 * Create default services on theme activation
 */
function ckl_create_default_services() {
    // Check if services already exist
    $existing_services = get_posts(array(
        'post_type' => 'vehicle_service',
        'posts_per_page' => 1,
        'fields' => 'ids'
    ));

    if (!empty($existing_services)) {
        return; // Services already exist
    }

    $default_services = array(
        array(
            'title' => __('GPS Navigation', 'ckl-car-rental'),
            'description' => __('Portable GPS navigation system with updated maps', 'ckl-car-rental'),
            'icon' => 'dashicons-location',
            'pricing_type' => 'daily',
            'price' => 10.00
        ),
        array(
            'title' => __('Child Seat', 'ckl-car-rental'),
            'description' => __('Safety seat for children (ages 0-4)', 'ckl-car-rental'),
            'icon' => 'dashicons-admin-users',
            'pricing_type' => 'daily',
            'price' => 15.00
        ),
        array(
            'title' => __('Baby Seat', 'ckl-car-rental'),
            'description' => __('Safety seat for infants and toddlers (ages 0-2)', 'ckl-car-rental'),
            'icon' => 'dashicons-admin-users',
            'pricing_type' => 'daily',
            'price' => 15.00
        ),
        array(
            'title' => __('Additional Driver', 'ckl-car-rental'),
            'description' => __('Add an additional driver to the rental agreement (one-time fee)', 'ckl-car-rental'),
            'icon' => 'dashicons-admin-users',
            'pricing_type' => 'one_time',
            'price' => 20.00
        ),
        array(
            'title' => __('Full Insurance', 'ckl-car-rental'),
            'description' => __('Comprehensive insurance coverage with zero excess', 'ckl-car-rental'),
            'icon' => 'dashicons-shield',
            'pricing_type' => 'daily',
            'price' => 30.00
        ),
        array(
            'title' => __('Personal Accident Insurance', 'ckl-car-rental'),
            'description' => __('Personal accident coverage for driver and passengers', 'ckl-car-rental'),
            'icon' => 'dashicons-plus',
            'pricing_type' => 'one_time',
            'price' => 15.00
        )
    );

    foreach ($default_services as $service_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $service_data['title'],
            'post_type' => 'vehicle_service',
            'post_status' => 'publish',
            'post_excerpt' => $service_data['description']
        ));

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_service_description', $service_data['description']);
            update_post_meta($post_id, '_service_type', 'checkbox');
            update_post_meta($post_id, '_service_icon', $service_data['icon']);
            update_post_meta($post_id, '_service_pricing_type', $service_data['pricing_type']);

            if ($service_data['pricing_type'] === 'daily') {
                update_post_meta($post_id, '_service_price_per_day', $service_data['price']);
            } elseif ($service_data['pricing_type'] === 'one_time') {
                update_post_meta($post_id, '_service_price_one_time', $service_data['price']);
            }
        }
    }
}
add_action('after_switch_theme', 'ckl_create_default_services');

/**
 * Helper function to get all active services
 *
 * @param int $vehicle_id Optional vehicle ID to filter services by category
 * @return array Array of service data
 */
function ckl_get_vehicle_services($vehicle_id = null) {
    $services = get_posts(array(
        'post_type' => 'vehicle_service',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));

    // If no vehicle_id provided, return all services (backward compatible)
    if (!$vehicle_id) {
        $service_data = array();
        foreach ($services as $service) {
            $service_data[] = array(
                'id' => $service->ID,
                'title' => $service->post_title,
                'description' => get_post_meta($service->ID, '_service_description', true),
                'type' => get_post_meta($service->ID, '_service_type', true),
                'icon' => get_post_meta($service->ID, '_service_icon', true),
                'pricing_type' => get_post_meta($service->ID, '_service_pricing_type', true),
                'price_per_day' => get_post_meta($service->ID, '_service_price_per_day', true),
                'price_per_hour' => get_post_meta($service->ID, '_service_price_per_hour', true),
                'price_one_time' => get_post_meta($service->ID, '_service_price_one_time', true),
                'categories' => get_post_meta($service->ID, '_service_categories', true),
            );
        }
        return $service_data;
    }

    // Get vehicle categories
    $vehicle_categories = wp_get_object_terms($vehicle_id, 'vehicle_category', array('fields' => 'ids'));

    $service_data = array();
    foreach ($services as $service) {
        $service_categories = get_post_meta($service->ID, '_service_categories', true);

        // Empty array = global service (available to all)
        if (empty($service_categories) || !is_array($service_categories)) {
            $service_data[] = array(
                'id' => $service->ID,
                'title' => $service->post_title,
                'description' => get_post_meta($service->ID, '_service_description', true),
                'type' => get_post_meta($service->ID, '_service_type', true),
                'icon' => get_post_meta($service->ID, '_service_icon', true),
                'pricing_type' => get_post_meta($service->ID, '_service_pricing_type', true),
                'price_per_day' => get_post_meta($service->ID, '_service_price_per_day', true),
                'price_per_hour' => get_post_meta($service->ID, '_service_price_per_hour', true),
                'price_one_time' => get_post_meta($service->ID, '_service_price_one_time', true),
                'categories' => array(),
            );
            continue;
        }

        // Check if vehicle category matches service categories
        if (array_intersect($vehicle_categories, $service_categories)) {
            $service_data[] = array(
                'id' => $service->ID,
                'title' => $service->post_title,
                'description' => get_post_meta($service->ID, '_service_description', true),
                'type' => get_post_meta($service->ID, '_service_type', true),
                'icon' => get_post_meta($service->ID, '_service_icon', true),
                'pricing_type' => get_post_meta($service->ID, '_service_pricing_type', true),
                'price_per_day' => get_post_meta($service->ID, '_service_price_per_day', true),
                'price_per_hour' => get_post_meta($service->ID, '_service_price_per_hour', true),
                'price_one_time' => get_post_meta($service->ID, '_service_price_one_time', true),
                'categories' => $service_categories,
            );
        }
    }

    return $service_data;
}
