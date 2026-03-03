<?php
/**
 * Theme Settings Page
 *
 * Comprehensive theme settings for CK Langkawi Car Rental
 * Includes homepage sections, hero settings, vehicle display, reviews, pricing, and amenities
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme menu item
 * Note: This function is kept for backwards compatibility but the menu is now handled by cklangkawi-menu.php
 */
function ckl_add_theme_menu() {
    // Menu is now handled by cklangkawi-menu.php
    // This function is kept for backwards compatibility
}
// add_action('admin_menu', 'ckl_add_theme_menu'); // Disabled - handled by cklangkawi-menu.php

/**
 * Register theme settings
 */
function ckl_register_settings() {
    // Homepage Sections
    register_setting('ckl_homepage_sections', 'ckl_homepage_sections', array(
        'type' => 'array',
        'default' => ckl_get_default_homepage_sections(),
        'sanitize_callback' => 'ckl_sanitize_homepage_sections'
    ));

    // Hero Settings
    register_setting('ckl_hero_settings', 'ckl_hero_settings', array(
        'type' => 'array',
        'default' => ckl_get_default_hero_settings(),
        'sanitize_callback' => 'ckl_sanitize_hero_settings'
    ));

    // Vehicle Display Settings
    register_setting('ckl_vehicle_display', 'ckl_vehicle_display_settings', array(
        'type' => 'array',
        'default' => ckl_get_default_vehicle_display_settings(),
        'sanitize_callback' => 'ckl_sanitize_vehicle_display_settings'
    ));

    // Global Pricing Settings
    register_setting('ckl_pricing', 'ckl_global_pricing', array(
        'type' => 'array',
        'default' => ckl_get_default_pricing_settings(),
        'sanitize_callback' => 'ckl_sanitize_pricing_settings'
    ));

    // Amenities List
    register_setting('ckl_amenities', 'ckl_amenities_list', array(
        'type' => 'array',
        'default' => ckl_get_default_amenities(),
        'sanitize_callback' => 'ckl_sanitize_amenities_list'
    ));

    // Manual Reviews
    register_setting('ckl_reviews', 'ckl_manual_reviews', array(
        'type' => 'array',
        'default' => array(),
        'sanitize_callback' => 'ckl_sanitize_manual_reviews'
    ));

    // Global Peak Prices
    register_setting('ckl_peak_prices', 'ckl_global_peak_prices', array(
        'type' => 'array',
        'default' => array(),
        'sanitize_callback' => 'ckl_sanitize_peak_prices'
    ));

    // Global Pricing Rules
    register_setting('ckl_pricing_rules', 'ckl_global_pricing_rules', array(
        'type' => 'array',
        'default' => array(),
        'sanitize_callback' => 'ckl_sanitize_global_pricing_rules'
    ));

    // Pricing Rule Templates
    register_setting('ckl_pricing_templates', 'ckl_pricing_rule_templates', array(
        'type' => 'array',
        'default' => array(),
        'sanitize_callback' => 'ckl_sanitize_pricing_templates'
    ));
}
add_action('admin_init', 'ckl_register_settings');

// Note: Helper functions (ckl_get_default_homepage_sections, etc.) are now in functions.php
// to make them available on the frontend

/**
 * Sanitize homepage sections
 */
function ckl_sanitize_homepage_sections($input) {
    $sanitized = array();
    $defaults = ckl_get_default_homepage_sections();

    foreach ($defaults as $section => $config) {
        $sanitized[$section] = array(
            'enabled' => isset($input[$section]['enabled']) ? (bool) $input[$section]['enabled'] : $config['enabled'],
            'order' => isset($input[$section]['order']) ? intval($input[$section]['order']) : $config['order'],
        );
    }

    return $sanitized;
}

/**
 * Sanitize hero settings
 */
function ckl_sanitize_hero_settings($input) {
    $sanitized = array();
    $defaults = ckl_get_default_hero_settings();

    $sanitized['title'] = sanitize_text_field($input['title'] ?? $defaults['title']);
    $sanitized['subtitle'] = sanitize_textarea_field($input['subtitle'] ?? $defaults['subtitle']);
    $sanitized['overlay_opacity'] = intval($input['overlay_opacity'] ?? $defaults['overlay_opacity']);
    $sanitized['show_search_form'] = isset($input['show_search_form']) ? (bool) $input['show_search_form'] : $defaults['show_search_form'];
    $sanitized['search_button_text'] = sanitize_text_field($input['search_button_text'] ?? $defaults['search_button_text']);

    // Sanitize image URLs
    $sanitized['background_images'] = array();
    if (isset($input['background_images']) && is_array($input['background_images'])) {
        foreach ($input['background_images'] as $url) {
            $url = esc_url_raw($url);
            if ($url) {
                $sanitized['background_images'][] = $url;
            }
        }
    }

    return $sanitized;
}

/**
 * Sanitize vehicle display settings
 */
function ckl_sanitize_vehicle_display_settings($input) {
    $sanitized = array();
    $defaults = ckl_get_default_vehicle_display_settings();

    $sanitized['number_of_vehicles'] = intval($input['number_of_vehicles'] ?? $defaults['number_of_vehicles']);
    $sanitized['sort_by'] = in_array($input['sort_by'] ?? '', array('date', 'title', 'price', 'rand')) ? $input['sort_by'] : $defaults['sort_by'];
    $sanitized['sort_order'] = in_array(strtoupper($input['sort_order'] ?? ''), array('ASC', 'DESC')) ? strtoupper($input['sort_order']) : $defaults['sort_order'];
    $sanitized['show_category_tabs'] = isset($input['show_category_tabs']) ? (bool) $input['show_category_tabs'] : $defaults['show_category_tabs'];
    $sanitized['featured_vehicles_only'] = isset($input['featured_vehicles_only']) ? (bool) $input['featured_vehicles_only'] : $defaults['featured_vehicles_only'];
    $sanitized['grid_columns'] = in_array(intval($input['grid_columns'] ?? $defaults['grid_columns']), array(2, 3, 4, 5)) ? intval($input['grid_columns']) : $defaults['grid_columns'];

    return $sanitized;
}

/**
 * Sanitize pricing settings
 */
function ckl_sanitize_pricing_settings($input) {
    $sanitized = array();
    $defaults = ckl_get_default_pricing_settings();

    $sanitized['default_hourly_rate'] = floatval($input['default_hourly_rate'] ?? $defaults['default_hourly_rate']);
    $sanitized['daily_rate_multiplier'] = floatval($input['daily_rate_multiplier'] ?? $defaults['daily_rate_multiplier']);
    $sanitized['weekly_discount'] = intval($input['weekly_discount'] ?? $defaults['weekly_discount']);
    $sanitized['monthly_discount'] = intval($input['monthly_discount'] ?? $defaults['monthly_discount']);

    // Sanitize vehicle type multipliers
    $sanitized['vehicle_type_multipliers'] = array();
    if (isset($input['vehicle_type_multipliers']) && is_array($input['vehicle_type_multipliers'])) {
        foreach ($input['vehicle_type_multipliers'] as $type => $multiplier) {
            $sanitized['vehicle_type_multipliers'][$type] = floatval($multiplier);
        }
    }

    // Sanitize seasonal pricing
    $sanitized['seasonal_pricing'] = array();
    if (isset($input['seasonal_pricing']) && is_array($input['seasonal_pricing'])) {
        foreach ($input['seasonal_pricing'] as $i => $season) {
            if (!empty($season['name']) && !empty($season['start_date']) && !empty($season['end_date']) && isset($season['multiplier'])) {
                $sanitized['seasonal_pricing'][] = array(
                    'name' => sanitize_text_field($season['name']),
                    'start_date' => sanitize_text_field($season['start_date']),
                    'end_date' => sanitize_text_field($season['end_date']),
                    'multiplier' => floatval($season['multiplier']),
                );
            }
        }
    }

    return $sanitized;
}

/**
 * Sanitize amenities list
 */
function ckl_sanitize_amenities_list($input) {
    $sanitized = array();

    if (isset($input) && is_array($input)) {
        foreach ($input as $key => $amenity) {
            // Use the key from the form (allows dynamic keys)
            $amenity_key = isset($amenity['key']) ? sanitize_key($amenity['key']) : sanitize_key($key);

            if (isset($amenity['label']) && !empty($amenity['label'])) {
                $sanitized[$amenity_key] = array(
                    'label' => sanitize_text_field($amenity['label']),
                    'icon' => sanitize_text_field($amenity['icon'] ?? 'dashicons-flag'),
                    'enabled' => isset($amenity['enabled']) ? (bool) $amenity['enabled'] : true,
                    'order' => isset($amenity['order']) ? intval($amenity['order']) : 0,
                );
            }
        }
    }

    return $sanitized;
}

/**
 * Sanitize manual reviews
 */
function ckl_sanitize_manual_reviews($input) {
    $sanitized = array();

    if (isset($input) && is_array($input)) {
        foreach ($input as $i => $review) {
            if (!empty($review['reviewer_name']) && isset($review['rating'])) {
                $sanitized[] = array(
                    'id' => isset($review['id']) ? intval($review['id']) : $i,
                    'vehicle_id' => isset($review['vehicle_id']) ? intval($review['vehicle_id']) : 0,
                    'vehicle_name' => sanitize_text_field($review['vehicle_name'] ?? ''),
                    'reviewer_name' => sanitize_text_field($review['reviewer_name']),
                    'rating' => intval($review['rating']),
                    'review_text' => sanitize_textarea_field($review['review_text'] ?? ''),
                    'date' => sanitize_text_field($review['date'] ?? ''),
                    'country_flag' => sanitize_text_field($review['country_flag'] ?? ''),
                    'featured' => isset($review['featured']) ? (bool) $review['featured'] : false,
                    'order' => isset($review['order']) ? intval($review['order']) : $i,
                );
            }
        }
    }

    // Sort by order
    usort($sanitized, function($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $sanitized;
}

/**
 * Sanitize global peak prices
 */
function ckl_sanitize_peak_prices($input) {
    $sanitized = array();

    if (isset($input) && is_array($input)) {
        foreach ($input as $peak) {
            if (!empty($peak['name']) && !empty($peak['start_date']) && !empty($peak['end_date']) && isset($peak['amount'])) {
                $sanitized[] = array(
                    'id' => isset($peak['id']) ? intval($peak['id']) : uniqid(),
                    'name' => sanitize_text_field($peak['name']),
                    'start_date' => sanitize_text_field($peak['start_date']),
                    'end_date' => sanitize_text_field($peak['end_date']),
                    'adjustment_type' => sanitize_text_field($peak['adjustment_type'] ?? 'percentage'),
                    'amount' => floatval($peak['amount']),
                    'recurring' => sanitize_text_field($peak['recurring'] ?? 'none'),
                    'active' => isset($peak['active']) ? (bool) $peak['active'] : true,
                    'priority' => isset($peak['priority']) ? intval($peak['priority']) : 100,
                    'created_at' => sanitize_text_field($peak['created_at'] ?? current_time('mysql')),
                    'updated_at' => current_time('mysql'),
                );
            }
        }
    }

    return $sanitized;
}

/**
 * Sanitize global pricing rules
 */
function ckl_sanitize_global_pricing_rules($input) {
    $sanitized = array();

    if (isset($input) && is_array($input)) {
        foreach ($input as $rule) {
            if (!empty($rule['name']) && !empty($rule['start_date']) && !empty($rule['end_date']) && isset($rule['amount'])) {
                $sanitized[] = array(
                    'id' => isset($rule['id']) ? intval($rule['id']) : uniqid(),
                    'name' => sanitize_text_field($rule['name']),
                    'start_date' => sanitize_text_field($rule['start_date']),
                    'end_date' => sanitize_text_field($rule['end_date']),
                    'adjustment_type' => sanitize_text_field($rule['adjustment_type'] ?? 'percentage'),
                    'amount' => floatval($rule['amount']),
                    'recurring' => sanitize_text_field($rule['recurring'] ?? 'none'),
                    'priority' => isset($rule['priority']) ? intval($rule['priority']) : 90,
                    'active' => isset($rule['active']) ? (bool) $rule['active'] : true,
                );
            }
        }
    }

    return $sanitized;
}

/**
 * Sanitize pricing templates
 */
function ckl_sanitize_pricing_templates($input) {
    $sanitized = array();

    if (isset($input) && is_array($input)) {
        foreach ($input as $template) {
            if (!empty($template['name'])) {
                $sanitized[] = array(
                    'id' => isset($template['id']) ? sanitize_text_field($template['id']) : uniqid(),
                    'name' => sanitize_text_field($template['name']),
                    'adjustment_type' => sanitize_text_field($template['adjustment_type'] ?? 'percentage'),
                    'amount' => floatval($template['amount'] ?? 0),
                    'recurring' => sanitize_text_field($template['recurring'] ?? 'none'),
                    'description' => sanitize_textarea_field($template['description'] ?? ''),
                );
            }
        }
    }

    return $sanitized;
}

/**
 * Render settings page HTML
 */
function ckl_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings?
    if (isset($_POST['ckl_settings_nonce'])) {
        check_admin_referer('ckl_settings_action', 'ckl_settings_nonce');
        // Settings are auto-saved by WordPress Settings API
        echo '<div class="notice notice-success"><p>' . __('Settings saved.', 'ckl-car-rental') . '</p></div>';
    }

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'homepage';
    ?>
    <div class="wrap">
        <h1><?php _e('CKL Theme Settings', 'ckl-car-rental'); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=ckl-theme-settings&tab=homepage" class="nav-tab <?php echo $active_tab === 'homepage' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Homepage', 'ckl-car-rental'); ?>
            </a>
            <a href="?page=ckl-theme-settings&tab=hero" class="nav-tab <?php echo $active_tab === 'hero' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Hero', 'ckl-car-rental'); ?>
            </a>
            <a href="?page=ckl-theme-settings&tab=vehicles" class="nav-tab <?php echo $active_tab === 'vehicles' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Vehicles', 'ckl-car-rental'); ?>
            </a>
            <a href="?page=ckl-theme-settings&tab=pricing" class="nav-tab <?php echo $active_tab === 'pricing' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Pricing', 'ckl-car-rental'); ?>
            </a>
            <a href="?page=ckl-theme-settings&tab=amenities" class="nav-tab <?php echo $active_tab === 'amenities' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Amenities', 'ckl-car-rental'); ?>
            </a>
            <a href="?page=ckl-theme-settings&tab=reviews" class="nav-tab <?php echo $active_tab === 'reviews' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Reviews', 'ckl-car-rental'); ?>
            </a>
        </h2>

        <form method="post" action="">
            <?php wp_nonce_field('ckl_settings_action', 'ckl_settings_nonce'); ?>

            <?php
            switch ($active_tab) {
                case 'homepage':
                    ckl_render_homepage_tab();
                    break;
                case 'hero':
                    ckl_render_hero_tab();
                    break;
                case 'vehicles':
                    ckl_render_vehicles_tab();
                    break;
                case 'pricing':
                    ckl_render_pricing_tab();
                    break;
                case 'amenities':
                    ckl_render_amenities_tab();
                    break;
                case 'reviews':
                    ckl_render_reviews_tab();
                    break;
            }
            ?>

            <?php submit_button(); ?>
        </form>
    </div>

    <style>
        .ckl-field-group { margin-bottom: 20px; }
        .ckl-field-label { display: block; font-weight: 600; margin-bottom: 5px; }
        .ckl-field-description { font-size: 13px; color: #666; margin-top: 5px; }
        .ckl-repeater-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; background: #f9f9f9; }
        .ckl-repeater-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .ckl-image-preview { max-width: 200px; max-height: 150px; margin-top: 10px; border: 1px solid #ddd; }
        .ckl-tabs { display: flex; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .ckl-tab { padding: 10px 20px; cursor: pointer; border-bottom: 3px solid transparent; }
        .ckl-tab.active { border-bottom-color: #0073aa; }
        .ckl-tab-content { display: none; }
        .ckl-tab-content.active { display: block; }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Image upload handler
        $('.ckl-upload-image').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const inputField = button.prev('input[type="text"]');
            const preview = button.next('.ckl-image-preview');

            const mediaUploader = wp.media({
                title: 'Select Image',
                button: { text: 'Use This Image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                inputField.val(attachment.url);
                if (preview.length) {
                    preview.attr('src', attachment.url).show();
                }
            });

            mediaUploader.open();
        });

        // Repeater add/remove
        $('.ckl-add-repeater').on('click', function() {
            const template = $(this).data('template');
            const index = Date.now();
            let html = template.replace(/\{index\}/g, index);
            $(this).prev('.ckl-repeater-container').append(html);
        });

        $(document).on('click', '.ckl-remove-repeater', function() {
            $(this).closest('.ckl-repeater-item').remove();
        });
    });
    </script>
    <?php
}

/**
 * Render Homepage tab
 */
function ckl_render_homepage_tab() {
    $sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());
    settings_fields('ckl_homepage_sections');
    ?>
    <table class="form-table">
        <?php foreach ($sections as $section => $config) : ?>
            <tr>
                <th scope="row">
                    <?php printf(__('Enable %s Section', 'ckl-car-rental'), ucwords(str_replace('_', ' ', $section))); ?>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="ckl_homepage_sections[<?php echo $section; ?>][enabled]" value="1" <?php checked($config['enabled']); ?>>
                        <?php _e('Show this section on homepage', 'ckl-car-rental'); ?>
                    </label>
                    <input type="number" name="ckl_homepage_sections[<?php echo $section; ?>][order]" value="<?php echo $config['order']; ?>" class="small-text" style="margin-left: 15px;">
                    <?php _e('Order', 'ckl-car-rental'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

/**
 * Render Hero tab
 */
function ckl_render_hero_tab() {
    $settings = get_option('ckl_hero_settings', ckl_get_default_hero_settings());
    settings_fields('ckl_hero_settings');
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><?php _e('Hero Title', 'ckl-car-rental'); ?></th>
            <td>
                <input type="text" name="ckl_hero_settings[title]" value="<?php echo esc_attr($settings['title']); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Hero Subtitle', 'ckl-car-rental'); ?></th>
            <td>
                <textarea name="ckl_hero_settings[subtitle]" rows="3" class="large-text"><?php echo esc_textarea($settings['subtitle']); ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Background Images', 'ckl-car-rental'); ?></th>
            <td>
                <div class="ckl-background-images">
                    <?php foreach ($settings['background_images'] as $i => $url) : ?>
                        <div class="ckl-repeater-item">
                            <div class="ckl-repeater-header">
                                <span><?php printf(__('Image %d', 'ckl-car-rental'), $i + 1); ?></span>
                                <button type="button" class="button ckl-remove-repeater"><?php _e('Remove', 'ckl-car-rental'); ?></button>
                            </div>
                            <input type="text" name="ckl_hero_settings[background_images][<?php echo $i; ?>]" value="<?php echo esc_url($url); ?>" class="regular-text">
                            <?php if ($url) : ?>
                                <img src="<?php echo esc_url($url); ?>" class="ckl-image-preview" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="add-hero-image"><?php _e('Add Background Image', 'ckl-car-rental'); ?></button>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Overlay Opacity', 'ckl-car-rental'); ?></th>
            <td>
                <input type="range" name="ckl_hero_settings[overlay_opacity]" value="<?php echo $settings['overlay_opacity']; ?>" min="0" max="100" style="width: 200px;">
                <span><?php echo $settings['overlay_opacity']; ?>%</span>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Show Search Form', 'ckl-car-rental'); ?></th>
            <td>
                <label>
                    <input type="checkbox" name="ckl_hero_settings[show_search_form]" value="1" <?php checked($settings['show_search_form']); ?>>
                    <?php _e('Display search form in hero section', 'ckl-car-rental'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Search Button Text', 'ckl-car-rental'); ?></th>
            <td>
                <input type="text" name="ckl_hero_settings[search_button_text]" value="<?php echo esc_attr($settings['search_button_text']); ?>" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Render Vehicles tab
 */
function ckl_render_vehicles_tab() {
    $settings = get_option('ckl_vehicle_display_settings', ckl_get_default_vehicle_display_settings());
    settings_fields('ckl_vehicle_display');
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><?php _e('Number of Vehicles', 'ckl-car-rental'); ?></th>
            <td>
                <input type="number" name="ckl_vehicle_display_settings[number_of_vehicles]" value="<?php echo $settings['number_of_vehicles']; ?>" min="1" max="50">
                <p class="description"><?php _e('Number of vehicles to display on homepage', 'ckl-car-rental'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Sort By', 'ckl-car-rental'); ?></th>
            <td>
                <select name="ckl_vehicle_display_settings[sort_by]">
                    <option value="date" <?php selected($settings['sort_by'], 'date'); ?>><?php _e('Date', 'ckl-car-rental'); ?></option>
                    <option value="title" <?php selected($settings['sort_by'], 'title'); ?>><?php _e('Title', 'ckl-car-rental'); ?></option>
                    <option value="price" <?php selected($settings['sort_by'], 'price'); ?>><?php _e('Price', 'ckl-car-rental'); ?></option>
                    <option value="rand" <?php selected($settings['sort_by'], 'rand'); ?>><?php _e('Random', 'ckl-car-rental'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Sort Order', 'ckl-car-rental'); ?></th>
            <td>
                <select name="ckl_vehicle_display_settings[sort_order]">
                    <option value="DESC" <?php selected($settings['sort_order'], 'DESC'); ?>><?php _e('Descending', 'ckl-car-rental'); ?></option>
                    <option value="ASC" <?php selected($settings['sort_order'], 'ASC'); ?>><?php _e('Ascending', 'ckl-car-rental'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Show Category Tabs', 'ckl-car-rental'); ?></th>
            <td>
                <label>
                    <input type="checkbox" name="ckl_vehicle_display_settings[show_category_tabs]" value="1" <?php checked($settings['show_category_tabs']); ?>>
                    <?php _e('Display category tabs (All, Cars, Motorcycles)', 'ckl-car-rental'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Featured Vehicles Only', 'ckl-car-rental'); ?></th>
            <td>
                <label>
                    <input type="checkbox" name="ckl_vehicle_display_settings[featured_vehicles_only]" value="1" <?php checked($settings['featured_vehicles_only']); ?>>
                    <?php _e('Show only featured vehicles', 'ckl-car-rental'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Grid Columns', 'ckl-car-rental'); ?></th>
            <td>
                <select name="ckl_vehicle_display_settings[grid_columns]">
                    <option value="2" <?php selected($settings['grid_columns'], 2); ?>>2</option>
                    <option value="3" <?php selected($settings['grid_columns'], 3); ?>>3</option>
                    <option value="4" <?php selected($settings['grid_columns'], 4); ?>>4</option>
                    <option value="5" <?php selected($settings['grid_columns'], 5); ?>>5</option>
                </select>
                <p class="description"><?php _e('Number of columns in vehicle grid', 'ckl-car-rental'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Render Pricing tab
 */
function ckl_render_pricing_tab() {
    $settings = get_option('ckl_global_pricing', ckl_get_default_pricing_settings());
    settings_fields('ckl_pricing');
    ?>
    <h3><?php _e('Base Rates', 'ckl-car-rental'); ?></h3>
    <table class="form-table">
        <tr>
            <th scope="row"><?php _e('Default Hourly Rate (RM)', 'ckl-car-rental'); ?></th>
            <td>
                <input type="number" name="ckl_global_pricing[default_hourly_rate]" value="<?php echo $settings['default_hourly_rate']; ?>" step="0.01" min="0">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Daily Rate Multiplier', 'ckl-car-rental'); ?></th>
            <td>
                <input type="number" name="ckl_global_pricing[daily_rate_multiplier]" value="<?php echo $settings['daily_rate_multiplier']; ?>" step="0.1" min="1">
                <p class="description"><?php _e('Daily rate = hourly rate × multiplier', 'ckl-car-rental'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Weekly Discount (%)', 'ckl-car-rental'); ?></th>
            <td>
                <input type="number" name="ckl_global_pricing[weekly_discount]" value="<?php echo $settings['weekly_discount']; ?>" min="0" max="100">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Monthly Discount (%)', 'ckl-car-rental'); ?></th>
            <td>
                <input type="number" name="ckl_global_pricing[monthly_discount]" value="<?php echo $settings['monthly_discount']; ?>" min="0" max="100">
            </td>
        </tr>
    </table>

    <h3><?php _e('Vehicle Type Multipliers', 'ckl-car-rental'); ?></h3>
    <table class="form-table">
        <?php foreach ($settings['vehicle_type_multipliers'] as $type => $multiplier) : ?>
            <tr>
                <th scope="row"><?php echo ucwords(str_replace('_', ' ', $type)); ?></th>
                <td>
                    <input type="number" name="ckl_global_pricing[vehicle_type_multipliers][<?php echo $type; ?>]" value="<?php echo $multiplier; ?>" step="0.1" min="0">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3><?php _e('Seasonal Pricing', 'ckl-car-rental'); ?></h3>
    <div class="ckl-repeater-container">
        <?php foreach ($settings['seasonal_pricing'] as $i => $season) : ?>
            <div class="ckl-repeater-item">
                <div class="ckl-repeater-header">
                    <strong><?php echo esc_html($season['name']); ?></strong>
                    <button type="button" class="button ckl-remove-repeater"><?php _e('Remove', 'ckl-car-rental'); ?></button>
                </div>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Name', 'ckl-car-rental'); ?></th>
                        <td><input type="text" name="ckl_global_pricing[seasonal_pricing][<?php echo $i; ?>][name]" value="<?php echo esc_attr($season['name']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><?php _e('Start Date', 'ckl-car-rental'); ?></th>
                        <td><input type="date" name="ckl_global_pricing[seasonal_pricing][<?php echo $i; ?>][start_date]" value="<?php echo esc_attr($season['start_date']); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php _e('End Date', 'ckl-car-rental'); ?></th>
                        <td><input type="date" name="ckl_global_pricing[seasonal_pricing][<?php echo $i; ?>][end_date]" value="<?php echo esc_attr($season['end_date']); ?>"></td>
                    </tr>
                    <tr>
                        <th><?php _e('Multiplier', 'ckl-car-rental'); ?></th>
                        <td><input type="number" name="ckl_global_pricing[seasonal_pricing][<?php echo $i; ?>][multiplier]" value="<?php echo $season['multiplier']; ?>" step="0.1" min="0"></td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="add-seasonal-pricing"><?php _e('Add Seasonal Pricing', 'ckl-car-rental'); ?></button>
    <?php
}

/**
 * Render Amenities tab
 */
function ckl_render_amenities_tab() {
    $amenities = get_option('ckl_amenities_list', ckl_get_default_amenities());
    settings_fields('ckl_amenities');

    // Sort by order
    uasort($amenities, function($a, $b) {
        $order_a = isset($a['order']) ? intval($a['order']) : 999;
        $order_b = isset($b['order']) ? intval($b['order']) : 999;
        return $order_a - $order_b;
    });

    // Default amenities that cannot be deleted
    $default_amenities = array_keys(ckl_get_default_amenities());
    ?>
    <div id="ckl-amenities-container">
        <?php foreach ($amenities as $key => $amenity) :
            $is_default = in_array($key, $default_amenities);
        ?>
            <div class="ckl-repeater-item ckl-amenity-item" data-amenity-key="<?php echo esc_attr($key); ?>">
                <div class="ckl-repeater-header">
                    <span class="ckl-repeater-title">
                        <span class="dashicons <?php echo esc_attr($amenity['icon']); ?>"></span>
                        <?php echo esc_html($amenity['label']); ?>
                    </span>
                    <?php if (!$is_default) : ?>
                        <button type="button" class="button ckl-remove-repeater ckl-remove-amenity">
                            <?php _e('Remove', 'ckl-car-rental'); ?>
                        </button>
                    <?php else : ?>
                        <span class="description"><?php _e('Default', 'ckl-car-rental'); ?></span>
                    <?php endif; ?>
                </div>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Amenity Key', 'ckl-car-rental'); ?></th>
                        <td>
                            <?php if ($is_default) : ?>
                                <input type="text" value="<?php echo esc_attr($key); ?>" class="regular-text" disabled>
                                <input type="hidden" name="ckl_amenities_list[<?php echo $key; ?>][key]" value="<?php echo esc_attr($key); ?>">
                                <p class="description"><?php _e('Default amenity keys cannot be changed', 'ckl-car-rental'); ?></p>
                            <?php else : ?>
                                <input type="text" name="ckl_amenities_list[<?php echo $key; ?>][key]" value="<?php echo esc_attr($key); ?>" class="regular-text ckl-amenity-key-input">
                                <p class="description"><?php _e('Unique identifier (lowercase, underscores only)', 'ckl-car-rental'); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Label', 'ckl-car-rental'); ?></th>
                        <td>
                            <input type="text" name="ckl_amenities_list[<?php echo $key; ?>][label]" value="<?php echo esc_attr($amenity['label']); ?>" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Icon', 'ckl-car-rental'); ?></th>
                        <td>
                            <input type="text" name="ckl_amenities_list[<?php echo $key; ?>][icon]" value="<?php echo esc_attr($amenity['icon']); ?>" class="regular-text ckl-icon-input" placeholder="dashicons-format-audio">
                            <p class="description">
                                <?php _e('Dashicon class (e.g., dashicons-format-audio)', 'ckl-car-rental'); ?>
                                <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank"><?php _e('Browse icons', 'ckl-car-rental'); ?></a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Enabled', 'ckl-car-rental'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ckl_amenities_list[<?php echo $key; ?>][enabled]" value="1" <?php checked($amenity['enabled']); ?>>
                                <?php _e('Show this amenity in vehicle lists', 'ckl-car-rental'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Order', 'ckl-car-rental'); ?></th>
                        <td>
                            <input type="number" name="ckl_amenities_list[<?php echo $key; ?>][order]" value="<?php echo $amenity['order']; ?>" min="0" max="100">
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="ckl-add-amenity">
        <?php _e('+ Add New Amenity', 'ckl-car-rental'); ?>
    </button>

    <script>
    jQuery(document).ready(function($) {
        var amenityIndex = Date.now();

        // Add new amenity
        $('#ckl-add-amenity').on('click', function() {
            var template = `
                <div class="ckl-repeater-item ckl-amenity-item" data-amenity-key="new_${amenityIndex}">
                    <div class="ckl-repeater-header">
                        <span class="ckl-repeater-title">
                            <span class="dashicons dashicons-flag"></span>
                            <?php _e('New Amenity', 'ckl-car-rental'); ?>
                        </span>
                        <button type="button" class="button ckl-remove-repeater ckl-remove-amenity">
                            <?php _e('Remove', 'ckl-car-rental'); ?>
                        </button>
                    </div>
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Amenity Key', 'ckl-car-rental'); ?></th>
                            <td>
                                <input type="text" name="ckl_amenities_list[new_${amenityIndex}][key]" value="new_${amenityIndex}" class="regular-text ckl-amenity-key-input">
                                <p class="description"><?php _e('Unique identifier (lowercase, underscores only)', 'ckl-car-rental'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Label', 'ckl-car-rental'); ?></th>
                            <td>
                                <input type="text" name="ckl_amenities_list[new_${amenityIndex}][label]" value="" class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Icon', 'ckl-car-rental'); ?></th>
                            <td>
                                <input type="text" name="ckl_amenities_list[new_${amenityIndex}][icon]" value="dashicons-flag" class="regular-text ckl-icon-input" placeholder="dashicons-format-audio">
                                <p class="description">
                                    <?php _e('Dashicon class (e.g., dashicons-format-audio)', 'ckl-car-rental'); ?>
                                    <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank"><?php _e('Browse icons', 'ckl-car-rental'); ?></a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Enabled', 'ckl-car-rental'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="ckl_amenities_list[new_${amenityIndex}][enabled]" value="1" checked>
                                    <?php _e('Show this amenity in vehicle lists', 'ckl-car-rental'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Order', 'ckl-car-rental'); ?></th>
                            <td>
                                <input type="number" name="ckl_amenities_list[new_${amenityIndex}][order]" value="99" min="0" max="100">
                            </td>
                        </tr>
                    </table>
                </div>
            `;

            $('#ckl-amenities-container').append(template);
            amenityIndex++;
        });

        // Remove amenity
        $(document).on('click', '.ckl-remove-amenity', function() {
            if (confirm('<?php _e('Are you sure you want to remove this amenity?', 'ckl-car-rental'); ?>')) {
                $(this).closest('.ckl-amenity-item').remove();
            }
        });

        // Update title preview when label changes
        $(document).on('input', '.ckl-amenity-item input[name*="[label]"]', function() {
            var $item = $(this).closest('.ckl-amenity-item');
            var label = $(this).val() || '<?php _e('Amenity', 'ckl-car-rental'); ?>';
            $item.find('.ckl-repeater-title').text(label);
        });

        // Update icon preview when icon changes
        $(document).on('input', '.ckl-amenity-item input[name*="[icon]"]', function() {
            var $item = $(this).closest('.ckl-amenity-item');
            var iconClass = $(this).val() || 'dashicons-flag';
            $item.find('.ckl-repeater-title .dashicons').attr('class', 'dashicons ' + iconClass);
        });
    });
    </script>

    <style>
        .ckl-amenity-item {
            margin-bottom: 15px;
        }
        .ckl-repeater-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        .ckl-repeater-title .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
    </style>
    <?php
}

/**
 * Render Reviews tab
 */
function ckl_render_reviews_tab() {
    $reviews = get_option('ckl_manual_reviews', array());
    settings_fields('ckl_reviews');
    ?>
    <div class="ckl-repeater-container">
        <?php foreach ($reviews as $i => $review) : ?>
            <div class="ckl-repeater-item">
                <div class="ckl-repeater-header">
                    <strong><?php echo esc_html($review['reviewer_name']); ?></strong>
                    <button type="button" class="button ckl-remove-repeater"><?php _e('Remove', 'ckl-car-rental'); ?></button>
                </div>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Vehicle', 'ckl-car-rental'); ?></th>
                        <td>
                            <input type="text" name="ckl_manual_reviews[<?php echo $i; ?>][vehicle_name]" value="<?php echo esc_attr($review['vehicle_name'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Vehicle name or leave blank', 'ckl-car-rental'); ?>">
                            <input type="hidden" name="ckl_manual_reviews[<?php echo $i; ?>][vehicle_id]" value="<?php echo esc_attr($review['vehicle_id'] ?? 0); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Reviewer Name', 'ckl-car-rental'); ?></th>
                        <td><input type="text" name="ckl_manual_reviews[<?php echo $i; ?>][reviewer_name]" value="<?php echo esc_attr($review['reviewer_name']); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><?php _e('Rating', 'ckl-car-rental'); ?></th>
                        <td>
                            <select name="ckl_manual_reviews[<?php echo $i; ?>][rating]">
                                <?php for ($r = 5; $r >= 1; $r--) : ?>
                                    <option value="<?php echo $r; ?>" <?php selected($review['rating'], $r); ?>><?php echo $r; ?> <?php _e('Stars', 'ckl-car-rental'); ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Review Text', 'ckl-car-rental'); ?></th>
                        <td><textarea name="ckl_manual_reviews[<?php echo $i; ?>][review_text]" rows="3" class="large-text"><?php echo esc_textarea($review['review_text'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th><?php _e('Date Display', 'ckl-car-rental'); ?></th>
                        <td><input type="text" name="ckl_manual_reviews[<?php echo $i; ?>][date]" value="<?php echo esc_attr($review['date'] ?? ''); ?>" placeholder="e.g., 3 days ago"></td>
                    </tr>
                    <tr>
                        <th><?php _e('Country Flag', 'ckl-car-rental'); ?></th>
                        <td><input type="text" name="ckl_manual_reviews[<?php echo $i; ?>][country_flag]" value="<?php echo esc_attr($review['country_flag'] ?? ''); ?>" placeholder="e.g., 🇲🇾"></td>
                    </tr>
                    <tr>
                        <th><?php _e('Featured', 'ckl-car-rental'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ckl_manual_reviews[<?php echo $i; ?>][featured]" value="1" <?php checked($review['featured'] ?? false); ?>>
                                <?php _e('Show on homepage', 'ckl-car-rental'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Order', 'ckl-car-rental'); ?></th>
                        <td><input type="number" name="ckl_manual_reviews[<?php echo $i; ?>][order]" value="<?php echo $review['order']; ?>" min="0"></td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="add-review"><?php _e('Add Review', 'ckl-car-rental'); ?></button>
    <?php
}
