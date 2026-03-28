<?php
/**
 * Peak Calendar AJAX Handlers
 *
 * AJAX handlers for peak calendar CRUD operations.
 * These must be loaded early to ensure AJAX endpoints are registered.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for saving peak price
 */
function ckl_ajax_save_peak_price() {
    check_ajax_referer('ckl-peak-price-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $editing = isset($_POST['editing']) ? intval($_POST['editing']) : 0;
    $peak_price = isset($_POST['peak_price']) ? $_POST['peak_price'] : array();

    if (empty($peak_price['name']) || empty($peak_price['start_date']) || empty($peak_price['end_date'])) {
        wp_send_json_error(array('message' => __('Missing required fields', 'ckl-car-rental')));
    }

    // Get existing peak prices
    $peak_prices = get_option('ckl_global_peak_prices', array());

    if ($editing) {
        // Update existing
        $index = -1;
        foreach ($peak_prices as $i => $peak) {
            if ($peak['id'] == $peak_price['id']) {
                $index = $i;
                break;
            }
        }

        if ($index >= 0) {
            $peak_prices[$index] = array(
                'id' => intval($peak_price['id']),
                'name' => sanitize_text_field($peak_price['name']),
                'start_date' => sanitize_text_field($peak_price['start_date']),
                'end_date' => sanitize_text_field($peak_price['end_date']),
                'recurring' => sanitize_text_field($peak_price['recurring']),
                'active' => boolval($peak_price['active']),
                'priority' => 100,
                'created_at' => $peak_prices[$index]['created_at'],
                'updated_at' => current_time('mysql')
            );
        }
    } else {
        // Add new
        $peak_prices[] = array(
            'id' => intval($peak_price['id']),
            'name' => sanitize_text_field($peak_price['name']),
            'start_date' => sanitize_text_field($peak_price['start_date']),
            'end_date' => sanitize_text_field($peak_price['end_date']),
            'recurring' => sanitize_text_field($peak_price['recurring']),
            'active' => boolval($peak_price['active']),
            'priority' => 100,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
    }

    // Sort by start date
    usort($peak_prices, function($a, $b) {
        return strtotime($a['start_date']) - strtotime($b['start_date']);
    });

    update_option('ckl_global_peak_prices', $peak_prices);

    wp_send_json_success(array(
        'message' => __('Peak period saved successfully', 'ckl-car-rental'),
        'peak_prices' => $peak_prices
    ));
}
add_action('wp_ajax_ckl_save_peak_price', 'ckl_ajax_save_peak_price');

/**
 * AJAX handler for deleting peak price
 */
function ckl_ajax_delete_peak_price() {
    check_ajax_referer('ckl-peak-price-nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if (!$id) {
        wp_send_json_error(array('message' => __('Invalid peak period ID', 'ckl-car-rental')));
    }

    $peak_prices = get_option('ckl_global_peak_prices', array());

    // Find and remove the peak price
    $found = false;
    foreach ($peak_prices as $i => $peak) {
        if ($peak['id'] == $id) {
            unset($peak_prices[$i]);
            $found = true;
            break;
        }
    }

    if (!$found) {
        wp_send_json_error(array('message' => __('Peak period not found', 'ckl-car-rental')));
    }

    // Re-index array
    $peak_prices = array_values($peak_prices);

    update_option('ckl_global_peak_prices', $peak_prices);

    wp_send_json_success(array(
        'message' => __('Peak period deleted successfully', 'ckl-car-rental')
    ));
}
add_action('wp_ajax_ckl_delete_peak_price', 'ckl_ajax_delete_peak_price');
