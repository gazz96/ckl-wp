<?php
/**
 * Default Vehicle Services by Category
 *
 * Defines default services for Cars and Motorcycles
 *
 * @package CKL_Car_Rental
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get default services organized by category type
 *
 * @return array Array of services organized by category type (cars/motorcycles)
 */
function ckl_get_default_services_by_category() {
    return array(
        'cars' => array(
            array(
                'title' => 'GPS Navigation',
                'description' => 'Portable GPS navigator with Malaysia maps',
                'icon' => 'dashicons-location',
                'type' => 'checkbox',
                'pricing_type' => 'daily',
                'price_per_day' => 15.00,
                'categories' => array() // Empty = all car categories
            ),
            array(
                'title' => 'Child Seat',
                'description' => 'Safety seat for children (0-4 years)',
                'icon' => 'dashicons-car',
                'type' => 'quantity',
                'pricing_type' => 'daily',
                'price_per_day' => 10.00,
                'categories' => array('sedan', 'mpv', 'luxury-mpv', 'suv')
            ),
            array(
                'title' => 'Luggage Rack',
                'description' => 'Roof-mounted luggage carrier',
                'icon' => 'dashicons-archive',
                'type' => 'checkbox',
                'pricing_type' => 'daily',
                'price_per_day' => 20.00,
                'categories' => array('suv', '4x4', 'mpv')
            )
        ),
        'motorcycles' => array(
            array(
                'title' => 'Helmet',
                'description' => 'DOT-certified safety helmet',
                'icon' => 'dashicons-shield',
                'type' => 'quantity',
                'pricing_type' => 'one_time',
                'price_one_time' => 5.00,
                'categories' => array('scooter', 'moped', 'sports-bike')
            ),
            array(
                'title' => 'Storage Box',
                'description' => 'Rear-mounted storage container',
                'icon' => 'dashicons-archive',
                'type' => 'checkbox',
                'pricing_type' => 'daily',
                'price_per_day' => 8.00,
                'categories' => array('scooter', 'moped')
            ),
            array(
                'title' => 'Phone Mount',
                'description' => 'Waterproof phone holder with charger',
                'icon' => 'dashicons-smartphone',
                'type' => 'checkbox',
                'pricing_type' => 'one_time',
                'price_one_time' => 3.00,
                'categories' => array('scooter', 'sports-bike')
            )
        )
    );
}

/**
 * Map category slugs to term IDs
 *
 * @param array $category_slugs Array of category slugs
 * @return array Array of term IDs
 */
function ckl_map_category_slugs_to_ids($category_slugs) {
    if (empty($category_slugs)) {
        return array();
    }

    $term_ids = array();
    foreach ($category_slugs as $slug) {
        $term = get_term_by('slug', $slug, 'vehicle_category');
        if ($term && !is_wp_error($term)) {
            $term_ids[] = $term->term_id;
        }
    }

    return $term_ids;
}

/**
 * Initialize default services on theme activation
 */
function ckl_initialize_default_services() {
    $existing_services = get_posts(array(
        'post_type' => 'vehicle_service',
        'posts_per_page' => 1,
        'fields' => 'ids'
    ));

    // Only create if no services exist
    if (!empty($existing_services)) {
        return;
    }

    $defaults = ckl_get_default_services_by_category();

    foreach ($defaults['cars'] as $service_data) {
        ckl_create_service_post($service_data, 'cars');
    }

    foreach ($defaults['motorcycles'] as $service_data) {
        ckl_create_service_post($service_data, 'motorcycles');
    }
}

/**
 * Create a service post from data
 *
 * @param array $data Service data
 * @param string $category_type Category type (cars/motorcycles)
 * @return int|WP_Error Post ID on success, WP_Error on failure
 */
function ckl_create_service_post($data, $category_type) {
    $post_id = wp_insert_post(array(
        'post_title' => $data['title'],
        'post_type' => 'vehicle_service',
        'post_status' => 'publish',
        'post_content' => $data['description'],
        'post_excerpt' => $data['description']
    ));

    if ($post_id && !is_wp_error($post_id)) {
        // Save service details
        update_post_meta($post_id, '_service_description', $data['description']);
        update_post_meta($post_id, '_service_type', $data['type']);
        update_post_meta($post_id, '_service_icon', $data['icon']);
        update_post_meta($post_id, '_service_pricing_type', $data['pricing_type']);

        // Save pricing
        if ($data['pricing_type'] === 'daily') {
            update_post_meta($post_id, '_service_price_per_day', $data['price_per_day']);
        } elseif ($data['pricing_type'] === 'one_time') {
            update_post_meta($post_id, '_service_price_one_time', $data['price_one_time']);
        }

        // Store category associations (map slugs to term IDs)
        $category_ids = ckl_map_category_slugs_to_ids($data['categories']);
        update_post_meta($post_id, '_service_categories', $category_ids);
    }

    return $post_id;
}

/**
 * Check if default services need to be initialized
 *
 * @return bool True if services need initialization
 */
function ckl_needs_service_initialization() {
    $existing_services = get_posts(array(
        'post_type' => 'vehicle_service',
        'posts_per_page' => 1,
        'fields' => 'ids'
    ));

    return empty($existing_services);
}
